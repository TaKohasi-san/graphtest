<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    const SHIPMENT_UNSENT = 1;
    const SHIPMENT_SENT = 2;

    /**
     * order detail product relation
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'shopify_product_id', 'shopify_product_id');
    }
}
