<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

/**
 * カテゴリ名の重複チェックを行うバリデーションルール
 *
 * 同一enterprise_idとtypeの組み合わせで同じ名前のカテゴリが存在するかチェックする
 */
class UniqueCategoryName implements ValidationRule, DataAwareRule
{
    /**
     * バリデーション時のリクエストデータを格納
     *
     * @var array
     */
    private array $data = [];

    /**
     * 更新時の対象カテゴリID
     *
     * @var int|null
     */
    private ?int $excludeId;

    /**
     * コンストラクタ
     *
     * @param int|null $excludeId 更新時の対象カテゴリID
     */
    public function __construct(?int $excludeId = null)
    {
        $this->excludeId = $excludeId;
    }

    /**
     * バリデーション時のリクエストデータをセット
     *
     * @param array $data リクエストデータ
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * カテゴリ名の重複チェックを実行
     *
     * @param string $attribute 検証中の属性名
     * @param mixed $value 検証中の値
     * @param Closure $fail バリデーション失敗時のコールバック
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Category::where('enterprise_id', $this->data['enterprise_id'])
            ->where('type', $this->data['type'])
            ->where('name', $value);

        // 更新時は自分自身を除外
        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        if ($query->exists()) {
            $typeLabel = $this->data['type'] === 'catalog' ? '文書カテゴリ' : '商品カテゴリ';
            $fail("この{$typeLabel}名は既に使用されています。");
        }
    }
}
