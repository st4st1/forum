<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = ['name', 'description', 'avatar', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'community_posts');
    }
}
