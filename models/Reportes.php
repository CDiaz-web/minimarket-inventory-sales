<?php

namespace Model;

class Reportes extends ActiveRecord {
    protected static $tabla = null;

    public ?string $numero = null;
    public ?string $fechaapro = null;
    public ?string $cliente = null;
    public ?string $documento = null;
    public ?float $subtotal = null;
    public ?float $igv = null;
    public ?float $total = null;
    public ?string $aprobado_por = null;
    public ?float $ticket_promedio = null;

    public ?int $cantidad_ordenes = null;
    public ?float $subtotal_vendido = null;
    public ?float $total_igv = null;
    public ?float $total_general = null;

    public ?string $codigo = null;
    public ?string $producto = null;
    public ?int $cantidad_vendida = null;
    public ?float $precio_promedio = null;
    public ?string $estado = null;

    public ?float $stock_actual = null;
    public ?float $stock_comprometido = null;
    public ?float $stock_disponible = null;
    public ?float $stock_min = null;
    public ?float $stock_max = null;

    public static function ventasPorTienda($idTienda, $f1, $f2, $opcion)
    {
        return self::procedureLista(
            'prc_ventas_por_tienda',
            [$opcion, $idTienda, $f1, $f2]
        );
    }

    public static function ventasPorCliente($idTienda, $f1, $f2)
    {
        return self::procedureLista(
            'prc_ventas_por_cliente',
            [$idTienda, $f1, $f2]
        );
    }

    public static function ventasPorProducto($idTienda, $f1, $f2)
    {
        return self::procedureLista(
            'prc_ventas_por_producto',
            [$idTienda, $f1, $f2]
        );
    }
    public static function ventasPorEstado($idTienda, $f1, $f2)
    {
        return self::procedureLista(
            'prc_ventas_por_estado',
            [$idTienda, $f1, $f2]
        );
    }
    public static function Inventario($idTienda)
    {
        return self::procedureLista(
            'prc_inventario',
            [$idTienda]
        );
    }
}