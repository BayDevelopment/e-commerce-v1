<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItemModel::class, 'cart_id');
    }
    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
}
