<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable();
            $table->string('real_name')->nullable();
            $table->string('tutor_alias')->nullable();
            $table->string('student_id')->nullable();
            $table->string('department')->nullable();
            $table->decimal('gpax', 3, 2)->nullable();
            $table->string('transcript_url')->nullable();
            $table->string('id_card_url')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->string('pdf_url')->nullable();
            $table->decimal('hours_num', 5, 1)->default(20);
            $table->string('duration')->default('12 เดือน');
            $table->string('exam_type')->default('Midterm + Final');
            $table->text('suitable_for')->nullable();
            $table->text('syllabus')->nullable();
            $table->string('tutor_name')->nullable();
            $table->string('tutor_department')->nullable();
            $table->string('owner_email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_url', 'real_name', 'tutor_alias', 'student_id',
                'department', 'gpax', 'transcript_url', 'id_card_url'
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'video_url', 'pdf_url', 'hours_num', 'duration', 'exam_type',
                'suitable_for', 'syllabus', 'tutor_name', 'tutor_department', 'owner_email'
            ]);
        });
    }
};