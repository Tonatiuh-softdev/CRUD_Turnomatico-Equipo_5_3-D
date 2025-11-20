<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario tiene una tienda asignada
if (!isset($_SESSION["id_tienda"])) {
    die("Error: No tienes una tienda asignada");
}

$id_tienda = $_SESSION["id_tienda"];


function renderLogIn(){
    // Permitir acceso si es empleado, admin, superadmin o cliente
    if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin','cliente'])) {
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