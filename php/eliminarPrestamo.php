<?php
header('Content-Type: application/json');

require_once 'database.php';

// Validar ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID del préstamo no recibido'
    ]);
    exit;
}

$id = intval($_POST['id']);

try {
    // Crear conexión usando la clase Database
    $database = new Database();
    $conn = $database->getConnection();

    // Consulta preparada
    $sql = "DELETE FROM prestamos WHERE id = ? AND devuelto = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
    echo json_encode([
        'success' => true,
        'message' => 'Préstamo eliminado correctamente'
    ]);
    } else {
    echo json_encode([
        'success' => false,
        'message' => 'No se puede eliminar: el préstamo ya fue devuelto o no existe'
    ]);
    }

    $stmt->close();

    $database->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>