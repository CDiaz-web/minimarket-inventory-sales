<?php

namespace Model;

class Correlativos extends ActiveRecord {
    protected static $tabla = 'correlativos';
    protected static $columnasDB = ['id','idtienda','tipo_documento','ultimo_numero','updated_at'];

    public ?int $id = null;    
    public int $idtienda = 0;
    public ?string $tipo_documento = null;
    public int $ultimo_numero = 0;
    public ?string $updated_at = null;

    public ?string $nombre = null;
  

    // Constructor opcional (sobreescribe valores si se pasan en $args)
    public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Valida que los datos de la categoría sean correctos
     * @return array
     */
    public function validar(): array {
        if(!$this->idtienda) {
            self::$alertas['error'][] = 'La tienda es Obligatorio';
        }     
        if(!$this->tipo_documento) {
            self::$alertas['error'][] = 'El Tipo de Documento es Obligatorio';
        }         
        return self::$alertas;
    }
}
