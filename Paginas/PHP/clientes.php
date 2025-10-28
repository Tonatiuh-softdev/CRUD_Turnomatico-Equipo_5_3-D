<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();
// 🔹 Cambiar status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_status_id'])) {
    $id = intval($_POST['cambiar_status_id']);
    $nuevo_status = $_POST['nuevo_status'] === 'Activo' ? 'Inactivo' : 'Activo';
    $stmt = $conn->prepare("UPDATE usuarios SET status=? WHERE id=? AND rol='cliente'");
    $stmt->bind_param("si", $nuevo_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Eliminar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=? AND rol='cliente'");
    $stmt->bind_param("i", $idEliminar);
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
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, status) VALUES (?, ?, ?, 'cliente', 'Activo')");
        $stmt->bind_param("sss", $nombre, $email, $password);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

require __DIR__ . '/../HTML/clientes.html';
?>

