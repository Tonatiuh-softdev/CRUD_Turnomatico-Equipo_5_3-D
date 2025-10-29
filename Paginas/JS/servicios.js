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
      <td><button onclick="abrirModalEditar(${s.ID_Servicio}, '${s.Nombre.replace(/'/g,"\\'")}', '${(s.Descripcion || "").replace(/'/g,"\\'")}')">Editar</button></td>
      <td><button onclick="eliminarServicio(${s.ID_Servicio})">Eliminar</button></td>
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
