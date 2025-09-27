<?php
function ConexionBD() {
    $conn = null; // evitar warning

    $host = "localhost";
    $port = "5432"; 
    $dbname = "ClickMatic";
    $username = "postgres";
    $password = "root";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
        echo "Se conectó correctamente a la Base de Datos";
    } 
    catch (PDOException $exp) {
        echo "No se pudo conectar a la base de datos: " . $exp->getMessage();
    }

    return $conn;
}
?>