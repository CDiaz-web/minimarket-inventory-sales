<?php

namespace Model;

class Dashboard extends ActiveRecord {
        
    public $totalProductos;
    public $totalCompras;
    public $totalVentas;
    public $totalGanancias;
    public $productosPocoStock;
    public $ventasAyer;
    public $ventasHoy;

    public $ventasMesAnterior;
    public $ventasMes;
    public $codigo;
    public $nombre;
    public $cantidad;   


   
}