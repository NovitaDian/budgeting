@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">





        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0">Edit Transaction</h6>
                            <a href="{{ route($transaction->type === 'income' ? 'transactions.income' : 'transactions.expense') }}"
                                class="btn btn-secondary btn-sm">
                                Back
                            </a>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Date --}}
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ old('date', $transaction->date ? $transaction->date->format('Y-m-d') : '') }}" required>
                                </div>

                                {{-- Category --}}
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }} ({{ ucfirst($category->type) }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Amount --}}
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" name="amount" step="0.01" class="form-control"
                                        value="{{ old('amount', $transaction->amount) }}" required>
                                </div>

                                {{-- Note --}}
                                <div class="mb-3">
                                    <label class="form-label">Note (Optional)</label>
                                    <textarea name="note" class="form-control" rows="2">{{ old('note', $transaction->note) }}</textarea>
                                </div>

                                {{-- Existing Receipt (Preview) --}}
                                @if ($transaction->receipt_image)
                                <div class="mb-3">
                                    <label class="form-label d-block">Current Receipt</label>
                                    <img src="{{ asset('storage/' . $transaction->receipt_image) }}" width="120" class="rounded border">
                                </div>
                                @endif

                                {{-- Upload New Receipt --}}
                                <div class="mb-3">
                                    <label class="form-label">Replace Receipt (Optional)</label>
                                    <input type="file" name="receipt_image" class="form-control" accept="image/*">
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="btn btn-primary w-100">Update Transaction</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@endsection