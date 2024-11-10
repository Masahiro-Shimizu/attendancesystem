@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>勤怠詳細</h1>
    <div id="calendar"></div>

    <!-- 勤怠詳細を表示するモーダル -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">打刻詳細</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>出勤時刻:</strong> <span id="punchInTime"></span></p>
                    <p><strong>退勤時刻:</strong> <span id="punchOutTime"></span></p>
                    <p><strong>休憩時間:</strong> <span id="breakTime"></span></p>
                    <p><strong>合計勤務時間:</strong> <span id="totalHours"></span></p>
                    <p><strong>コメント:</strong> <span id="comments"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // FullCalendarの設定
    $('#calendar').fullCalendar({
        locale: 'ja',
        selectable: true,
        select: function(start) {
            const selectedDate = start.format('YYYY-MM-DD');

            console.log("AJAXリクエスト開始: ", selectedDate);
            $.ajax({
                url: '{{ route("times.dateDetail") }}',
                method: 'GET',
                data: { date: selectedDate },
                success: function(response) {
                    if (response.status === 'success') {
                        const data = response.data;
                        $('#punchInTime').text(data.punchIn || 'N/A');
                        $('#punchOutTime').text(data.punchOut || 'N/A');
                        $('#breakTime').text(data.break_time || 'N/A');
                        $('#totalHours').text(data.total_hours || 'N/A');
                        $('#comments').text(data.comments || 'N/A');
                        $('#attendanceModal').modal('show');
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    // URLにshowModal=trueがある場合にモーダルを自動表示
    const urlParams = new URLSearchParams(window.location.search);
    const showModal = urlParams.get('showModal');

    if (showModal === 'true') {
        $('#attendanceModal').modal('show');
    }
});
</script>
@endsection
