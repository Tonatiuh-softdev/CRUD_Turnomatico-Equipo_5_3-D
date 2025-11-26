<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // Conecta a la base de datos
loadLogIn(); // Verifica sesión si aplica
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Estadísticas</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="/Paginas/CSS/estadisticas.css">
</head>
<body>

<?php loadHeader(); ?>
<div class="container">
<?php loadNavbar(); ?>

<main>
    <h2><i class="bi bi-pie-chart-fill"></i> Estadísticas de Turnos</h2>

    <!-- KPI CARDS -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <i class="bi bi-people-fill kpi-icon"></i>
            <div>
                <h4>Total de Turnos</h4>
                <p id="totalTurnos">—</p>
            </div>
        </div>

        <div class="kpi-card">
            <i class="bi bi-person-badge-fill kpi-icon"></i>
            <div>
                <h4>Clientes</h4>
                <p id="kpiClientes">—</p>
            </div>
        </div>

        <div class="kpi-card">
            <i class="bi bi-person-fill kpi-icon"></i>
            <div>
                <h4>Visitantes</h4>
                <p id="kpiVisitantes">—</p>
            </div>
        </div>
    </div>

    <div class="container-grafica">
        <div class="chart-container">
            <canvas id="grafica"></canvas>
        </div>

        <div class="info">
            <div id="serviciosLeyenda">
                <!-- Se llena dinámicamente con JS -->
            </div>
        </div>
    </div>

    <div class="botones">
        <button onclick="actualizarGrafica('dia')"><i class="bi bi-calendar-day"></i> Día</button>
        <button onclick="actualizarGrafica('mes')"><i class="bi bi-calendar-week"></i> Mes</button>
        <button onclick="actualizarGrafica('año')"><i class="bi bi-calendar2-check"></i> Año</button>
    </div>
</main>
</div>

<script src="../JS/estadisticas.js"></script>
</body>
</html>

