<?php
require '../CRUD_Turnomatico-Equipo_5_3-D/Recursos/PHP/conexion.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

$tipo = isset($_GET["tipo"]) ? strtoupper($_GET["tipo"]) : "VISITANTE";
$nombreCliente = ($tipo === "CLIENTE" && isset($_SESSION["usuario"])) ? $_SESSION["usuario"] : null;

// Obtener el número total actual para generar el código
$resultado = $conn->query("SELECT COUNT(*) AS total FROM turnos");
$fila = $resultado->fetch_assoc();
$total = $fila["total"] + 1;

$codigoTurno = ($tipo === "CLIENTE" ? "A" : "B") . str_pad($total, 3, "0", STR_PAD_LEFT);

// Insertar turno en la base de datos
$query = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado) VALUES (?, ?, ?, 'EN_ESPERA')";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $codigoTurno, $tipo, $nombreCliente);
$stmt->execute();

echo $codigoTurno;
?>
