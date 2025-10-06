<?php
session_start();
include __DIR__ . "/../conexion.php"; // Ajusta la ruta según tu estructura

// 1️⃣ Buscar el último turno del día
$sql = "SELECT codigo_turno FROM turnos WHERE DATE(fecha) = CURDATE() ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimoTurno = $row['codigo_turno'];
    $numero = (int) substr($ultimoTurno, 2) + 1;
} else {
    $numero = 1;
}

// 2️⃣ Crear el nuevo turno
$codigo_turno = "A-" . str_pad($numero, 3, "0", STR_PAD_LEFT);
$tipo = "VISITANTE";
$estado = "EN_ESPERA";

// 3️⃣ Insertar el turno en la base
$stmt = $conn->prepare("INSERT INTO turnos (codigo_turno, tipo, estado) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $codigo_turno, $tipo, $estado);
$stmt->execute();

// 4️⃣ Devolver el turno
echo $codigo_turno;

$conn->close();
?>
