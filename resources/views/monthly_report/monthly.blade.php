@extends('layouts.layout')

@section('content')

<div class="container">

    <div class="row">

        <div class="col-md-12">

            <h2>月報: {{ $year }}年{{ $month }}月</h2>

            <form method="GET" action="{{ route('times.monthly') }}" class="form-inline">

                <div class="form-group mb-2">
                    <label for="year">年: </label>
                    <select name="year" class="form-control ml-2">
                        @for ($y = Carbon\Carbon::now()->year - 5; $y <= Carbon\Carbon::now()->year; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="month">月: </label>
                    <select name="month" class="form-control ml-2">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mb-2 ml-2">表示</button>

            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>日付 (曜日)</th>
                        <th>出勤時間</th>
                        <th>退勤時間</th>
                        <th>実労働時間</th>
                        <th>休憩時間</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($times as $time)
                        @php
                            // 曜日を漢字で表す配列
                            $weekMap = ['日', '月', '火', '水', '木', '金', '土'];
                            $weekday = $weekMap[Carbon\Carbon::parse($time->punchIn)->dayOfWeek];
                        @endphp
                        <tr>
                            <td>{{ Carbon\Carbon::parse($time->punchIn)->format('Y年m月d日') }} ({{ $weekday }})</td>
                            <td>{{ $time->punchIn ? $time->punchIn->format('H:i') : '出勤なし' }}</td>
                            <td>{{ $time->punchOut ? $time->punchOut->format('H:i') : '退勤なし' }}</td>
                            <td>
                                @if($time->punchOut)
                                    @php
                                        $totalMinutes = $time->punchIn->diffInMinutes($time->punchOut);
                                        $workMinutes = $totalMinutes - ($time->break_time ?? 0);
                                        $workHours = intdiv($workMinutes, 60);
                                        $workRemainingMinutes = $workMinutes % 60;
                                    @endphp
                                    {{ sprintf('%02d:%02d', $workHours, $workRemainingMinutes) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($time->punchOut)
                                    {{ $time->break_time ? $time->break_time . '分' : '休憩なし' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection
