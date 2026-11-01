<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProductController, AuthController, OrderController, ReviewController};

Route::get('/products', [ProductController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// API สำหรับสตรีมมิ่งวิดีโอ (ทำให้กอคลิป ไปหน้า/ถอยหลังได้ 100%)
Route::get('/stream-video/{lessonId}', [ProductController::class, 'streamVideo']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/member-info', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/apply-tutor', [AuthController::class, 'applyTutor']); 
    
    Route::post('/products', [ProductController::class, 'store']); // อัปโหลดคลิปหลายบท
    Route::post('/tutor/coupons', [ProductController::class, 'createCoupon']); // ติวเตอร์สร้างโค้ด

    Route::get('/my-courses', [OrderController::class, 'myCourses']);
    Route::post('/cart', [OrderController::class, 'addToCart']);
    Route::get('/cart', [OrderController::class, 'viewCart']);
    Route::delete('/cart/{itemId}', [OrderController::class, 'removeFromCart']);
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});