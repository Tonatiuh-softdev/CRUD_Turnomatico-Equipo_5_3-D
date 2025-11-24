<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();
loadLogIn();

// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");

// Roles permitidos
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado', 'admin', 'superadmin'])) {
    header("Location: ./login.php");
    exit;
}

// 🔹 Obtener ID_Tienda de la sesión
$id_tienda = $_SESSION["id_tienda"];

// 🔹 Crear caja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numeroCaja'])) {
    $numero = intval($_POST['numeroCaja']);
    $estado = trim($_POST['estadoCaja']);
    $servicio = !empty($_POST['idServicio']) ? intval($_POST['idServicio']) : null;

    if ($numero > 0) {
        $stmt = $conn->prepare("INSERT INTO Caja (Numero_Caja, Estado, ID_Servicio, ID_Tienda) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $numero, $estado, $servicio, $id_tienda);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 🔹 Eliminar caja (verificar que pertenece a la tienda actual)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $stmt = $conn->prepare("DELETE FROM Caja WHERE ID_Caja=? AND ID_Tienda=?");
    $stmt->bind_param("ii", $idEliminar, $id_tienda);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Editar caja (verificar que pertenece a la tienda actual)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $id = intval($_POST['editar_id']);
    $numero = trim($_POST['editar_numero']);
    $estado = trim($_POST['editar_estado']);
    $servicio = !empty($_POST['editar_servicio']) ? intval($_POST['editar_servicio']) : null;

    $stmt = $conn->prepare("UPDATE Caja SET Numero_Caja=?, Estado=?, ID_Servicio=? WHERE ID_Caja=? AND ID_Tienda=?");
    $stmt->bind_param("ssiii", $numero, $estado, $servicio, $id, $id_tienda);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Cargar navbar

// 🔹 Obtener todas las cajas de la tienda actual
$sql = "SELECT c.*, s.Nombre AS ServicioNombre 
        FROM Caja c 
        INNER JOIN Servicio s ON c.ID_Servicio = s.ID_Servicio
        WHERE c.ID_Tienda = ?
        ORDER BY c.ID_Caja ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 🔹 Obtener servicios para el select (solo de la tienda actual)
$servicios_sql = "SELECT ID_Servicio, Nombre FROM Servicio WHERE ID_Tienda=? ORDER BY Nombre ASC";
$servicios_stmt = $conn->prepare($servicios_sql);
$servicios_stmt->bind_param("i", $id_tienda);
$servicios_stmt->execute();
$servicios = $servicios_stmt->get_result();
$servicios_stmt->close();

require __DIR__ . '/../HTML/cajas.html';    
?>
