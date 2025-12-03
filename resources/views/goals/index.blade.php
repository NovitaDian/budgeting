@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success text-white font-weight-bold">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>My Savings Goals</h6>
            <a href="{{ route('goals.create') }}" class="btn btn-primary btn-sm">+ Add Goal</a>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-3">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Goal</th>
                            <th>Progress</th>
                            <th>Target</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($goals as $i => $goal)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ ucfirst($goal->goal_name) }}</td>

                            <td class="align-middle">
                                @php
                                $percent = $goal->target_amount > 0
                                ? round(($goal->current_amount / $goal->target_amount) * 100, 2)
                                : 0;
                                @endphp

                                <div class="d-flex align-items-center">
                                    <span class="me-2 text-xs font-weight-bold">{{ $percent }}%</span>
                                    <div class="progress" style="width: 120px;">
                                        <div class="progress-bar 
                                            {{ $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success') }}"
                                            role="progressbar"
                                            style="width: {{ min($percent,100) }}%;">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>Rp {{ number_format($goal->target_amount,0,',','.') }}</td>

                            <td>{{ $goal->deadline ?? '-' }}</td>

                            <td>
                                @if($percent >= 100)
                                <span class="badge bg-danger">Completed</span>
                                @else
                                <span class="badge bg-success">Ongoing</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('goals.edit', $user->id) }}"
                                    class="mx-3"
                                    data-bs-toggle="tooltip"
                                    data-bs-original-title="Edit user">
                                    <i class="fas fa-user-edit text-secondary"></i>
                                </a>
                                <a href="{{ route('goals.show', $user->id) }}"
                                    class="mx-3"
                                    data-bs-toggle="tooltip"
                                    data-bs-original-title="Edit user">
                                    <i class="fas fa-eye text-secondary"></i>
                                </a>

                                <form action="{{ route('goals.destroy', $user->id) }}" method="POST" class="d-inline">
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
                            <td colspan="7" class="text-center text-muted">No goals yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection