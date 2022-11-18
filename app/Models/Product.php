<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;
use App\Models\ProductVariant;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "product_id";

    public function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function product_variant()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }
}
