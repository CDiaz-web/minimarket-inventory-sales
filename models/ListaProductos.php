<?php

namespace Model;

class ListaProductos extends ActiveRecord {
    protected static $tabla = 'lista_productos';
    protected static $columnasDB = ['id','idlista', 'idproducto','precio','idusercrea','fechacrea','idusermodi','fechamodi'];

    public ?int $id = null;
    public ?int $idlista = 0;   
    public int $idproducto = 0;
    public ?float $precio = null;
    public string $producto = '';
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;
    
 public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
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