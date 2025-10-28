<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Turnos</title>
  <link rel="stylesheet" href="../CSS/estadisticas.css">
</head>
<body>
  <?php
  loadHeader();
  ?>

  <div class="container">
<?php

loadNavbar();
$navbarHTML = ob_get_clean();

// Incluir el archivo HTML
include __DIR__ . "/../HTML/estadisticas.html";
?>

<script>
// Insertar la hora y fecha en el header
document.getElementById('headerTime').innerHTML = '<?= $hora; ?><br><?= $fecha; ?>';

// Insertar el navbar
document.getElementById('navbar').outerHTML = `<?= addslashes($navbarHTML); ?>`;
</script>
