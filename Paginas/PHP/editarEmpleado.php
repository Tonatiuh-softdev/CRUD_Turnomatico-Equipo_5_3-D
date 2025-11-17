<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);
$nombre = trim($data['nombre'] ?? '');
$correo = trim($data['correo'] ?? '');
$puesto = trim($data['puesto'] ?? '');
$password = trim($data['password'] ?? '');

if ($id <= 0 || !$nombre || !$correo || !$puesto) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

if ($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nombre=?, email=?, rol=?, password=? WHERE id=? AND rol='empleado'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $correo, $puesto, $hash, $id);
} else {
    $sql = "UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=? AND rol='empleado'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $correo, $puesto, $id);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$stmt->close();
$conn->close();
?>
