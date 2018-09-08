<?php

    session_start();
    $idorg = $_SESSION["accelog_idorganizacion"];
    $sql = $_SESSION["sequel"];
    $idestiloomision = $_SESSION["iestilo"];
    $descripcion = $_SESSION["desc"];

    $filtros_cuantos = $_SESSION["filtros_cuantos"];
    $filtros_etiquetas = $_SESSION["filtros_etiquetas"];
    $filtros_posicion_inicio = $_SESSION["filtros_posicion_inicio"];
    $filtros_posicion_fin = $_SESSION["filtros_posicion_fin"];

    $filtros_valores = array();
    $filtros_valores_hum = array();

		include("parametros.php");


		//CSRF
		$reset_vars = false;
		include("../catalog/clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}


    for($i = 1; $i<=$filtros_cuantos; $i++){
        
        $etiqueta = $filtros_etiquetas[$i];

        $pos_barra=strrpos($etiqueta,"#");
        if(is_numeric($pos_barra)){
            $anual = $conexion->escapalog($_REQUEST["f".$i."_3"]);
            $mes = $conexion->escapalog($_REQUEST["f".$i."_1"]);
            if(strlen($mes)<2){
                $mes = '0' . $mes;
            }
            $dia = $conexion->escapalog($_REQUEST["f".$i."_2"]);
            if(strlen($dia)<2){
                $dia = '0' . $dia;
            }
            $fecha = $anual."-".$mes."-".$dia;
            $filtros_valores[$i] = $fecha;
            $sql = str_replace("[".$etiqueta."]", $fecha,$sql);
            
            $filtros_valores_hum[$i] = $dia."/".$mes."/".$anual;

        } else {

            $valor = $conexion->escapalog($_REQUEST["txt".$i]);
            $filtros_valores[$i] = $valor;
            $sql = str_replace("[".$etiqueta."]", $valor,$sql);

            $pos_barra=strrpos($etiqueta,"@");
            if(is_numeric($pos_barra)){
                $filtros_valores_hum[$i] = $conexion->escapalog($_REQUEST["dep".$i]);
            } else {                
                $filtros_valores_hum[$i] = $valor;
            }
                        
        }
    }

    $_SESSION["sequel"] = $sql;

    //PARAMETROS ALMACENADOS EN LA SESSION POR SI SE UTILIZAN EN ALGUN PROCESO
    $_SESSION["repolog_valores"] = $filtros_valores;
    $_SESSION["repolog_valores_hum"] = $filtros_valores_hum;
    $_SESSION["repolog_cuantos"] = $filtros_cuantos;
    $_SESSION["repolog_filtros"] = $filtros_etiquetas;
    ///////

		$conexion->cerrar();

    header("Location: reporte.php");
    //echo $sql;

?>
