<?php

if (!function_exists('loadConexion')) {
    function loadConexion(){
        require_once __DIR__ . "/conexion.php";
        return conexion();
    }
}

if (!function_exists('loadLogIn')) {
    function loadLogIn(){
        require_once __DIR__ . "/ctrl_sesion.php";
        renderLogIn();
    }
}

if (!function_exists('loadNavbar')) {
    function loadNavbar() {
        require_once __DIR__ . "/../../Paginas/Componentes/PHP/navbar.php";
        renderNavbar();
    }
}

if (!function_exists('loadHeader')) {
    function loadHeader(){
        require_once __DIR__ . "/../../Paginas/Componentes/PHP/header.php";
        renderHeader();
    }
}

if (!function_exists('loadNavbarSuperAdmin')) {
    function loadNavbarSuperAdmin() {
        require_once __DIR__ . "/../../Paginas/Componentes/PHP/navbarSuperAdmin.php";
        renderNavbarSuperAdmin();
    }
}


?>
