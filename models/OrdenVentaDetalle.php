<?php

namespace Model;

class OrdenVentaDetalle extends ActiveRecord {
    protected static $tabla = 'orden_venta_detalle';
    protected static $columnasDB = ['id','idorden','idproducto','cantidad','precio_unitario','precio_unitario_igv','total','total_igv','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public ?int $idorden = null;
    public int $idproducto = 0;
    public ?float $cantidad = null;
    public ?float $precio_unitario = null;
    public ?float $precio_unitario_igv = null;
    public ?float $total = null;
    public ?float $total_igv = null;

    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;

    public string $unidad = '';
    public string $nombre = '';
    public string $codigo = '';


    
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