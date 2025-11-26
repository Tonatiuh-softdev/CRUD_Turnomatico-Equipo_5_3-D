<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

$clienteLogueado = false;
$nombreCliente = "";

if (isset($_SESSION["usuario"]) && $_SESSION["rol"] === "cliente") {
    $clienteLogueado = true;
    $nombreCliente = $_SESSION["usuario"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tomar turno</title>
<link rel="stylesheet" href="../CSS/pantallaTomarTurno.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="../../Recursos/JS/tranducciones.js"></script>
<link rel="stylesheet" href="../../Recursos/CSS/theme-vars.css">
<script src="../../Recursos/JS/theme-init.js"></script>
</head>
<body>

<header>
  <div class="logo">
    <img src="/img/img.Logo_blanco-Photoroom.png" width="70"/>
    <span>ClickMatic</span>
  </div>
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
</div>

<script>
const overlay = document.querySelector('.overlay');
const modal = document.querySelector('.modal');
const cerrar = document.querySelector('.cerrar');

cerrar.addEventListener('click', cerrarModal);
overlay.addEventListener('click', cerrarModal);

const clienteLogueado = <?php echo $clienteLogueado ? 'true' : 'false'; ?>;
const nombreCliente = "<?php echo htmlspecialchars($nombreCliente ?? ''); ?>";

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay un turno en la URL (después de generar)
    const urlParams = new URLSearchParams(window.location.search);
    const turno = urlParams.get('turno');
    
    if (turno) {
        // Mostrar el turno generado
        document.getElementById("turnoModal").textContent = turno;
        document.getElementById("nombreModal").textContent = clienteLogueado ? nombreCliente : "Visitante";
        overlay.classList.add('active');
        modal.classList.add('active');
        
        // Limpiar la URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

function abrirModalVisitante() {
    // 🔹 Limpiar sesión del cliente antes de ir a visitante
    fetch("logout_cliente.php").then(() => {
        window.location.href = "seleccionar_servicio_visitante.php";
    });
}

function abrirModalCliente() {
    window.location.href = "seleccionar_servicio.php";
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
