<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();


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

// ✅ Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();    // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header("Location: ./login.php");
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
    header("Location: ./pantallaEmpleado.php");
    exit;
}

require __DIR__ . '/../HTML/pantalla_espera.html';
?>
