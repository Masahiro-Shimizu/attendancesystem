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
                        <td>
                            @if ($report->status == 'pending')
                                保留
                            @elseif ($report->status == 'approved')
                                承認済み
                            @elseif ($report->status == 'rejected')
                                却下
                            @else
                                不明
                            @endif
                        </td>
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
                        <td>
                            @if ($request->type == 'paid_leave')
                                有給休暇
                            @elseif ($request->type == 'sick_leave')
                                病気休暇
                            @elseif ($request->type == 'absence')
                                欠勤
                            @else
                                不明
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($request->date)->format('Y年m月d日') }}</td> <!-- 日付を日本語形式で表示 -->
                        <td>
                            @if ($request->status == 'pending')
                                保留
                            @elseif ($request->status == 'approved')
                                承認済み
                            @elseif ($request->status == 'rejected')
                                却下
                            @else
                                不明
                            @endif
                        </td>
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
