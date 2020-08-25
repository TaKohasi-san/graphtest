<?php

namespace App\Libs;

class GraphQuery
{
    /**
     * query collectionGet
     */
    public static function collectionGet(int $scid)
    {
        return <<<GRAPHQL
            {
                collection(id: "gid://shopify/Collection/{$scid}") {
                    id
                    title
                    handle
                    metafields(namespace: "muuu", first: 15) {
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
    }

    /**
     * mutation collectionCreate
     */
    public static function collectionCreate()
    {
        return <<<'GRAPHQL'
            mutation collectionCreate($input: CollectionInput!) {
                collectionCreate(input: $input) {
                    collection {
                        id
                        handle
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * mutation collectionUpdate
     */
    public static function collectionUpdate()
    {
        return <<<'GRAPHQL'
            mutation collectionUpdate($input: CollectionInput!) {
                collectionUpdate(input: $input) {
                    collection {
                        id
                        handle
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * 注文情報の検索
     *
     * @param string $args
     */
    public static function orderFind(string $args)
    {
        return <<<GRAPHQL
            {
                orders($args) {
                    edges {
                        cursor
                        node {
                            id
                            name
                            createdAt
                            processedAt
                            paymentGatewayNames
                            displayFinancialStatus
                            displayFulfillmentStatus
                            totalPriceSet {
                                shopMoney {
                                    amount
                                }
                            }
                            subtotalPriceSet {
                                shopMoney {
                                    amount
                                }
                            }
                            totalShippingPriceSet {
                                shopMoney {
                                    amount
                                }
                            }
                            totalTaxSet {
                                shopMoney {
                                    amount
                                }
                            }
                            totalDiscountsSet {
                                shopMoney {
                                    amount
                                }
                            }
                            transactions {
                                processedAt
                            }
                            lineItems(first:10) {
                                edges {
                                    cursor
                                    node {
                                        id
                                        sku
                                        quantity
                                        variant {
                                            id
                                        }
                                        originalUnitPriceSet {
                                            shopMoney {
                                                amount
                                            }
                                        }
                                        discountedTotalSet {
                                            shopMoney {
                                                amount
                                            }
                                        }
                                        product {
                                            id
                                        }
                                    }
                                }
                                pageInfo {
                                    hasNextPage
                                }
                            }
                        }
                    }
                    pageInfo {
                        hasNextPage
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * nodesからorderを取得する
     *
     * @param string $ids
     */
    public static function orderNodes($ids)
    {
        return <<<GRAPHQL
            query {
                nodes(ids: $ids) {
                    ...on Order {
                        id
                        name
                        createdAt
                        updatedAt
                        processedAt
                        paymentGatewayNames
                        totalPriceSet {
                            shopMoney {
                                amount
                            }
                        }
                        subtotalPriceSet {
                            shopMoney {
                                amount
                            }
                        }
                        totalShippingPriceSet {
                            shopMoney {
                                amount
                            }
                        }
                        totalTaxSet {
                            shopMoney {
                                amount
                            }
                        }
                        transactions {
                            processedAt
                        }
                        shippingAddress {
                            countryCodeV2
                            lastName
                            firstName
                            company
                            zip
                            provinceCode
                            city
                            address1
                            address2
                            phone
                        }
                        billingAddress {
                            countryCodeV2
                            lastName
                            firstName
                            company
                            zip
                            provinceCode
                            city
                            address1
                            address2
                            phone
                        }
                        customer {
                            email
                        }
                        lineItems(first:10) {
                            edges {
                                cursor
                                node {
                                    id
                                    sku
                                    quantity
                                    originalTotalSet {
                                        shopMoney {
                                            amount
                                        }
                                    }
                                    taxLines {
                                        priceSet {
                                            shopMoney {
                                                amount
                                            }
                                        }
                                    }
                                }
                            }
                            pageInfo {
                                hasNextPage
                            }
                        }
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * line_itemsの検索
     *
     * @param string $orderArgs
     * @param string $productArgs
     */
    public static function lineItemFind(string $orderArgs, string $productArgs)
    {
        return <<<GRAPHQL
            {
                order($orderArgs){
                    lineItems($productArgs) {
                        edges {
                            cursor
                            node {
                                id
                                sku
                                quantity
                                variant {
                                    id
                                }
                                originalUnitPriceSet {
                                    shopMoney {
                                        amount
                                    }
                                }
                                discountedTotalSet {
                                    shopMoney {
                                        amount
                                    }
                                }
                                product {
                                    id
                                }
                            }
                        }
                        pageInfo {
                            hasNextPage
                        }
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * 在庫数量を取得する
     *
     * @param string $filter
     * @return string
     */
    public static function getInventoryQuantities($filter)
    {
        return <<<GRAPHQL
            {
                productVariants(first:100, query:"$filter") {
                    edges {
                        node {
                            id
                            sku
                            inventoryQuantity
                            inventoryItem {
                                id
                            }
                        }
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * 在庫数量を更新する
     */
    public static function inventoryBulkAdjustQuantityAtLocation()
    {
        return <<<'GRAPHQL'
            mutation inventoryBulkAdjustQuantityAtLocation($inventoryItemAdjustments: [InventoryAdjustItemInput!]!, $locationId: ID!) {
                inventoryBulkAdjustQuantityAtLocation(inventoryItemAdjustments: $inventoryItemAdjustments, locationId: $locationId) {
                    inventoryLevels {
                        id
                        available
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * 商品を登録する
     */
    public static function productCreate()
    {
        return <<<'GRAPHQL'
            mutation productCreate($productInput: ProductInput!) {
                productCreate(input: $productInput) {
                    product {
                        id
                        publishedAt
                        variants(first:100) {
                            edges {
                                node {
                                    id
                                    sku
                                    inventoryItem {
                                        id
                                    }
                                }
                            }

                        }
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * 商品を更新する
     */
    public static function productUpdate()
    {
        return <<<'GRAPHQL'
            mutation productUpdate($productInput: ProductInput!) {
                productUpdate(input: $productInput) {
                    product {
                        id
                        publishedAt
                        variants(first:100) {
                            edges {
                                node {
                                    id
                                    sku
                                    inventoryItem {
                                        id
                                    }
                                }
                            }

                        }
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }
}
