<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
