@extends('layouts.layout')

@section('content')
<div class="container">
    <h1 class="mb-4">休暇申請</h1>

    <form action="{{ route('leave_requests.store') }}" method="POST">
        @csrf

        <!-- 申請の種類 -->
        <div class="form-group mb-3">
            <label for="type" class="form-label">申請の種類</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="">選択してください</option>
                <option value="vacation" {{ old('type') == 'vacation' ? 'selected' : '' }}>休暇</option>
                <option value="paid_leave" {{ old('type') == 'paid_leave' ? 'selected' : '' }}>有給休暇</option>
                <option value="absent" {{ old('type') == 'absent' ? 'selected' : '' }}>欠勤</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 開始日 -->
        <div class="form-group mb-3">
            <label for="start_date" class="form-label">開始日</label>
            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 終了日 -->
        <div class="form-group mb-3">
            <label for="end_date" class="form-label">終了日</label>
            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 理由 -->
        <div class="form-group mb-4">
            <label for="reason" class="form-label">理由 (任意)</label>
            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3">{{ old('reason') }}</textarea>
            @error('reason')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary w-10">申請</button>
    </form>
</div>
@endsection
