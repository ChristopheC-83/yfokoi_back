export function showAlert(message, type = 'danger') {
        const alertBox = document.getElementById('formAlert');
        if (!alertBox) return;

        alertBox.className = `alert alert-${type} mt-3`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');

        setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 5000);
    }