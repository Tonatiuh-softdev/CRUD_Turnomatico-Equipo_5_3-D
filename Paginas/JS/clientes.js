// Elementos del DOM
const modalCliente = document.getElementById("modalCliente");

// Abrir y cerrar modal
function abrirModalCliente(){ 
    modalCliente.style.display = "flex"; 
}

function cerrarModalCliente(){ 
    modalCliente.style.display = "none"; 
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
