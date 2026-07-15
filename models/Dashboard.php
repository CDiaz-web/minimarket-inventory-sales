<?php

namespace Model;

class Dashboard extends ActiveRecord {
        
    public $totalProductos;
    public $totalCompras;
    public $totalComprasAnt;
    public $totalVentas;
    public $totalGanancias;
    public $totalGananciasAnt;
    public $productosPocoStock;
    public $ventasAyer;
    public $ventasHoy;

    public $ventasMesAnterior;
    public $ventasMes;
    public $codigo;
    public $nombre;
    public $cantidad;   


   
}