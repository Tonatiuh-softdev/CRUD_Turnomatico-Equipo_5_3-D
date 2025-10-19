<?php

function loadNavbar() {
    require_once __DIR__ . "/navbar.php";
    renderNavbar();
}


function loadLogIn(){
    require_once __DIR__ . "/ctrl_sesion.php";
    renderLogIn();
}

function loadConexion(){
    require_once __DIR__ . "/conexion.php";
    conexion();
    return conexion();
}

function loadHeader(){
    require_once __DIR__ . "/header.php";
    renderHeader();
}





?>