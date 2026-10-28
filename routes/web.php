<?php

use Illuminate\Support\Facades\Route;

// 1. หน้าแรก: ค้นหารหัสวิชาและรายการคอร์สเรียนทั้งหมด
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// 2. หน้ารายละเอียดคอร์สเรียนรายวิชา
Route::get('/courses/{id}', function ($id) {
    return view('pages.course-detail', ['courseId' => $id]);
})->name('course.detail');

// 3. หน้าคอร์สเรียนที่ซื้อแล้ว & คลิปสอนที่ฉันลงขาย (พร้อมตัวเล่นวิดีโอจริง)
Route::get('/my-courses', function () {
    return view('pages.my-courses');
})->name('my.courses');

// 4. หน้าสมัครและยืนยันตัวตนเพื่อเป็นติวเตอร์
Route::get('/apply-tutor', function () {
    return view('pages.apply-tutor');
})->name('apply.tutor');

// 5. หน้าลงขายคอร์สเรียนและอัปโหลดคลิปวิดีโอสำหรับติวเตอร์
Route::get('/create-course', function () {
    return view('pages.create-course');
})->name('create.course');

// 6. หน้าแก้ไขโปรไฟล์และรูปภาพโปรไฟล์
Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');