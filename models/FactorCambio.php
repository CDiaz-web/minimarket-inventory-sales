<?php

namespace Model;

class FactorCambio extends ActiveRecord{
    protected static $tabla = 'factor_cambio';            
    protected static $columnasDB = ['id', 'idempresa','fecha','idmoneda_origen','idmoneda_destino','compra_oficial','venta_oficial','compra_mercado','venta_mercado','idusercrea','fechacrea','idusermodi','fechamodi'];
  
    public ?int $id = null;
    public int $idempresa = 0;
    public ?string $fecha = null;
    public int $idmoneda_origen = 0;
    public int $idmoneda_destino = 0;
    public ?float $compra_oficial = null;
    public ?float $venta_oficial = null;
    public ?float $compra_mercado = null;
    public ?float $venta_mercado = null;

    public int $idusercrea = 0;
    public ?string $fechacrea = null;
    public int $idusermodi = 0;
    public ?string $fechamodi = null;   

    public string $origen = '';
    public string $destino = '';
    
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

    public function validar() {
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es Obligatorio';
        }      
        if(!$this->idmoneda_origen) {
            self::$alertas['error'][] = 'La Moneda Origen es Obligatoria';
        } 
        if(!$this->idmoneda_destino) {
            self::$alertas['error'][] = 'La Moneda Destino es Obligatoria';
        } 
        if(!$this->idmoneda_destino) {
            self::$alertas['error'][] = 'La Moneda Destino es Obligatoria';
        } 
        if(!$this->compra_oficial) {
            self::$alertas['error'][] = 'Ingrese Compra Oficial';
        } 
        if(!$this->venta_oficial) {
            self::$alertas['error'][] = 'Ingrese Venta Oficial';
        } 

        if(!$this->compra_mercado) {
            self::$alertas['error'][] = 'Ingrese Compra Mercado';
        } 
        if(!$this->venta_mercado) {
            self::$alertas['error'][] = 'Ingrese Venta Mercado';
        } 

        return self::$alertas;
    }

}