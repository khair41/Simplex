<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Simplex</title>
    </head>
    <body>        
        <div class="container">
            <div id="seleccionar_metodo" class="jumbotron">
                <h1>ProgamaciónLineal</h1>
                <a onclick="display_simplex()" class="btn btn-lg btn-success" role="button">Simplex</a>
                <a onclick="display_hungaro()" class="btn btn-lg btn-success" role="button">MétodoHúngaro</a>
            </div>
        <div class="row" id="simplex" style="display: none">
            <div class="col-lg-6">
            <h3>Número de variables</h3><input style="height: 30px; width: 50px;" type="number" id="num_variables" />
            </div>
            <div class="col-lg-6">
            <h3>Número de restricciones</h3><input style="height: 30px; width: 50px;" type="number" id="num_restricciones" /><br>
            </div>
            <div class="row" style="text-align: center">
            <button class="btn btn-primary" onclick="crearCampos()" />Desplegar</button><hr>
            </div>
        
                <form action="llenarTabla.php" method="post">
                    <div id="llenar_datos_simplex" style="text-align: center; display: none" class="row">
                    <label>Objetivo de la función: </label>
                    <select name="tipo">
                        <option value="max">Maximizar</option>
                        <option value="min">Minimizar</option>
                    </select>
                    <br>. . . <br>
                    <label>Función objetivo:</label>
                    <div id="funcion_objetivo"></div>

                    <label>Restricciones:</label><br>
                    <div id="restricciones"></div>
                    
                    <div class="row marketing" style="text-align: center">
                        <input class="btn btn-primary" type="submit" id="calcular" value="Calcular"/>
                    </div>
                    </div>
                </form>
        </div>
            <!----------------DIV METODO HUNGARO---------------->
        <div class="row" id="hungaro" style="display: none">
            <div class="row" style="text-align: center">
            <h3>Número de variables</h3><input style="height: 30px; width: 50px;" type="number" id="num_variables_hungaro" />
            </div>
            <div class="row" style="text-align: center">
                <button class="btn btn-primary" onclick="crearCamposHungaro()" />Desplegar</button><hr>
            </div>
        
                <form action="hungaro.php" method="post">
                    <div id="llenar_datos_hungaro" style="text-align: center; display: none" class="row">
                    <label>Tabla: </label>
                    
                    <div id="tabla_hungaro"></div>
                    
                    <div class="row marketing" style="text-align: center">
                        <input class="btn btn-primary" type="submit" id="calcular" value="Calcular"/>
                    </div>
                    </div>
                </form>
        </div>
            
        </div>
    </body>
    <script>
        function display_simplex(){
            $("#simplex").css("display", "block");
            $("#hungaro").css("display", "none");
        }
        
        function display_hungaro(){
            $("#simplex").css("display", "none");
            $("#hungaro").css("display", "block");
            $("#llenar_datos_hungaro").css("display", "none");
        }
        
        function crearCamposHungaro(){
            
            $("#tabla_hungaro").html("");
            var num_variables_hungaro = parseInt($("#num_variables_hungaro").val());
            
            var tabla_hungaro = "";
            for(var i = 0; i<num_variables_hungaro; i++){
                for(var j = 0; j<num_variables_hungaro; j++){
                    tabla_hungaro += "<input style='height: 30px; width: 60px;' type='number' name='valor_hungaro"+i+j+"' />";
                }
                tabla_hungaro+= "<br>";
            }
            
            $("#llenar_datos_hungaro").css("display", "block");
            $("#llenar_datos_hungaro").append("<input type='hidden' name='num_variables' value='"+num_variables_hungaro+"'/>");
            $("#tabla_hungaro").append(tabla_hungaro);
            
        }
        
        function crearCampos(){
            $("#llenar_datos_simplex").css("display", "block");
            $("#llenar_datos_hungaro").css("display", "none");
            $("#funcion_objetivo").html("");
            $("#restricciones").html("");
            var num_variables = parseInt($("#num_variables").val());
            var num_restricciones = parseInt($("#num_restricciones").val());
            
            var funcion_objetivo = "";
            var restricciones = "";
            for(var i = 0; i<num_variables; i++){
                var num = i+1;
                funcion_objetivo += "<input style='height: 30px; width: 60px;' type='number' name='valor_variable"+i+"' /> X"+num;
                if(i < num_variables-1)
                    funcion_objetivo += "+ ";
                else
                    funcion_objetivo += "<br>";
            }
            for(var i = 0; i<num_restricciones; i++){
                for(var j = 0; j<num_variables; j++){
                    var num = j+1;
                    restricciones += "<input style='height: 30px; width: 60px;' type='number' name='valor_restriccion"+i+j+"' /> X"+num+" ";
                    if(j < num_variables-1)
                        restricciones += "+ ";
                    else
                        restricciones += "<select style='height: 30px; width: 60px;' name='comparador"+i+"'>"+
                            "<option value='menor_igual'><=</option>"+
                            "<option value='mayor_igual'>>=</option>"+
                            "<option value='igual'>=</option>"+
                                "</select> <input style='height: 30px; width: 60px;' type='number' name='resultado_restriccion"+i+"' /><br>";
                    
                    }
            }
            $("#funcion_objetivo").append(funcion_objetivo);
            $("#restricciones").append(restricciones);
            $("#tabla").css("display","block");
            $("#restricciones").append("<input type='hidden' name='num_restricciones' value='"+num_restricciones+"' />");
            $("#restricciones").append("<input type='hidden' name='num_variables' value='"+num_variables+"' />");
}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" rel="stylesheet">
    <link href="http://getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        
        <script>
         $(document).ready(function(){
            $(".jumbotron").css("margin-bottom", "0px");
        });
        </script>
</html>