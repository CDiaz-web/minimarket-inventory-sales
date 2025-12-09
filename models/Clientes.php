<?php

namespace Model;

class Clientes extends ActiveRecord {
    protected static $tabla = 'clientes';
    protected static $columnasDB = ['id','idempresa','tipo_persona','documento','nombre','apellidos','razon_social','nombre_cliente','idtipo','telefono','direccion','idtienda_default','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idempresa = 0;
    public string $tipo_persona = '';
    public ?string $documento = null;    
    public string $nombre = '';
    public string $apellidos = '';
    public string $razon_social = '';
    public string $nombre_cliente = '';
    public int $idtipo = 0;
    public string $telefono = '';
    public string $direccion = '';
    public int $idtienda_default = 0;
    public int $idestado = 0;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

    public string $clasificacion = '';

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
        if(!$this->documento) {
            self::$alertas['error'][] = 'El Documento es Obligatorio';
        }

        if(!$this->tipo_persona) {
            self::$alertas['error'][] = 'El tipo de persona es obligatorio';
        }

        if(!$this->tipo_persona ==="N") {
            if(!$this->nombre) {
                self::$alertas['error'][] = 'El Nombre es Obligatorio';
            }
            if(!$this->apellidos) {
                self::$alertas['error'][] = 'El Apellido es Obligatorio';
            }
        }

         if(!$this->tipo_persona ==="J") {
            if(!$this->razon_social) {
                self::$alertas['error'][] = 'La razon social es Obligatorio';
            }
        }       

        if(!$this->idtipo) {
            self::$alertas['error'][] = 'La Clasificacion es Obligatoria';
        }
        if(!$this->idtienda_default) {
            self::$alertas['error'][] = 'La Tienda por defecto es Obligatoria';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado es Obligatorio';
        }
        return self::$alertas;
    }
}
