<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    /**
     * getOptions
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::orderBy('herokura_color_id')
                    ->pluck('name', 'herokura_color_id')
                    ->toArray();
    }
}
