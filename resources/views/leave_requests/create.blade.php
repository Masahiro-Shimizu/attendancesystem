@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>休暇申請</h1>
    <form action="{{ route('leave_requests.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="type">申請の種類</label>
            <select name="type" id="type" class="form-control">
                <option value="vacation">休暇</option>
                <option value="paid_leave">有給休暇</option>
                <option value="absent">欠勤</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">開始日</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_date">終了日 (任意)</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <div class="form-group">
            <label for="reason">理由 (任意)</label>
            <textarea name="reason" id="reason" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">申請</button>
    </form>
</div>
@endsection
