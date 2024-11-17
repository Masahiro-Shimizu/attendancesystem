@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>承認/差し戻し履歴 ({{ $year }}年{{ $month }}月)</h1>

    <!-- 月選択フォーム -->
    <form method="GET" action="{{ route('admin.history', ['year' => $year, 'month' => $month]) }}" class="form-inline mb-3">
        <label for="month" class="mr-2">月選択:</label>
        <input type="month" name="month" id="month" value="{{ sprintf('%04d-%02d', $year, $month) }}" class="form-control mr-2">
        <button type="submit" class="btn btn-primary">表示</button>
    </form>

    <!-- データがある場合 -->
    @if($histories->isNotEmpty())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>申請タイプ</th>
                    <th>申請ID</th>
                    <th>管理者</th>
                    <th>アクション</th>
                    <th>コメント</th>
                    <th>日時</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->application_type_jp }}</td>
                        <td>{{ $history->application_id }}</td>
                        <td>{{ $history->admin->name }}</td>
                        <td>{{ $history->action }}</td>
                        <td>{{ $history->comment }}</td>
                        <td>{{ $history->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <!-- データがない場合 -->
        <div class="alert alert-info">
            {{ $noDataMessage }}
        </div>
    @endif
</div>
@endsection
