<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'accounts' => $user->accounts,
            'transactionCount' => $user->transactions()->count(),
            'recentTransactions' => $user->transactions()
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    public function accounts()
    {
        return view('user.accounts', [
            'accounts' => Auth::user()->accounts,
        ]);
    }

    // Show deposit form
    public function showDepositForm()
    {
        return view('user.deposit');
    }

    // Handle deposit request
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $account = Account::findOrFail($request->account_id);
        $account->increment('balance', $request->amount);

        Transaction::create([
            'from_account_id' => null,
            'to_account_id' => $account->id,
            'amount' => $request->amount,
            'type' => 'deposit',
            'status' => 'success',
        ]);

        return redirect()->back()->with('success', 'Deposit successful.');
    }

    // Show withdrawal form
    public function showWithdrawForm()
    {
        return view('user.withdraw');
    }

    // Handle withdrawal
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $account = Account::findOrFail($request->account_id);

        if ($account->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient balance.']);
        }

        $account->decrement('balance', $request->amount);

        Transaction::create([
            'from_account_id' => $account->id,
            'to_account_id' => null,
            'amount' => $request->amount,
            'type' => 'withdrawal',
            'status' => 'success',
        ]);

        return redirect()->back()->with('success', 'Withdrawal successful.');
    }

    // Show loan form
    public function showLoanForm()
    {
        return view('user.loan');
    }

    // Handle loan
    public function takeLoan(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $account = Account::findOrFail($request->account_id);
        $account->increment('balance', $request->amount);

        Transaction::create([
            'from_account_id' => null,
            'to_account_id' => $account->id,
            'amount' => $request->amount,
            'type' => 'loan',
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Loan credited to your account.');
    }

    public function transactions()
    {
        $accounts = Auth::user()->accounts;

        $transactions = Transaction::where(function ($q) use ($accounts) {
            $q->whereIn('from_account_id', $accounts->pluck('id'))
              ->orWhereIn('to_account_id', $accounts->pluck('id'));
        })->latest()->paginate(20);

        return view('user.transactions', compact('transactions'));
    }
}
