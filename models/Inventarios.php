<?php

namespace Model;

class Inventarios extends ActiveRecord {
    protected static $tabla = 'movimiento_inventario';
    protected static $columnasDB = ['id','numero','idempresa','idtienda','idtipo','fecha','observacion','idrelacion','idtienda_relacion','idestado','motivo_anulacion','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public string $numero = ''; 
    public string $codigotipo = '';  
    public string $tipo_movimiento = '';   

    public int $idempresa = 0;
    public int $idtienda = 0;
    public int $idtipo = 0;
    public ?string $fecha = null;
    public string $observacion = '';  
    public ?string $motivo_anulacion = null;
    public int $es_generado = 0;
    // public int $idrelacion = 0;
    public ?int $idrelacion = null;
    // public int $idtienda_relacion = 0;
    public ?int $idtienda_relacion = null;
    public int $idestado = 0;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

    public int $idmovimiento = 0;
    public string $documento  = ''; 
    public string $tienda_origen = '';  
    public string $movimiento = '';  
    public ?string $tienda_destino = null;  
    public ?string $codestado = null;
    public string $estado = '';  
    public string $usuario = '';  
   
    public function __construct(array $args = []) {
        foreach ($args as $key => $value) {
             if (($key === 'idrelacion' || $key === 'idtienda_relacion') && $value === '') {
                $this->$key = null;
            } else {
                $this->$key = $value;
            }
        }
    }
    /**
     * Valida que los datos de la categoría sean correctos
     * @return array
     */
    public function validar(): array {
        if(!$this->codigotipo) {
            self::$alertas['error'][] = 'El tipo de Movimiento es Obligatoria';
        }       

        if($this->idtipo == 7) {   //salida por transferencia
            if(!$this->idtienda_relacion) {
                self::$alertas['error'][] = 'La tienda de Destino es Obligatoria';
            }                          
        }  

 
        return self::$alertas;
    }
}