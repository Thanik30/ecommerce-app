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
}