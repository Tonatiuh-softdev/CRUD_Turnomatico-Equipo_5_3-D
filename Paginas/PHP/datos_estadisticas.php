<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();

$periodo = $_GET['periodo'] ?? 'año';
$condicion = '';

if ($periodo === 'dia') {
    $condicion = "WHERE DATE(fecha_creacion) = CURDATE()";
} elseif ($periodo === 'mes') {
    $condicion = "WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())";
} elseif ($periodo === 'año') {
    $condicion = "WHERE YEAR(fecha_creacion) = YEAR(CURDATE())";
}

$query = "SELECT tipo, COUNT(*) AS total FROM turnos $condicion GROUP BY tipo";
$resultado = $conn->query($query);

$datos = [];
while ($fila = $resultado->fetch_assoc()) {
    $datos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>
