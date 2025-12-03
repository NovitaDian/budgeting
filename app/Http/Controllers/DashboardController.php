<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Goal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // ===== FILTER TAHUN =====
        $year = $request->year ?? now()->year;

        // ===========================
        // 1. INCOME & EXPENSE TAHUNAN
        // ===========================
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $year)
            ->sum('amount');

        $totalSavings = $totalIncome - $totalExpense;

        // ===========================
        // 2. DATA PER BULAN (CHART)
        // ===========================
        $monthlyIncome = [];
        $monthlyExpense = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyIncome[] = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->sum('amount');

            $monthlyExpense[] = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->sum('amount');
        }

        // ===========================
        // 3. PIE CHART PERBANDINGAN
        // ===========================
        $incomeVsExpense = [
            'income' => $totalIncome,
            'expense' => $totalExpense,
        ];

        // ===========================
        // 4. GOALS PROGRESS
        // ===========================
        $goals = Goal::where('user_id', $userId)->get()->map(function ($goal) {
            $progress = $goal->current_amount / $goal->target_amount * 100;

            return [
                'name' => $goal->goal_name,
                'target' => $goal->target_amount,
                'current' => $goal->current_amount,
                'progress' => min(100, round($progress, 2)), // cap at 100%
            ];
        });

        // List tahun (6 tahun kebelakang)
        $yearList = range(now()->year - 5, now()->year + 1);

        return view('dashboard', compact(
            'year',
            'yearList',
            'totalIncome',
            'totalExpense',
            'totalSavings',
            'monthlyIncome',
            'monthlyExpense',
            'incomeVsExpense',
            'goals'
        ));
    }
}
