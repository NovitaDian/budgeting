@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header"><h6>Add New Goal</h6></div>
        <div class="card-body">

            <form action="{{ route('goals.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Goal Name</label>
                    <input type="text" class="form-control" name="goal_name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Target Amount</label>
                    <input type="number" class="form-control" name="target_amount" required min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" class="form-control" name="deadline">
                </div>

                <button type="submit" class="btn btn-primary">Save Goal</button>
                <a href="{{ route('goals.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
