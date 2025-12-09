<?php

namespace Model;

class TipoCliente extends ActiveRecord {
    protected static $tabla = 'tipo_cliente';
    protected static $columnasDB = ['id', 'codigo', 'nombre','idlista','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];
 
    public ?int $id = null;
    public string $codigo = '';
    public string $nombre = '';
    public int $idlista = 0;
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

        if(!$this->codigo) {
            self::$alertas['error'][] = 'El Código es Obligatorio';
        }
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre no puede ir vacio';
        }
        if(!$this->idlista) {
            self::$alertas['error'][] = 'La Lista de Precios es Obligatorio';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado es Obligatorio';
        }
        return self::$alertas;

    }


   
}