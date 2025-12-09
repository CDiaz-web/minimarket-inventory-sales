<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>

<div class="dashboard__formulario">  

    <div class="formulario__campo">              
        <input 
            class="formulario__input"
            type="text"
            name="buscar"
            placeholder=  "Buscar Tiendas..." 
            id="buscar"                    
        />
    </div> 
    <div class="dashboard__contenedor"  id="table_box_master">
        <?php if(!empty($tiendas)) { ?>
            <table class="table" id ="tabla">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Tienda</th>
                        <th scope='col' class="table__th"></th>
                    </tr>
                </thead>
                <tbody class="table__tbody" id="tabla">
                    <?php foreach($tiendas as $tienda) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $tienda->tienda;?>
                            </td>                                
                            <td class="table__td--acciones" >
                                <form id ="frSeleccionar<?php echo $tienda->idtienda; ?>"  method="POST" action="/tiendas" class="table__formulario">
                                    <input type="hidden" name="idtienda" value="<?php echo $tienda->idtienda;?>">
                                    <button class="table__mantenimiento table__mantenimiento--editar"  type="submit" data-id="<?php echo $tienda->idtienda; ?>">
                                        <i class="fa-solid fa-user-pen" ></i>
                                    </button> 
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay tiendas Asignadas</p>
        <?php } ?>
    </div>
    <div id="index_native_master" class="box"></div> 
</div>