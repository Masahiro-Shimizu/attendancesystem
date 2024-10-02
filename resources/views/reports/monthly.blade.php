@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>月報: {{ $year }}年{{ $month }}月</h2>
            
            <form method="GET" action="{{ route('monthly-report') }}" class="form-inline">
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
                        <th>日付</th>
                        <th>出勤時間</th>
                        <th>退勤時間</th>
                        <th>勤務時間</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        // $timesがnullでないか確認する
                        $timeEntry = $times ? $times->firstWhere('punchIn', '>=', $date->startOfDay())
                                ->firstWhere('punchIn', '<', $date->endOfDay()) 
                                : null;
                    @endphp

                    @if($timeEntry)
                        <p>出勤時間: {{ $timeEntry->punchIn }}</p>
                        <p>退勤時間: {{ $timeEntry->punchOut }}</p>
                    @else
                        <p>出勤時間: なし</p>
                        <p>退勤時間: なし</p>
                    @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
