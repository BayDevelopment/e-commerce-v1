<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantModel extends Model
{
    use SoftDeletes;

    protected $table = 'product_variants';
    protected $fillable = [
        'product_id',
        'branch_id',
        'sku',
        'color',
        'size',
        'price',
        'stock',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(ProductVariantModel::class);
    // }
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id'); // foreign key 'product_id'
    }

    public function branch()
    {
        return $this->belongsTo(BranchModel::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItemModel::class, 'variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class);
    }
}
