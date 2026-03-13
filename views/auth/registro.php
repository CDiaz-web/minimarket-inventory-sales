<main class="auth">

    <div class="auth__contenedor">    

        <h2 class="auth__heading"><?php echo $titulo; ?></h2>   

        <?php
            require_once __DIR__ . '/../templates/alertas.php';
        ?>

        <form method="POST" action="/registro" class="formulario">
            <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre</label>
                <input
                    type="text"
                    class="formulario__input"
                    placeholder="Tu Nombre"
                    id="nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                />
            </div>
            <div class="formulario__campo">
            <label for="apellido" class="formulario__label">Apellido</label>
                <input
                    type="text"
                    class="formulario__input"
                    placeholder="Tu Apellido"
                    id="apellido"
                    name="apellido"
                    value="<?php echo $usuario->apellido; ?>"
                />
            </div>
            <div class="formulario__campo">
                <label for="email" class="formulario__label">Email</label>
                <input
                    type="email"
                    class="formulario__input"
                    placeholder="Tu Email"
                    id="email"
                    name="email"
                    value="<?php echo $usuario->email; ?>"
                />
            </div>
            <div class="formulario__campo">
                <label for="telefono" class="formulario__label">Teléfono</label>
                <input
                    type="tel"
                    class="formulario__input"
                    placeholder="Tu Teléfono"
                    id="telefono"
                    name="telefono"
                    value="<?php echo $usuario->telefono; ?>"
                />
            </div>
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Password</label>
                <input
                    type="password"
                    class="formulario__input"
                    placeholder="Tu Password"
                    id="password"
                    name="password"
                />
            </div>
            <div class="formulario__campo">
                <label for="password2" class="formulario__label">Repetir Password</label>
                <input
                    type="password"
                    class="formulario__input"
                    placeholder="Repite Tu Password"
                    id="password2"
                    name="password2"
                />
            </div>
            <input type="submit" class="formulario__submit" value="Crear Cuenta">
        </form>
        <div class="acciones">
            <a href="/login" class="acciones__enlace">¿Ya tienes cuenta?Inicia Sesion</a>
            <a href="/olvide" class="acciones__enlace">¿Olvidate su Password?</a>
        </div>

    </div>



</main>
