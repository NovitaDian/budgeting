@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Add Monthly Budget</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('monthly-budget.store') }}" method="POST">
                @csrf

                {{-- Month --}}
                <div class="mb-3">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select" required>
                        @foreach($months as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Year --}}
                <div class="mb-3">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control"
                        value="{{ date('Y') }}" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $c)
                        <option value="{{ $c->id }}">{{ ucfirst($c->name) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Planned Amount --}}
                <div class="mb-3">
                    <label class="form-label">Budget Target (Rp)</label>
                    <input type="number" name="planned_amount" required class="form-control" min="0">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Budget</button>
                <a href="{{ route('monthly-budget.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection