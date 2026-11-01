<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    // กำหนดฟิลด์ที่อนุญาตให้รับข้อมูลมาบันทึกลงตาราง coupons ได้
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_purchase',
        'is_active',
        'expiration_date'
    ];
}