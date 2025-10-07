<?php
// Aquí podrías agregar lógica PHP, por ejemplo:
// session_start();
// include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Círculos entrelazados</title>
  <link rel="stylesheet" href="../css/components/pantalla_espera.css">

  <!-- Íconos (Font Awesome) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<header>
  <div class="logo">
    <img src="../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
    <span>ClickMatic</span>
  </div>

<!-- 🔹 Botón con ícono de pantalla (lado derecho) -->
<a href="/pantallas/pantallaDeTurno.php" class="btn-pantalla" title="Pantalla">
  <i class="fa-solid fa-display"></i>
</a>

</header>

<main></main>

<div class="texto">TOMA TU TURNO</div>

<div class="contenedor">
  <div class="circulo rojo"></div>
  <div class="circulo azul"></div>
  <div class="circulo verde"></div>

  <button class="boton izquierda" onclick="window.location.href='/pantallas/login.php'">CLIENTE</button>
  <button class="boton derecha" onclick="abrirModal()">VISITANTE</button>
</div>

<!-- Modal oculto -->
<div class="overlay" id="modal">
  <div class="modal">
    <div class="turno-modal">
      <button class="cerrar" onclick="cerrarModal()">✖</button>
      <img src="/img/img.Logo_blanco-Photoroom.png" alt="Mi Imagen" class="imagen">
      <div class="texto">ClickMatic</div>
      <div class="rectangulo">Turno</div>
      <div class="rectangulo1">A-001</div>
      <div class="rectangulo2"></div>
      <div class="rectangulo3"></div>
      <div class="texto1">TOMA TURNO |</div>
    </div>
  </div>
</div>



  
<script>
  const overlay = document.querySelector('.overlay');
  const modal = document.querySelector('.modal');
  const cerrar = document.querySelector('.cerrar');

  // 🔹 Abrir modal y obtener turno
  async function abrirModal() {
    try {
      // Obtener turno del servidor PHP
      const response = await fetch("generar_turno.php");
      const turno = await response.text();

      // Mostrar el turno dinámico dentro del modal
      document.querySelector(".rectangulo1").textContent = turno;

      // Mostrar el modal con animación
      overlay.classList.add('active');
      modal.classList.add('active');
    } catch (error) {
      console.error("Error al generar turno:", error);
    }
  }

  // 🔹 Cerrar modal con animación
  function cerrarModal() {
    modal.classList.add('closing');
    overlay.classList.remove('active');
    setTimeout(() => {
      modal.classList.remove('active', 'closing');
    }, 600); // duración de animación
  }

  // 🔹 Eventos de cierre
  cerrar.addEventListener('click', cerrarModal);
  overlay.addEventListener('click', cerrarModal);
</script>


</body>
</html>
