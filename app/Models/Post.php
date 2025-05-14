<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;



class Post extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'content',
        'status',
        'topic_id',
        'user_id',
        'created_at',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function isFavourited($userId = null)
    {
        if ($userId === null) {
            $userId = auth()->id();
        }
        
        return $this->favourite()->where('user_id', $userId)->exists();
    }

    public function scopePassedModeration($query)
    {
        return $query->where('status', 'passed moderator verification');
    }

    public function scopeModeration($query)
    {
        return $query->where('status', 'passed automatic verification');
    }

    public function scopeNotModeration($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeRecalled($query)
    {
        return $query->where('status', 'the post has been recalled to users');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_posts');
    }



}
