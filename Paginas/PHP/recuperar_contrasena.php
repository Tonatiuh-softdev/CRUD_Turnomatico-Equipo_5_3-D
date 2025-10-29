<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php"; // contiene la función conexion()
require_once __DIR__ . "/enviar_correo.php";

$conn = conexion(); // Obtenemos la conexión a la base de datos

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar Contraseña</title>
<link rel="stylesheet" href="../CSS/login_Cliente.css">
</head>
<body>
<img src="../../img/fondo_login.png" alt="Fondo" class="imagen">
<img src="../../img/img.Logo_blanco-Photoroom.png" alt="Logo" class="imagen1">

<header>
  <div class="logo">
    <img src="../../img/img.Logo_blanco-Photoroom.png" width="70"/>
    <span>ClickMatic</span>
  </div>
</header>

<div class="texto1">Recuperar Contraseña</div>
<div class="texto2">Ingresa tu correo para recibir un enlace</div>

<?php if (!empty($mensaje)): ?>
  <div class="error-msg"><?php echo $mensaje; ?></div>
<?php endif; ?>

<form action="" method="POST">
  <div class="input-field email-field">
    <input type="email" name="email" placeholder="Email" required>
  </div>
  <button type="submit" class="login-button">Enviar enlace</button>
</form>

<div class="forgot-password">
  <a href="login_cliente.php">Volver al inicio de sesión</a>
</div>
</body>
</html>

