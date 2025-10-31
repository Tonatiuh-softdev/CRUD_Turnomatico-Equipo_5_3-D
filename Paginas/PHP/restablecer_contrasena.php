<?php
include __DIR__ . "/../../Recursos/PHP/conexion.php";

$conn = conexion(); // <-- Creamos la conexión a la base de datos

$mensaje = "";
$token = $_POST["token"] ?? $_GET["token"] ?? ""; // Conservar token entre GET y POST

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = trim($_POST["password"] ?? "");
    $passwordConfirm = trim($_POST["passwordConfirm"] ?? "");

    $errores = [];

    // Validar campos
    if ($password === "") {
        $errores[] = "⚠️ Ingresa la nueva contraseña.";
    }
    if ($passwordConfirm === "") {
        $errores[] = "⚠️ Confirma la contraseña.";
    }
    if ($password !== "" && $passwordConfirm !== "" && $password !== $passwordConfirm) {
        $errores[] = "⚠️ Las contraseñas no coinciden.";
    }

    if (empty($errores)) {
        // Verificar token válido
        $sql = "SELECT * FROM usuarios WHERE token_recuperacion=? AND token_expira > NOW()";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                // Actualizar contraseña
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE usuarios SET password=?, token_recuperacion=NULL, token_expira=NULL WHERE token_recuperacion=?");
                if ($update) {
                    $update->bind_param("ss", $hashed, $token);
                    $update->execute();

                    $mensaje = "✅ Contraseña actualizada correctamente. <a href='login_cliente.php'>Inicia sesión</a>";
                    $token = ""; // Evitar mostrar formulario
                } else {
                    $mensaje = "❌ Error al actualizar la contraseña. Intenta más tarde.";
                }
            } else {
                $mensaje = "⚠️ Enlace inválido o expirado.";
            }
        } else {
            $mensaje = "❌ Error interno. Intenta más tarde.";
        }
    } else {
        $mensaje = implode("<br>", $errores);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablecer Contraseña</title>
<link rel="stylesheet" href="/Paginas/CSS/restablecer_password">
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

<div class="texto1">Restablecer Contraseña</div>
<div class="texto2">Ingresa tu nueva contraseña</div>

<?php if (!empty($mensaje)): ?>
  <div class="error-msg"><?php echo $mensaje; ?></div>
<?php endif; ?>

<?php if (!empty($token)): ?>
<form action="" method="POST">
  <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

  <div class="input-field password-field">
    <input type="password" name="password" placeholder="Nueva contraseña" required>
  </div>
  <div class="input-field password-field">
    <input type="password" name="passwordConfirm" placeholder="Confirmar contraseña" required>
  </div>

  <button type="submit" class="login-button">Guardar</button>
</form>
<?php endif; ?>

<div class="forgot-password">
  <a href="login_cliente.php">Volver al inicio de sesión</a>
</div>
</body>
</html>
