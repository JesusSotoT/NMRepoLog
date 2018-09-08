<?php

	$idcampo = $_POST['ic'];
	$campovalor = $_POST['cv'];
	$campodesc = $_POST['cd'];
	$dependenciatabla = $_POST['dt'];
	$sqlw = $_POST['sw'];
		//echo "recibí:".$_SERVER["REQUEST_URI"]."   sqlw:".$sqlw;
	$sqlw = str_replace("\\","",$sqlw);
		//echo " -- id:".$idcampo." sw:".$sqlw;
	
	$seleccionado_m = $_POST['sm'];
	$deshabilitado = $_POST['de'];
	$formato = $_POST['fo'];



	//echo "cargando... ".$idcampo." ".$campovalor." ".$campodesc." ".$dependenciatabla." ".$sqlw;
	
	include("conexionbd.php");	
	
	$objeto="
			<div id='i".$idcampo."_div'>
			<select id='i".$idcampo."' name='i".$idcampo."' class=' nminputselect ' onchange='campo_onchange(this,true)' ".$deshabilitado." >";		     

		$campodesc=str_replace(",",",' ',",$campodesc);	
		$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	
		$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." where ".$sqlw." order by catalog_campodesc";
		$campodesc="catalog_campodesc";
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
									
			$datoenelcombo = $regsimple{$campodesc}; 
			if(($formato=="$")||($formato=="$.00")){
				$datoenelcombo = "$ ".number_format($datoenelcombo);
			}
			
			if($formato=="0.00"){
				$datoenelcombo = number_format($datoenelcombo);
			}
			
			
			$objeto.="<option value='".$regsimple{$campovalor}."' ".$selecciona_m." >".$datoenelcombo."</option>";				
		}
		$conexion->cerrar_consulta($rsdependenciasimple);
	
	$objeto.="</select></div>";


	echo $objeto;
	
	$conexion->cerrar();
	
	
	
?>
