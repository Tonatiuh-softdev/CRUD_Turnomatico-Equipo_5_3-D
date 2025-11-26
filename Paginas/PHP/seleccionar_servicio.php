<?php
session_start();

require '../../Recursos/PHP/redirecciones.php';
$conn = loadConexion();

// 🔹 NO usar loadLogIn() aquí para no perder la sesión del cliente

// Validar que el cliente esté logueado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "cliente") {
    die("❌ Debes iniciar sesión como cliente. SESSION: " . json_encode($_SESSION));
}

$nombreCliente = $_SESSION["usuario"];
$id_tienda = $_SESSION["id_tienda"];
$id_usuario = $_SESSION["usuario_id"];

// Obtener servicios de la tienda
$sql_servicios = "SELECT ID_Servicio, nombre, Descripcion FROM servicio WHERE ID_Tienda = ? ORDER BY nombre ASC";
$stmt_servicios = $conn->prepare($sql_servicios);
$stmt_servicios->bind_param("i", $id_tienda);
$stmt_servicios->execute();
$res_servicios = $stmt_servicios->get_result();
$servicios = [];
while ($row = $res_servicios->fetch_assoc()) {
    $servicios[] = $row;
}
$stmt_servicios->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seleccionar Servicio</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../Recursos/CSS/theme-vars.css">
<script src="../../Recursos/JS/theme-init.js"></script>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Arial', sans-serif;
        background: #667eea;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .contenedor {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 40px;
        max-width: 500px;
        width: 90%;
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #667eea;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 40px;
    }
    
    .titulo {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    
    .subtitulo {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }
    
    .cliente-info {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .cliente-info p {
        color: #666;
        font-size: 14px;
    }
    
    .cliente-info strong {
        color: #667eea;
        display: block;
        font-size: 18px;
        margin-top: 5px;
    }
    
    .servicios-lista {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .servicio-btn {
        padding: 15px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .servicio-btn:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        background: #5568d3;
    }
    
    .servicio-btn:active {
        transform: scale(0.98);
    }
    
    .servicio-icon {
        font-size: 20px;
        margin-right: 10px;
    }
    
    .servicio-info {
        flex: 1;
    }
    
    .servicio-nombre {
        font-weight: 700;
        display: block;
    }
    
    .servicio-desc {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 3px;
    }
    
    .servicio-arrow {
        margin-left: 10px;
        font-size: 18px;
    }
</style>
</head>
<body>

<div class="contenedor">
    <div class="header">
        <div class="logo">
            <i class="fas fa-tasks"></i>
        </div>
        <h1 class="titulo">ClickMatic</h1>
        <p class="subtitulo">Selecciona un servicio</p>
    </div>
    
    <div class="cliente-info">
        <p>Bienvenido,</p>
        <strong><?php echo htmlspecialchars($nombreCliente); ?></strong>
    </div>
    
    <div class="servicios-lista">
        <?php if (empty($servicios)): ?>
            <p style="text-align: center; color: #999; padding: 20px;">
                No hay servicios disponibles en esta tienda
            </p>
        <?php else: ?>
            <?php foreach ($servicios as $servicio): ?>
                <button class="servicio-btn" onclick="seleccionarServicio(<?php echo $servicio['ID_Servicio']; ?>, '<?php echo htmlspecialchars($servicio['nombre']); ?>')">
                    <div class="servicio-info">
                        <span class="servicio-nombre"><?php echo htmlspecialchars($servicio['nombre']); ?></span>
                        <?php if (!empty($servicio['Descripcion'])): ?>
                            <span class="servicio-desc"><?php echo htmlspecialchars($servicio['Descripcion']); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="servicio-arrow">→</span>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function seleccionarServicio(idServicio, nombreServicio) {
    console.log("Seleccionado servicio:", idServicio, nombreServicio);
    
    // Llamar a un endpoint para generar el turno
    fetch("../../Recursos/PHP/generar_turno_cliente.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id_servicio=" + idServicio
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Turno generado:", data.turno);
            // Guardar en sesión y redirigir
            window.location.href = "pantallaTomarTurno.php?turno=" + encodeURIComponent(data.turno);
        } else {
            alert("Error al generar turno: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error al procesar la solicitud");
    });
}
</script>

</body>
</html>
