<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestRequest extends BaseRequest
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
            'reason' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => '休暇、有給、欠勤のいずれかをご選択ください。',
            'type.in' => '選択された休暇タイプが無効です。',
            'start_date.required' => '開始日を入力してください。',
            'start_date.date' => '開始日は有効な日付で入力してください。',
            'end_date.date' => '終了日は有効な日付で入力してください。',
            'end_date.after_or_equal' => '終了日は開始日と同じかそれ以降の日付を入力してください。',
            'reason.string' => '理由は文字列で入力してください。',
            'reason.max' => '理由は500文字以内で入力してください。',
        ];
    }
}
