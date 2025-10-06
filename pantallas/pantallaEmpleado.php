<?php
include __DIR__ . "/../conexion.php";

// 🔹 Procesar acciones de los botones
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];

        if ($accion === "atender") {
            // Buscar el siguiente turno en espera
            $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC LIMIT 1";
            $res_siguiente = $conn->query($sql_siguiente);

            if ($res_siguiente->num_rows > 0) {
                $siguiente = $res_siguiente->fetch_assoc()['id'];

                // Marcar todos los turnos como atendidos para evitar conflictos
                $conn->query("UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO'");

                // Cambiar el siguiente turno a ATENDIENDO
                $conn->query("UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = $siguiente");
            }
        }

        if ($accion === "pausar") {
            // Poner en pausa el turno que se esté atendiendo
            $conn->query("UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO'");
        }
    }

    // 🔄 Recargar página para reflejar cambios
    header("Location: pantallaEmpleado.php");
    exit;
}

// 🔹 Obtener número de personas en espera
$sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA'";
$res_espera = $conn->query($sql_espera);
$en_espera = $res_espera->fetch_assoc()['total'];

// 🔹 Obtener el turno actual (el que está siendo atendido)
$sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' ORDER BY id DESC LIMIT 1";
$res_turno = $conn->query($sql_turno);
$turno_actual = $res_turno->fetch_assoc();

// Si no hay turno atendiendo, mostrar el último generado
if (!$turno_actual) {
    $sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos ORDER BY id DESC LIMIT 1";
    $res_turno = $conn->query($sql_turno);
    $turno_actual = $res_turno->fetch_assoc();
}

// 🔹 Obtener lista de espera
$sql_lista = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC";
$res_lista = $conn->query($sql_lista);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Empleado</title>
<link rel="stylesheet" href="../css/components/pantalla_empleado.css">
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
            <p><?= htmlspecialchars($turno_actual['estado'] ?? '---') ?></p>
        </div>
    </div>

    <!-- Acciones -->
    <form method="post" class="actions">
        <button type="button" class="btn" onclick="toggleLista()">LISTA DE ESPERA</button>
        <button type="submit" name="accion" value="pausar" class="btn">PAUSAR ATENCIÓN</button>
        <button type="submit" name="accion" value="atender" class="btn">ATENDER SIGUIENTE</button>
    </form>

    <!-- 🔹 Lista de espera -->
    <table id="tablaLista" class="lista-espera">
        <tr>
            <th>Código de turno</th>
            <th>Módulo (tipo)</th>
            <th>Estado</th>
        </tr>
        <?php while ($fila = $res_lista->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['codigo_turno']) ?></td>
                <td><?= htmlspecialchars($fila['tipo']) ?></td>
                <td><?= htmlspecialchars($fila['estado']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Registrar nuevo cliente -->
    <a href="/pantallas/login.php" class="register">REGISTRAR NUEVO CLIENTE</a>
</main>

<script>
function toggleLista() {
    const tabla = document.getElementById('tablaLista');
    tabla.style.display = tabla.style.display === 'none' || tabla.style.display === '' ? 'table' : 'none';
}
</script>

</body>
</html>
