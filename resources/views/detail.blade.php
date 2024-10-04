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

                    <!--<p><strong>出勤時間:</strong> {{ $times->punchIn ? $times->punchIn->diffForHumans() : '出勤時間なし' }}</p>
                    <p><strong>退勤時間:</strong> {{ $times->punchOut ? $times->punchOut->diffForHumans() : '退勤時間なし' }}</p>-->

                    <p><strong>コメント:</strong> {{ $times->comments ?? 'コメントはありません' }}</p>

                    <a href="{{ route('times.edit', ['id' => $times->id]) }}" class="btn btn-primary">編集する</a>
                    <a href="{{ route('home') }}" class="btn btn-primary">戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection