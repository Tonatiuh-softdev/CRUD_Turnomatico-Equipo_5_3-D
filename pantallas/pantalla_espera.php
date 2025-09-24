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
</head>
<body>

<header>
  <div class="logo">
    <img src="../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
    <span>ClickMatic</span>
  </div>
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
      <div class="texto2">Mexico <p>Manzanillo Colima</p></div>
      <div class="time">01:26 am<br>25 de Agosto 2025</div>
    </div>
  </div>
</div>

<script>
  function abrirModal() {
    document.getElementById('modal').style.display = 'flex';
  }
  function cerrarModal() {
    document.getElementById('modal').style.display = 'none';
  }
  const overlay = document.querySelector('.overlay');
const modal = document.querySelector('.modal');
const cerrar = document.querySelector('.cerrar');

function abrirModal() {
  overlay.classList.add('active');
  modal.classList.add('active');
}

function cerrarModal() {
  modal.classList.add('closing');
  overlay.classList.remove('active');
  setTimeout(() => {
    modal.classList.remove('active', 'closing');
  }, 600); // Duración de la animación
}

cerrar.addEventListener('click', cerrarModal);
overlay.addEventListener('click', cerrarModal);

</script>

</body>
</html>
