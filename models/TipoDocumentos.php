<?php

namespace Model;

class TipoDocumentos extends ActiveRecord {
    protected static $tabla = 'tipo_documento';
    protected static $columnasDB = ['id','idempresa', 'codigo', 'nombre','abreviatura','comprobante_pago','activo'];
 
    public ?int $id = null;
    public int $idempresa = 0;
    public string $codigo = '';    
    public string $nombre = '';    
    public int $comprobante_pago = 0;
    public int $activo = 1;
    public string $abreviatura = '';     
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