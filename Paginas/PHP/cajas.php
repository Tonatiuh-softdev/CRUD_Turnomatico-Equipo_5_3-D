<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();
loadLogIn();

// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");



// 🔹 Crear caja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numeroCaja'])) {
    $numero = intval($_POST['numeroCaja']);
    $estado = trim($_POST['estadoCaja']);
    $servicio = !empty($_POST['idServicio']) ? intval($_POST['idServicio']) : null;

    if ($numero > 0) {
        $stmt = $conn->prepare("INSERT INTO Caja (Numero_Caja, Estado, ID_Servicio) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $numero, $estado, $servicio);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 🔹 Eliminar caja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $stmt = $conn->prepare("DELETE FROM Caja WHERE ID_Caja=?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 🔹 Editar caja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $id = intval($_POST['editar_id']);
    $numero = intval($_POST['editar_numero']);
    $estado = trim($_POST['editar_estado']);
    $servicio = !empty($_POST['editar_servicio']) ? intval($_POST['editar_servicio']) : null;

    $stmt = $conn->prepare("UPDATE Caja SET Numero_Caja=?, Estado=?, ID_Servicio=? WHERE ID_Caja=?");
    $stmt->bind_param("isii", $numero, $estado, $servicio, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Cargar navbar


// 🔹 Obtener todas las cajas
$sql = "SELECT c.*, s.Nombre AS ServicioNombre 
        FROM Caja c 
        LEFT JOIN Servicio s ON c.ID_Servicio = s.ID_Servicio
        ORDER BY c.ID_Caja ASC";
$result = $conn->query(query: $sql);

// 🔹 Obtener servicios para el select
$servicios = $conn->query("SELECT ID_Servicio, Nombre FROM Servicio ORDER BY Nombre ASC");

require __DIR__ . '/../HTML/cajas.html';    
?>