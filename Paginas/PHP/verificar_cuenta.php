<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token = trim($token);

    error_log("=== VERIFICAR CUENTA DEBUG ===");
    error_log("Token recibido: [" . $token . "]");
    error_log("Longitud: " . strlen($token));

    // Buscar usuario con ese token - SIN filtrar por tienda
    $sql = "SELECT id, nombre, email, verificado FROM usuarios WHERE token_verificacion = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Error en prepare: " . $conn->error);
        die("Error en la consulta");
    }
    
    error_log("Query preparada correctamente");
    $stmt->bind_param("s", $token);
    error_log("Bind param hecho");
    $stmt->execute();
    error_log("Execute hecho");
    
    $res = $stmt->get_result();
    error_log("Resultados encontrados: " . $res->num_rows);

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();
        error_log("Usuario encontrado: " . $usuario['email']);

        if ($usuario['verificado'] == 0) {
            error_log("Marcando como verificado...");
            $update = $conn->prepare(
                "UPDATE usuarios SET verificado = 1, token_verificacion = NULL WHERE id = ?"
            );
            $update->bind_param("i", $usuario['id']);
            $update->execute();
            error_log("Actualización completada");

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
        error_log("❌ Token no encontrado en la BD");
        error_log("Token buscado: [" . $token . "]");
        
        // Debug: mostrar todos los tokens
        $debug_sql = "SELECT id, email, token_verificacion FROM usuarios WHERE verificado = 0 AND token_verificacion IS NOT NULL LIMIT 10";
        $debug_stmt = $conn->prepare($debug_sql);
        $debug_stmt->execute();
        $debug_res = $debug_stmt->get_result();
        error_log("Tokens sin verificar en la BD:");
        while ($row = $debug_res->fetch_assoc()) {
            error_log("  - Email: " . $row['email'] . " | Token: [" . $row['token_verificacion'] . "]");
        }
        
        echo "<script>
                alert('❌ Token inválido o expirado.');
                window.location='login.php';
              </script>";
    }
}
?>
