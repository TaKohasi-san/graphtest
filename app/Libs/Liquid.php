<?php

namespace App\Libs;

class Liquid
{
    /**
     * @var NEW_ARRIVALS_KEY 新着アイテムのliquidファイル
     */
    const NEW_ARRIVALS_KEY = 'sections/laravel-new-arrivals.liquid';

    /**
     * @var JSON_CREATORS クリエイター情報のliquidファイル
     */
    const JSON_CREATORS = 'snippets/json-creators.liquid';

    /**
     * @var JSON_CREATOR_LINKS クリエイターの関連リンク用liquidファイル
     */
    const JSON_CREATOR_LINKS = 'snippets/json-creator-links.liquid';

    /**
     * @var JSON_CATEGORIES カテゴリー情報のliquidファイル
     */
    const JSON_CATEGORIES = 'snippets/json-categories.liquid';

    /**
     * @var CATEGORY_HANDLES カテゴリーのhandle
     */
    const CATEGORY_HANDLES = 'snippets/category-handles.liquid';

    /**
     * @var CREATOR_SALE_PAGE_HANDLES
     */
    const CREATOR_SALE_PAGE_HANDLES = 'snippets/creator-salepage-handles.liquid';

    /**
     * @var CATEGORY_SALE_PAGE_HANDLES
     */
    const CATEGORY_SALE_PAGE_HANDLES = 'snippets/category-salepage-handles.liquid';

    /**
     * sections/laravel-new-arrivals.liquid
     *
     * @param string $handles
     * @return string
     */
    public static function newArrivals(string $handles): string
    {
        return <<<LIQUID
        <section id="section-{{ section.id }}" class="content__body">
            {% assign handles = '${handles}' | split: ' ' %}
            {% render 'new-arrivals.liquid', handles: handles %}
        </section>

        {% schema %}
            {
            "name": "新着アイテム（販売ページ）",
            "settings": [],
            "presets": [
                {
                "category": "高度なレイアウト",
                "name": "新着アイテム（販売ページ）"
                }
            ]
            }
        {% endschema %}
        LIQUID;
    }

    /**
     * snippets/json-creators.liquidの作成
     *
     * @param string $creators
     * @return string
     */
    public static function jsonCreators(
        string $creators,
        string $alpha,
        string $kana,
        string $kana2
    )
    {
        return <<<LIQUID
        <script type="application/json" id="js-creators">${creators}</script>
        <script type="application/json" id="js-creators-abc">${alpha}</script>
        <script type="application/json" id="js-creators-kana">${kana}</script>
        <script type="application/json" id="js-creators-kana2">${kana2}</script>
        LIQUID;
    }

    /**
     * snippets/json-creator-links.liquidの作成
     *
     * @param string $creators
     * @return string
     */
    public static function jsonCreatorLinks(string $creatorLinks)
    {
        return <<<LIQUID
        <script type="application/json" id="js-creator-links">${creatorLinks}</script>
        LIQUID;
    }

    /**
     * snippets/json-categories.liquidの作成
     *
     * @param string $categories
     * @return string
     */
    public static function jsonCategories(string $categories)
    {
        return <<<LIQUID
        <script type="application/json" id="js-categories">${categories}</script>
        LIQUID;
    }

    /**
     * snippets/category-handles.liquidの作成
     *
     * @param string $categoryHandles
     * @return string
     */
    public static function categoryHandles(string $categoryHandles)
    {
        return "${categoryHandles}";
    }

    /**
     * snippets/creator-salepage-handles.liquidの作成
     *
     * @param string $creatorSalePages
     * @return string
     */
    public static function creatorSalePageHandles($creatorSalePages)
    {
        return "${creatorSalePages}";
    }

    /**
     * snippets/category-salepage-handles.liquidの作成
     *
     * @param string $categorySalePageHandles
     * @return string
     */
    public static function categorySalePageHandles($categorySalePageHandles)
    {
        return "${categorySalePageHandles}";
    }
}
