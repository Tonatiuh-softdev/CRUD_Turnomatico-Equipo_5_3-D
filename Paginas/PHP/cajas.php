<?php
// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");

// Cargar el navbar
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();


require __DIR__ . '/../HTML/cajas.html';    
?>
