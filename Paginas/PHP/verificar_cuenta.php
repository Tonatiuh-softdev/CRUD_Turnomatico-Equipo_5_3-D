<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Buscar usuario con ese token
    $sql = "SELECT * FROM usuarios WHERE token_verificacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();

        if ($usuario['verificado'] == 0) {

            $update = $conn->prepare(
                "UPDATE usuarios SET verificado = 1, token_verificacion = NULL WHERE id = ?"
            );
            $update->bind_param("i", $usuario['id']);
            $update->execute();

            echo "<script>
                    alert('✅ Tu cuenta ha sido verificada exitosamente.');
                    window.location='login.php';   
                  </script>";
        } else {
            echo "<script>
                    alert('⚠️ Esta cuenta ya fue verificada.');
                    window.location='login.php';
                  </script>";
        }

    } else {
        echo "<script>
                alert('❌ Token inválido o expirado.');
                window.location='login.php';
              </script>";
    }
}
?>
