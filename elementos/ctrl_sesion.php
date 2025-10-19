<?php
function renderLogIn(){
   
    session_start();

    // Solo permitir acceso si es empleado o admin
    if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
        header("Location: ../login.php");
        exit;
    }
}
?>