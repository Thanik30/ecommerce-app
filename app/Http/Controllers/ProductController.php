<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images', 'variants', 'reviews.user'])
            ->latest()
            ->get();

        $categories = Category::all();

        return response()->json([
            'message' => 'ดึงข้อมูลคอร์สเรียนสำเร็จ',
            'data' => $products,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'tutor') {
            return response()->json([
                'message' => 'เฉพาะผู้ที่ผ่านการสมัครและยืนยันตัวตนเป็นติวเตอร์แล้วเท่านั้น จึงจะสามารถลงขายคอร์สเรียนได้'
            ], 403);
        }

        $request->validate([
            'sku' => 'required|digits:6',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'hours_number' => 'required|numeric|min:0.5|max:500',
            'duration' => 'required|string|max:50',
            'suitable_for' => 'required|string',
            'description' => 'required|string',
            'syllabus' => 'required|string',
            'pdf_url' => 'required|url',
            'cover_image' => 'required|image|max:5120',
        ]);

        // จับคู่หมวดหมู่สาขาวิชาอัตโนมัติจากรหัสวิชา 3 หลักแรก
        $sku = trim($request->sku);
        $prefix = substr($sku, 0, 3);
        $category = Category::where('name', 'like', "%({$prefix}xxx)%")->first();
        if (!$category) {
            $category = Category::first();
        }

        // บันทึกรูปปกที่อัปโหลด
        $imageUrl = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=900&q=80';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'course_' . $sku . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/courses');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $imageUrl = '/uploads/courses/' . $filename;
        }

        // ดึงชื่อนามแฝงและสาขาวิชาจากโปรไฟล์ติวเตอร์โดยตรง
        $userMeta = Cache::get("user_meta_{$user->id}", []);
        $tutorAlias = $userMeta['tutor_alias'] ?? $user->name;
        $tutorDept = $userMeta['department'] ?? ($user->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์');

        Model::unguard();

        $product = Product::create([
            'sku' => $sku,
            'name' => $request->name,
            'category_id' => $category->id,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => $imageUrl,
        ]);

        $courseMetadata = json_encode([
            'hours_num' => (float) $request->hours_number,
            'duration' => $request->duration,
            'tutor_department' => $tutorDept,
            'suitable_for' => $request->suitable_for,
            'syllabus' => $request->syllabus,
            'pdf_url' => $request->pdf_url,
            'exam_type' => $request->exam_type ?: 'Midterm + Final',
        ], JSON_UNESCAPED_UNICODE);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => $tutorAlias . ' | OWNER:' . $user->email,
            'color' => $courseMetadata,
            'stock_quantity' => 999,
        ]);

        Model::reguard();

        return response()->json([
            'message' => 'เปิดขายคอร์สเรียนสำเร็จ! (หากต้องการแก้ไขข้อมูลภายหลัง กรุณาติดต่อแอดมิน)',
            'data' => $product->load(['category', 'images', 'variants', 'reviews.user'])
        ], 201);
    }
}