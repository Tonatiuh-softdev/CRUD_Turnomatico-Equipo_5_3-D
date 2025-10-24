<?php
// Este archivo ahora actúa como endpoint JSON para la pantalla de turnos.
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . "/../../Recursos/PHP/conexion.php";

// Asegurar sesión para leer rol u otras variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_ES.UTF-8");
$fecha = strftime("%d de %B %Y");

// Procesar POST: acciones y cierre de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cerrar sesión
    if (isset($_POST['cerrar_sesion'])) {
        session_unset();
        session_destroy();
        echo json_encode(['redirect' => './login.php']);
        $conn->close();
        exit;
    }

    // Acciones: atender, pausar
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion === 'atender') {
            $sql_siguiente = "SELECT id FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC LIMIT 1";
            $res_siguiente = $conn->query($sql_siguiente);

            if ($res_siguiente && $res_siguiente->num_rows > 0) {
                $siguiente = (int)$res_siguiente->fetch_assoc()['id'];
                $conn->query("UPDATE turnos SET estado = 'ATENDIDO' WHERE estado = 'ATENDIENDO'");
                $conn->query("UPDATE turnos SET estado = 'ATENDIENDO' WHERE id = $siguiente");
            }
        }

        if ($accion === 'pausar') {
            $conn->query("UPDATE turnos SET estado = 'PAUSADO' WHERE estado = 'ATENDIENDO'");
        }

        // Responder con éxito
        echo json_encode(['success' => true]);
        $conn->close();
        exit;
    }
}

// Si se pide la API (GET con ?api=1) devolver datos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api'])) {
    // Obtener turnos en espera
    $sql_turnos = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'EN_ESPERA' ORDER BY id ASC";
    $res_turnos = $conn->query($sql_turnos);
    $turnos = [];
    if ($res_turnos) {
        while ($row = $res_turnos->fetch_assoc()) {
            $turnos[] = $row;
        }
    }

    // Obtener turno actual
    $sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos WHERE estado = 'ATENDIENDO' ORDER BY id DESC LIMIT 1";
    $res_actual = $conn->query($sql_actual);
    $turnoActual = $res_actual ? $res_actual->fetch_assoc() : null;

    // Si no hay turno en atención, mostrar el último generado
    if (!$turnoActual) {
        $sql_actual = "SELECT codigo_turno, tipo, estado FROM turnos ORDER BY id DESC LIMIT 1";
        $res_actual = $conn->query($sql_actual);
        $turnoActual = $res_actual ? $res_actual->fetch_assoc() : null;
    }

    $resp = [
        'turnos' => $turnos,
        'turnoActual' => $turnoActual,
        'hora' => $hora,
        'fecha' => $fecha,
        'rol' => $_SESSION['rol'] ?? null
    ];

    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    $conn->close();
    exit;
}

// Si se llega aquí sin parámetros, devolver un pequeño mensaje de ayuda
echo json_encode(['error' => 'Endpoint de API. Usa ?api=1 para obtener datos o envía POST para acciones.']);
$conn->close();

