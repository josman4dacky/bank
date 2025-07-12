<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class RecentTransactions extends Component
{
    public $transactions;

    public function mount()
    {
        $user = Auth::user();
        $accountIds = $user->accounts->pluck('id');

        $this->transactions = Transaction::where(function ($q) use ($accountIds) {
            $q->whereIn('from_account_id', $accountIds)
              ->orWhereIn('to_account_id', $accountIds);
        })
        ->latest()
        ->take(5)
        ->get();
    }

    public function render()
    {
        return view('livewire.user.recent-transactions', [
            'transactions' => $this->transactions,
        ]);
    }
}
