@extends

@section('content')
<div class="container">
    <h2>プロフィール編集</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$user->name )}}" require>
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',$user->email )}}" require>
        </div>

        <div class="form-group">
            <label for="password">新しいパスワード（変更する場合のみ入力）</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">新しいパスワード（確認）</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">更新する</button>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">キャンセル</a>
    </form>
</div>
@endsection
        