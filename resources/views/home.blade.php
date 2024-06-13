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
                        <dt class="col-sm-9">{{ Auth::user()->name }}</dt>
                        <dt class="col-sm-3">ログインID</dt>
                        <dt class="col-sm-9">{{ Auth::user()->id }}</dt>
                    </dl>
                    @if (session('status'))
                        <div class="alert alert-success" id="popup-message">{{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" id="popup-message">{{ session('error') }}
                        </div>
                    @endif
                    <div class="button-form">                        
                        <ul>
                            <li>
                                <form action="{{ route('punch-in') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-primary">出勤</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('punch-out') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-success">退勤</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popupMessage = document.getElementById('popup-message');
        if(popupMessage){
            alert(popupMessage.textContent);
            popupMessage.remove();
        }
    });
</script>
@endsection