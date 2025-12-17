<?php
require_once 'database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo = trim($_POST['equipo']);
    $serial = trim($_POST['serial']);
    $tipo_equipo = trim($_POST['tipo_equipo']);
    $aprendiz = trim($_POST['aprendiz']);
    $ficha = trim($_POST['ficha']);
    $instructor = trim($_POST['instructor']);
    $fecha_prestamo = $_POST['fecha_prestamo'];

    $stmt = $conn->prepare("INSERT INTO prestamos (equipo, serial, tipo_equipo, aprendiz, ficha, instructor, fecha_prestamo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $equipo, $serial, $tipo_equipo, $aprendiz, $ficha, $instructor, $fecha_prestamo);
    $stmt->execute();
    $stmt->close();
}

$db->close();
header('Location: ../index.php');
?>