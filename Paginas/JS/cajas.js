// Datos y contadores
let cajas = [];
let id = 1;

// Elementos del DOM
let tabla;
let formModal;
let modalCaja;

// Inicializar elementos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function(){
    tabla = document.getElementById("tablaCajas");
    formModal = document.getElementById("formCajaModal");
    modalCaja = document.getElementById("modalCaja");
    
    // Abrir y cerrar modal
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
    
    // Filtrar tabla de cajas en tiempo real
    const input = document.querySelector('.InputContainer .input');
    
    if(input && tabla){
        input.addEventListener('input', function(){
            const searchTerm = this.value.toLowerCase().trim();
            const filas = tabla.querySelectorAll('tr');
            
            filas.forEach(fila => {
                const textoFila = fila.textContent.toLowerCase();
                if(searchTerm === '' || textoFila.includes(searchTerm)){
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
    
    // Inicializa la tabla al cargar
    mostrarCajas();
});

// Abrir y cerrar modal
function abrirModalCaja(){ 
    modalCaja.style.display = "flex"; 
}

function cerrarModalCaja(){ 
    modalCaja.style.display = "none"; 
}

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