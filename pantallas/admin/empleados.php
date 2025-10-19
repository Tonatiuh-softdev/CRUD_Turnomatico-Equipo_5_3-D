<?php
require '../../elementos/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión
loadLogIn();

// Obtener los empleados (rol = 'empleado')
$sql = "SELECT id, nombre, rol as puesto FROM usuarios WHERE rol='empleado'";
$result = $conn->query($sql);
$empleados = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $empleados[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Empleados</title>
<style>
body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }
header { display: flex; justify-content: space-between; align-items: center; background: #eceae7ff; padding: 10px 20px; border-bottom: 1px solid #ddd; }
header .logo { display: flex; align-items: center; font-weight: bold; }
header .logo span { margin-left: 10px; font-size: 14px; }
header .user { display: flex; align-items: center; gap: 15px; font-weight: bold; }
header .time { font-size: 12px; color: #666; text-align: right; }
.container { display: flex; height: calc(100vh - 50px); }
aside { width: 220px; background: #9cb6d6ff; color: #fff; padding: 15px 10px; display: flex; flex-direction: column; gap: 10px; }
aside a { display: flex; align-items: center; padding: 40px; border-radius: 5px; color: #000000ff; text-decoration: none; font-size: 14px; }
main { flex: 1; padding: 20px; background: #fff; overflow-y: auto; }
h2 { margin-top: 0; display: flex; align-items: center; gap: 10px; }
table { width: 100%; border-collapse: collapse; background: #fafafa; }
table th, table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
table th { background: #747e8bff; color: white; }
.btn-eliminar { background: red; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
.btn-eliminar:hover { background: darkred; }
.btn-agregar {
    display: block;
    margin: 20px auto;
    padding: 15px 30px;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 30px;
    background: #2b3d57;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}
.btn-agregar:hover { background: #3f5675; }
/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(50,50,50,0.9));
    justify-content: center;
    align-items: center;
}
.modal-contenido {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    width: 300px;
    text-align: center;
}
.modal-contenido h3 { margin-top: 0; }
.modal-contenido input { width: 90%; padding: 10px; margin-bottom: 15px; }
.modal-contenido button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background: #2b3d57;
    color: #fff;
    cursor: pointer;
}
.modal-contenido button:hover { background: #3f5675; }
.cerrar { margin-top: 10px; background: red !important; }
.cerrar:hover { background: darkred !important; }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span>Administrador</span>
        <div class="time"><?= date("h:i a"); ?><br><?= date("d \d\e F Y"); ?></div>
    </div>
</header>

<div class="container">
<?php
require '../../elementos/redirecciones.php';
loadNavbar();
?>

<main>
    <h2>Administrar Empleados</h2>

    <!-- Botón grande para abrir modal -->
    <button class="btn-agregar" onclick="abrirModalEmpleado()">➕ Agregar Empleado</button>

    <!-- Modal agregar empleado -->
    <div id="modalEmpleado" class="modal">
        <div class="modal-contenido">
            <h3>Agregar Empleado</h3>
            <form id="formEmpleadoModal">
                <input type="text" id="nombre" placeholder="Nombre" required>
                <input type="text" id="puesto" placeholder="Puesto" required>
                <button type="submit">Agregar</button>
            </form>
            <button class="cerrar" onclick="cerrarModalEmpleado()">Cerrar</button>
        </div>
    </div>

    <!-- Tabla empleados -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaEmpleados"></tbody>
    </table>
</main>
</div>

<script>
let empleados = <?php echo json_encode($empleados); ?>;
let id = empleados.length > 0 ? empleados[empleados.length-1].id + 1 : 1;

const tabla = document.getElementById("tablaEmpleados");
const formModal = document.getElementById("formEmpleadoModal");
const modalEmpleado = document.getElementById("modalEmpleado");

function abrirModalEmpleado(){ modalEmpleado.style.display = "flex"; }
function cerrarModalEmpleado(){ modalEmpleado.style.display = "none"; }
window.addEventListener("click", e => { if(e.target === modalEmpleado) cerrarModalEmpleado(); });

formModal.addEventListener("submit", function(e){
    e.preventDefault();
    const nombre = document.getElementById("nombre").value;
    const puesto = document.getElementById("puesto").value;
    empleados.push({ id: id++, nombre, puesto });
    mostrarEmpleados();
    formModal.reset();
    cerrarModalEmpleado();
});

function mostrarEmpleados(){
    tabla.innerHTML = "";
    empleados.forEach(emp => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${emp.id}</td>
            <td>${emp.nombre}</td>
            <td>${emp.puesto}</td>
            <td>
                <button class="btn-eliminar" onclick="eliminarEmpleado(${emp.id})">Eliminar</button>
            </td>
        `;
        tabla.appendChild(fila);
    });
}

// Inicializa la tabla al cargar
mostrarEmpleados();

function eliminarEmpleado(id){
    empleados = empleados.filter(emp => emp.id !== id);
    mostrarEmpleados();
}
</script>
</body>
</html>
