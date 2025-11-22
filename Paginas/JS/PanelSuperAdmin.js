// Cargar tiendas al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarTiendas();
});

// Cargar tiendas desde la base de datos
function cargarTiendas() {
    fetch('../PHP/PanelSuperAdmin.php?action=obtenerTiendas')
        .then(response => {
            console.log('Response status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Response text:', text);
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            const tabla = document.getElementById('tablaTiendas');
            tabla.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(tienda => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${tienda.id}</td>
                        <td>${tienda.nombre}</td>
                        <td>${tienda.descripcion || 'N/A'}</td>
                        <td>
                            <button class="btn-editar" onclick="editarTienda(${tienda.id}, '${tienda.nombre}', '${tienda.descripcion}')" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l8.5-8.5a2.121 2.121 0 013 3L14 10.5M16 3l3 3"></path>
                                </svg>
                            </button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarTienda(${tienda.id})" title="Eliminar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay tiendas registradas</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            const tabla = document.getElementById('tablaTiendas');
            tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: red;">Error al cargar datos</td></tr>';
        });
}

// Abrir modal para agregar tienda
function abrirModal() {
    document.getElementById('modalTienda').style.display = 'block';
}

// Cerrar modal de agregar
function cerrarModal() {
    document.getElementById('modalTienda').style.display = 'none';
}

// Cerrar modal de editar
function cerrarModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Abrir modal para editar tienda
function editarTienda(id, nombre, descripcion) {
    document.getElementById('idEditar').value = id;
    document.getElementById('nombreEditar').value = nombre;
    document.getElementById('descripcionEditar').value = descripcion;
    document.getElementById('modalEditar').style.display = 'block';
}

// Agregar nueva tienda
document.getElementById('formModal')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreModal').value;
    const descripcion = document.getElementById('descripcionModal').value;
    
    fetch('../PHP/PanelSuperAdmin.php?action=agregarTienda', {
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
        cargarTiendas();
    })
    .catch(error => console.error('Error:', error));
});

// Guardar cambios de tienda editada
document.getElementById('formEditar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('idEditar').value;
    const nombre = document.getElementById('nombreEditar').value;
    const descripcion = document.getElementById('descripcionEditar').value;
    
    fetch('../PHP/PanelSuperAdmin.php?action=editarTienda', {
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
        cargarTiendas();
    })
    .catch(error => console.error('Error:', error));
});

// Eliminar tienda
function eliminarTienda(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta tienda?')) {
        fetch('../PHP/PanelSuperAdmin.php?action=eliminarTienda', {
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
            cargarTiendas();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const modalTienda = document.getElementById('modalTienda');
    const modalEditar = document.getElementById('modalEditar');
    
    if (event.target == modalTienda) {
        modalTienda.style.display = 'none';
    }
    if (event.target == modalEditar) {
        modalEditar.style.display = 'none';
    }
}