
(function() {
    const valida = document.getElementById('myChart')
    if(valida){
        obtenerDatos()
    }
    
    async function obtenerDatos(){
        const url = '/api/ventasmes';
        const respuesta = await fetch(url)
        const resultado = await respuesta.json() 
    
       
            
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: resultado.map(ventas => ventas.femision),
            datasets: [{
            label: 'Ventas del Mes',
            data: resultado.map(ventas => ventas.total_ventas),
            borderWidth: 1
            }]
        },
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        }
        });  
    }    
    
})();