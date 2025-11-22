// Cargar tiendas al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarTiendas();
});

// Cargar tiendas desde la base de datos
function cargarTiendas() {
    fetch('../PHP/PanelSuperAdmin.php?action=obtenerTiendas')
        .then(response => response.json())
        .then(data => {
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
                            <button class="btn-editar" onclick="editarTienda(${tienda.id}, '${tienda.nombre}', '${tienda.descripcion}')">Editar</button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarTienda(${tienda.id})">Eliminar</button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay tiendas registradas</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
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