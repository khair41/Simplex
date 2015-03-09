<?php

class variable {
    var $variable, $coeficiente;
    
    function __construct($variable, $coeficiente) {
        $this->variable = $variable;
        $this->coeficiente = $coeficiente;
    }

    public function getVariable() {
        return $this->variable;
    }

    public function getCoeficiente() {
        return $this->coeficiente;
    }

    public function setVariable($variable) {
        $this->variable = $variable;
    }

    public function setCoeficiente($coeficiente) {
        $this->coeficiente = $coeficiente;
    }
}
?>