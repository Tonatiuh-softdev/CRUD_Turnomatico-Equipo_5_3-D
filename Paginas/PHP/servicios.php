<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();
loadLogIn();

// ============================================================
// 🔹 AGREGAR SERVICIO (desde fetch AJAX)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO Servicio (Nombre, Descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        $stmt->execute();
        $stmt->close();
    }
    exit;
}

// ============================================================
// 🔹 EDITAR SERVICIO
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (!empty($nombre)) {
        $stmt = $conn->prepare("UPDATE Servicio SET Nombre=?, Descripcion=? WHERE ID_Servicio=?");
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        $stmt->execute();
        $stmt->close();
    }
    exit;
}

// ============================================================
// 🔹 ELIMINAR SERVICIO
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM Servicio WHERE ID_Servicio=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    exit;
}

// ============================================================
// 🔹 OBTENER SERVICIOS (para la tabla vía AJAX)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar'])) {
    $result = $conn->query("SELECT * FROM Servicio ORDER BY ID_Servicio ASC");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}
// CONSULTA PARA LA VISTA PRINCIPAL
$result = $conn->query("SELECT * FROM Servicio ORDER BY ID_Servicio ASC");

// ============================================================
// 🔹 CARGAR VISTA HTML PRINCIPAL
// ============================================================
require __DIR__ . '/../HTML/servicios.html';
?>
