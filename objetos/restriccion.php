<?php

class restriccion {
    var $variables, $comparador, $resultado;
    
    function __construct($variables, $comparador, $resultado) {
        $this->variables = $variables;
        $this->comparador = $comparador;
        $this->resultado = $resultado;
    }

    public function getVariables() {
        return $this->variables;
    }

    public function getComparador() {
        return $this->comparador;
    }

    public function getResultado() {
        return $this->resultado;
    }

    public function setVariables($variables) {
        $this->variables = $variables;
    }

    public function setComparador($comparador) {
        $this->comparador = $comparador;
    }

    public function setResultado($resultado) {
        $this->resultado = $resultado;
    }


}
