// Cargar servicios al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    cargarServicios();
    inicializarBusqueda();
});

// Variable para almacenar todos los servicios
let todosLosServicios = [];

// Cargar servicios desde la base de datos
function cargarServicios() {
    fetch('../PHP/SupAdmServ.php?action=obtenerServicios')
        .then(response => response.json())
        .then(data => {
            todosLosServicios = data || [];
            mostrarServicios(todosLosServicios);
        })
        .catch(error => console.error('Error:', error));
}

// Mostrar servicios en la tabla
function mostrarServicios(servicios) {
    const tabla = document.getElementById('tablaServicios');
    tabla.innerHTML = '';
    
    if (servicios && servicios.length > 0) {
        servicios.forEach(servicio => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${servicio.id}</td>
                <td>${servicio.nombre}</td>
                <td>${servicio.descripcion || 'N/A'}</td>
                <td>
                    <button class="btn-editar" onclick="editarServicio(${servicio.id}, '${servicio.nombre}', '${servicio.descripcion}')" title="Editar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l8.5-8.5a2.121 2.121 0 013 3L14 10.5M16 3l3 3"></path>
                        </svg>
                    </button>
                </td>
                <td>
                    <button class="btn-eliminar" onclick="eliminarServicio(${servicio.id})" title="Eliminar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            tabla.appendChild(fila);
        });
    } else {
        tabla.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No hay servicios registrados</td></tr>';
    }
}

// Inicializar búsqueda
function inicializarBusqueda() {
    const inputBusqueda = document.querySelector('.input');
    if (!inputBusqueda) return;
    
    inputBusqueda.addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase().trim();
        
        if (termino === '') {
            mostrarServicios(todosLosServicios);
        } else {
            const resultados = todosLosServicios.filter(servicio => 
                servicio.id.toString().includes(termino) ||
                servicio.nombre.toLowerCase().includes(termino) ||
                (servicio.descripcion && servicio.descripcion.toLowerCase().includes(termino))
            );
            mostrarServicios(resultados);
        }
    });
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
