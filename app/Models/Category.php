<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // เพิ่มฟังก์ชันนี้เข้าไป เพื่อเชื่อมกับ Model Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}