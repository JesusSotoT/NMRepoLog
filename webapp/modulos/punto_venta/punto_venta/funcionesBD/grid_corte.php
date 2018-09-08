<?php

if(isset($_POST["funcion"]))
{
	switch ($_POST["funcion"])
	{
		case 'grid_corte': echo  grid_corte($_POST["pagina"],$_POST["filtro"],true);
		break;	
	}
}

/////////////////////////////////////////////////////////////////////////////////////////// 
function grid_corte($pagina=1,$filtro=1,$post=false,$paginacion=15,$elimina=false)
{
	session_start();
	
	if(isset($_SESSION["abono_array"]))
		unset($_SESSION["abono_array"]);
	if(isset($_SESSION["fecha_abono_array"]))
		unset($_SESSION["fecha_abono_array"]);
	if(isset($_SESSION["id_forma_pago_array"]))
		unset($_SESSION["id_forma_pago_array"]);
	if(isset($_SESSION["forma_pago_array"]))
		unset($_SESSION["forma_pago_array"]);
	if(isset($_SESSION["referencia_array"]))
		unset($_SESSION["referencia_array"]);
	
	if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}
	
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	date_default_timezone_set('America/Mexico_City'); 
	$hoy_beta = date("Y-m-d");
	$hoy = strtotime($hoy_beta);
	
	if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}
	
	$consulta0="	SELECT idCortecaja, fechainicio, fechafin, retirocaja, abonocaja, saldoinicialcaja, saldofinalcaja FROM corte_caja WHERE ".$filtro." ORDER BY fechafin desc "; 		
	$q0=$conection->query($consulta0);
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	//$paginas=ceil($paginas);
	
	$consulta="		SELECT idCortecaja, fechainicio, fechafin, retirocaja, abonocaja, saldoinicialcaja, saldofinalcaja, montoventa FROM corte_caja WHERE ".$filtro." ORDER BY fechafin desc  
					LIMIT ".$begin.",".$paginacion; 		
	$q=$conection->query($consulta);
	$i=0;
	
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
		list($fechaInicio, $horaInicio) = explode(" ", $row["fechainicio"]);
		list($fechaFin, $horaFin) = explode(" ", $row["fechafin"]);
		
		list($anoInicio, $mesInicio, $diaInicio) = explode("-", $fechaInicio);
		list($anoFin, $mesFin, $diaFin) = explode("-", $fechaFin);
		
		$link="corte_caja.php?id=".$row['idCortecaja']."&f_ini=".$row['fechainicio']."&f_fin=".$row["fechafin"];
		
		if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
				
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["idCortecaja"].'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaInicio.'-'.$mesInicio.'-'.$anoInicio.' '.$horaInicio.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaFin.'-'.$mesFin.'-'.$anoFin.' '.$horaFin.'</a></td>';
		
		$filas.='<td>				<a class="a_registro" href="'.$link.'"><center>$'.$row["saldoinicialcaja"].'</a></center></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'"><center>$'.$row["montoventa"].'</a></center></td>';
		
		$filas.='<td>				<a class="a_registro" href="'.$link.'" style="color: #9A2EFE;"><center>$'.$row["retirocaja"].'</a></center></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'" style="color: #22CC88;"><center>$'.$row["abonocaja"].'</a></center></td>';
		
		$filas.='<td>				<a class="a_registro" href="'.$link.'"><center>$'.$row["saldofinalcaja"].'</a></center></td>';	
		
		$filas.='</tr>';
		$i++;
	}

	
	$encabezado='
	<td align="center">ID</td>	
	<td align="center">Fecha de inicio</td>
	<td align="center">Fecha de fin</td>
	<td align="center">Saldo inicial de caja</td>
	<td align="center">Monto de ventas</td>
	<td align="center" style="color: #9A2EFE;">Retiro de caja</td>
	<td align="center" style="color: #22CC88;">Abono de caja</td>
	<td align="center">Saldo final de caja</td>';
	
	
	if($i<10)
	{
		for($j=$i;$j<10;$j++)
		{	
		if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
		$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$filas.="</tr>";
		}
	}
	
	if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			
	
	
	$catalogo='
	<p><div class="tipo">
	<table><tbody><tr>
	<td><input type="button" value="<" onclick="paginacionGridCortes('.$pag_anterior.',1);"></td>
	<td><input type="button" value=">" onclick="paginacionGridCortes('.$pag_siguiente.',1);" ></td>
	<td><a href="javascript:window.print();">
	<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b>Cortes de caja</b></td></tr></tbody></table></div><br>
	<div style="width: 95%; text-align: right;"><input type="button" value="Agregar corte" onclick="agregaCorte();"></div>';
	$catalogo.='<center>
	<p>
	<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="95%">
	<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
	<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
	'.$filas.'</table></center>';		
	
	mysqli_close($conection);
	return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>	