<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

// Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CONTROL DE ACCESO POR ROL
if (!isset($_SESSION['rol'])) {
    header("Location: ./login.php");
    exit;
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
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

// Incluir el archivo HTML
include __DIR__ . "/../HTML/index.html";
?>

<script>
// Insertar rol del usuario
document.getElementById('userRole').textContent = '<?= $rol; ?>';

// Insertar la hora y fecha
document.getElementById('headerTime').innerHTML = '<?= $hora; ?><br><?= $fecha; ?>';

// Mostrar/ocultar botón regresar según rol
<?php if ($mostrarBtnRegresar): ?>
document.getElementById('btnRegresar').style.display = 'inline-block';
<?php endif; ?>

// Insertar el navbar si corresponde
<?php if ($mostrarNavbar): ?>
document.getElementById('navbar').outerHTML = `<?= addslashes($navbarHTML); ?>`;
<?php else: ?>
document.getElementById('navbar').remove();
<?php endif; ?>
</script>
