<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ดึงรายการคอร์สเรียนทั้งหมด
Route::get('/products', [ProductController::class, 'index']);

// สมัครสมาชิกและเข้าสู่ระบบ
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// โซนสำหรับสมาชิกที่ล็อกอินแล้ว
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/member-info', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']); // แก้ไขโปรไฟล์และรูปภาพ
    Route::post('/apply-tutor', [AuthController::class, 'applyTutor']);       // สมัครเป็นติวเตอร์
    Route::post('/products', [ProductController::class, 'store']);            // ลงขายคอร์สเรียน
    Route::get('/my-courses', [OrderController::class, 'myCourses']);         // ดูคอร์สที่ซื้อแล้ว
    Route::post('/cart', [OrderController::class, 'addToCart']);              // หยิบใส่ตะกร้า
    Route::get('/cart', [OrderController::class, 'viewCart']);                // ดูตะกร้าสินค้า
    Route::delete('/cart/{itemId}', [OrderController::class, 'removeFromCart']); // ลบคอร์สออกจากตะกร้า
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);    // ใช้คูปองส่วนลด (รองรับหลายโค้ด)
    Route::post('/checkout', [OrderController::class, 'checkout']);           // ยืนยันคำสั่งซื้อ
    Route::post('/reviews', [OrderController::class, 'store']);
    Route::post('/reviews', [ReviewController::class, 'store']);              // รีวิวคอร์สเรียน
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});