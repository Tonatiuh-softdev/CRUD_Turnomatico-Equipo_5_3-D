<?php
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_MX.UTF-8"); 
$fecha = (new DateTime())->format("d \ \e F \ \e Y");

// Datos de ejemplo
$turnos = [
    ["turno" => "B-001", "modulo" => "1", "nombre" => "Camila Perez"],
    ["turno" => "B-002", "modulo" => "2", "nombre" => "Ximena Vega"],
    ["turno" => "B-003", "modulo" => "3", "nombre" => "Fatima Alvarez"],
    ["turno" => "B-004", "modulo" => "4", "nombre" => "Abigail Suarez"],
];

// Turno actual
$turnoActual = $turnos[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sistema de Turnos</title>
<link rel="stylesheet" href="../css/components/pantallaDeTurno.css">
</head>
<body>
    <header>
        <div class="empresa">
            <img src="img/img.Logo_blanco.png" alt="logo">
            ClickMatic
        </div>
        <div class="info">
            <?php echo $hora; ?><br>
            <?php echo ucfirst($fecha); ?>
        </div>
    </header>

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
                        <td><?php echo $t["turno"]; ?></td>
                        <td><?php echo $t["modulo"]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="nombre"><?php echo $t["nombre"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Panel turno actual -->
        <div class="panel-actual">
            <div class="datos">
                <div><?php echo $turnoActual["turno"]; ?></div>
                <div><?php echo str_pad($turnoActual["modulo"],3,"0",STR_PAD_LEFT); ?></div>
            </div>
            <div class="nombre">
                <?php echo $turnoActual["nombre"]; ?>
            </div>
        </div>
    </div>

    <footer>
        ClickMatic
    </footer>
</body>
</html>
