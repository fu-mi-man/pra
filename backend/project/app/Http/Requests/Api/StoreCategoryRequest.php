<?php

namespace App\Http\Requests\Api;

use App\Rules\CategoryLimit;
use App\Rules\UniqueCategoryName;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => [
                'bail',
                'required',
                'string',
                'max:30',
                new UniqueCategoryName()
            ],
            'type' => [
                'bail',
                'required',
                'string',
                'in:catalog,product',
                 new CategoryLimit()
            ]
        ];
    }

    /**
     * バリデーションエラーメッセージで使用する属性名を定義
     */
    public function attributes(): array
    {
        return [
            'name' => 'カテゴリ名',
            'enterprise_id' => '出展者ID',
            'type' => 'カテゴリ種別',
        ];
    }
}
