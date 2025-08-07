<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Room;
use App\Models\User;

class expense extends Model
{
    protected $fillable = [
        'room_id',
        'created_by',
        'title',
        'description',
        'amount',
        'paid_by_user_id',
        'members_share',
        'image_path',
    ];

    protected $cat = [
        'amount' => 'decimal:2',
        'members_share' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    public function getSharedMembersAttribute()
    {
        if (!$this->members_share) {
            return collect();
        }

        $memberIds = array_keys($this->members_share);
        return User::whereIn('id', $memberIds)->get();
    }

    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }
}
