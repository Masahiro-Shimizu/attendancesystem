<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends BaseRequest
{
    /**
     * リクエストが許可されているかを判断
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 認可が不要なら常にtrue
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge($this->commonRules(),[
        ]);
    }

    /**
     * カスタムメッセージを定義
     *
     * @return array
     */
    public function messages(): array
    {
        return array_merge($this->commonMessages(),[

        ]);
    }
}
