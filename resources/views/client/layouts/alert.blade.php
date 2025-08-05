<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer
            toast.onmouseleave = Swal.resumeTimer
        }
    });

    @if (session('toast_success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('toast_success') }}'
        });
    @endif

    @if (session('toast_error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session('toast_error') }}'
        });
    @endif

    @if (session('toast_warning'))
        Toast.fire({
            icon: 'warning',
            title: '{{ session('toast_warning') }}'
        });
    @endif

    @if (session('toast_info'))
        Toast.fire({
            icon: 'info',
            title: '{{ session('toast_info') }}'
        });
    @endif
</script>
