<?php

namespace Model;

class Empresa extends ActiveRecord {
    protected static $tabla = 'empresas';
    protected static $columnasDB = ['id', 'nombre','ruc','direccion', 'idmoneda','idtipo_pago','porcentaje_imp','email','logo','validar_tc','variaciontc','ov_requiere_aprobacion','oc_requiere_aprobacion','idusercrea','fechacrea','idusermodi','fechamodi'];


    public ?int $id = null;
    public string $codigo = '';
    public string $nombre = '';
    public string $ruc = '';
    public string $direccion = '';
    public int $idmoneda = 0;
    public int $idtipo_pago = 0;
    public ?float $porcentaje_imp = null;
    public string $email = '';
    public string $logo = '';
    public int $validar_tc = 0;
    public int $ov_requiere_aprobacion = 1;
    public int $oc_requiere_aprobacion = 1;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;   
    public ?float $variaciontc = null;
    
    public string $logo_actual = '';
    
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

    // Validar el Login de Usuarios
    public function validar() {
        if(!$this->ruc) {
            self::$alertas['error'][] = 'El RUC es Obligatorio';
        }
        if(!$this->nombre) {
            self::$alertas['error'][] = 'La Razon Social es Obligatoria';
        }
        if(!$this->direccion) {
            self::$alertas['error'][] = 'La direccion es Obligatorio';
        }
        if(!$this->idmoneda) {
            self::$alertas['error'][] = 'La Moneda es Obligatoria';
        }
        if(!$this->porcentaje_imp) {
            self::$alertas['error'][] = 'El porcentaje de Igv es Obligatorio';
        }
        return self::$alertas;

    }

    
}