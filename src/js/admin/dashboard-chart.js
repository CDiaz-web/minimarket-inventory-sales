// js/admin/dashboard-chart.js

let chartInstance = null;

export function initDashboardChart() {

    const canvas = document.getElementById('myChart');
    if (!canvas) return;

    cargarGrafico(canvas);
}

export function destroyDashboardChart() {
    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }
}

async function cargarGrafico(canvas) {

    try {

        const respuesta = await fetch('/api/dashboardgrafico');
        const resultado = await respuesta.json();

        // 🔥 destruir si ya existe
        destroyDashboardChart();

        chartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: resultado.map(v => v.femision),
                datasets: [{
                    label: 'Ventas del Mes',
                    data: resultado.map(v => v.total_ventas),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    } catch (error) {
        console.error('Error cargando gráfico:', error);
    }
}