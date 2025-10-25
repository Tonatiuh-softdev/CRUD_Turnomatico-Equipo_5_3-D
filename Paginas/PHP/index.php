<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();



// Evitar notice si la sesión ya está iniciada
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

    header("Location: ./pantallaEmpleado.php");
    exit;
}

// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set('America/Mexico_City');
$hora = date('h:i a');
$fecha = date('d \d\e F Y');

// Obtener rol del usuario
$rol = $_SESSION['rol'] ?? 'Empleado';

// Determinar si debe mostrar navbar (solo admin y superadmin)
$mostrarNavbar = in_array($rol, ['admin', 'superadmin']);

// Cargar el navbar si corresponde
$navbarHTML = '';
if ($mostrarNavbar) {
    require '../../Recursos/PHP/redirecciones.php';
    ob_start();
    loadNavbar();
    $navbarHTML = ob_get_clean();
}

// Determinar si mostrar botón regresar (solo empleado)
$mostrarBtnRegresar = ($rol === 'empleado');


?>

<style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }

 

    /* Layout general */
    .container { display: flex; height: calc(100vh - 50px); }

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
      transition: transform 0.2s, box-shadow 0.2s;
      text-decoration: none;
    }
    .card:link, .card:visited { text-decoration: none; color: inherit; }
    .card:nth-child(1) { background: #92aecbff; }
    .card:nth-child(2) { background: #bfd4eaff; }
    .card:nth-child(3) { background: #e1ebf6; grid-column: span 2; }
    .card img { width: 40px; margin-bottom: 10px; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.2); }

    /* Botón regresar (flecha) */
    .btn-regresar {
        width: 24px;
        height: 24px;
        background-color: #2b3d57;
        mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>') no-repeat center;
        -webkit-mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>') no-repeat center;
        display:inline-block;
        cursor:pointer;
        transition: background-color 0.2s, transform 0.3s;
    }
    .btn-regresar:hover { background-color: #3f5675; transform: translateX(-5px); }

    /* Botón cerrar sesión (ícono puerta con flecha) */
    .btn-cerrar {
        width: 24px;
        height: 24px;
        background-color: #d9534f;
        mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v10zm8-11h-6v2h6v10h-6v2h6c1.1 0 2-.9 2-2v-10c0-1.1-.9-2-2-2z"/></svg>') no-repeat center;
        -webkit-mask: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5v10zm8-11h-6v2h6v10h-6v2h6c1.1 0 2-.9 2-2v-10c0-1.1-.9-2-2-2z"/></svg>') no-repeat center;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.2s;
    }
    .btn-cerrar:hover { background-color: #c9302c; transform: translateY(-2px); }
</style>
<script>
// Insertar rol del usuario
document.getElementById('userRole').textContent = '<?= $rol; ?>';
</script>
<?php loadHeader(); ?>




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
