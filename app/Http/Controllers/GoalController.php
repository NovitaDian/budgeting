<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /** Show all goals */
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())->get();

        // Hitung presentase progress
        foreach ($goals as $goal) {
            $goal->percentage = $goal->target_amount > 0
                ? round(($goal->current_amount / $goal->target_amount) * 100, 2)
                : 0;
        }

        return view('goals.index', compact('goals'));
    }


    /** Show create form */
    public function create()
    {
        return view('goals.create');
    }


    /** Store new goal */
    public function store(Request $request)
    {
        $request->validate([
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        Goal::create([
            'user_id' => Auth::id(),
            'goal_name' => $request->goal_name,
            'target_amount' => $request->target_amount,
            'deadline' => $request->deadline,
            'current_amount' => 0,
            'status' => 'ongoing',
        ]);

        return redirect()->route('goals.index')->with('success', 'Goal created successfully.');
    }


    /** Show edit form */
    public function edit(Goal $goal)
    {
        $this->authorizeGoal($goal);

        return view('goals.edit', compact('goal'));
    }


    /** Update goal */
    public function update(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $request->validate([
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        $goal->update($request->only(['goal_name', 'target_amount', 'deadline']));

        return redirect()->route('goals.index')->with('success', 'Goal updated successfully.');
    }


    /** Delete a goal */
    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal deleted successfully.');
    }


    /** Store saving log */

    public function addLog(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

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

        // 2. Tambahkan transaksi expense
        Transaction::create([
            'user_id' => $goal->user_id,
            'category_id' => "Goal",
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

        return redirect()->route('goals.index')->with('success', 'Progress added successfully.');
    }



    /** Ensure user can only edit own data */
    private function authorizeGoal(Goal $goal)
    {
        if ($goal->user_id != Auth::id()) {
            abort(403);
        }
    }
    public function show($id)
    {
        $goal = Goal::with('logs')->findOrFail($id);

        return view('goals.show', compact('goal'));
    }
}
