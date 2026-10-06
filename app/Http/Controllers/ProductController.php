<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // ต้อง import Model Product เข้ามาด้วย

class ProductController extends Controller
{
    // ฟังก์ชันสำหรับดึงสินค้าทั้งหมด (ใช้กับหน้า Products Page)
    public function index()
    {
        // ดึงข้อมูลสินค้าทั้งหมด และพ่วงข้อมูลหมวดหมู่ (category) มาด้วย
        $products = Product::with('category')->get();
        
        // ส่งข้อมูลกลับไปเป็นรูปแบบ JSON
        return response()->json([
            'message' => 'ดึงข้อมูลสินค้าสำเร็จ',
            'data' => $products
        ]);
    }
}