// Cargar empleados al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarEmpleados();
});

// Cargar empleados desde la base de datos
function cargarEmpleados() {
    fetch('../PHP/SupAdmEmp.php?action=obtenerEmpleados')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaEmpleados');
            tabla.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(empleado => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${empleado.id}</td>
                        <td>${empleado.nombre}</td>
                        <td>${empleado.descripcion || 'N/A'}</td>
                        <td>
                            <button class="btn-editar" onclick="editarEmpleado(${empleado.id}, '${empleado.nombre}', '${empleado.descripcion}')" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l8.5-8.5a2.121 2.121 0 013 3L14 10.5M16 3l3 3"></path>
                                </svg>
                            </button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarEmpleado(${empleado.id})" title="Eliminar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay empleados registrados</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Abrir modal para agregar empleado
function abrirModal() {
    document.getElementById('modalEmpleado').style.display = 'block';
}

// Cerrar modal de agregar
function cerrarModal() {
    document.getElementById('modalEmpleado').style.display = 'none';
}

// Cerrar modal de editar
function cerrarModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Abrir modal para editar empleado
function editarEmpleado(id, nombre, descripcion) {
    document.getElementById('idEditar').value = id;
    document.getElementById('nombreEditar').value = nombre;
    document.getElementById('descripcionEditar').value = descripcion;
    document.getElementById('modalEditar').style.display = 'block';
}

// Agregar nuevo empleado
document.getElementById('formModal')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreModal').value;
    const descripcion = document.getElementById('descripcionModal').value;
    
    fetch('../PHP/SupAdmEmp.php?action=agregarEmpleado', {
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
        cargarEmpleados();
    })
    .catch(error => console.error('Error:', error));
});

// Guardar cambios de empleado editado
document.getElementById('formEditar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('idEditar').value;
    const nombre = document.getElementById('nombreEditar').value;
    const descripcion = document.getElementById('descripcionEditar').value;
    
    fetch('../PHP/SupAdmEmp.php?action=editarEmpleado', {
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
        cargarEmpleados();
    })
    .catch(error => console.error('Error:', error));
});

// Eliminar empleado
function eliminarEmpleado(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este empleado?')) {
        fetch('../PHP/SupAdmEmp.php?action=eliminarEmpleado', {
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
            cargarEmpleados();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const modalEmpleado = document.getElementById('modalEmpleado');
    const modalEditar = document.getElementById('modalEditar');
    
    if (event.target == modalEmpleado) {
        modalEmpleado.style.display = 'none';
    }
    if (event.target == modalEditar) {
        modalEditar.style.display = 'none';
    }
}
