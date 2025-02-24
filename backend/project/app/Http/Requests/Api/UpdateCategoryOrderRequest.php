<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateCategoryOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:catalog,product',
            'enterprise_id' => 'required|integer',
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.display_order' => 'required|integer|min:1'
        ];
    }

    /**
     * バリデーションエラーのカスタムメッセージを定義
     *
     * @return array<string, string> バリデーションエラーメッセージの配列
     */
    public function messages(): array
    {
        return [
            'enterprise_id' => '出展者ID',
            'type' => 'カテゴリ種別',
            'categories.required' => 'カテゴリデータは必須です',
            'categories.array' => 'カテゴリデータは配列形式で送信してください',
            'categories.*.id.required' => 'カテゴリIDは必須です',
            'categories.*.id.exists' => '指定されたカテゴリIDは存在しません',
            'categories.*.display_order.required' => '表示順は必須です',
            'categories.*.display_order.integer' => '表示順は整数で指定してください',
            'categories.*.display_order.min' => '表示順は1以上の数値を指定してください'
        ];
    }
}
