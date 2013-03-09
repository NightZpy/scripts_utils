<?php

class ArrayControl {

    private $arreglo    = NULL;
    private $size       = NULL;
    private $indice     = NULL;

    function __construct($arreglo) {
        $this->arreglo  = $arreglo;
        $this->size     = $this->sizeArreglo();
        $this->irInicio();
    }
    
    public function irInicio()
    {
        $this->indice = 0;
        return $this->arreglo[$this->indice];
    }

    public function irFin()
    {
        $this->indice = $this->size-1;
        return $this->arreglo[$this->indice];
    }

    public function irSiguiente() {
        $this->indice++;
        if($this->indice >= $this->size)
            return NULL;

        return $this->arreglo[$this->indice];
    }

    public function irAnterior() {
        $this->indice--;
        if($this->indice < 0)
                return NULL;

        $this->arreglo[$this->indice];
    }

    public function actual(){
        return $this->arreglo[$this->indice];
    }

    public function irPosicion($posicion){
        if ($posicion >= 0 && $posicion < $this->size)
                $this->indice = $posicion;
        return ($posicion >= 0 && $posicion < $this->size ? $this->arreglo[$posicion] : NULL);
    }

    public function hayMas() {
        return ($this->indice>= 0 && $this->indice < $this->size);
    }

    public function salir()
    {
        $this->indice = $this->size;
    }

    private function  sizeArreglo()
    {
        return count($this->arreglo);
    }

    public function  borrar(){
        $this->arreglo = NULL;
        $this->indice  = NULL;
        $this->size    = NULL;
    }

    public function borrarActual() {
        unset ($this->arreglo[$this->indice]);
        $this->indice--;
        $this->size--;
    }

    public function imprimir() {
        echo "<br>";
        print_r($this->arreglo);
    }

    public function esPrimero()
    {
        return ($this->indice == 0);
    }

    public function esUltimo()
    {
        return ($this->indice == $this->size - 1);
    }

    public function esFinal() {
        return ($this->indice >= $this->size);
    }

    public function getSize() {
        return $this->size;
    }

    public function getArreglo() {
        return $this->arreglo;
    }

    public function getIndice() {
        return $this->indice;
    }

    

}
?>
