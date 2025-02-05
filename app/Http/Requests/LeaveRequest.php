<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends BaseRequest
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
            'type' => 'required|in:vacation,paid_leave,absent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => '休暇、有給、欠勤のいずれかをご選択ください。',
            'start_date.required|date' => '日付を入力してください',
            'end_date.required|date' => '日付を入力してください',
            'reason.nullable' => ''
        ];
    }
}
