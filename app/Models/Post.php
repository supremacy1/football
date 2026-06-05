<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'club_id',
        'post_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_comment_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'post_likes')->wherePivot('is_like', true);
    }

    public function dislikedBy()
    {
        return $this->belongsToMany(User::class, 'post_likes')->wherePivot('is_like', false);
    }

    public function toggleLike(User $user)
    {
        $like = $this->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            if ($like->is_like) {
                $like->delete();
                $this->decrement('likes_count');
            } else {
                $like->update(['is_like' => true]);
                $this->increment('likes_count');
                $this->decrement('dislikes_count');
            }
        } else {
            $this->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);
            $this->increment('likes_count');
        }
    }

    public function toggleDislike(User $user)
    {
        $like = $this->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            if (!$like->is_like) {
                $like->delete();
                $this->decrement('dislikes_count');
            } else {
                $like->update(['is_like' => false]);
                $this->decrement('likes_count');
                $this->increment('dislikes_count');
            }
        } else {
            $this->likes()->create([
                'user_id' => $user->id,
                'is_like' => false,
            ]);
            $this->increment('dislikes_count');
        }
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', true)->exists();
    }

    public function isDislikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', false)->exists();
    }
}
