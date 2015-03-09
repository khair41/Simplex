<?php

class modelo {
    var $funcion_objetivo, $restricciones, $num_variables_artificiales, $num_variables_olgura;
    
    function __construct($funcion_objetivo, $restricciones, $num_variables_artificiales, $num_variables_olgura) {
        $this->funcion_objetivo = $funcion_objetivo;
        $this->restricciones = $restricciones;
        $this->num_variables_artificiales = $num_variables_artificiales;
        $this->num_variables_olgura = $num_variables_olgura;
    }

    
    public function getFuncion_objetivo() {
        return $this->funcion_objetivo;
    }

    public function getRestricciones() {
        return $this->restricciones;
    }

    public function getNum_variables_artificiales() {
        return $this->num_variables_artificiales;
    }

    public function getNum_variables_olgura() {
        return $this->num_variables_olgura;
    }

    public function setFuncion_objetivo($funcion_objetivo) {
        $this->funcion_objetivo = $funcion_objetivo;
    }

    public function setRestricciones($restricciones) {
        $this->restricciones = $restricciones;
    }

    public function setNum_variables_artificiales($num_variables_artificiales) {
        $this->num_variables_artificiales = $num_variables_artificiales;
    }

    public function setNum_variables_olgura($num_variables_olgura) {
        $this->num_variables_olgura = $num_variables_olgura;
    }


}
