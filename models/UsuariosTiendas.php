<?php

namespace Model;

class UsuariosTiendas extends ActiveRecord {
    protected static $tabla = 'usuarios_tiendas';
    protected static $columnasDB = ['id','idusuario', 'idtienda','idusercrea','fechacrea'];

    public ?int $id = null;
    public ?int $idusuario = 0;   
    public int $idtienda = 0;
    public string $tienda = '';
    public int $idusercrea = 0;
    public ?string $fechacrea = null;

    
 public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    // Validar el Login de Usuarios
    public function validar() {
        if(!$this->idtienda) {
            self::$alertas['error'][] = 'La Tienda es Obligatorio';
        }      
        return self::$alertas;
    }

    
}