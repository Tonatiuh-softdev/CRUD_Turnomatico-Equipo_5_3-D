<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();


// El id_tienda ya está disponible desde ctrl_sesion.php
$id_tienda = $_SESSION["id_tienda"];
// Obtener los empleados de la tienda actual
$sql = "SELECT u.id, u.nombre, u.rol as puesto 
        FROM usuarios u 
        WHERE u.rol = 'empleado' AND u.ID_Tienda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tienda);
$stmt->execute();
$result = $stmt->get_result();
$empleados = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $empleados[] = $row;
    }
}

$stmt->close();

require __DIR__ . '/../HTML/empleados.html';
?>

