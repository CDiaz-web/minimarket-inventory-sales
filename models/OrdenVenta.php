<?php

namespace Model;

class OrdenVenta extends ActiveRecord {
    protected static $tabla = 'orden_venta';
    protected static $columnasDB = ['id','idcarrito','numero','idcliente','idtienda','idtipopago','idmoneda','fecha','observacion','factor','subtotal','impuesto','total','idestado','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public int $idcarrito = 0;
    public string $numero = '';
    public int $idcliente = 0;
    public int $idtienda = 0;
    public int $idmoneda = 0;
    public int $idtipopago = 0;
    public ?string $fecha = null;
    public string $observacion = ''; 
    public ?float $factor = null;
    public ?float $subtotal = null;
    public ?float $impuesto = null;
    public ?float $total = null;
    public int $idestado = 0;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;
    

    public string $codigotipo = '';  
    public string $tipo_movimiento = ''; 
    public ?int $idrelacion = null;
    public ?int $idtienda_relacion = null;
    public string $documento  = ''; 
    public string $tienda_origen = '';  
    public string $movimiento = '';  
    public ?string $tienda_destino = null;  
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
        if(!$this->idcliente) {
            self::$alertas['error'][] = 'El Cliente es Obligatoria';
        }       

        if($this->idmoneda) {   //salida por transferencia
            self::$alertas['error'][] = 'La Moneda es Obligatoria';                       
        }  
 
        return self::$alertas;
    }
}