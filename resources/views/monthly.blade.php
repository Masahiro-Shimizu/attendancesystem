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
                        <th>日付</th>
                        <th>出勤時間</th>
                        <th>退勤時間</th>
                        <th>合計勤務時間</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($times as $time)
                        <tr>
                            <td>{{ $time->punchIn->format('Y-m-d') }}</td>
                            <td>{{ $time->punchIn->format('H:i') }}</td>
                            <td>{{ $time->punchOut ? $time->punchOut->format('H:i') : '退勤なし' }}</td>
                            <td>
                                @if($time->punchOut)
                                    {{ $time->punchIn->diffInHours($time->punchOut) }}時間
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