<?php
    include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$opc=$_REQUEST['opc' ];
	switch ($opc) {
	case 1://consulta de unidades y cantidad existente
	$almacen=$_REQUEST['a'];
	$producto=$_REQUEST['p'];
	$cons=$conection->query("select s.cantidad,u.compuesto,u.idUni from mrp_stock s, mrp_producto p,mrp_unidades u where s.idProducto=p.idProducto and  p.idProducto=".$producto." and s.idAlmacen=".$almacen." and s.idUnidad=u.idUni");
	if($cons->num_rows>0){
	if($canti=$cons->fetch_array(MYSQLI_BOTH)){
	echo $canti['0'].",".$canti[1].",".$canti[2];
	}
	}else{
	echo "0,Unidades";
	}
	break;
	case 2://select almacen
	$almacen=$_REQUEST['a'];
		$alma=$conection->query("select * from almacen where idAlmacen <>" .$almacen);
			if($alma->num_rows>0){

			echo '<option selected>-- Elija un almacen --</option>';
			while($almacen=$alma->fetch_array(MYSQLI_ASSOC)){

			echo '
			<option value="'.$almacen['idAlmacen'].'" >'.$almacen['nombre'].'</option>
			';
			}
			}else{
			echo
            '<option selected>--No existen mas almacenes--</option>';
            }
	break;
//////////////////////////////////////////////////////////////////////////////
	case 3://inserts y updates del movimiento(funcionamiento,proceso)
	$almaorigen=$_REQUEST['almaorigen'];
    $almadestino=$_REQUEST['almadestino'];
 	$producto=$_REQUEST['producto'];
  	$unidad=$_REQUEST['unidad'];
  	$cantidad=$_REQUEST['cantidad'];
	number_format($cantidad, 2, '.', ',');
  	$upd=$conection->query("update mrp_stock set cantidad=cantidad+".$cantidad." where idAlmacen=".$almadestino." and idProducto=" .$producto);

		if($conection->affected_rows>0){
		$updorigen=$conection->query("update mrp_stock set cantidad=cantidad-".$cantidad." where idAlmacen=".$almaorigen." and idProducto=".$producto);
		
		$select=$conection->query("select * from mrp_stock where idAlmacen=".$almaorigen." and idProducto=".$producto);
		if($cantidades=$select->fetch_array(MYSQLI_BOTH)){
		$selec=$conection->query("select * from mrp_stock where idAlmacen=".$almadestino." and idProducto=".$producto);
		if($cantidadestino=$selec->fetch_array(MYSQLI_BOTH)){
		$insert=$conection->query("insert into movimientos_mercancia
		
		(idAlmacenOrigen,cantidadtotalOrigen,cantidadmovimiento,idUnidad,
		idProducto,idAlmacenDestino,
		cantidadtotalDestino,fechamovimiento) values
		(".$almaorigen.",".$cantidades['cantidad'].",".$cantidad.",".$unidad.",".$producto.",".$almadestino.",".$cantidadestino['cantidad'].",'".date('Y-m-d H:i:s')."')" );
			if($insert){ echo "ok";}
			}
			}

			}else{
			$upde=$conection->query(
			"insert into mrp_stock (idProducto,cantidad,idAlmacen,idUnidad) values(".$producto.",".$cantidad.",".$almadestino.",".$unidad.");");
			$updorigen=$conection->query("update mrp_stock set cantidad=cantidad-".$cantidad." where idAlmacen=".$almaorigen." and idProducto=".$producto);
			$select=$conection->query("select * from mrp_stock where idAlmacen=".$almaorigen." and idProducto=".$producto);
			if($cantidades=$select->fetch_array(MYSQLI_BOTH)){
			$selec=$conection->query("select * from mrp_stock where idAlmacen=".$almadestino." and idProducto=".$producto);
			if($cantidadestino=$selec->fetch_array(MYSQLI_BOTH)){
			$insert=$conection->query("insert into movimientos_mercancia
			(idAlmacenOrigen,cantidadtotalOrigen,cantidadmovimiento,idUnidad,
			idProducto,idAlmacenDestino,
			cantidadtotalDestino,fechamovimiento) values
			(".$almaorigen.",".$cantidades['cantidad'].",".$cantidad.",".$unidad.",".$producto.",".$almadestino.",".$cantidadestino['cantidad'].",'".date('Y-m-d H:i:s')."')");
			if($insert){ echo "ok";}
			}
			}
			}
			break;
			
    case 4://departamento
    $depa=$_REQUEST['depa'];
		$fami=$conection->query("select * from mrp_familia where idDep=".$depa);
			if($fami->num_rows>0){

			echo '<option value="elije" selected >-- Elija una Familia --</option>';
			while($famimilia=$fami->fetch_array(MYSQLI_ASSOC)){

			echo '
			<option value="'.$famimilia['idFam'].'" >'.$famimilia['nombre'].'</option>
			';
			}
			}else{
			echo
            '<option selected>--No existen Familias--</option>';
            }
	break;
	   
	case 5://familia
	$fami=$_REQUEST['fami'];
	$line=$conection->query("select * from mrp_linea where idFam=".$fami);
			if($line->num_rows>0){

			echo '<option selected value="elije">-- Elija una Linea --</option>';
			while($linea=$line->fetch_array(MYSQLI_ASSOC)){

			echo '
			<option value="'.$linea['idLin'].'" >'.$linea['nombre'].'</option>
			';
			}
			}else{
			echo
            '<option selected>--No existen Lineas--</option>';
            }
	break;
		case 6://linea
	//$fami=$_REQUEST['fami'];
		//$depa=$_REQUEST['depa'];
		$linea=$_REQUEST['linea'];
		$prod=$conection->query("select * from mrp_producto where idLinea=" .$linea);
			if($prod->num_rows>0){
			echo ' <option value="elije" selected>----- Elija un producto -----</option>';
			while($producto=$prod->fetch_array(MYSQLI_ASSOC)){

			echo'<option value="'.$producto['idProducto'].'" >'.$producto['nombre'].'</option>
			';
			}
			}else{
			echo '<option selected>--No existen Productos de esa Linea--</option>';
			}
			break;

			case 7:
		//consulta apartir de familia
 $famili = $_REQUEST['familia'];
			$prod = $conection -> query("select p.idProducto,p.nombre from mrp_producto p,mrp_linea l where p.idLinea=l.idLin and l.idFam=" . $famili);
			if ($prod -> num_rows > 0) {
				echo ' <option value="elije" selected>----- Elija un producto -----</option>';
				while ($producto = $prod -> fetch_array(MYSQLI_ASSOC)) {

                echo '<option value="' . $producto['idProducto'] . '" >' . $producto['nombre'] . '</option>';
				}
			} else {
				echo '<option selected>--No existen Productos--</option>';
			}
			break;
			case 8:
	 //consulta apartir de departamento
				$depa = $_REQUEST['depa'];
				$prod = $conection -> query("select p.idProducto,p.nombre from mrp_producto p,mrp_linea l,mrp_familia f where p.idLinea=l.idLin and l.idFam=f.idFam and f.idDep=" . $depa);
				if ($prod -> num_rows > 0) {
					echo ' <option value="elije" selected>----- Elija un producto -----</option>';
					while ($producto = $prod -> fetch_array(MYSQLI_ASSOC)) {

						echo '<option value="' . $producto['idProducto'] . '" >' . $producto['nombre'] . '</option>';
					}
				} else {
					echo '<option selected>--No existen Productos--</option>';
				}
				break;

case 9:
				echo
'
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
	<th align="center">ID</th>
	<th align="center">Almacen Origen</th>
	<th align="center">Cantidad Total Origen</th>
	<th align="center">Movimiento</th>
	<th align="center">Almacen Destino</th>
	<th align="center">Cantidad Total Destino</th>
	<th align="center">Fecha</th>
</tr>';
$idalmacen=$_REQUEST['a'];
$consul=$conection->query("select mm.id,
(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
mm.cantidadtotalOrigen,
concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
mm.cantidadtotalDestino,mm.fechamovimiento
from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a
where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
and mm.idUnidad=u.idUni and mm.idAlmacenDestino=".$idalmacen." || mm.idAlmacenOrigen=".$idalmacen." GROUP BY mm.id;");
//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}

while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
echo '
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$lista['id'].' </td >
	<td align="center"> '.$lista['almacenorigen'].' </td>
	<td align="center"> '.$lista['cantidadtotalOrigen'].' </td>
	<td align="center"> '.$lista['movimiento'].' </td>
	<td align="center"> '.$lista['almacendestino'].' </td>
	<td align="center"> '.$lista['cantidadtotalDestino'].' </td>
	<td align="center"> '.$lista['fechamovimiento'].' </td>

</tr>
'; }
break;
case 10:
	echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
			<th align="center">ID</th>
			<th align="center">Almacen Origen</th>
			<th align="center">Cantidad Total Origen</th>
			<th align="center">Movimiento</th>
			<th align="center">Almacen Destino</th>
			<th align="center">Cantidad Total Destino</th>
			<th align="center">Fecha</th>
			</tr>
';
	$inicio=$_REQUEST['inicio'];
	$fin=$_REQUEST['fin'];
	$buscaf=$conection->query("select mm.id,
				(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
				mm.cantidadtotalOrigen,
				concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
                (select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
                mm.cantidadtotalDestino,mm.fechamovimiento
                from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a
                where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
                and mm.idUnidad=u.idUni and mm.fechamovimiento BETWEEN '".$inicio."' AND '".$fin."'");
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 echo '	<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
                <td align="center"> 
			 '.$listaf['id'].'
				</td >
				 <td align="center">
			'.$listaf['almacenorigen'].'
				</td>
				<td align="center">
			'.$listaf['cantidadtotalOrigen'].'
				</td>
				<td align="center">
			'.$listaf['movimiento'].'
				</td>
				<td align="center">
		'.$listaf['almacendestino'].'
				</td>
				<td align="center">
			'.$listaf['cantidadtotalDestino'].'
				</td>
				<td align="center">
			'.$listaf['fechamovimiento'].'
				</td>
				
			</tr>';	
	 } 
// 	
	break;
	
			};
$conection->close();
		?>