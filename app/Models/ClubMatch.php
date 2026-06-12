<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'club_matches';

    protected $fillable = [
        'home_club_id',
        'away_club_id',
        'match_date',
        'venue',
        'home_score',
        'away_score',
        'status',
        'league',
        'description',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function homeClub()
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function awayClub()
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }

    public function bets()
    {
        return $this->hasMany(Bet::class, 'match_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->withoutGlobalScope('no_club');
    }
}
