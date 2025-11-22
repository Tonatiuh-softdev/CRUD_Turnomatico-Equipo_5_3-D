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
                            <button class="btn-editar" onclick="editarEmpleado(${empleado.id}, '${empleado.nombre}', '${empleado.descripcion}')">Editar</button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarEmpleado(${empleado.id})">Eliminar</button>
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
