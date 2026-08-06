// =============================================
// alert.init.js — SweetAlert2 Helper Functions
// =============================================

// Toast Notifications (Top Right)
window.Toast = {
    fire(icon, title) {
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    },
    success(title) { this.fire('success', title); },
    error(title) { this.fire('error', title); },
    warning(title) { this.fire('warning', title); },
    info(title) { this.fire('info', title); }
};

// Modal Alert & Confirmations
window.Alert = {
    success(title, text = '') {
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                confirmButtonColor: '#1e6fd9'
            });
        }
    },
    error(title, text = '') {
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonColor: '#1e6fd9'
            });
        }
    },
    confirm(title, text, callback) {
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        }
    }
};
