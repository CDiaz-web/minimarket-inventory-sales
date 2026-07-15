<?php
namespace Model;
class ActiveRecord {
    // Base DE DATOS
    protected static $tabla = ''; 
    protected static $columnasDB = [];
    protected static $db;
    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas() {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    public static function consultarSQL($query) {
      
        
        $resultado = self::$db->query($query);       
      
        $array = [];
    
        // Verificar si hay resultados
        if ($resultado) {
            // Iterar los resultados del primer conjunto
            while ($registro = $resultado->fetch_assoc()) {
                $array[] = static::crearObjeto($registro);
            }
            // Liberar el primer resultado
            $resultado->free();
    
            // Procesar cualquier conjunto de resultados adicional
            while (self::$db->more_results()) {
                self::$db->next_result(); // Avanzar al siguiente conjunto de resultados
    
                // Si hay más resultados, los liberamos también
                if ($resultado = self::$db->store_result()) {
                    $resultado->free();  // Liberar los resultados adicionales
                }
            }
        }
    
        // Retornar el array con los resultados
        return $array;
    }
    
    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                // $objeto->$key = $value;
                if(is_null($value)) {
                    $objeto->$key = null;
                } else {
                    $objeto->$key = $value;
                }
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    } 

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos(): array {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            if (is_null($value)) {
                // Forzamos a cadena vacía si es null
                $sanitizado[$key] = '';
            } else {
                // escapamos lo que sí tenga valor
                $sanitizado[$key] = self::$db->escape_string((string)$value);
            }
        }

        return $sanitizado;
    }



    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
                $refProp = new \ReflectionProperty($this, $key);
                $type = $refProp->getType();

                if ($type) {
                    $typeName   = $type->getName();
                    $isNullable = $type->allowsNull();

                    // Manejo de valores nulos o vacíos
                    if ($value === '' || $value === null) {
                        $this->$key = $isNullable ? null : $this->$key;
                        continue;
                    }

                    switch ($typeName) {
                        case 'int':
                            $this->$key = (int) $value;
                            break;

                        case 'float':
                            $this->$key = (float) $value;
                            break;

                        case 'string':
                            $this->$key = (string) $value;
                            break;

                        case 'bool':
                            $this->$key = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                            if ($this->$key === null && $isNullable) {
                                $this->$key = null;
                            }
                            break;

                        case \DateTime::class:
                            try {
                                $this->$key = new \DateTime($value);
                            } catch (\Exception $e) {
                                $this->$key = $isNullable ? null : $this->$key;
                            }
                            break;

                        default:
                            // Si es otro objeto (ejemplo: tu propio ValueObject)
                            $this->$key = $value;
                            break;
                    }
                } else {
                    // Si no tiene tipo declarado
                    $this->$key = $value;
                }
            }
        }
    }


    public function toArray(): array {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;

            $valor = $this->$columna ?? null;

            if ($valor instanceof \DateTime) {
                // Guardar fechas en formato estándar
                $atributos[$columna] = $valor->format('Y-m-d H:i:s');
            } elseif (is_bool($valor)) {
                // Convertir bool a 1 o 0
                $atributos[$columna] = $valor ? 1 : 0;
            } elseif (is_float($valor)) {
                // Asegurar formato decimal
                $atributos[$columna] = number_format($valor, 2, '.', '');
            } else {
                // Cualquier otro valor (int, string, null, etc.)
                $atributos[$columna] = $valor;
            }
        }

        return $atributos;
    }
    public function toPublicArray(): array {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {    

            $valor = $this->$columna ?? null;

            if ($valor instanceof \DateTime) {
                // Guardar fechas en formato estándarrequiere_cobro
                $atributos[$columna] = $valor->format('Y-m-d H:i:s');
            } elseif (is_bool($valor)) {
                // Convertir bool a 1 o 0
                $atributos[$columna] = $valor ? 1 : 0;
            } elseif (is_float($valor)) {
                // Asegurar formato decimal
                $atributos[$columna] = number_format($valor, 2, '.', '');
            } else {
                // Cualquier otro valor (int, string, null, etc.)
                $atributos[$columna] = $valor;
            }
        }

        return $atributos;
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        
        if(!is_null($this->id)) {
            // actualizar            
           
            $resultado = $this->actualizar();
            
        } else {
            // Creando un nuevo registro
      
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Obtener todos los Registros
    public static function all($orden = 'DESC',$columna='id') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY {$columna} {$orden}";       
        $resultado = self::consultarSQL($query);        
        return $resultado;
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor,$unico = false) {        
        $valor = self::$db->escape_string($valor);
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'";        
        // debuguear($query );
        $resultado = self::consultarSQL($query); 
        return $unico ? array_shift($resultado) : $resultado; 
    } 

    // Busqueda Where con multriples opciones 
    public static function findArray($array =[],$unico = false) {
       //si unico es true retorna array_shift($resultado)(solo 1) caso contrario $resultado (varios)
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
         foreach($array as $key => $value){
              $value = self::$db->escape_string($value);// se agrega para limpiar
             if($key == array_key_last($array)){
                 $query .= " {$key} = '{$value}'";
             }else{
                 $query .= " {$key} = '{$value}' AND ";
             }            
         }       
        //  debuguear($query);
         $resultado = self::consultarSQL($query); 
         return $unico ? array_shift($resultado) : $resultado; 
    }

    public static function findArrayOperador($condiciones = [], $unico = false) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        $parts = [];

        foreach($condiciones as $condicion){
            $campo = $condicion['campo'];
            $operador = $condicion['operador'] ?? '='; // si no envías, usa '=' por defecto
            $valor = self::$db->escape_string($condicion['valor']);
            $parts[] = " {$campo} {$operador} '{$valor}'";
        }

        $query .= implode(" AND ", $parts);

        $resultado = self::consultarSQL($query);
        return $unico ? array_shift($resultado) : $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {

        $id = intval($id);

        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id} LIMIT 1";

        $resultado = self::consultarSQL($query);

        if(empty($resultado)){
            return null;
        }

        return $resultado[0];
    }



    // Busca un registro por su id

    public static function procedureLista($nombre, $valores =[]) {
        $variables = '"' . implode('","',$valores) . '"';
        if($valores){
            $query = " CALL {$nombre}({$variables}) "; 
        }else{
            $query = " CALL {$nombre} "; 
        }       
        // debuguear($query);
        $resultado = self::consultarSQL($query);    
         
        return $resultado  ;
    }

    public static function procedure( $nombre,$valores =[]) {
        $variables = '"' . implode('","',$valores) . '"';
        if($valores){            
            $query = " CALL {$nombre} ({$variables})";
        }else{
            $query =  " CALL {$nombre}  " ; 
        }          
        $resultado = self::consultarSQL($query);    
        
        return array_shift( $resultado ) ;
    }

    public static function procedureMantenimiento($nombre, $valores =[]) {
        $parts = array_map(function($v) {
            if ($v === null) {
                return 'NULL';
            }
            if (is_numeric($v)) {
                return $v; // número sin comillas
            }
            return "'" . self::$db->real_escape_string((string)$v) . "'";
        }, $valores);

        $variables = implode(',', $parts);

        try {
            $query = "CALL {$nombre}({$variables})";       
            $resultado = self::$db->query($query);
            return $resultado;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception("Error ejecutando procedimiento: " . $e->getMessage());
        }
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . "  ORDER BY id DESC LIMIT {$limite} " ;
        $resultado = self::consultarSQL($query);
        return  $resultado ;
    }
    //paginar los registros

    public static function paginar($por_pagina, $offset){
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$por_pagina} OFFSET {$offset}" ;
        $resultado = self::consultarSQL($query);
        return  $resultado ; 
    }


    // Busqueda Where con Columna 
    public static function opcionesMenu($valor) {     

        $query =  " select t2.id,t2.nombre,t2.idsuperior,t2.vista,t2.icono,t2.admin, t2.boton, t2.subnivel from perfil_opciones t1 ";
        $query .= " inner join opciones t2 on t1.idopcion = t2.id ";  
        $query .= " where t1.idperfil = '{$valor}' order by t2.id ";

        $resultado = self::consultarSQL($query);    
         
        return $resultado  ; 
    }

    //retornar los registros por un orden
    public static function ordenar($columna,$orden){
        $query = "SELECT * FROM " . static::$tabla . " order by {$columna}  {$orden} ";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //retornar los registros por un orden
    public static function ordenarLimite($columna,$orden,$limite){
        $query = "SELECT * FROM " . static::$tabla . " order by {$columna}  {$orden} LIMIT {$limite} ";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //traer total de registros

    public static function total($columna ='', $valor = ''){
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        if($columna){
            $query .= " WHERE {$columna}  = {$valor} ";
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        return array_shift( $total );
    }

    // total de registros con un array where
    public static function totalArray($array = []){
        $query = "SELECT COUNT(*) FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value){
            if($key == array_key_last($array)){
                $query .= " {$key} = '{$value}'";
            }else{
                $query .= " {$key} = '{$value}' AND ";
            }
            
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        return array_shift( $total );
    }

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . "(";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "') ";

        //debuguear($query); // Descomentar si no te funciona algo

        // Resultado de la consulta
        $resultado = self::$db->query($query);

        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }        
        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 
        //debuguear($query); 
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        // debuguear($query);
        $resultado = self::$db->query($query);
    
        return $resultado;
    }

    public function eliminarArray($array =[]) {

        $query = "DELETE FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value){
            if($key == array_key_last($array)){
                $query .= " {$key} = '{$value}'";
            }else{
                $query .= " {$key} = '{$value}' AND ";
            }
            
        }  
        $query = $query . "  LIMIT 1"; 
        //debuguear($query);
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function buscarPaginado($empresaId, $q, $limit, $offset)
    {
        $empresaId = (int)$empresaId;
        $limit = (int)$limit;
        $offset = (int)$offset;

        $whereBusqueda = "";

        if ($q !== '') {
            $q = self::$db->real_escape_string($q);
            $whereBusqueda = "AND nombre LIKE '%{$q}%'";
        }

        $sql = "
            SELECT *
            FROM " . static::$tabla . "
            WHERE idempresa = {$empresaId}
            AND activo = 1
            {$whereBusqueda}
            ORDER BY nombre ASC
            LIMIT {$limit}
            OFFSET {$offset}
        ";

        return self::consultarSQL($sql);
    }

  
    public static function contarBusqueda($empresaId, $q)
    {
        $q = self::$db->real_escape_string($q);

        $sql = "
            SELECT COUNT(*) as total
            FROM " . static::$tabla . "
            WHERE idempresa = '{$empresaId}'
            AND activo = 1
            AND nombre LIKE '%{$q}%'
        ";

        $resultado = self::$db->query($sql);
        $fila = $resultado->fetch_assoc();

        return $fila['total'] ?? 0;
    }
    
    // public function cambiarEstado($activo) {
    //     $this->activo = $activo;
    //     return $this->guardar();
    // }


}