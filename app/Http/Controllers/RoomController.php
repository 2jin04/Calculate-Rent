<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Services\RoomService;
use App\Models\Room;
use PhpParser\Node\Stmt\Catch_;

class RoomController extends Controller
{
    protected $roomService;

    // public function __construct(RoomService $roomService)
    // {
    //     $this->roomService = $roomService;
    //     $this->middleware('auth');
    // }

    public function index()
    {
        return view('client.rooms.index');
    }

    //Tạo room mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balace' => 'nullable|numeric|min:0',
        ]);

        try {
            $room = $this->roomService->createRoom(
                $request->only(['name', 'balance']),
                Auth::user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Room được tạo thành công!',
                'data' => $room
            ], 201);
            
        } Catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo room: ' . $e->getMessage()
            ], 500);
        };
    }
}
