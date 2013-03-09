<?php

class Arreglo {
    private $arreglo = array();
    private $indice = 0;
    private $size = 0;

    public function agregar($objeto) {
        array_push($this->arreglo, $objeto);
        $this->indice++;
        $this->size++;
    }

    public function quitar() {
        $this->size--;
        $this->indice--;
        return array_pop($this->arreglo);
    }

    public function actual() {
        return $this->arreglo[$this->size-1];
    }

    public function obtener($indice) {
        return ($indice>=0 && $indice < $this->size ? $this->arreglo[$indice] : NULL);
    }

    public function getArreglo() {
        return $this->arreglo;
    }

    public function getIndice() {
        return $this->indice;
    }

    public function getSize() {
        return $this->size;
    }
}
?>
