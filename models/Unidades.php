<?php

namespace Model;

class Unidades extends ActiveRecord {
    protected static $tabla = 'unidad_medida';
    protected static $columnasDB = ['id', 'idempresa','codigo', 'nombre','activo','idusercrea','fechacrea','idusermodi','fechamodi'];
 
    public ?int $id = null;
     public int $idempresa = 0;
    public string $codigo = '';
    public string $nombre = '';
    public int $activo = 1;
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

    /* ===================================================
     * VALIDACIONES
     * =================================================== */
    public function validar() {
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El Código es Obligatorio';
        }
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre no puede ir vacio';
        }
        return self::$alertas;

    }


   
}