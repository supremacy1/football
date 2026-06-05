<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
