<div class="evento swiper-slide">    
    <div class="evento__informacion">
        <h4 class="evento__nombre"><?php echo $categoria->nombre; ?></h4>         
        <div class="evento__autor-info">
            <picture>
                <source srcset="img/categorias/<?php echo $categoria->imagen; ?>.webp" type="image/webp">
                <source srcset="img/categorias/<?php echo $categoria->imagen; ?>.png" type="image/png">
                <img class="evento__imagen-autor" loading="lazy" width="200" height="300" src="img/categorias/<?php echo $categoria->imagen; ?>.png" alt="Imagen Ponente">
            </picture>           
        </div>
    </div>
</div>