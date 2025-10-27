<?php
require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

// Solo permitir si el usuario logueado es empleado
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ./login.php");
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

require __DIR__ . '/../HTML/registro.html';
?>


