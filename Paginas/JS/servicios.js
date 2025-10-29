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

// Filtrar tabla de servicios en tiempo real
document.addEventListener('DOMContentLoaded', function(){
    const input = document.querySelector('.InputContainer .input');
    const tabla = document.getElementById("tablaServicios");
    
    if(!input || !tabla) return;
    
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
});