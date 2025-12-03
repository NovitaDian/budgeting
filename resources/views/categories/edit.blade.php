@extends('layouts.user_type.auth')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add New Category</h6>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        {{-- Category Name --}}
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Food, Salary, Transport" required>
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary w-100">Save Category</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
