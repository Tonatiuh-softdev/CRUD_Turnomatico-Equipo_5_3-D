<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

// Iniciar sesión sin verificación de rol
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya hay sesión iniciada, redirigir al index
if (isset($_SESSION["rol"]) && in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
    header("Location: ./index.php");
    exit;
}

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
                    header("Location: ./index.php");
                    break;
                case "admin":
                    header("Location: ./index.php");
                    break;
                case "empleado":
                    header("Location: ./index.php");
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
require __DIR__ . '/../HTML/login.html';
?>

