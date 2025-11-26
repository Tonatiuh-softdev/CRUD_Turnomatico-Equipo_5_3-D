// ===========================
//  CONFIGURACIÓN INICIAL
// ===========================
const canvas = document.getElementById('grafica');
const ctx = canvas.getContext('2d');

let grafica;

// Paletas de colores por periodo
const paletas = {
    año: ['#7d6df6', '#ff7070'],
    mes: ['#6dc8f6', '#ff9b9b'],
    dia: ['#ffd766', '#ff8a8a']
};

// ===================================
//  PLUGIN: TEXTO EN EL CENTRO
// ===================================
Chart.register({
    id: 'centerText',
    afterDraw(chart) {
        const { ctx, chartArea: { width, height } } = chart;
        const total = chart.config.data.datasets[0].data.reduce((a, b) => a + b, 0);

        ctx.save();
        ctx.font = "bold 22px Poppins";
        ctx.fillStyle = "#444";
        ctx.textAlign = "center";
        ctx.fillText(`${total} Turnos`, chart.width / 2, chart.height / 2 + 8);
        ctx.restore();
    }
});

// ===================================
//  MINI-LOADER MIENTRAS CARGA DATOS
// ===================================
function mostrarLoader() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = "16px Poppins";
    ctx.fillStyle = "#999";
    ctx.textAlign = "center";
    ctx.fillText("Cargando...", canvas.width / 2, canvas.height / 2);
}

// ===================================
//  FUNCIÓN PARA OBTENER DATOS
// ===================================
async function obtenerDatos(periodo = 'año') {
    try {
        const response = await fetch(`../PHP/datos_estadisticas.php?periodo=${periodo}`);
        if (!response.ok) throw new Error("Error al obtener datos del servidor");
        return await response.json();
    } catch (error) {
        console.error("Error en obtenerDatos():", error);
        return [];
    }
}

// ===================================
//  CREAR / ACTUALIZAR GRÁFICA
// ===================================
async function crearGrafica(periodo = 'año') {
    // Fade-in bonito
    canvas.style.opacity = 0;
    setTimeout(() => canvas.style.opacity = 1, 300);

    mostrarLoader();

    const datos = await obtenerDatos(periodo);

    const cliente = datos.find(d => d.tipo === 'CLIENTE') || { total: 0 };
    const visitante = datos.find(d => d.tipo === 'VISITANTE') || { total: 0 };

    const etiquetas = ['CLIENTE', 'VISITANTE'];
    const valores = [parseInt(cliente.total), parseInt(visitante.total)];

    // Actualizar textos informativos
    document.getElementById('infoCliente').textContent = `${cliente.total} turnos`;
    document.getElementById('infoVisitante').textContent = `${visitante.total} turnos`;

    // Si no hay datos, mostrar mensaje y no crear gráfica
    if (valores[0] === 0 && valores[1] === 0) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = "18px Poppins";
        ctx.fillStyle = "#555";
        ctx.textAlign = "center";
        ctx.fillText("Sin datos disponibles", canvas.width / 2, canvas.height / 2);
        return;
    }

    // Elegir paleta según el periodo
    const colores = paletas[periodo] || paletas['año'];

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
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.label}: ${context.raw} turnos`
                    }
                }
            }
        }
    });
}

// ===================================
//  CAMBIAR PERIODO (BOTONES)
// ===================================
function actualizarGrafica(periodo) {
    crearGrafica(periodo);
}

// ===================================
//  CARGAR GRÁFICA INICIAL
// ===================================
document.addEventListener('DOMContentLoaded', () => {
    crearGrafica();
});
