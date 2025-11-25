<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // Conexión a la BD
loadLogIn();

// ID de tienda desde la sesión
$id_tienda = $_SESSION["id_tienda"];

/* ============================================================
   🔹 CAMBIAR STATUS (Empleado/Admin)
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_status_id'])) {

    $id = intval($_POST['cambiar_status_id']);
    $nuevo_status = $_POST['nuevo_status'] === 'Activo' ? 'Inactivo' : 'Activo';

    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET status=? 
        WHERE id=? AND rol IN ('Empleado','Admin') AND ID_Tienda=?
    ");
    $stmt->bind_param("sii", $nuevo_status, $id, $id_tienda);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ============================================================
   🔹 ELIMINAR EMPLEADO
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {

    $idEliminar = intval($_POST['eliminar_id']);

    $stmt = $conn->prepare("
        DELETE FROM usuarios 
        WHERE id=? AND rol IN ('Empleado','Admin') AND ID_Tienda=?
    ");
    $stmt->bind_param("ii", $idEliminar, $id_tienda);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ============================================================
   🔹 EDITAR EMPLEADO (nombre + correo + status + puesto)
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {

    $id = intval($_POST['editar_id']);
    $nombre = trim($_POST['editar_nombre']);
    $email = trim($_POST['editar_email']);
    $rol = trim($_POST['editar_rol']);   // Empleado o Admin
    $status = trim($_POST['editar_status']);

    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET nombre=?, email=?, rol=?, status=? 
        WHERE id=? AND ID_Tienda=?
    ");

    $stmt->bind_param("ssssii", $nombre, $email, $rol, $status, $id, $id_tienda);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ============================================================
   🔹 LISTAR EMPLEADOS
   ============================================================ */
$sql = "
    SELECT id, nombre, email, rol, status 
    FROM usuarios 
    WHERE rol IN ('Empleado','Admin') 
    AND ID_Tienda=?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$result = $stmt->get_result();

$empleados = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $empleados[] = $row;
    }
}

$stmt->close();

// Cargar vista
require __DIR__ . '/../HTML/empleados.html';
?>
