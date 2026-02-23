<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('category')->where('user_id', Auth::user()->id)->latest()->paginate(10);
        $totalTransactions = Transaction::where('user_id', Auth::user()->id)->count();
        $totalIncome = (float) Transaction::where('user_id', Auth::user()->id)->where('type', 'in')->sum('amount');
        $totalOutcome = (float) Transaction::where('user_id', Auth::user()->id)->where('type', 'out')->sum('amount');
        $totalBalance = $totalIncome - $totalOutcome;

        // Expense by Category (Pie Chart)
        $expenseByCategory = Transaction::selectRaw('categories.name as category, sum(amount) as total')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', Auth::user()->id)
            ->where('transactions.type', 'out')
            ->groupBy('categories.name')
            ->get();

        // Income by Category
        $incomeByCategory = Transaction::selectRaw('categories.name as category, sum(amount) as total')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', Auth::user()->id)
            ->where('transactions.type', 'in')
            ->groupBy('categories.name')
            ->get();

        // Daily Transactions for Last 30 Days (Bar/Line Chart)
        $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30);
        $dailyTransactions = Transaction::selectRaw('DATE(created_at) as date, type, sum(amount) as total')
            ->where('user_id', Auth::user()->id)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date', 'type')
            ->orderBy('date', 'asc')
            ->get();

        return Inertia::render('Dashboard', [
            'transactions' => $transactions,
            'totalTransactions' => $totalTransactions,
            'totalIncome' => $totalIncome,
            'totalOutcome' => $totalOutcome,
            'totalBalance' => $totalBalance,
            'expenseByCategory' => $expenseByCategory,
            'incomeByCategory' => $incomeByCategory,
            'dailyTransactions' => $dailyTransactions,
        ]);
    }

    public function duitNotes()
    {
        return Inertia::render('DuitNotes', [
            'transactions' => Transaction::with('category')->where('user_id', Auth::user()->id)->latest()->paginate(10),
        ]);
    }

    public function storeDuitNote(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:in,out',
            'description' => 'required|string|max:255',
            'category_name' => 'nullable|string|max:100',
        ]);

        $categoryId = null;
        if (!empty($validated['category_name'])) {
            $category = Category::firstOrCreate(['name' => strtolower($validated['category_name'])]);
            $categoryId = $category->id;
        }

        Transaction::create([
            'user_id' => Auth::user()->id,
            'category_id' => $categoryId,
            'amount' => $validated['amount'],
            'phone' => Auth::user()->phone,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => 'settled', // Assuming manual entry is settled
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function updateDuitNote(Request $request, Transaction $transaction)
    {
        // Ensure user owns the transaction
        if ($transaction->user_id !== Auth::user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:in,out',
            'description' => 'required|string|max:255',
            'category_name' => 'nullable|string|max:100',
        ]);

        $categoryId = null;
        if (!empty($validated['category_name'])) {
            $category = Category::firstOrCreate(['name' => strtolower($validated['category_name'])]);
            $categoryId = $category->id;
        }

        $transaction->update([
            'category_id' => $categoryId,
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroyDuitNote(Transaction $transaction)
    {
        // Ensure user owns the transaction
        if ($transaction->user_id !== Auth::user()->id) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }
}
