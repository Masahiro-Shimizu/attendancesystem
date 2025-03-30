@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>プロフィール情報</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <tr>
            <th>名前</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $user->email }}</td>
        </tr>
    </table>

    <a href="{{ route('profile.edit') }}" class="btn btn-primary">編集する</a>
    <a href="{{ route('home') }}" class="btn btn-secondary">戻る</a>
</div>
@endsection
