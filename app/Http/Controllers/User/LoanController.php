<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;

class LoanController extends Controller
{
    public function showForm()
    {
        return view('user.loan.form');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'purpose' => 'required|string|max:255',
            'term' => 'required|integer|min:1|max:60', // months
        ]);

        Loan::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'term' => $request->term,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Loan application submitted successfully.');
    }
}
