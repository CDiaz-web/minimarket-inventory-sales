<?php

namespace Model;

class OrdenCompraDetalle extends ActiveRecord {
    protected static $tabla = 'orden_compra_detalle';
    protected static $columnasDB = ['id','idorden','idproducto','cantidad','costo_origen','costo_igv_origen','subtotal_origen','total_origen','costo_base','costo_igv_base','subtotal_base','total_base','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public ?int $idorden = null;
    public int $idproducto = 0;
    public ?float $cantidad = null;

    public ?float $costo_origen = null;
    public ?float $costo_igv_origen = null;
    public ?float $subtotal_origen = null;
    public ?float $total_origen = null;
    public ?float $costo_base = null;
    public ?float $costo_igv_base = null;
    public ?float $subtotal_base = null;
    public ?float $total_base = null;        
    
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

    public ?int $iddetalle = null;
    public ?int $idarticulo = null;

    public string $unidad = '';
    public string $nombre = '';
    public string $codigo = '';
    public ?float $cantidad_recibida = null;
    public ?float $porrecibir = null;
    public ?float $arecibir = null;

    
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
     * Valida que los datos sean correctos
     * @return array
     */
    public function validar(): array {
        if(!$this->idproducto) {
            self::$alertas['error'][] = 'El Producto es Obligatoria';
        }       
       if(!$this->cantidad) {
            self::$alertas['error'][] = 'La cantidad es Obligatoria';
        }  
 
        return self::$alertas;
    }
}