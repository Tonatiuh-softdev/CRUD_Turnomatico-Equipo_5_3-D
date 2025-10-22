<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();




date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_ES.UTF-8");
$fecha = strftime("%d de %B %Y");

// 🔹 Obtener turnos en espera
$sql_turnos = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC";
$res_turnos = $conn->query($sql_turnos);
$turnos = [];
while ($row = $res_turnos->fetch_assoc()) {
    $turnos[] = $row;
}

// 🔹 Obtener turno actual
$sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' ORDER BY id DESC LIMIT 1";
$res_actual = $conn->query($sql_actual);
$turnoActual = $res_actual->fetch_assoc();

// Si no hay turno en atención, mostrar el último generado
if (!$turnoActual) {
    $sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos ORDER BY id DESC LIMIT 1";
    $res_actual = $conn->query($sql_actual);
    $turnoActual = $res_actual->fetch_assoc();
}

$conn->close();


// ✅ Evitar notice si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();    // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header("Location: login.php");
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
    header("Location: pantallaEmpleado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sistema de Turnos</title>
<link rel="stylesheet" href="../css/components/pantallaDeTurno.css">
</head>

<body>
<?php

loadHeader();
?>

<div class="contenedor">
    <!-- Lista de turnos -->
    <div class="lista-turnos">
        <table class="tabla">
            <tr>
                <th>Turno</th>
                <th>Módulo</th>
            </tr>
            <?php foreach ($turnos as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t["codigo_turno"]) ?></td>
                    <td><?= htmlspecialchars($t["tipo"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Panel turno actual -->
    <div class="panel-actual">
        <div class="datos">
            <div><?= htmlspecialchars($turnoActual["codigo_turno"]) ?></div>
            <div><?= htmlspecialchars($turnoActual["tipo"]) ?></div>
        </div>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pantalla de Espera</title>
<link rel="stylesheet" href="../CSS/pantallaTomarTurno.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
    <span>ClickMatic</span>
  </div>
  <a href="./pantallaDeTurno.php" class="btn-pantalla" title="Pantalla">
    <i class="fa-solid fa-display"></i>
  </a>
</header>

<main>
  <div class="contenedor">
    <div class="texto">TOMA TU TURNO</div>
    <div class="circulo rojo"></div>
    <div class="circulo azul"></div>
    <div class="circulo verde"></div>
  </div>

  <div class="botones-container">
    <!-- Botón CLIENTE -->
    <?php if ($clienteLogueado): ?>
        <button class="boton" onclick="abrirModalCliente()">CLIENTE</button>
    <?php else: ?>
        <button class="boton" onclick="window.location.href='/Paginas/PHP/login_Cliente.php'">CLIENTE</button>
    <?php endif; ?>

    <!-- Botón VISITANTE -->
    <button class="boton" onclick="abrirModalVisitante()">VISITANTE</button>
  </div>
</main>

<!-- Modal -->
<div class="overlay" id="modal">
  <div class="modal">
    <div class="turno-modal">
      <button class="cerrar">✖</button>
      <img src="../../img/img.Logo_blanco-Photoroom.png" alt="Logo" class="imagen">
      <div class="texto">ClickMatic</div>
      <div class="rectangulo">Turno</div>
      <div class="rectangulo1" id="turnoModal">...</div>
      <div class="rectangulo4" id="nombreModal">...</div>
      <div class="texto1">TOMA TURNO |</div>
    </div>
</div>

<footer>
    Nexora
</footer>
<script>
const overlay = document.querySelector('.overlay');
const modal = document.querySelector('.modal');
const cerrar = document.querySelector('.cerrar');

cerrar.addEventListener('click', cerrarModal);
overlay.addEventListener('click', cerrarModal);

// Generar turno visitante
async function abrirModalVisitante() {
    try {
        const response = await fetch("../../Recursos/PHP/generar_turno.php?tipo=visitante");
        const turno = await response.text();
        document.getElementById("turnoModal").textContent = turno;
        document.getElementById("nombreModal").textContent = "Visitante";
        overlay.classList.add('active');
        modal.classList.add('active');
    } catch (error) {
        console.error("Error al generar turno visitante:", error);
    }
}

// Generar turno cliente logueado
async function abrirModalCliente() {
    try {
        const response = await fetch("../../Recursos/PHP/generar_turno.php?tipo=cliente");
        const turno = await response.text();
        document.getElementById("turnoModal").textContent = turno;
        document.getElementById("nombreModal").textContent = "<?php echo htmlspecialchars($nombreCliente); ?>";
        overlay.classList.add('active');
        modal.classList.add('active');

        // 🔹 Destruir sesión al sacar el turno (para que otro cliente pueda iniciar)
        await fetch("./logout_cliente.php");
    } catch (error) {
        console.error("Error al generar turno cliente:", error);
    }
}

function cerrarModal() {
    modal.classList.add('closing');
    overlay.classList.remove('active');
    setTimeout(() => {
        modal.classList.remove('active', 'closing');
    }, 600);
}
</script>

</body>
</html>
