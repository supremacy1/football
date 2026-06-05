<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'country',
        'founded_year',
        'members_count',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'club_members');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function homeMatches()
    {
        return $this->hasMany('App\\Models\\Match', 'home_club_id');
    }

    public function awayMatches()
    {
        return $this->hasMany('App\\Models\\Match', 'away_club_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'club_members');
    }

    public function addMember(User $user)
    {
        if (!$this->isMember($user)) {
            $this->members()->attach($user->id);
            $this->increment('members_count');
        }
    }

    public function removeMember(User $user)
    {
        $this->members()->detach($user->id);
        $this->decrement('members_count');
    }

    public function isMember(User $user)
    {
        return $this->members()->where('users.id', $user->id)->exists();
    }
}
