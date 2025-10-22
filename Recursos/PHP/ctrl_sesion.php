<?php
function renderLogIn(){
   
    session_start();

    // Solo permitir acceso si es empleado o admin
    if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
        header("Location: ../../Paginas/PHP/login.php");
        exit;
    }
}

// 🔹 Cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();    // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header("Location: ../../Paginas/PHP/login.php");
    exit;
}

?>