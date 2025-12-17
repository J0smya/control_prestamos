<?php
require_once 'php/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Filtros
$filtro = $_GET['filtro'] ?? 'todos';
$orden = $_GET['orden'] ?? 'fecha_prestamo DESC';

$query = "SELECT * FROM prestamos WHERE devuelto != 2";  // Excluir eliminados
if ($filtro === 'prestamo') $query .= " AND devuelto = 0";
if ($filtro === 'devueltos') $query .= " AND devuelto = 1";
$query .= " ORDER BY $orden";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/assets/style.css">
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); } /* Fondo suave azul-lila */
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .btn { border-radius: 20px; }
        .table-hover tbody tr:hover { background-color: rgba(76, 175, 80, 0.1); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center text-success fw-bold"><i class="bi bi-tools"></i> Control de Préstamos de Equipos</h1>

        <!-- Formulario Agregar Préstamo en Card Compacta -->
        <div class="card mb-4 bg-light">
            <div class="card-header bg-success text-white text-center">
                <i class="bi bi-plus-circle"></i> Agregar Nuevo Préstamo
            </div>
            <div class="card-body">
                <form action="/control_prestamos/php/GuardarPrestamo.php" method="POST" id="formPrestamo">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-gear"></i> Equipo</label>
                            <input type="text" name="equipo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-tag"></i> Serial</label>
                            <input type="text" name="serial" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-laptop"></i> Tipo de Equipo</label>
                            <select name="tipo_equipo" class="form-select">
                                <option value="">Seleccionar</option>
                                <option value="herramienta">Herramienta</option>
                                <option value="activo_red">Activo de Red</option>
                                <option value="portatil">Portátil</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person"></i> Aprendiz</label>
                            <input type="text" name="aprendiz" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-card-text"></i> Ficha</label>
                            <input type="text" name="ficha" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person-badge"></i> Instructor</label>
                            <input type="text" name="instructor" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-calendar"></i> Fecha de Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" required>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill shadow">
                                <i class="bi bi-save"></i> Guardar Préstamo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filtros con Badges y Select Mejorado -->
        <div class="d-flex flex-wrap justify-content-center mb-4 gap-2">
            <a href="?filtro=todos&orden=fecha_prestamo DESC" class="badge bg-secondary text-decoration-none p-2 rounded-pill">
                <i class="bi bi-list"></i> Todos
            </a>
            <a href="?filtro=prestamo&orden=fecha_prestamo DESC" class="badge bg-warning text-decoration-none p-2 rounded-pill">
                <i class="bi bi-clock"></i> En Préstamo
            </a>
            <a href="?filtro=devueltos&orden=fecha_prestamo DESC" class="badge bg-success text-decoration-none p-2 rounded-pill">
                <i class="bi bi-check-circle"></i> Devueltos
            </a>
            <select onchange="location.href='?filtro=<?php echo $filtro; ?>&orden=' + this.value" class="form-select w-auto">
                <option value="fecha_prestamo DESC"><i class="bi bi-sort-down"></i> Ordenar por Fecha</option>
                <option value="tipo_equipo ASC"><i class="bi bi-sort-alpha-down"></i> Ordenar por Tipo</option>
            </select>
        </div>

        <!-- Lista de Préstamos en Tabla con Hover -->
        <div class="card">
            <div class="card-header bg-info text-white text-center">
                <i class="bi bi-table"></i> Lista de Préstamos
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-gear"></i> Equipo</th>
                            <th><i class="bi bi-person"></i> Aprendiz</th>
                            <th><i class="bi bi-calendar"></i> Fecha Préstamo</th>
                            <th><i class="bi bi-info-circle"></i> Estado</th>
                            <th><i class="bi bi-gear"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['equipo']); ?> <small class="text-muted">(<?php echo htmlspecialchars($row['tipo_equipo']); ?>)</small></td>
                                <td><?php echo htmlspecialchars($row['aprendiz']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_prestamo']); ?></td>
                                <td>
                                    <?php if ($row['devuelto']): ?>
                                        <span class="badge bg-success"><i class="bi bi-check"></i> Devuelto</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><i class="bi bi-clock"></i> En Préstamo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$row['devuelto']): ?>
                                        <button class="btn btn-outline-success btn-sm rounded-pill" onclick="marcarDevuelto(<?php echo $row['id']; ?>)">
                                            <i class="bi bi-check-circle"></i> Marcar Devuelto
                                        </button>
                                    <?php endif; ?>
                                    <a href="/control_prestamos/php/EliminarPrestamo.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill ms-1" onclick="return confirm('¿Eliminar?')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para Marcar Devuelto -->
    <div class="modal fade" id="modalDevuelto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5><i class="bi bi-check-circle"></i> Marcar como Devuelto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/control_prestamos/php/ActualizarPrestamo.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="prestamoId">
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-calendar-check"></i> Fecha de Devolución</label>
                            <input type="date" name="fecha_devolucion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-chat-dots"></i> Observaciones</label>
                            <textarea name="observaciones" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success rounded-pill">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/app.js"></script>
</body>
</html>
<?php $db->close(); ?>