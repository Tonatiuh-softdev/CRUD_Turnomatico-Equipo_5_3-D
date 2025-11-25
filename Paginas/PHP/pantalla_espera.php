<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_ES.UTF-8");
$fecha = strftime("%d de %B %Y");

// 🔹 Obtener ID_Tienda de la sesión
$id_tienda = $_SESSION["id_tienda"];

// 🔹 Si se selecciona una tienda diferente, cambiar la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_tienda'])) {
    $nueva_tienda = intval($_POST['id_tienda_select']);
    $id_tienda = $nueva_tienda;
    $_SESSION["id_tienda"] = $id_tienda;
}

// 🔹 Obtener todas las tiendas
$tiendas = [];
$sql_tiendas = "SELECT ID_Tienda, Nombre FROM tienda ORDER BY Nombre ASC";
$res_tiendas = $conn->query($sql_tiendas);
while ($row = $res_tiendas->fetch_assoc()) {
    $tiendas[] = $row;
}

// 🔹 Obtener servicios de la tienda actual
$servicios = [];
$sql_servicios = "SELECT ID_Servicio, nombre FROM Servicio WHERE ID_Tienda = ? ORDER BY nombre ASC";
$stmt_servicios = $conn->prepare($sql_servicios);
$stmt_servicios->bind_param("i", $id_tienda);
$stmt_servicios->execute();
$res_servicios = $stmt_servicios->get_result();
while ($row = $res_servicios->fetch_assoc()) {
    $servicios[] = $row;
}
$stmt_servicios->close();

// 🔹 Obtener turnos en espera
$sql_turnos = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? ORDER BY id ASC";
$stmt_turnos = $conn->prepare($sql_turnos);
$stmt_turnos->bind_param("i", $id_tienda);
$stmt_turnos->execute();
$res_turnos = $stmt_turnos->get_result();
$turnos = [];
while ($row = $res_turnos->fetch_assoc()) {
    $turnos[] = $row;
}
$stmt_turnos->close();

// Debug: Si no hay turnos, obtener todos para verificar
if (empty($turnos)) {
    $sql_debug = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE ID_Tienda = ? ORDER BY id ASC LIMIT 10";
    $stmt_debug = $conn->prepare($sql_debug);
    $stmt_debug->bind_param("i", $id_tienda);
    $stmt_debug->execute();
    $res_debug = $stmt_debug->get_result();
    while ($row = $res_debug->fetch_assoc()) {
        $turnos[] = $row;
    }
    $stmt_debug->close();
}

// 🔹 Obtener turno actual
$sql_actual = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? ORDER BY id DESC LIMIT 1";
$stmt_actual = $conn->prepare($sql_actual);
$stmt_actual->bind_param("i", $id_tienda);
$stmt_actual->execute();
$res_actual = $stmt_actual->get_result();
$turnoActual = $res_actual->fetch_assoc();
$stmt_actual->close();

// Si no hay turno en atención, mostrar el último generado
if (!$turnoActual) {
    $sql_last = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE ID_Tienda = ? ORDER BY id DESC LIMIT 1";
    $stmt_last = $conn->prepare($sql_last);
    $stmt_last->bind_param("i", $id_tienda);
    $stmt_last->execute();
    $res_last = $stmt_last->get_result();
    $turnoActual = $res_last->fetch_assoc();
    $stmt_last->close();
}

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
        $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? ORDER BY id ASC LIMIT 1";
        $stmt_sig = $conn->prepare($sql_siguiente);
        $stmt_sig->bind_param("i", $id_tienda);
        $stmt_sig->execute();
        $res_siguiente = $stmt_sig->get_result();

        if ($res_siguiente->num_rows > 0) {
            $siguiente = $res_siguiente->fetch_assoc()['id'];
            
            // Marcar como atendido el turno actual
            $sql_actualizar = "UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO' AND ID_Tienda = ?";
            $stmt_up1 = $conn->prepare($sql_actualizar);
            $stmt_up1->bind_param("i", $id_tienda);
            $stmt_up1->execute();
            $stmt_up1->close();
            
            // Atender el siguiente
            $sql_atender = "UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = ? AND ID_Tienda = ?";
            $stmt_up2 = $conn->prepare($sql_atender);
            $stmt_up2->bind_param("ii", $siguiente, $id_tienda);
            $stmt_up2->execute();
            $stmt_up2->close();
        }
        $stmt_sig->close();
    }

    if ($accion === "pausar") {
        $sql_pausar = "UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO' AND ID_Tienda = ?";
        $stmt_pausa = $conn->prepare($sql_pausar);
        $stmt_pausa->bind_param("i", $id_tienda);
        $stmt_pausa->execute();
        $stmt_pausa->close();
    }

    // 🔄 Recargar página
    header("Location: ./pantallaEmpleado.php");
    exit;
}

$conn->close();

require __DIR__ . '/../HTML/pantalla_espera.html';
?>
