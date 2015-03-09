<?php
include_once "objetos/variable.php";
include_once "objetos/restriccion.php";
include_once "imprimirTabla.php";

$num_restricciones = $_POST['num_restricciones'];
$num_variables = $_POST['num_variables'];

$num_variables_olgura = 1;
$num_variables_artificiales = 1;
$solucion_inicial = 0;

$restricciones = array();
$restriccion = array();

$funcion_objetivo = array();

//llenar funcion objetivo
for($i = 0; $i<$num_variables; $i++){
    $valor_variable = $_POST['valor_variable'.$i];
    $variable = new variable("X".($i+1), $valor_variable);
    
    array_push($funcion_objetivo, $variable);
}

//llenar arreglo de restricciones
for($i = 0; $i<$num_restricciones; $i++){
    for($j = 0; $j<$num_variables; $j++){
        $valor_restriccion = $_POST['valor_restriccion'.$i.$j];
        array_push($restriccion, new variable("X".($j+1), $valor_restriccion));
    }
    $comparador = $_POST['comparador'.$i];
    $resultado = $_POST['resultado_restriccion'.$i];
    array_push($restricciones, new restriccion($restriccion, $comparador, $resultado));
    $restriccion = array();
}

//Agregar variables artificiales o de olgura según sea el caso

$valor_m = calcular_m($funcion_objetivo, $_POST['tipo']);

for($i = 0; $i<$num_restricciones; $i++){
    if($restricciones[$i]->comparador == "menor_igual"){
        $var_olgura = new variable("S".$num_variables_olgura++, 1);
        $var_olgura_fo = new variable($var_olgura->variable, 0);
        array_push($funcion_objetivo, $var_olgura_fo);                 //agregar a funcion objetivo
        array_push($restricciones[$i]->variables, $var_olgura);     //agregar a su restriccion
    }
    
    if($restricciones[$i]->comparador == "mayor_igual"){
        $var_olgura = new variable("S".$num_variables_olgura++, -1);
        $var_olgura_fo = new variable($var_olgura->variable, 0);
        array_push($funcion_objetivo, $var_olgura_fo);                   //agregar a funcion objetivo
        array_push($restricciones[$i]->variables, $var_olgura);         //agregar a su restriccion
        $num_var = $num_variables_artificiales++;
        array_push($funcion_objetivo, new variable("A".$num_var, $valor_m));                 //agregar a funcion objetivo
        array_push($restricciones[$i]->variables, new variable ("A".$num_var, 1));     //agregar a su restriccion
    }
    
    if($restricciones[$i]->comparador == "igual"){
        $num_var = $num_variables_artificiales++;
        $var_artificial = new variable("A".$num_var, $valor_m);
        array_push($funcion_objetivo, $var_artificial);                 //agregar a funcion objetivo
        array_push($restricciones[$i]->variables, new variable ("A".$num_var, 1));     //agregar a su restriccion
    }
}

if($_POST['tipo'] == "min"){
    for($i = 0; $i<count($funcion_objetivo); $i++)
        $funcion_objetivo[$i]->coeficiente = $funcion_objetivo[$i]->coeficiente * -1;
}

function calcular_m ($funcion_objetivo, $objetivo){
$mayor = -100000;
        for($i = 0; $i<count($funcion_objetivo); $i++){
            if(substr($funcion_objetivo[$i]->variable,0,1) == "X")
                if($mayor < $funcion_objetivo[$i]->coeficiente)
                    $mayor = $funcion_objetivo[$i]->coeficiente;   
        }
        if($objetivo == "min")
            return $mayor*10;
        
}

session_start();
$_SESSION['tipo_funcion'] = $_POST['tipo'];
$_SESSION['funcion_objetivo'] = $funcion_objetivo;
$_SESSION['restricciones'] = $restricciones;
$_SESSION['num_variables_olgura'] = $num_variables_olgura-1;
$_SESSION['num_variables_artificiales'] = $num_variables_artificiales-1;
$_SESSION['num_variables'] = $num_variables;
$_SESSION['valor_m'] = $valor_m;

//imprimir tabla de confirmación
$objeto_impresion = new imprimirTabla();


//echo header("Location: iteracion1.php");

?>
<html>
    <head>
        <link href="http://getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet">
        <link href="http://getbootstrap.com/examples/jumbotron/jumbotron.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>
            function changeLocation(){
                window.location = "tablasResultado.php";
            }
        </script>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <h1>Función y restricciones a calcular </h1>
            </div>
        </div>
        <div class="container">
            <?php
                echo $objeto_impresion->imprimirFO($funcion_objetivo, false)."<hr>";
                echo $objeto_impresion->imprimirRestricciones($restricciones, FALSE);
            ?>
        </div>
        <div class="row" style="text-align: center">
            <button class="btn btn-primary" onclick="changeLocation()">Ver tabla</button>
        </div>
    </body>
</html>

