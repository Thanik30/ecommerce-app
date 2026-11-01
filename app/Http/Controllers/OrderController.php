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
    private function recalculateOrderTotal(Order $order)
    {
        $items = OrderItem::with('product')->where('order_id', $order->id)->get();
        $originalTotal = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (!is_array($appliedCodes)) $appliedCodes = [];

        $currentTotal = $originalTotal;
        $validCodes = [];

        foreach ($appliedCodes as $code) {
            $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
            if ($coupon) {
                $eligibleTotal = 0;
                foreach ($items as $item) {
                    if ($coupon->owner_email) {
                        if ($item->product && $item->product->owner_email === $coupon->owner_email) {
                            $eligibleTotal += ($item->quantity * $item->price);
                        }
                    } else {
                        $eligibleTotal += ($item->quantity * $item->price);
                    }
                }

                if ($eligibleTotal > 0 && (!$coupon->min_purchase || $originalTotal >= $coupon->min_purchase)) {
                    if ($coupon->discount_type == 'percentage') {
                        $discount = ($eligibleTotal * $coupon->discount_value) / 100;
                    } else {
                        $discount = $coupon->discount_value;
                    }
                    $currentTotal = max(0, $currentTotal - $discount);
                    $validCodes[] = $code;
                }
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

    public function addToCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);
        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        $alreadyPurchased = Order::where('user_id', $user->id)->where('status', 'paid')
            ->whereHas('items', function ($query) use ($product) { $query->where('product_id', $product->id); })->exists();

        if ($alreadyPurchased) return response()->json(['message' => 'คุณได้ซื้อคอร์สเรียนวิชานี้ไปแล้ว'], 400);

        $order = Order::firstOrCreate(['user_id' => $user->id, 'status' => 'pending'], ['total_amount' => 0]);
        $existingItem = OrderItem::where('order_id', $order->id)->where('product_id', $product->id)->first();

        if ($existingItem) return response()->json(['message' => 'คอร์สเรียนนี้อยู่ในตะกร้าของคุณเรียบร้อยแล้ว'], 400);

        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 1, 'price' => $product->price]);
        $summary = $this->recalculateOrderTotal($order);

        return response()->json(['message' => 'เพิ่มลงตะกร้าสำเร็จ!', 'cart_total' => $summary['new_total']]);
    }

    public function removeFromCart(Request $request, $itemId)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();
        if (!$order) return response()->json(['message' => 'ไม่พบตะกร้าสินค้า'], 404);

        $item = OrderItem::where('order_id', $order->id)->where('id', $itemId)->first();
        if (!$item) return response()->json(['message' => 'ไม่พบรายการคอร์ส'], 404);

        $item->delete();
        $remainingCount = OrderItem::where('order_id', $order->id)->count();
        if ($remainingCount === 0) {
            Cache::forget("order_coupons_list_{$order->id}");
            $order->total_amount = 0;
            $order->save();
        } else {
            $this->recalculateOrderTotal($order);
        }
        return response()->json(['message' => 'ลบออกจากตะกร้าเรียบร้อยแล้ว']);
    }

    public function viewCart(Request $request)
    {
        $user = $request->user();
        // ดึง lessons มาด้วยเพื่อเตรียมพร้อม
        $cart = Order::with(['items.product.images', 'items.product.variants', 'items.product.category', 'items.product.lessons'])
                     ->where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'ตะกร้าว่างเปล่า', 'cart' => null, 'original_total' => 0, 'discount_applied' => 0, 'applied_coupons' => []]);
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

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper(trim($request->code));
        $user = $request->user();
        $order = Order::with('items.product')->where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order || $order->items->isEmpty()) return response()->json(['message' => 'ไม่มีคอร์สเรียนในตะกร้า'], 404);

        $originalTotal = $order->items->sum(function ($item) { return $item->quantity * $item->price; });
        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (!is_array($appliedCodes)) $appliedCodes = [];

        if (in_array($code, $appliedCodes)) return response()->json(['message' => "คุณใช้โค้ด {$code} ไปแล้ว"], 400);
        if (Cache::has("user_{$user->id}_used_coupon_{$code}")) return response()->json(['message' => "คุณเคยใช้สิทธิ์โค้ดนี้ไปแล้ว"], 400);

        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
        if (!$coupon) return response()->json(['message' => 'โค้ดไม่ถูกต้องหรือหมดอายุ'], 400);

        $eligibleTotal = 0;
        foreach ($order->items as $item) {
            if ($coupon->owner_email) {
                if ($item->product && $item->product->owner_email === $coupon->owner_email) $eligibleTotal += ($item->quantity * $item->price);
            } else {
                $eligibleTotal += ($item->quantity * $item->price);
            }
        }

        if ($eligibleTotal == 0) return response()->json(['message' => 'โค้ดนี้ใช้ได้เฉพาะคอร์สของเจ้าของโค้ดเท่านั้น'], 400);
        if ($coupon->min_purchase && $originalTotal < $coupon->min_purchase) return response()->json(['message' => 'ยอดสั่งซื้อไม่ถึงเกณฑ์'], 400);

        $appliedCodes[] = $code;
        Cache::put("order_coupons_list_{$order->id}", $appliedCodes, now()->addHours(24));
        $summary = $this->recalculateOrderTotal($order);

        return response()->json([
            'message' => "ใช้โค้ด {$code} สำเร็จ!",
            'applied_coupons' => $summary['applied_coupons'],
            'original_total' => $summary['original_total'],
            'discount_applied' => $summary['discount_applied'],
            'new_total' => $summary['new_total']
        ]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $order = Order::with('items')->where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order || $order->items->isEmpty()) return response()->json(['message' => 'ตะกร้าว่างเปล่า'], 400);

        $appliedCodes = Cache::get("order_coupons_list_{$order->id}", []);
        if (is_array($appliedCodes)) {
            foreach ($appliedCodes as $code) Cache::put("user_{$user->id}_used_coupon_{$code}", true, now()->addMonths(6));
        }

        $order->status = 'paid';
        $order->save();
        Cache::forget("order_coupons_list_{$order->id}");

        return response()->json(['message' => 'ชำระเงินสำเร็จ!', 'order_id' => $order->id]);
    }

    public function myCourses(Request $request)
    {
        $user = $request->user();
        
        // จุดสำคัญ: ต้องมี 'items.product.lessons' อยู่ใน with() เพื่อส่งรายชื่อคลิปให้ผู้เรียน
        $paidOrders = Order::with(['items.product.category', 'items.product.images', 'items.product.variants', 'items.product.lessons'])
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