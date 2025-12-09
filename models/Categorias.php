<?php

namespace Model;

class Categorias extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id','codigo','idempresa','nombre','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public ?string $codigo = null;
    public int $idempresa = 0;
    public string $nombre = '';
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
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(strlen($this->nombre) > 45) {
            self::$alertas['error'][] = 'El Nombre no puede tener más de 45 caracteres';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado es Obligatorio';
        }
        return self::$alertas;
    }
}
