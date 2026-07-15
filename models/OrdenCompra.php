<?php

namespace Model;

class OrdenCompra extends ActiveRecord {
    protected static $tabla = 'orden_compra';
    protected static $columnasDB = ['id','numero','idempresa','idtienda','idproveedor','idmoneda','fecha','tc_oficial','tc_operacion','observacion','idestado','subtotal_origen','porcentaje_impuesto','igv_origen','total_origen','subtotal_base','igv_base','total_base','iduserapro','fechaapro','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public string $numero = '';    
    public int $idempresa = 0;
    public int $idtienda = 0;
    public int $idproveedor = 0;
    public int $idmoneda = 0;
    public ?string $fecha = null;
    public ?float $tc_oficial = null;
    public ?float $tc_operacion = null;
    public string $observacion = ''; 
    public int $idestado = 0;
    public ?float $subtotal_origen = null;
    public ?float $porcentaje_impuesto = null;
    public ?float $igv_origen = null;
    public ?float $total_origen = null;

    public ?float $subtotal_base = null;    
    public ?float $igv_base = null;
    public ?float $total_base = null;
    public int $iduserapro = 0;
    public ?string $fechaapro = null;

    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

    public ?int $idorden = null;
    
    public string $proveedor = ''; 
    public string $documento  = ''; 
    public string $direccion_proveedor = ''; 
    public string $tienda = ''; 
    public string $direccion = ''; 
    public string $usuario = '';  
    public ?float $porcentaje_igv = null;
    public string $estado = '';  
    public string $moneda = '';  
    public string $simbolo = '';  
 
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
        if(!$this->idproveedor) {
            self::$alertas['error'][] = 'El Proveedor es Obligatoria';
        }       

        if($this->idmoneda) {   //salida por transferencia
            self::$alertas['error'][] = 'La Moneda es Obligatoria';                       
        }  
 
        return self::$alertas;
    }
}