<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                'message' => 'เฉพาะผู้ที่ผ่านการยืนยันตัวตนเป็นติวเตอร์แล้วเท่านั้น จึงจะสามารถลงขายคอร์สเรียนได้'
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
            'cover_image' => 'required|file|mimes:jpg,jpeg,png,webp|max:10240',
            'course_video' => 'required|file|mimes:mp4,webm,mov,ogg|max:204800', // รองรับไฟล์วิดีโอสูงสุด 200MB
        ]);

        // จับคู่หมวดหมู่สาขาวิชาอัตโนมัติจากรหัสวิชา 3 หลักแรก
        $sku = trim($request->sku);
        $prefix = substr($sku, 0, 3);
        $category = Category::where('name', 'like', "%({$prefix}xxx)%")->first() ?: Category::first();

        // 1. บันทึกไฟล์รูปปกคอร์ส
        $imageUrl = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=900&q=80';
        if ($request->hasFile('cover_image')) {
            $imgFile = $request->file('cover_image');
            $imgName = 'cover_' . $sku . '_' . time() . '.' . $imgFile->getClientOriginalExtension();
            $imgPath = public_path('uploads/courses');
            if (!file_exists($imgPath)) {
                mkdir($imgPath, 0775, true);
            }
            $imgFile->move($imgPath, $imgName);
            $imageUrl = '/uploads/courses/' . $imgName;
        }

        // 2. บันทึกไฟล์คลิปวิดีโอการสอน
        $videoUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
        if ($request->hasFile('course_video')) {
            $vidFile = $request->file('course_video');
            $vidName = 'video_' . $sku . '_' . time() . '.' . $vidFile->getClientOriginalExtension();
            $vidPath = public_path('uploads/videos');
            if (!file_exists($vidPath)) {
                mkdir($vidPath, 0775, true);
            }
            $vidFile->move($vidPath, $vidName);
            $videoUrl = '/uploads/videos/' . $vidName;
        }

        $tutorAlias = $user->tutor_alias ?: $user->name;
        $tutorDept = $user->department ?: ($user->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์');
        $examType = $request->exam_type ?: 'Midterm + Final';

        $product = Product::create([
            'sku' => $sku,
            'name' => $request->name,
            'category_id' => $category->id,
            'price' => $request->price,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'pdf_url' => $request->pdf_url,
            'hours_num' => (float) $request->hours_number,
            'duration' => $request->duration,
            'exam_type' => $examType,
            'suitable_for' => $request->suitable_for,
            'syllabus' => $request->syllabus,
            'tutor_name' => $tutorAlias,
            'tutor_department' => $tutorDept,
            'owner_email' => $user->email,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => $imageUrl,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => $tutorAlias . ' | OWNER:' . $user->email,
            'color' => json_encode([
                'hours_num' => (float) $request->hours_number,
                'duration' => $request->duration,
                'tutor_department' => $tutorDept,
                'suitable_for' => $request->suitable_for,
                'syllabus' => $request->syllabus,
                'pdf_url' => $request->pdf_url,
                'video_url' => $videoUrl,
                'exam_type' => $examType,
            ], JSON_UNESCAPED_UNICODE),
            'stock_quantity' => 999,
        ]);

        return response()->json([
            'message' => 'อัปโหลดคลิปวิดีโอและเปิดขายคอร์สเรียนสำเร็จ!',
            'data' => $product->load(['category', 'images', 'variants', 'reviews.user'])
        ], 201);
    }
}