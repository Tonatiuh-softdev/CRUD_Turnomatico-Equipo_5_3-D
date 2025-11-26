<?php
require '../../Recursos/PHP/redirecciones.php';
require 'enviar_correo.php'; // ← IMPORTANTE
$conn = loadConexion();
session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($nombre && $email && $password) {
        // Verificar si el correo ya existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "⚠️ Este correo ya está registrado.";
        } else {

            // ============================
            // GENERAR TOKEN DE VERIFICACIÓN
            // ============================
            $token = bin2hex(random_bytes(32));

            $link_verificacion = "http://localhost/system.Turnomatico26/NEXORA/Paginas/PHP/verificar_empleado.php?token=$token";

            // Guardar empleado con token
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $sql_insert = "INSERT INTO usuarios (nombre, email, password, rol, token_verificacion, verificado)
                           VALUES (?, ?, ?, 'empleado', ?, 0)";

            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $nombre, $email, $password_hash, $token);

            if ($stmt_insert->execute()) {
                
                // ============================
                // ENVIAR CORREO DE VERIFICACIÓN
                // ============================
                enviarCorreoVerificacion($email, $nombre, $link_verificacion);

                $mensaje = "✅ Registro exitoso. Se envió un correo de confirmación.";
            } else {
                $mensaje = "❌ Error al registrar. Intenta nuevamente.";
            }
            $stmt_insert->close();
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
  <title>Registro de Empleados</title>
  <link rel="stylesheet" href="/Paginas/CSS/registro_empleado.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <header>
  <link rel="stylesheet" href="/Paginas/Componentes/CSS/headerprivado.css">
  <div class="logo">
    <img src="/img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
    
  </div>
</header>

<div class="container">
  <!-- Panel Izquierdo -->
  <div class="left-panel">
    <div class="left-text">
      <h1>¡Bienvenido!</h1>
      <p>Completa los datos para registrar un nuevo empleado</p>
    </div>
  </div>

  <!-- Panel Derecho -->
  <div class="right-panel">
    <h2>Registro</h2>
    <form method="POST">
      <div class="input-field">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
      </div>
      <div class="input-field">
        <input type="email" name="email" placeholder="Correo electrónico" required>
      </div>
       <div class="input-field password-field">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <i class="fa-solid fa-eye-slash toggle-eye" id="togglePassword"></i>
</div>
      <button type="submit" class="registro-button">Registrar empleado</button>
    </form>

    <div class="mensaje"><?= $mensaje ?></div>

    <div class="register-section">
      <p>¿Ya registraste a todos?</p>
      <a href="/Paginas/PHP/empleados.php" class="register-button">← Volver al panel</a>
    </div>
  </div>
</div>

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
