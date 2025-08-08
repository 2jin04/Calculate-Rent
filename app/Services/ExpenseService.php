<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ExpenseService
{
    public function getExpensesByRoom(Room $room): Collection
    {
        return Expense::where('room_id', $room->id)
            ->with(['creator', 'paidByUser'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    
}