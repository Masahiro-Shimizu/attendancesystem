@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>勤怠カレンダー</h1>
    <div id="calendar"></div>
</div>
@endsection

@section('scripts')
<script>
    /**
     * カレンダーの初期化と設定
     */
    $(document).ready(function() {
        // 日本語ロケール設定
        moment.updateLocale('ja', {
            months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            weekdays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
            weekdaysShort: ['日', '月', '火', '水', '木', '金', '土'],
            weekdaysMin: ['日', '月', '火', '水', '木', '金', '土'],
            longDateFormat: {
                LT: 'HH:mm',
                LTS: 'HH:mm:ss',
                L: 'YYYY/MM/DD',
                LL: 'YYYY年M月D日',
                LLL: 'YYYY年M月D日 HH:mm',
                LLLL: 'YYYY年M月D日 dddd HH:mm'
            },
            calendar: {
                sameDay: '[今日] LT',
                nextDay: '[明日] LT',
                nextWeek: '[来週]dddd LT',
                lastDay: '[昨日] LT',
                lastWeek: '[前週]dddd LT',
                sameElse: 'L'
            },
            meridiemParse: /午前|午後/,
            isPM: function(input) {
                return input === '午後';
            },
            meridiem: function(hour, minute, isLower) {
                return hour < 12 ? '午前' : '午後';
            },
            week: {
                dow: 0, // 日曜日を週の始まりに設定
                doy: 4  // 年の最初の週は1月4日を含む週
            }
        });

        // FullCalendarの初期化
        $('#calendar').fullCalendar({
            locale: 'ja',  // 日本語化
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: '今日',
                month: '月',
                week: '週',
                day: '日'
            },
            selectable: true,
            /**
             * 日付選択時の動作
             * @param {object} start - 選択された開始日
             */
            select: function(start) {
                const selectedDate = start.format('YYYY-MM-DD');
                
                $.ajax({
                    url: '/attendancesystem/public/times/get-id-by-date',
                    method: 'GET',
                    data: { date: selectedDate },
                    success: function(response) {
                        if (response.status === 'success') {
                            const id = response.data.id;
                            window.location.href = `/attendancesystem/public/times/detail/${id}`;
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response:', xhr.responseText);
                        alert('エラーが発生しました: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection
