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
            @foreach ($leaveRequests as $request)
                <tr>
                    <td>
                        @if ($request->type === 'paid_leave')
                            有給休暇
                        @elseif ($request->type === 'vacation')
                            休暇
                        @elseif ($request->type === 'absent')
                            欠勤
                        @else
                            {{ $request->type }}
                        @endif
                    </td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date ?? 'なし' }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>
                        @if ($request->status === 'pending')
                            保留中
                        @elseif ($request->status === 'approved')
                            承認済み
                        @elseif ($request->status === 'rejected')
                            却下
                        @else
                            {{ $request->status }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
