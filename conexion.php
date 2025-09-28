<?php

function conexion(){
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $BD = "voces_db";

    $conn = new mysqli($host,$usuario,$password,$BD);

    if($conn->connect_error){
        die("Error de conexion: " . $conn->connect_error);
    }

   return $conn; 
}
?>