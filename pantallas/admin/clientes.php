<?php
require '../../elementos/redirecciones.php';
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
h2 { margin-top: 0; display: flex; align-items: center; gap: 10px; }
table { width: 100%; border-collapse: collapse; background: #fafafa; }
table th, table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
table th { background: #747e8bff; color: white; }
.btn-eliminar { background: red; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
.btn-eliminar:hover { background: darkred; }
.btn-status { background: #2b3d57; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
.btn-status:hover { background: #3f5675; }

/* Botón grande estilo servicios */
.btn-agregar {
    display: block;
    margin: 20px auto;
    padding: 15px 30px;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 30px;
    background: #2b3d57;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}
.btn-agregar:hover { background: #3f5675; }

/* Modal estilo servicios */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(50,50,50,0.9));
    justify-content: center;
    align-items: center;
}
.modal-contenido {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    width: 300px;
    text-align: center;
}
.modal-contenido h3 { margin-top: 0; }
.modal-contenido input { width: 90%; padding: 10px; margin-bottom: 15px; }
.modal-contenido button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background: #2b3d57;
    color: #fff;
    cursor: pointer;
}
.modal-contenido button:hover { background: #3f5675; }
.cerrar { margin-top: 10px; background: red !important; }
.cerrar:hover { background: darkred !important; }

</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span>Administrador</span>
        <div class="time"><?= date("h:i a"); ?><br><?= date("d \d\e F Y"); ?></div>
    </div>
</header>

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

