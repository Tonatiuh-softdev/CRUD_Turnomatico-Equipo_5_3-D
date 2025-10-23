<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nexora";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los empleados (rol = 'empleado')
$sql = "SELECT id, nombre, rol as puesto FROM usuarios WHERE rol='empleado'";
$result = $conn->query($sql);
$empleados = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $empleados[] = $row;
    }
}

// Configurar zona horaria y obtener fecha/hora
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
$fecha = date("d \d\e F Y");

// Cargar el navbar
require '../../Recursos/PHP/redirecciones.php';
ob_start();
loadNavbar();
$navbarHTML = ob_get_clean();

// Convertir empleados a JSON para JavaScript
$empleadosJSON = json_encode($empleados);

// Incluir el archivo HTML
include __DIR__ . "/../HTML/empleados.html";
?>

<script>
// Insertar la hora y fecha en el header
document.getElementById('headerTime').innerHTML = '<?= $hora; ?><br><?= $fecha; ?>';

// Insertar el navbar
document.getElementById('navbar').outerHTML = `<?= addslashes($navbarHTML); ?>`;

// Cargar datos de empleados desde PHP
empleados = <?= $empleadosJSON; ?>;
id = empleados.length > 0 ? empleados[empleados.length-1].id + 1 : 1;

// Inicializar la tabla con los datos
mostrarEmpleados();
</script>
