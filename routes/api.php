<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

// สร้าง URL: http://127.0.0.1:8000/api/products
Route::get('/products', [ProductController::class, 'index']);
// URL สำหรับสมัครสมาชิก: http://127.0.0.1:8000/api/register
Route::post('/register', [AuthController::class, 'register']);
// URL สำหรับเข้าสู่ระบบ: http://127.0.0.1:8000/api/login
Route::post('/login', [AuthController::class, 'login']);
// URL สำหรับดูข้อมูลส่วนตัว: http://127.0.0.1:8000/api/member-info
// สังเกตว่ามีการใช้ middleware('auth:sanctum') เพื่อตรวจสอบ Token
Route::middleware('auth:sanctum')->get('/member-info', [AuthController::class, 'profile']);
// โซนนี้ต้องใช้ Token (ต้องล็อกอินก่อนถึงจะทำได้)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/member-info', [AuthController::class, 'profile']); // ดูข้อมูลส่วนตัว
    Route::post('/cart', [OrderController::class, 'addToCart']);    // หยิบใส่ตะกร้า
    Route::get('/cart', [OrderController::class, 'viewCart']);      // ดูตะกร้าสินค้า
});
