<?php
include __DIR__ . "/../Recursos/PHP/conexion.php"; 
session_start();

// Solo permitir si el usuario logueado es empleado
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: login.php");
    exit;
}

// Procesar formulario de registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($nombre) && !empty($email) && !empty($password)) {

        // 🔹 Verificar si el email ya existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows > 0) {
            echo "<script>alert('⚠️ Este correo ya está registrado');</script>";
        } else {
            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar nuevo usuario
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Usuario registrado exitosamente'); window.location='registro.php';</script>";
            } else {
                echo "<script>alert('⚠️ Error al registrar usuario');</script>";
            }

            $stmt->close();
        }

        $check->close();
    } else {
        echo "<script>alert('⚠️ Completa todos los campos');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/components/registro.css">
</head>
<body>
<img src="../img/fondo_login.png" alt="Mi Imagen" class="imagen">
<img src="../img/img.Logo_blanco-Photoroom.png" alt="Mi Imagen" class="imagen1">
    <header>
    <div class="logo">
        <img src="../img/img.Logo_blanco-Photoroom.png" width="70"/>
      <span>ClickMatic</span>
    </div>
    <div class="user">
        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg></span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>
<div class="rectangulo1"></div>
<div class="rectangulo2"></div>
<div class="texto1">Welcome Back</div>
<div class="texto2">Sign in  to contunue</div>
<div class="texto3">REGISTER</div>

<button class="boton" onclick="window.history.back()">
  <img src="../img/flecha_regresar.png" alt="" class="icono">
</button>

<!-- ✅ Aquí empieza el formulario (sin mover nada visual) -->
<form action="registro.php" method="POST">

<div class="input-field email-field">
  <input type="email" class="email-input" name="email" placeholder="Email" required>
</div>
<div class="input-field email-field2">
  <input type="text" class="email-input" name="nombre" placeholder="Name" required>
</div>
<div class="input-field password-field">
  <input type="password" class="password-input" name="password" placeholder="Password" required>
</div>

<!-- El botón ya existente envía el formulario -->
<button type="submit" class="login-button">Continue</button>

</form>
<!-- ✅ Aquí termina el formulario -->

</body>
</html>

