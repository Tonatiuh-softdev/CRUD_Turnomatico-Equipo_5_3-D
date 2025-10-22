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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Clientes</title>
<link rel="stylesheet" href="../CSS/clientes.css">

</head>
<body>

<?php

loadHeader();
?>
<div class="container">

<?php


loadNavbar();
?>
<main>
    <h2>Administrar Clientes</h2>

    <!-- Botón grande para abrir modal -->
    <button class="btn-agregar" onclick="abrirModalCliente()">➕ Agregar Cliente</button>

    <!-- Modal agregar cliente -->
    <div id="modalCliente" class="modal">
        <div class="modal-contenido">
            <h3>Agregar Cliente</h3>
            <form method="POST">
                <input type="text" name="nombreCliente" placeholder="Nombre del cliente" required>
                <button type="submit">Agregar</button>
            </form>
            <button class="cerrar" onclick="cerrarModalCliente()">Cerrar</button>
        </div>
    </div>

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

<script>
// Abrir y cerrar modal
const modalCliente = document.getElementById("modalCliente");
function abrirModalCliente(){ modalCliente.style.display = "flex"; }
function cerrarModalCliente(){ modalCliente.style.display = "none"; }
// Cerrar modal al hacer clic fuera del contenido
window.addEventListener("click", function(e){
    if(e.target === modalCliente) cerrarModalCliente();
});
</script>
</body>
</html>

