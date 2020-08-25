<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTime extends Model
{
    /**
     * getOptions
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::orderBy('code')
                    ->pluck('body', 'code')
                    ->toArray();
    }
}
