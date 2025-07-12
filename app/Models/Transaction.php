<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
        'type',
        'status',
    ];

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    // 🔽 Scope: deposits
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    // 🔽 Scope: transfers
    public function scopeTransfers($query)
    {
        return $query->where('type', 'transfer');
    }

    // 🔽 Scope: approved only
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
