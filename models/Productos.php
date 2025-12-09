<?php

namespace Model;

class Productos extends ActiveRecord {
    protected static $tabla = 'productos';
    protected static $columnasDB = ['id', 'idempresa','codigo', 'nombre','idcategoria', 'costo','venta','idunidad_medida','imagen','idestado',  'idusercrea','fechacrea','idusermodi','fechamodi'];

   
    public ?int $id = null;
    public int $idempresa = 0;    
    public string $codigo = '';
    public string $nombre = '';
    public ?int $idcategoria = null;
    public ?string $imagen = null;
    public ?string $imagen_actual = null;
    public ?int $idunidad_medida = null;
    public ?int $idestado = null;
    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;    
    public ?float $costo = null;
    public ?float $venta = null;
    public ?float $utilidad = null;    
    public ?float $stock_actual = null;
    public ?float $stock_minimo = null;
    public ?float $stock_maximo = null;
    public string $categoria = '';


    public string $unidad = '';
   
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
        if(!$this->codigo) {
            self::$alertas['error'][] = 'El Codigo es Obligatorio';
        }
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->idcategoria) {
            self::$alertas['error'][] = 'La categoria es Obligatorio';
        }
        if(!$this->costo) {
            self::$alertas['error'][] = 'El Costo es Obligatorio';
        }
        if(!$this->venta) {
            self::$alertas['error'][] = 'El Precio de venta es Obligatorio';
        }
        if(!$this->idunidad_medida) {
            self::$alertas['error'][] = 'La unidad es Obligatorio';
        }
        if(!$this->idestado) {
            self::$alertas['error'][] = 'El estado es Obligatorio';
        }
        return self::$alertas;

    }

    
}