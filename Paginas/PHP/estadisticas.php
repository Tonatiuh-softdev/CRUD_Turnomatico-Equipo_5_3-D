<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";
session_start();

// Control de acceso
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
    header("Location: /Paginas/PHP/login.php");
    exit;
}

// Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header("Location: /Paginas/PHP/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Estadísticas</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="/Paginas/CSS/estadisticas.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span><?= htmlspecialchars($_SESSION['rol']) ?></span>
        <form method="post" style="margin:0;">
            <button type="submit" name="cerrar_sesion" style="background:red;color:white;border:none;padding:5px 8px;border-radius:4px;cursor:pointer;">Salir</button>
        </form>
        <div class="time"><?= date("h:i a"); ?><br><?= date("d \d\e F Y"); ?></div>
    </div>
</header>

<div class="container">
<?php
require '../../Recursos/PHP/redirecciones.php';
loadNavbar();
?>
<main>
    <h2> Estadísticas de Turnos</h2>

    <div class="container-grafica">
        <div class="chart-container">
            <canvas id="grafica"></canvas>
        </div>

        <div class="info">
            <div class="info-item">
                <span class="serv1"></span>
                <b>Servicio 1</b><br>
                Al mes se atienden xxx turnos del servicio 1
            </div>
            <div class="info-item">
                <span class="serv2"></span>
                <b>Servicio 2</b><br>
                Al mes se atienden xxx turnos del servicio 2
            </div>
            <div class="info-item">
                <span class="serv3"></span>
                <b>Servicio 3</b><br>
                Al mes se atienden xxx turnos del servicio 3
            </div>
        </div>
    </div>

    <div class="botones">
        <button onclick="actualizarGrafica('dia')">DÍA</button>
        <button onclick="actualizarGrafica('mes')">MES</button>
        <button onclick="actualizarGrafica('año')">AÑO</button>
    </div>
</main>
</div>

<script>
const datos = {
    dia: [50, 30, 20],
    mes: [400, 280, 220],
    año: [4800, 3350, 2600]
};

const colores = ['#a595f9', '#ff5959', '#ffbe5b'];
const etiquetas = ['Servicio1', 'Servicio2', 'Servicio3'];

const ctx = document.getElementById('grafica').getContext('2d');
let grafica = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: etiquetas,
        datasets: [{
            data: datos.año,
            backgroundColor: colores,
            borderWidth: 0
        }]
    },
    options: {
        cutout: '60%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.raw + ' turnos';
                    }
                }
            }
        }
    }
});

function actualizarGrafica(periodo) {
    grafica.data.datasets[0].data = datos[periodo];
    grafica.update();
}
</script>
</body>
</html>
