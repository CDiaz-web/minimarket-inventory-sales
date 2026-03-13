
<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="table-header">
    <a class="boton boton--primary-link" href="/admin/seguridad/perfiles">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<form method="POST" class="formulario">
    <div class="treeview">
        <?php
            function generarTreeView($categorias) {
                echo '<ul class="treeview__ul">';
                foreach ($categorias as $categoria) {
                    echo '<li class="treeview__li">';
                    
                    $checked = $categoria->sel ? 'checked' : '';

                    echo '<input type="checkbox" id="cat-' . $categoria->id . '" ' . $checked . '>';
                    echo '<label for="cat-' . $categoria->id    . '">' . htmlspecialchars($categoria->nombre) . '</label>';

                    if (!empty($categoria->hijos)) {
                        generarTreeView($categoria->hijos);
                    }

                    echo '</li>';
                }
                echo '</ul>';
            }

            // 👇 AQUÍ ESTABA FALTANDO ESTO
            generarTreeView($accesoRaiz);
        ?>
    </div>

    <div class="form-wrapper">
        <button type="submit" class="boton boton--primary">
            Guardar seleccionados
        </button>
    </div>
</form>