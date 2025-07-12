<?php
namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class TransactionsTable extends Component
{
    use WithPagination;

    public $type = '';
    public $status = '';

    public function updating($field)
    {
        $this->resetPage();
    }

    public function render()
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        $query = Transaction::where(function ($q) use ($accountIds) {
            $q->whereIn('from_account_id', $accountIds)
              ->orWhereIn('to_account_id', $accountIds);
        });

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $transactions = $query->latest()->paginate(10);

        return view('livewire.user.transactions-table', [
            'transactions' => $transactions,
        ]);
    }
}
