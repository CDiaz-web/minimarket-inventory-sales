
<main class="auth auth--page">

    <div class="auth__contenido">

        <div class="user-context">

            <p class="user-context__welcome">
                Hola, <span class="user-context__name">
                    <?= $usuario ?>
                </span>
            </p>

            <p class="user-context__meta">
                <?= $empresa ?> · <?= $perfil->nombre ?>
            </p>

        </div>

        <h2 class="auth__heading">
       
            <?php echo $titulo; ?>
        </h2>

        <div class="table-wrapper">  

            <div class="table-header">   
                <div class="table-search">
                    <input 
                        class="formulario__input"
                        type="text"
                        name="buscar"
                        placeholder=  "Buscar Tiendas..." 
                        data-table-search="tablaTiendas"                 
                    />
                </div>  
            </div> 

            <div class="table-body">
                <?php if(!empty($tiendas)) { ?>
                    <table id="tablaTiendas"  class="table" data-table data-page-size="5">
                        <thead class="table thead">
                            <tr>               
                                <th scope='col' class="table--center">Tiendas</th>
                                <th scope='col' class="table__col-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody" id="tabla">
                            <?php foreach($tiendas as $tienda) { ?>
                                <tr class="table__tr">
                                    <td class="table__td">
                                        <?php echo $tienda->tienda;?>
                                    </td>                                
                                    <td class="table__col-actions" >
                                        <form id ="frSeleccionar<?php echo $tienda->idtienda; ?>"  method="POST" action="/tiendas" class="table__formulario">
                                            <input type="hidden" name="idtienda" value="<?php echo $tienda->idtienda;?>">
                                                <div class="table__acciones">
                                                    <button class="boton boton--primary"  type="submit" data-id="<?php echo $tienda->idtienda; ?>">
                                                        <i class="fa-solid fa-user-pen" ></i>
                                                    </button> 
                                                </div>

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

            

            <div class="table-footer">
                <div class="table-pagination" data-table-pagination="tablaTiendas"></div>  
            </div>
            
        </div>

    </div>

</main>