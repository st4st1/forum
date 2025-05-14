<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_id',
        'telegram_username',
        'role',
        'about',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : asset('images/default-avatar.png');
    }

    // Отношения
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    // Отношения друзей
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', Friend::STATUS_ACCEPTED)
            ->withTimestamps()
            ->select('users.*'); // Явно указываем какие столбцы выбирать
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', Friend::STATUS_PENDING)
            ->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', Friend::STATUS_PENDING)
            ->withTimestamps();
    }

    // Методы для работы с друзьями
    public function addFriend(User $user): void
    {
        if (!$this->isFriendWith($user)) {
            Friend::firstOrCreate([
                'user_id' => $this->id,
                'friend_id' => $user->id,
                'status' => Friend::STATUS_PENDING
            ]);
        }
    }

    public function acceptFriend(User $user): void
    {
        // Находим запрос на дружбу
        $friendship = Friend::where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', Friend::STATUS_PENDING)
            ->firstOrFail();
    
        // Обновляем статус исходного запроса
        $friendship->update(['status' => Friend::STATUS_ACCEPTED]);
    
        // Создаем обратную запись о дружбе (если ее еще нет)
        Friend::firstOrCreate([
            'user_id' => $this->id,
            'friend_id' => $user->id,
            'status' => Friend::STATUS_ACCEPTED
        ]);
    }

    public function rejectFriend(User $user): void
    {
        Friend::where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', Friend::STATUS_PENDING)
            ->delete();
    }

    public function removeFriend(User $user): void
    {
        Friend::where(function ($query) use ($user) {
            $query->where('user_id', $this->id)
                ->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('friend_id', $this->id);
        })->delete();
    }

    public function isFriendWith(User $user): bool
    {
        return Friend::where(function ($query) use ($user) {
            $query->where('user_id', $this->id)
                ->where('friend_id', $user->id)
                ->where('status', Friend::STATUS_ACCEPTED);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('friend_id', $this->id)
                ->where('status', Friend::STATUS_ACCEPTED);
        })->exists();
    }

    public function hasFriendRequestFrom(User $user): bool
    {
        return Friend::where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->where('status', Friend::STATUS_PENDING)
            ->exists();
    }

    public function mutualFriendsCount($otherUserId): int
    {
        $myFriends = $this->friends()->pluck('users.id'); // Явное указание таблицы
        $otherFriends = User::find($otherUserId)->friends()->pluck('users.id');
        
        return $myFriends->intersect($otherFriends)->count();
    }

    // Методы для работы с авторами
    public function subscriptions()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'author_id')
            ->withTimestamps();
    }
    
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'author_id', 'user_id')
            ->withTimestamps();
    }
    
    public function subscribedPosts()
    {
        return Post::whereIn('user_id', $this->subscriptions()->pluck('author_id'))
            ->latest()
            ->with('user');
    }

    // Методы для работы с сообществоми 
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function createdCommunities()
    {
        return $this->hasMany(Community::class, 'creator_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants');
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }
}