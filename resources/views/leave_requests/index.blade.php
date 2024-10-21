@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>申請一覧</h1>
    <table class="table">
        <thead>
            <tr>
                <th>種類</th>
                <th>開始日</th>
                <th>終了日</th>
                <th>理由</th>
                <th>ステータス</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
            <tr>
                <td>{{ $request->type }}</td>
                <td>{{ $request->start_date }}</td>
                <td>{{ $request->end_date ?? 'なし' }}</td>
                <td>{{ $request->reason ?? 'なし' }}</td>
                <td>{{ $request->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
