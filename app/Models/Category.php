<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // ปลดล็อคให้ระบบสร้างหมวดหมู่ "อื่นๆ" อัตโนมัติได้
    protected $guarded = []; 
}