"use strict";

document.addEventListener("DOMContentLoaded", function () {
  cargarServicios();
  var formModal = document.getElementById("formModal");
  var formEditar = document.getElementById("formEditar");
  formModal.addEventListener("submit", function _callee(e) {
    var nombre, descripcion;
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            e.preventDefault();
            nombre = document.getElementById("nombreModal").value.trim();
            descripcion = document.getElementById("descripcionModal").value.trim();
            _context.next = 5;
            return regeneratorRuntime.awrap(fetch("../PHP/servicios.php", {
              method: "POST",
              body: new URLSearchParams({
                accion: "agregar",
                nombre: nombre,
                descripcion: descripcion
              })
            }));

          case 5:
            cerrarModal();
            cargarServicios();

          case 7:
          case "end":
            return _context.stop();
        }
      }
    });
  });
  formEditar.addEventListener("submit", function _callee2(e) {
    var id, nombre, descripcion;
    return regeneratorRuntime.async(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            e.preventDefault();
            id = document.getElementById("idEditar").value;
            nombre = document.getElementById("nombreEditar").value.trim();
            descripcion = document.getElementById("descripcionEditar").value.trim();
            _context2.next = 6;
            return regeneratorRuntime.awrap(fetch("../PHP/servicios.php", {
              method: "POST",
              body: new URLSearchParams({
                accion: "editar",
                id: id,
                nombre: nombre,
                descripcion: descripcion
              })
            }));

          case 6:
            cerrarModalEditar();
            cargarServicios();

          case 8:
          case "end":
            return _context2.stop();
        }
      }
    });
  });
});

function cargarServicios() {
  var res, servicios, tabla;
  return regeneratorRuntime.async(function cargarServicios$(_context3) {
    while (1) {
      switch (_context3.prev = _context3.next) {
        case 0:
          _context3.next = 2;
          return regeneratorRuntime.awrap(fetch("../PHP/servicios.php?listar=1"));

        case 2:
          res = _context3.sent;
          _context3.next = 5;
          return regeneratorRuntime.awrap(res.json());

        case 5:
          servicios = _context3.sent;
          tabla = document.getElementById("tablaServicios");
          tabla.innerHTML = "";
          servicios.forEach(function (s) {
            var fila = document.createElement("tr");
            fila.innerHTML = "\n      <td>".concat(s.ID_Servicio, "</td>\n      <td>").concat(s.Nombre, "</td>\n      <td>").concat(s.Descripcion || "—", "</td>\n      <td>\n        <button class=\"btn-editar\" \n          onclick=\"abrirModalEditar(").concat(s.ID_Servicio, ", '").concat(s.Nombre.replace(/'/g, "\\'"), "', '").concat((s.Descripcion || "").replace(/'/g, "\\'"), "')\">\n          <span class=\"btn-editar__icon\">\n            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\"\n                 viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">\n              <path d=\"M12 20h9\" />\n              <path d=\"M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 \n                       1-4 12.5-12.5z\" />\n            </svg>\n          </span>\n        </button>\n      </td>\n      <td>\n        <button class=\"btn-eliminar\" onclick=\"eliminarServicio(").concat(s.ID_Servicio, ")\">\n          <span class=\"btn-eliminar__icon\">\n            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\"\n                 viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\">\n              <path d=\"M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 \n                       0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 \n                       0 012 2v2M10 11v6M14 11v6\"></path>\n            </svg>\n          </span>\n        </button>\n      </td>\n    ");
            tabla.appendChild(fila);
          });

        case 9:
        case "end":
          return _context3.stop();
      }
    }
  });
}

function eliminarServicio(id) {
  return regeneratorRuntime.async(function eliminarServicio$(_context4) {
    while (1) {
      switch (_context4.prev = _context4.next) {
        case 0:
          if (confirm("¿Eliminar este servicio?")) {
            _context4.next = 2;
            break;
          }

          return _context4.abrupt("return");

        case 2:
          _context4.next = 4;
          return regeneratorRuntime.awrap(fetch("../PHP/servicios.php", {
            method: "POST",
            body: new URLSearchParams({
              accion: "eliminar",
              id: id
            })
          }));

        case 4:
          cargarServicios();

        case 5:
        case "end":
          return _context4.stop();
      }
    }
  });
}

function abrirModal() {
  document.getElementById("modalServicio").style.display = "flex";
}

function cerrarModal() {
  document.getElementById("modalServicio").style.display = "none";
}

function abrirModalEditar(id, nombre, descripcion) {
  document.getElementById("idEditar").value = id;
  document.getElementById("nombreEditar").value = nombre;
  document.getElementById("descripcionEditar").value = descripcion;
  document.getElementById("modalEditar").style.display = "flex";
}

function cerrarModalEditar() {
  document.getElementById("modalEditar").style.display = "none";
}
//# sourceMappingURL=servicios.dev.js.map
