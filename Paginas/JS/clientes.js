// Elementos del DOM
const modalCliente = document.getElementById("modalCliente");

function abrirEditarModal(id, nombre, status) {
    // Rellenar campos del modal
    document.getElementById("editar_id").value = id;
    document.getElementById("editar_nombre").value = nombre;
    document.getElementById("editar_status").value = status;

    // Mostrar modal
    document.getElementById("editarClienteModal").style.display = "flex";
}

function cerrarEditarModal() {
    document.getElementById("editarClienteModal").style.display = "none";
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function(e) {
    let modal = document.getElementById("editarClienteModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
}

// Cerrar modal al hacer clic fuera del contenido
window.addEventListener("click", function(e){
    if(e.target === modalCliente) cerrarModalCliente();
});

// Filtrar tabla de clientes en tiempo real
document.addEventListener('DOMContentLoaded', function(){
    const input = document.querySelector('.InputContainer .input');
    const tabla = document.querySelector('table tbody');
    
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
