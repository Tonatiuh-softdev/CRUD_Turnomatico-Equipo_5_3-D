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
            // Registrar empleado
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

<h2 style="text-align:center;">Registro de Empleados</h2>

<form action="" method="POST" style="max-width:400px;margin:auto;">
    <input type="text" name="nombre" placeholder="Nombre completo" required><br><br>
    <input type="email" name="email" placeholder="Correo electrónico" required><br><br>
    <input type="password" name="password" placeholder="Contraseña" required><br><br>
    <button type="submit">Registrar empleado</button>
</form>

<p style="color:red; text-align:center;"><?php echo $mensaje; ?></p>
<p style="text-align:center;"><a href="/Paginas/PHP/empleados.php">← Volver</a></p>

</body>
</html>

