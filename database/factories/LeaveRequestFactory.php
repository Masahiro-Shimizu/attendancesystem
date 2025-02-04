<?php

namespace Database\Factories;
// database/factories/LeaveRequestFactory.php

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * LeaveRequestFactory
 *
 * LeaveRequest モデル用のテストデータを生成するファクトリ。
 * ユーザーとの関連やダミーデータを自動生成します。
 *
 * @package Database\Factories
 */
class LeaveRequestFactory extends Factory
{
    /**
     * このファクトリで生成する対象モデル。
     *
     * @var string
     */
    protected $model = LeaveRequest::class;

    /**
     * LeaveRequest モデルのデータ構造を定義します。
     *
     * @return array<string, mixed>
     *     モデルのフィールドに対応するダミーデータ。
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),        // 関連するユーザーを生成
            'type' => 'paid_leave',            // 固定値：有給休暇
            'start_date' => $this->faker->date(), // ランダムな開始日
            'end_date' => $this->faker->date(),   // ランダムな終了日
            'reason' => $this->faker->sentence,  // ランダムな申請理由
            'status' => 'pending',             // 固定値：保留中
        ];
    }
}

