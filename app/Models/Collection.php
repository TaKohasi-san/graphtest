<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    /**
     * @var int TYPE_SALE
     */
    const TYPE_SALE = 1;

    /**
     * @var int TYPE_CREATOR
     */
    const TYPE_CREATOR = 2;

    /**
     * @var int TYPE_CATEGORY
     */
    const TYPE_CATEGORY = 3;

    /**
     * @var int TYPE_ONDEMAND
     */
    const TYPE_ONDEMAND = 4;

    /**
     * creatorsとのリレーション.
     */
    public function creators()
    {
        return $this->belongsToMany('App\Models\Creator', 'collection_cids', 'collection_id', 'cid');
    }

    /**
     * 販売ページ用コレクションかどうか
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeIsSaleCollection(Builder $query)
    {
        return $query->where('type', self::TYPE_SALE);
    }

    /**
     * クリエイター用コレクションかどうか
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeIsCreatorCollection(Builder $query)
    {
        return $query->where('type', self::TYPE_CREATOR);
    }

    /**
     * カテゴリー用コレクションかどうか
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeIsCategoryCollection(Builder $query)
    {
        return $query->where('type', self::TYPE_CATEGORY);
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
                'key' => 'type',
                'valueType' => 'INTEGER'
            ],
            [
                'key' => 'category_id',
                'valueType' => 'INTEGER'
            ],
            [
                'key' => 'category_name',
                'valueType' => 'STRING',
            ],
            [
                'key' => 'display_start_sales_period',
                'valueType' => 'INTEGER',
            ],
            [
                'key' => 'display_end_sales_period',
                'valueType' => 'INTEGER',
            ],
            [
                'key' => 'display_recommend',
                'valueType' => 'INTEGER'
            ],
            [
                'key' => 'label',
                'valueType' => 'STRING',
            ],
            [
                'key' => 'subtitle',
                'valueType' => 'STRING',
            ],
            [
                'key' => 'creators',
                'valueType' => 'JSON_STRING',
            ],
            [
                'key' => 'published_at',
                'valueType' => 'STRING'
            ],
            [
                'key' => 'unpublished_at',
                'valueType' => 'STRING'
            ],
            [
                'key' => 'lead_time',
                'valueType' => 'STRING'
            ],
            [
                'key' => 'lead_time_label',
                'valueType' => 'STRING'
            ]
        ];
    }

    /**
     * collectionのタイプ一覧の取得
     *
     * @return array
     */
    public static function getTypeOptions()
    {
        return [
            self::TYPE_SALE => '販売ページ',
            self::TYPE_CREATOR => 'クリエイターページ',
            self::TYPE_CATEGORY => '商品カテゴリー',
            self::TYPE_ONDEMAND => 'クリエイターページ（オンデマンド）'
        ];
    }

    /**
     * cidに紐づくコレクションを取得する
     *
     * @param int $cid
     */
    public static function findByCid($cid)
    {
        return self::whereHas('creators', function ($q) use ($cid) {
            $q->where('cid', $cid);
        })->get();
    }

    /**
     * shopifyのcollection.idからレコードを検索する
     *
     * @param int $shopifyCollectionId
     * @return Collection
     */
    public static function getByShopifyCollectionId($shopifyCollectionId)
    {
        return self::where('shopify_collection_id', $shopifyCollectionId)->first();
    }

    /**
     * オンデマンド用販売ページが存在するかチェックする
     *
     * @param string $cid
     * @return Collection
     */
    public static function findOndemandCollection($cid)
    {
        return Collection::join('collection_cids', function ($q) use ($cid) {
            $q->on('collections.id', 'collection_cids.collection_id')
                ->where('collection_cids.cid', $cid);
        })->where('collections.type', Collection::TYPE_ONDEMAND)->first();
    }
}
