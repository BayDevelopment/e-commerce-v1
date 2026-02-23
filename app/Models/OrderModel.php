<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'total_price',

        // snapshot bank
        'bank_name',
        'bank_account_number',
        'bank_account_name',

        'payment_proof',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'total_price' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PayMethodModel::class);
    }
}
