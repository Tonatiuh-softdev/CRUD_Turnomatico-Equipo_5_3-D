<?php
require '../../Recursos/PHP/redirecciones.php';
require '../../Recursos/PHP/conexion.php';
require __DIR__ . '/../Componentes/PHP/navbarSuperAdmin.php';

// Manejo de acciones AJAX - DEBE ESTAR ANTES DE CUALQUIER SALIDA HTML
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];

    if ($action == 'obtenerCajas') {
        $query = "SELECT ID_Caja as id, Numero_Caja as nombre, Estado as descripcion FROM caja ORDER BY ID_Caja DESC";
        $result = $conn->query($query);
        $cajas = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cajas[] = $row;
            }
        }
        echo json_encode($cajas);
        exit;
    }
}

// Manejo de POST para agregar, editar y eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];
    $data = json_decode(file_get_contents('php://input'), true);

    if ($action == 'agregarCaja') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "INSERT INTO caja (Numero_Caja, Estado) VALUES ('$nombre', '$descripcion')";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Caja agregada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'editarCaja') {
        $id = (int)$data['id'];
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "UPDATE caja SET Numero_Caja='$nombre', Estado='$descripcion' WHERE ID_Caja=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Caja actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'eliminarCaja') {
        $id = (int)$data['id'];
        $query = "DELETE FROM caja WHERE ID_Caja=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Caja eliminada exitosamente']);
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
<title>NEXORA - Panel SuperAdmin Cajas</title>
<link rel="stylesheet" href="../CSS/SupAdmCaj.css">
</head>
<body>
<div class="container">
   <?php renderNavbarSuperAdmin(); ?>
   <main>
       <div class="cajas-section">
           <h2 data-translate="Administrar Cajas">Administrar Cajas</h2>

           <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: -10px;">
               <div style="max-width: 300px;">
                   <?php include __DIR__ . '/../Componentes/HTML/BarraBusqueda.html'; ?>
               </div>
               <button class="btn-agregar" onclick="abrirModal()">+ Agregar Caja</button>
           </div>

           <!-- Modal agregar -->
           <div id="modalCaja" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Agregar caja">Agregar Caja</h3>
                   <form id="formModal">
                       <input type="text" id="nombreModal" placeholder="Nombre de la caja" required>
                       <textarea id="descripcionModal" placeholder="Descripción de la caja"></textarea>
                       <button type="submit" data-translate="Agregar Datos">Agregar Datos</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModal()">Cerrar</button>
               </div>
           </div>

           <!-- Modal editar -->
           <div id="modalEditar" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Editar caja">Editar Caja</h3>
                   <form id="formEditar">
                       <input type="hidden" id="idEditar">
                       <input type="text" id="nombreEditar" placeholder="Nombre de la caja" required>
                       <textarea id="descripcionEditar" placeholder="Descripción de la caja"></textarea>
                       <button type="submit" data-translate="Guardar Cambios">Guardar Cambios</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModalEditar()">Cerrar</button>
               </div>
           </div>

           <!-- Tabla cajas -->
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th data-translate="Caja">Caja</th>
                       <th data-translate="Descripción">Descripción</th>
                       <th data-translate="Editar">Editar</th>
                       <th data-translate="Eliminar">Eliminar</th>
                   </tr>
               </thead>
               <tbody id="tablaCajas"></tbody>
           </table>
       </div>
   </main>
</div>
<script src="../JS/SupAdmCaj.js"></script>
</body>
</html>
