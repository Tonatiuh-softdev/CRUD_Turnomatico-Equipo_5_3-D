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
      background: #f3f2f0;
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
      background: #2b3d57;
      color: #fff;
      padding: 15px 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    aside a {
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 5px;
      color: #fff;
      text-decoration: none;
      font-size: 14px;
    }

    aside a:hover {
      background: #3f5675;
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
      background: #c7d8ea;
    }

    .card:nth-child(2) {
      background: #d0dff0;
    }

    .card:nth-child(3) {
      background: #e1ebf6;
      grid-column: span 2;
    }

    .card img {
      width: 40px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="https://img.icons8.com/ios-filled/50/000000/company.png" width="30"/>
      <span>Nombre de<br>nuestra empresa</span>
    </div>
    <div class="user">
      <span>👤 Administrador</span>
      <div class="time">
        01:26 am<br>
        25 de Agosto 2025
      </div>
    </div>
  </header>

  <div class="container">
<aside>
  <a href="#">🏠 Página principal</a>
  <a href="#">⚙️ Administrar servicios</a>
  <a href="#">💲 Administrar cajas</a>
  <a href="index_Empleado.php">👥 Administrar empleados</a>
  <a href="#">🔧 Administrar clientes</a>
  <a href="#">📊 Ver estadísticas</a>
</aside>


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