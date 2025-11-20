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
            $sql_atendido = "UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO' AND ID_Tienda = ?";
            $stmt_up1 = $conn->prepare($sql_atendido);
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
    header("Location: pantallaEmpleado.php");
    exit;
}

// 🔹 Obtener cantidad de turnos en espera
$sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ?";
$stmt_count = $conn->prepare($sql_espera);
$stmt_count->bind_param("i", $id_tienda);
$stmt_count->execute();
$resultado = $stmt_count->get_result();
$fila = $resultado->fetch_assoc();
$en_espera = $fila["total"];
$stmt_count->close();

// 🔹 Obtener turno actual
$sql_turno = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? ORDER BY id DESC LIMIT 1";
$stmt_turno = $conn->prepare($sql_turno);
$stmt_turno->bind_param("i", $id_tienda);
$stmt_turno->execute();
$res_turno = $stmt_turno->get_result();
$turno_actual = $res_turno->fetch_assoc();
$stmt_turno->close();

// Si no hay turno en atención, mostrar el último generado
if (!$turno_actual) {
    $sql_last = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE ID_Tienda = ? ORDER BY id DESC LIMIT 1";
    $stmt_last = $conn->prepare($sql_last);
    $stmt_last->bind_param("i", $id_tienda);
    $stmt_last->execute();
    $res_last = $stmt_last->get_result();
    $turno_actual = $res_last->fetch_assoc();
    $stmt_last->close();
}

// 🔹 Obtener lista de turnos en espera
$sql_lista = "SELECT codigo_turno, tipo, estado, nombre_cliente FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? ORDER BY id ASC";
$stmt_lista = $conn->prepare($sql_lista);
$stmt_lista->bind_param("i", $id_tienda);
$stmt_lista->execute();
$res_lista = $stmt_lista->get_result();
$lista_turnos = [];
while ($row = $res_lista->fetch_assoc()) {
    $lista_turnos[] = $row;
}
$stmt_lista->close();

// Cerrar conexión antes de incluir la vista
$conn->close();

require __DIR__ . '/../HTML/pantallaEmpleado.html'; 
?>

