<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name','勤怠管理システム') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    <!--<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">-->
    <!-- jQuery, Moment.js, FullCalendarの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name','勤怠管理システム') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- ホームへのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">ホーム</a>
                        </li>
                    @if (Auth::check()) <!-- ログインしているか確認 -->    
                        <!-- 勤怠詳細へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('times.calendar', ['id' => Auth::id()]) }}">勤怠詳細</a>
                        </li>

                        <!-- 勤怠編集へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('times.edit', ['id' => Auth::id()]) }}">勤怠編集</a> <!-- idを渡す -->
                        </li>

                        <!-- 月報へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('times.monthly', ['id' => Auth::id()]) }}">月報</a> <!-- idを渡す -->
                        </li>

                        <!-- 月報申請へのリンクを追加 -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('monthly_report.create') }}">月報申請</a>
                        </li>

                         <!-- 申請一覧へのリンク -->
                         <li class="nav-item">
                            <a class="nav-link" href="{{ route('leave_requests.index') }}">申請一覧</a>
                        </li>

                        <!-- 休暇・有給・欠勤申請へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('leave_requests.create') }}">申請作成</a>
                        </li>
                    @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}
        });
    </script>
</body>
</html>
