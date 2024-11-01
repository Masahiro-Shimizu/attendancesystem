@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>

    <!-- 申請一覧 -->
    <h2>申請一覧</h2>
    @if($applications->isEmpty())
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
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->user->name }}</td>
                        <td>
                            @if ($application instanceof \App\Models\MonthlyReport)
                                月報申請
                            @elseif ($application instanceof \App\Models\LeaveRequest)
                                @if($application->type == 'paid_leave')
                                    有給休暇
                                @elseif($application->type == 'vacation')
                                    休暇
                                @elseif($application->type == 'absence')
                                    欠勤
                                @endif
                            @endif
                        </td>
                        <!-- 月報申請の場合はmonth、有給休暇申請の場合はstart_dateを表示 -->
                        <td>
                            @if ($application instanceof \App\Models\MonthlyReport)
                                {{ $application->month }}
                            @elseif ($application instanceof \App\Models\LeaveRequest)
                                {{ $application->start_date }}
                            @endif
                        </td>
                        <td>
                            @if ($application->status == 'pending')
                                保留中
                            @elseif ($application->status == 'approved')
                                承認済み
                            @elseif ($application->status == 'rejected')
                                却下
                            @endif
                        </td>
                        <td>
                            @if ($application instanceof \App\Models\MonthlyReport)
                                <form action="{{ route('monthly_report.approve', $application->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">承認</button>
                                </form>
                                <form action="{{ route('monthly_report.reject', $application->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">却下</button>
                                </form>
                            @elseif ($application instanceof \App\Models\LeaveRequest)
                                <form action="{{ route('admin.leave_requests.approve', $application->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">承認</button>
                                </form>
                                <form action="{{ route('admin.leave_requests.reject', $application->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">却下</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // CSRFトークンの設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 承認ボタンのクリックイベント
        $('.approve-btn').on('click', function() {
            const requestId = $(this).data('id');
            handleRequest(requestId, 'approve');
        });

        // 却下ボタンのクリックイベント
        $('.reject-btn').on('click', function() {
            const requestId = $(this).data('id');
            handleRequest(requestId, 'reject');
        });

        // 承認・却下処理の共通関数
        function handleRequest(requestId, action) {
            $.ajax({
                url: `/admin/leave_requests/${action}/${requestId}`,
                method: 'POST',
                success: function(response) {
                    // 成功した場合、ステータスを更新してメッセージを表示
                    $(`#request-${requestId} .status`).text(response.status);
                    showMessage(response.message, 'success');
                },
                error: function(xhr) {
                    showMessage('エラーが発生しました。再度お試しください。', 'danger');
                }
            });
        }

        // ポップアップメッセージを表示する関数
        function showMessage(message, type) {
            const popupMessage = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' + message + '</div>');
            $('body').append(popupMessage);
            setTimeout(function() {
                popupMessage.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }
    });
</script>
@endsection
