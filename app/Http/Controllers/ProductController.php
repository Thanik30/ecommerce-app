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
        // ตัวอย่างการดึงสินค้าทั้งหมด (หน้า Home)
        $products = Product::with(['images', 'variants'])->get(); 

        // ตัวอย่างการดึงรายละเอียดสินค้า 1 ชิ้น (หน้า Read more)
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        
        // ส่งข้อมูลกลับไปเป็นรูปแบบ JSON
        return response()->json([
            'message' => 'ดึงข้อมูลสินค้าสำเร็จ',
            'data' => $products
        ]);
    }
}