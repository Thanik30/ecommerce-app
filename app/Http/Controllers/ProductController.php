<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\CourseLesson;
use App\Models\Coupon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images', 'variants', 'reviews.user', 'lessons'])
            ->latest()
            ->get();
        return response()->json(['data' => $products, 'categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'tutor') {
            return response()->json(['message' => 'เฉพาะติวเตอร์เท่านั้นที่อัปโหลดคอร์สได้'], 403);
        }

        $request->validate([
            'sku' => 'required|digits:6',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'hours_number' => 'required|numeric|min:0.5',
            'duration' => 'required|string',
            'suitable_for' => 'required|string',
            'description' => 'required|string',
            'pdf_url' => 'required|url',
            'cover_image' => 'required|file|mimes:jpg,jpeg,png,webp',
            'lesson_titles' => 'required|array|min:1',
            'lesson_titles.*' => 'required|string',
            'lesson_videos' => 'required|array|min:1',
            'lesson_videos.*' => 'required|file|mimes:mp4,webm,mov,ogg',
        ]);

        $sku = trim($request->sku);
        $prefix = substr($sku, 0, 3);

        // 1. ลองค้นหาหมวดหมู่จาก 3 รหัสหน้าก่อน (เช่น 259)
        $category = Category::where('name', 'like', "%{$prefix}%")->first();

        // 2. ถ้าไม่เจอหมวดวิชานี้ ให้ปัดตกไปอยู่ "หมวดหมู่อื่นๆ" ทันที
        if (!$category) {
            $category = Category::where('name', 'like', '%อื่นๆ%')->first();
    
            // ถ้าในฐานข้อมูลยังไม่มีหมวดอื่นๆ ให้สร้างขึ้นมาใหม่เลย
            if (!$category) {
                $category = Category::create([
                    'name' => 'หมวดหมู่อื่นๆ (Others)'
                ]);
            }
        }

        $imageUrl = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=900&q=80';
        if ($request->hasFile('cover_image')) {
            $imgPath = $request->file('cover_image')->store('courses', 'public');
            $imageUrl = '/storage/' . $imgPath;
        }

        $tutorAlias = $user->tutor_alias ?: $user->name;
        $tutorDept = $user->department ?: 'คณะวิศวกรรมศาสตร์';
        
        $product = Product::create([
            'sku' => $sku,
            'name' => $request->name,
            'category_id' => $category->id,
            'price' => $request->price,
            'description' => $request->description,
            'pdf_url' => $request->pdf_url,
            'hours_num' => (float) $request->hours_number,
            'duration' => $request->duration,
            'exam_type' => $request->exam_type ?: 'Midterm + Final',
            'suitable_for' => $request->suitable_for,
            'tutor_name' => $tutorAlias,
            'tutor_department' => $tutorDept,
            'owner_email' => $user->email,
        ]);

        ProductImage::create(['product_id' => $product->id, 'image_url' => $imageUrl]);
        ProductVariant::create([
            'product_id' => $product->id,
            'size' => $tutorAlias . ' | OWNER:' . $user->email,
            'color' => json_encode([
                'hours_num' => (float) $request->hours_number,
                'duration' => $request->duration,
                'tutor_department' => $tutorDept,
                'suitable_for' => $request->suitable_for,
                'pdf_url' => $request->pdf_url,
                'exam_type' => $request->exam_type ?: 'Midterm + Final',
            ], JSON_UNESCAPED_UNICODE),
            'stock_quantity' => 999,
        ]);

        $titles = $request->input('lesson_titles');
        $videos = $request->file('lesson_videos');
        $autoSyllabus = "";
        
        foreach ($titles as $index => $title) {
            if (isset($videos[$index])) {
                $vidPath = $videos[$index]->store('videos', 'public');
                $lessonTitle = "บทที่ " . ($index + 1) . ": " . $title;
                $autoSyllabus .= $lessonTitle . "\n";
                
                CourseLesson::create([
                    'product_id' => $product->id,
                    'title' => $title,
                    'video_url' => '/storage/' . $vidPath,
                    'order_num' => $index + 1
                ]);
            }
        }

        $product->syllabus = trim($autoSyllabus);
        $product->save();

        return response()->json([
            'message' => 'อัปโหลดคลิปแบบแยกบท และวางขายสำเร็จ!',
            'data' => $product->load(['category', 'images', 'variants', 'reviews.user', 'lessons'])
        ], 201);
    }

    public function streamVideo($lessonId)
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $relativePath = str_replace('/storage/', '', $lesson->video_url);
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists($fullPath)) {
            abort(404, 'ไม่พบไฟล์วิดีโอ');
        }

        return response()->file($fullPath, [
            'Accept-Ranges' => 'bytes',
            'Content-Type' => mime_content_type($fullPath),
            'Cache-Control' => 'public, max-age=2592000'
        ]);
    }

    public function createCoupon(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'tutor') {
            return response()->json(['message' => 'เฉพาะติวเตอร์เท่านั้น'], 403);
        }
        
        // 💡 แก้ไข: เพิ่ม min:1 ป้องกันการสร้างโค้ดติดลบ
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_value' => 'required|numeric|min:1',
            'discount_type' => 'required|string'
        ]);
        
        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'owner_email' => $user->email,
            'is_active' => true,
        ]);
        return response()->json(['message' => 'สร้างโค้ดสำเร็จ!']);
    }
}