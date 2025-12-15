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
});