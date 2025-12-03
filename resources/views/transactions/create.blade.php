@extends('layouts.user_type.auth')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add Transaction</h6>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Date --}}
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>

                        {{-- Category --}}
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="category" class="form-control" required>
                                <option value="">-- Choose Type First --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                        </div>

                        {{-- Note --}}
                        <div class="mb-3">
                            <label class="form-label">Note (optional)</label>
                            <input type="text" name="note" class="form-control" placeholder="e.g Salary, Lunch, Gas">
                        </div>

                        {{-- Receipt Image --}}
                        <div class="mb-3">
                            <label class="form-label">Receipt Image (optional)</label>
                            <input type="file" name="receipt_image" class="form-control">
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary w-100">Save Transaction</button>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Filter category based on selected type --}}
<script>
    document.getElementById('type').addEventListener('change', function () {
        let selectedType = this.value;
        let categorySelect = document.getElementById('category');

        Array.from(categorySelect.options).forEach(option => {
            option.hidden = option.dataset.type !== selectedType && option.value !== "";
        });

        categorySelect.value = ""; 
    });
</script>

@endsection
