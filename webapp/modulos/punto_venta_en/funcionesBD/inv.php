<?php
//header('Content-Type: text/html; charset=ISO-8859-1'); 
//ini_set('display_errors', 1);
//error_reporting(E_ERROR);
session_start();
$usuario = $_SESSION["accelog_idempleado"];
include("../../../netwarelog/webconfig.php");
$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
if($_POST['Operacion'] == 1)
{
$myQuery = "SELECT s.cantidad AS Cantidad, p.nombre AS Nombre 
	FROM mrp_stock s INNER JOIN mrp_producto p ON p.idProducto = s.idProducto 
	WHERE s.idProducto = ".$_POST['Id']." AND s.idAlmacen = ".$_POST['Almacen'];


$producto = $connection->query($myQuery);
$connection->close();
$producto = $producto->fetch_object();
?>
<table class="table">
	<tr class='busqueda_fila'>
		<td><label>Nombre:</label></td><td><?php echo $producto->Nombre; ?></td>
	</tr>
	<tr class='busqueda_fila2'>
		<td><label>En Stock:</label></td><td><?php echo $producto->Cantidad; ?><input type='hidden' id='enstock' name='enstock' value='<?php echo $producto->Cantidad; ?>'></td>
	</tr>
</table>
<?php
}
if($_POST['Operacion'] == 2)
{
	$myQuery = "UPDATE mrp_stock SET cantidad = " . $_POST['CantidadTotal'] . " WHERE idProducto = " . $_POST['Id'] . " AND idAlmacen = " . $_POST['Almacen'] ;
	//echo $myQuery;
	$connection->query($myQuery);
	$myQuery = "INSERT INTO mrp_movimientos_inv(usuario,movimiento,tipo,cantidad,idProducto,idAlmacen,comentario,fecha) VALUES($usuario,'Movimiento Inventario','".$_POST['Tipo']."',".$_POST['Cantidad'].",".$_POST['Id'].",".$_POST['Almacen'].",'".$_POST['Comentario']."',DATE_SUB(NOW(), INTERVAL 6 HOUR))";
	$connection->query($myQuery);
	$connection->close();
	//echo $myQuery;
	$return = array('estatus' => true );
	echo json_encode($return);
}

if($_POST['Operacion'] == 3)
{
	$myQuery = "SELECT u.usuario, mi.movimiento, mi.tipo, mi.cantidad, mi.idProducto, a.nombre, mi.comentario, mi.fecha 
	FROM mrp_movimientos_inv mi INNER JOIN accelog_usuarios u ON u.idempleado = mi.usuario INNER JOIN almacen a ON a.idAlmacen = mi.idAlmacen 
	WHERE mi.idProducto = ".$_POST['Id']." AND mi.idAlmacen = ".$_POST['Almacen']." ORDER BY fecha DESC LIMIT 10";

	$datosOb = $connection->query($myQuery);
	$connection->close();
	?>
<table style='font-size:12px;' border=1 class="table table-bordered">
<tr ><td>Usuario</td><td>Movimiento</td><td>Tipo</td><td>Cantidad</td><td>Producto</td><td>Almacen</td><td>Comentario</td><td>Fecha</td></tr>

<?php
$cont = 0;
while($datos = $datosOb->fetch_object())
{
	if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
	{
    	$color='busqueda_fila';
	}
	else//Si es impar pinta esto
	{
    	$color='busqueda_fila2';
	}
	echo "<tr class='$color'><td>$datos->usuario</td><td>$datos->movimiento</td><td>$datos->tipo</td><td>$datos->cantidad</td><td>$datos->idProducto</td><td>$datos->nombre</td><td>$datos->comentario</td><td>$datos->fecha</td></tr>";
	$cont++;
}
?>
</table>

<?php
}
if($_POST['Operacion'] == 4)
{
	
	$queryDep = "SELECT * from mrp_departamento;";
	$datosDep = $connection->query($queryDep);
	//$connection->close();
	$ht='<select id="departamento" class="form-control" onchange="buscaFam()"><option value="0">--Departamento--</option>';
	while($datosd = $datosDep->fetch_object()){
		 $ht .='<option value="'.$datosd->idDep.'">'.utf8_encode($datosd->nombre).'</option>';
	}
	$ht .='</select>';


	$queryCol = "SELECT * from mrp_color;";
	$datosCol = $connection->query($queryCol);
	//$connection->close();
	$htC='<select id="color" class="form-control" name="color"><option value="0">--Color--</option>';
	while($datosc = $datosCol->fetch_object()){
		 $htC .='<option value="'.$datosc->idCol.'">'.utf8_encode($datosc->color).'</option>';
	}
	$htC .='</select>';

	$queryTalla = "SELECT * from mrp_talla;";
	$datosTalla = $connection->query($queryTalla);
	//$connection->close();
	$htT='<select id="talla" class="form-control" name="talla"><option value="0">--Talla--</option>';
	while($datosT = $datosTalla->fetch_object()){
		 $htT .='<option value="'.$datosT->idTal.'">'.utf8_encode($datosT->talla).'</option>';
	}
	$htT .='</select>';

	$arr = array('dep' => $ht,'col' => $htC, 'talla' => $htT );

   	echo json_encode($arr);

   //exit();
	
}
if($_POST['Operacion'] == 5)
{
	$queryFam = "SELECT * from mrp_familia where idDep=".$_POST['idDep'];
	$datosFam = $connection->query($queryFam);
	//$connection->close();
	$ht='<select id="familia" class="form-control" onchange="buscaLin();"><option value="0">--Familia--</option>';
	while($datosF = $datosFam->fetch_object()){
		 $ht .='<option value="'.$datosF->idFam.'">'.utf8_encode($datosF->nombre).'</option>';
	}
	$ht .='</select>';

	$arr = array('fam' => $ht);

   	echo json_encode($arr);
}
if($_POST['Operacion'] == 6)
{
	$queryLin = "SELECT * from mrp_linea where idFam=".$_POST['idFam'];
	$datosLin = $connection->query($queryLin);
	//$connection->close();
	$htL='<select id="linea" class="form-control" name="linea"><option value="0">--linea--</option>';
	while($datosL = $datosLin->fetch_object()){
		 $htL .='<option value="'.$datosL->idLin.'">'.utf8_encode($datosL->nombre).'</option>';
	}
	$htL .='</select>';

	$arra = array('lin' => $htL);
	//print_r($arr);
   	echo json_encode($arra);
}if($_POST['Operacion'] == 7){

		$idProducto = $_POST['idProducto'];
		$idAlmacen = $_POST['idAlmacen'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$tablePI='';

		$filtro = '';
		if($desde !='' && $hasta!=''){
			$filtro = ' where fecha_pedido between "'.$desde.'" and "'.$hasta.'" ';
		}
		
		$queryNombre = "SELECT idProducto,nombre,costo,stock_inicial from mrp_producto where idProducto=".$idProducto;
		$datosPro = $connection->query($queryNombre);
		while($datpro = $datosPro->fetch_object()){
		$costInicial = $datpro->costo;
		$cantidadInicial = $datpro->stock_inicial;
		$costoTotInicial = $costInicial * $cantidadInicial;
		 $tableP.='<h1>'.utf8_encode($datpro->nombre).'<h1>';
		 $tableP.='<input type="hidden" value="'.$datpro->idProducto.'" id="producto">';
		}

		$queryAlmacen = "SELECT idAlmacen,nombre from almacen where idAlmacen=".$idAlmacen;
		$datosAl = $connection->query($queryAlmacen);
		while($datAlm = $datosAl->fetch_object()){
		 $tableP.='<h4>'.utf8_encode($datAlm->nombre).'</h4>';
		 $tableP.='<input type="hidden" value="'.$datAlm->idAlmacen.'" id="almacenProm">';
		}
		$tableP.='<table><tr><td>Desde:</td><td><input type="text" id="desde"></td><td>Hasta:</td><td><input type="text" id="hasta"></td><td><input type="button" id="promFe" onclick="promedioFechas();" value="Enviar"></td></tr></table>';
		$queryCompras = "SELECT * from (SELECT 'Compra' as movimiento, o.fecha_pedido,p.cantidad,p.ultCosto, (p.cantidad * p.ultCosto) as costo_total from mrp_producto_orden_compra p, mrp_orden_compra o where o.idOrd=p.idOrden and p.idProducto=".$idProducto." and o.estatus='Cerrada' and o.idAlmacen=".$idAlmacen."
		 UNION 
		 select 'Venta' as movimiento ,v.fecha as fecha_pedido,p.cantidad,'' as ultCosto,'' as costo_total from venta_producto p, venta v where v.idVenta=p.idVenta and   p.idProducto=".$idProducto.") as super ".$filtro." order by fecha_pedido";
		$conCompras=$connection->query($queryCompras); 

		$cantidad=$cantidadInicial;
		$costoTotal = $costoTotInicial;
		$costoUnidad = $costInicial;
		if($cantidadInicial!=0){
			$tablePI.='<tr class="nmcatalogbusquedacont_1"><td>Inicial</td><td></td><td align="center">'.number_format($cantidad).'</td><td align="right">$'.number_format($costoUnidad,2).'</td><td align="right">$'.number_format($costoTotal,2).'</td><td align="center">'.number_format($cantidad).'</td><td align="right">$'.number_format($costoUnidad,2).'</td><td align="right">$'.number_format($costoTotal,2).'</td></tr>';
			if($desde!=''){
				$tablePI='';
			}
		}

		$tableP.='<table border="1"';
		$tableP.='<tr><td class="nmcatalogbusquedatit">Movimiento</td><td class="nmcatalogbusquedatit">Fecha</td><td class="nmcatalogbusquedatit">Cantidad</td><td class="nmcatalogbusquedatit">Costo Unidad</td><td class="nmcatalogbusquedatit">Costo Total</td>';
		$tableP.='<td class="nmcatalogbusquedatit">Saldo Cantidad</td><td class="nmcatalogbusquedatit">Saldo Costo Unidad</td><td class="nmcatalogbusquedatit">Saldo Costo Total</td></tr>'.$tablePI;
		//$tableP.='<tr class="nmcatalogbusquedacont_1"><td>Inicial</td><td></td><td>'.$cantidad.'</td><td>'.$costoUnidad.'</td><td>'.$costoTotal.'</td><td>'.$cantidad.'</td><td>'.$costoUnidad.'</td><td>'.$costoTotal.'</td></tr>';
		while($compras = $conCompras->fetch_object()){
		
			$tableP .= '<tr class="nmcatalogbusquedacont_1">';
			$tableP.='<td>'.$compras->movimiento.'</td>';
			$tableP.='<td>'.substr($compras->fecha_pedido,0,10).'</td>';
			if($compras->movimiento =='Compra'){
				$tableP.= '<td align="center">'.$compras->cantidad.'</td>'.'<td align="right">$'.number_format($compras->ultCosto,2).'</td>'.'<td align="right">$'.number_format($compras->costo_total,2).'</td>';
				//$tableP.='<td></td><td></td><td></td>';
					$cantidad +=$compras->cantidad;
					$costoTotal += $compras->cantidad * $compras->ultCosto;
					$costoUnidad = $costoTotal / $cantidad;
					$tableP.='<td align="center">'.$cantidad.'</td><td align="right">$'.number_format($costoUnidad,2).'</td><td align="right">$'.number_format($costoTotal,2).'</td>'; 
			}else{
				//$tableP.='<td></td><td></td><td></td>';
				$tableP.= '<td align="center">'.$compras->cantidad.'</td>'.'<td align="right">$'.number_format($costoUnidad,2).'</td>'.'<td align="right">$'.number_format(($compras->cantidad*$costoUnidad),2).'</td>';
					$cantidad -=$compras->cantidad;

				$tableP.='<td align="center">'.$cantidad.'</td><td align="right">$'.number_format($costoUnidad,2).'</td><td align="right">$'.number_format(($costoTotal-($compras->cantidad*$costoUnidad)),2).'</td>'; 
				$costoTotal=($costoTotal-($compras->cantidad*$costoUnidad));

			}
			/////esto es lo bueno
			/*$tableP.= '<td>'.$compras->cantidad.'</td>'.'<td>'.$compras->ultCosto.'</td>'.'<td>'.$compras->costo_total.'</td>';
			$cantidad +=$compras->cantidad;
			$costoTotal += $compras->cantidad * $compras->ultCosto;
			$costoUnidad = $costoTotal / $cantidad;
			$tableP.='<td>'.$cantidad.'</td><td>'.$costoUnidad.'</td><td>'.$costoTotal.'</td>'; */
			//////
			$tableP .= '</tr>'; 
		}

		$tableP .='</table>';
		echo $tableP;
		//$tableP='';
}
if($_POST['Operacion'] == 8){
	$q = utf8_decode($_POST['q']);
	//echo '('.$q.')';
		$return = array();
		//unset($return);
		$queryNombre = "SELECT nombre,codigo from mrp_producto where nombre LIKE '%".$q."%' and estatus=1";
		// $queryNombre;
		$datosPro = $connection->query($queryNombre);
		while($x = $datosPro->fetch_array()){
			if($x['nombre']!='null'){
				array_push($return, array('nombre' =>utf8_encode($x['nombre'])));
			}
			
		}
		//$return = utf8_encode($return);
		//print_r($return);
		echo json_encode($return);
      //printf ("%s (%s)\n", $row["Name"], $row["CountryCode"]);
		

}
?>