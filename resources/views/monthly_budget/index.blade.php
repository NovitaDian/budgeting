@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="alert alert-success text-white font-weight-bold" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Monthly Budget Plan</h6>

            <a href="{{ route('monthly-budget.create') }}" class="btn btn-primary btn-sm">
                + Add Monthly Budget
            </a>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-3">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Category</th>
                            <th>Planned</th>
                            <th>Actual</th>
                            <th>Remaining</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($budgets as $index => $budget)
                        @php
                        $actual = $budget->actual_amount;
                        $planned = $budget->planned_amount;
                        $remaining = max($planned - $actual, 0);


                        $percentage = $budget->planned_amount > 0
                        ? round(($budget->actual_amount / $budget->planned_amount) * 100, 1)
                        : 0;

                        if ($percentage >= 100) {
                        $progressClass = 'bg-gradient-danger';
                        } elseif ($percentage >= 80) {
                        $progressClass = 'bg-gradient-warning';
                        } else {
                        $progressClass = 'bg-gradient-success';
                        }
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $budget->month }} {{ $budget->year }}</td>

                            <td>{{ ucfirst($budget->category->name ?? '-') }}</td>

                            <td>Rp {{ number_format($planned, 0, ',', '.') }}</td>

                            <td>Rp {{ number_format($actual, 0, ',', '.') }}</td>

                            <td>Rp {{ number_format($remaining, 0, ',', '.') }}</td>

                            <td class="align-middle text-center">

                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2 text-xs font-weight-bold">{{ $percentage }}%</span>

                                    <div style="width: 120px;">
                                        <div class="progress">
                                            <div
                                                class="progress-bar {{ $progressClass }}"
                                                role="progressbar"
                                                aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                style="width: {{ min($percentage, 100) }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>


                            {{-- Status --}}
                            <td class="text-center">
                                @if($percentage >= 100)
                                <span class="badge bg-danger text-white">Over Budget</span>
                                @elseif($percentage >= 80)
                                <span class="badge bg-warning text-dark">Near Limit</span>
                                @else
                                <span class="badge bg-success text-white">Safe</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <a href="{{ route('monthly-budget.edit', $budget->id) }}" class="btn btn-warning" title="Edit" style="padding: 5px;opacity: 0.8; margin-top:12px;">
                                    <i class="fas fa-pencil-alt "></i>
                                </a>

                                <form action="{{ route('monthly-budget.destroy', $budget->id) }}" class="d-inline" method="POST"
                                    onsubmit="return confirm('Delete this budget?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" title="Delete" style="padding: 5px;opacity: 0.8; margin-top:12px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection