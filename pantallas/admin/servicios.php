<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Servicios</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f5f5f5;
    }

    /* Barra superior */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #eceae7ff;
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
    }

    header .logo {
        display: flex;
        align-items: center;
        font-weight: bold;
    }

    header .logo span {
        margin-left: 10px;
        font-size: 14px;
    }

    header .user {
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: bold;
    }

    header .time {
        font-size: 12px;
        color: #666;
        text-align: right;
    }

    /* Layout general */
    .container {
        display: flex;
        height: calc(100vh - 50px);
    }

    /* Sidebar */
    aside {
        width: 220px;
        background: #9cb6d6ff;
        color: #fff;
        padding: 15px 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    aside a {
        display: flex;
        align-items: center;
        padding: 40px;
        border-radius: 5px;
        color: #000000ff;
        text-decoration: none;
        font-size: 14px;
    }

    /* Sección de servicios */
    main {
        flex: 1;
        padding: 20px;
        background: #fff;
        overflow-y: auto;
    }

    h2 {
        margin-top: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fafafa;
    }

    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table th {
        background: #747e8bff;
        color: white;
    }

    .btn-eliminar {
        background: red;
        color: white;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-eliminar:hover {
        background: darkred;
    }

    .btn-configurar {
        background: #2b3d57;
        color: white;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-configurar:hover {
        background: #3f5675;
    }

    /* Botón grande */
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

    .btn-agregar:hover {
        background: #3f5675;
    }

    /* Fondo degradado del modal */
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

    /* Contenido del modal */
    .modal-contenido {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        width: 300px;
        text-align: center;
    }
    .modal-contenido h3 {
        margin-top: 0;
    }
    .modal-contenido input {
        width: 90%;
        padding: 10px;
        margin-bottom: 15px;
    }
    .modal-contenido button {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        background: #2b3d57;
        color: #fff;
        cursor: pointer;
    }
    .modal-contenido button:hover {
        background: #3f5675;
    }
    .cerrar {
        margin-top: 10px;
        background: red !important;
    }
    .cerrar:hover {
        background: darkred !important;
    }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg> Administrador</span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>

<div class="container">
   <?php
require '../../elementos/redirecciones.php';
loadNavbar();
?>

<main>
    <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px;">
  <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 0 0 7.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 0 0 4.902-5.652l-1.3-1.299a1.875 1.875 0 0 0-1.325-.549H5.223Z" />
  <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 0 0 9.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 0 0 2.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3Zm3-6a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75v-3Zm8.25-.75a.75.75 0 0 0-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-5.25a.75.75 0 0 0-.75-.75h-3Z" clip-rule="evenodd" />
</svg> Administrar Servicios</h2>

<!-- Botón grande para abrir el modal -->
<button class="btn-agregar" onclick="abrirModal()">+ Agregar Servicio</button>

<!-- Modal agregar -->
<div id="modalServicio" class="modal">
  <div class="modal-contenido">
    <h3>Agregar Servicio</h3>
    <form id="formModal">
      <input type="text" id="nombreModal" placeholder="Nombre del servicio" required>
      <button type="submit">Agregar Datos</button>
    </form>
    <button class="cerrar" onclick="cerrarModal()">Cerrar</button>
  </div>
</div>

<!-- Modal editar -->
<div id="modalEditar" class="modal">
  <div class="modal-contenido">
    <h3>Editar Servicio</h3>
    <form id="formEditar">
      <input type="text" id="nombreEditar" placeholder="Nombre del servicio" required>
      <button type="submit">Guardar Cambios</button>
    </form>
    <button class="cerrar" onclick="cerrarModalEditar()">Cerrar</button>
  </div>
</div>

<!-- Tabla servicios -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Servicio</th>
            <th>Configurar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="tablaServicios"></tbody>
</table>
</main>
</div>

<script>
let servicios = [];
let id = 1;
const tabla = document.getElementById("tablaServicios");

function mostrarServicios() {
    tabla.innerHTML = "";
    servicios.forEach(serv => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${serv.id}</td>
            <td>${serv.nombre}</td>
            <td><button class="btn-configurar" onclick="configurarServicio(${serv.id})">Configurar</button></td>
            <td><button class="btn-eliminar" onclick="eliminarServicio(${serv.id})">Eliminar</button></td>
        `;
        tabla.appendChild(fila);
    });
}

function eliminarServicio(id) {
    servicios = servicios.filter(serv => serv.id !== id);
    mostrarServicios();
}

// Función para abrir modal de edición
function configurarServicio(id) {
    const servicio = servicios.find(serv => serv.id === id);
    if(servicio){
        abrirModalEditar(servicio);
    }
}

// Modal agregar
const modal = document.getElementById("modalServicio");
const formModal = document.getElementById("formModal");

function abrirModal() { modal.style.display = "flex"; }
function cerrarModal() { modal.style.display = "none"; }

formModal.addEventListener("submit", function(e){
    e.preventDefault();
    const nombre = document.getElementById("nombreModal").value;
    servicios.push({ id: id++, nombre });
    mostrarServicios();
    formModal.reset();
    cerrarModal();
});

// Modal editar
const modalEditar = document.getElementById("modalEditar");
const formEditar = document.getElementById("formEditar");
let idEditar = null;

function abrirModalEditar(servicio){
    idEditar = servicio.id;
    document.getElementById("nombreEditar").value = servicio.nombre;
    modalEditar.style.display = "flex";
}
function cerrarModalEditar(){ modalEditar.style.display = "none"; }

formEditar.addEventListener("submit", function(e){
    e.preventDefault();
    const nuevoNombre = document.getElementById("nombreEditar").value;
    servicios = servicios.map(serv=>{
        if(serv.id === idEditar){
            serv.nombre = nuevoNombre;
        }
        return serv;
    });
    mostrarServicios();
    cerrarModalEditar();
});

// Cerrar modal al hacer clic fuera del contenido
window.addEventListener("click", function(e){
    if(e.target === modal) cerrarModal();
    if(e.target === modalEditar) cerrarModalEditar();
});
</script>
</body>
</html>
