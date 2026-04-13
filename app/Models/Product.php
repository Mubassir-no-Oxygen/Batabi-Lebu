<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Based on DB tables schema you provided
    protected $fillable = [
        'supplier_id',
        'product_name',
        'category',
        'description',
        'price',
        'unit',
        'stock_quantity',
        'image',
        'status',
    ];

    public function supplier()
    {
        // Assuming a predefined supplier relationship structure exists.
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
