"use strict";

var empleados = [];
var id = 1;
var tabla = document.getElementById("tablaEmpleados");
var formModal = document.getElementById("formEmpleadoModal");
var modalEmpleado = document.getElementById("modalEmpleado"); // Inicializar cuando el DOM esté listo

document.addEventListener('DOMContentLoaded', function () {
  // Obtener empleados del atributo data
  var dataContainer = document.getElementById("dataContainer");

  if (dataContainer && dataContainer.dataset.empleados) {
    empleados = JSON.parse(dataContainer.dataset.empleados);
    id = empleados.length > 0 ? empleados[empleados.length - 1].id + 1 : 1;
  }

  mostrarEmpleados(); // Filtrar tabla de empleados en tiempo real

  var input = document.querySelector('.InputContainer .input');
  if (!input || !tabla) return;
  input.addEventListener('input', function () {
    var searchTerm = this.value.toLowerCase().trim();
    var filas = tabla.querySelectorAll('tr');
    filas.forEach(function (fila) {
      var textoFila = fila.textContent.toLowerCase();

      if (searchTerm === '' || textoFila.includes(searchTerm)) {
        fila.style.display = '';
      } else {
        fila.style.display = 'none';
      }
    });
  });
});

function abrirModalEmpleado() {
  modalEmpleado.style.display = "flex";
}

function cerrarModalEmpleado() {
  modalEmpleado.style.display = "none";
}

window.addEventListener("click", function (e) {
  if (e.target === modalEmpleado) cerrarModalEmpleado();
});
formModal.addEventListener("submit", function (e) {
  e.preventDefault();
  var nombre = document.getElementById("nombre").value;
  var puesto = document.getElementById("puesto").value;
  empleados.push({
    id: id++,
    nombre: nombre,
    puesto: puesto
  });
  mostrarEmpleados();
  formModal.reset();
  cerrarModalEmpleado();
});

function mostrarEmpleados() {
  tabla.innerHTML = "";
  empleados.forEach(function (emp) {
    var fila = document.createElement("tr");
    fila.innerHTML = "\n            <td>".concat(emp.id, "</td>\n            <td>").concat(emp.nombre, "</td>\n            <td>").concat(emp.puesto, "</td>\n            <td>\n                <button class=\"btn-eliminar\" onclick=\"eliminarEmpleado(").concat(emp.id, ")\">Eliminar</button>\n            </td>\n        ");
    tabla.appendChild(fila);
  });
}

function eliminarEmpleado(idEmpleado) {
  empleados = empleados.filter(function (emp) {
    return emp.id !== idEmpleado;
  });
  mostrarEmpleados();
}
//# sourceMappingURL=empleados.dev.js.map
