<?php
require '../../Recursos/PHP/redirecciones.php';
require '../../Recursos/PHP/conexion.php';
require __DIR__ . '/../Componentes/PHP/navbarSuperAdmin.php';

// Manejo de acciones AJAX - DEBE ESTAR ANTES DE CUALQUIER SALIDA HTML
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];

    if ($action == 'obtenerTiendas') {
        $query = "SELECT ID_Tienda as id, Nombre as nombre, '' as descripcion FROM tienda ORDER BY ID_Tienda DESC";
        $result = $conn->query($query);
        $tiendas = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tiendas[] = $row;
            }
        }
        echo json_encode($tiendas);
        exit;
    }
}

// Manejo de POST para agregar, editar y eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];
    $data = json_decode(file_get_contents('php://input'), true);

    if ($action == 'agregarTienda') {
        $nombre = $conn->real_escape_string($data['nombre']);
        
        $query = "INSERT INTO tienda (Nombre) VALUES ('$nombre')";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Tienda agregada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'editarTienda') {
        $id = (int)$data['id'];
        $nombre = $conn->real_escape_string($data['nombre']);
        
        $query = "UPDATE tienda SET Nombre='$nombre' WHERE ID_Tienda=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Tienda actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'eliminarTienda') {
        $id = (int)$data['id'];
        $query = "DELETE FROM tienda WHERE ID_Tienda=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Tienda eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    $conn->close();
}

// Cargar header solo para peticiones de página (no AJAX)
loadHeader();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NEXORA - Panel SuperAdmin</title>
<link rel="stylesheet" href="../CSS/PanelSuperAdmin.css">
</head>
<body>
<div class="container">
   <?php renderNavbarSuperAdmin(); ?>
   <main>
       <div class="tiendas-section">
           <h2 data-translate="Administrar Tiendas">Administrar Tiendas</h2>

           <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: -10px;">
               <div style="max-width: 300px;">
                   <?php include __DIR__ . '/../Componentes/HTML/BarraBusqueda.html'; ?>
               </div>
               <button class="btn-agregar" onclick="abrirModal()">+ Agregar Tienda</button>
           </div>

           <!-- Modal agregar -->
           <div id="modalTienda" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Agregar tienda">Agregar Tienda</h3>
                   <form id="formModal">
                       <input type="text" id="nombreModal" placeholder="Nombre de la tienda" required>
                       <textarea id="descripcionModal" placeholder="Descripción de la tienda"></textarea>
                       <button type="submit" data-translate="Agregar Datos">Agregar Datos</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModal()">Cerrar</button>
               </div>
           </div>

           <!-- Modal editar -->
           <div id="modalEditar" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Editar tienda">Editar Tienda</h3>
                   <form id="formEditar">
                       <input type="hidden" id="idEditar">
                       <input type="text" id="nombreEditar" placeholder="Nombre de la tienda" required>
                       <textarea id="descripcionEditar" placeholder="Descripción de la tienda"></textarea>
                       <button type="submit" data-translate="Guardar Cambios">Guardar Cambios</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModalEditar()">Cerrar</button>
               </div>
           </div>

           <!-- Tabla tiendas -->
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th data-translate="Tienda">Tienda</th>
                       <th data-translate="Descripción">Descripción</th>
                       <th data-translate="Editar">Editar</th>
                       <th data-translate="Eliminar">Eliminar</th>
                   </tr>
               </thead>
               <tbody id="tablaTiendas"></tbody>
           </table>
       </div>
   </main>
</div>
<script src="../JS/PanelSuperAdmin.js"></script>
</body>
</html>