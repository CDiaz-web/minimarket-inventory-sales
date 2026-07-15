<?php

namespace Model;

class TipoPago extends ActiveRecord{
    protected static $tabla = 'tipo_pago';            
    protected static $columnasDB = ['id','idempresa','codigo', 'nombre', 'requiere_cobro','activo', 'idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idempresa = 0;
    public string $codigo = '';
    public string $nombre = '';
    public int $activo = 1;
    public int $requiere_cobro = 0;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;  
     
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
 
    public function validar() {

        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El codigo es Obligatorio';
        }
        return self::$alertas;

    }

    }