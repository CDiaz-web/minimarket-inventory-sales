<?php

namespace Model;

class Proveedores extends ActiveRecord {
    protected static $tabla = 'proveedores';
    protected static $columnasDB = ['id','idempresa','tipo_persona','documento','nombre','apellidos','razon_social','nombre_proveedor','direccion','telefono','email','contacto','activo','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idempresa = 0;
    public string $tipo_persona = '';
    public ?string $documento = null;    
    public string $nombre = '';
    public string $apellidos = '';
    public string $razon_social = '';
    public string $nombre_proveedor = '';
    public string $direccion = '';
    public string $telefono = '';
     public string $email = '';
    public string $contacto = '';

    public int $activo = 1;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;
    public int $idlista = 0;
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

        return self::$alertas;
    }
}
