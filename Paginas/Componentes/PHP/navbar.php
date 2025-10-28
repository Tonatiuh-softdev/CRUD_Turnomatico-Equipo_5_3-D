<?php
function renderNavbar(){
  // Usar ruta basada en __DIR__ para que el include funcione aunque el script se ejecute desde
  // otro directorio de trabajo. __DIR__ apunta al directorio actual del archivo PHP.
  include __DIR__ . '/../HTML/navbar.html';
}
?>
