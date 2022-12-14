<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    public $table = "variants";
    protected $primaryKey = "variant_id";

    protected $fillable = [
        'product_id',
        'weight',
        'size',
        'type',
        'price'
    ];

    // function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'product_id');
    // }
}
