<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn(); // contiene la función conexion()

require_once __DIR__ . "/enviar_correo.php";


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");

    if (empty($email)) {
        $mensaje = "⚠️ Ingresa tu correo electrónico.";
    } else {
        // Buscar usuario por email
        $sql = "SELECT id, email FROM usuarios WHERE email=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Error al preparar consulta: " . $conn->error);
            $mensaje = "❌ Error interno. Intenta más tarde.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                // Generar token único y expiración
                $token = bin2hex(random_bytes(16));
                $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

                $update = $conn->prepare("UPDATE usuarios SET token_recuperacion=?, token_expira=? WHERE email=?");
                if (!$update) {
                    error_log("Error al preparar UPDATE: " . $conn->error);
                    $mensaje = "❌ Error interno. Intenta más tarde.";
                } else {
                    $update->bind_param("sss", $token, $expira, $email);
                    $update->execute();

                    // Construir enlace seguro
                    $enlace = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/restablecer_contrasena.php?token=" . urlencode($token);

                    // Enviar correo usando la función de enviar_correo.php
                    if (enviarCorreoRecuperacion($email, $enlace)) {
                        $mensaje = "✅ Revisa tu correo para restablecer tu contraseña.";
                    } else {
                        $mensaje = "❌ No se pudo enviar el correo. Contacta al administrador.";
                    }
                }
            } else {
                $mensaje = "⚠️ No existe una cuenta con ese correo.";
            }
        }
    }
}

require_once __DIR__ . "/../HTML/recuperar_contrasena.html";
?>

