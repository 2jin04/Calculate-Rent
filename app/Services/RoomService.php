<?php

namespace App\Services;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class RoomService
{
    //Tạo room mới
    public function createRoom(array $data, User $creator)
    {
        //Sử dụng DB::transaction giúp rollback nếu có lỗi
        return DB::transaction(function () use ($data, $creator) {
            //Tạo room
            $room = Room::create([
                'name' => $data['name'],
                'code' => Room::generateUniqueCode(),
                'balance' => $data['balance'] ?? 0.00,
                'created_by' => $creator->id,
            ]);

            //Tạo link tham gia
            $room->join_link = $room->generateJoinLink();
            $room->save();
    
            //Thêm người tạo room với role admin
            $room->members()->attach($creator->id, [
                'role' => 'admin',
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return $room->load(['creator', 'members']);
        });
    }

    //Vào room với code
    public function joinRoom(string $code, User $user, string $role = 'members') 
    {
        return DB::transaction(function () use ($code, $user, $role) {
            $room = Room::where('code', $code)->fisrtOrFail();

            //Kiểm tra user đã trong room chưa
            if ($room->members()->where('user_id', $user->id)->exists()) {
                throw new Exception('Bạn đã tham gia phòng này rôi!');
            }

            //Theem user vào room
            $room->members()->attach($user->id, [
                'room' => $role,
                'join_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $room->load(['creator', 'members']);
        });
    }

    public function updateBalance(Room $room, float $amount)
    {
        $room->update(['balance' => $amount]);
        return $room;
    }

    public function getUserRooms(User $user)
    {
        return $user->room()
                    ->with(['creator', 'members'])
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}