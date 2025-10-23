<?php
// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");

// Cargar el navbar
require '../../Recursos/PHP/redirecciones.php';

// Iniciar el buffer de salida para capturar el navbar
ob_start();
loadNavbar();
$navbarHTML = ob_get_clean();

// Incluir el archivo HTML
include __DIR__ . "/../HTML/cajas.html";
?>

<script>
// Insertar la hora y fecha en el header
document.getElementById('headerTime').innerHTML = '<?= $hora; ?><br><?= $fecha; ?>';

// Insertar el navbar
document.getElementById('navbar').outerHTML = '<?= addslashes($navbarHTML); ?>';
</script>
