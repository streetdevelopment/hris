var Toast = Swal.mixin({
toast: true,
position: 'top-end',
showConfirmButton: false,
timer: 3000
});

Toast.fire({
title: ``,
icon: 'warning'
})