<?php
//include("../../../netwarelog/catalog/conexionbd.php");


if(isset($_POST["funcion"]))
{
	switch ($_POST["funcion"])
	{
		case 'gridcxp': echo  gridCxp($_POST["pagina"],$_POST["filtro"],true);
		break;	
	}
}


/////////////////////////////////////////////////////////////////////////////////////////// 
function gridCxp($pagina=1,$filtro=1,$post=false,$paginacion=15,$elimina=false)
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
	
	$querybeta="     SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus FROM cxp c ORDER BY c.idcxp desc "; 		
	$r = $conection->query($querybeta);
	
	while($row = $r->fetch_array(MYSQLI_ASSOC))
	{
		$nvosaldoabonado = $row["monto"] - $row["saldoabonado"];
		$conection->query("UPDATE cxp c SET c.saldoactual = ".$nvosaldoabonado." WHERE c.idcxp = ".$row["idcxp"]);
		
		if($row["saldoactual"] <= 0 && $row["estatus"] != 1)
		{
			$conection->query("UPDATE cxp c SET c.estatus = 1 WHERE c.idcxp = ".$row["idcxp"]);
		}
	}
	
	$consulta0="	SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus FROM cxp c WHERE ".$filtro." ORDER BY c.idcxp desc "; 		
	$q0=$conection->query($consulta0);
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	//$paginas=ceil($paginas);
	
	$consulta="		SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus FROM cxp c WHERE ".$filtro." ORDER BY c.idcxp desc   LIMIT ".$begin.",".$paginacion; 		
	$q=$conection->query($consulta);
	$i=0;
	
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
		list($anoCargo, $mesCargo, $diaCargo) = explode("-", $row["fechacargo"]);
		list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row["fechavencimiento"]);
		
		$link="cuenta.php?id=".$row['idcxp']."&fven=".$row['fechavencimiento'];
		
		if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
				
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["idcxp"].'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaCargo.'-'.$mesCargo.'-'.$anoCargo.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento.'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.utf8_decode($row["concepto"]).'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">$'.$row["monto"].'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">$'.$row["saldoabonado"].'</a></td>';	
		if($row["estatus"] != 1)
		{
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #FF000C;">'.$row['saldoactual'].'</div></a></td>';
		}
		else
		{
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #01a05f;">'.utf8_decode("Saldada").'</div></a></td>';
		}
		$filas.='</tr>';
		$i++;
	}

	
	$encabezado='
	<td align="center">ID</td>
	<td align="center">Fecha de cargo</td>
	<td align="center">Fecha de vencimiento</td>
	<td align="center">Concepto</td>
	<td align="center">Monto</td>
	<td align="center">Saldo abonado</td>
	<td align="center">Saldo actual</td>';
	
	
	if($i<10)
	{
		for($j=$i;$j<10;$j++)
		{	
		if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
		$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$filas.="</tr>";
		}
	}
	
	if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			
	
	
	$catalogo='
	<p><div class="tipo">
	<table><tbody><tr>
	<td><input type="button" value="<" onclick="paginacionGridCxp('.$pag_anterior.',1);"></td>
	<td><input type="button" value=">" onclick="paginacionGridCxp('.$pag_siguiente.',1);" ></td>
	<td><a href="javascript:window.print();">
	<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b>Cuentas por pagar</b></td></tr></tbody></table></div><br>';
						
	$catalogo.='<center><div style="width: 95%; text-align: right;"><input type="button" value="Agregar cuenta" onclick="cargaCxp();"></div>
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