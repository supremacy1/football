<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->decimal('balance', 15, 2)->default(0.00);
            $blueprint->string('paystack_customer_code')->nullable();
            $blueprint->string('paystack_account_number')->nullable();
            $blueprint->string('paystack_bank_name')->nullable();
            $blueprint->string('paystack_account_name')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};