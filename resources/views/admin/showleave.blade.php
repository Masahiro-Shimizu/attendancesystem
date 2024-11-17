@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">申請詳細</div>
                <div class="card-body">
                    <p><strong>ユーザー名:</strong> {{ $leaveRequest->user->name }}</p>
                    <p><strong>申請タイプ:</strong> {{ $leaveRequest->type }}</p>
                    <p><strong>申請日付:</strong> {{ $leaveRequest->start_date }}</p>
                    <p><strong>ステータス:</strong> {{ $leaveRequest->status }}</p>
                    <p><strong>理由:</strong> {{ $leaveRequest->reason }}</p>
                    <a href="{{ route('admin.home') }}" class="btn btn-secondary">戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
