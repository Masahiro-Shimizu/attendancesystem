@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>

    <!-- 申請一覧 -->
    <h2>申請一覧</h2>
    @if($applications->isEmpty())
        <p>申請はありません。</p>
    @else
        <table class="table table">
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
                                <!-- 差し戻しボタン -->
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $application->id }}">
                                    却下
                                </button>

                                <!-- 差し戻し用のモーダル -->
                                <div class="modal fade" id="rejectModal-{{ $application->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $application->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('monthly_report.reject', $application->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $application->id }}">差し戻しコメントを入力</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea name="reject_comment" class="form-control" rows="4" placeholder="コメントを入力してください"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                                    <button type="submit" class="btn btn-danger">差し戻し</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @elseif ($application instanceof \App\Models\LeaveRequest)
                                <form action="{{ route('admin.leave_requests.approve', $application->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">承認</button>
                                </form>
                                <!-- 差し戻しボタン -->
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $application->id }}">
                                    却下
                                </button>

                                <!-- 差し戻し用のモーダル -->
                                <div class="modal fade" id="rejectModal-{{ $application->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $application->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.leave_requests.reject', $application->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $application->id }}">差し戻しコメントを入力</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea name="reject_comment" class="form-control" rows="4" placeholder="コメントを入力してください"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                                    <button type="submit" class="btn btn-danger">差し戻し</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    });
</script>
@endsection
