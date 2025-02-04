<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Time;
use Carbon\Carbon;

/**
 * TestAttendanceSeeder
 *
 * 2024年10月のテスト用勤怠データを生成するシーダークラスです。
 * 平日のみデータを挿入し、土日と祝日を除外します。
 *
 * @package Database\Seeders
 */
class TestAttendanceSeeder extends Seeder
{
    /**
     * 対象期間の祝日リストを取得します。
     *
     * @return array
     *     祝日の配列を返します (例: ['2024-10-14'] )
     */
    private function getHolidays()
    {
        // 2024年の祝日（例として一部を設定）
        return [
            '2024-10-14', // 振替休日
            //'2024-09-23', // 秋分の日
        ];
    }

    /**
     * 勤怠データを生成
     * 
     * 平日のみデータを挿入し、土日および祝日はスキップします。
     */
    public function run()
    {
        $startDate = Carbon::create(2024, 10, 1); // 生成の開始日
        $endDate = Carbon::create(2024, 10, 31); // 生成の終了日

        $holidays = $this->getHolidays(); // 祝日リストを取得

        // 期間中の毎日をループ
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // 土日および祝日は除外
            if ($date->isWeekend() || in_array($date->toDateString(), $holidays)) {
                continue;
            }

            // 勤怠データを挿入
            Time::create([
                'user_id'   => 1, // テストデータ用にユーザーID 1を指定（適宜変更）
                'punchIn'   => $date->copy()->setTime(10, 0, 0), // 出勤時間を10:00に設定
                'punchOut'  => $date->copy()->setTime(19, 0, 0), // 退勤時間を19:00に設定
                'break_time' => '01:00:00', // 休憩時間を1時間に設定
                'comments'   => 'テストコメント', // 任意のコメント
            ]);
        }
    }
}
