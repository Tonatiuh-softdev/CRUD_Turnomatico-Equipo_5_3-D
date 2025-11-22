<?php
require '../../Recursos/PHP/redirecciones.php';
require '../../Recursos/PHP/conexion.php';
require __DIR__ . '/../Componentes/PHP/navbarSuperAdmin.php';

// Manejo de acciones AJAX - DEBE ESTAR ANTES DE CUALQUIER SALIDA HTML
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];

    if ($action == 'obtenerServicios') {
        $query = "SELECT ID_Servicio as id, Nombre as nombre, Descripcion as descripcion FROM servicio ORDER BY ID_Servicio DESC";
        $result = $conn->query($query);
        $servicios = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $servicios[] = $row;
            }
        }
        echo json_encode($servicios);
        exit;
    }
}

// Manejo de POST para agregar, editar y eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];
    $data = json_decode(file_get_contents('php://input'), true);

    if ($action == 'agregarServicio') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "INSERT INTO servicio (Nombre, Descripcion) VALUES ('$nombre', '$descripcion')";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Servicio agregado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'editarServicio') {
        $id = (int)$data['id'];
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "UPDATE servicio SET Nombre='$nombre', Descripcion='$descripcion' WHERE ID_Servicio=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Servicio actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'eliminarServicio') {
        $id = (int)$data['id'];
        $query = "DELETE FROM servicio WHERE ID_Servicio=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Servicio eliminado exitosamente']);
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
<title>NEXORA - Panel SuperAdmin Servicios</title>
<link rel="stylesheet" href="../CSS/SupAdmServ.css">
</head>
<body>
<div class="container">
   <?php renderNavbarSuperAdmin(); ?>
   <main>
       <div class="servicios-section">
           <h2 data-translate="Administrar Servicios">Administrar Servicios</h2>

           <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: -10px;">
               <div style="max-width: 300px;">
                   <?php include __DIR__ . '/../Componentes/HTML/BarraBusqueda.html'; ?>
               </div>
               <button class="btn-agregar" onclick="abrirModal()">+ Agregar Servicio</button>
           </div>

           <!-- Modal agregar -->
           <div id="modalServicio" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Agregar servicio">Agregar Servicio</h3>
                   <form id="formModal">
                       <input type="text" id="nombreModal" placeholder="Nombre del servicio" required>
                       <textarea id="descripcionModal" placeholder="Descripción del servicio"></textarea>
                       <button type="submit" data-translate="Agregar Datos">Agregar Datos</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModal()">Cerrar</button>
               </div>
           </div>

           <!-- Modal editar -->
           <div id="modalEditar" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Editar servicio">Editar Servicio</h3>
                   <form id="formEditar">
                       <input type="hidden" id="idEditar">
                       <input type="text" id="nombreEditar" placeholder="Nombre del servicio" required>
                       <textarea id="descripcionEditar" placeholder="Descripción del servicio"></textarea>
                       <button type="submit" data-translate="Guardar Cambios">Guardar Cambios</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModalEditar()">Cerrar</button>
               </div>
           </div>

           <!-- Tabla servicios -->
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th data-translate="Servicio">Servicio</th>
                       <th data-translate="Descripción">Descripción</th>
                       <th data-translate="Editar">Editar</th>
                       <th data-translate="Eliminar">Eliminar</th>
                   </tr>
               </thead>
               <tbody id="tablaServicios"></tbody>
           </table>
       </div>
   </main>
</div>
<script src="../JS/SupAdmServ.js"></script>
</body>
</html>