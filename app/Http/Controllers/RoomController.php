<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Services\RoomService;
use App\Services\ExpenseService;
use App\Models\Room;
use PhpParser\Node\Stmt\Catch_;

class RoomController extends Controller
{
    protected $roomService;
    protected $expenseService;

    public function __construct(RoomService $roomService, ExpenseService $expenseService)
    {
        $this->roomService = $roomService;
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $rooms = Room::orderBy('id', 'desc')->get();

        return view('client.rooms.create', compact('rooms'));
    }

    //Tạo room mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $room = $this->roomService->createRoom(
                $request->only(['name']),
                Auth::user()
            );

            return redirect('/rooms/' . $room->code)->with('toast_success', 'Tạo phòng thành công');
            
        } Catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'Đã xảy ra lỗi khi tạo phòng!');
        };
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:rooms,code',
        ]);

        try {
            $room = $this->roomService->joinRoom(
                $request->code,
                Auth::user()
            );

            return redirect('/rooms/' . $room->code)->with('toast_success', 'Tham gia phòng thành công');

        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'Đã xảy ra lỗi khi tham gia phòng!');
        }
    }

    public function joinLink($code)
    {
        try {
            $room = $this->roomService->joinRoom(
                $code,
                Auth::user()
            );

            return redirect('/rooms/' . $room->code)->with('toast_success', 'Tham gia phòng thành công');

        } catch (\Exception) {
            return redirect()->back()->with('toast_error', 'Đã xảy ra lỗi khi tham gia phòng!');
        }
    }

    //Hiện thông tin room
    public function show(Room $room)
    {
        try {
            // Kiểm tra quyền truy cập
            if (!$room->members()->where('user_id', Auth::id())->exists()) {
                return redirect('rooms')->with('toast_error', 'Bạn không có quyền truy cập phòng này!');
            }

            $room->load(['creator', 'members']);

            $expenses = $this->expenseService->getExpensesByRoom($room);

            return view('client.rooms.index', compact('room', 'expenses'));

        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'Lỗi khi truy cập phòng này!'. $e->getMessage());
        }
    }
}
