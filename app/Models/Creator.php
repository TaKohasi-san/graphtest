<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Creator extends Model
{
    /**
     * @param array $fillable
     */
    protected $fillable = ['cid', 'name'];

    /**
     * cidによるレコード検索
     *
     * @param array $cids
     */
    public static function findByCids(array $cids)
    {
        return self::whereIn('cid', $cids)->get();
    }
}
