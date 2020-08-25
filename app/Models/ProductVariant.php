<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'shopify_variant_id', 'inventory_item_id', 'sku', 'color_code', 'size_code', 'body_master_id', 'manufacture_price', 'creator_price',
    ];
}
