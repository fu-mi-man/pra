<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCategoryRequest extends FormRequest
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
            'enterprise_id' => 'bail|required|integer|exists:enterprises,id',
            'type' => 'bail|required|string|in:catalog,product',
        ];
    }

    /**
     * バリデーションエラーメッセージで使用する属性名を定義
     *
     * @return array<string, string> キーは属性名，値は日本語の表示名
     */
    public function attributes(): array
    {
        return [
            'enterprise_id' => '出展者ID',
            'type' => 'カテゴリ種別',
        ];
    }
}
