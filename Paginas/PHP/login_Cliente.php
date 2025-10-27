<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

// No usar loadLogIn() aquí: esa función valida roles de empleado/admin
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Si ya hay un cliente logueado redirigir a pantalla de espera
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente') {
  header('Location: pantalla_espera.php');
  exit;
}

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

require __DIR__ . '/../HTML/login_Cliente.html';
?>
