<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

# Que el login sea en base a la idtienda
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email && $password) {
        $sql = "SELECT u.*, t.nombre as nombre_tienda FROM usuarios u 
        LEFT JOIN tienda t ON u.ID_Tienda = t.ID_Tienda WHERE email = ?";
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
                header("Location: pantallaTomarTurno.php");
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

require __DIR__ . '/../HTML/login_Cliente.html';
?>

