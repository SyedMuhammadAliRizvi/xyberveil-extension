<div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '{{ $message }}',
                icon: '{{ $type }}',
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true
            });
        });
    </script>
</div>
