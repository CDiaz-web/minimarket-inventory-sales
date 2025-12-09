<?php

namespace Model;

class PerfilOpciones extends ActiveRecord{
    protected static $tabla = 'perfil_opciones';            
    protected static $columnasDB = ['idperfil', 'idopcion','idusercrea','fechacrea'];

    public $idperfil;
    public $idopcion;
    public $idusercrea;
    public $fechacrea;
    public $id;
    public function __construct($args = [])
    {
        $this->idperfil = $args['idperfil'] ?? null;
        $this->idopcion = $args['idopcion'] ?? '';     
        $this->idusercrea = $args['idusercrea'] ?? ''; 
        $this->fechacrea = $args['fechacrea'] ?? ''; 
        $this->id = $args['id'] ?? ''; 
             
                
    }


}