<?php

namespace Model;

class Devoluciones extends ActiveRecord {
    protected static $tabla = 'motivos_devolucion';
    protected static $columnasDB = ['id',  'nombre','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public string $nombre = ''; 
    public int $idestado = 0;
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
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre no puede ir vacio';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado no puede ir vacio';
        }
        return self::$alertas;
    }
   
}