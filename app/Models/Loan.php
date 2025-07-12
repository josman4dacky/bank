<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',  // Add this line
        'amount',
        'purpose',
        'term',
        // add other fields you want to mass assign
    ];
}
