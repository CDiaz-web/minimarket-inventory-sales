(function(){

    const btnTotalProd = document.querySelector('#total-producto');    

    if(btnTotalProd){

        let productos=[];
        let fila=''; 

        obtenerProductos();

        async function obtenerProductos(){
            const url = `/api/totalproductostienda`;             
            const respuesta = await fetch(url);           
            const resultado = await respuesta.json();      
             
            formatearProductos(resultado)
            generaFilas()
        }
 
    
        function formatearProductos(arrayProductos =[]){
            productos = arrayProductos.map( producto => {
                return{
                    codigo: producto.codigo,
                    nombre:producto.nombre,
                    venta:producto.venta,
                    stock:producto.stock,
                    minimo:producto.stock_minimo,
                    maximo:producto.stock_maximo                      
                }
            })          
        }

        function generaFilas(){

            if (productos.length === 0 ){         
                fila = ` <p class="text-center">No hay Productos</p> `
            }else{
                productos.forEach(function(producto) {
                    fila += `  
                            <tr class="table__tr">
                                <td class="table__td">
                                    ${producto.codigo}  
                                </td>    
                                <td class="table__td">
                                    ${producto.nombre}  
                                </td> 
                                <td class="table__td">
                                    ${producto.venta}  
                                </td>       
                                <td class="table__td">
                                    ${producto.stock}  
                                </td>    
                                <td class="table__td">
                                    ${producto.minimo}  
                                </td> 
                                <td class="table__td">
                                    ${producto.maximo}  
                                </td> 
                            </tr> 
                            `               
                });
            }

        }



    
        btnTotalProd.addEventListener('click', function(){    
                 
            mostraFormulario();
            paginator({
                table: document.getElementById("table_box_native").getElementsByTagName("table")[0],
                box: document.getElementById("index_native"),
                active_class: "color_page"
            });
        }); 

    
        function mostraFormulario(){    
                   
            const modal = document.createElement('DIV');       
            modal.classList.add('modal');
            modal.innerHTML = `
                <form class="formulario nueva-tarea">                
                <legend>Productos en Tienda</legend>

                    <div class="formulario__campo">              
                        <input 
                            class="formulario__input"
                            type="text"
                            name="buscarModal"
                            placeholder=  "Buscar Producto..." 
                            id="buscarModal"                    
                        />
                    </div>    
                    <div class="dashboard__contenedor" id="table_box_native">                     
                        <table class="table" id ="tablaModal">
                            <thead class="table__thead">
                                <tr>    
                                    <th scope='col' class="table__th">Codigo</th>   
                                    <th scope='col' class="table__th">Producto</th> 
                                    <th scope='col' class="table__th">P.Venta</th> 
                                    <th scope='col' class="table__th">Stock</th>                      
                                    <th scope='col' class="table__th">Stock Min.</th> 
                                    <th scope='col' class="table__th">Stock Max.</th> 
                                </tr>
                            </thead>
                            <tbody class="table__tbody" id="tabla">                                
                            ${fila}                              
                            </tbody>
                        </table>  
                    </div>
                                       
                    <div id="index_native" class="box"></div>

                    <div class="opciones">                
                        <button type="button" class="cerrar-modal">Cerrar</button>
                    </div>
                </form>
                `;
            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');
            }, 0);
            
            modal.addEventListener('click', function(e){
                e.preventDefault();
                //boton cerrar    
                if(e.target.classList.contains('cerrar-modal')){
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');                   
                    setTimeout(() => {
                        modal.remove();
                    }, 500);        
                }                 
                

            }) 

            modal.addEventListener('keyup',function(e){          
                if(e.target.classList.contains('formulario__input')){
                    var table = document.getElementById("tablaModal").tBodies[0];
                    var textoBuscar = document.getElementById('buscarModal').value;
                    
                    if(table){
                        texto = textoBuscar.toLowerCase();   
                        
                        var r=0;
                        if(texto){
                            while(row = table.rows[r++])
                                {
                                if ( row.innerText.toLowerCase().indexOf(texto) !== -1 )
                                    row.style.display = null;
                                else
                                    row.style.display = 'none';
                                }                          
                        }  else{
                            paginator({
                                table: document.getElementById("table_box_native").getElementsByTagName("table")[0],
                                box: document.getElementById("index_native"),
                                active_class: "color_page"
                            });
                        }                   
                        

                    }
                }
    
            })
            
            document.querySelector('.dashboard').appendChild(modal);  

        }

    }



     


    //////
    function paginator(config) {
        // throw errors if insufficient parameters were given
        if (typeof config != "object")
            throw "Paginator was expecting a config object!";
        if (typeof config.get_rows != "function" && !(config.table instanceof Element))
            throw "Paginator was expecting a table or get_row function!";
    
        // get/set if things are disabled
        if (typeof config.disable == "undefined") {
            config.disable = false;
        }
    
        // get/make an element for storing the page numbers in
        var box;
        if (!(config.box instanceof Element)) {
            config.box = document.createElement("div");
        }
        box = config.box;
    
        // get/make function for getting table's rows
        if (typeof config.get_rows != "function") {
            config.get_rows = function () {
                var table = config.table
                var tbody = table.getElementsByTagName("tbody")[0]||table;
    
                children = tbody.children;
                var trs = [];
                for (var i=0;i<children.length;i++) {
                    if (children[i].nodeType = "tr") {
                        if (children[i].getElementsByTagName("td").length > 0) {
                            trs.push(children[i]);
                        }
                    }
                }
    
                return trs;
            }
        }
        var get_rows = config.get_rows;
        var trs = get_rows();
    
        // get/set rows per page
        if (typeof config.rows_per_page == "undefined") {
            var selects = box.getElementsByTagName("select");
            if (typeof selects != "undefined" && (selects.length > 0 && typeof selects[0].selectedIndex != "undefined")) {
                config.rows_per_page = selects[0].options[selects[0].selectedIndex].value;
            } else {
                config.rows_per_page = 10;
            }
        }
        var rows_per_page = config.rows_per_page;
    
        // get/set current page
        if (typeof config.page == "undefined") {
            config.page = 1;
        }
        var page = config.page;
    
        // get page count
        var pages = (rows_per_page > 0)? Math.ceil(trs.length / rows_per_page):1;
    
        // check that page and page count are sensible values
        if (pages < 1) {
            pages = 1;
        }
        if (page > pages) {
            page = pages;
        }
        if (page < 1) {
            page = 1;
        }
        config.page = page;
     
        // hide rows not on current page and show the rows that are
        for (var i=0;i<trs.length;i++) {
            if (typeof trs[i]["data-display"] == "undefined") {
                trs[i]["data-display"] = trs[i].style.display||"";
            }
            if (rows_per_page > 0) {
                if (i < page*rows_per_page && i >= (page-1)*rows_per_page) {
                    trs[i].style.display = trs[i]["data-display"];
                } else {
                    // Only hide if pagination is not disabled
                    if (!config.disable) {
                        trs[i].style.display = "none";
                    } else {
                        trs[i].style.display = trs[i]["data-display"];
                    }
                }
            } else {
                trs[i].style.display = trs[i]["data-display"];
            }
        }
    
        // page button maker functions
        config.active_class = config.active_class||"active";
        if (typeof config.box_mode != "function" && config.box_mode != "list" && config.box_mode != "buttons") {
            config.box_mode = "button";
        }
        if (typeof config.box_mode == "function") {
            config.box_mode(config);
        } else {
            var make_button;
            if (config.box_mode == "list") {
                make_button = function (symbol, index, config, disabled, active) {
                    var li = document.createElement("li");
                    var a  = document.createElement("a");
                    a.href = "#";
                    a.innerHTML = symbol;
                    a.addEventListener("click", function (event) {
                        event.preventDefault();
                        this.parentNode.click();
                        return false;
                    }, false);
                    li.appendChild(a);
    
                    var classes = [];
                    if (disabled) {
                        classes.push("disabled");
                    }
                    if (active) {
                        classes.push(config.active_class);
                    }
                    li.className = classes.join(" ");
                    li.addEventListener("click", function () {
                        if (this.className.split(" ").indexOf("disabled") == -1) {
                            config.page = index;
                            paginator(config);
                        }
                    }, false);
                    return li;
                }
            } else {
                make_button = function (symbol, index, config, disabled, active) {
                    var button = document.createElement("button");
                    if(symbol ==="Anterior" || symbol ==="Siguiente" ){
                        button.classList.add("extremos")            
                    } 
                    button.innerHTML = symbol;
                    button.addEventListener("click", function (event) {
                        event.preventDefault();
                        if (this.disabled != true) {
                            config.page = index;
                            paginator(config);
                        }
                        return false;
                    }, false);
                    if (disabled) {
                        button.disabled = true;
                    }
                    if (active) {
                        button.className = config.active_class;
                    }
                    return button;
                }
            }
    
            // make page button collection
            var page_box = document.createElement(config.box_mode == "list"?"ul":"div");
            if (config.box_mode == "list") {
                page_box.className = "pagination";
            }
            page_box.classList.add('paginacion_modal');
            // var left = make_button("&laquo;", (page>1?page-1:1), config, (page == 1), false);
            var left = make_button("Anterior", (page>1?page-1:1), config, (page == 1), false);
            page_box.appendChild(left);
    

            var right = make_button("Siguiente", (pages>page?page+1:page), config, (page == pages), false);
            page_box.appendChild(right);
            if (box.childNodes.length) {
                while (box.childNodes.length > 1) {
                    box.removeChild(box.childNodes[0]);
                }
                box.replaceChild(page_box, box.childNodes[0]);
            } else {
                box.appendChild(page_box);
            }
        }
    
      
    
        // hide pagination if disabled
        if (config.disable) {
            if (typeof box["data-display"] == "undefined") {
                box["data-display"] = box.style.display||"";
            }
            box.style.display = "none";
        } else {
            if (box.style.display == "none") {
                box.style.display = box["data-display"]||"";
            }
        }
    
        // run tail function
        if (typeof config.tail_call == "function") {
            config.tail_call(config);
        }
    
        return box;
    }
 
})();