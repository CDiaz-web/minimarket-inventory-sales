<?php

namespace Model;

class Estados extends ActiveRecord{
    protected static $tabla = 'estados';            
    protected static $columnasDB = ['id', 'idmaster', 'codigo', 'nombre','orden'];

    public $id;
    public $idmaster;
    public $codigo;
    public $nombre;
    public $orden;
   

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
   
    }