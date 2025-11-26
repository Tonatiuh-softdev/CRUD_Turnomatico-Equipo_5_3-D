<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';
$conn = conexion();

// Obtener ID_Tienda de la sesión
$id_tienda = isset($_SESSION["id_tienda"]) ? $_SESSION["id_tienda"] : null;

if (!$id_tienda) {
    echo json_encode(['error' => 'No hay tienda seleccionada']);
    exit;
}

// Obtener servicios de la tienda
$sql = "SELECT ID_Servicio, nombre FROM Servicio WHERE ID_Tienda = ? ORDER BY nombre ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$resultado = $stmt->get_result();

$servicios = [];
while ($fila = $resultado->fetch_assoc()) {
    $servicios[] = $fila;
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($servicios);
?>
