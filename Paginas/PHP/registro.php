<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

require_once __DIR__ . "/enviar_correo.php"; // 📩 Archivo para enviar correos (usa PHPMailer)

// 🔹 Obtener ID_Tienda de la sesión
$id_tienda = $_SESSION["id_tienda"] ?? null;

// Validar que existe una tienda en sesión
if (!$id_tienda) {
    die("⚠️ No hay tienda seleccionada. Por favor, accede desde la pantalla de espera.");
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // Verificar si el correo ya existe en la tienda actual
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND ID_Tienda = ?");
        $check->bind_param("si", $email, $id_tienda);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows > 0) {
            $mensaje = "⚠️ Este correo ya está registrado en esta tienda";
        } else {
            // Crear token único de verificación
            $token = bin2hex(random_bytes(16));

            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Guardar usuario con ID_Tienda
            $sql = "INSERT INTO usuarios (nombre, email, password, rol, ID_Tienda, verificado, token_verificacion) 
                    VALUES (?, ?, ?, 'cliente', ?, 0, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssis", $nombre, $email, $hashed_password, $id_tienda, $token);

            if ($stmt->execute()) {
                // Enviar correo con enlace de verificación
                $baseURL = "https://nexora.com.mx/Paginas/PHP/verificar_cuenta.php";
                $link_verificacion = $baseURL . "?token=" . $token;


                if (enviarCorreoVerificacion($email, $nombre, $link_verificacion)) {
                    $mensaje = "✅ Registro pendiente. Se envió un correo de confirmación al cliente.";
                } else {
                    $mensaje = "⚠️ Usuario creado, pero no se pudo enviar el correo.";
                }
            } else {
                $mensaje = "⚠️ Error al registrar usuario";
            }

            $stmt->close();
        }

        $check->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos";
    }
}
require __DIR__ . '/../HTML/registro.html';
?>



