@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">勤怠入力</div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">名前</dt>
                        <dd class="col-sm-9">{{ Auth::user()->name }}</dd>
                        <dt class="col-sm-3">ログインID</dt>
                        <dd class="col-sm-9">{{ Auth::user()->id }}</dd>
                    </dl>
                    <div id="popup-message" style="display: none;" class="alert"></div>
                    <div class="button-form">                        
                        <ul>
                            <li>
                                <form id="punchin-form" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">出勤</button>
                                </form>
                            </li>
                            <li>
                                <form id="punchout-form" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">退勤</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    

                </div>
            </div>
        </div>
    </div>
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

        function showMessage(message) {
            const popupMessage = $('#popup-message');
            popupMessage.removeClass('alert-success alert-danger');
            if(message.includes('error')){
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
    });
</script>
@endsection
