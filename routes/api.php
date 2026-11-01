<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

// 1. ดึงรายการคอร์สเรียนทั้งหมด
Route::get('/products', [ProductController::class, 'index']);

// 2. สมัครสมาชิกและเข้าสู่ระบบ
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 3. โซนสำหรับสมาชิกที่ล็อกอินแล้ว (ต้องส่ง Bearer Token มาด้วย)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/member-info', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']); 
    Route::post('/apply-tutor', [AuthController::class, 'applyTutor']);       
    Route::post('/products', [ProductController::class, 'store']);            
    Route::get('/my-courses', [OrderController::class, 'myCourses']);         
    Route::post('/cart', [OrderController::class, 'addToCart']);              
    Route::get('/cart', [OrderController::class, 'viewCart']);                
    Route::delete('/cart/{itemId}', [OrderController::class, 'removeFromCart']); 
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);    
    Route::post('/checkout', [OrderController::class, 'checkout']);           
    Route::post('/reviews', [ReviewController::class, 'store']);              
    Route::post('/logout', [AuthController::class, 'logout']);
});