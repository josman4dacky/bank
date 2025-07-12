<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'balance',
        'status',
    ];

    /**
     * The user who owns this account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transactions sent from this account.
     */
    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    /**
     * Transactions received by this account.
     */
    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }
}
