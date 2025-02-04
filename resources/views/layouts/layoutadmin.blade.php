<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- タイトル: アプリケーション名またはデフォルト値 -->
    <title>{{ config('app.name','勤怠管理システム') }}</title>

    <!-- jQueryの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <!--<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">-->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->

</head>
<body>
    <div id="app">
        <!-- ナビゲーションバー -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- アプリケーション名へのリンク -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name','勤怠管理システム') }}
                </a>
                <!-- ナビゲーションの折りたたみボタン -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- 左側のナビゲーション -->
                    <ul class="navbar-nav me-auto">
                        <!-- ホームへのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.home') }}">ホーム</a>
                        </li>

                        <!-- ユーザー表示へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users') }}">ユーザー</a>
                        </li>

                        <!-- 履歴表示へのリンク -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.history', ['year' => now()->year, 'month' => now()->month]) }}">履歴</a>
                        </li>

                    </ul>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

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

    <!-- スクリプト -->
    @yield('scripts')
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}
        });
    </script>

    @include('layouts.footer')
</body>
</html>
