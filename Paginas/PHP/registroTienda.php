<?php 
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

require_once __DIR__ . "/enviar_correo.php"; // 📩 Archivo para enviar correos (usa PHPMailer)


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $nombreTienda = trim($_POST["nombreTienda"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($nombre) && !empty($nombreTienda) && !empty($email) && !empty($password)) {
        // Verificar si el correo ya existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows > 0) {
            $mensaje = "⚠️ Este correo ya está registrado";
        } else {
            // Crear token único de verificación
            $token = bin2hex(random_bytes(16));

            // Encriptar contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Primero insertar la tienda
            $sql_tienda = "INSERT INTO tienda (nombre) VALUES (?)";
            $stmt_tienda = $conn->prepare($sql_tienda);
            $stmt_tienda->bind_param("s", $nombreTienda);
            
            if ($stmt_tienda->execute()) {
                $tienda_id = $conn->insert_id; // Obtener el ID de la tienda insertada
                
                // Guardar usuario como NO verificado con la referencia a la tienda
                $sql = "INSERT INTO usuarios (nombre, email, password, rol, ID_Tienda, verificado, token_verificacion) 
                        VALUES (?, ?, ?, 'superadmin', ?, 0, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisi", $nombre, $email, $hashed_password, $tienda_id, $token);

                if ($stmt->execute()) {
                    // Enviar correo con enlace de verificación
                    $link_verificacion = "http://localhost/Paginas/PHP/verificar_cuenta.php?token=$token";

                    if (enviarCorreoVerificacion($email, $nombre, $link_verificacion)) {
                        $mensaje = "✅ Registro pendiente. Se envió un correo de confirmación al cliente.";
                    } else {
                        $mensaje = "⚠️ Usuario creado, pero no se pudo enviar el correo.";
                    }
                } else {
                    $mensaje = "⚠️ Error al registrar usuario: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $mensaje = "⚠️ Error al crear la tienda: " . $stmt_tienda->error;
            }

            $stmt_tienda->close();
        }

        $check->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos";
    }
}



require __DIR__ . '/../HTML/registroTienda.html';
?>