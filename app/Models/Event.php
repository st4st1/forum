<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'community_id', 'creator_id',
        'start_time', 'end_time', 'location'
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants');
    }

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
