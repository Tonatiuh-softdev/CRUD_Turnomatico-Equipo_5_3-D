let empleados = [];
let id = 1;

const tabla = document.getElementById("tablaEmpleados");
const formModal = document.getElementById("formEmpleadoModal");
const modalEmpleado = document.getElementById("modalEmpleado");

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function(){
    // Obtener empleados del atributo data
    const dataContainer = document.getElementById("dataContainer");
    if (dataContainer && dataContainer.dataset.empleados) {
        empleados = JSON.parse(dataContainer.dataset.empleados);
        id = empleados.length > 0 ? empleados[empleados.length-1].id + 1 : 1;
    }
    
    mostrarEmpleados();
    
    // Filtrar tabla de empleados en tiempo real
    const input = document.querySelector('.InputContainer .input');
    
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

function abrirModalEmpleado(){ 
    modalEmpleado.style.display = "flex"; 
}

function cerrarModalEmpleado(){ 
    modalEmpleado.style.display = "none"; 
}

window.addEventListener("click", e => { 
    if(e.target === modalEmpleado) cerrarModalEmpleado(); 
});

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

function eliminarEmpleado(idEmpleado){
    empleados = empleados.filter(emp => emp.id !== idEmpleado);
    mostrarEmpleados();
}
