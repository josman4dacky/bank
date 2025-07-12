<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'accountCount' => Account::count(),
            'totalBalance' => Account::sum('balance'),
            'transactionCount' => Transaction::count(),
            'recentTransactions' => Transaction::latest()->take(5)->get(),
        ]);
    }

    public function users()
    {
        return view('admin.users', ['users' => User::all()]);
    }

    public function accounts()
    {
        return view('admin.accounts', ['accounts' => Account::with('user')->get()]);
    }

    public function transactions()
    {
        return view('admin.transactions', ['transactions' => Transaction::latest()->paginate(20)]);
    }
}
