
(function() {
    const valida = document.getElementById('tbl_productos_poco_stock')
    if(valida){
        pocoStock()
    }
    
    async function pocoStock(){
        const url = '/api/pocostock';
        const respuesta = await fetch(url)
        const resultado = await respuesta.json() 
            
        for (let i = 0; i < resultado.length; i++){
            filas = '<tr>'+
                        '<td>' + resultado[i]["codigo"] + '</td>'+
                        '<td>' + resultado[i]["nombre"] + '</td>'+
                        '<td>' + resultado[i]["stock"] + '</td>'+
                        '<td>' + resultado[i]["stock_minimo"] + '</td>'+
                    '</tr>'                              
            document.getElementById('tbl_productos_poco_stock').insertRow(-1).innerHTML   = filas    
        }
    }
    
})();