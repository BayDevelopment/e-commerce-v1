<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
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
    // LOGIKA BARU DAN LAMA
    public function getIsNewAttribute()
    {
        return $this->created_at->diffInDays(now()) < 4;
    }
    // product slug
    protected static function booted()
    {
        static::saving(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // IMAGE PRODUCT
    public function getMainImageAttribute()
    {
        if (is_array($this->image) && count($this->image)) {
            return $this->image[0];
        }

        return null;
    }
    public function branches()
    {
        return $this->belongsToMany(
            BranchModel::class,
            'product_branch',
            'product_id',
            'branch_id'
        );
    }
}
