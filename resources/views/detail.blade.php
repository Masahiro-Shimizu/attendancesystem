@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">打刻詳細</div>

                <div class="card-body">
                    <!--
                    <p><strong>出勤時刻：</strong> {{ $time->punchIn ?? '記録なし'}}</p>
                    <p><strong>退勤時刻：</strong> {{ $time->punchOut ?? '記録なし'}}</p>
                    -->
                    <p>出勤時間: {{ $time->punchIn ? $time->punchIn->diffForHumans() : '出勤時間なし' }}</p>
                    <p>退勤時間: {{ $time->punchOut ? $time->punchOut->diffForHumans() : '退勤時間なし' }}</p>

                    <p><strong>コメント:</strong> {{ $time->comments ?? 'コメントはありません' }}</p>

                    <a href="{{ route('home') }}" class="btn btn-primary">戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection