<?php
//include("../../../netwarelog/catalog/conexionbd.php");

//ini_set('display_errors', 1);
if(isset($_POST["funcion"]))
{
	switch ($_POST["funcion"])
	{
		case 'gridcxc': echo  gridCxc($_POST["pagina"],$_POST["filtro"],true);
		break;	

		case 'clientesSelect': echo clientesSelect();
		break;
	}
}
function clientesSelect(){
	if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}

	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	date_default_timezone_set('America/Mexico_City');
	$conection->set_charset('utf8');

	$query = "SELECT * from comun_cliente;";
	$r = $conection->query($query);

	$selectHtml = '<select id="clienteselect" class="form-control">';
	$selectHtml .='<option value="A">--Seleciona Cliente--</option>';
	 	while($row = $r->fetch_array(MYSQLI_ASSOC))
		{
			$selectHtml.='<option value='.$row['id'].'>'.$row['nombre'].'</option>';

		}
	$selectHtml	.='</select>';
	return $selectHtml;
}


/////////////////////////////////////////////////////////////////////////////////////////// 
function gridCxc($pagina=1,$filtro=1,$post=false,$paginacion=30,$elimina=false)
{
	if (!isset($_SESSION)) {
		@session_start();
	}
	
	
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
	$conection->set_charset('utf8');
	$hoy_beta = date("Y-m-d");
	$hoy = strtotime($hoy_beta);
	$filtro = stripslashes($filtro);
	
	if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}
	
	$querybeta="	SELECT c.idcxc, c.fechacargo, c.fechavencimiento, c.idVenta, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idCliente FROM cxc c ORDER BY c.idcxc desc "; 		
	$r = $conection->query($querybeta);
	
	while($row = $r->fetch_array(MYSQLI_ASSOC))
	{
		$nvosaldoabonado = $row["monto"] - $row["saldoabonado"];
		$conection->query("UPDATE cxc c SET c.saldoactual = ".$nvosaldoabonado." WHERE c.idcxc = ".$row["idcxc"]);
		
		if($row["saldoactual"] <= 0 && $row["estatus"] != 1)
		{
			$conection->query("UPDATE cxc c SET c.estatus = 1 WHERE c.idcxc = ".$row["idcxc"]);
		}
	}

/////////////////////////////////////////////////
	$filtro = stripslashes($filtro);
	if($filtro==1){
		$querydSaldo="SELECT sum(saldoactual) as saldoactual from cxc where estatus=0"; 
	}else{
		$querydSaldo="SELECT sum(c.saldoactual) as saldoactual from cxc c where ".$filtro." and c.estatus=0"; 
	}
			//echo '('.$querydSaldo.')';
	$x = $conection->query($querydSaldo);
	
	while($rowy = $x->fetch_array(MYSQLI_ASSOC))
	{
		$saldodeuda = $rowy["saldoactual"];
		

	} //echo 'X'.$saldodeuda.'X';
/////////////////////////////////////////////////


	
	$consulta0="	SELECT c.idcxc, c.fechacargo, c.fechavencimiento, c.idVenta, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idCliente, 
					cl.nombre, cl.nombretienda FROM cxc c 
					INNER JOIN comun_cliente cl ON c.idCliente = cl.id 	
					WHERE ".$filtro." ORDER BY c.idcxc desc "; 	
					//echo '['.$consulta0.']';	
	$q0=$conection->query($consulta0);
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	//$paginas=ceil($paginas);
	
	$consulta="		SELECT c.idcxc, c.fechacargo, c.fechavencimiento, c.idVenta, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idCliente,  
					cl.nombre, cl.nombretienda, rf.folio
 FROM cxc c 
					INNER JOIN comun_cliente cl ON c.idCliente = cl.id  
					left join pvt_respuestaFacturacion rf on c.idVenta=rf.idSale
					WHERE ".$filtro." ORDER BY c.idcxc desc   LIMIT ".$begin.",".$paginacion; 	
					//echo '['.$consulta.']';	
	$q=$conection->query($consulta);
	$i=0;
	$filas = "";
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
		list($anoCargo, $mesCargo, $diaCargo) = explode("-", $row["fechacargo"]);
		list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row["fechavencimiento"]);
		
		$link="cuenta.php?id=".$row['idcxc']."&fven=".$row['fechavencimiento'];
		
		if($i%2==0){$filas.='<tr class="nmcatalogbusquedacont_1">';}else{$filas.='<tr class="nmcatalogbusquedacont_2">';}
				
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["idcxc"].'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaCargo.'-'.$mesCargo.'-'.$anoCargo.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento.'</a></td>';
		
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["nombre"]." ( ".$row["nombretienda"]." )".'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["idVenta"].'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["folio"].'</a></td>';
		
		$filas.='<td>				<a class="a_registro" href="'.$link.'">$'.$row["monto"].'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">$'.$row["saldoabonado"].'</a></td>';	
		if($row["estatus"] != 1)
		{
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #FF000C;">'.$row['saldoactual'].'</div></a></td>';
		}
		else
		{
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #01a05f;">'."Saldada".'</div></a></td>';
		}
		$filas.='</tr>';
		$i++;
	}

	
	$encabezado='
	<th class="nmcatalogbusquedatit" align="center">ID</th>	
	<th class="nmcatalogbusquedatit" align="center">Fecha de cargo</th>
	<th class="nmcatalogbusquedatit" align="center">Fecha de vencimiento</th>
	<th class="nmcatalogbusquedatit" align="center">Cliente</th>
	<th class="nmcatalogbusquedatit" align="center">Folio de venta</th>
	<th class="nmcatalogbusquedatit" align="center">Factura</th>
	<th class="nmcatalogbusquedatit" align="center">Monto</th>
	<th class="nmcatalogbusquedatit" align="center">Saldo abonado</th>
	<th class="nmcatalogbusquedatit" align="center">Saldo actual</th>';
	
	
	if($i<10)
	{
		for($j=$i;$j<10;$j++)
		{	
		if($j%2==0){$filas.='<tr class="nmcatalogbusquedacont_1">';}else{$filas.='<tr class="nmcatalogbusquedacont_2">';}
		$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$filas.="</tr>";
		}
	}
	
	if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			
	
	$x='<div style="width: 35%; text-align: right; margin-bottom:0.5em;" class="pull-right"><label>Saldo Total:$ </label><input type="text" id="adeudototal" value="'.$saldodeuda.'" class="form-control" readonly></div>
	<p>';

	$catalogo='
	<p><div class="tipo">
	<table><tbody><tr>
	<td><img class="nmwaicons" type="button"  onclick="paginacionGridCxc('.$pag_anterior.',1);" src="../../../../netwarelog/design/default/pag_ant.png"></td>
	<td><img class="nmwaicons" type="button"  onclick="paginacionGridCxc('.$pag_siguiente.',1);" src="../../../../netwarelog/design/default/pag_sig.png" ></td>
	<td><a href="javascript:window.print();">
	<img class="nmwaicons" src="../../../../netwarelog/design/default/impresora.png" border="0"></a></td>
	<td></td></tr></tbody></table></div><br>'.$x;
	//<div style="width: 95%; text-align: right;"><input type="button" value="Agregar cuenta" onclick="cargaCxc();"></div>
	$catalogo.='<center>
	<p>
	<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%" id="table1"><thead>
	<tr class="tit_tabla_buscar">'.$encabezado.'</tr></thead>		
	'.$filas.'</table></center>';		


	
	mysqli_close($conection);
	return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>	