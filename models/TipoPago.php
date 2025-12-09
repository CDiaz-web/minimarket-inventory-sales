<?php

namespace Model;

class TipoPago extends ActiveRecord{
    protected static $tabla = 'tipo_pago';            
    protected static $columnasDB = ['id','codigo', 'nombre', 'idestado', 'idusercrea','fechacrea','idusermodi','fechamodi'];

    public $id;
    public $codigo;
    public $nombre;
    public $idestado;
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
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El codigo es Obligatorio';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El Estado es Obligatorio';
        }
        return self::$alertas;

    }

    }