<?php

namespace App\Services;

use App\Libs\Util;
use App\Models\Product;
use App\Models\User;
use App\Models\Collection;

class ProductService
{
    /**
     * 商品詳細情報を取得する.
     *
     * @param App\Models\User $shop
     * @param int $spid shopifyのproduct.id
     */
    public function find(User $shop, int $spid): ?Product
    {
        // DBから商品情報を取得
        $product = Product::with(
            [
                'creators' => function ($q) {
                    $q->select(['creators.cid', 'creators.name']);
                },
            ]
        )
        ->where('shopify_product_id', $spid)
        ->first();

        if (! $product) {
            // @mock 仮でデータ登録する
            $product = new Product;
            $product->shopify_product_id = $spid;
            $product->herokura_item_code = '1111111111';
            $product->save();
        }

        // Shopify APIから商品情報を取得
        $shopifyProduct = $this->getShopifyProduct($shop, $spid);

        return $this->mergeShopifyProduct($product, $shopifyProduct);
    }

    /**
     * 商品の更新.
     *
     * @param int $id Shopify上の商品ID
     * @param array $params
     */
    public function update(User $shop, int $spid, array $params): bool
    {
        $product = Product::where('shopify_product_id', $spid)->first();

        if (! $product) {
            // @todo log
            return false;
        }

        // Custom App上の更新 @todo log
        $product->updated_at = date('Y-m-d H:i:s');
        $product->save();

        // Shopify上の更新
        // @todo log
        // @todo エラー処理
        $ret = $this->updateShopifyMetafields($shop, $spid, $params);

        return count($ret->productUpdate->userErrors) === 0 ? true : false;
    }

    /**
     * Shopifyから商品情報を取得する.
     *
     * @param App\Models\User $shop
     * @param int $spid
     */
    public function getShopifyProduct(User $shop, int $spid): ?object
    {
        $query = <<<GRAPHQL
        {
            product(id: "gid://shopify/Product/{$spid}") {
                id
                title
                description
                metafields(namespace: "muuu", first: 10) {
                    edges {
                        node {
                            id
                            key
                            value
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $shopifyProduct = $shop->api()->graph($query)->body->product;


            return $shopifyProduct;
        }

        // 商品IDのカラムを用意する
        $tmp = explode('/', $shopifyProduct->id);
        $shopifyProduct->product_id = $tmp[count($tmp) - 1];

        // metafieldsを使用しやすい形に整理
        return $this->trimMetafields($shopifyProduct);
    }

   
    /**
     * DBの商品情報とshopify上の商品情報をmergeする.
     *
     * @param App\Models\Product $product
     * @param object $shopifyProduct
     * @return App\Models\Product $product
     */
    protected function mergeShopifyProduct(Product $product, object $shopifyProduct): Product
    {
        $product->shopify = $shopifyProduct;

        return $product;
    }

    /**
     * metafieldsを使用しやすいように整形する.
     *
     * @param object $shopifyProduct
     * @return object $shopifyProduct
     */
    protected function trimMetafields(object $shopifyProduct): object
    {
        $metafields = $shopifyProduct->metafields;
        $shopifyProduct->original_metafields = $metafields;
        $shopifyProduct->metafields = Util::trimGraphQLMetafields($metafields);

        return $shopifyProduct;
    }

    /**
     * 保存用のmetafieldsを作成する.
     *
     * @param array $metafields
     * @return array
     */
    protected function makeMetafieldsByFormParams(array $metafields): array
    {
        $ret = [];

        $namespace = config('const.metafields.namespace');
        $params = $metafields[$namespace];
        $fields = Product::metafields();

        foreach ($fields as $field) {
            $key = $field['key'];

            if (empty($params[$key])) {
                continue;
            }

            $arr = [
                'namespace' => $namespace,
                'key' => $key,
                'value' => $params[$key]['value'],
                'valueType' => $field['valueType'],
            ];

            if (isset($params[$key]['id'])) {
                $arr['id'] = $params[$key]['id'];
            }

            $ret[] = $arr;
        }

        return $ret;
    }

    /**
     * Shopifyから商品リスト情報を取得する.
     *
     * @param App\Models\User $shop
     * @param int $spid
     */
    public function getShopifyProductList(User $shop): ?array
    {
        $query = <<<GRAPHQL
        {
            products(first: 100) {
                edges {
                    node {
                        id
                        title
                        images(first:1) {
                            edges {
                                node {
                                    originalSrc
                                }
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $shopifyProducts = $shop->api()->graph($query)->body->products->edges;

        $products = [];

        foreach ($shopifyProducts as $index => $product) {
            $products[$index]['id'] = str_replace('gid://shopify/Product/', '', $product->node->id);
            $products[$index]['title'] = $product->node->title;
            $products[$index]['image'] = count($product->node->images->edges) ? $product->node->images->edges[0]->node->originalSrc : null;
        }

        return $products;
    }

    /**
     * 商品をコレクションに紐付ける
     */
    public function addProductToCollection($product)
    {
        if ($product->creators->count() < 1) {
            return;
        }

        foreach ($product->creators as $c) {
            $collections = Collection::findByCid($c->cid);
        }
    }

    /**
     * cidから商品を検索する
     *
     * @param array $cids
     * @return Collection
     */
    public function findProductsByCids(array $cids)
    {
        return Product::select(['products.id', 'products.shopify_product_id', 'products.shopify_product_name'])
                        ->join('product_cids', function ($q) use ($cids) {
                            $q->whereIn('product_cids.cid', $cids)
                                ->on('products.id', 'product_cids.product_id');
                        })
                        ->get();
    }
}
