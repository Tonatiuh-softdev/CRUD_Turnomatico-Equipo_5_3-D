<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

// ✅ Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Obtener ID_Tienda de la sesión
$id_tienda = $_SESSION["id_tienda"];

// 🔹 Obtener ID_Caja seleccionada (por defecto la primera de la tienda)
$id_caja = isset($_POST['id_caja_select']) ? intval($_POST['id_caja_select']) : null;

// 🔹 Obtener todas las cajas de la tienda
$cajas = [];
$sql_cajas = "SELECT ID_Caja, numero_caja, ID_Servicio FROM Caja WHERE ID_Tienda = ? ORDER BY numero_caja ASC";
$stmt_cajas = $conn->prepare($sql_cajas);
$stmt_cajas->bind_param("i", $id_tienda);
$stmt_cajas->execute();
$res_cajas = $stmt_cajas->get_result();
while ($row = $res_cajas->fetch_assoc()) {
    $cajas[] = $row;
}
$stmt_cajas->close();

// Si no hay caja seleccionada y hay cajas disponibles, usar la primera
if (!$id_caja && !empty($cajas)) {
    $id_caja = $cajas[0]['ID_Caja'];
}

// 🔹 Obtener el ID_Servicio de la caja seleccionada
$id_servicio = null;
if ($id_caja) {
    foreach ($cajas as $caja) {
        if ($caja['ID_Caja'] == $id_caja) {
            $id_servicio = $caja['ID_Servicio'];
            break;
        }
    }
}

// 🔹 Procesar acciones de botones
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {
    $accion = $_POST["accion"];

    if ($accion === "atender" && $id_servicio) {
        $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? AND ID_Servicio = ? ORDER BY id ASC LIMIT 1";
        $stmt_sig = $conn->prepare($sql_siguiente);
        $stmt_sig->bind_param("ii", $id_tienda, $id_servicio);
        $stmt_sig->execute();
        $res_siguiente = $stmt_sig->get_result();

        if ($res_siguiente->num_rows > 0) {
            $siguiente = $res_siguiente->fetch_assoc()['id'];
            
            // Marcar como atendido el turno actual
            $sql_atendido = "UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? AND ID_Servicio = ?";
            $stmt_up1 = $conn->prepare($sql_atendido);
            $stmt_up1->bind_param("ii", $id_tienda, $id_servicio);
            $stmt_up1->execute();
            $stmt_up1->close();
            
            // Atender el siguiente
            $sql_atender = "UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = ? AND ID_Tienda = ? AND ID_Servicio = ?";
            $stmt_up2 = $conn->prepare($sql_atender);
            $stmt_up2->bind_param("iii", $siguiente, $id_tienda, $id_servicio);
            $stmt_up2->execute();
            $stmt_up2->close();
        }
        $stmt_sig->close();
    }

    if ($accion === "pausar" && $id_servicio) {
        $sql_pausar = "UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? AND ID_Servicio = ?";
        $stmt_pausa = $conn->prepare($sql_pausar);
        $stmt_pausa->bind_param("ii", $id_tienda, $id_servicio);
        $stmt_pausa->execute();
        $stmt_pausa->close();
    }

    // 🔄 Recargar página
    header("Location: pantallaEmpleado.php");
    exit;
}

// 🔹 Obtener cantidad de turnos en espera (solo del servicio seleccionado)
$en_espera = 0;
if ($id_servicio) {
    $sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? AND ID_Servicio = ?";
    $stmt_count = $conn->prepare($sql_espera);
    $stmt_count->bind_param("ii", $id_tienda, $id_servicio);
    $stmt_count->execute();
    $resultado = $stmt_count->get_result();
    $fila = $resultado->fetch_assoc();
    $en_espera = $fila["total"];
    $stmt_count->close();
}

// 🔹 Obtener turno actual (solo del servicio seleccionado)
$turno_actual = null;
if ($id_servicio) {
    // Primero buscar ATENDIENDO
    $sql_turno = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? AND ID_Servicio = ? ORDER BY id DESC LIMIT 1";
    $stmt_turno = $conn->prepare($sql_turno);
    $stmt_turno->bind_param("ii", $id_tienda, $id_servicio);
    $stmt_turno->execute();
    $res_turno = $stmt_turno->get_result();
    $turno_actual = $res_turno->fetch_assoc();
    $stmt_turno->close();

    // Si no hay turno en atención, mostrar el siguiente EN_ESPERA (no PAUSADO ni ATENDIDO)
    if (!$turno_actual) {
        $sql_next = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE (estado = 'EN_ESPERA' OR estado = 'PAUSADO') AND ID_Tienda = ? AND ID_Servicio = ? ORDER BY id ASC LIMIT 1";
        $stmt_next = $conn->prepare($sql_next);
        $stmt_next->bind_param("ii", $id_tienda, $id_servicio);
        $stmt_next->execute();
        $res_next = $stmt_next->get_result();
        $turno_actual = $res_next->fetch_assoc();
        $stmt_next->close();
    }
}

// 🔹 Obtener lista de turnos en espera (solo del servicio seleccionado)
$lista_turnos = [];
if ($id_servicio) {
    $sql_lista = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? AND ID_Servicio = ? ORDER BY id ASC";
    $stmt_lista = $conn->prepare($sql_lista);
    $stmt_lista->bind_param("ii", $id_tienda, $id_servicio);
    $stmt_lista->execute();
    $res_lista = $stmt_lista->get_result();
    while ($row = $res_lista->fetch_assoc()) {
        $lista_turnos[] = $row;
    }
    $stmt_lista->close();
}

// Cerrar conexión antes de incluir la vista
$conn->close();

require __DIR__ . '/../HTML/pantallaEmpleado.html'; 
?>

