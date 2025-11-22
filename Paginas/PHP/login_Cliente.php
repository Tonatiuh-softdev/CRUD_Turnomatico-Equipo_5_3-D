<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email && $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user["rol"] !== "cliente") {
                $mensaje = "⚠️ Solo los clientes pueden iniciar sesión aquí.";
            } elseif (password_verify($password, $user["password"])) {
                $_SESSION["usuario"] = $user["nombre"];
                $_SESSION["rol"] = "cliente";
                header("Location: pantalla_espera.php");
                exit;
            } else {
                $mensaje = "⚠️ Contraseña incorrecta.";
            }
        } else {
            $mensaje = "⚠️ Usuario no encontrado.";
        }
        $stmt->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Cliente</title>
<link rel="stylesheet" href="../CSS/login_Cliente.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<img src="../../img/fondo_login.png" alt="Fondo" class="imagen">
<img src="../../img/img.Logo_blanco-Photoroom.png" alt="Mi Imagen" class="imagen1">

<header>
  <div class="logo">
    <img src="../../img/img.Logo_blanco-Photoroom.png" width="70"/>
    <span>ClickMatic</span>
  </div>
</header>

<div class="texto1">Welcome Back</div>
<div class="texto2">Sign in to continue</div>
<div class="texto3">LOG IN</div>

<?php if ($mensaje): ?>
    <div class="error-msg"><?php echo $mensaje; ?></div>
<?php endif; ?>

<!-- Botón de regreso -->
<a href="pantalla_espera.php" class="boton">
    <img src="../../img/flecha_regresar.png" alt="Regresar" class="icono">
</a>

<form action="" method="POST">
  <div class="input-field email-field">
    <input type="email" name="email" placeholder="Email" required>
  </div>
  <div class="input-field password-field">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <i class="fa-solid fa-eye-slash toggle-eye" id="togglePassword"></i>
</div>


  <button type="submit" class="login-button">Continue</button>

  <!-- esto es para recuperar la contrasenia :)-->
  <div class="forgot-password">
    <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
  </div>
</form>
<script>
const toggle = document.getElementById('togglePassword');
const password = document.getElementById('password');

toggle.addEventListener('click', () => {
    const isPassword = password.type === "password";
    password.type = isPassword ? "text" : "password";

    toggle.classList.toggle("fa-eye");
    toggle.classList.toggle("fa-eye-slash");
});
</script>


</body>
</html>
