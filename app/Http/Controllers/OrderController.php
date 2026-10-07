<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    // 1. ฟังก์ชันหยิบสินค้าใส่ตะกร้า
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $product = Product::find($request->product_id);

        // หาตะกร้าปัจจุบัน (Order ที่สถานะ pending) ถ้าไม่มีให้สร้างใหม่
        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_amount' => 0]
        );

        // เพิ่มสินค้าลงในตะกร้า (หรืออัปเดตจำนวนถ้ามีอยู่แล้ว)
        $orderItem = OrderItem::where('order_id', $order->id)
                              ->where('product_id', $product->id)->first();

        if ($orderItem) {
            $orderItem->quantity += $request->quantity;
            $orderItem->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price // เก็บราคา ณ วันที่ซื้อ
            ]);
        }

        // อัปเดตยอดรวมทั้งหมดของตะกร้า
        $order->total_amount = OrderItem::where('order_id', $order->id)
            ->get()->sum(function($item) {
                return $item->quantity * $item->price;
            });
        $order->save();

        return response()->json(['message' => 'เพิ่มสินค้าลงตะกร้าสำเร็จ!', 'cart_total' => $order->total_amount]);
    }

    // 2. ฟังก์ชันดูข้อมูลในตะกร้า (Cart Page)
    public function viewCart(Request $request)
    {
        $user = $request->user();
        $cart = Order::with('items.product')
                     ->where('user_id', $user->id)
                     ->where('status', 'pending')->first();

        if (!$cart) {
            return response()->json(['message' => 'ตะกร้าสินค้าว่างเปล่า']);
        }

        return response()->json(['cart' => $cart]);
    }

    // 3. ฟังก์ชันใช้งานคูปองส่วนลด (Promotion Coupons)
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();
        
        if (!$order) {
            return response()->json(['message' => 'ไม่มีสินค้าในตะกร้า'], 404);
        }

        // ค้นหาคูปอง (สมมติว่าเรามีการสร้างข้อมูลคูปองไว้ใน DB แล้ว)
        $coupon = \App\Models\Coupon::where('code', $request->code)->where('is_active', true)->first();
        
        if (!$coupon) {
            return response()->json(['message' => 'คูปองไม่ถูกต้องหรือหมดอายุแล้ว'], 400);
        }

        // คำนวณส่วนลดตามประเภท (percentage หรือ fixed)
        $discount = 0;
        if ($coupon->discount_type == 'percentage') {
            $discount = ($order->total_amount * $coupon->discount_value) / 100;
        } else {
            $discount = $coupon->discount_value;
        }

        $order->total_amount -= $discount;
        $order->save();

        return response()->json([
            'message' => 'ใช้งานคูปองสำเร็จ!',
            'discount_applied' => $discount,
            'new_total' => $order->total_amount
        ]);
    }

    // 4. ฟังก์ชันยืนยันคำสั่งซื้อ (Checkout)
    public function checkout(Request $request)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$order || $order->total_amount == 0) {
            return response()->json(['message' => 'ไม่สามารถทำรายการได้ ตะกร้าสินค้าว่างเปล่า'], 400);
        }

        // เปลี่ยนสถานะเป็นชำระเงินแล้ว
        $order->status = 'paid';
        $order->save();

        return response()->json([
            'message' => 'ยืนยันคำสั่งซื้อและชำระเงินสำเร็จ!',
            'order_id' => $order->id
        ]);
    }
}