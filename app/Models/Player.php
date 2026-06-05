<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'club_id',
        'position',
        'jersey_number',
        'date_of_birth',
        'nationality',
        'photo',
        'goals',
        'assists',
        'matches_played',
        'bio',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
