<?php

namespace Model;

class Opciones extends ActiveRecord{
    protected static $tabla = 'opciones';            
    protected static $columnasDB = ['id', 'nombre','idsuperior','vista','icono','admin','boton','subnivel'];

    public $sel;
    public $id;
    public $nombre;
    public $idsuperior;
    public $vista;
    public $icono;
    public $hijos;
    public $admin;
    public $boton;
    public $subnivel;
  
  
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';     
        $this->idsuperior = $args['idsuperior'] ?? ''; 
        $this->vista = $args['vista'] ?? ''; 
        $this->icono = $args['icono'] ?? '';   
        $this->admin = $args['admin'] ?? '';         
                
    }


}