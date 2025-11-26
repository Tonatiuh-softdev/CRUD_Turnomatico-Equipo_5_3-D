<?php
session_start();

require 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "cliente") {
    echo json_encode(["success" => false, "error" => "No autenticado"]);
    exit;
}

$id_servicio = $_POST["id_servicio"] ?? null;
$id_tienda = $_SESSION["id_tienda"];
$nombre_cliente = $_SESSION["usuario"];

if (!$id_servicio) {
    echo json_encode(["success" => false, "error" => "Servicio no especificado"]);
    exit;
}

$conn = conexion();

// Generar código de turno
$sql_count = "SELECT COUNT(*) AS total FROM turnos WHERE ID_Tienda = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_tienda);
$stmt_count->execute();
$res_count = $stmt_count->get_result();
$row_count = $res_count->fetch_assoc();
$total = $row_count["total"] + 1;
$stmt_count->close();

$codigoTurno = "A" . str_pad($total, 3, "0", STR_PAD_LEFT);
$tipo = "CLIENTE";

// Insertar turno
$sql_turno = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado, ID_Tienda, ID_Servicio) 
              VALUES (?, ?, ?, 'EN_ESPERA', ?, ?)";
$stmt_turno = $conn->prepare($sql_turno);
$stmt_turno->bind_param("sssii", $codigoTurno, $tipo, $nombre_cliente, $id_tienda, $id_servicio);

if ($stmt_turno->execute()) {
    $stmt_turno->close();
    echo json_encode([
        "success" => true,
        "turno" => $codigoTurno
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "Error al generar turno: " . $stmt_turno->error
    ]);
}
?>
