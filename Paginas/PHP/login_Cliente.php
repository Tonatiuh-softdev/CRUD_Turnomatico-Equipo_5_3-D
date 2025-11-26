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
                // ✅ Verificar que el usuario está verificado
                if ($user["verificado"] != 1) {
                    $mensaje = "⚠️ Tu cuenta no ha sido verificada. Revisa tu correo.";
                } else {
                    // ✅ Usuario verificado, crear sesión y generar turno
                    $_SESSION["usuario"] = $user["nombre"];
                    $_SESSION["usuario_id"] = $user["id"];
                    $_SESSION["rol"] = "cliente";
                    $_SESSION["id_tienda"] = $user["ID_Tienda"];

                    // 🔹 Usar la tienda del usuario, no la de sesión
                    $id_tienda_usuario = $user["ID_Tienda"];

                    // 🔹 Obtener el primer servicio de la tienda para asignar al turno
                    $sql_servicio = "SELECT ID_Servicio FROM servicio WHERE ID_Tienda = ? LIMIT 1";
                    $stmt_servicio = $conn->prepare($sql_servicio);
                    $stmt_servicio->bind_param("i", $id_tienda_usuario);
                    $stmt_servicio->execute();
                    $res_servicio = $stmt_servicio->get_result();
                    $id_servicio = null;
                    if ($res_servicio->num_rows > 0) {
                        $servicio_row = $res_servicio->fetch_assoc();
                        $id_servicio = $servicio_row['ID_Servicio'];
                    }
                    $stmt_servicio->close();

                    // 🔹 Generar turno automático tipo CLIENTE (B-###)
                    $tipo = "CLIENTE";
                    
                    // Obtener el número total actual para generar el código
                    $sql_count = "SELECT COUNT(*) AS total FROM turnos WHERE ID_Tienda = ?";
                    $stmt_count = $conn->prepare($sql_count);
                    $stmt_count->bind_param("i", $id_tienda_usuario);
                    $stmt_count->execute();
                    $res_count = $stmt_count->get_result();
                    $row_count = $res_count->fetch_assoc();
                    $total = $row_count["total"] + 1;
                    $stmt_count->close();

                    $codigoTurno = "B" . str_pad($total, 3, "0", STR_PAD_LEFT);

                    // Insertar turno en la base de datos con ID_Servicio
                    if ($id_servicio) {
                        $sql_turno = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado, ID_Tienda, ID_Servicio) VALUES (?, ?, ?, 'EN_ESPERA', ?, ?)";
                        $stmt_turno = $conn->prepare($sql_turno);
                        $stmt_turno->bind_param("sssii", $codigoTurno, $tipo, $user["nombre"], $id_tienda_usuario, $id_servicio);
                    } else {
                        $sql_turno = "INSERT INTO turnos (codigo_turno, tipo, nombre_cliente, estado, ID_Tienda) VALUES (?, ?, ?, 'EN_ESPERA', ?)";
                        $stmt_turno = $conn->prepare($sql_turno);
                        $stmt_turno->bind_param("sssi", $codigoTurno, $tipo, $user["nombre"], $id_tienda_usuario);
                    }
                    $stmt_turno->execute(); 
                    $stmt_turno->close();

                    // Guardar el código del turno en la sesión
                    $_SESSION["turno_codigo"] = $codigoTurno;

                    header("Location: pantallaTomarTurno.php");
                    exit;
                }
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

