<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

if (isset($_GET["token"])) {
    $token = $_GET["token"];

    $sql = "SELECT id FROM usuarios WHERE token_verificacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {

        $sql_update = "UPDATE usuarios SET verificado = 1, token_verificacion = NULL WHERE token_verificacion = ?";
        $stmt2 = $conn->prepare($sql_update);
        $stmt2->bind_param("s", $token);
        $stmt2->execute();

        echo "<h2 style='font-family:Arial;text-align:center;margin-top:50px;color:green'>
                ✔ Cuenta verificada correctamente. Ya puedes iniciar sesión.
              </h2>";
    } else {
        echo "<h2 style='font-family:Arial;text-align:center;margin-top:50px;color:red'>
                ❌ Enlace inválido o ya utilizado.
              </h2>";
    }
} else {
    echo "<h2 style='font-family:Arial;text-align:center;margin-top:50px;color:red'>
            ❌ No se recibió token.
          </h2>";
}
?>
