@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>

    <!-- 月報一覧 -->
    <h2>月報一覧</h2>
    @if($monthlyReports->isEmpty())
        <p>月報の申請はありません。</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>月</th>
                    <th>状態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyReports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ $report->month }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            <form action="{{ route('monthly_report.approve', $report->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">承認</button>
                            </form>
                            <form action="{{ route('monthly_report.reject', $report->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">却下</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- 休暇・有給・欠勤申請一覧 -->
    <h2>休暇・有給・欠勤申請一覧</h2>
    @if($leaveRequests->isEmpty())
        <p>申請はありません。</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>申請タイプ</th>
                    <th>日付</th>
                    <th>状態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->type }}</td>
                        <td>{{ $request->date }}</td>
                        <td>{{ $request->status }}</td>
                        <td>
                            <form action="{{ route('admin.leave_requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">承認</button>
                            </form>
                            <form action="{{ route('admin.leave_requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">却下</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
