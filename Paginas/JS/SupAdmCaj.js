// Cargar cajas al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarCajas();
});

// Cargar cajas desde la base de datos
function cargarCajas() {
    fetch('../PHP/SupAdmCaj.php?action=obtenerCajas')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaCajas');
            tabla.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(caja => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${caja.id}</td>
                        <td>${caja.nombre}</td>
                        <td>${caja.descripcion || 'N/A'}</td>
                        <td>
                            <button class="btn-editar" onclick="editarCaja(${caja.id}, '${caja.nombre}', '${caja.descripcion}')">Editar</button>
                        </td>
                        <td>
                            <button class="btn-eliminar" onclick="eliminarCaja(${caja.id})">Eliminar</button>
                        </td>
                    `;
                    tabla.appendChild(fila);
                });
            } else {
                tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay cajas registradas</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Abrir modal para agregar caja
function abrirModal() {
    document.getElementById('modalCaja').style.display = 'block';
}

// Cerrar modal de agregar
function cerrarModal() {
    document.getElementById('modalCaja').style.display = 'none';
}

// Cerrar modal de editar
function cerrarModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Abrir modal para editar caja
function editarCaja(id, nombre, descripcion) {
    document.getElementById('idEditar').value = id;
    document.getElementById('nombreEditar').value = nombre;
    document.getElementById('descripcionEditar').value = descripcion;
    document.getElementById('modalEditar').style.display = 'block';
}

// Agregar nueva caja
document.getElementById('formModal')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreModal').value;
    const descripcion = document.getElementById('descripcionModal').value;
    
    fetch('../PHP/SupAdmCaj.php?action=agregarCaja', {
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
        cargarCajas();
    })
    .catch(error => console.error('Error:', error));
});

// Guardar cambios de caja editada
document.getElementById('formEditar')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('idEditar').value;
    const nombre = document.getElementById('nombreEditar').value;
    const descripcion = document.getElementById('descripcionEditar').value;
    
    fetch('../PHP/SupAdmCaj.php?action=editarCaja', {
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
        cargarCajas();
    })
    .catch(error => console.error('Error:', error));
});

// Eliminar caja
function eliminarCaja(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta caja?')) {
        fetch('../PHP/SupAdmCaj.php?action=eliminarCaja', {
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
            cargarCajas();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const modalCaja = document.getElementById('modalCaja');
    const modalEditar = document.getElementById('modalEditar');
    
    if (event.target == modalCaja) {
        modalCaja.style.display = 'none';
    }
    if (event.target == modalEditar) {
        modalEditar.style.display = 'none';
    }
}
