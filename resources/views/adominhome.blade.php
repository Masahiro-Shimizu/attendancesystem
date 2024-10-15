@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h2>承認待ちの月報</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>期間</th>
                <th>ステータス</th>
                <th>アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->user->name }}</td>
                <td>{{ $report->month }}</td>
                <td>{{ $report->status }}</td>
                <td>
                    <form method="POST" action="{{ route('monthly_report.approve', $report->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">承認</button>
                    </form>
                    <form method="POST" action="{{ route('monthly_report.reject', $report->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">却下</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
