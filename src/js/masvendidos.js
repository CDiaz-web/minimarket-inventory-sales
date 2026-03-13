
(function() {
   const valida = document.getElementById('tbl_productos_mas_vendidos')
    if(valida){
        MasVedidos()
    }
    
    async function MasVedidos(){
        const url = `${window.BASE_URL}/api/masvendidos`;
        const respuesta = await fetch(url)
        const resultado = await respuesta.json() 
            
        for (let i = 0; i < resultado.length; i++){
            filas = '<tr>'+
                        '<td>' + resultado[i]["codigo"] + '</td>'+
                        '<td>' + resultado[i]["nombre"] + '</td>'+
                        '<td>' + resultado[i]["cantidad"] + '</td>'+
                        '<td>' + resultado[i]["total_venta"] + '</td>'+
                    '</tr>'
           // $("#tbl_productos_mas_vendidos tbody").append(filas);
           document.getElementById('tbl_productos_mas_vendidos').insertRow(-1).innerHTML   = filas     
        }
    }
    
})();