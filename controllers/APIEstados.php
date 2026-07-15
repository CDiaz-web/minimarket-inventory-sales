<?php

namespace Controllers;

use Model\Usuario;
use Model\Clientes;
use Model\Productos;
use Model\Perfiles;
use Model\Tiendas;
use Model\Unidades;
use Model\TipoPago;
use Model\TipoCliente;
use Model\Listas;
use Model\Categorias;
use Model\Proveedores;
use Model\Devoluciones;

class APIEstados{

    public static function estados(){
   
        if(is_auth()){  
            
            header('Content-Type: application/json');

            $data = json_decode(file_get_contents("php://input"), true);

            $id = $data['id'] ?? null;
            $modelo = $data['modelo'] ?? null;
            $activo = $data['activo'] ?? null;


            if (!$id || !$modelo) {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Datos incompletos'
                ]);
                exit;
            }
            
            $modelosPermitidos = [
                'Usuarios' => Usuario::class,
                'Perfiles' => Perfiles::class,
                'Tiendas' => Tiendas::class,
                'Productos' => Productos::class,
                'Unidades' => Unidades::class,
                'TipoPago' => TipoPago::class,
                'TipoCliente' => TipoCliente::class,
                'Listas' => Listas::class,
                'Categorias' => Categorias::class,
                'Proveedores' => Proveedores::class,
                'Clientes' => Clientes::class,
                'Devoluciones' => Devoluciones::class,
            ];

            if (!array_key_exists($modelo, $modelosPermitidos)) {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Modelo no permitido'
                ]);
                exit;
            }

            $modeloClase = $modelosPermitidos[$modelo];

            $registro = $modeloClase::find($id);
            
            if (!$registro) {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Registro no encontrado'
                ]);
                exit;
            }
            
            $registro->activo = $activo;
            $resultado = $registro->guardar();

            echo json_encode([
                'ok' => $resultado ? true : false
            ]);
            exit;

        }

    }

}

