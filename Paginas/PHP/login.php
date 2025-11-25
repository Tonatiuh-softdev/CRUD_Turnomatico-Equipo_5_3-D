    <?php
    require '../../Recursos/PHP/redirecciones.php';
    $conn = loadConexion(); // ✅ Crea la conexión

    date_default_timezone_set("America/Mexico_City");
    $hora = date("h:i a");
    setlocale(LC_TIME, "es_MX.UTF-8"); 
    $fecha = (new DateTime())->format("d \ \e F \ \e Y");
    
    // Iniciar sesión sin verificación de rol
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Si ya hay sesión iniciada, redirigir al index
    if (isset($_SESSION["rol"]) && in_array($_SESSION["rol"], ['empleado','admin','superadmin'])) {
        header("Location: ./index.php");
        exit;
    }

    // Si no es POST y no es un cierre de sesión, mostrar el formulario HTML
    if ($_SERVER["REQUEST_METHOD"] !== "POST" && !isset($_POST['cerrar_sesion'])) {
        include '../HTML/login.html';
        exit;
    }

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!$email || !$password) {
        $mensaje = "⚠️ Completa todos los campos.";
        header("Location: ../HTML/login.html?msg=" . urlencode($mensaje));
        exit;
    }

    // Buscar usuario
    $sql = "SELECT u.*, t.nombre as nombre_tienda FROM usuarios u 
            LEFT JOIN tienda t ON u.ID_Tienda = t.ID_Tienda
            WHERE u.email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["usuario"] = $user["nombre"];
            $_SESSION["rol"] = $user["rol"];
            $_SESSION["id_tienda"] = $user["ID_Tienda"]; // Guardar ID de tienda
            $_SESSION["nombre_tienda"] = $user["nombre_tienda"]; // Guardar nombre de tienda
            
            // Redirecciones según el rol
            switch ($user["rol"]) {
                //aqui redirigir al nuevo interfaz de superadmin ----------------------------------------------------------------------
                case "superadmin":
                    header("Location: ./PanelSuperAdmin.php");
                    exit;

                case "admin":
                case "empleado":
                    header("Location: ./index.php");
                    exit;
            }
        } else {
            $mensaje = "⚠️ Contraseña incorrecta";
            header("Location: ../HTML/login.html?msg=" . urlencode($mensaje));
            exit;
        }
    } else {
        $mensaje = "⚠️ Usuario no encontrado";
        header("Location: ../HTML/login.html?msg=" . urlencode($mensaje));
        exit;
    }

    $conn->close();
    ?>

