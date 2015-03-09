<?php
include 'objetos/restriccion.php';
include 'objetos/variable.php';
session_start();
class simplex {
    public function realizar_simplex(){

        
        $funcion_objetivo = $_SESSION['funcion_objetivo'];
        $restricciones = $_SESSION['restricciones'];
        $num_variables_artificiales = $_SESSION['num_variables_artificiales'];
        $num_variables_olgura = $_SESSION['num_variables_olgura'];
        $num_variables_introducidas = $_SESSION['num_variables'];
        $objetivo = $_SESSION['tipo_funcion'];

        $variables = array();
        
        $tabla = array();
        $variable_tabla = array();
        $base = array();
        $solucion_tabla = array();

for($i = 0; $i<$num_variables_introducidas; $i++)
    array_push($variables, $funcion_objetivo[$i]->variable);

if($num_variables_olgura>0)
for($i = 1; $i<=$num_variables_olgura; $i++)
    array_push($variables, "S".$i);

if($num_variables_artificiales>0)
for($i = 1; $i<=$num_variables_artificiales; $i++)
    array_push($variables, "A".$i);


//llenar variables de Cj
for($i = 0; $i<count($variables); $i++){
    $se_encuentra = false;
    
    for($j = 0; $j<count($funcion_objetivo); $j++){
        if($funcion_objetivo[$j]->variable == $variables[$i]){
            $se_encuentra = true;
            array_push($variable_tabla, new variable($variables[$i],$funcion_objetivo[$j]->coeficiente));
            break;
        }
    }
    if(!$se_encuentra)
        array_push($variable_tabla, new variable($variables[$i],0));
    
    array_push($tabla, $variable_tabla);
    $variable_tabla = array();
}

//buscar variable en cada restriccion y agregar a arreglo tabla
for($i = 0; $i<count($tabla); $i++){
    $variable = $tabla[$i][0]->variable;
    for($j = 0; $j<count($restricciones); $j++){
        $se_encuentra = false;
        for($k = 0; $k<count($restricciones[$j]->variables); $k++){
            if($variable == $restricciones[$j]->variables[$k]->variable){
                $se_encuentra = true;
                array_push($tabla[$i], $restricciones[$j]->variables[$k]->coeficiente);
            }
        }
        if(!$se_encuentra)
            array_push($tabla[$i],0);
    }
}

//llenar base con coeficiente y solucion
array_push($base, "Variables iniciales");
for($i = $num_variables_introducidas; $i< count($tabla); $i++){
    for($j=1; $j<count($tabla[$i]); $j++){
        if($tabla[$i][$j] == 1){
            array_push($base, $tabla[$i][0]);
        }
    }
}

array_push($solucion_tabla, "Soluciones iniciales");
for($i = 0; $i<count($restricciones); $i++){
    array_push($solucion_tabla, $restricciones[$i]->resultado);
}

//agregar variables y soluciones iniciales a la tabla
array_unshift($tabla, $solucion_tabla);
array_unshift($tabla, $base);

//llenar Zj
$Zj = array();
array_push($Zj, "Zj");
for($i = 1; $i<count($tabla); $i++){
    $sumatoria = 0;
    for($j = 1; $j<=count($restricciones); $j++){
        $sumatoria += $tabla[0][$j]->coeficiente*$tabla[$i][$j];
    }
    array_push($Zj, $sumatoria);
}

//llenar Cj menos Zj
$CjmZj = array();
array_push($CjmZj, "Cj menos Zj");
array_push($CjmZj, NULL);

for($i = 2; $i<count($tabla); $i++){
    $resta = $tabla[$i][0]->coeficiente-$Zj[$i]; 
    array_push($CjmZj, $resta);
}


//$objeto_impresion = new imprimirTabla();
//echo $objeto_impresion->imprimirTablaSolucion($tabla, 1, $Zj, $CjmZj);
//PRIMERA TABLA LLENA ^

//-----------------inician iteraciones------------------//

$tablas_solucion = array();
$CjmZj_solucion = array();
$Zj_solucion = array();
$mensaje_iteracion = array();

array_push($tablas_solucion, $tabla);
array_push($CjmZj_solucion, $CjmZj);
array_push($Zj_solucion, $Zj);
array_push($mensaje_iteracion, "Tabla de soluciones, iteración 1");


$CjmZj_mayores_cero = true;
$num_max_iteraciones = 10;
$cont = 2;


while($CjmZj_mayores_cero && ($num_max_iteraciones > $cont)){

    //buscar del arreglo Cj menos Zj cuál es el mayor
    $CjmZj_mayores_cero = false;
    $posicion = 0;
    $mayor = -100000;

    for($i = 2; $i<count($CjmZj); $i++){
        //los valores de Cj menos Zj sean mayores a 0
        if($CjmZj[$i] > $mayor && $CjmZj[$i] > 0){
            $CjmZj_mayores_cero = true;
            $mayor = $CjmZj[$i];
            $posicion = $i;
        }
    }
    
    if($posicion == 0)
        break;

    //buscar pivote
    $menor = 100000;
    $renglon_pivote = 0;
    $valores_tabla_mayores_cero = false;
    //valida que los campos de la columna sean mayores a cero para que pueda continuar
    for($i = 1; $i<=count($restricciones); $i++){
        if ($tabla[$posicion][$i] > 0){
            $valores_tabla_mayores_cero = true;
            if($menor > $tabla[1][$i]/$tabla[$posicion][$i]){
                $menor = $tabla[1][$i]/$tabla[$posicion][$i];
                $renglon_pivote = $i;
            }
        }
    }
    
    if($CjmZj_mayores_cero && $valores_tabla_mayores_cero){
        //reemplazar fila por la nueva variable dividida entre el pivote
        $valor_pivote = $tabla[$posicion][$renglon_pivote];
        $tabla[0][$renglon_pivote] = $tabla[$posicion][0];  //remplaza la variable que entra

        //actualiza el renglon pivote
        for($i = 1; $i<count($tabla); $i++){
            $tabla[$i][$renglon_pivote] = $tabla[$i][$renglon_pivote]/$valor_pivote;
        }   

        //Actualizar todos los renglones
        for($a = 1; $a<=count($restricciones); $a++){
            if($renglon_pivote != $a){// && $tabla[$posicion][$a]>0){
                $pivote_temporal = $tabla[$posicion][$a];    
        //hacer variable 0 y ajustar el renglon correspondiente
                for($j = 1; $j<count($tabla); $j++){
                    //if($pivote_temporal > 0)
                    $tabla[$j][$a] = $tabla[$j][$a] + ($tabla[$j][$renglon_pivote]*$pivote_temporal*-1);
                    //else
                    //$tabla[$j][$a] = $tabla[$j][$a] + ($tabla[$j][$renglon_pivote]*$pivote_temporal);
                }
            }
        }
        array_push($tablas_solucion, $tabla);
        array_push($CjmZj_solucion, $CjmZj);
        array_push($Zj_solucion, $Zj);
        array_push($mensaje_iteracion, "Tabla de soluciones, iteración: ".$cont);
    }
    else if(!$CjmZj_mayores_cero){
            array_push($tablas_solucion, $tabla);
            array_push($CjmZj_solucion, $CjmZj);
            array_push($Zj_solucion, $Zj);
            array_push($mensaje_iteracion, "Tabla de solución optima");
            break;
         }
         else if($CjmZj_mayores_cero && !$valores_tabla_mayores_cero) {
            array_push($tablas_solucion, $tabla);
            array_push($CjmZj_solucion, $CjmZj);
            array_push($Zj_solucion, $Zj);
            array_push($mensaje_iteracion, "Solución truncada en la iteracion: ".$cont);
            break;
    }
    
    if($cont >= $num_max_iteraciones){
        array_push($tablas_solucion, $tabla);
        array_push($CjmZj_solucion, $CjmZj);
        array_push($Zj_solucion, $Zj);
        array_push($mensaje_iteracion, "Solución ciclada en la iteración: ".$cont);
        break;
    }
        //llenar Zj
        $Zj = array();
        array_push($Zj, "Zj");
        for($i = 1; $i<count($tabla); $i++){
            $sumatoria = 0;
            for($j = 1; $j<=count($restricciones); $j++){
                $sumatoria += $tabla[0][$j]->coeficiente*$tabla[$i][$j];
            }
            array_push($Zj, $sumatoria);
        }

        //llenar Cj menos Zj
        $CjmZj = array();
        array_push($CjmZj, "Cj menos Zj");
        array_push($CjmZj, NULL);
        
        for($i = 2; $i<count($tabla); $i++){
            $resta = $tabla[$i][0]->coeficiente-$Zj[$i];  
            array_push($CjmZj, $resta);
        }
        //echo $objeto_impresion->imprimirTablaSolucion($tabla, $cont, $Zj, $CjmZj);
        
    $cont++;
}
 


$_SESSION['tablas_solucion'] = $tablas_solucion;
$_SESSION['CjmZj_solucion'] = $CjmZj_solucion;
$_SESSION['Zj_solucion'] = $Zj_solucion;
//$_SESSION['mensaje_iteracion'] = $mensaje_iteracion;
////meter arreglos en variables de sesion

}

        }

//------------------FIN ITERACION ----------------------//