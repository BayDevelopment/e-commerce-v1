<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'image'     => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(CategoryModel::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariantModel::class, 'product_id');
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // Ambil harga termurah dari variant
    public function getLowestPriceAttribute()
    {
        return $this->variants()->min('price');
    }

    // Total stok dari semua variant
    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock');
    }

    // Cek apakah masih ada stok
    public function getIsInStockAttribute()
    {
        return $this->total_stock > 0;
    }
}
