<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";
session_start();

// Solo permitir acceso si es empleado o admin
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
    header("Location: ./login.php");
    exit;
}

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

// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");

// Cargar el navbar
require '../../Recursos/PHP/redirecciones.php';
ob_start();
loadNavbar();
$navbarHTML = ob_get_clean();

// Obtener los datos de clientes
$sql = "SELECT id, nombre, email, status FROM usuarios WHERE rol='cliente' ORDER BY id ASC";
$result = $conn->query($sql);
$clientesHTML = "";

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clientesHTML .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['email']}</td>
            <td>
                <form method='POST'>
                    <input type='hidden' name='cambiar_status_id' value='{$row['id']}'>
                    <input type='hidden' name='nuevo_status' value='{$row['status']}'>
                    <button type='submit' class='btn-status'>{$row['status']}</button>
                </form>
            </td>
            <td>
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='eliminar_id' value='{$row['id']}'>
                    <button type='submit' class='btn-eliminar'>Eliminar</button>
                </form>
            </td>
        </tr>";
    }
} else {
    $clientesHTML = "<tr><td colspan='5'>No hay clientes registrados</td></tr>";
}

// Incluir el archivo HTML
include __DIR__ . "/../HTML/clientes.html";
?>

<script>
// Insertar la hora y fecha en el header
document.getElementById('headerTime').innerHTML = '<?= $hora; ?><br><?= $fecha; ?>';

// Insertar el navbar
document.getElementById('navbar').outerHTML = `<?= addslashes($navbarHTML); ?>`;

// Insertar los datos de clientes en la tabla
document.getElementById('tablaClientes').innerHTML = `<?= addslashes($clientesHTML); ?>`;
</script>
