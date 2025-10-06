<?php
include __DIR__ . "/../conexion.php";
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_ES.UTF-8");
$fecha = strftime("%d de %B %Y");

// 🔹 Obtener turnos en espera
$sql_turnos = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC";
$res_turnos = $conn->query($sql_turnos);
$turnos = [];
while ($row = $res_turnos->fetch_assoc()) {
    $turnos[] = $row;
}

// 🔹 Obtener turno actual
$sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' ORDER BY id DESC LIMIT 1";
$res_actual = $conn->query($sql_actual);
$turnoActual = $res_actual->fetch_assoc();

// Si no hay turno en atención, mostrar el último generado
if (!$turnoActual) {
    $sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos ORDER BY id DESC LIMIT 1";
    $res_actual = $conn->query($sql_actual);
    $turnoActual = $res_actual->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sistema de Turnos</title>
<link rel="stylesheet" href="../css/components/pantallaDeTurno.css">
</head>
<body>
<header>
    <div class="empresa">
        <img src="../img/img.Logo_blanco.png" alt="logo">
        ClickMatic
    </div>
    <div class="info">
        <?= $hora ?><br>
        <?= ucfirst($fecha) ?>
    </div>
</header>

<div class="contenedor">
    <!-- Lista de turnos -->
    <div class="lista-turnos">
        <table class="tabla">
            <tr>
                <th>Turno</th>
                <th>Módulo</th>
            </tr>
            <?php foreach ($turnos as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t["codigo_turno"]) ?></td>
                    <td><?= htmlspecialchars($t["tipo"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Panel turno actual -->
    <div class="panel-actual">
        <div class="datos">
            <div><?= htmlspecialchars($turnoActual["codigo_turno"]) ?></div>
            <div><?= htmlspecialchars($turnoActual["tipo"]) ?></div>
        </div>
    </div>
</div>

<footer>
    ClickMatic
</footer>
</body>
</html>
