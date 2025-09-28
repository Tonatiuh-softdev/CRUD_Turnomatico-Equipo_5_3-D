<?php

function conexion(){
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "clickmatic";
    $port = "3306";  

    try {
        // Creamos la conexión
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        
        // Configuramos atributos
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "✅ Conexión exitosa a MySQL";
    } catch (PDOException $e) {
        echo "❌ Error en la conexión: " . $e->getMessage();
        $conn = null;
    }

    return $conn;
}
?>