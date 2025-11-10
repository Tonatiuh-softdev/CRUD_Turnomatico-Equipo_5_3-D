<?php
require '../../Recursos/PHP/redirecciones.php';
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
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql_insert = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'empleado')";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $nombre, $email, $password_hash);

            if ($stmt_insert->execute()) {
                $mensaje = "✅ Registro exitoso. Ya puedes iniciar sesión.";
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
</head>
<body>

  <header>
  <div class="logo">
    <img src="/img/img.Logo_blanco-Photoroom.png" alt="Logo">
    <span>Panel Administrativo</span>
  </div>
  <div class="user">
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
      <div class="input-field">
        <input type="password" name="password" placeholder="Contraseña" required>
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


</body>
</html>
