@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">ホーム</div>
                <div class="card-body">
                    <dl class="row mb-3">
                        <dt class="col-sm-3">名前</dt>
                        <dd class="col-sm-9">{{ Auth::user()->name }}</dd>
                        <dt class="col-sm-3">ログインID</dt>
                        <dd class="col-sm-9">{{ Auth::user()->id }}</dd>
                    </dl>
                    <div id="popup-message" style="display: none;" class="alert"></div>
                    <h4>現在の日時</h4>
                    <p id="current-date"></p>
                    <p id="current-time"></p>

                    <div class="button-group">
                        <form id="punchin-form" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">出勤</button>
                        </form>
                        <form id="punchout-form" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">退勤</button>
                        </form>
                        <form id="breakstart-form" method="POST" action="{{ route('break-start') }}">
                            @csrf
                            <button type="submit" class="btn btn-info">休憩開始</button>
                        </form>
                        <form id="breakend-form" method="POST" action="{{ route('break-end') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">休憩終了</button>
                        </form>
                    </div>

                </div>

                <div class="row">
                <!-- 打刻履歴（左側） -->
                    <div class="col-md-6">
                    <h4>打刻履歴(3日分)</h4>
                    <ul class="list-group">
                    @foreach($items as $date => $records)
                        @php
                            // $recordsを配列として扱う
                            $recordsArray = $records->toArray();
                            usort($recordsArray, function ($a, $b) {
                            return strtotime($b['punchOut']) <=> strtotime($a['punchOut']);
                            });
                            $sortedRecords = collect($recordsArray);

                            // 曜日を漢字で表す配列
                            $weekMap = ['日', '月', '火', '水', '木', '金', '土'];
                            $weekday = $weekMap[Carbon\Carbon::parse($date)->dayOfWeek]; // 日付の曜日取得
                        @endphp
                        <li class="list-group-item">
                            <strong>{{ Carbon\Carbon::parse($date)->format('Y年m月d日') }}（{{ $weekday }}）</strong>
                            <p>出勤: {{ $records->first()->punchIn ?? '打刻はありません' }}</p>
                            <p>退勤: {{ $records->sortByDesc('punchOut')->first()->punchOut ?? '打刻はありません' }}</p>
                            <button class="btn btn-secondary btn-sm">
                                <a href="{{ route('times.detail', $records->first()->id) }}" style="color:white;">詳細</a>
                            </button>
                            <button class="btn btn-secondary btn-sm">
                                <a href="{{ route('times.edit', $records->first()->id) }}" style="color:white;">編集</a>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-6">
                <h4>通知</h4>
            <div class="notifications">
                @if ($notifications->isEmpty())
                    <p>通知はありません</p>
                @else
                    <ul>
                    @foreach ($notifications as $notification)
                        <li @if($notification->is_checked) style="color: gray;" @endif>
                            {{ $notification->message }}
                            @if (!$notification->is_checked)
                                <form method="POST" action="{{ route('notifications.check', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">確認済み</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @endif
            </div>
            </div>
@endsection

    @section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // 現在の日時を表示するための関数
    function updateTime() {
        const now = new Date();

        // 日付を表示
        const optionsDate = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        };
        const currentDate = now.toLocaleDateString('ja-JP', optionsDate);
        document.getElementById('current-date').innerText = currentDate;

        // 時刻を表示
        const optionsTime = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        const currentTime = now.toLocaleTimeString('ja-JP', optionsTime);  // 修正: 時刻は toLocaleTimeString で取得
        document.getElementById('current-time').innerText = currentTime;
    }

    // 毎秒ごとに現在の時刻を更新する
    setInterval(updateTime, 1000);

    // ページ読み込み時に最初の時刻を表示
    updateTime();

    // 出勤・退勤・休憩用のメッセージを表示するための関数
    function showMessage(message) {
        const popupMessage = $('#popup-message');
        popupMessage.removeClass('alert-success alert-danger');
        if (message.includes('error')) {
            popupMessage.addClass('alert-danger');
        } else {
            popupMessage.addClass('alert-success');
        }
        popupMessage.text(message);
        popupMessage.show();

        setTimeout(function() {
            popupMessage.fadeOut();
        }, 3000); // 3秒後にポップアップをフェードアウト
    }

    $(document).ready(function() {
        // CSRFトークンの設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 出勤
        $('#punchin-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("punch-in") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    showMessage(response.message);
                },
                error: function(xhr) {
                    showMessage('An error occurred.');
                }
            });
        });

        // 退勤
        $('#punchout-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("punch-out") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    showMessage(response.message);
                },
                error: function(xhr) {
                    showMessage('An error occurred.');
                }
            });
        });

        // 休憩開始
        $('#breakstart-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("break-start") }}',
                method: 'POST',
                success: function(response) {
                    showMessage(response.message);
                },
                error: function(xhr) {
                    showMessage('休憩開始に失敗しました。');
                }
            });
        });

        // 休憩終了
        $('#breakend-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("break-end") }}',
                method: 'POST',
                success: function(response) {
                    showMessage(response.message);
                },
                error: function(xhr) {
                    showMessage('休憩終了に失敗しました。');
                }
            });
        });
    });

    function markNotificationAsRead(notificationId) {
    $.ajax({
        url: `/notifications/check/${notificationId}`,
        method: 'POST',  // 必ず POST メソッドを指定
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr) {
            alert('エラーが発生しました。');
        }
    });
}
</script>
@endsection