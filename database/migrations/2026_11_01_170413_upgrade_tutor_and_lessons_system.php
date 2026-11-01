<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. เพิ่มสถานะรออนุมัติให้ติวเตอร์
        Schema::table('users', function (Blueprint $table) {
            $table->string('tutor_status')->default('none'); // none, pending, approved
        });

        // 2. สร้างตารางแยกบทเรียน (1 คอร์ส มีได้หลายคลิปวิดีโอ)
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('title');
            $table->string('video_url');
            $table->integer('order_num')->default(1);
            $table->timestamps();
        });

        // 3. เพิ่มเจ้าของคูปอง เพื่อให้ติวเตอร์สร้างโค้ดตัวเองได้
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('owner_email')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('tutor_status'); });
        Schema::table('coupons', function (Blueprint $table) { $table->dropColumn('owner_email'); });
    }
};