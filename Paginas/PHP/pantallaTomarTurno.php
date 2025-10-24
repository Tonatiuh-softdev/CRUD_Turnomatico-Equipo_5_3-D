<?php
session_start();
include __DIR__ . "/../../Recursos/PHP/conexion.php";

$clienteLogueado = false;
$nombreCliente = "";

if (isset($_SESSION["usuario"]) && $_SESSION["rol"] === "cliente") {
    $clienteLogueado = true;
    $nombreCliente = $_SESSION["usuario"];
}

// Incluir la vista HTML (contiene fragmentos PHP y usará las variables definidas arriba)
include __DIR__ . "/../HTML/pantallaTomarTurno.html";

?>
