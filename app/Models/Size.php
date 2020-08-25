<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    /**
     * getOptions
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::orderBy('herokura_size_id')
                    ->pluck('name', 'herokura_size_id')
                    ->toArray();
    }
}
