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
</div>

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


