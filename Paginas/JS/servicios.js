document.addEventListener("DOMContentLoaded", () => {
  cargarServicios();

  const formModal = document.getElementById("formModal");
  const formEditar = document.getElementById("formEditar");

  formModal.addEventListener("submit", async (e) => {
    e.preventDefault();
    const nombre = document.getElementById("nombreModal").value.trim();
    const descripcion = document.getElementById("descripcionModal").value.trim();

    await fetch("../PHP/servicios.php", {
      method: "POST",
      body: new URLSearchParams({ accion: "agregar", nombre, descripcion }),
    });
    cerrarModal();
    cargarServicios();
  });

  formEditar.addEventListener("submit", async (e) => {
    e.preventDefault();
    const id = document.getElementById("idEditar").value;
    const nombre = document.getElementById("nombreEditar").value.trim();
    const descripcion = document.getElementById("descripcionEditar").value.trim();

    await fetch("../PHP/servicios.php", {
      method: "POST",
      body: new URLSearchParams({ accion: "editar", id, nombre, descripcion }),
    });
    cerrarModalEditar();
    cargarServicios();
  });
});

async function cargarServicios() {
  const res = await fetch("../PHP/servicios.php?listar=1");
  const servicios = await res.json();
  const tabla = document.getElementById("tablaServicios");
  tabla.innerHTML = "";

  servicios.forEach((s) => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${s.ID_Servicio}</td>
      <td>${s.Nombre}</td>
      <td>${s.Descripcion || "—"}</td>
      <td>
        <button class="btn-editar" 
          onclick="abrirModalEditar(${s.ID_Servicio}, '${s.Nombre.replace(/'/g,"\\'")}', '${(s.Descripcion || "").replace(/'/g,"\\'")}')">
          <span class="btn-editar__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M12 20h9" />
              <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 
                       1-4 12.5-12.5z" />
            </svg>
          </span>
        </button>
      </td>
      <td>
        <button class="btn-eliminar" onclick="eliminarServicio(${s.ID_Servicio})">
          <span class="btn-eliminar__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 
                       0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 
                       0 012 2v2M10 11v6M14 11v6"></path>
            </svg>
          </span>
        </button>
      </td>
    `;
    tabla.appendChild(fila);
  });
}

async function eliminarServicio(id) {
  if (!confirm("¿Eliminar este servicio?")) return;
  await fetch("../PHP/servicios.php", {
    method: "POST",
    body: new URLSearchParams({ accion: "eliminar", id }),
  });
  cargarServicios();
}

function abrirModal() { document.getElementById("modalServicio").style.display = "flex"; }
function cerrarModal() { document.getElementById("modalServicio").style.display = "none"; }

function abrirModalEditar(id, nombre, descripcion) {
  document.getElementById("idEditar").value = id;
  document.getElementById("nombreEditar").value = nombre;
  document.getElementById("descripcionEditar").value = descripcion;
  document.getElementById("modalEditar").style.display = "flex";
}
function cerrarModalEditar() { document.getElementById("modalEditar").style.display = "none"; }
