let empleados = [];
let id = 1;

const tabla = document.getElementById("tablaEmpleados");

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {

    // Obtener empleados del atributo data
    const dataContainer = document.getElementById("dataContainer");

    if (dataContainer && dataContainer.dataset.empleados) {
        empleados = JSON.parse(dataContainer.dataset.empleados);
        id = empleados.length > 0 ? empleados[empleados.length - 1].id + 1 : 1;
    }

    mostrarEmpleados();

    // Filtrar tabla de empleados en tiempo real
    const input = document.querySelector('.InputContainer .input');

    if (input && tabla) {
        input.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            const filas = tabla.querySelectorAll('tr');

            filas.forEach(fila => {
                const textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});


function mostrarEmpleados() {
    tabla.innerHTML = "";

    empleados.forEach(emp => {
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${emp.id}</td>
            <td>${emp.nombre}</td>
            <td>
                <button class="btn-eliminar" onclick="eliminarEmpleado(${emp.id})">
                    <span class="btn-eliminar__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" 
                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 
                            0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"></path>
                        </svg>
                    </span>
                </button>
            </td>
        `;

        tabla.appendChild(fila);
    });
}




// =============================
//     ELIMINAR EMPLEADO (BD)
// =============================
function eliminarEmpleado(idEmpleado) {

    if (!confirm("¿Seguro que deseas eliminar este empleado?")) return;

    fetch("/Paginas/PHP/eliminar_empleado.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(idEmpleado)
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {

                // Eliminar del arreglo visual
                empleados = empleados.filter(emp => emp.id !== idEmpleado);
                mostrarEmpleados();

                alert("Empleado eliminado correctamente.");

            } else {
                alert("Error: " + data.msg);
            }
        })
        .catch(err => {
            alert("Error al conectar con el servidor.");
            console.error(err);
        });
}





// =============================
//   GUARDAR EDICIÓN (BD)
// =============================
function guardarCambiosEmpleado() {
    const id = document.getElementById("editId").value;
    const nombre = document.getElementById("editNombre").value;
    const correo = document.getElementById("editCorreo").value;

    fetch("/Paginas/PHP/editar_empleado.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body:
            "id=" + encodeURIComponent(id) +
            "&nombre=" + encodeURIComponent(nombre) +
            "&correo=" + encodeURIComponent(correo)
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                const index = empleados.findIndex(emp => emp.id == id);
                if (index !== -1) {
                    empleados[index].nombre = nombre;
                    empleados[index].correo = correo;
                }

                mostrarEmpleados();
                cerrarModalEditar();

                alert("Cambios guardados correctamente.");
            } else {
                alert("Error: " + data.msg);
            }

        })
        .catch(err => {
            alert("Error al conectar con servidor.");
            console.error(err);
        });
}
