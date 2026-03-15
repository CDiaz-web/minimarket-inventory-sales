<?php
namespace Controllers;

use Classes\Email;
use Model\Empresa;
use Model\Monedas;
use Model\Usuario;
use Model\Perfiles;
use MVC\Router;

class AuthController { 
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitizamos entrada
            $datos = filter_input_array(INPUT_POST, [
                'email' => FILTER_SANITIZE_EMAIL,
                'password' => FILTER_DEFAULT
            ]);

            $usuarioTmp = new Usuario($datos);
            $alertas = $usuarioTmp->validarLogin();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuarioTmp->email,true);
                // debuguear($usuario);        
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
                } elseif (!password_verify($datos['password'], $usuario->password)) {
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                } elseif (!$usuario->estaActivo()) {
                    Usuario::setAlerta('error', 'Usuario inactivo');
                } else {
                    // Inicia sesión
                    self::iniciarSesion($usuario);
                    header('Location: /tiendas');
                    exit;
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Renderiza vista de login
        $router->render('auth/login', [
            'titulo'  => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    private static function iniciarSesion($usuario)
    {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
        session_regenerate_id(true);

        $empresa = Empresa::where('id', $usuario->idempresa,true);
        $moneda = Monedas::where('id', $empresa->idmoneda,true);
        $perfil = Perfiles::where('id', $usuario->idperfil,true);
        $_SESSION = [
            'id'            => $usuario->id,
            'nombre'        => $usuario->nombre,
            'apellido'      => $usuario->apellido,
            'idempresa'       => $usuario->idempresa, 
            'empresa'       => $empresa->nombre,            
            'email'         => $usuario->email,
            'idperfil'      => $usuario->idperfil,
            'perfil'        => $perfil->nombre,
            'admin'         => $usuario->admin ?? null,
            'idtienda'      => '',
            'tienda'        => null,
            'simbolo_moneda'=> $moneda->simbolo,         
            'moneda'        => $empresa->idmoneda,           
            'igv'           => $empresa->porcentaje_imp,
            'validar_tc'    => $empresa->validar_tc,
            'tpago_defecto' => $empresa->idtipo_pago
        ];
    }

    public static function logout() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION = [];
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/'); // elimina cookie            
            header('Location: /login');
        }
       
    }

    public static function registro(Router $router) {
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validar_cuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email,true);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();

                    // Crear un nuevo usuario
                    $resultado =  $usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        // Render a la vista
        $router->render('auth/registro', [
            'titulo' => 'Crea tu cuenta en SID Negocios',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email,true);
      
                if($usuario && $usuario->confirmado) {

                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email( $usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarInstrucciones();


                    // Imprimir la alerta
                    // Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                    $alertas['exito'][] = 'Hemos enviado las instrucciones a tu email';
                } else {
                 
                    // Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');

                    $alertas['error'][] = 'El Usuario no existe o no esta confirmado';
                }
            }
        }

        // Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $token = s($_GET['token']);

        $token_valido = true;

        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token,true);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo');
            $token_valido = false;
        }


        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();

                // Eliminar el Token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redireccionar
                if($resultado) {
                    header('Location: /login');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'token_valido' => $token_valido
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router) {
        
        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token,true);

        if(empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido, la cuenat no se confirmo');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            
            // Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada exitosamente');
        }

     

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta DevWebcamp',
            'alertas' => Usuario::getAlertas()
        ]);
    }
}