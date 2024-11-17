@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>月報詳細</h1>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $report->id }}</td>
        </tr>
        <tr>
            <th>ユーザー名</th>
            <td>{{ $report->user->name }}</td>
        </tr>
        <tr>
            <th>対象月</th>
            <td>{{ $report->month }}</td>
        </tr>
        <tr>
            <th>提出日</th>
            <td>{{ $report->created_at->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <th>状態</th>
            <td>
                @if ($report->status == 'pending')
                    保留中
                @elseif ($report->status == 'approved')
                    承認済み
                @elseif ($report->status == 'rejected')
                    却下
                @endif
            </td>
        </tr>
        <tr>
            <th>理由</th>
            <td>{{ $report->reason ?? '理由なし' }}</td>
        </tr>
        <tr>
            <th>差し戻しコメント</th>
            <td>{{ $report->reject_comment ?? 'コメントなし' }}</td>
        </tr>
    </table>
    <a href="{{ route('admin.home') }}" class="btn btn-secondary">戻る</a>
</div>
@endsection
