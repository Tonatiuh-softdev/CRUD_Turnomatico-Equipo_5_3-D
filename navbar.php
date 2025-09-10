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

    aside a:hover {
        background: #88a1c3ff;
    }


</style>
</head>
<body>
<aside>
  <a href="index.php">🏠 Página principal</a>
  <a href="servicios.php">⚙️ Servicios</a>
  <a href="cajas.php">💲Cajas</a>
  <a href="empleados.php">👥 Empleados</a>
  <a href="clientes.php">🔧 Clientes</a>
  <a href="estadisticas.php">📊 Ver estadísticas</a>

</aside>
</body>