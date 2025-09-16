<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Turnos</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }

    /* Barra superior */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #eceae7ff;
      padding: 10px 20px;
      border-bottom: 1px solid #ddd;
    }

    header .logo {
      display: flex;
      align-items: center;
      font-weight: bold;
    }

    header .logo span {
      margin-left: 10px;
      font-size: 14px;
    }

    header .user {
      display: flex;
      align-items: center;
      gap: 15px;
      font-weight: bold;
    }

    header .time {
      font-size: 12px;
      color: #666;
      text-align: right;
    }

    /* Layout general */
    .container {
      display: flex;
      height: calc(100vh - 50px);
    }

    /* Sidebar */
    aside {
      width: 220px;
      background: #9cb6d6ff;
      color: #fff;
      padding: 15px 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    aside a {
      display: flex;
      align-items: center;
      padding: 40px;
      border-radius: 5px;
      color: #000000ff;
      text-decoration: none;
      font-size: 14px;
    }


    /* Main */
    main {
      flex: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: 1fr 1fr;
      gap: 10px;
      padding: 20px;
    }

    .card {
      background: #dce6f3;
      border-radius: 8px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      font-weight: bold;
      flex-direction: column;
      color: #333;
    }

    .card:nth-child(1) {
      background: #92aecbff;
    }

    .card:nth-child(2) {
      background: #bfd4eaff;
    }

    .card:nth-child(3) {
      background: #e1ebf6;
      grid-column: span 2;
    }

    .card img {
      width: 40px;
      margin-bottom: 10px;
    }
    /*solo para quitar la linea de seleccion del texto*/
.card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background: #f1f1f1;
  border: none;
  text-decoration: none;  /* 👈 elimina el subrayado */
  font-size: 16px;
  font-family: Arial, sans-serif;
  color: #000;            /* 👈 asegura que el texto no quede azul */
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}


  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
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
require '../../navbar.php';
?>



<main>
  <a href="pantalla_espera.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/conference.png"/>
    Pantalla de espera
  </a>
  <a href="pantallaDeTurno.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/return.png"/>
    Pantalla de turno
  </a>
  <a href="pantallaEmpleado.php" class="card">
    <img src="https://img.icons8.com/ios-filled/50/000000/conference-call.png"/>
    Pantalla de empleado
  </a>
</main>


  </div>
</body>
</html>