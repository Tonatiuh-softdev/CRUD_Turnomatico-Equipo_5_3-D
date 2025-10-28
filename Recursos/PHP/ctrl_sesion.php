<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function renderLogIn(){
    // Solo permitir acceso si es empleado o admin
    if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
        header("Location: ../../Paginas/PHP/login.php");
        exit;
    }
}

function handleLogout() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
        session_unset();    // Elimina todas las variables de sesión
        session_destroy();  // Destruye la sesión
        header("Location: ../../Paginas/PHP/login.php");
        exit;
    }
}



// Ejecutar el manejador de cierre de sesión
handleLogout();
?>