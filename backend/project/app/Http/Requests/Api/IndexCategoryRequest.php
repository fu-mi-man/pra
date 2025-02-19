<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexCategoryRequest extends FormRequest
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
            'enterprise_id' => ['required', 'integer', 'exists:enterprises,id'],
        ];
    }

    /**
     * バリデーションエラーのカスタムメッセージ
     */
    public function messages(): array
    {
        return [
            'enterprise_id.required' => '出展者IDは必須です',
            'enterprise_id.integer' => '出展者IDは整数で指定してください',
            'enterprise_id.exists' => '指定された出展者IDは存在しません',
        ];
    }
}
