@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">打刻詳細</div>

                <div class="card-body">
                    <p><strong>出勤時刻：</strong> {{ $times->punchIn ? $times->punchIn->format('Y-m-d H:i') : '記録なし' }}</p>
                    <p><strong>退勤時刻：</strong> {{ $times->punchOut ? $times->punchOut->format('Y-m-d H:i') : '記録なし' }}</p>

                    <dt class="col-sm-3">休憩時間</dt>
                    <dd class="col-sm-9">
                        @if($times->break_time)
                            @php
                                $hours = intdiv($times->break_time, 60); // 時間部分
                                $minutes = $times->break_time % 60; // 分部分
                            @endphp
                            {{ sprintf('%02d:%02d', $hours, $minutes) }} <!-- "時間:分" の形式で表示 -->
                        @else
                            休憩なし
                        @endif
                    </dd>

                    <dt class="col-sm-3">合計勤務時間</dt>
                    <dd class="col-sm-9">
                        @if($times->punchOut)
                            @php
                                // 出勤と退勤の差を計算（分単位）
                                $totalMinutes = $times->punchIn->diffInMinutes($times->punchOut);
            
                                // 休憩時間を差し引く
                                $workMinutes = $totalMinutes - ($times->break_time ?? 0);
            
                                // 時間と分に変換
                                $workHours = intdiv($workMinutes, 60); // 時間部分
                                $workRemainingMinutes = $workMinutes % 60; // 分部分
                            @endphp
                            {{ sprintf('%02d:%02d', $workHours, $workRemainingMinutes) }}  <!-- "時間:分" の形式で表示 -->
                            @else
                                退勤が記録されていません
                            @endif
                        </dd>

                        <p><strong>コメント:</strong> {{ $times->comments ?? 'コメントはありません' }}</p>

                    <a href="{{ route('times.edit', ['id' => $times->id]) }}" class="btn btn-primary">編集する</a>
                    <a href="{{ route('home') }}" class="btn btn-primary">戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection