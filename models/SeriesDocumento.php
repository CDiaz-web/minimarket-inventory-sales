<?php

namespace Model;

class SeriesDocumento extends ActiveRecord{
    protected static $tabla = 'series_documento';            
    protected static $columnasDB = ['id','idempresa','idtienda', 'idtipodocumento','serie','cantidad_digitos','ultimo_correlativo','predeterminado','activo','idusercrea','fechacrea','idusermodi','fechamodi'];
  
    public ?int $id = null;
    public int $idempresa = 0;
    public int $idtienda = 0;
    public int $idtipodocumento = 0;
    public string $serie = '';
    public int $cantidad_digitos = 0;
    public int $ultimo_correlativo = 0;    
    public int $predeterminado = 0;
    public int $activo = 1;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;   

    public string $nombre = '';
    public string $codigo = '';

    
    
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
        if(!$this->serie) {
            self::$alertas['error'][] = 'La serie es Obligatoria';
        }      
        if(!$this->idtienda) {
            self::$alertas['error'][] = 'La tienda es Obligatoria';
        } 
        if(!$this->cantidad_digitos) {
            self::$alertas['error'][] = 'El Número de digitos para el correlativo es Obligatorio';
        } 
        return self::$alertas;
    }

}