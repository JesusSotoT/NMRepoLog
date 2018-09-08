<?php
	include("../../../netwarelog/webconfig.php");
	header('Content-Type: text/html; charset=utf-8');
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$opc=$_REQUEST['opc' ];

	switch ($opc) {
		case 1://consulta de unidades y cantidad existente
			$almacen=$_REQUEST['a'];
			$producto=$_REQUEST['p'];
			$cons=$conection->query("select s.cantidad,u.compuesto,u.idUni from mrp_stock s, mrp_producto p,mrp_unidades u where s.idProducto=p.idProducto and  p.idProducto=".$producto." and s.idAlmacen=".$almacen." and s.idUnidad=u.idUni and p.estatus=1");
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
				echo '<option value="0" >-- Elija un almacen --</option>';
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
					echo'<option value="'.$producto['idProducto'].'" >'.$producto['codigo'].' / '.$producto['nombre'].'</option>
					';
				}
			}else{
				echo '<option selected>--No existen Productos de esa Linea--</option>';
			}
			break;

		case 7:
			//consulta apartir de familia
			$famili = $_REQUEST['familia'];
			$prod = $conection -> query("select p.idProducto,p.nombre,p.codigo from mrp_producto p,mrp_linea l where p.idLinea=l.idLin and l.idFam=" . $famili ." and p.estatus=1");
			if ($prod -> num_rows > 0) {
				echo ' <option value="elije" selected>----- Elija un producto -----</option>';
				while ($producto = $prod -> fetch_array(MYSQLI_ASSOC)) {
					echo '<option value="' . $producto['idProducto'] . '" >' .$producto['codigo'].' / '. $producto['nombre'] . '</option>';
				}
			} else {
				echo '<option selected>--No existen Productos--</option>';
			}
			break;

		case 8:
			//consulta apartir de departamento
			$depa = $_REQUEST['depa'];
			$prod = $conection -> query("select p.idProducto,p.nombre,p.codigo from mrp_producto p,mrp_linea l,mrp_familia f where p.idLinea=l.idLin and l.idFam=f.idFam and f.idDep=" . $depa ." and p.estatus=1");
			if ($prod -> num_rows > 0) {
				echo ' <option value="elije" selected>----- Elija un producto -----</option>';
				while ($producto = $prod -> fetch_array(MYSQLI_ASSOC)) {
					echo '<option value="' . $producto['idProducto'] . '" >' .$producto['codigo'].' / '. $producto['nombre'] . '</option>';
				}
			} else {
				echo '<option selected>--No existen Productos--</option>';
			}
			break;


		case 9:
			echo
			'
			<tr  class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
				<th class="nmcatalogbusquedatit" align="center">ID</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Movimiento</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Fecha</th>
			</tr>';
			$idalmacen=$_REQUEST['a'];
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			if($inicio != '' && $fin != '') {
				$entre = " and mm.fechamovimiento BETWEEN '".$inicio."' AND '".$fin."'";
			}
			$consul=$conection->query("select mm.id,
				(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
					mm.cantidadtotalOrigen,
					concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
					(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
					mm.cantidadtotalDestino,mm.fechamovimiento
				from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a
				where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
					and mm.idUnidad=u.idUni and mm.idAlmacenDestino=".$idalmacen." || mm.idAlmacenOrigen=".$idalmacen.$entre."  GROUP BY mm.id;");
			//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}
			$cont=0;
			while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
				if ($cont%2==0) {  //Si el contador es par pinta esto en la fila del grid
					$color='nmcatalogbusquedacont_1';
				} else { //Si es impar pinta esto
					$color='nmcatalogbusquedacont_2';
				}
				$cont++;
				echo '
				<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
					<td align="center"> '.$lista['id'].' </td >
					<td align="center"> '.$lista['almacenorigen'].' </td>
					<td align="center"> '.$lista['cantidadtotalOrigen'].' </td>
					<td align="center"> '.$lista['movimiento'].' </td>
					<td align="center"> '.$lista['almacendestino'].' </td>
					<td align="center"> '.$lista['cantidadtotalDestino'].' </td>
					<td align="center"> '.$lista['fechamovimiento'].' </td>
				</tr>
				';
			}
			break;

		case 10:
			echo '
			<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
				<th class="nmcatalogbusquedatit" align="center">ID</th>
				<th class="nmcatalogbusquedatit" align="center" style="display:none">Folio impresi&oacute;n</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Movimiento</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Fecha</th>
				<th class="nmcatalogbusquedatit" align="center">Salida</th>
				<th class="nmcatalogbusquedatit" align="center">Entrada</th>
				<th class="nmcatalogbusquedatit" align="center">Comprobante</th>
			</tr>
			';
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			$idalmacen=$_REQUEST['a'];
			$usuario = $_REQUEST['user'];
			if($idalmacen != '--Elija un almacén--') {
				//$whereAlamecen =  " and mm.idAlmacenDestino=".$idalmacen." OR mm.idAlmacenOrigen=".$idalmacen;
				$whereAlamecen =  " and mm.idAlmacenOrigen=".$idalmacen;
			}else{
				$whereAlamecen ='';
			}

			if($inicio =='' || $fin==''){
				$whereFecha = "";
			}else{
				$whereFecha =" and mm.fechamovimiento BETWEEN '".$inicio." 01:01:01' AND '".$fin." 23:59:59'";
			}

			if($usuario==0){
				$whereUsuario = "";
			}else{
				$whereUsuario = " and mm.idEmpleado=".$usuario;
			}
			//exit();
			/*	select mm.id,
			(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
				mm.cantidadtotalOrigen,
				concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
				(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
					mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
					(select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
			from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
			where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
				and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado  GROUP BY mm.id ORDER BY mm.id desc;*/
			/*	echo "select mm.id,
				(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
				mm.cantidadtotalOrigen,
				concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
				(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
				mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
				(select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
			from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
			where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
				and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado ".$whereAlamecen." ".$whereFecha." ".$whereUsuario." GROUP BY mm.id ORDER BY mm.id desc;";
			exit(); */
			$buscaf=$conection->query("
				select mm.id, mm.idImpresion, 
					(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
					mm.cantidadtotalOrigen,
					concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
					(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
					mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
					(select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
				from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
				where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
					and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado ".$whereAlamecen." ".$whereFecha." ".$whereUsuario." GROUP BY mm.id ORDER BY mm.id desc;");
			$cont=0;
			while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
				if ($cont%2==0) {//Si el contador es par pinta esto en la fila del grid
					$color='nmcatalogbusquedacont_1';
				} else {//Si es impar pinta esto
					$color='nmcatalogbusquedacont_2';
				}
				$cont++;

				if( $listaf['usuarioentrada']==''){
					$usuarioentrada = '<p style="color: #FFBA0A;">En Transito</p>';
				}else{
					$usuarioentrada ='<p style="color: #027B06;">'.$listaf['usuarioentrada'].'</p>';
				}

				echo '
				<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
					<td align="center"> 
						'.$listaf['id'].'
					</td >
					<td align="center" style="display:none">
						'.$listaf['idImpresion'].'
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
					<td align="center">
						'.$listaf['usuario'].'
					</td>
					<td align="center">
						'.$usuarioentrada.'
					</td>
					<td align="center">
						<div onclick="comprobante('.$listaf['idImpresion'].');">
							<img src="../../punto_venta_nuevo/images/imprime.png" style="width:20px; height:20px; align:center;" alt=""  >
						</div>
					</td>
				</tr>';
			} 
			//
			break;

		case 11:
			//echo 'Entro al CAse 11';
			$return =array();
			$alorigen=$_REQUEST['origen'];
			$aldestino=$_REQUEST['destino'];
			$unidad=$_REQUEST['unidad'];
			$idProducto = $_REQUEST['idProducto'];
			//echo "SELECT a.nombre as destino,(select nombre from almacen where idAlmacen='.$alorigen.') as origen, p.nombre as producto, p.idProducto as idProducto, u.compuesto as unidad from almacen a, mrp_producto p, mrp_unidades u where a.idAlmacen='.$aldestino.' and p.idProducto='.$idProducto.' and u.idUni='.$unidad'";
			$prod=$conection->query('SELECT a.nombre as destino,(select nombre from almacen where idAlmacen='.$alorigen.') as origen, p.nombre as producto, p.idProducto as idProducto, u.compuesto as unidad from almacen a, mrp_producto p, mrp_unidades u where a.idAlmacen='.$aldestino.' and p.idProducto='.$idProducto.' and u.idUni='.$unidad);
			//echo 'SELECT a.nombre as destino,(select nombre from almacen where idAlmacen='.$alorigen.') as origen, p.nombre as producto, p.idProducto as idProducto, u.compuesto as unidad from almacen a, mrp_producto p, mrp_unidades u where a.idAlmacen='.$aldestino.' and p.idProducto='.$idProducto.' and u.idUni='.$unidad;
			if($prod->num_rows>0){
				while($producto=$prod->fetch_array(MYSQLI_ASSOC)){
					array_push($return, array('destino' => $producto["destino"],'origen' => $producto["origen"], 'idProducto' => $producto['idProducto'], 'nombre' => utf8_encode($producto['producto']), 'unidad' => $producto['unidad']));
				}
			}else{
				echo '<option selected>--No existen Productos de esa Linea--</option>';
			}
			//return $return;
			echo json_encode($return);
			//print_r($return); */
			break;

		case 12:
			//echo 'llego';
			$array=$_REQUEST['x'];
			$array =json_decode($array);
			//print_r($array);
			$cont = 1;

			////////////////////     Primero guarda en la tabla de Movimientos de Impresion para tomar el ID     \\\\\\\\\\\\\\\\\\\\
			$sql ='INSERT into movimientos_mercancia_impresion(idAlmacenOrigen,idAlmacenDestino,fechamovimiento,idEmpleado)';
			$sql.='values ("'.$value->almorigen.'","'.$value->almdestino.'","'.date('Y-m-d H:i:s').'","'.$_SESSION['accelog_idempleado'].'")';
			$insert=$conection->query($sql);
			////////////////////     Enseguida obtiene el ID Guardado     \\\\\\\\\\\\\\\\\\\\
			$imp=$conection->query("select idImpresion from movimientos_mercancia_impresion Order by idImpresion");
			if($imp->num_rows>0){
				while ($imprime=$imp->fetch_array(MYSQLI_ASSOC)){
					$folioimpresion = $imprime['idImpresion'];
				}
			}else{
				$folioimpresion = 1;
			}


			foreach ($array as $key => $value) {
				$cons=$conection->query("select s.cantidad,u.compuesto,u.idUni from mrp_stock s, mrp_producto p,mrp_unidades u where s.idProducto=p.idProducto and  p.idProducto=".$value->idProducto." and s.idAlmacen=".$value->almdestino." and s.idUnidad=u.idUni and p.estatus=1");
				if($cons->num_rows>0){
					if($canti=$cons->fetch_array(MYSQLI_BOTH)){
						$cantidadAlamcenDes = $canti['0'];
					}
				}else{
					$cantidadAlamcenDes = 0;
					//$upde=$conection->query("INSERT into mrp_stock (idProducto,cantidad,idAlmacen,idUnidad) values(".$producto.",".$cantidad.",".$almadestino.",".$unidad.");");;
				}
				//$upde=$conection->query("insert into mrp_stock (idProducto,cantidad,idAlmacen,idUnidad) values(".$producto.",".$cantidad.",".$almadestino.",".$unidad.");");

				$sql ='INSERT into movimientos_mercancia(idAlmacenOrigen,cantidadtotalOrigen,cantidadmovimiento,idUnidad,idProducto,idAlmacenDestino,cantidadtotalDestino,fechamovimiento,idEmpleado,idImpresion)';
				$sql.='values ("'.$value->almorigen.'","'.$value->cantalmorigen.'","'.$value->canti.'","'.$value->uni.'","'.$value->idProducto.'","'.$value->almdestino.'","'.$cantidadAlamcenDes.'","'.date('Y-m-d H:i:s').'","'.$_SESSION['accelog_idempleado'].'","'.$folioimpresion.'")';
				$insert=$conection->query($sql);

				$sql2 = "UPDATE mrp_stock set cantidad=cantidad-".$value->canti." WHERE idProducto=".$value->idProducto." and idAlmacen=".$value->almorigen;
				$update=$conection->query($sql2);
			}
			echo 'ok';
			break;

		case 13:
			$idmov = $_REQUEST['id'];
			$cons=$conection->query("SELECT * from movimientos_mercancia where id=".$idmov);
			if($cons->num_rows>0){
				if($canti=$cons->fetch_array(MYSQLI_BOTH)){
					$almacenorigen = $canti[1];
					$cantidadtotalORigen = $canti[2];
					$cantidadmovimiento = $canti[3];
					$unidad = $canti[4];
					$idProducto = $canti[5];
					$alamcendestino = $canti[6];
					$cantidadtotalDestino = $canti[7];
				}
			}else{
				echo "0,Unidades";
			}

			//echo "select s.cantidad,u.compuesto,u.idUni from mrp_stock s, mrp_producto p,mrp_unidades u where s.idProducto=p.idProducto and  p.idProducto=".$idProducto." and s.idAlmacen=".$alamcendestino." and s.idUnidad=u.idUni and p.estatus=1";
			$cons2=$conection->query("select s.cantidad,u.compuesto,u.idUni from mrp_stock s, mrp_producto p,mrp_unidades u where s.idProducto=p.idProducto and  p.idProducto=".$idProducto." and s.idAlmacen=".$alamcendestino." and s.idUnidad=u.idUni and p.estatus=1");
			if($cons2->num_rows>0){
				$update = $conection->query('UPDATE mrp_stock set cantidad=cantidad+'.$cantidadmovimiento.' where idProducto='.$idProducto.' and idAlmacen='.$alamcendestino);
				//	$update2 = $conection->query('UPDATE  mrp_stock set cantidad=cantidad-'.$cantidadmovimiento.' where idProducto='.$idProducto.' and idAlmacen='.$almacenorigen);
				$update3 = $conection->query('UPDATE movimientos_mercancia set status=1 , idEmpleadoRec='.$_SESSION['accelog_idempleado'].' where id='.$idmov);
			}else{
				$update=$conection->query("INSERT into mrp_stock (idProducto,cantidad,idAlmacen,idUnidad) values(".$idProducto.",".$cantidadmovimiento.",".$alamcendestino.",".$unidad.");");
				//$update2 = $conection->query('UPDATE  mrp_stock set cantidad=cantidad-'.$cantidadmovimiento.' where idProducto='.$idProducto.' and idAlmacen='.$almacenorigen);
				$update3 = $conection->query('UPDATE movimientos_mercancia set status=1, idEmpleadoRec='.$_SESSION['accelog_idempleado'].' where id='.$idmov);
			}

			echo 'ok';
			break;

		case 14:
			$return =array();
			$idmov=$_POST['idmov'];
			//echo '(('.$idmov.'))';

			$sql = 'SELECT m.id,p.nombre,m.cantidadmovimiento,u.compuesto,m.fechamovimiento,usu.usuario';
			$sql.=' from movimientos_mercancia m, mrp_producto p, mrp_unidades u, accelog_usuarios usu';
			$sql.=' where m.idProducto=p.idProducto and m.idUnidad=u.idUni and m.idEmpleado=usu.idEmpleado and m.id='.$idmov;

			$prod=$conection->query($sql);
			//echo 'SELECT a.nombre as destino,(select nombre from almacen where idAlmacen='.$alorigen.') as origen, p.nombre as producto, p.idProducto as idProducto, u.compuesto as unidad from almacen a, mrp_producto p, mrp_unidades u where a.idAlmacen='.$aldestino.' and p.idProducto='.$idProducto.' and u.idUni='.$unidad;
			if($prod->num_rows>0){
				while($mov=$prod->fetch_array(MYSQLI_ASSOC)){
					array_push($return, array('id' => $mov["id"],'nombre' => $mov["nombre"], 'cantidad' => $mov['cantidadmovimiento'], 'unidad' => $mov['compuesto'], 'fecha' => $mov['fechamovimiento'], 'usuario' => $mov['usuario']));
				}
			}else{
				echo 'Error...';
			}

			//return $return;
			echo json_encode($return);
			break;

		case 15:
			echo '
			<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
				<th class="nmcatalogbusquedatit" align="center">ID</th>
				<th class="nmcatalogbusquedatit" align="center" style="display:none">Folio impresi&oacute;n</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Origen</th>
				<th class="nmcatalogbusquedatit" align="center">Movimiento</th>
				<th class="nmcatalogbusquedatit" align="center">Almacen Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Cantidad Total Destino</th>
				<th class="nmcatalogbusquedatit" align="center">Fecha</th>
				<th class="nmcatalogbusquedatit" align="center">Salida</th>
				<th class="nmcatalogbusquedatit" align="center">Entrada</th>
				<th class="nmcatalogbusquedatit" align="center">Comprobante</th>
			</tr>
			';
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			$idalmacen=$_REQUEST['a'];
			$usuario = $_REQUEST['user'];
			if($idalmacen != '--Elija un almacén--') {
				$whereAlamecen =  " and mm.idAlmacenDestino=".$idalmacen." ";
			}else{
				$whereAlamecen = "";
			}
			if($inicio =='' || $fin==''){
				$whereFecha = "";
			}else{
				$whereFecha =" and mm.fechamovimiento BETWEEN '".$inicio." 01:01:01' AND '".$fin." 23:59:59'";
			}
			if($usuario==0){
				$whereUsuario = "";
			}else{
				$whereUsuario = " and mm.idEmpleadoRec=".$usuario;
			}

			$buscaf=$conection->query("
				select mm.id, mm.idImpresion, 
					(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
					mm.cantidadtotalOrigen,
					concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
					(select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
					mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
					(select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
				from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
				where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
					and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado ".$whereAlamecen." ".$whereFecha." ".$whereUsuario." GROUP BY mm.id ORDER BY mm.id desc;");
			$cont=0;
			while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
				if ($cont%2==0) { //Si el contador es par pinta esto en la fila del grid
					$color='nmcatalogbusquedacont_1';
				} else {//Si es impar pinta esto
					$color='nmcatalogbusquedacont_2';
				}
				$cont++;

				if( $listaf['usuarioentrada']==''){
					$usuarioentrada =  '<button class="btn btn-warning btn-xs" type="button" onclick="aprueba("'.$listaf['id'].'")">Recibir</button>';
				}else{
					$usuarioentrada ='<p style="color: #027B06;">'.$listaf['usuarioentrada'].'</p>';
				}

				echo '	<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
					<td align="center">
						'.$listaf['id'].'
					</td >
					<td align="center" style="display:none">
						'.$listaf['idImpresion'].'
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
					<td align="center">
					'.$listaf['usuario'].'
					</td>
					<td align="center">
					'.$usuarioentrada.'
					</td>

					<td align="center">
						<div onclick="comprobante('.$listaf['idImpresion'].');">
							<img src="../../punto_venta_nuevo/images/imprime.png" style="width:20px; height:20px; align:center;" alt=""  >
						</div>
					</td>
				</tr>';
			}
			//
			break;
	};
	$conection->close();
?>