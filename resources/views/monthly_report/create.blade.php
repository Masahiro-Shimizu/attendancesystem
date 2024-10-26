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

