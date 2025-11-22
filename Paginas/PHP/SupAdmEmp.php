<?php
require '../../Recursos/PHP/redirecciones.php';
require '../../Recursos/PHP/conexion.php';
require __DIR__ . '/../Componentes/PHP/navbarSuperAdmin.php';

// Manejo de acciones AJAX - DEBE ESTAR ANTES DE CUALQUIER SALIDA HTML
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];

    if ($action == 'obtenerEmpleados') {
        // Por ahora retornamos array vacío ya que la tabla empleados no existe en la BD
        echo json_encode([]);
        exit;
    }
}

// Manejo de POST para agregar, editar y eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $conn = conexion();
    $action = $_GET['action'];
    $data = json_decode(file_get_contents('php://input'), true);

    if ($action == 'agregarEmpleado') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "INSERT INTO empleados (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Empleado agregado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'editarEmpleado') {
        $id = (int)$data['id'];
        $nombre = $conn->real_escape_string($data['nombre']);
        $descripcion = $conn->real_escape_string($data['descripcion']);
        
        $query = "UPDATE empleados SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Empleado actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action == 'eliminarEmpleado') {
        $id = (int)$data['id'];
        $query = "DELETE FROM empleados WHERE id=$id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'Empleado eliminado exitosamente']);
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
<title>NEXORA - Panel SuperAdmin Empleados</title>
<link rel="stylesheet" href="../CSS/SupAdmEmp.css">
</head>
<body>
<div class="container">
   <?php renderNavbarSuperAdmin(); ?>
   <main>
       <div class="empleados-section">
           <h2 data-translate="Administrar Empleados">Administrar Empleados</h2>

           <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: -10px;">
               <div style="max-width: 300px;">
                   <?php include __DIR__ . '/../Componentes/HTML/BarraBusqueda.html'; ?>
               </div>
               <button class="btn-agregar" onclick="abrirModal()">+ Agregar Empleado</button>
           </div>

           <!-- Modal agregar -->
           <div id="modalEmpleado" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Agregar empleado">Agregar Empleado</h3>
                   <form id="formModal">
                       <input type="text" id="nombreModal" placeholder="Nombre del empleado" required>
                       <textarea id="descripcionModal" placeholder="Descripción del empleado"></textarea>
                       <button type="submit" data-translate="Agregar Datos">Agregar Datos</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModal()">Cerrar</button>
               </div>
           </div>

           <!-- Modal editar -->
           <div id="modalEditar" class="modal">
               <div class="modal-contenido">
                   <h3 data-translate="Editar empleado">Editar Empleado</h3>
                   <form id="formEditar">
                       <input type="hidden" id="idEditar">
                       <input type="text" id="nombreEditar" placeholder="Nombre del empleado" required>
                       <textarea id="descripcionEditar" placeholder="Descripción del empleado"></textarea>
                       <button type="submit" data-translate="Guardar Cambios">Guardar Cambios</button>
                   </form>
                   <button class="cerrar" onclick="cerrarModalEditar()">Cerrar</button>
               </div>
           </div>

           <!-- Tabla empleados -->
           <table>
               <thead>
                   <tr>
                       <th>ID</th>
                       <th data-translate="Empleado">Empleado</th>
                       <th data-translate="Descripción">Descripción</th>
                       <th data-translate="Editar">Editar</th>
                       <th data-translate="Eliminar">Eliminar</th>
                   </tr>
               </thead>
               <tbody id="tablaEmpleados"></tbody>
           </table>
       </div>
   </main>
</div>
<script src="../JS/SupAdmEmp.js"></script>
</body>
</html>
