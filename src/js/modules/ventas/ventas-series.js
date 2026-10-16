
export function initVentasSerie(){

    const serie = window.APP?.config?.serie;
    // console.log(serie);
    const selectSerie = document.getElementById("idserie");
    
    if(!selectSerie) return;
        
    if (serie) {
        App.ventas.idserie = parseInt(serie) || null;
        
    }

    selectSerie.addEventListener("change", async function(){

        const serieSeleccionada = this.value;

        App.ventas.idserie = parseInt(serieSeleccionada) || null;

    });

}
