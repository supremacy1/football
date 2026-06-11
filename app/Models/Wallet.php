<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'paystack_customer_code',
        'paystack_account_number',
        'paystack_bank_name',
        'paystack_account_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}