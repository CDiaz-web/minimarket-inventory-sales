<?php

namespace Model;

class InventarioDetalle extends ActiveRecord {
    protected static $tabla = 'detalle_inventario';
    protected static $columnasDB = ['id','idmovimiento','idproducto','cantidad','costo_unitario','venta_unitario','impuesto_unitario','subtotal_venta','total_venta','stock_anterior','stock_resultante','idusercrea','fechacrea'];

    public ?int $id = null;
    public ?int $iddetalle = null;
    public int $idmovimiento = 0;
    public int $idproducto = 0;
    public ?float $cantidad = null;
    public ?float $costo_unitario = null;
    public ?float $subtotal_costo = null;
    public ?float $venta_unitario = null;
    public ?float $impuesto_unitario = null;
    public ?float $subtotal_venta = null;
    public ?float $total_venta = null;
    public ?float $stock_anterior = null;
    public ?float $stock_resultante = null;    
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
   

    public string $documento  = ''; 
    public string $tienda_origen = '';  
    public string $movimiento = '';  
    public string $tipo = '';  
    public string $producto = '';  
    public ?string $tienda_destino = null;  
    public ?float $total = null;   
    public string $estado = '';  
    public string $codigo = ''; 
    public string $unidad = ''; 
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
        if(!$this->idproducto) {
            self::$alertas['error'][] = 'El Producto es Obligatoria';
        }       
       if(!$this->cantidad) {
            self::$alertas['error'][] = 'La cantidad es Obligatori';
        }  
 
        return self::$alertas;
    }
}