<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'punchIn' => 'required|date',
            'punchOut' => 'nullable|date',
            'comments' => 'nullable|string|max:255',
            'method' => 'required|string',
            'break_time' => 'nullable|integer|min:0',
        ];
    }
}
