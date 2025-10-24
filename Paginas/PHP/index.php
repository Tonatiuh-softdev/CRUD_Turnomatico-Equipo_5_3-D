<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();



//  Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

<?php

loadHeader();
?>

<div class="container">
    <!-- Solo admin y superadmin ven la barra de navegación -->
    <?php if(isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin','superadmin'])): ?>
        <?php loadNavbar(); ?>
    <?php endif; ?>

    <main>
        <a href="./pantalla_espera.php" class="card">
          <img src="https://img.icons8.com/ios-filled/50/000000/conference.png"/>
          Pantalla de espera
        </a>
        <a href="./pantallaTomarTurno.php" class="card">
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
