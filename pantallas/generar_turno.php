<?php
session_start();
include __DIR__ . "/../conexion.php"; // Ajusta si tu conexión está en otra ruta

// 1️⃣ Consultar el último turno de hoy
$sql = "SELECT codigo_turno FROM turnos WHERE DATE(fecha) = CURDATE() ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimoTurno = $row['codigo_turno'];

    // Extraer número y sumarle 1
    $numero = (int) substr($ultimoTurno, 2) + 1;
} else {
    // Si no hay turnos hoy, empieza en 1
    $numero = 1;
}

// 2️⃣ Formatear el nuevo turno
$codigo_turno = "A-" . str_pad($numero, 3, "0", STR_PAD_LEFT);

// 3️⃣ Insertar en la base de datos
$stmt = $conn->prepare("INSERT INTO turnos (codigo_turno) VALUES (?)");
$stmt->bind_param("s", $codigo_turno);
$stmt->execute();

// 4️⃣ Devolver turno al navegador
echo $codigo_turno;
?>
