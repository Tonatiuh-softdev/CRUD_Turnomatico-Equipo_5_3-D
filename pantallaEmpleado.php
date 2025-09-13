<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Empleado</title>
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

    /* Layout principal */
    main {
        padding: 20px;
        background: #fff;
        min-height: calc(100vh - 60px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        gap: 20px;
    }

    /* Tarjetas de información */
    .info-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 15px;
    }

    .card {
        background: #a8bfd5;
        color: #fff;
        border-radius: 25px;
        padding: 50px;
        text-align: center;
        min-width: 350px;
        margin-top: 10px;
    }

    .card h3 {
        margin: 0;
        font-size: 20px;
        font-weight: normal;
    }

    .card p {
        margin: 15px;
        font-size: 30px;
        font-weight: bold;
    }

    /* Botones de acción */
    .actions {
        display: flex;
        border-radius: 10px;
        gap: 90px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 10px;
    }

    .btn {
        background: #c5d4e7;
        color: #fff;
        border: none;
        border-radius: 25px;
        padding: 35px;
        font-size: 25px;
        font-weight: bold;
        cursor: pointer;
        text-align: center;
        min-width: 350px;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;   /* Centra verticalmente */
        gap: 8px;      
    }        /* Espacio entre ícono y texto */

    .btn:hover {
        background: #7a9ac1;
    }

    /* Botón registrar */
    .register {
        margin-top: 50px;
        background: #a8bfd5;
        color: #fff;
        border-radius: 25px;
        padding: 50px;
        font-size: 25px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        min-width: 550px;
        transition: 0.3s;
    }

    .register:hover {
        background: #86a7c5ff;
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
        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg> Empleado</span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>

<main>
    <!-- Info de turno -->
    <div class="info-container">
        <div class="card">
            <h3>EN ESPERA</h3>
            <p>30</p>
        </div>
        <div class="card">
            <h3>ATG 2 &nbsp; 002</h3>
            <p>Ximena Vega</p>
        </div>
        <div class="card">
            <h3>ESTATUS</h3>
            <p>CLIENTE</p>
        </div>
    </div>

    <!-- Acciones -->
    <div class="actions">
        <button class="btn">LISTA DE ESPERA</button>
        <button class="btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 40px; height: 40px;">
  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM9 8.25a.75.75 0 0 0-.75.75v6c0 .414.336.75.75.75h.75a.75.75 0 0 0 .75-.75V9a.75.75 0 0 0-.75-.75H9Zm5.25 0a.75.75 0 0 0-.75.75v6c0 .414.336.75.75.75H15a.75.75 0 0 0 .75-.75V9a.75.75 0 0 0-.75-.75h-.75Z" clip-rule="evenodd" />
</svg> PAUSAR ATENCIÓN</button>
        <button class="btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 40px; height: 40px;">
  <path fill-rule="evenodd" d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
  <path fill-rule="evenodd" d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
</svg> ATENDER SIGUIENTE</button>
    </div>

    <!-- Registrar nuevo cliente -->
    <div class="register">
        REGISTRAR NUEVO CLIENTE
    </div>
</main>
</body>
</html>
