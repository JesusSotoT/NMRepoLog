<?php
//include("../../../netwarelog/catalog/conexionbd.php");


if(isset($_POST["funcion"]))
{
	switch ($_POST["funcion"])
	{
		case 'gridcxp': echo  gridCxp($_POST["pagina"],$_POST["filtro"],true);
		case 'ordendecompra': echo ordendecompra($_POST["id"]);
		break;	
	}
}
function proveedores_filtro($post=false){
  if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}
  
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	$query = "SELECT DISTINCT(razon_social), idPrv FROM mrp_proveedor T1 INNER JOIN cxp T2 ON T1.idPrv = T2.idProveedor ";
	$result = $conection->query($query);
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
    //print_r($row);
      echo'<option value="'.$row['idPrv'].'">'.$row['razon_social'].'</option>';
	}
}
function saldoproveedor($post=false){
  if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}
  echo "SELECT sum(saldoactual) from cxp where idProveedor=".$_POST['idProveedor'];
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	$query = "SELECT sum(saldoactual) from cxp where idProveedor=".$_POST['idProveedor'];
	$result = $conection->query($query);
	var_dump($result);
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
    //print_r($row);
      echo $row['saldoactual'];
      $x2.=$row['saldoactual'];
	}
	return $x2;
}
function ordendecompra($idorden){
//	echo '(idorden='.$idorden.')';
	$post=true;
	//$tabla='';
	  if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}
  
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	$query = "SELECT p.nombre, u.compuesto,o.cantidad, o.ultCosto from mrp_producto p, mrp_unidades u, mrp_producto_orden_compra o where p.idProducto=o.idProducto and u.idUni=o.idUnidad and o.idOrden=".$idorden;
	//echo $query;
	$result = $conection->query($query);
//var_dump($result);
$tabla.='<div id="ordendecompra"><table>
	<tr>
	<td class="nmcatalogbusquedatit" align="center">Cantidad</td>
	<td class="nmcatalogbusquedatit" align="center">Unidad</td>
	<td class="nmcatalogbusquedatit" align="center">Producto</td>
	<td class="nmcatalogbusquedatit" align="center">Costo unitario</td>
	<td class="nmcatalogbusquedatit" align="center">Subtotal</td>
	</tr>';
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
    //print_r($row);
	//$tabla.='<tr>';
	if($i%2==0){$tabla.='<tr class="nmcatalogbusquedacont_1">';}else{$tabla.='<tr class="nmcatalogbusquedacont_2">';}
	$tabla.='<td class="" align="center">'.$row['cantidad'].'</td>';
	$tabla.='<td class="" align="center">'.$row['compuesto'].'</td>';
	$tabla.='<td class="" align="center">'.$row['nombre'].'</td>';
	$tabla.='<td class="" align="center">'.$row['ultCosto'].'</td>';
	$tabla.='<td class="" align="center">'.($row['ultCosto']*$row['cantidad']).'</td>';
	$tabla.='</tr>';
	$total+=$row['cantidad']*$row['ultCosto'];
      //echo '('.$row['nombre'].'")'.$row['compuesto'].'--'.$row['cantidad'].'--'.$row['ultCosto'].')';
	}
	$tabla.='<tr class="nmcatalogbusquedacont_1"><td></td><td></td><td><td>Subtotal:</td><td>$'.number_format($total,2,".",",").' </td></tr>';
	$tabla.='<tr class="nmcatalogbusquedacont_1"><td></td><td></td><td><td>IVA:</td><td>$'.number_format(($total*.16),2,".",",").' </td></tr>';
	$tabla.='<tr class="nmcatalogbusquedacont_1"><td></td><td></td><td><td>Total:</td><td>$'.number_format(($total*1.16),2,".",",").' </td></tr>';
	$tabla.='</table></div>';
	return $tabla;

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
	

		$querybeta="     SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idProveedor, c.idOrCom FROM cxp c where estatus=0 ORDER BY c.idcxp desc "; 		
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
    
	//////////
	$filtro = stripslashes($filtro);
	if($filtro==1){
		$queryadeudo="SELECT sum(saldoactual) as saldoactual from cxp where estatus=0"; 
	}else{
		$queryadeudo="SELECT sum(c.saldoactual) as saldoactual from cxp c where ".$filtro." and c.estatus=0"; 
	}
			
	$x = $conection->query($queryadeudo);

	
	while($rowy = $x->fetch_array(MYSQLI_ASSOC))
	{
		$saldodeuda = $rowy["saldoactual"];
		

	} 
		if($filtro==1){
		$filtro='';
		$estatus='estatus=0';
	}else{
		$estatus='';
	}
/////////
	$filtro = stripslashes($filtro);
	$consulta0="SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idProveedor, c.idOrCom FROM cxp c WHERE ".$estatus." ".$filtro." ORDER BY c.idcxp desc "; 		
	$q0=$conection->query($consulta0);
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	//$paginas=ceil($paginas);
	
	$consulta="SELECT c.idcxp, c.fechacargo, c.fechavencimiento, c.concepto, c.monto, c.saldoabonado, c.saldoactual, c.estatus, c.idProveedor, c.idOrCom FROM cxp c WHERE ".$estatus." ".$filtro." ORDER BY c.idcxp desc   LIMIT ".$begin.",".$paginacion; 		
	$q=$conection->query($consulta);
	$i=0;
	$filas='';
	$proveedores_filtrados = array();
  
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
	  if($row["idProveedor"] != '' || $row["idProveedor"] != NULL){
	    $query = "SELECT razon_social, rfc, domicilio, telefono, email, web, idestado, idmunicipio, legal FROM mrp_proveedor WHERE idPrv=".$row["idProveedor"];
  	  $result = $conection->query($query);
  	  // echo $query;
  	  //echo $conection->error;
  	  //echo '---------';
  	  $array_proveedor = $result->fetch_array(MYSQLI_ASSOC);
  	  if(!in_array($row["idProveedor"],$proveedores_filtrados)){
  	    $proveedores_filtrados[] = array(
    	            "idProveedor" =>$row["idProveedor"],
    	            "razonSocial" => $array_proveedor['razon_social']
    	  );
    	  
  	  }
  	  
  	  
	  }else{
	    $array_proveedor['razon_social'] = '';
	  } 
	  	date_default_timezone_set('America/Mexico_City'); 
		$hoy = date("Y-m-d");
		$fechavence = date('Y-m-d',strtotime($row['fechavencimiento']));

		if($fechavence==$hoy){
			list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row['fechavencimiento']);
			$vencimiento = '<span style="color: #F09723;font-size: 14pt; font-weight:bold">&bull;</span>'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento;
			
		}else if($fechavence < $hoy){//font-size: 14pt; font-weight:bold
			if($row["estatus"]==1){
	            list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row['fechavencimiento']);
				$vencimiento = '<span style="color:#01a05f; font-size: 14pt; font-weight:bold;">&bull;</span>'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento;				
			}else{
				list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row['fechavencimiento']);
				$vencimiento = '<span style="color:#FF000C; font-size: 14pt; font-weight:bold;">&bull;</span>'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento;
			}
			

		}else if($fechavence > $hoy){
			list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row['fechavencimiento']);
			$vencimiento = '<div>'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento.'</div>';
		} 
		list($anoCargo, $mesCargo, $diaCargo) = explode("-", $row["fechacargo"]);
		//list($anoVencimiento, $mesVencimiento, $diaVencimiento) = explode("-", $row['fechavencimiento']);
		
		$link="cuenta.php?id=".$row['idcxp']."&fven=".$row['fechavencimiento'];
		

		if($i%2==0){$filas.='<tr class="nmcatalogbusquedacont_1">';}else{$filas.='<tr class="nmcatalogbusquedacont_2">';}
				
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$row["idcxp"].'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaCargo.'-'.$mesCargo.'-'.$anoCargo.'</a></td>';
		//$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaVencimiento.'-'.$mesVencimiento.'-'.$anoVencimiento.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$vencimiento.'</a></td>';
		$filas.='<td>				<a class="a_registro" href="'.$link.'">'.$array_proveedor['razon_social'].'</a></td>';
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
    $filas.='<td  style="text-align:center;">				<a class="a_registro" onclick="ordencompra('.$row["idOrCom"].');"><img src="../../../../netwarelog/catalog/img/preliminar.png"/></a></td>';	
		$filas.='</tr>';
		$i++;
	}
 // print_r($proveedores_filtrados);
	
	$encabezado='
	<td class="nmcatalogbusquedatit" align="center">ID</td>
	<td class="nmcatalogbusquedatit" align="center">Fecha de cargo</td>
	<td class="nmcatalogbusquedatit" align="center">Fecha de vencimiento</td>
	<td class="nmcatalogbusquedatit" align="center">Proveedores</td>
	<td class="nmcatalogbusquedatit" align="center">Concepto</td>
	<td class="nmcatalogbusquedatit" align="center">Monto</td>
	<td class="nmcatalogbusquedatit" align="center">Saldo abonado</td>
	<td class="nmcatalogbusquedatit" align="center">Saldo actual</td>
	<td class="nmcatalogbusquedatit" align="center">Orden de Compra</td>';
	
	
	if($i<10)
	{
		for($j=$i;$j<10;$j++)
		{	
		if($j%2==0){$filas.='<tr class="nmcatalogbusquedacont_1" style="height: 20px">';}else{$filas.='<tr class="nmcatalogbusquedacont_2" style="height: 20px">';}
		$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$filas.="</tr>";
		}
	}
	
	if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			
		

	
	$catalogo='
	<p><div id="oc" style="display:none;"></div><div class="tipo">
	<table><tbody><tr>
	<td><img class="nmwaicons" type="button"  onclick="paginacionGridCxp('.$pag_anterior.',1);" src="../../../../netwarelog/design/default/pag_sig.png"></td>
	<td><img class="nmwaicons" type="button"  onclick="paginacionGridCxp('.$pag_siguiente.',1);" src="../../../../netwarelog/design/default/pag_sig.png" ></td>
	<td><a href="javascript:window.print();">
	<img class="nmwaicons" src="../../../../netwarelog/design/default/impresora.png" border="0"></a></td>
	<td></td></tr></tbody></table></div><br>';
						
	$catalogo.='
	<div class="row">
		<div class="col-sm-3 col-sm-offset-6">
			<label>Saldo Total:$</label>
			<input type="text" id="adeudototal" value="'.$saldodeuda.'" class="form-control" readonly>
		</div>
		<div class="col-sm-3">
			<label>&nbsp;</label>
			<input type="button" value="Agregar cuenta" onclick="cargaCxp();" class="btn btn-primary btnMenu">
		</div>
	</div>
	<p>
	<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
	<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
	<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
	'.$filas.'</table></center>';		
	
	mysqli_close($conection);
	return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>	