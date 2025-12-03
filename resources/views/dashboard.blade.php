@extends('layouts.user_type.auth')

@section('content')

{{-- ======================== --}}
{{-- FILTER TAHUN --}}
{{-- ======================== --}}
<div class="row mb-4">
    <div class="col-md-3">
        <form method="GET" action="{{ route('dashboard') }}">
            <label class="form-label">Filter Tahun</label>
            <select name="year" class="form-control" onchange="this.form.submit()">
                @foreach ($yearList as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- ======================== --}}
{{-- SUMMARY CARDS --}}
{{-- ======================== --}}

<div class="row">

    {{-- Total Income --}}
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <p class="text-sm mb-1">Total Income ({{ $year }})</p>
                <h4 class="font-weight-bolder">Rp {{ number_format($totalIncome,0,',','.') }}</h4>
            </div>
        </div>
    </div>

    {{-- Total Expense --}}
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <p class="text-sm mb-1">Total Expense ({{ $year }})</p>
                <h4 class="font-weight-bolder">Rp {{ number_format($totalExpense,0,',','.') }}</h4>
            </div>
        </div>
    </div>

    {{-- Total Savings --}}
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <p class="text-sm mb-1">Savings ({{ $year }})</p>
                <h4 class="font-weight-bolder">Rp {{ number_format($totalSavings,0,',','.') }}</h4>
            </div>
        </div>
    </div>

</div>


{{-- ======================== --}}
{{-- CHARTS --}}
{{-- ======================== --}}

<div class="row mt-4">

    {{-- Bar Chart Income vs Expense --}}
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Income vs Expense per Bulan ({{ $year }})</h6>
            </div>
            <div class="card-body">
                <canvas id="chart-bar" height="300"></canvas>
            </div>
        </div>
    </div>

    {{-- Pie Chart Income vs Expense --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Perbandingan Income vs Expense (Pie Chart)</h6>
            </div>
            <div class="card-body">
                <canvas id="chart-pie" height="260"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ======================== --}}
{{-- GOALS PROGRESS --}}
{{-- ======================== --}}
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Goals Progress</h6>
            </div>
            <div class="card-body">

                @forelse ($goals as $g)
                    <p class="mb-1"><strong>{{ $g['name'] }}</strong></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" role="progressbar"
                            style="width: {{ $g['progress'] }}%;"
                            aria-valuenow="{{ $g['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $g['progress'] }}%
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada goals.</p>
                @endforelse

            </div>
        </div>
    </div>
</div>


@endsection

@push('dashboard')
<script>
document.addEventListener("DOMContentLoaded", () => {

    // BAR CHART
    new Chart(document.getElementById("chart-bar"), {
        type: "bar",
        data: {
            labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets: [
                {
                    label: "Income",
                    backgroundColor: "rgba(76, 175, 80, 0.8)",
                    data: @json($monthlyIncome)
                },
                {
                    label: "Expense",
                    backgroundColor: "rgba(244, 67, 54, 0.8)",
                    data: @json($monthlyExpense)
                }
            ]
        }
    });

    // PIE CHART (Income vs Expense)
    new Chart(document.getElementById("chart-pie"), {
        type: "pie",
        data: {
            labels: ["Income", "Expense"],
            datasets: [{
                data: [{{ $incomeVsExpense['income'] }}, {{ $incomeVsExpense['expense'] }}],
                backgroundColor: ["#4CAF50", "#F44336"]
            }]
        }
    });

});
</script>
@endpush
