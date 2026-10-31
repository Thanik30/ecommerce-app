<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    // บรรทัดนี้ป้องกัน Error ถัดไป
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'stock_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}