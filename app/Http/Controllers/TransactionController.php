<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $category = Category::find($request->category_id);

        $data = [
            'user_id' => Auth::id(),
            'category_id' => $category->id,
            'type' => $category->type,
            'date' => $request->date,
            'amount' => $request->amount,
            'note' => $request->note,
        ];

        if ($request->hasFile('receipt_image')) {
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        Transaction::create($data);

        // Redirect sesuai tipe transaksi
        if ($category->type === 'income') {
            return redirect()->route('transactions.income')->with('success', 'Income added successfully.');
        }

        return redirect()->route('transactions.expense')->with('success', 'Expense added successfully.');
    }

    /**
     * Show the form for editing the transaction.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $categories = Category::orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $category = Category::find($request->category_id);

        $transaction->update([
            'category_id' => $category->id,
            'type' => $category->type,
            'date' => $request->date,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        if ($request->hasFile('receipt_image')) {
            $transaction->receipt_image = $request->file('receipt_image')->store('receipts', 'public');
            $transaction->save();
        }

        // Redirect sesuai tipe transaksi
        if ($category->type === 'income') {
            return redirect()->route('transactions.income')->with('success', 'Income updated successfully.');
        }

        return redirect()->route('transactions.expense')->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete the record.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction deleted.');
    }
    public function show(Transaction $transaction)
    {


        return redirect()->back();
    }

    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
    }
    public function income()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('type', 'income')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('transactions.income.index', compact('transactions'));
    }
    public function expense()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('transactions.expense.index', compact('transactions'));
    }
}
