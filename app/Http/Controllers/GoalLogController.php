<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Goal;
use App\Models\GoalLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalLogController extends Controller
{
    /** Store new log entry */
    public function store(Request $request, Goal $goal)
    {

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date'
        ]);

        // 1. Simpan log goal
        GoalLog::create([
            'goal_id' => $goal->id,
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        $goalCategory = Category::firstOrCreate(
            [
                'user_id' => $goal->user_id,
                'name' => 'GOAL',
                'type' => 'expense',
            ],
            [
                'limit' => null // jika kolom limit nullable
            ]
        );

        Transaction::create([
            'user_id' => $goal->user_id,
            'category_id' => $goalCategory->id,
            'date' => $request->date,
            'type' => 'expense',
            'amount' => $request->amount,
            'note' => 'Saving untuk goal: ' . $goal->goal_name
        ]);

        // 3. Update current amount goal
        $goal->current_amount = GoalLog::where('goal_id', $goal->id)->sum('amount');

        // 4. Auto mark completed
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
        }

        $goal->save();

        return back()->with('success', 'Log deleted successfully.');
    }

    public function destroy(GoalLog $log)
    {
        $goal = $log->goal;

        // Hapus transaksi terkait log
        Transaction::where('user_id', $goal->user_id)
            ->where('amount', $log->amount)
            ->where('date', $log->date)
            ->where('note', 'like', '%Saving untuk goal: ' . $goal->goal_name . '%')
            ->delete();

        // Hapus log
        $log->delete();

        // Update current_amount goal
        $goal->current_amount = GoalLog::where('goal_id', $goal->id)->sum('amount');

        // Update status goal
        $goal->status = $goal->current_amount >= $goal->target_amount ? 'completed' : 'ongoing';

        $goal->save();

        return back()->with('success', 'Log deleted successfully.');
    }
}
