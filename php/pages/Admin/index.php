<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preload" href="../../css/pages/Admin/index.css" as="style">
  <link rel="stylesheet" href="../../css/pages/Admin/index.css">
  <title>Sistema de Turnos</title>

</head>
<body>
  <header>
    <div class="logo">
      <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
      <span>ClickMatic</span>
    </div>
    <div class="user">
      <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg>
 Administrador</span>
      <div class="time">
        01:26 am<br>
        25 de Agosto 2025
      </div>
    </div>
  </header>

  <div class="container">
<?php
require '../../elementos/redirecciones.php';
loadNavbar();

?>



<main>
  <a href="../pantalla_espera.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/conference.png"/>
    Pantalla de espera
  </a>
  <a href="../pantallaDeTurno.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/return.png"/>
    Pantalla de turno
  </a>
  <a href="../pantallaEmpleado.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/conference-call.png"/>
    Pantalla de empleado
  </a>
</main>


  </div>
</body>
</html>