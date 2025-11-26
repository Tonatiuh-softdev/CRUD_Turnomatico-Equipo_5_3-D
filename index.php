<?php 




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Paginas/CSS/informacion.css">
    <link rel="stylesheet" href="/Paginas/Componentes/CSS/footer.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../../index.php">
                <img src="/img/img.Logo_blanco-Photoroom.png" alt="" width="70">
            </a>
        </div>
        <a href="/Paginas/PHP/login.php"><button>Iniciar sesion</button></a>

    </header>
    <main>
    <div class="container">
        
        <div class="hero">
            <img src="/img/fila_de_personas.avif" alt="Fila de personas">
            <div class="overlay">
                <div class="descripcion">
                    <p>Nexora es un sistema turnomatico que te va a ayudar a
                        sistematizar tus negocios con cajas y servicios customisables
                    </p>
                </div>
                <a href="/Paginas/PHP/registroTienda.php"><button class="cta">Regístrate</button></a>
            </div>
        </div>
    </div>

<section class="beneficios">
    <h2>¿Por qué usar Nexora?</h2>
    <div class="beneficios-grid">
        <div class="beneficio">
            <img src="/img/iconRapidez.png" alt="">
            <h3>Atención más rápida</h3>
            <p>Reduce filas y mejora la experiencia de tus clientes.</p>
        </div>

        <div class="beneficio">
            <img src="/img/iconControl.png" alt="">
            <h3>Control total</h3>
            <p>Gestiona cajas, servicios y turnos desde una interfaz clara.</p>
        </div>

        <div class="beneficio">
            <img src="/img/iconPantalla.png" alt="">
            <h3>Pantalla de espera</h3>
            <p>Muestra turnos en tiempo real desde cualquier dispositivo.</p>
        </div>
    </div>
</section>
<section class="pasos">
    <h2>¿Cómo funciona?</h2>
    <div class="pasos-grid">
        <div class="paso">
            <span>1</span>
            <h4>Registra tu negocio</h4>
            <p>Crea una cuenta y personaliza tus servicios.</p>
        </div>

        <div class="paso">
            <span>2</span>
            <h4>Genera turnos</h4>
            <p>Los clientes solicitan turnos desde tu panel o presencial.</p>
        </div>

        <div class="paso">
            <span>3</span>
            <h4>Atiende sin filas</h4>
            <p>Visualiza y llama turnos desde cualquier dispositivo.</p>
        </div>
    </div>
</section>
    <!-- CARRUSEL -->
<div class="carousel-container">
    <div class="carousel">
        <img src="/img/Adios.png" class="carousel-img" alt="Imagen 1">
        <img src="/img/Hola de nuevo.png" class="carousel-img" alt="Imagen 2">
        <img src="/img/hola.png" class="carousel-img" alt="Imagen 3">
    </div>

    <button class="prev-btn">&#10094;</button>
    <button class="next-btn">&#10095;</button>
</div>

<!-- MODAL PARA AMPLIAR IMAGEN -->
<div id="modal" class="modal">
    <span class="close">&times;</span>
    <img id="modal-img" class="modal-content">
</div>

    <div class="container" >
        
        <img src="/img/presentacion.png" alt=""  class="card">
        
        <ul>
            <h1>Ten el control del orden de tu establecimiento</h1>
            <li><h5>Solicita los turnos</h5></li>
            <li><h5>Muestralos en la pantalla de espera</h5></li>
            <li><h5>Manejalo desde una interfaz limpia</h5></li>
        </ul>
        
        
        
       
           
       
    </div>
    </main>
    <?php include __DIR__ . '/Paginas/Componentes/HTML/footer.html'; ?>
    <script src="/Paginas/JS/carrusel.js"></script>

</body>
</html>