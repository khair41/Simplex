<?php
//include_once 'objetos/restriccion.php';
//include_once 'objetos/variable.php';

class imprimirTabla {
    function imprimirTablaSolucion($tabla, $iteracion, $Zj, $CjmZj){
             
        //exclusivo para llenar thead        
        $tabla_thead = "<thead><tr>";
        $tabla_thead .= "<th>".$tabla[0][0]."</th>"; 
        $tabla_thead .= "<th>".$tabla[1][0]."</th>";        
        
        for($i = 2; $i<count($tabla); $i++){
                   $tabla_thead .= 
                   "<th>".
                   $tabla[$i][0]->coeficiente.
                   $tabla[$i][0]->variable.
                   "</th>"; 
        }
        $tabla_thead .= "</tr></thead>";
        
        //para llenar tbody
        $tabla_body = "<tbody>";
        for($j = 1; $j<count($tabla[0]); $j++){
                $tabla_body .= 
                        "<tr><td>"
                        .$tabla[0][$j]->coeficiente
                        .$tabla[0][$j]->variable
                        ."</td>";
                for($i = 1; $i<count($tabla); $i++){
                   $tabla_body .= "<td>".$tabla[$i][$j]."</td>"; 
                }
                $tabla_body .= "</tr>";
        }
        $tabla_thead .= "</tbody>";
        
        //para llenar tfoot
        $tabla_foot = "<tfoot><tr>";
        for($i = 0; $i<count($tabla); $i++){
            $tabla_foot .= "<td>".$Zj[$i]."</td>";
        }
        $tabla_foot .= "</tr>";
        
        $tabla_foot .= "<tr>";
        for($i = 0; $i<count($tabla); $i++){
            if($CjmZj == null)
            $tabla_foot .= "<td> </td>";
            else
            $tabla_foot .= "<td>".$CjmZj[$i]."</td>";
        }
        $tabla_foot .= "</tr><tfoot>";
        
        return "<h2>Tabla de soluciones, iteracion ".$iteracion."</h2><br>"
                . "<table class='table table-hover'>".$tabla_thead.$tabla_body.$tabla_foot."</table>";
    }
    
    function imprimirFO($funcion_objetivo, $original){
        $html_funcion_objetivo = "<h2>Función Objetivo:</h2><br><table class='table table-striped'><tr>";
        for($i = 0; $i<count($funcion_objetivo); $i++){
            if(!$original){
                if($funcion_objetivo[$i]->coeficiente>0 && $i == 0){
                    $html_funcion_objetivo.= 
                        "<td>".
                            $funcion_objetivo[$i]->coeficiente.
                            $funcion_objetivo[$i]->variable.
                        "</td>";
                }
                elseif($funcion_objetivo[$i]->coeficiente>=0){
                     $html_funcion_objetivo.= 
                        "<td> + ".
                            $funcion_objetivo[$i]->coeficiente.
                            $funcion_objetivo[$i]->variable.
                        "</td>";
                }
                else{
                    $html_funcion_objetivo.= 
                        "<td> ".
                            $funcion_objetivo[$i]->coeficiente.
                            $funcion_objetivo[$i]->variable.
                        "</td>";
                }
            }
            else{
                $entra  = substr($funcion_objetivo[$i]->variable,0,1);
                if($entra != "S" && $entra != "A" ){                    
                    if($funcion_objetivo[$i]->coeficiente>0 && $i == 0){
                    $html_funcion_objetivo.= 
                        "<td>".
                            $funcion_objetivo[$i]->coeficiente.
                            $funcion_objetivo[$i]->variable.
                        "</td>";
                    }
                    else if($funcion_objetivo[$i]->coeficiente>0){
                         $html_funcion_objetivo.= 
                            "<td> ".
                                $funcion_objetivo[$i]->coeficiente.
                                $funcion_objetivo[$i]->variable.
                            "</td>";
                    }
                    else{
                        $html_funcion_objetivo.= 
                            "<td>".
                                $funcion_objetivo[$i]->coeficiente.
                                $funcion_objetivo[$i]->variable.
                            "</td>";
                    }
                }
            }
        }
        $html_funcion_objetivo .= "</tr></table>";
        
        return $html_funcion_objetivo;
    }
    
    function imprimirRestricciones($restricciones, $original){
        $html_restricciones = "<h2>Restricciones</h2><table class='table table-striped'>";
        for($i = 0; $i<count($restricciones); $i++){
            $html_restricciones .= "<tr>";
            for($j = 0; $j<count($restricciones[$i]->variables); $j++){
                //var_dump (count($restricciones[$i]->variables)); break;
                if(!$original){          
                    if($restricciones[$i]->variables[$j]->coeficiente > 0 && $j==0){
                $html_restricciones .= 
                        "<td>".
                        $restricciones[$i]->variables[$j]->coeficiente.
                        $restricciones[$i]->variables[$j]->variable.
                        "</td>";
                }
                
                    else if($restricciones[$i]->variables[$j]->coeficiente > 0){
                        $html_restricciones .= 
                            "<td> + ".
                            $restricciones[$i]->variables[$j]->coeficiente.
                            $restricciones[$i]->variables[$j]->variable.
                            "</td>";
                    }
                    else{
                        $html_restricciones .= 
                            "<td> ".
                            $restricciones[$i]->variables[$j]->coeficiente.
                            $restricciones[$i]->variables[$j]->variable.
                            "</td>";
                    }
                }
                else{
                    $entra  = substr($restricciones[$i]->variables[$j]->variable,0,1);
                    if($entra != "S" && $entra != "A" ){
                        if($restricciones[$i]->variables[$j]->coeficiente > 0 && $j==0){
                            $html_restricciones .= 
                            "<td>".
                            $restricciones[$i]->variables[$j]->coeficiente.
                            $restricciones[$i]->variables[$j]->variable.
                            "</td>";
                        }

                        else if($restricciones[$i]->variables[$j]->coeficiente > 0){
                            $html_restricciones .= 
                                "<td> + ".
                                $restricciones[$i]->variables[$j]->coeficiente.
                                $restricciones[$i]->variables[$j]->variable.
                                "</td>";
                        }
                        else{
                            $html_restricciones .= 
                                "<td> ".
                                $restricciones[$i]->variables[$j]->coeficiente.
                                $restricciones[$i]->variables[$j]->variable.
                                "</td>";
                        }
                    }
                }
            }
                if($original){
                    if($restricciones[$i]->comparador == "menor_igual"){
                        $html_restricciones .= "<td> &#8804 </td>";
                    }
                    
                    if($restricciones[$i]->comparador == "mayor_igual"){
                        $html_restricciones .= "<td> &#8805 </td>";
                    }
                    if($restricciones[$i]->comparador == "igual"){
                        $html_restricciones .= "<td> &#8804 </td>";
                    }
                }
                else{
                    $html_restricciones .= 
                        "<td> = </td>";
                }
                
                $html_restricciones .= 
                        "<td> ".
                        $restricciones[$i]->resultado.
                        "</td></tr>";
        }
        
        return $html_restricciones."</table>";
    }
    
    function imprimirTablaHungaro ($matriz, $tipo){
        $html_hungaro = "<table class='table table-striped' border='1'><tbody>";
        
        for($i = 0; $i<count($matriz); $i++){
            $html_hungaro.= "<tr>";
            for($j = 0; $j<count($matriz[0]); $j++){
                switch ($tipo){
                    case "num":
                        $html_hungaro .= "<td>".$matriz[$i][$j]->num."</td>";
                    break;
                    
                    case "lineas":
                        if($matriz[$i][$j]->linea)
                            $html_hungaro .= "<td>1</td>";
                        else
                            $html_hungaro .= "<td>0</td>";
                    break;
                
                    case "intersecciones":
                        if($matriz[$i][$j]->interseccion)
                            $html_hungaro .= "<td>1</td>";
                        else
                            $html_hungaro .= "<td>0</td>";
                    break;
                }
            }
            $html_hungaro.= "</tr>";
        }
        
        return $html_hungaro."<tbody></table>";
    }
}