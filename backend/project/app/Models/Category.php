<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;

    // テーブル名の明示的な指定
    protected $table = 'categories';

    // 一括代入可能な属性の指定
    protected $fillable = [
        'name',
        'type',
        'enterprise_id',
        'display_order',
    ];

    // 型キャスト
    protected $casts = [
        'enterprise_id' => 'integer',
        'display_order' => 'integer',
    ];

    /**
     * 出展者IDに基づいてカテゴリーをタイプごとにグループ化して取得
     *
     * @param int $enterpriseId
     * @return Collection
     */
    public static function groupByType(int $enterpriseId): Collection
    {
        return static::forEnterprise($enterpriseId)
            ->ordered()
            ->get()
            ->groupBy('type');
    }

    /**
     * 出展者IDでフィルタリング
     */
    public function scopeForEnterprise(Builder $query, int $enterpriseId): Builder
    {
        return $query->where('enterprise_id', $enterpriseId);
    }

    /**
     * 表示順でソート
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
