<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all accounts owned by this user.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get all transactions (sent or received) related to this user's accounts.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactions(): Builder
    {
        $accountIds = $this->accounts()->pluck('id')->toArray();

        return Transaction::where(function ($q) use ($accountIds) {
            $q->whereIn('from_account_id', $accountIds)
              ->orWhereIn('to_account_id', $accountIds);
        });
    }

    /**
     * Get transaction count for the user.
     */
    public function transactionCount(): int
    {
        return $this->transactions()->count();
    }

    /**
     * Get only deposits.
     */
    public function depositTransactions(): Collection
    {
        return $this->transactions()->where('type', 'deposit')->get();

    }
    public function loans()
{
    return $this->hasMany(Loan::class);
}


    /**
     * Get only transfers.
     */
    public function transferTransactions(): Collection
    {
        return $this->transactions()->where('type', 'transfer')->get();
    }
}