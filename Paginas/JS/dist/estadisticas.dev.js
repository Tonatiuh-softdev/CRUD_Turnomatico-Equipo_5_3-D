"use strict";

var ctx = document.getElementById('grafica').getContext('2d');
var colores = ['#a595f9', '#ff5959']; // CLIENTE - VISITANTE

var grafica; // 🔹 Obtener datos del servidor según el periodo

function obtenerDatos() {
  var periodo,
      response,
      datos,
      _args = arguments;
  return regeneratorRuntime.async(function obtenerDatos$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          periodo = _args.length > 0 && _args[0] !== undefined ? _args[0] : 'año';
          _context.prev = 1;
          _context.next = 4;
          return regeneratorRuntime.awrap(fetch("../PHP/datos_estadisticas.php?periodo=".concat(periodo)));

        case 4:
          response = _context.sent;

          if (response.ok) {
            _context.next = 7;
            break;
          }

          throw new Error("Error al obtener datos del servidor");

        case 7:
          _context.next = 9;
          return regeneratorRuntime.awrap(response.json());

        case 9:
          datos = _context.sent;
          return _context.abrupt("return", datos);

        case 13:
          _context.prev = 13;
          _context.t0 = _context["catch"](1);
          console.error("Error en obtenerDatos():", _context.t0);
          return _context.abrupt("return", []);

        case 17:
        case "end":
          return _context.stop();
      }
    }
  }, null, null, [[1, 13]]);
} // 🔹 Crear o actualizar la gráfica


function crearGrafica() {
  var periodo,
      datos,
      cliente,
      visitante,
      etiquetas,
      valores,
      _args2 = arguments;
  return regeneratorRuntime.async(function crearGrafica$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          periodo = _args2.length > 0 && _args2[0] !== undefined ? _args2[0] : 'año';
          _context2.next = 3;
          return regeneratorRuntime.awrap(obtenerDatos(periodo));

        case 3:
          datos = _context2.sent;
          // Si no hay datos, asignar ceros
          cliente = datos.find(function (d) {
            return d.tipo === 'CLIENTE';
          }) || {
            total: 0
          };
          visitante = datos.find(function (d) {
            return d.tipo === 'VISITANTE';
          }) || {
            total: 0
          };
          etiquetas = ['CLIENTE', 'VISITANTE'];
          valores = [parseInt(cliente.total), parseInt(visitante.total)]; // Actualizar texto informativo

          document.getElementById('infoCliente').textContent = "".concat(cliente.total, " turnos");
          document.getElementById('infoVisitante').textContent = "".concat(visitante.total, " turnos"); // Destruir la gráfica anterior (si existe)

          if (grafica) grafica.destroy(); // Crear nueva gráfica

          grafica = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: etiquetas,
              datasets: [{
                data: valores,
                backgroundColor: colores,
                borderWidth: 0
              }]
            },
            options: {
              cutout: '60%',
              animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000
              },
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  callbacks: {
                    label: function label(context) {
                      return "".concat(context.label, ": ").concat(context.raw, " turnos");
                    }
                  }
                }
              }
            }
          });

        case 12:
        case "end":
          return _context2.stop();
      }
    }
  });
} // 🔹 Botones para cambiar el periodo


function actualizarGrafica(periodo) {
  crearGrafica(periodo);
} // 🔹 Cargar gráfica inicial (por año)


document.addEventListener('DOMContentLoaded', function () {
  crearGrafica();
});
//# sourceMappingURL=estadisticas.dev.js.map
