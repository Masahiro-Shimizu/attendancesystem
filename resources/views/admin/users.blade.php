@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h1>ユーザー管理</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>ロール</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    @if($user->role !== 'admin')
                    <form method="POST" action="{{ route('admin.promote', $user->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">昇格</button>
                    </form>
                    @else
                    <span class="text-muted">管理者</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
