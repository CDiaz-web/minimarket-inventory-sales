<?php

namespace Model;

class Monedas extends ActiveRecord{
    protected static $tabla = 'monedas';            
    protected static $columnasDB = ['id', 'nombre', 'simbolo', 'idusercrea','fechacrea','idusermodi','fechamodi'];

    public $id;
    public $nombre;
    public $simbolo;
    public $idusercrea;
    public $fechacrea;
    public $idusermodi;
    public $fechamodi;

    public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {
            // Si la propiedad es int o ?int, convierte el valor
            $propiedadTipo = (new \ReflectionProperty($this, $key))->getType();
            if($propiedadTipo && $propiedadTipo->getName() === 'int') {
                $this->$key = $value !== '' ? (int)$value : null;
            } else {
                $this->$key = $value;
            }
        }
    }
    // Validar el Login de Usuarios
    public function validar() {

        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->simbolo) {
            self::$alertas['error'][] = 'El Simbolo es Obligatorio';
        }
        return self::$alertas;

    }

    }