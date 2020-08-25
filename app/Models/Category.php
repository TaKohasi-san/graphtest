<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * getOptions
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::orderBy('herokura_category_id')
                    ->pluck('name', 'code')
                    ->toArray();
    }

    /**
     * herokura_category_idから カテゴリーコードを取得する
     * 中カテゴリーコード: 2桁, 小カテゴリーコード: 4桁
     *
     * @param string $herokuraId
     * @param int $digit
     * @return ?string
     */
    public static function getCodeByHerokuraId(string $herokuraId, int $digit): ?string
    {
        return self::where('herokura_category_id', $herokuraId)
                    ->whereRaw('LENGTH(code) = ?', $digit)
                    ->value('code');
    }
}
