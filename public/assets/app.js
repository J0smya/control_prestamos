function marcarDevuelto(id) {
    document.getElementById('prestamoId').value = id;
    new bootstrap.Modal(document.getElementById('modalDevuelto')).show();
}

document.getElementById('formPrestamo').addEventListener('submit', function(e) {
    const equipo = document.querySelector('[name="equipo"]').value.trim();
    const aprendiz = document.querySelector('[name="aprendiz"]').value.trim();
    const fecha = document.querySelector('[name="fecha_prestamo"]').value;
    if (!equipo || !aprendiz || !fecha) {
        alert('Campos obligatorios: Equipo, Aprendiz, Fecha de Préstamo');
        e.preventDefault();
    }

function eliminarPrestamo(id) {
    if (!confirm('¿Seguro que deseas eliminar este préstamo?')) return;

    fetch('php/eliminarPrestamo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
}

});