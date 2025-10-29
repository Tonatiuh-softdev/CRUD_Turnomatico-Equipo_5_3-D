<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

require_once __DIR__ . "/enviar_correo.php"; // 📩 Archivo para enviar correos (usa PHPMailer)

// Solo permitir si el usuario logueado es empleado
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ./login.php");
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // Verificar si el correo ya existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows > 0) {
            $mensaje = "⚠️ Este correo ya está registrado";
        } else {
            // Crear token único de verificación
            $token = bin2hex(random_bytes(16));

            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Guardar usuario como NO verificado
            $sql = "INSERT INTO usuarios (nombre, email, password, rol, verificado, token_verificacion) 
                    VALUES (?, ?, ?, 'cliente', 0, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $email, $hashed_password, $token);

            if ($stmt->execute()) {
                // Enviar correo con enlace de verificación
                $link_verificacion = "http://localhost/pantallas/PHP/verificar_cuenta.php?token=$token";

                if (enviarCorreoVerificacion($email, $nombre, $link_verificacion)) {
                    $mensaje = "✅ Registro pendiente. Se envió un correo de confirmación al cliente.";
                } else {
                    $mensaje = "⚠️ Usuario creado, pero no se pudo enviar el correo.";
                }
            } else {
                $mensaje = "⚠️ Error al registrar usuario";
            }

            $stmt->close();
        }

        $check->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro Cliente</title>
<link rel="stylesheet" href="/Paginas/CSS/registro.css">
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

<div class="rectangulo1"></div>
<div class="rectangulo2"></div>
<div class="texto1">Welcome Back</div>
<div class="texto2">Sign in to continue</div>
<div class="texto3">REGISTER</div>

<?php if ($mensaje): ?>
    <div class="error-msg"><?php echo $mensaje; ?></div>
<?php endif; ?>

<button class="boton" onclick="window.history.back()">
  <img src="../../img/flecha_regresar.png" alt="" class="icono">
</button>

<form action="registro.php" method="POST">
    <div class="input-field email-field2">
      <input type="text" class="email-input" name="nombre" placeholder="Name" required>
    </div>
    <div class="input-field email-field">
      <input type="email" class="email-input" name="email" placeholder="Email" required>
    </div>
    <div class="input-field password-field">
      <input type="password" class="password-input" name="password" placeholder="Password" required>
    </div>
    <button type="submit" class="login-button">Continue</button>
</form>
</body>
</html>


