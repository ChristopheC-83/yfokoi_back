// Suppression alertes au bout de 4 secondes

const alertes = document.querySelectorAll('.alert');

if (alertes) {
    alertes.forEach((alerte) => {
        setTimeout(() => {
            alerte.remove();
        }, 4000);
    });
}