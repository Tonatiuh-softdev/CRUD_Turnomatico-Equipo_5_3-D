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

// 🔹 Agregar cliente desde el modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreCliente'])) {
    $nombre = trim($_POST['nombreCliente']);
    if (!empty($nombre)) {
        $password = password_hash("123456", PASSWORD_DEFAULT);
        $email = strtolower(str_replace(" ","",$nombre)) . "@example.com";
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, status, ID_Tienda) VALUES (?, ?, ?, 'cliente', 'Activo', ?)");
        $stmt->bind_param("sssi", $nombre, $email, $password, $id_tienda);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
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

