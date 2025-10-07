<?php
include __DIR__ . "/../conexion.php";

// ✅ Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();    // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header("Location: login.php");
    exit;
}

// 🔹 Procesar acciones de botones
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {
    $accion = $_POST["accion"];

    if ($accion === "atender") {
        $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC LIMIT 1";
        $res_siguiente = $conn->query($sql_siguiente);

        if ($res_siguiente->num_rows > 0) {
            $siguiente = $res_siguiente->fetch_assoc()['id'];
            $conn->query("UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO'");
            $conn->query("UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = $siguiente");
        }
    }

    if ($accion === "pausar") {
        $conn->query("UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO'");
    }

    // 🔄 Recargar página
    header("Location: pantallaEmpleado.php");
    exit;
}

// 🔹 Obtener datos de turnos
$sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA'";
$res_espera = $conn->query($sql_espera);
$en_espera = $res_espera->fetch_assoc()['total'];

$sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' ORDER BY id DESC LIMIT 1";
$res_turno = $conn->query($sql_turno);
$turno_actual = $res_turno->fetch_assoc();

if (!$turno_actual) {
    $sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos ORDER BY id DESC LIMIT 1";
    $res_turno = $conn->query($sql_turno);
    $turno_actual = $res_turno->fetch_assoc();
}

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
<style>
/* Botón regresar (flecha) */
.btn-regresar {
    width: 24px;
    height: 24px;
    background-color: #2b3d57;
    mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>') no-repeat center;
    -webkit-mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>') no-repeat center;
    display:inline-block;
    cursor:pointer;
    transition: background-color 0.2s, transform 0.3s;
}

.btn-regresar:hover {
    background-color: #3f5675;
    transform: translateX(-5px);
}

/* Botón cerrar sesión (ícono puerta con flecha) */
.btn-cerrar {
    width: 24px;
    height: 24px;
    background-color: #d9534f;
    mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v10zm8-11h-6v2h6v10h-6v2h6c1.1 0 2-.9 2-2v-10c0-1.1-.9-2-2-2z"/></svg>') no-repeat center;
    -webkit-mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v10zm8-11h-6v2h6v10h-6v2h6c1.1 0 2-.9 2-2v-10c0-1.1-.9-2-2-2z"/></svg>') no-repeat center;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
}

.btn-cerrar:hover {
    background-color: #c9302c;
    transform: translateY(-2px);
}
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="/img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user-panel" style="display:flex; align-items:center; gap:8px;">
        <span style="display:flex; align-items:center; gap:5px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
            </svg>
            <?= $_SESSION['rol'] ?? 'Empleado' ?>
        </span>

        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'empleado'): ?>
            <a href="./admin/" class="btn-regresar" title="Regresar"></a>
        <?php endif; ?>

        <form method="post" style="margin:0;">
            <button type="submit" name="cerrar_sesion" class="btn-cerrar" title="Cerrar sesión"></button>
        </form>

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

    <form method="post" class="actions">
        <button type="button" class="btn" onclick="toggleLista()">LISTA DE ESPERA</button>
        <button type="submit" name="accion" value="pausar" class="btn">PAUSAR ATENCIÓN</button>
        <button type="submit" name="accion" value="atender" class="btn">ATENDER SIGUIENTE</button>
    </form>

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

    <a href="/pantallas/registro.php" class="register">REGISTRAR NUEVO CLIENTE</a>
</main>

<script>
function toggleLista() {
    const tabla = document.getElementById('tablaLista');
    tabla.style.display = tabla.style.display === 'none' || tabla.style.display === '' ? 'table' : 'none';
}
</script>
</body>
</html>
