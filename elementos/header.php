<?php
date_default_timezone_set("America/Mexico_City");
$hora = date("h:i a");
setlocale(LC_TIME, "es_MX.UTF-8"); 
$fecha = (new DateTime())->format("d \ \e F \ \e Y");
?>

<body>
    

<header>
        <div class="empresa">
            <img src="../img/img.Logo_blanco.png" alt="logo">
            ClickMatic
        </div>
        <div class="info">
            <?php echo $hora; ?><br>
            <?php echo ucfirst($fecha); ?>
        </div>
</header>
</body>