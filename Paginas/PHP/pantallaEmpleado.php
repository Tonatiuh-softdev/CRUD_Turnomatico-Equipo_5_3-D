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

// 🔹 Procesar acciones de botones
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {
    $accion = $_POST["accion"];
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

    if ($accion === "atender") {
        $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? ORDER BY id ASC LIMIT 1";
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
$sql_espera = "SELECT COUNT(*) AS total FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ?";
$stmt_count = $conn->prepare($sql_espera);
$stmt_count->bind_param("i", $id_tienda);
$stmt_count->execute();
$resultado = $stmt_count->get_result();
$fila = $resultado->fetch_assoc();
$total = $fila["total"] + 1;
$stmt_count->close();

$sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' AND ID_Tienda = ? ORDER BY id DESC LIMIT 1";
$res_turno = $conn->query($sql_turno);
$turno_actual = $res_turno->fetch_assoc();

if (!$turno_actual) {
    $sql_turno = "SELECT codigo_turno, tipo, estado FROM turnos WHERE ID_Tienda = ? ORDER BY id DESC LIMIT 1";
    $res_turno = $conn->query($sql_turno);
    $turno_actual = $res_turno->fetch_assoc();
}

$sql_lista = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'EN_ESPERA' AND ID_Tienda = ? ORDER BY id ASC";
$res_lista = $conn->query($sql_lista);

// Cerrar conexión antes de incluir la vista
$conn->close();

require __DIR__ . '/../HTML/pantallaEmpleado.html'; 
?>

