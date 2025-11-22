// Cargar servicios al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarServicios();
});

// Cargar servicios desde la base de datos
function cargarServicios() {
    fetch('../PHP/SupAdmServ.php?action=obtenerServicios')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaServicios');
            tabla.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(servicio => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${servicio.id}</td>
                        <td>${servicio.nombre}</td>
                        <td>${servicio.descripcion || 'N/A'}</td>
                        <td>
                            <button class="btn-editar" onclick="editarServicio(${servicio.id}, '${servicio.nombre}', '${servicio.descripcion}')">Editar</button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarServicio(${servicio.id})">Eliminar</button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay servicios registrados</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Abrir modal para agregar servicio
function abrirModal() {
    document.getElementById('modalServicio').style.display = 'block';
}

// Cerrar modal de agregar
function cerrarModal() {
    document.getElementById('modalServicio').style.display = 'none';
}

// Cerrar modal de editar
function cerrarModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Abrir modal para editar servicio
function editarServicio(id, nombre, descripcion) {
    document.getElementById('idEditar').value = id;
    document.getElementById('nombreEditar').value = nombre;
    document.getElementById('descripcionEditar').value = descripcion;
    document.getElementById('modalEditar').style.display = 'block';
}

// Agregar nuevo servicio
document.getElementById('formModal')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreModal').value;
    const descripcion = document.getElementById('descripcionModal').value;
    
    fetch('../PHP/SupAdmServ.php?action=agregarServicio', {
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
        cargarServicios();
    })
    .catch(error => console.error('Error:', error));
});

// Guardar cambios de servicio editado
document.getElementById('formEditar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('idEditar').value;
    const nombre = document.getElementById('nombreEditar').value;
    const descripcion = document.getElementById('descripcionEditar').value;
    
    fetch('../PHP/SupAdmServ.php?action=editarServicio', {
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
        cargarServicios();
    })
    .catch(error => console.error('Error:', error));
});

// Eliminar servicio
function eliminarServicio(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este servicio?')) {
        fetch('../PHP/SupAdmServ.php?action=eliminarServicio', {
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
            cargarServicios();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const modalServicio = document.getElementById('modalServicio');
    const modalEditar = document.getElementById('modalEditar');
    
    if (event.target == modalServicio) {
        modalServicio.style.display = 'none';
    }
    if (event.target == modalEditar) {
        modalEditar.style.display = 'none';
    }
}
