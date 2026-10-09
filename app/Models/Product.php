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

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }
}