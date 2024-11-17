document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal:modal', (eventData) => {
        Swal.fire({
            title: eventData[0].title,
            text: eventData[0].text,
            icon: eventData[0].icon,
        });
    });

    Livewire.on('toast:message', event => {
        const message = event[0].message;
        const status = event[0].status;

        Swal.fire({
            toast: true,
            position: "top-end",
            icon: status,
            title: message,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 3000,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    });
});
