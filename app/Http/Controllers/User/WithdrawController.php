<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;

class WithdrawController extends Controller
{
    public function showForm()
    {
        return view('user.withdraw.form');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $account = $user->account;

        if (!$account) {
            return back()->withErrors('Account not found.');
        }

        if ($account->balance < $request->amount) {
            return back()->withErrors('Insufficient balance for this withdrawal.');
        }

        // Create transaction
        Transaction::create([
            'account_id' => $account->id,
            'type'       => 'withdrawal',
            'amount'     => $request->amount,
            'note'       => 'User withdrawal',
        ]);

        // Update account balance
        $account->balance -= $request->amount;
        $account->save();

        return redirect()->route('dashboard')->with('success', 'Withdrawal successful!');
    }
}
