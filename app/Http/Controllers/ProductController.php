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

        // เช็คการแนบไฟล์บทเรียนที่เป็น Array
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
        $category = Category::where('name', 'like', "%(" . substr($sku, 0, 3) . "xxx)%")->first() ?: Category::first();

        // 1. เก็บรูปปก
        $imageUrl = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=900&q=80';
        if ($request->hasFile('cover_image')) {
            $imgPath = $request->file('cover_image')->store('courses', 'public');
            $imageUrl = '/storage/' . $imgPath;
        }

        // 2. สร้างคอร์สหลัก
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

        // 3. วนลูปเก็บไฟล์วิดีโอแยกทีละบทเรียน
        $titles = $request->input('lesson_titles');
        $videos = $request->file('lesson_videos');

        // จัดการเรียงหัวข้อให้เป็น Syllabus อัตโนมัติ (เอาไปโชว์ในหน้ารายละเอียดคอร์ส)
        $autoSyllabus = "";
        
        foreach ($titles as $index => $title) {
            if (isset($videos[$index])) {
                $vidPath = $videos[$index]->store('videos', 'public');
                $lessonTitle = "บทที่ " . ($index + 1) . ": " . $title;
                $autoSyllabus .= $lessonTitle . "\n";
                
                CourseLesson::create([
                    'product_id' => $product->id,
                    'title' => $title, // เก็บเฉพาะชื่อหัวข้อที่ติวเตอร์ตั้ง
                    'video_url' => '/storage/' . $vidPath,
                    'order_num' => $index + 1
                ]);
            }
        }

        // บันทึก Syllabus กลับไปที่ตาราง Product
        $product->syllabus = trim($autoSyllabus);
        $product->save();

        return response()->json([
            'message' => 'อัปโหลดคลิปแบบแยกบท และวางขายสำเร็จ!',
            'data' => $product->load(['category', 'images', 'variants', 'reviews.user', 'lessons'])
        ], 201);
    }

    // สตรีมวิดีโอ (เพิ่ม Headers เพื่อให้โหลดไฟล์รวดเดียว/กอคลิปได้เนียนๆ)
    public function streamVideo($lessonId)
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $relativePath = str_replace('/storage/', '', $lesson->video_url);
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists($fullPath)) {
            abort(404, 'ไม่พบไฟล์วิดีโอ');
        }

        // ส่งไฟล์กลับไปพร้อมอนุญาตให้ Browser โหลดได้อิสระ
        return response()->file($fullPath, [
            'Accept-Ranges' => 'bytes',
            'Content-Type' => mime_content_type($fullPath),
            'Cache-Control' => 'no-cache, must-revalidate'
        ]);
    }

    public function createCoupon(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'tutor') {
            return response()->json(['message' => 'เฉพาะติวเตอร์เท่านั้น'], 403);
        }
        $request->validate(['code' => 'required|string|unique:coupons,code', 'discount_value' => 'required|numeric', 'discount_type' => 'required|string']);
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