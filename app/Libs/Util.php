<?php

namespace App\Libs;

class Util
{
    /**
     * ナビゲーションを取得.
     *
     * @param string $routeName
     * @return string
     */
    public static function getNav(string $routeName): string
    {
        list($prefix) = explode('_', $routeName);

        return $prefix;
    }

    /**
     * session内にあるshopifyのアクセストークンを取得する.
     *
     * @param array $session
     * @return string
     */
    public static function getShopifyAccessToken($session): ?string
    {
        return $session->get('shopify_session_token');
    }

    /**
     * shpoifyのGraphQLから取得したmetafields情報を整形する.
     *
     * @param object $metafields
     * @return array
     */
    public static function trimGraphQLMetafields(object $metafields): array
    {
        $ret = [];

        foreach ($metafields->edges as $edge) {
            $ret[$edge->node->key] = [
                'id' => $edge->node->id,
                'key' => $edge->node->key,
                'value' => $edge->node->value,
            ];
        }

        return $ret;
    }

    /**
     * shopifyのGraphQL用IDから数字部分だけ取り除く
     *
     * @param string $graphId
     * @return int
     */
    public static function getIdByGraphId(string $graphId)
    {
        if (strpos($graphId, '/') === false) {
            return $graphId;
        }

        $tmp = explode('/', $graphId);

        return $tmp[count($tmp) - 1];
    }

    /**
     * Base api url 取得する.
     *
     * @return string
     */
    public static function getBaseUrl(): string
    {
        return '/admin/api/' . config('shopify-app.api_version');
    }

    /**
     * 学研のロケーションidを取得する
     *
     * @return string
     */
    public static function getGakkenLocationId(): string
    {
        return config('app.locations.gakken');
    }

    /**
     * IMのロケーションidを取得する
     *
     * @return string
     */
    public static function getIMLocationId(): string
    {
        return config('app.locations.im');
    }

    /**
     * 日付のフォーマットを確認する
     *
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function validateDate($date, $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * ファイルの拡張子が指定されたものと同様か判定する
     *
     * @param $file
     * @param string $ext
     * @return bool
     */
    public static function checkFileExtension($file, string $ext): bool
    {
        $e = $file->getClientOriginalExtension();

        return $e === $ext ? true : false;
    }

    /**
     * 改行コードをbr tagで置換する
     *
     * @param string $text
     * @return string
     */
    public static function convertBrTag(string $text): string
    {
        $text = str_replace("\r\n", '<br>', $text);
        $text = str_replace("\r", '<br>', $text);
        $text = str_replace("\n", '<br>', $text);

        return $text;
    }

    /**
     * graphqlのfilter用文字列を生成する
     *
     * @param string $key
     * @param array $params
     * @return string
     */
    public static function makeGraphQueryOr($key, $params)
    {
        $ret = '';

        foreach ($params as $param) {
            $ret .= " OR ${key}:${param}";
        }

        return $ret;
    }
}
