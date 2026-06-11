<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'user_id',
        'partner_id',
        'amount',
        'selection', // 'home' or 'away'
        'status',    // 'pending', 'matched', 'confirmed', 'locked', 'won', 'lost', 'cancelled'
        'payout',
    ];

    public function match()
    {
        return $this->belongsTo(ClubMatch::class, 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isOpposite(Bet $other)
    {
        return $this->selection !== $other->selection;
    }

    public function getSelectionTeamAttribute()
    {
        return $this->selection === 'home' ? $this->match->homeClub : $this->match->awayClub;
    }
}