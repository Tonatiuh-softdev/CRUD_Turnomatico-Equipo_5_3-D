<?php

function renderHeader(){



    date_default_timezone_set("America/Mexico_City");
    $hora = date("h:i a");
    setlocale(LC_TIME, "es_MX.UTF-8"); 
    $fecha = (new DateTime())->format("d \ \e F \ \e Y");
  
    
    include __DIR__ . '/../../Componentes/HTML/header.html';
        
    
    

}

renderHeader();
?>
