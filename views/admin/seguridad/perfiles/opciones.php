<?php
    function generarTreeView($categorias) {
        echo '<ul class="treeview__ul">';
        foreach ($categorias as $categoria) {
            echo '<li class="treeview__li">';
            
            // Si $categoria->isChecked es verdadero, el checkbox estará marcado
            $checked = $categoria->sel ? 'checked' : '';

            echo '<input type="checkbox" id="' . $categoria->id . '" ' . $checked . '>';
            echo '<label for="cat-' . $categoria->id . '">' . htmlspecialchars($categoria->nombre) . '</label>';

            // Si tiene hijos, generar recursivamente el treeview para ellos
            if (!empty($categoria->hijos)) {
                generarTreeView($categoria->hijos);
            }

            echo '</li>';
        }
        echo '</ul>';
    }
?>


<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contendor-boton">
    <a class="dashboard__boton" href="/admin/seguridad/perfiles">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario" id="treeview-container">    

    <form method="POST"  enctype="multipart/form-data" class="formulario">
        
        <?php generarTreeView($accesoRaiz); ?>
        <!-- <input class="formulario__submit formulario__submit--registrar" type="submit" value="Guardar Cambios"  id="guardarSeleccionados"> -->
    </form>
    <button id="guardarSeleccionados" class="dashboard__boton-agregar">Guardar seleccionados</button>

</div>