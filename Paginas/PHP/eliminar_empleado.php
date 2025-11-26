<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);

    if ($id > 0) {

        $sql = "DELETE FROM usuarios WHERE id = ? AND rol = 'empleado'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Error al eliminar"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "msg" => "ID inválido"]);
    }
}
