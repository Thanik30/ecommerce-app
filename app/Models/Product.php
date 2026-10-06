<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // เพิ่มฟังก์ชันนี้เข้าไป เพื่อเชื่อมกับ Model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}