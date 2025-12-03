<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MonthlyBudget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyBudgetController extends Controller
{
    public function index()
    {
        $budgets = MonthlyBudget::with('category')->get();

        foreach ($budgets as $budget) {
            $budget->actual_amount = Transaction::where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereYear('date', $budget->year)
                ->whereMonth('date', date('m', strtotime($budget->month)))
                ->sum('amount');
        }

        return view('monthly_budget.index', compact('budgets'));
    }

    public function create()
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $categories = Category::where('type', 'expense')->get();

        return view('monthly_budget.create', compact('months', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|numeric',
            'planned_amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable'
        ]);

        MonthlyBudget::create([
            'user_id' => Auth::id(),
            'month' => $request->month,
            'year' => $request->year,
            'category_id' => $request->category_id,
            'planned_amount' => $request->planned_amount,
            'notes' => $request->notes
        ]);

        return redirect()->route('monthly-budget.index')
            ->with('success', 'Monthly budget created successfully.');
    }


    public function edit($id)
    {
        $budget = MonthlyBudget::findOrFail($id);

        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $categories = Category::where('type', 'expense')->get();

        return view('monthly_budget.edit', compact('budget', 'months', 'categories'));
    }


    public function update(Request $request, MonthlyBudget $monthlyBudget)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required|numeric',
            'planned_amount' => 'required|numeric',
            'actual_amount' => 'nullable|numeric',
            'notes' => 'nullable'
        ]);

        $monthlyBudget->update($request->all());

        return redirect()->route('monthly-budget.index')->with('success', 'Monthly budget updated successfully.');
    }

    public function destroy(MonthlyBudget $monthlyBudget)
    {
        $monthlyBudget->delete();

        return redirect()->route('monthly-budget.index')->with('success', 'Monthly budget deleted.');
    }
}
