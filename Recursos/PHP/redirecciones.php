<?php
function loadNavbar() {
    require_once __DIR__ . "/../../Paginas/Componentes/PHP/navbar.php";
    renderNavbar();
}

function loadHeader(){
    require_once __DIR__ . "/../../Paginas/Componentes/PHP/header.php";
    renderHeader();
}

function loadConexion(){
    require_once __DIR__ . "/conexion.php";
    $conn = conexion();
}

function loadLogIn(){
    require_once __DIR__ . "/ctrl_sesion.php";
    renderLogIn();
}




?>
