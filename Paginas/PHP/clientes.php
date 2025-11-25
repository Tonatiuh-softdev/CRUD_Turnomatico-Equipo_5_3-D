<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

// El id_tienda ya está disponible desde ctrl_sesion.php
$id_tienda = $_SESSION["id_tienda"];
// 🔹 Cambiar status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_status_id'])) {
    $id = intval($_POST['cambiar_status_id']);
    $nuevo_status = $_POST['nuevo_status'] === 'Activo' ? 'Inactivo' : 'Activo';
    $stmt = $conn->prepare("UPDATE usuarios SET status=? WHERE id=? AND rol='cliente' AND ID_Tienda=?");
    $stmt->bind_param("sii", $nuevo_status, $id, $id_tienda);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Eliminar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=? AND rol='cliente' AND ID_Tienda=?");
    $stmt->bind_param("ii", $idEliminar, $id_tienda);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Editar cliente (nombre + status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {

    $id = intval($_POST['editar_id']);
    $nombre = trim($_POST['editar_nombre']);
    $status = trim($_POST['editar_status']);

    $stmt = $conn->prepare("UPDATE usuarios 
                            SET nombre=?, status=? 
                            WHERE id=? AND rol='cliente' AND ID_Tienda=?");

    $stmt->bind_param("ssii", $nombre, $status, $id, $id_tienda);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Obtener clientes de la tienda actual
$sql = "SELECT id, nombre, email, status FROM usuarios WHERE rol='cliente' AND ID_Tienda=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$result = $stmt->get_result();
$clientes = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $clientes[] = $row;
    }
}

$stmt->close();

require __DIR__ . '/../HTML/clientes.html';
?>

