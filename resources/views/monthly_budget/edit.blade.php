@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Edit Monthly Budget</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('monthly-budget.update', $budget->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Month --}}
                <div class="mb-3">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select" required>
                        @foreach($months as $m)
                        <option value="{{ $m }}" {{ old('month', $budget->month) == $m ? 'selected' : '' }}>
                            {{ $m }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Year --}}
                <div class="mb-3">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control"
                        value="{{ old('year', $budget->year) }}" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{-- Cek old value (case create gagal validation) lalu fallback ke edit value --}}
                            {{ old('category_id', $budget->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                {{-- Planned Amount --}}
                <div class="mb-3">
                    <label class="form-label">Budget Target (Rp)</label>
                    <input type="number" name="planned_amount" required class="form-control"
                        value="{{ old('planned_amount', $budget->planned_amount) }}" min="0">
                </div>

                <button type="submit" class="btn btn-warning mt-3">Update</button>
                <a href="{{ route('monthly-budget.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection