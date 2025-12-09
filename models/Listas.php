<?php

namespace Model;

class Listas extends ActiveRecord {
    protected static $tabla = 'lista_precios';
    protected static $columnasDB = ['id','idempresa','codigo','descripcion','idmoneda','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idempresa = 0;
    public ?string $codigo = null;    
    public string $descripcion = '';
    public int $idmoneda = 0;
    public int $idestado = 0;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

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
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El Código es Obligatorio';
        }
        if(!$this->descripcion) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
      
        if(!$this->idmoneda) {
            self::$alertas['error'][] = 'La Moneda es Obligatoria';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado es Obligatorio';
        }
        return self::$alertas;
    }
}
