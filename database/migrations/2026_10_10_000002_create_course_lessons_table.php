<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ถ้ามีตารางนี้อยู่แล้ว ไม่ต้องสร้างซ้ำ (กัน Error)
        if (!Schema::hasTable('course_lessons')) {
            Schema::create('course_lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('title');
                $table->string('video_url');
                $table->integer('order_num')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};