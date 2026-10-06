<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['order_id', 'product_id', 'quantity', 'price'])]
class OrderItem extends Model
{
    // สินค้าในตะกร้าแต่ละชิ้น ดึงข้อมูลมาจากตาราง Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}