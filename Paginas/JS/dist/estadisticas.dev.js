"use strict";

// ===========================
//  CONFIGURACIÓN INICIAL
// ===========================
var canvas = document.getElementById('grafica');
var ctx = canvas.getContext('2d');
var grafica; // Paletas de colores por periodo

var paletas = {
  año: ['#7d6df6', '#ff7070'],
  mes: ['#6dc8f6', '#ff9b9b'],
  dia: ['#ffd766', '#ff8a8a']
}; // ===================================
//  PLUGIN: TEXTO EN EL CENTRO
// ===================================

Chart.register({
  id: 'centerText',
  afterDraw: function afterDraw(chart) {
    var ctx = chart.ctx,
        _chart$chartArea = chart.chartArea,
        width = _chart$chartArea.width,
        height = _chart$chartArea.height;
    var total = chart.config.data.datasets[0].data.reduce(function (a, b) {
      return a + b;
    }, 0);
    ctx.save();
    ctx.font = "bold 22px Poppins";
    ctx.fillStyle = "#444";
    ctx.textAlign = "center";
    ctx.fillText("".concat(total, " Turnos"), chart.width / 2, chart.height / 2 + 8);
    ctx.restore();
  }
}); // ===================================
//  MINI-LOADER MIENTRAS CARGA DATOS
// ===================================

function mostrarLoader() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.font = "16px Poppins";
  ctx.fillStyle = "#999";
  ctx.textAlign = "center";
  ctx.fillText("Cargando...", canvas.width / 2, canvas.height / 2);
} // ===================================
//  FUNCIÓN PARA OBTENER DATOS
// ===================================


function obtenerDatos() {
  var periodo,
      response,
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
          return _context.abrupt("return", _context.sent);

        case 12:
          _context.prev = 12;
          _context.t0 = _context["catch"](1);
          console.error("Error en obtenerDatos():", _context.t0);
          return _context.abrupt("return", []);

        case 16:
        case "end":
          return _context.stop();
      }
    }
  }, null, null, [[1, 12]]);
} // ===================================
//  CREAR / ACTUALIZAR GRÁFICA
// ===================================


function crearGrafica() {
  var periodo,
      datos,
      cliente,
      visitante,
      etiquetas,
      valores,
      colores,
      _args2 = arguments;
  return regeneratorRuntime.async(function crearGrafica$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          periodo = _args2.length > 0 && _args2[0] !== undefined ? _args2[0] : 'año';
          // Fade-in bonito
          canvas.style.opacity = 0;
          setTimeout(function () {
            return canvas.style.opacity = 1;
          }, 300);
          mostrarLoader();
          _context2.next = 6;
          return regeneratorRuntime.awrap(obtenerDatos(periodo));

        case 6:
          datos = _context2.sent;
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
          valores = [parseInt(cliente.total), parseInt(visitante.total)]; // Actualizar textos informativos

          document.getElementById('infoCliente').textContent = "".concat(cliente.total, " turnos");
          document.getElementById('infoVisitante').textContent = "".concat(visitante.total, " turnos"); // Si no hay datos, mostrar mensaje y no crear gráfica

          if (!(valores[0] === 0 && valores[1] === 0)) {
            _context2.next = 20;
            break;
          }

          ctx.clearRect(0, 0, canvas.width, canvas.height);
          ctx.font = "18px Poppins";
          ctx.fillStyle = "#555";
          ctx.textAlign = "center";
          ctx.fillText("Sin datos disponibles", canvas.width / 2, canvas.height / 2);
          return _context2.abrupt("return");

        case 20:
          // Elegir paleta según el periodo
          colores = paletas[periodo] || paletas['año'];
          if (grafica) grafica.destroy();
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
                duration: 1200,
                easing: 'easeOutQuart'
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

        case 23:
        case "end":
          return _context2.stop();
      }
    }
  });
} // ===================================
//  CAMBIAR PERIODO (BOTONES)
// ===================================


function actualizarGrafica(periodo) {
  crearGrafica(periodo);
} // ===================================
//  CARGAR GRÁFICA INICIAL
// ===================================


document.addEventListener('DOMContentLoaded', function () {
  crearGrafica();
});
//# sourceMappingURL=estadisticas.dev.js.map
