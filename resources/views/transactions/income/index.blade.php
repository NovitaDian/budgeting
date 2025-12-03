@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">

                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Income List</h6>
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                            + Add Income
                        </a>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Date</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Category</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Note</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse ($transactions as $transaction)
                                    <tr>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                                            </p>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-gradient-success">{{ $transaction->category->name ?? '-' }}</span>
                                        </td>

                                        <td class="text-center">
                                            <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
                                        </td>

                                        <td class="text-center text-xs">
                                            {{ $transaction->note ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('transactions.edit', $user->id) }}"
                                                class="mx-3"
                                                data-bs-toggle="tooltip"
                                                data-bs-original-title="Edit user">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>

                                            <form action="{{ route('transactions.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link p-0 m-0"
                                                    onclick="return confirm('Delete this user?')">
                                                    <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-secondary">
                                            No income records found.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@endsection