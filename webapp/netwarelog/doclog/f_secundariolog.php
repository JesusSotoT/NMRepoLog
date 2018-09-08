<?php

	//include "conexionbd.php";
	include("../catalog/conexionbd.php");

	$deshabilitado = $_GET["d"];
	$idobjeto = $_GET["o"];
	$sql_encriptado = $_GET["q"];
	$campovalor = $_GET["v"];
	$campodesc = $_GET["c"];
	$sql = base64_decode(str_rot13($sql_encriptado));

	$objeto="<select id='".$idobjeto."' name='".$idobjeto."' class='seleccion' 
					onchange='campo_onchange(this,true)' ".$deshabilitado."  >";
					
			//echo $sql;
			$rsdependenciasimple = $conexion->consultar($sql);
                                       
            $inicio_parciallog = 0;
			while($regsimple=$conexion->siguiente($rsdependenciasimple)){
				$objeto.="<option value='".$regsimple{$campovalor}."' >".$regsimple{$campodesc}."</option>";
			}
			$conexion->cerrar_consulta($rsdependenciasimple);
			
	$objeto.="</select>";	

	echo $objeto;

	$conexion->cerrar();
?>