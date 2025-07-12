<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;

class DepositController extends Controller
{
    public function showDepositForm()
    {
        return view('user.deposit');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $account = $user->account; // Assuming one-to-one relationship

        if (!$account) {
            return back()->withErrors('Account not found.');
        }

        // Create transaction
        Transaction::create([
            'account_id' => $account->id,
            'type'       => 'deposit',
            'amount'     => $request->amount,
            'note'       => 'User deposit',
        ]);

        // Update account balance
        $account->balance += $request->amount;
        $account->save();

        return redirect()->route('dashboard')->with('success', 'Deposit successful!');
    }
}
