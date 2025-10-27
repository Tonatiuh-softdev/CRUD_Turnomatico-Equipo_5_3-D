<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

$clienteLogueado = false;
$nombreCliente = "";

if (isset($_SESSION["usuario"]) && $_SESSION["rol"] === "cliente") {
    $clienteLogueado = true;
    $nombreCliente = $_SESSION["usuario"];
}

require __DIR__ . '/../HTML/pantallaTomarTurno.html';   
?>
