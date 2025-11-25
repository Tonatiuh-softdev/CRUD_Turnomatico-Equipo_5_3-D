<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../PHP/conexion.php';
$conn = conexion();

// 🔹 Obtener ID_Tienda de la sesión
$id_tienda = isset($_SESSION["id_tienda"]) ? $_SESSION["id_tienda"] : null;

// Validar que existe ID_Tienda
if (!$id_tienda) {
    echo json_encode(['error' => 'No hay tienda seleccionada']);
    exit;
}

$tipo = isset($_GET["tipo"]) ? strtoupper($_GET["tipo"]) : "VISITANTE";
$id_servicio = isset($_GET["id_servicio"]) ? intval($_GET["id_servicio"]) : null;
$nombreCliente = ($tipo === "CLIENTE" && isset($_SESSION["usuario"])) ? $_SESSION["usuario"] : null;

// Obtener el número total actual para generar el código usando prepared statement
$sql_count = "SELECT COUNT(*) AS total FROM turnos WHERE ID_Tienda = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_tienda);
$stmt_count->execute();
$resultado = $stmt_count->get_result();
$fila = $resultado->fetch_assoc();
$total = $fila["total"] + 1;
$stmt_count->close();

$codigoTurno = ($tipo === "CLIENTE" ? "A" : "B") . str_pad($total, 3, "0", STR_PAD_LEFT);

// Insertar turno en la base de datos con ID_Servicio
if ($id_servicio) {
    $query = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado, ID_Tienda, ID_Servicio) VALUES (?, ?, ?, 'EN_ESPERA', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $codigoTurno, $tipo, $nombreCliente, $id_tienda, $id_servicio);
} else {
    $query = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado, ID_Tienda) VALUES (?, ?, ?, 'EN_ESPERA', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $codigoTurno, $tipo, $nombreCliente, $id_tienda);
}

$stmt->execute();
$stmt->close();

echo $codigoTurno;
$conn->close();
?>
