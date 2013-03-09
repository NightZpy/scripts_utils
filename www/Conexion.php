<?php
class Conexion
{
    private $conexion;
    private $usuario;
    private $password;
    private $bd;
    private $host;
    private $ultimoError;
    private $bdSeleccionada;

    public function __construct($usuario = 'root', $password = '', $bd = 'test', $host = 'localhost') {
        $this->usuario  = $usuario;
        $this->password = $password;
        $this->bd       = $bd;
        $this->host     = $host;

        $this->conexion = mysql_connect($this->host, $this->usuario, $this->password);
        $this->ultimoError = mysql_error($this->conexion);
        
        if(!$this->conexion)
                die ("Error conectando con el servidor especificado: ".$this->ultimoError);

        $this->bdSeleccionada = mysql_select_db($this->bd, $this->conexion);
        mysql_query("SET NAMES UTF8", $this->conexion);

        $this->ultimoError = mysql_error($this->conexion);
        if(!$this->conexion)
                die ("Error Base de Datos especificada: ".$this->ultimoError);
       
    }

    public function conectarA($usuario, $password, $bd, $host) {

        if($this->conexion != NULL)
        {
            $this->cerrarConexion();
        }

        $this->usuario  = $usuario;
        $this->password    = $password;
        $this->bd       = $bd;
        $this->host     = $host;

        $this->conexion = mysql_connect($this->host, $this->usuario, $this->password);
        $this->ultimoError = mysql_error($this->conexion);
        if(!$this->conexion)
                die ("Error conectando con el servidor especificado: ".$this->ultimoError);

        $this->bdSeleccionada = mysql_select_db($this->bd, $this->conexion);
        $this->ultimoError = mysql_error($this->conexion);
        if(!$this->conexion)
                die ("Error Base de Datos especificada: ".$this->ultimoError);
        
            
    }

    public function seleccionarBD($bd) {
        $this->bd = $bd;
        $this->bdSeleccionada = mysql_select_db($this->bd, $this->conexion);

        if(!$this->conexion)
                die ("Error Base de Datos especificada: ".$this->ultimoError());
    }

    public function ultimoError() {
        $this->ultimoError = mysql_error($this->conexion);
        return $this->ultimoError;
    }

    public function ultimoID() {
        return mysql_insert_id($this->conexion);
    }

    public function cerrarConexion(){
        $this->usuario  = NULL;
        $this->password    = NULL;
        $this->bd       = NULL;
        $this->host     = NULL;

        return mysql_close($this->conexion);
    }

    public function seleccionarDatos($strQuery) {
        $result = mysql_query($strQuery);
        if(!$result)
            return NULL;
        
        $cFilas = mysql_num_rows($result);  
        
        if($cFilas > 0)
        {
            $i=0;
            $array = Array($cFilas);

            while($tmpArray = mysql_fetch_array($result))
            {
                $array[$i] = $tmpArray;
                $i++;
            }

            return $array;
        }
        return NULL;     
    }

    public function actualizarDatos($strQuery) {
        if(!($result = mysql_query($strQuery, $this->conexion)))
                return false;
        return true;
    }

    public function numRegistros($tabla){
        $strQuery = "SELECT id FROM ".$tabla.";";
        if(!($result = mysql_query($strQuery)))
            return NULL;
        return mysql_num_rows($result);
    }

    public function agregarRegistro($strQuery) {
        if(!($result = mysql_query($strQuery, $this->conexion)))
                return false;
        return true;
    }

    public function agregarRegistros($arrayStrQuery) {
        $cQuerys = count($arrayStrQuery);
        for ($i = 0; $i < $cQuerys; $i++) {
            if(!($result = mysql_query($arrayStrQuery[$i], $this->conexion)))
                return false;
        }
        return true;

    }

    public function getTablesName()
    {
        $strQuery = 'SHOW TABLES;';
        $result = mysql_query($strQuery);
        if(!$result)
            return NULL;
        
        $tablas = array();
        
        while($rs = mysql_fetch_array($result))    
            array_push($tablas, $rs[0]);
        
        return $tablas;        
    }

    public function getColumnsForTable($tableName = '')
    {
        $strQuery = "SHOW COLUMNS FROM $tableName;";
        return $this->seleccionarDatos($strQuery);
    }

    //Getters and Setters
    public function getConexion() {
        return $this->conexion;
    }

    public function setConexion($conexion) {
        $this->conexion = $conexion;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getBd() {
        return $this->bd;
    }

    public function setBd($bd) {
        $this->bd = $bd;
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getUltimoError() {
        return $this->ultimoError;
    }

    public function setUltimoError($ultimoError) {
        $this->ultimoError = $ultimoError;
    }

    public function getBdSeleccionada() {
        return $this->bdSeleccionada;
    }

    public function setBdSeleccionada($bdSeleccionada) {
        $this->bdSeleccionada = $bdSeleccionada;
    }

}
?>
