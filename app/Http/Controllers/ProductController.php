<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

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
            'course_video' => 'required|file|mimes:mp4,webm,mov,ogg|max:204800', 
        ]);

        $sku = trim($request->sku);
        $prefix = substr($sku, 0, 3);

        // แก้ไข: ค้นหาหมวดหมู่ ถ้าไม่พบให้ใช้ "หมวดหมู่อื่นๆ"
        $category = Category::where('name', 'like', "%({$prefix}xxx)%")->first();

        if (!$category) {
            $category = Category::where('name', 'like', '%หมวดหมู่อื่นๆ%')->first();
        }

        $categoryId = $category ? $category->id : 1;

        $imageUrl = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97...';
        if ($request->hasFile('cover_image')) {
            $imgPath = $request->file('cover_image')->store('courses', 'public');
            $imageUrl = '/storage/' . $imgPath;
        }

        $videoUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
        if ($request->hasFile('course_video')) {
            $vidPath = $request->file('course_video')->store('videos', 'public');
            $videoUrl = '/storage/' . $vidPath;
        }

        $tutorAlias = $user->tutor_alias ?: $user->name;
        $tutorDept = $user->department ?: ($user->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์');
        $examType = $request->exam_type ?: 'Midterm + Final';

        $product = Product::create([
            'sku' => $sku,
            'name' => $request->name,
            'category_id' => $categoryId, // แก้ไข: ใช้ตัวแปร $categoryId
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