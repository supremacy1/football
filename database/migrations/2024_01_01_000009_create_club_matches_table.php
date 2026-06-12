<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_club_id')->constrained('clubs')->onDelete('cascade');
            $table->foreignId('away_club_id')->constrained('clubs')->onDelete('cascade');
            $table->dateTime('match_date');
            $table->string('venue')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished', 'cancelled'])->default('scheduled');
            $table->string('league')->nullable();
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_matches');
    }
};