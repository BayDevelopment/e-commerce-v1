<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'product_name',
        'product_sku',
        'note',
    ];

    /* ================= RELATION ================= */

    public function order()
    {
        return $this->belongsTo(OrderModel::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
