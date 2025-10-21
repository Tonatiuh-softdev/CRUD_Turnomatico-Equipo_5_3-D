<?php
session_start();
include __DIR__ . "/../../Recursos/PHP/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Buscar usuario
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Comparar contraseñas (si usas password_hash cambia a password_verify)
        if (password_verify($password, $user["password"])) {
            $_SESSION["usuario"] = $user["nombre"];
            $_SESSION["rol"] = $user["rol"];

            // Redirecciones según el rol
            switch ($user["rol"]) {
                case "superadmin":
                    header("Location: ../../pantallas/admin/index.php");
                    break;
                case "admin":
                    header("Location: ../../pantallas/admin/index.php");
                    break;
                case "empleado":
                    header("Location: ../../pantallas/admin/index.php");
                    break;
            }
            exit;
        } else {
            echo "⚠️ Contraseña incorrecta";
        }
    } else {
        echo "⚠️ Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../css/components/login.css">
</head>
<body>

<img src="../../img/fondo_login.png" alt="Mi Imagen" class="imagen">
<img src="../../img/img.Logo_blanco-Photoroom.png" alt="Mi Imagen" class="imagen1">

<header>
    <div class="logo">
        <img src="../../img/img.Logo_blanco-Photoroom.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
            </svg>
        </span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>

<div class="rectangulo1"></div>
<div class="rectangulo2"></div>

<div class="texto1">Welcome Back</div>
<div class="texto2">Sign in to continue</div>
<div class="texto3">LOG IN</div>

<!-- 🔹 FORMULARIO CORRECTO -->
<form action="login.php" method="POST">
    <div class="input-field email-field">
        <input type="email" name="email" required placeholder="Email">
    </div>

    <div class="input-field password-field">
        <input type="password" name="password" required placeholder="Password">
    </div>

    <button type="submit" class="login-button">Continue</button>
</form>

</body>
</html>
