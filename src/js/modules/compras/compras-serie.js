
export function initCompraSerie(){

    const serie = window.APP?.config?.serie;
    const selectSerie = document.getElementById("idserie");
    
    if(!selectSerie) return;
        
    if (serie) {
        App.compras.idserie = parseInt(serie) || null;
        
    }

    selectSerie.addEventListener("change", async function(){

        const serieSeleccionada = this.value;

        App.compras.idserie = parseInt(serieSeleccionada) || null;

    });

}
