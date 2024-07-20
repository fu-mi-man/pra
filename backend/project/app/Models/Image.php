<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Image extends Model
{
    use HasFactory;

    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'sequence_id';

    /**
     * Auto Increment
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * type別の合計sizeを取得する
     *
     * @param int $user_id
     * @return collection type別の合計size（product, catalog, enterprise）
     */
    public static function getSumSizeByType(int $user_id): collection
    {
        return self::query()
            ->selectRaw('type, SUM(size) AS sum_size')
            ->where('user_id', $user_id)
            ->groupBy('type')
            ->pluck('sum_size', 'type');
    }
}
