<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'join_link',
        'balance',
        'created_by',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'code';
    }


    // Kết nối với User
    public function creator() 
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //kết nối với RoomUser
    public function members()
    {
        return $this->belongsToMany(User::class, 'room_user')
                    ->withPivot('role', 'joined_at', 'created_at', 'updated_at')
                    ->withTimestamps();
    }

    //Tạo code phòng duy nhất
    public static function generateUniqueCode($length = 8)
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function generateJoinLink()
    {
        return url("/room/join/{$this->code}");
    }
}
