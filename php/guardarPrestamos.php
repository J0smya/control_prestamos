<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $equipo = $_POST['equipo'];
    $serial = $_POST['serial'] ?? null;
    $aprendiz = $_POST['aprendiz'];
    $ficha = $_POST['ficha'] ?? null;
    $fecha_prestamo = $_POST['fecha_prestamo'];

    $sql = "INSERT INTO prestamos 
            (equipo, serial, aprendiz, ficha, fecha_prestamo, devuelto)
            VALUES (?, ?, ?, ?, ?, 0)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",   // ← 5 tipos, 5 variables
        $equipo,
        $serial,
        $aprendiz,
        $ficha,
        $fecha_prestamo
    );

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    }
}

$db->close();
