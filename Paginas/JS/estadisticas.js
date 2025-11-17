const ctx = document.getElementById('grafica').getContext('2d');
const colores = ['#a595f9', '#ff5959']; // CLIENTE - VISITANTE
let grafica;

// 🔹 Obtener datos del servidor según el periodo
async function obtenerDatos(periodo = 'año') {
    try {
        const response = await fetch(`../PHP/datos_estadisticas.php?periodo=${periodo}`);
        if (!response.ok) throw new Error("Error al obtener datos del servidor");
        const datos = await response.json();
        return datos;
    } catch (error) {
        console.error("Error en obtenerDatos():", error);
        return [];
    }
}

// 🔹 Crear o actualizar la gráfica
async function crearGrafica(periodo = 'año') {
    const datos = await obtenerDatos(periodo);

    // Si no hay datos, asignar ceros
    const cliente = datos.find(d => d.tipo === 'CLIENTE') || { total: 0 };
    const visitante = datos.find(d => d.tipo === 'VISITANTE') || { total: 0 };

    const etiquetas = ['CLIENTE', 'VISITANTE'];
    const valores = [parseInt(cliente.total), parseInt(visitante.total)];

    // Actualizar texto informativo
    document.getElementById('infoCliente').textContent = `${cliente.total} turnos`;
    document.getElementById('infoVisitante').textContent = `${visitante.total} turnos`;

    // Destruir la gráfica anterior (si existe)
    if (grafica) grafica.destroy();

    // Crear nueva gráfica
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

// 🔹 Botones para cambiar el periodo
function actualizarGrafica(periodo) {
    crearGrafica(periodo);
}

// 🔹 Cargar gráfica inicial (por año)
document.addEventListener('DOMContentLoaded', () => {
    crearGrafica();
});
