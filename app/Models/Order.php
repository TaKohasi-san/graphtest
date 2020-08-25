<?php

namespace App\Models;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * 入金ステータス：支払い済み
     */
    const FINANCIAL_PAID = 'paid';

    /**
     * order_detailsとのリレーション
     */
    public function details()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    /**
     * 受注確定であるかどうか
     *
     * @param Builder $query
     */
    public function scopeIsConfirmed(Builder $query)
    {
        return $query->where('shopify_financial_status', self::FINANCIAL_PAID);
    }

    /**
     * shopifyのorder.idからcustom app上の注文を確認する
     *
     * @param int $shopifyOrderId
     * @return Order
     */
    public static function getByShopifyOrderId($shopifyOrderId)
    {
        return self::with([
            'details'
        ])->where('shopify_order_id', $shopifyOrderId)->first();
    }

    /**
     * shopify上のorder.nameから注文を取得.shopifyAPIではnameによる検索ができないのでDBから検索.
     *
     * @param array $shopifyOrderNames
     * @return Collection
     */
    public static function findByShopifyOrderNames(array $shopifyOrderNames)
    {
        return self::whereIn('shopify_order_name', $shopifyOrderNames)->get();
    }
}