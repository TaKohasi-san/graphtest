<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * 商品種別:オリジナル商品
     */
    const TYPE_ORIGINAL = 1;

    /**
     * 商品種別:IM商品
     */
    const TYPE_IM = 2;

    /**
     * creatorsとのリレーション.
     */
    public function creators()
    {
        return $this->belongsToMany('App\Models\Creator', 'product_cids', 'product_id', 'cid');
    }

    /**
     * categoriesとのリレーション
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'product_categories', 'product_id', 'category_id');
    }

    /**
     * metafieldsの値タイプ.
     *
     * @return array
     */
    public static function metafields(): array
    {
        return [
            [
                'key' => 'resale',
                'valueType' => 'INTEGER',
            ],
            [
                'key' => 'combine',
                'valueType' => 'INTEGER',
            ],
            [
                'key' => 'cautioned_note',
                'valueType' => 'STRING',
            ],
            [
                'key' => 'image_src',
                'valueType' => 'STRING',
            ],
            [
                'key' => 'creators',
                'valueType' => 'JSON_STRING',
            ],
        ];
    }
}
