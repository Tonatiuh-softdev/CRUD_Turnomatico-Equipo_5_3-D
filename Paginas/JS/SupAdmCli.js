// Cargar clientes al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarClientes();
    inicializarBusqueda();
});

// Variable para almacenar todos los clientes
let todosLosClientes = [];

// Cargar clientes desde la base de datos
function cargarClientes() {
    fetch('../PHP/SupAdmCli.php?action=obtenerClientes')
        .then(response => response.json())
        .then(data => {
            todosLosClientes = data || [];
            mostrarClientes(todosLosClientes);
        })
        .catch(error => console.error('Error:', error));
}

// Mostrar clientes en la tabla
function mostrarClientes(clientes) {
    const tabla = document.getElementById('tablaClientes');
    tabla.innerHTML = '';
    
    if (clientes && clientes.length > 0) {
        clientes.forEach(cliente => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${cliente.id}</td>
                <td>${cliente.nombre}</td>
                <td>${cliente.descripcion || 'N/A'}</td>
                <td>
                    <button class="btn-editar" onclick="editarCliente(${cliente.id}, '${cliente.nombre}', '${cliente.descripcion}')" title="Editar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l8.5-8.5a2.121 2.121 0 013 3L14 10.5M16 3l3 3"></path>
                        </svg>
                    </button>
                </td>
                <td>
                    <button class="btn-eliminar" onclick="eliminarCliente(${cliente.id})" title="Eliminar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            tabla.appendChild(fila);
        });
    } else {
        tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay clientes registrados</td></tr>';
    }
}

// Inicializar búsqueda
function inicializarBusqueda() {
    const inputBusqueda = document.querySelector('.input');
    if (!inputBusqueda) return;
    
    inputBusqueda.addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase().trim();
        
        if (termino === '') {
            mostrarClientes(todosLosClientes);
        } else {
            const resultados = todosLosClientes.filter(cliente => 
                cliente.id.toString().includes(termino) ||
                cliente.nombre.toLowerCase().includes(termino) ||
                (cliente.descripcion && cliente.descripcion.toLowerCase().includes(termino))
            );
            mostrarClientes(resultados);
        }
    });
}

// Abrir modal para agregar cliente
function abrirModal() {
    document.getElementById('modalCliente').style.display = 'block';
}

// Cerrar modal de agregar
function cerrarModal() {
    document.getElementById('modalCliente').style.display = 'none';
}

// Cerrar modal de editar
function cerrarModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Abrir modal para editar cliente
function editarCliente(id, nombre, descripcion) {
    document.getElementById('idEditar').value = id;
    document.getElementById('nombreEditar').value = nombre;
    document.getElementById('descripcionEditar').value = descripcion;
    document.getElementById('modalEditar').style.display = 'block';
}

// Agregar nuevo cliente
document.getElementById('formModal')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreModal').value;
    const descripcion = document.getElementById('descripcionModal').value;
    
    fetch('../PHP/SupAdmCli.php?action=agregarCliente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            nombre: nombre,
            descripcion: descripcion
        })
    })
    .then(response => response.json())
    .then(data => {
        cerrarModal();
        document.getElementById('formModal').reset();
        cargarClientes();
    })
    .catch(error => console.error('Error:', error));
});

// Guardar cambios de cliente editado
document.getElementById('formEditar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('idEditar').value;
    const nombre = document.getElementById('nombreEditar').value;
    const descripcion = document.getElementById('descripcionEditar').value;
    
    fetch('../PHP/SupAdmCli.php?action=editarCliente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: id,
            nombre: nombre,
            descripcion: descripcion
        })
    })
    .then(response => response.json())
    .then(data => {
        cerrarModalEditar();
        cargarClientes();
    })
    .catch(error => console.error('Error:', error));
});

// Eliminar cliente
function eliminarCliente(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
        fetch('../PHP/SupAdmCli.php?action=eliminarCliente', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            cargarClientes();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const modalCliente = document.getElementById('modalCliente');
    const modalEditar = document.getElementById('modalEditar');
    
    if (event.target == modalCliente) {
        modalCliente.style.display = 'none';
    }
    if (event.target == modalEditar) {
        modalEditar.style.display = 'none';
    }
}
