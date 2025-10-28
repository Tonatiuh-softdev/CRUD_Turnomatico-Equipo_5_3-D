<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

// Obtener los empleados (rol = 'empleado')
$sql = "SELECT id, nombre, rol as puesto FROM usuarios WHERE rol='empleado'";
$result = $conn->query($sql);
$empleados = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $empleados[] = $row;
    }
}

require __DIR__ . '/../HTML/empleados.html';
?>

