@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>申請一覧</h1>

    <!-- 申請データを表示するテーブル -->
    <table class="table">
        <thead>
            <tr>
                <th>種類</th> <!-- 申請タイプ -->
                <th>開始日</th>  <!-- 開始日 -->
                <th>終了日</th>  <!-- 終了日 -->
                <th>理由</th> <!-- 理由 -->
                <th>ステータス</th> <!-- ステータス -->
            </tr>
        </thead>
        <tbody>
            <!-- 申請データのループ処理 -->
            @foreach ($leaveRequests as $request)
                <tr>
                    <!-- 申請タイプの判定 -->
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
                    <!-- 開始日 -->
                    <td>{{ $request->start_date }}</td>
                    <!-- 終了日: 空の場合は "なし" -->
                    <td>{{ $request->end_date ?? 'なし' }}</td>
                    <!-- 理由 -->
                    <td>{{ $request->reason }}</td>

                    <!-- ステータスの判定 -->
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
