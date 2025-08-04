<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomUser extends Pivot
{
    protected $table = 'room_user';

    protected $fillable = [
        'room_id',
        'user_id',
        'role',
        'join_at',
        'created_at',
        'update_at'
    ];

    protected $casts = [
        'join_at' => 'datetime',
        'created_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
