@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h6>Goal: {{ $goal->goal_name }}</h6>
        </div>

        <div class="card-body">
            <p><strong>Target:</strong> Rp {{ number_format($goal->target_amount,0,',','.') }}</p>
            <p><strong>Saved:</strong> Rp {{ number_format($goal->current_amount,0,',','.') }}</p>
            <p><strong>Deadline:</strong> {{ $goal->deadline ?? '-' }}</p>

            @php
            $percentage = round(($goal->current_amount / $goal->target_amount) * 100, 2);
            @endphp

            <div class="progress mb-4" style="height:25px;">
                <div class="progress-bar bg-gradient-info" style="width: {{ min($percentage,100) }}%;height:25px;">
                    {{ $percentage }}%
                </div>
            </div>

            <hr>

            <h6 class="mt-3">Add Contribution</h6>
            <form action="{{ route('goal.log.store', $goal->id) }}" method="POST">
                @csrf
                <div class="d-flex gap-2">
                    <input type="number" name="amount" class="form-control" placeholder="Amount" required>
                    <input type="date" name="date" class="form-control" required>
                    <button class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Logs --}}
    <div class="card">
        <div class="card-header">
            <h6>Savings History</h6>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goal->logs as $i => $log)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>Rp {{ number_format($log->amount,0,',','.') }}</td>
                        <td>{{ $log->date }}</td>
                        <td>
                            <form action="{{ route('goal.log.delete', $log->id) }}" method="POST"
                                onsubmit="return confirm('Delete log entry?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection