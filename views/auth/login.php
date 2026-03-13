
<main class="auth">

    <div class="auth__contenedor">

        <h2 class="auth__heading"><?php echo $titulo; ?></h2>

        <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

        <form method="POST" action="/login" class="formulario">
            <div class="formulario__campo">
                <label class="formulario__label">Email</label>
                <input type="email" name="email" class="formulario__input" placeholder="Tu Email"/>
            </div>

            <div class="formulario__campo">
                <label class="formulario__label">Password</label>
                <input type="password" name="password" class="formulario__input" placeholder="Tu Password"/>
            </div>

            <input type="submit" class="formulario__submit" value="Iniciar Sesion">
        </form>

        <div class="acciones">
            <a href="/registro" class="acciones__enlace">¿Aun no tienes cuenta? Obtener una</a>
            <a href="/olvide" class="acciones__enlace">¿Olvidaste tu Password?</a>
        </div>

    </div>

</main>