<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

// Obtener la tienda de la sesión actual
$id_tienda_sesion = $_SESSION["id_tienda"] ?? null;

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email && $password) {
        // Obtener usuario (sin restricción de tienda en la búsqueda inicial)
        $sql = "SELECT u.*, t.nombre as nombre_tienda FROM usuarios u 
        LEFT JOIN tienda t ON u.ID_Tienda = t.ID_Tienda WHERE email = ? AND u.rol = 'cliente'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user["rol"] !== "cliente") {
                $mensaje = "⚠️ Solo los clientes pueden iniciar sesión aquí.";
            } elseif (password_verify($password, $user["password"])) {
                // ✅ Usuario autenticado, crear sesión
                $_SESSION["usuario"] = $user["nombre"];
                $_SESSION["usuario_id"] = $user["id"];
                $_SESSION["rol"] = "cliente";
                $_SESSION["id_tienda"] = $user["ID_Tienda"];

                // 🔹 Redirigir a pantallaTomarTurno para que elija servicio
                header("Location: pantallaTomarTurno.php");
                exit;
            } else {
                $mensaje = "⚠️ Contraseña incorrecta.";
            }
        } else {
            $mensaje = "⚠️ Usuario no encontrado o no es cliente.";
        }
        $stmt->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos.";
    }
}

require __DIR__ . '/../HTML/login_Cliente.html'; 
?>

