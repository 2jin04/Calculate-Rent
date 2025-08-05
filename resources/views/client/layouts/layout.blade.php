<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease forwards;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#8b5cf6'
                    }
                }
            }
        }
    </script>
</head>
<body>
    @include('client.partials.nav')
    @yield('content')
    
    @include('client.layouts.alert')
    <script>
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date
            document.getElementById('expenseDate').value = new Date().toISOString().split('T')[0];
            
            // Event listeners
            document.getElementById('dateRange').addEventListener('change', toggleCustomDateRange);
            document.getElementById('addExpenseBtn').addEventListener('click', openModal);
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('cancelExpense').addEventListener('click', closeModal);
            document.getElementById('submitExpense').addEventListener('click', submitExpense);
            document.getElementById('imageUpload').addEventListener('change', handleImageUpload);
            
            // Split members calculation
            const checkboxes = document.querySelectorAll('input[name="splitMembers"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSplitInfo);
            });
            
            document.getElementById('expenseAmount').addEventListener('input', updateSplitInfo);
            
            // Initialize split info
            updateSplitInfo();
        });

        function toggleCustomDateRange() {
            const dateRange = document.getElementById('dateRange').value;
            const customDateRange = document.getElementById('customDateRange');
            
            if (dateRange === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        }

        function openModal() {
            document.getElementById('addExpenseModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('addExpenseModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetForm();
        }

        function resetForm() {
            document.getElementById('expenseAmount').value = '';
            document.getElementById('expenseSource').value = 'Tiền mặt';
            document.getElementById('expenseItem').value = '';
            document.getElementById('expenseDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('expensePayer').value = 'Nguyễn Văn A';
            
            // Reset checkboxes
            const checkboxes = document.querySelectorAll('input[name="splitMembers"]');
            checkboxes.forEach((checkbox, index) => {
                checkbox.checked = index === 0; // Only first one checked
            });
            
            // Reset image
            document.getElementById('imageUpload').value = '';
            document.getElementById('imageFileName').classList.add('hidden');
            
            updateSplitInfo();
        }

        function updateSplitInfo() {
            const checkedBoxes = document.querySelectorAll('input[name="splitMembers"]:checked');
            const amount = parseInt(document.getElementById('expenseAmount').value) || 0;
            const splitAmount = checkedBoxes.length > 0 ? Math.round(amount / checkedBoxes.length) : 0;
            
            document.getElementById('splitInfo').textContent = 
                `Chia cho ${checkedBoxes.length} người • ${splitAmount.toLocaleString('vi-VN')} ₫/người`;
        }

        function handleImageUpload(event) {
            const file = event.target.files[0];
            const fileName = document.getElementById('imageFileName');
            
            if (file) {
                fileName.textContent = `Đã chọn: ${file.name}`;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }

        function submitExpense() {
            const amount = document.getElementById('expenseAmount').value;
            const item = document.getElementById('expenseItem').value;
            const checkedMembers = document.querySelectorAll('input[name="splitMembers"]:checked');
            
            if (!amount || !item || checkedMembers.length === 0) {
                alert('Vui lòng điền đầy đủ thông tin!');
                return;
            }
            
            // Here you would normally send data to server
            alert('Đã thêm chi tiêu thành công!');
            closeModal();
        }

        // Close modal when clicking outside
        document.getElementById('addExpenseModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>