<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;

class OrderController extends Controller
{
    // คำนวณยอดรวมในตะกร้าใหม่พร้อมหักส่วนลดจากคูปองทั้งหมดที่ใส่ไว้
    private function recalculateOrderTotal(Order $order)
    {
        $items = OrderItem::where('order_id', $order->id)->get();
        $originalTotal = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (!is_array($appliedCodes)) {
            $appliedCodes = [];
        }

        $currentTotal = $originalTotal;
        $validCodes = [];

        foreach ($appliedCodes as $code) {
            $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
            if ($coupon && (!$coupon->min_purchase || $originalTotal >= $coupon->min_purchase)) {
                if ($coupon->discount_type == 'percentage') {
                    $discount = ($originalTotal * $coupon->discount_value) / 100;
                } else {
                    $discount = $coupon->discount_value;
                }
                $currentTotal = max(0, $currentTotal - $discount);
                $validCodes[] = $code;
            }
        }

        Cache::put("order_coupons_list_{$order->id}", $validCodes, now()->addHours(24));
        $order->total_amount = $currentTotal;
        $order->save();

        return [
            'original_total' => $originalTotal,
            'new_total' => $currentTotal,
            'discount_applied' => max(0, $originalTotal - $currentTotal),
            'applied_coupons' => $validCodes
        ];
    }

    // 1. หยิบคอร์สเรียนใส่ตะกร้า
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        // ตรวจสอบว่าเคยซื้อคอร์สนี้ไปแล้วหรือยัง
        $alreadyPurchased = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists();

        if ($alreadyPurchased) {
            return response()->json([
                'message' => 'คุณได้ซื้อคอร์สเรียนวิชานี้ไปแล้ว สามารถเข้าเรียนได้ที่เมนู "คอร์สของฉัน"'
            ], 400);
        }

        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_amount' => 0]
        );

        $existingItem = OrderItem::where('order_id', $order->id)
                                 ->where('product_id', $product->id)
                                 ->first();

        if ($existingItem) {
            return response()->json([
                'message' => 'คอร์สเรียนนี้อยู่ในตะกร้าของคุณเรียบร้อยแล้ว'
            ], 400);
        }

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ]);

        $summary = $this->recalculateOrderTotal($order);

        return response()->json([
            'message' => 'เพิ่มคอร์สเรียนลงตะกร้าสำเร็จ!',
            'cart_total' => $summary['new_total']
        ]);
    }

    // 2. ลบคอร์สเรียนออกจากตะกร้า (DELETE /api/cart/{itemId})
    public function removeFromCart(Request $request, $itemId)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order) {
            return response()->json(['message' => 'ไม่พบตะกร้าสินค้า'], 404);
        }

        $item = OrderItem::where('order_id', $order->id)->where('id', $itemId)->first();
        if (!$item) {
            return response()->json(['message' => 'ไม่พบรายการคอร์สในตะกร้า'], 404);
        }

        $item->delete();

        $remainingCount = OrderItem::where('order_id', $order->id)->count();
        if ($remainingCount === 0) {
            Cache::forget("order_coupons_list_{$order->id}");
            $order->total_amount = 0;
            $order->save();
        } else {
            $this->recalculateOrderTotal($order);
        }

        return response()->json([
            'message' => 'ลบคอร์สเรียนออกจากตะกร้าเรียบร้อยแล้ว'
        ]);
    }

    // 3. ดูรายการคอร์สในตะกร้า (ดึงทั้ง images และ variants เพื่อแสดงรูปปกและชื่อติวเตอร์)
    public function viewCart(Request $request)
    {
        $user = $request->user();
        $cart = Order::with(['items.product.images', 'items.product.variants', 'items.product.category'])
                     ->where('user_id', $user->id)
                     ->where('status', 'pending')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'ตะกร้าสินค้าว่างเปล่า',
                'cart' => null,
                'original_total' => 0,
                'discount_applied' => 0,
                'applied_coupons' => []
            ]);
        }

        $summary = $this->recalculateOrderTotal($cart);
        $cart->refresh();

        return response()->json([
            'cart' => $cart->load(['items.product.images', 'items.product.variants', 'items.product.category']),
            'original_total' => $summary['original_total'],
            'discount_applied' => $summary['discount_applied'],
            'applied_coupons' => $summary['applied_coupons']
        ]);
    }

    // 4. ใช้งานคูปองส่วนลด (กรอกโค้ดที่ 2 ต่อได้ แต่ห้ามใช้โค้ดตัวเดิมซ้ำ)
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper(trim($request->code));

        $user = $request->user();
        $order = Order::with('items')->where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order || $order->items->isEmpty()) {
            return response()->json(['message' => 'ไม่มีคอร์สเรียนในตะกร้า'], 404);
        }

        $originalTotal = $order->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // ดึงรายการโค้ดที่ใช้ไปแล้วในตะกร้านี้
        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (!is_array($appliedCodes)) {
            $appliedCodes = [];
        }

        // ป้องกันการกดใช้โค้ดตัวเดิมซ้ำในตะกร้าเดียวกัน
        if (in_array($code, $appliedCodes)) {
            return response()->json([
                'message' => "คุณได้ใช้โค้ดส่วนลด {$code} ในคำสั่งซื้อนี้ไปแล้ว (สามารถกรอกโค้ดส่วนลดรหัสอื่นเพิ่มได้)"
            ], 400);
        }

        // ป้องกันการใช้โค้ดตัวเดิมซ้ำจากออเดอร์ในอดีต
        $userCouponKey = "user_{$user->id}_used_coupon_{$code}";
        if (Cache::has($userCouponKey)) {
            return response()->json([
                'message' => "บัญชีของคุณเคยใช้สิทธิ์โค้ดส่วนลด {$code} ในการสั่งซื้อครั้งก่อนไปแล้ว"
            ], 400);
        }

        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['message' => 'โค้ดส่วนลดไม่ถูกต้องหรือหมดอายุแล้ว'], 400);
        }

        if ($coupon->min_purchase && $originalTotal < $coupon->min_purchase) {
            return response()->json([
                'message' => 'คูปองนี้ต้องมียอดสั่งซื้อรวมขั้นต่ำ ' . number_format($coupon->min_purchase) . ' บาท'
            ], 400);
        }

        // เพิ่มโค้ดใหม่ต่อท้ายรายการโค้ดที่ใช้แล้ว และคำนวณราคารวมใหม่
        $appliedCodes[] = $code;
        Cache::put("order_coupons_list_{$order->id}", $appliedCodes, now()->addHours(24));

        $summary = $this->recalculateOrderTotal($order);

        return response()->json([
            'message' => "ใช้โค้ดส่วนลด {$code} สำเร็จ! (ใช้แล้วรวม " . count($summary['applied_coupons']) . " โค้ด)",
            'applied_coupons' => $summary['applied_coupons'],
            'original_total' => $summary['original_total'],
            'discount_applied' => $summary['discount_applied'],
            'new_total' => $summary['new_total']
        ]);
    }

    // 5. ยืนยันคำสั่งซื้อและชำระเงิน
    public function checkout(Request $request)
    {
        $user = $request->user();
        $order = Order::with('items')->where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order || $order->items->isEmpty()) {
            return response()->json(['message' => 'ไม่สามารถทำรายการได้ ตะกร้าว่างเปล่า'], 400);
        }

        // บันทึกประวัติว่าผู้ใช้นี้ได้ใช้โค้ดเหล่านี้ไปแล้วเมื่อชำระเงินสำเร็จ
        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (is_array($appliedCodes)) {
            foreach ($appliedCodes as $code) {
                Cache::put("user_{$user->id}_used_coupon_{$code}", true, now()->addMonths(6));
            }
        }

        $order->status = 'paid';
        $order->save();
        Cache::forget("order_coupons_list_{$order->id}");

        return response()->json([
            'message' => 'ชำระเงินสำเร็จ! คอร์สเรียนถูกเพิ่มไปยังเมนู "คอร์สของฉัน" เรียบร้อยแล้ว',
            'order_id' => $order->id
        ]);
    }

    // 6. ดึงรายการคอร์สที่ผู้ใช้ซื้อแล้ว
    public function myCourses(Request $request)
    {
        $user = $request->user();

        $paidOrders = Order::with(['items.product.category', 'items.product.images', 'items.product.variants'])
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest()
            ->get();

        $purchasedCourses = [];
        $seenIds = [];

        foreach ($paidOrders as $order) {
            foreach ($order->items as $item) {
                if ($item->product && !in_array($item->product->id, $seenIds)) {
                    $seenIds[] = $item->product->id;
                    $courseData = $item->product->toArray();
                    $courseData['purchased_at'] = $order->updated_at->format('d/m/Y');
                    $courseData['order_id'] = $order->id;
                    $purchasedCourses[] = $courseData;
                }
            }
        }

        return response()->json([
            'purchased_courses' => $purchasedCourses,
            'purchased_ids' => $seenIds
        ]);
    }
}