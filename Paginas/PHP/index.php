<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

//  Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  CONTROL DE ACCESO POR ROL
if (!isset($_SESSION['rol'])) {
    header("Location: ./login.php");
    exit;
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();    // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header("Location: ./login.php");
    exit;
}

// 🔹 Procesar acciones de botones
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {
    $accion = $_POST["accion"];

    if ($accion === "atender") {
        $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC LIMIT 1";
        $res_siguiente = $conn->query($sql_siguiente);

        if ($res_siguiente->num_rows > 0) {
            $siguiente = $res_siguiente->fetch_assoc()['id'];
            $conn->query("UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO'");
            $conn->query("UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = $siguiente");
        }
    }

    if ($accion === "pausar") {
        $conn->query("UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO'");
    }

    // 🔄 Recargar página
    header("Location: ./pantallaEmpleado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Turnos</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>

<header>
    <div class="logo">
      <img src="../../img/img.Logo_blanco-Photoroom.png" width="70"/>
    </div>
    <div class="user-panel" style="display:flex; align-items:center; gap:8px;">
        <span style="display:flex; align-items:center; gap:5px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
            </svg>
            <?= $_SESSION['rol'] ?? 'Empleado' ?>
        </span>

        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'empleado'): ?>
            <a href="../../pantallas/admin/" class="btn-regresar" title="Regresar"></a>
        <?php endif; ?>

        <form method="post" style="margin:0;">
            <button type="submit" name="cerrar_sesion" class="btn-cerrar" title="Cerrar sesión"></button>
        </form>

        <div class="time">
            <?php
                date_default_timezone_set('America/Mexico_City');
                echo date('h:i a') . "<br>" . date('d \d\e F Y');
            ?>
        </div>
    </div>
</header>

<div class="container">
    <!-- Solo admin y superadmin ven la barra de navegación -->
    <?php if(isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin','superadmin'])): ?>
        <?php require '../../Recursos/PHP/redirecciones.php'; loadNavbar(); ?>
    <?php endif; ?>

    <main>
        <a href="../../pantallas/pantalla_espera.php" class="card">
          <img src="https://img.icons8.com/ios-filled/50/000000/conference.png"/>
          Pantalla de espera
        </a>
        <a href="./pantallaDeTurno.php" class="card">
          <img src="https://img.icons8.com/ios-filled/50/000000/return.png"/>
          Pantalla de turno
        </a>
        <a href="./pantallaEmpleado.php" class="card">
          <img src="https://img.icons8.com/ios-filled/50/000000/conference-call.png"/>
          Pantalla de empleado
        </a>
    </main>
</div>

</body>
</html>
