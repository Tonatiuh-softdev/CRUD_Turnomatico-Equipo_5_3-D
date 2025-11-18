<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();
loadLogIn();

// El id_tienda ya está disponible desde ctrl_sesion.php
$id_tienda = $_SESSION["id_tienda"];

// ============================================================
// 🔹 AGREGAR SERVICIO (desde fetch AJAX)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO servicio (Nombre, Descripcion, ID_Tienda) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_tienda);
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
        $stmt = $conn->prepare("UPDATE servicio SET Nombre=?, Descripcion=? WHERE ID_Servicio=? AND ID_Tienda=?");
        $stmt->bind_param("ssii", $nombre, $descripcion, $id, $id_tienda);
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
    $stmt = $conn->prepare("DELETE FROM servicio WHERE ID_Servicio=? AND ID_Tienda=?");
    $stmt->bind_param("ii", $id, $id_tienda);
    $stmt->execute();
    $stmt->close();
    exit;
}

// ============================================================
// 🔹 OBTENER SERVICIOS (para la tabla vía AJAX)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar'])) {
    $sql = "SELECT * FROM servicio WHERE ID_Tienda=? ORDER BY ID_Servicio ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tienda);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
    echo json_encode($data);
    exit;
}

// ============================================================
// 🔹 OBTENER SERVICIOS PARA LA VISTA PRINCIPAL
// ============================================================
$sql = "SELECT ID_Servicio, Nombre, Descripcion FROM servicio WHERE ID_Tienda=? ORDER BY ID_Servicio ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$result = $stmt->get_result();
$servicios = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $servicios[] = $row;
    }
}

$stmt->close();





// ============================================================
// 🔹 CARGAR VISTA HTML PRINCIPAL
// ============================================================
require __DIR__ . '/../HTML/servicios.html';
?>
