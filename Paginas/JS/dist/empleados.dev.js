"use strict";

var empleados = [];
var id = 1;
var tabla = document.getElementById("tablaEmpleados"); // Inicializar cuando el DOM esté listo

document.addEventListener('DOMContentLoaded', function () {
  // Obtener empleados del atributo data
  var dataContainer = document.getElementById("dataContainer");

  if (dataContainer && dataContainer.dataset.empleados) {
    empleados = JSON.parse(dataContainer.dataset.empleados);
    id = empleados.length > 0 ? empleados[empleados.length - 1].id + 1 : 1;
  }

  mostrarEmpleados(); // Filtrar tabla de empleados en tiempo real

  var input = document.querySelector('.InputContainer .input');

  if (input && tabla) {
    input.addEventListener('input', function () {
      var searchTerm = this.value.toLowerCase().trim();
      var filas = tabla.querySelectorAll('tr');
      filas.forEach(function (fila) {
        var textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(searchTerm) ? '' : 'none';
      });
    });
  }
});

function mostrarEmpleados() {
  tabla.innerHTML = "";
  empleados.forEach(function (emp) {
    var fila = document.createElement("tr");
    fila.innerHTML = "\n            <td>".concat(emp.id, "</td>\n            <td>").concat(emp.nombre, "</td>\n            <td>\n                <button class=\"btn-eliminar\" onclick=\"eliminarEmpleado(").concat(emp.id, ")\">\n                    <span class=\"btn-eliminar__icon\">\n                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"22\" height=\"22\" \n                        viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">\n                            <path d=\"M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 \n                            0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6\"></path>\n                        </svg>\n                    </span>\n                </button>\n            </td>\n        ");
    tabla.appendChild(fila);
  });
} // =============================
//     ELIMINAR EMPLEADO (BD)
// =============================


function eliminarEmpleado(idEmpleado) {
  if (!confirm("¿Seguro que deseas eliminar este empleado?")) return;
  fetch("/Paginas/PHP/eliminar_empleado.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "id=" + encodeURIComponent(idEmpleado)
  }).then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === "success") {
      // Eliminar del arreglo visual
      empleados = empleados.filter(function (emp) {
        return emp.id !== idEmpleado;
      });
      mostrarEmpleados();
      alert("Empleado eliminado correctamente.");
    } else {
      alert("Error: " + data.msg);
    }
  })["catch"](function (err) {
    alert("Error al conectar con el servidor.");
    console.error(err);
  });
} // =============================
//   GUARDAR EDICIÓN (BD)
// =============================


function guardarCambiosEmpleado() {
  var id = document.getElementById("editId").value;
  var nombre = document.getElementById("editNombre").value;
  var correo = document.getElementById("editCorreo").value;
  fetch("/Paginas/PHP/editar_empleado.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "id=" + encodeURIComponent(id) + "&nombre=" + encodeURIComponent(nombre) + "&correo=" + encodeURIComponent(correo)
  }).then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === "success") {
      var index = empleados.findIndex(function (emp) {
        return emp.id == id;
      });

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
  })["catch"](function (err) {
    alert("Error al conectar con servidor.");
    console.error(err);
  });
}
//# sourceMappingURL=empleados.dev.js.map
