<?php

namespace Model;

class TiposMovimientos extends ActiveRecord {
    protected static $tabla = 'tipo_movimiento';
    protected static $columnasDB = ['id','idempresa','codigo','nombre','accion','tipo_documento','es_transferencia','es_sistema','es_generado','mov_manual','activo','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idempresa = 0;
    public string $codigo = '';
    public string $nombre = '';
    public string $accion = '';
    public int $es_transferencia = 0;
    public int $es_sistema = 0;
    public int $es_generado = 0;
    public int $mov_manual = 1;
    public int $activo = 1;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;   
    public ?string $tipo_documento = null;
    public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {       
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
        if(!$this->accion) {
            self::$alertas['error'][] = 'La Accion es Obligatorio';
        }
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El Nombre no puede ir vacio';
        }
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre no puede ir vacio';
        }
        if(!$this->tipo_documento) {
            self::$alertas['error'][] = 'El Tipo de Documento es Obligatorio';
        } 
        return self::$alertas;

    }
   
}