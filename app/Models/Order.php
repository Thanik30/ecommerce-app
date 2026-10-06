<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'total_amount', 'status'])]
class Order extends Model
{
    // 1 คำสั่งซื้อ มีสินค้าได้หลายชิ้น
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}