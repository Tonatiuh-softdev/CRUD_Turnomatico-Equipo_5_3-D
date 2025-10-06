<?php
include_once "conexion.php"; // aquí va tu archivo con la clase CConexion

$conexion = new CConexion();
$conn = $conexion->ConexionBD();

try {
    // Query solo con ID_Tienda y Nombre
    $sql = "SELECT idtienda, nombre FROM tb_tiendas";
    $stmt = $conn->query($sql);

    // Recorremos los resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['idtienda'] . " - Nombre: " . $row['nombre'] . "<br>";
    }

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>