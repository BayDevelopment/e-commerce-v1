<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayMethodModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bank_name',
        'account_number',
        'account_name',
        'is_active',
    ];

    /* ================= RELATION ================= */

    public function orders()
    {
        return $this->hasMany(OrderModel::class);
    }
}
