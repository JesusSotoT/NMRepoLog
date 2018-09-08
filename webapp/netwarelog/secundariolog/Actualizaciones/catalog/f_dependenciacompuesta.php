<?php

	$idcampo = $_GET['ic'];
	$campovalor = $_GET['cv'];
	$campodesc = $_GET['cd'];
	$dependenciatabla = $_GET['dt'];
	$sqlw = $_GET['sw'];
		//echo "recibí:".$_SERVER["REQUEST_URI"]."   sqlw:".$sqlw;
	$sqlw = str_replace("\\","",$sqlw);
		//echo " -- id:".$idcampo." sw:".$sqlw;
	
	$seleccionado_m = $_GET['sm'];
	$deshabilitado = $_GET['de'];



	//echo "cargando... ".$idcampo." ".$campovalor." ".$campodesc." ".$dependenciatabla." ".$sqlw;
	
	include("conexionbd.php");	
	
	$objeto="<select id='i".$idcampo."' name='i".$idcampo."' class='seleccion' onchange='campo_onchange(this,true)' ".$deshabilitado." >";		     

		$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." where ".$sqlw." order by ".$campodesc;
		//echo $sql;
		$rsdependenciasimple = $conexion->consultar($sql);
		while($regsimple=$conexion->siguiente($rsdependenciasimple)){
			
			$selecciona_m="";
			if($seleccionado_m!=""){
				if($regsimple{$campovalor}==$seleccionado_m){
					$selecciona_m="selected";
				} else {
					$selecciona_m="";
				}
			}
			
			$objeto.="<option value='".$regsimple{$campovalor}."' ".$selecciona_m." >".$regsimple{$campodesc}."</option>";				
		}
		$conexion->cerrar_consulta($rsdependenciasimple);
	
	$objeto.="</select>";


	echo $objeto;
	
	$conexion->cerrar();
	
	
	
?>