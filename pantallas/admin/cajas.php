<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Cajas</title>
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
.btn-configurar { background: #2b3d57; color: white; border: none; padding: 5px 8px; cursor: pointer; border-radius: 4px; }
.btn-configurar:hover { background: #3f5675; }

/* Botón grande estilo servicios */
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

/* Modal estilo servicios */
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
    <h2>Administrar Cajas</h2>

    <!-- Botón grande para abrir modal -->
    <button class="btn-agregar" onclick="abrirModalCaja()">➕ Agregar Caja</button>

    <!-- Modal agregar caja -->
    <div id="modalCaja" class="modal">
        <div class="modal-contenido">
            <h3>Agregar Caja</h3>
            <form id="formCajaModal">
                <input type="text" id="numeroCaja" placeholder="Nombre de la caja" required>
                <input type="text" id="ubicacion" placeholder="Servicio asociado" required>
                <button type="submit">Agregar</button>
            </form>
            <button class="cerrar" onclick="cerrarModalCaja()">Cerrar</button>
        </div>
    </div>

    <!-- Tabla cajas -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Servicio</th>
                <th>Configurar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="tablaCajas"></tbody>
    </table>
</main>
</div>

<script>
// Datos y contadores
let cajas = [];
let id = 1;
const tabla = document.getElementById("tablaCajas");
const formModal = document.getElementById("formCajaModal");
const modalCaja = document.getElementById("modalCaja");

// Abrir y cerrar modal
function abrirModalCaja(){ modalCaja.style.display = "flex"; }
function cerrarModalCaja(){ modalCaja.style.display = "none"; }
window.addEventListener("click", e => { if(e.target === modalCaja) cerrarModalCaja(); });

// Agregar caja
formModal.addEventListener("submit", function(e){
    e.preventDefault();
    const numeroCaja = document.getElementById("numeroCaja").value;
    const ubicacion = document.getElementById("ubicacion").value;
    cajas.push({ id: id++, numeroCaja, ubicacion });
    mostrarCajas();
    formModal.reset();
    cerrarModalCaja();
});

// Mostrar tabla
function mostrarCajas(){
    tabla.innerHTML = "";
    cajas.forEach(caja => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${caja.id}</td>
            <td>${caja.numeroCaja}</td>
            <td>${caja.ubicacion}</td>
            <td><button class="btn-configurar" onclick="configurarCaja(${caja.id})">Configurar</button></td>
            <td><button class="btn-eliminar" onclick="eliminarCaja(${caja.id})">Eliminar</button></td>
        `;
        tabla.appendChild(fila);
    });
}

// Configurar/editar caja
function configurarCaja(id){
    const caja = cajas.find(c => c.id === id);
    if(!caja) return;
    const nuevoNumero = prompt("Editar Nombre de la Caja:", caja.numeroCaja);
    const nuevoUbicacion = prompt("Editar Servicio:", caja.ubicacion);
    if(nuevoNumero !== null && nuevoUbicacion !== null){
        caja.numeroCaja = nuevoNumero;
        caja.ubicacion = nuevoUbicacion;
        mostrarCajas();
    }
}

// Eliminar caja
function eliminarCaja(id){
    cajas = cajas.filter(c => c.id !== id);
    mostrarCajas();
}
</script>
</body>
</html>
