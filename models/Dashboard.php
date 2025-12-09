<?php

namespace Model;

class Dashboard extends ActiveRecord {
        
    //protected static $columnasDB = ['id', 'nombre', 'apellido','idsocio','idperfil', 'email','telefono',  'password', 'admin','estado', 'confirmado', 'token','idusercrea','fechacrea','idusermodi','fechamodi'];

    
    public $totalProductos;
    public $totalCompras;
    public $totalVentas;
    public $totalGanancias;
    public $productosPocoStock;
    public $ventasHoy;
    // public $simbolo_moneda;
    // public $moneda;
    
    // public $tpago_defecto;
    // public $porc_impues;
    public $ventasMes;
    public $codigo;
    public $nombre;
    public $cantidad;   


   
}