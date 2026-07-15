<?php

namespace Model;

class Perfiles extends ActiveRecord{
    protected static $tabla = 'perfiles';            
    protected static $columnasDB = ['id', 'nombre','inicial','idempresa','activo','idusercrea','fechacrea','idusermodi','fechamodi'];
  
    public ?int $id = null;
    public string $codigo = '';
    public string $nombre = '';
    public int $inicial = 0;
    public int $idempresa = 0;
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

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }      
        if(!$this->inicial) {
            self::$alertas['error'][] = 'Ingrese Pagina Inicial';
        } 
        return self::$alertas;
    }

}