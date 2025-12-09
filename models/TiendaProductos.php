<?php

namespace Model;

class TiendaProductos extends ActiveRecord {
    protected static $tabla = 'tienda_productos';
    protected static $columnasDB = ['id', 'idtienda','idproducto', 'stock_actual','stock_comprometido', 'stock_min','stock_max', 'idusercrea','fechacrea','idusermodi','fechamodi'];

   
    public ?int $id = null;
    public int $idtienda = 0;  
    public int $idproducto = 0; 
    public ?float $stock_actual = null;
    public ?float $stock_comprometido = null;
    public ?float $stock_min = null;
    public ?float $stock_max = null;

    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;   
    
    public string $codigo = '';
    public string $categoria = '';
    public string $nombre = '';
    public string $unidad = '';
    public ?float $venta = null;
    public ?string $imagen = null;
    public ?int $idcategoria = null;
    
   
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
        if(!$this->idproducto) {
            self::$alertas['error'][] = 'El Producto es Obligatorio';
        }
        
        return self::$alertas;

    }

    
}