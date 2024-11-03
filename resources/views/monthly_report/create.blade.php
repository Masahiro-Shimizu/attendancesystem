@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>月報の申請</h2>
    <div id="popup-message" style="display: none;" class="alert"></div>

    <form id="monthly-report-form" method="POST" action="{{ route('monthly_report.store') }}">
        @csrf

        <div class="form-group">
            <label for="month">申請する月</label>
            <input type="month" id="month" name="month" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">申請</button>
    </form>

    <!-- 差し戻し理由の表示 -->
    @if ($latestApplication && $latestApplication->status == 'rejected')
    <div class="mt-3 alert alert-warning">
        <strong>申請が却下されました</strong><br>
        <strong>却下日時:</strong> {{ $latestApplication->updated_at->format('Y年m月d日 H:i') }}<br>
        <strong>管理者:</strong> {{ $latestApplication->rejected_by ?? '不明' }}<br>
        <strong>理由:</strong> {{ $latestApplication->reject_comment }}
    </div>
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

        // フォーム送信処理
        $('#monthly-report-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("monthly_report.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#popup-message').text(response.message).addClass('alert-success').show();
                },
                error: function(xhr) {
                    $('#popup-message').text('申請に失敗しました。').addClass('alert-danger').show();
                }
            });
        });
    });
</script>
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

        // 月報申請フォームの送信イベント
        $('#monthly-report-form').on('submit', function(event) {
            event.preventDefault();  // フォームのデフォルト送信を無効化
            $.ajax({
                url: '{{ route("monthly_report.store")}}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log("成功:", response); // デバッグ用
                    showMessage(response.message, 'success');
                },
                error: function(xhr) {
                    console.log("エラー:", xhr); // デバッグ用
                    showMessage('エラーが発生しました。再度お試しください。', 'danger');
                }
            });
        });

        // ポップアップメッセージを表示するための関数
        function showMessage(message, type) {
            const popupMessage = $('#popup-message');
            popupMessage.removeClass('alert-success alert-danger').addClass('alert-' + type);
            popupMessage.text(message);
            popupMessage.show();

            setTimeout(function() {
                popupMessage.fadeOut();
            }, 3000); // 3秒後にフェードアウト
        }
    });
</script>
@endsection

