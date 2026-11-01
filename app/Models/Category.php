<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // อนุญาตให้บันทึกชื่อหมวดหมู่ลงตาราง categories
    protected $fillable = ['name'];

    // เชื่อมกับ Model Product (1 หมวดหมู่ มีได้หลายสินค้า)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}