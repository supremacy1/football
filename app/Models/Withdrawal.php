<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 
        'amount', 
        'bank_name', 
        'account_number', 
        'reference', 
        'status', 
        'transfer_code'
    ];
}