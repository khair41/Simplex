<html>
    <head>
        <link href="http://getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet">
        <link href="http://getbootstrap.com/examples/jumbotron/jumbotron.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <h1>Solución entera</h1>
            </div>
        </div>
        <div class="container">
            <?php

include_once 'objetos/modelo.php';
include_once 'imprimirTabla.php';
include_once 'simplex.php';

//recibe última solución del método simplex
//session_start();
$funcion_objetivo = $_SESSION['funcion_objetivo'];
$restricciones = $_SESSION['restricciones'];
$num_variables_artificiales = $_SESSION['num_variables_artificiales'];
$num_variables_olgura = $_SESSION['num_variables_olgura'];
$num_variables_introducidas = $_SESSION['num_variables'];
$objetivo = $_SESSION['tipo_funcion'];


 $tablas_solucion = $_SESSION['tablas_solucion'];
 $CjmZj_solucion = $_SESSION['CjmZj_solucion'];
 $Zj_solucion = $_SESSION['Zj_solucion'];
 
$valor_m = $_SESSION['valor_m'];

//crear la pila
$pila = array();

//crear arreglo de soluciones
$soluciones_enteras = array();
    
    //resolvemos modelo para inicializar la pila 
$ultima_solucion = array_pop($tablas_solucion);

    for($i = 1; $i<count($ultima_solucion[0]); $i++){
    if (substr($ultima_solucion[0][$i]->variable,0,1) == "X" &&
    floor($ultima_solucion[1][$i]) != $ultima_solucion[1][$i]){
                
        //creamos variables para meter en restricciones
        $variabes_restriccion_menor_igual = array();
        $variabes_restriccion_mayor_igual = array();
        
        //menor igual
            $var_sol = new variable($ultima_solucion[0][$i]->variable,1);
            $var_olgura_menor = new variable("S".contar_var_olgura($funcion_objetivo), 1);

            array_push($variabes_restriccion_menor_igual, $var_sol);
            array_push($variabes_restriccion_menor_igual, $var_olgura_menor);
        
        //mayor igual
            $var_olgura_mayor = new variable("S".  contar_var_artificiales($funcion_objetivo), -1);
            $var_artificial = new variable ("A".contar_var_artificiales($funcion_objetivo), 1);

            array_push($variabes_restriccion_mayor_igual, $var_olgura_mayor);
            array_push($variabes_restriccion_mayor_igual, $var_artificial);
            array_push($variabes_restriccion_mayor_igual, $var_sol);
        
        //agregamos variables a funcion objetivo para restriccion menor igual
        array_push($funcion_objetivo, new variable($var_olgura_menor->variable, 0));
        //agregamos restriccion para menor igual
        array_push($restricciones, new restriccion($variabes_restriccion_menor_igual, "menor_igual", floor($ultima_solucion[1][$i])));
        //agregamos a la pila el modelo menor igual
        array_push($pila, new modelo($funcion_objetivo, $restricciones, contar_var_artificiales($funcion_objetivo)-1, contar_var_olgura($funcion_objetivo)-1));
        
        //agregamos variables a funcion objetivo para restriccion mayor igual
        $funcion_objetivo[count($funcion_objetivo)-1] = new variable($var_olgura_mayor->variable, 0);
        array_push($funcion_objetivo, new variable("A".  contar_var_artificiales($funcion_objetivo), $valor_m));
        
        //agregamos restriccion para menor igual
        $restricciones[count($restricciones)-1] =  new restriccion($variabes_restriccion_mayor_igual, "mayor_igual", floor($ultima_solucion[1][$i])+1);
        //agregamos a la pila el modelo menor igual
        array_push($pila, new modelo($funcion_objetivo, $restricciones, contar_var_artificiales($funcion_objetivo)-1, contar_var_olgura($funcion_objetivo)-1));
        }
    }

while(count($pila) != 0){

    //sacamos ultimo modelo
    $ultimo_modelo = array_pop($pila);
    
    $_SESSION['funcion_objetivo'] = $ultimo_modelo->funcion_objetivo;
    $_SESSION['restricciones'] = $ultimo_modelo->restricciones;
    $_SESSION['num_variables_artificiales'] = $ultimo_modelo->num_variables_artificiales;
    $_SESSION['num_variables_olgura'] = $ultimo_modelo->num_variables_olgura;
    
    //resolvemos ultimo modelo
    $realizar_simplex = new simplex();
    $realizar_simplex->realizar_simplex();
    
    $tablas_solucion = $_SESSION['tablas_solucion'];   
    $Zj_solucion = $_SESSION['Zj_solucion'];    
    
    //resolvemos modelo para inicializar la pila 
    
$ultima_solucion = array_pop($tablas_solucion);
$quitar_solucion = count($pila)-1;
$seguir_iterando = false;
    for($i = 1; $i<count($ultima_solucion[0]); $i++){
    if (substr($ultima_solucion[0][$i]->variable,0,1) == "X" &&
    floor($ultima_solucion[1][$i]) != $ultima_solucion[1][$i]){
        $seguir_iterando = true;
        //creamos variables para meter en restricciones
        $variabes_restriccion_menor_igual = array();
        $variabes_restriccion_mayor_igual = array();
        
        //menor igual
            $var_sol = new variable($ultima_solucion[0][$i]->variable,1);
            $var_olgura_menor = new variable("S".contar_var_olgura($funcion_objetivo), 1);

            array_push($variabes_restriccion_menor_igual, $var_sol);
            array_push($variabes_restriccion_menor_igual, $var_olgura_menor);
        
        //mayor igual
            $var_olgura_mayor = new variable("S".contar_var_olgura($funcion_objetivo), -1);
            $var_artificial = new variable ("A".contar_var_artificiales($funcion_objetivo), 1);

            array_push($variabes_restriccion_mayor_igual, $var_olgura_mayor);
            array_push($variabes_restriccion_mayor_igual, $var_artificial);
            array_push($variabes_restriccion_mayor_igual, $var_sol);
        
        //agregamos variables a funcion objetivo para restriccion menor igual
        array_push($funcion_objetivo, new variable($var_olgura_menor->variable, 0));
        //agregamos restriccion para menor igual
        array_push($restricciones, new restriccion($variabes_restriccion_menor_igual, "menor_igual", floor($ultima_solucion[1][$i])));
        //agregamos a la pila el modelo menor igual
        array_push($pila, new modelo($funcion_objetivo, $restricciones,  contar_var_artificiales($funcion_objetivo)-1, contar_var_olgura($funcion_objetivo)-1));
               
        
        //agregamos variables a funcion objetivo para restriccion mayor igual
        $funcion_objetivo[count($funcion_objetivo)-1] = new variable($var_olgura_mayor->variable, 0);
        array_push($funcion_objetivo, new variable("A".  contar_var_artificiales($funcion_objetivo), $valor_m));
        //agregamos restriccion para menor igual
        array_push($restricciones, new restriccion($variabes_restriccion_mayor_igual, "mayor_igual", floor($ultima_solucion[1][$i])+1));
        //agregamos a la pila el modelo menor igual
        array_push($pila, new modelo($funcion_objetivo, $restricciones, contar_var_artificiales($funcion_objetivo)-1, contar_var_olgura($funcion_objetivo)-1));
       
        }
    }
    
    if($seguir_iterando && $quitar_solucion > 0)
        unset($pila[$quitar_solucion]);
    else if(!$seguir_iterando){
        array_push($soluciones_enteras, array_pop($pila));
        unset($pila[$quitar_solucion]);
    }
}

imprimir_soluciones_enteras($soluciones_enteras);

function contar_var_artificiales($funcion_objetivo){
    $cont = 1;
    for($i = 0; $i<count($funcion_objetivo); $i++)
        if (substr($funcion_objetivo[$i]->variable,0,1) == "A"){
            $cont++;
    }
    return $cont;
}

function contar_var_olgura($funcion_objetivo){
    $cont = 1;
    for($i = 0; $i<count($funcion_objetivo); $i++)
        if (substr($funcion_objetivo[$i]->variable,0,1) == "S"){
            $cont++;
    }
    return $cont;
}

function imprimir_soluciones_enteras($soluciones_enteras){
    
    $objeto_impresion = new imprimirTabla();
    $simplex = new simplex();
    
    for($i = 0; $i<count($soluciones_enteras); $i++){
        //sacamos las variables del arreglo de las soluciones enteras
        $_SESSION['funcion_objetivo'] = $soluciones_enteras[$i]->funcion_objetivo;
        $_SESSION['restricciones'] = $soluciones_enteras[$i]->restricciones;
        $_SESSION['num_variables_artificiales'] = $soluciones_enteras[$i]->num_variables_artificiales;
        $_SESSION['num_variables_olgura'] = $soluciones_enteras[$i]->num_variables_olgura;

        //realizamos el simplex
        $simplex->realizar_simplex();
        
        //obtenemos los arreglos que arroja el simplex
        $tablas_solucion = $_SESSION['tablas_solucion'];
        $CjmZj_solucion = $_SESSION['CjmZj_solucion'];
        $Zj_solucion = $_SESSION['Zj_solucion'];
       
        //imprimimos
        echo $objeto_impresion->imprimirTablaSolucion(array_pop($tablas_solucion), "(soluciones enteras): ".($i+1), array_pop($Zj_solucion), array_pop($CjmZj_solucion));
    }
}
?>

            </div>
        <div class="row" style="text-align: center">
             <a style='font-size: 24' href='salir.php'>Regresar</a>
        </div>
    </body>
</html>
