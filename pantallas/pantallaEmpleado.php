<?php
include __DIR__ . "/../conexion.php";

// 🔹 Obtener número de personas en espera
$sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA'";
$res_espera = $conn->query($sql_espera);
$en_espera = $res_espera->fetch_assoc()['total'];

// 🔹 Obtener el último turno generado
$sql_turno = "SELECT codigo_turno, tipo FROM turnos ORDER BY id DESC LIMIT 1";
$res_turno = $conn->query($sql_turno);
$turno_actual = $res_turno->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Empleado</title>
<link rel="stylesheet" href="../css/components/pantalla_empleado.css">
<meta http-equiv="refresh" content="3"> <!-- 🔁 Actualiza cada 3 segundos -->
</head>
<body>
<header>
    <div class="logo">
        <img src="/img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
            </svg> Empleado
        </span>
        <div class="time">
            <?php
                date_default_timezone_set('America/Mexico_City');
                echo date('h:i a') . "<br>" . date('d \d\e F Y');
            ?>
        </div>
    </div>
</header>

<main>
    <!-- Info de turno -->
    <div class="info-container">
        <div class="card">
            <h3>EN ESPERA</h3>
            <p><?= $en_espera ?></p>
        </div>
        <div class="card">
            <p>Turno actual</p>
            <h3><?= htmlspecialchars($turno_actual['codigo_turno'] ?? '---') ?></h3>
        </div>
        <div class="card">
            <h3>ESTATUS</h3>
            <p><?= htmlspecialchars($turno_actual['tipo'] ?? '---') ?></p>
        </div>
    </div>

    <!-- Acciones -->
    <div class="actions">
        <button class="btn">LISTA DE ESPERA</button>
        <button class="btn">PAUSAR ATENCIÓN</button>
        <button class="btn">ATENDER SIGUIENTE</button>
    </div>

    <!-- Registrar nuevo cliente -->
    <a href="/pantallas/login.php" class="register">REGISTRAR NUEVO CLIENTE</a>
</main>
</body>
</html>
