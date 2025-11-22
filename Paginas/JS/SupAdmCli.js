// Cargar clientes al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarClientes();
});

// Cargar clientes desde la base de datos
function cargarClientes() {
    fetch('../PHP/SupAdmCli.php?action=obtenerClientes')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaClientes');
            tabla.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(cliente => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${cliente.id}</td>
                        <td>${cliente.nombre}</td>
                        <td>${cliente.descripcion || 'N/A'}</td>
                        <td>
                            <button class="btn-editar" onclick="editarCliente(${cliente.id}, '${cliente.nombre}', '${cliente.descripcion}')">Editar</button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay clientes registrados</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
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
