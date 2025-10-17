<?php

function loadNavbar() {
    require_once __DIR__ . "/navbar.php";
    renderNavbar();
}


function loadLogIn(){
    require_once __DIR__ . "/ctrl_sesion.php";
    renderLogIn();
}
    





?>