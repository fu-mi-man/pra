<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

/**
 * カテゴリ数の制限チェックを行うバリデーションルール
 *
 * 同一enterprise_idとtypeの組み合わせで作成できるカテゴリ数を制限する
 * ValidationRule: カスタムバリデーションルールの基本インターフェース
 * DataAwareRule: フォームの全データにアクセスするためのインターフェース
 */
class CategoryLimit implements ValidationRule, DataAwareRule
{
    /**
     * バリデーション時のリクエストデータを格納
     *
     * @var array
     */
    private array $data = [];

     /**
     * バリデーション時のリクエストデータをセット
     * バリデーション実行時に、Laravelが自動的にsetDataを呼び出す
     * フォームから送信された全データが$this->dataに格納される
     * 例：$this->dataにはenterprise_id、name、typeなどが含まれる
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
     * カテゴリ数の制限チェックを実行
     *
     * @param string $attribute 検証中の属性名
     * @param mixed $value 検証中の値
     * @param Closure $fail バリデーション失敗時のコールバック
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = Category::where('enterprise_id', $this->data['enterprise_id'])
            ->where('type', $this->data['type'])
            ->count();

        if ($count >= 8) {
            $typeLabel = $this->data['type'] === 'catalog' ? '文書カテゴリ' : '商品カテゴリ';
            $fail("{$typeLabel}は最大8個までしか作成できません。");
        }
    }
}
