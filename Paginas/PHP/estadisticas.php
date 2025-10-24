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
?>



    <main>
      <div class="card">
        <img src="https://img.icons8.com/ios-filled/50/000000/conference.png"/>
        Pantalla de espera
      </div>
      <div class="card">
        <img src="https://img.icons8.com/ios-filled/50/000000/return.png"/>
        Pantalla de turno
      </div>
      <div class="card">
        <img src="https://img.icons8.com/ios-filled/50/000000/conference-call.png"/>
        Pantalla de empleado
      </div>
    </main>
  </div>
</body>
</html>