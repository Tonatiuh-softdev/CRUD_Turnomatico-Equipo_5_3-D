<?php
include __DIR__ . "/../../conexion.php"; // Ajusta la ruta a tu conexión
session_start();

// Solo permitir acceso si es empleado o admin
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
    header("Location: login.php");
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

// 🔹 Agregar cliente desde el CRUD
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Clientes</title>
<style>
    body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }
    header { display: flex; justify-content: space-between; align-items: center; background: #eceae7ff; padding: 10px 20px; border-bottom: 1px solid #ddd; }
    header .logo { display: flex; align-items: center; font-weight: bold; }
    header .logo span { margin-left: 10px; font-size: 14px; }
    header .user { display: flex; align-items: center; gap: 15px; font-weight: bold; }
    header .time { font-size: 12px; color: #666; text-align: right; }
    .container { display: flex; height: calc(100vh - 50px); }
    aside { width: 220px; background: #9cb6d6ff; color: #fff; padding: 15px 10px; display: flex; flex-direction: column; gap: 10px; }
    aside a { display: flex; align-items: center; padding: 40px; border-radius: 5px; color: #000000ff; text-decoration: none; font-size: 14px; }
    main { flex: 1; padding: 20px; background: #fff; overflow-y: auto; }
    h2 { margin-top: 0; }
    form { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    form input, form button { padding: 8px; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; background: #fafafa; }
    table th, table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    table th { background: #747e8bff; color: white; }
    .btn-eliminar { background: red; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
    .btn-eliminar:hover { background: darkred; }
    .btn-status { background: #2b3d57; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
    .btn-status:hover { background: #3f5675; }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg> Administrador</span>
        <div class="time"><?= date("h:i a"); ?><br><?= date("d \d\e F Y"); ?></div>
    </div>
</header>

<div class="container">
   <?php
require '../../elementos/redirecciones.php';
loadNavbar();
?>

<main>
    <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px;">
  <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
</svg> Administrar Clientes</h2>

    <!-- Formulario agregar -->
    <form method="POST" id="formCliente">
        <input type="text" name="nombreCliente" placeholder="Nombre del cliente" required>
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px;">
                <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
            </svg> Agregar Cliente
        </button>
    </form>

    <!-- Tabla clientes -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT id, nombre, email, status FROM usuarios WHERE rol='cliente' ORDER BY id ASC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
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
                echo "<tr><td colspan='5'>No hay clientes registrados</td></tr>";
            }
        ?>
        </tbody>
    </table>
</main>
</div>
</body>
</html>
