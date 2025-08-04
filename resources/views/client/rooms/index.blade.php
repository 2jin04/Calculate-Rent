<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo/Vào phòng</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-300 min-h-screen flex items-center justify-center">
    <div class="flex gap-20">
        <!-- Tạo phòng mới -->
        <div class="bg-white rounded-3xl p-3 w-64 shadow-sm">
            <h2 class="text-lg text-center font-medium text-gray-800 mb-4">Tạo phòng mới</h2>
            <form action="{{ url('/rooms') }}" method="POST">
                @csrf
                <div class="my-8 p-3 rounded-3xl bg-gray-300">
                    <label class="block text-sm font-medium pb-2 border-b-2 border-b-gray-400">Tên phòng</label>
                    <input 
                        type="text" 
                        name="name"
                        id="name" 
                        placeholder="Có thể đổi tên sau khi tạo"
                        class="w-full bg-gray-300 pt-2 border-0 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium p-2 rounded-3xl transition-colors">
                    Tạo phòng
                </button>
            </form>
        </div>

        <!-- Vào phòng có sẵn -->
        <div class="bg-white rounded-3xl p-3 w-64 shadow-sm">
            <h2 class="text-lg text-center font-medium text-gray-800 mb-4">Vào phòng có sẵn</h2>
            
            <div class="my-8 p-3 rounded-3xl bg-gray-300">
                <label class="block text-sm font-medium pb-2 border-b-2 border-b-gray-400">Mã phòng</label>
                <input 
                    type="text" 
                    placeholder="Mã phòng cần vào"
                    class="w-full bg-gray-300 pt-2 border-0 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            
            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium p-2 rounded-3xl transition-colors">
                Vào phòng
            </button>
        </div>
    </div>
</body>
</html>