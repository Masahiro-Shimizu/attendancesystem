@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">打刻編集画面</div>
            
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                <form action="{{ route('times.update',$time->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!--打刻方法-->
                    <div class="form-group">
                        <label for="method">打刻方法</label>
                        <select name="method" class="form-control" required>
                            <option value="自動" {{ $time->method == '自動' ? 'selected' : '' }}>自動</option>
                            <option value="手動" {{ $time->method == '手動' ? 'selected' : '' }}>手動</option>
                        </select>
                    </div>

                    <!-- 出勤時刻 -->
                    <div class="form-group">
                            <label for="punchIn">出勤時刻</label>
                            <input type="datetime-local" name="punchIn" class="form-control" value="{{ $time->punchIn->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <!-- 退勤時刻 -->
                    <div class="form-group">
                            <label for="punchOut">退勤時刻</label>
                            <input type="datetime-local" name="punchOut" class="form-control" value="{{ $time->punchOut ? $time->punchOut->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="form-group">
                            <label for="breakTime">休憩時間 (分)</label>
                            <input type="number" class="form-control" id="breakTime" name="break_time" value="{{ $time->break_time ? \Carbon\Carbon::parse($time->break_time)->format('H:i') : '' }}">
                    </div>

                    <!-- コメント -->
                    <div class="form-group">
                            <label for="comments">コメント</label>
                            <textarea name="comments" class="form-control" rows="3">{{ old('comments', $time->comments) }}</textarea>
                        </div>

                    <button type="submit" class="btn btn-primary">更新</button>
                    <a href="{{ route('times.index', $time->id) }}" class="btn btn-secondary">キャンセル</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection