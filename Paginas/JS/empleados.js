let empleados = [];
const tabla = document.getElementById("tablaEmpleados");

/* --------------------------------------------------
   CARGAR EMPLEADOS PASADOS DESDE PHP
-------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function(){
    const dataContainer = document.getElementById("dataContainer");

    if (dataContainer && dataContainer.dataset.empleados) {
        empleados = JSON.parse(dataContainer.dataset.empleados);
    }
    
    mostrarEmpleados();
});

/* --------------------------------------------------
   MOSTRAR TABLA
-------------------------------------------------- */
function mostrarEmpleados() {
    tabla.innerHTML = "";
    empleados.forEach(emp => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${emp.nombre}</td>
            <td>${emp.correo}</td>
            <td>${emp.puesto}</td>

            <td>
                <button class='btn-editar'
                    onclick='abrirEditarModal(${emp.id},"${emp.nombre}","${emp.puesto}")'>
                    ✎
                </button>
            </td>

            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="eliminar_id" value="${emp.id}">
                    <button class="btn-eliminar" type="submit">🗑</button>
                </form>
            </td>
        `;
        tabla.appendChild(fila);
    });
}

/* --------------------------------------------------
   MODAL EDITAR
-------------------------------------------------- */
function abrirEditarModal(id, nombre, rol) {
    document.getElementById("editId").value = id;
    document.getElementById("editNombre").value = nombre;
    document.getElementById("editRol").value = rol;

    document.getElementById("modalEditar").style.display = "flex";
}

function cerrarModalEditar() {
    document.getElementById("modalEditar").style.display = "none";
}

/* --------------------------------------------------
   ENVIAR FORMULARIO DE EDICIÓN A PHP
-------------------------------------------------- */
document.getElementById("formEditarEmpleado").addEventListener("submit", function(){
    // Convertir nombres de inputs a lo que espera tu PHP
    document.getElementById("editId").setAttribute("name", "editar_id");
    document.getElementById("editNombre").setAttribute("name", "editar_nombre");
    document.getElementById("editRol").setAttribute("name", "editar_puesto");
});
