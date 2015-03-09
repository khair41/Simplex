<?php
class num_hungaro {
    var $num, $linea, $interseccion;
    
    function __construct($num, $linea, $interseccion) {
        $this->num = $num;
        $this->linea = $linea;
        $this->interseccion = $interseccion;
    }

    public function getLinea() {
        return $this->linea;
    }

    public function getInterseccion() {
        return $this->interseccion;
    }

    public function setNum($num) {
        $this->num = $num;
    }

    public function getNum() {
        return $this->num;
    }
    
    public function setLinea($linea) {
        $this->linea = $linea;
    }

    public function setInterseccion($interseccion) {
        $this->interseccion = $interseccion;
    }


}
