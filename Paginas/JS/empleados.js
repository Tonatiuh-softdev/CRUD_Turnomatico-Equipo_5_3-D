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
            <td>${emp.email}</td>
            <td>${emp.rol}</td>

            <td>
                <button class='btn-editar'
                    onclick='abrirEditarModal(${emp.id},"${emp.nombre}","${emp.email}","${emp.rol}","${emp.status}")'>
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
function abrirEditarModal(id, nombre, email, rol, status) {
    document.getElementById("editId").value = id;
    document.getElementById("editNombre").value = nombre;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRol").value = rol;
    document.getElementById("editStatus").value = status;

    document.getElementById("modalEditar").style.display = "flex";
}

function cerrarModalEditar() {
    document.getElementById("modalEditar").style.display = "none";
}

/* --------------------------------------------------
   ENVIAR FORMULARIO DE EDICIÓN A PHP
-------------------------------------------------- */
document.getElementById("formEditarEmpleado").addEventListener("submit", function(e){
    e.preventDefault();
    
    const formData = new FormData();
    formData.append("editar_id", document.getElementById("editId").value);
    formData.append("editar_nombre", document.getElementById("editNombre").value);
    formData.append("editar_email", document.getElementById("editEmail").value);
    formData.append("editar_rol", document.getElementById("editRol").value);
    formData.append("editar_status", document.getElementById("editStatus").value);
    
    fetch(window.location.href, {
        method: "POST",
        body: formData
    }).then(() => {
        location.reload();
    });
});
