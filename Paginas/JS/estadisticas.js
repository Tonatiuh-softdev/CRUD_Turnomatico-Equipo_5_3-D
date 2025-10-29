const datos = {
    dia: [50, 30, 20],
    mes: [400, 280, 220],
    año: [4800, 3350, 2600]
};

const colores = ['#a595f9', '#ff5959', '#ffbe5b'];
const etiquetas = ['Servicio1', 'Servicio2', 'Servicio3'];

const ctx = document.getElementById('grafica').getContext('2d');
let grafica = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: etiquetas,
        datasets: [{
            data: datos.año,
            backgroundColor: colores,
            borderWidth: 0
        }]
    },
    options: {
        cutout: '60%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.raw + ' turnos';
                    }
                }
            }
        }
    }
});

function actualizarGrafica(periodo) {
    grafica.data.datasets[0].data = datos[periodo];
    grafica.update();
}