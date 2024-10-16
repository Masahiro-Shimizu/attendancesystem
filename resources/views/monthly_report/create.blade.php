@extends('layouts.layoutadmin')

@section('content')
<div class="container">
    <h2>月報の申請</h2>
    
    <form method="POST" action="{{ route('monthly_report.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="month">申請する月</label>
            <input type="month" id="month" name="month" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">申請</button>
    </form>
</div>
@endsection
