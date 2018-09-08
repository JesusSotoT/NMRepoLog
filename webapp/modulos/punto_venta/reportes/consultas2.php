<?php
	include("../../../netwarelog/webconfig.php");
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

	$opc=$_REQUEST['opc' ];
	$idsucu=$_REQUEST['sucursal' ];
	if($idsucu=="todo"){
		$sucursal=" ";
	}else{
		$sucursal=" and v.idSucursal=".$idsucu;
	}
	switch ($opc) {
		case 1://CASE DE RVENTAS(LINEAS ETC)CASO DE RANGO HOY AYER ETC
			$elije=$_REQUEST['elije'];
			// <option value="1"  selected>Hoy</option>
			// <option value="2" >Ayer</option>
			// <option value="3" >Ultimos 7 dias</option>
			// <option value="4" >Este a&ntilde;o</option>
			// <option value="5"  >Todas</option>

			$fechaelije=$_REQUEST['fecha'];
			//echo "fecha".$fechaelije;
			$fecha=date('Y-m-d ' );
			if ($fechaelije==1) {
				$fechabuscar=$fecha;//hoy
			}elseif($fechaelije==2){
				$nuevafecha = strtotime ( '-24 hour' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//ayer
			}elseif($fechaelije==3){
				$nuevafecha = strtotime ( '-7 days' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//7 dias

				if($elije==1){
					$linea=$_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,l.nombre filtro,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento,vp.total 
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idLin=".$linea." and p.idLinea=l.idLin and p.idUnidad=um.idUni and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
							s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00'");
				} elseif($elije==2) {
					$familia= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,f.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v, mrp_familia f,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idFam=f.idFam and f.idFam=".$familia." and p.idLinea=l.idLin and p.idUnidad=um.idUni and vp.idProducto=p.idProducto and 
							vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00'");
				} elseif($elije==3) {
					$departamento= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,d.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
						where  v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and d.idDep=".$departamento." and p.idLinea=l.idLin and p.idUnidad=um.idUni and 
							vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and 
							v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00'");
				} elseif($elije==4) {
					$umedida= $_REQUEST['lifade'];
					$consulta=$conection->query("
						select
							max(v.estatus) estatus, max(v.idVenta) idventa, max(v.fecha) fecha, p.nombre, um.compuesto filtro, max(s.nombre) sucu,
							sum(vp.cantidad) cantidad, max(vp.preciounitario) preciounitario, sum(vp.subtotal) subtotal, sum(vp.impuestosproductoventa) impuestosproductoventa,
							sum(vp.montodescuento) montodescuento, max(vp.descuento) descuento, max(vp.tipodescuento) tipodescuento, sum(vp.total) total
						from
							mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
						where
							v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and um.idUni=".$umedida." and p.idLinea=l.idLin and p.idUnidad=um.idUni and
							vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and
							v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00'
						group by 
							p.nombre, um.compuesto");
				}
			}elseif($fechaelije==4){
				$fechaini=date('Y-01-01');
				$fechafin=date('Y-12-31');
				
				///////////////////////////////////////////////////////////////////////
				if($elije==1){
					$linea=$_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,l.nombre filtro,s.nombre sucu, vp.cantidad,vp.preciounitario, vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idLin=".$linea." and p.idLinea=l.idLin and p.idUnidad=um.idUni and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
							s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
				} elseif($elije==2) {
					$familia= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,f.nombre filtro,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idFam=f.idFam and f.idFam=".$familia." and p.idLinea=l.idLin and p.idUnidad=um.idUni and vp.idProducto=p.idProducto and 
						vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
				} elseif($elije==3) {
					$departamento= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,d.nombre filtro,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and d.idDep=".$departamento." and p.idLinea=l.idLin and p.idUnidad=um.idUni and 
							vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and 
							v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
				} elseif($elije==4) {
					$umedida= $_REQUEST['lifade'];
					$consulta=$conection->query("
						select
							max(v.estatus) estatus, max(v.idVenta) idventa, max(v.fecha) fecha, p.nombre, um.compuesto filtro, max(s.nombre) sucu,
							sum(vp.cantidad) cantidad, max(vp.preciounitario) preciounitario, sum(vp.subtotal) subtotal, sum(vp.impuestosproductoventa) impuestosproductoventa,
							sum(vp.montodescuento) montodescuento, max(vp.descuento) descuento, max(vp.tipodescuento) tipodescuento, sum(vp.total) total
						from
							mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
						where
							v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and um.idUni=".$umedida." and p.idLinea=l.idLin and p.idUnidad=um.idUni and
							vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and
							v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'
						group by 
							p.nombre, um.compuesto");
				}
				/////////////////////////////////////////////////////////////////////////un ano
			} elseif($fechaelije==5) {
				if($elije==1){
					$linea=$_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,l.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idLin=".$linea." and p.idLinea=l.idLin and s.idSuc=v.idSucursal ".$sucursal." and vp.idProducto=p.idProducto and 
							p.idUnidad=um.idUni and vp.idVenta=v.idVenta");
				} elseif($elije==2) {
					$familia= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,f.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_sucursal s, mrp_unidades um
						where v.estatus=1 and l.idFam=f.idFam and f.idFam=".$familia." and p.idLinea=l.idLin and s.idSuc=v.idSucursal ".$sucursal." and 
							vp.idProducto=p.idProducto and p.idUnidad=um.idUni and vp.idVenta=v.idVenta");
				} elseif($elije==3) {
					$departamento= $_REQUEST['lifade'];
					$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,d.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
							vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
						from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um 
						where  v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and d.idDep=".$departamento." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and 
							p.idUnidad=um.idUni and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal);
				} elseif($elije==4) {
					$umedida= $_REQUEST['lifade'];
					$consulta=$conection->query("
						select
							max(v.estatus) estatus, max(v.idVenta) idventa, max(v.fecha) fecha, p.nombre, um.compuesto filtro, max(s.nombre) sucu,
							sum(vp.cantidad) cantidad, max(vp.preciounitario) preciounitario, sum(vp.subtotal) subtotal, sum(vp.impuestosproductoventa) impuestosproductoventa,
							sum(vp.montodescuento) montodescuento, max(vp.descuento) descuento, max(vp.tipodescuento) tipodescuento, sum(vp.total) total
						from
							mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
						where
							v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and um.idUni=".$umedida." and p.idLinea=l.idLin and p.idUnidad=um.idUni and
							vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal."
						group by 
							p.nombre, um.compuesto");

				}
			}

			if($elije==1 && $fechaelije!=4 && $fechaelije!=5 && $fechaelije!=3) {
				$linea=$_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,l.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_sucursal s, mrp_unidades um
					where v.estatus=1 and l.idLin=".$linea." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and p.idUnidad=um.idUni and vp.idVenta=v.idVenta and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00'");
			} elseif($elije==2 && $fechaelije!=4 && $fechaelije!=5) {
				$familia= $_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,f.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_sucursal s, mrp_unidades um 
					where v.estatus=1 and l.idFam=f.idFam and f.idFam=".$familia." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and p.idUnidad=um.idUni and 
						vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00'");
			} elseif($elije==3 && $fechaelije!=4 && $fechaelije!=5) {
				$departamento= $_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,d.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um 
					where  v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and d.idDep=".$departamento." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and 
						p.idUnidad=um.idUni and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and 
						v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00'");
			} elseif($elije==4 && $fechaelije!=4 && $fechaelije!=5) {
				$umedida= $_REQUEST['lifade'];
				$consulta=$conection->query("
					select
						max(v.estatus) estatus, max(v.idVenta) idventa, max(v.fecha) fecha, p.nombre, um.compuesto filtro, max(s.nombre) sucu,
						sum(vp.cantidad) cantidad, max(vp.preciounitario) preciounitario, sum(vp.subtotal) subtotal, sum(vp.impuestosproductoventa) impuestosproductoventa,
						sum(vp.montodescuento) montodescuento, max(vp.descuento) descuento, max(vp.tipodescuento) tipodescuento, sum(vp.total) total
					from
						mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
					where
						v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and um.idUni=".$umedida." and p.idLinea=l.idLin and p.idUnidad=um.idUni and
						vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and
						v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00'
					group by 
						p.nombre, um.compuesto");
			}
			// echo
			// '
			// <thead>
				// <tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
					// <th align="center">ID VENTA</th>
					// <th align="center">Fecha</th>
					// <th align="center">Producto</th>
					// <th align="center">Categoria</th>
					// <th align="center">Sucursal</th>
					// <th align="center">Cantidad</th>
					// <th align="center">Precio Unitario</th>
					// <th align="center">Descuento</th>
					// <th align="center">Subtotal</th>
					// <th align="center">Monto Descuento</th>
					// <th align="center">IVA</th>
					// <th align="center">Total</th>
					//
				// </tr>
			// </thead>

			// <tbody>
			// ';
			if($consulta->num_rows>0){
				$subtotal=0;
				$total=0;
				$iva=0;
				$des=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['filtro'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.$lista['cantidad'].' </td>
						<td align="center"> '.$lista['preciounitario'].' </td>
						<td align="center"> '.$lista['descuento'].'  '.$lista['tipodescuento'].'</td>
						<td align="center"> '.$lista['subtotal'].' </td>
						<td align="center"> '.$lista['montodescuento'].' </td>
						<td align="center"> '.$lista['impuestosproductoventa'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$subtotal=$subtotal+$lista['subtotal'];
					$total=$total+$lista['total'];
					$iva=$iva+$lista['impuestosproductoventa'];
					$des=$des+$lista['montodescuento'];
				}

				echo '
				->
				<tr style="background:#c4c4c4;color: #333" aling="center"><td></td><td></td><td></td><td></td>
					<td></td><td></td><td></td><td style="font-size: 14px;font-weight:bold;font: color:">Subtotales</td>
					<td  style="font-size: 14px;font-weight:bold"><center>$'.number_format($subtotal, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold" > <center>$'.number_format($des, 2, '.', ',').'</center></td>
					<td style="font-size: 14px;font-weight:bold" > <center>$'.number_format($iva, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold"></td></tr>
					<tr style="background:#c4c4c4;color: #333">
					<td height="30%" colspan="12" align="left" style="font-size:14px; text-align: right; height: 34px; font-weight:bold"> TOTAL DE VENTA $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			} else {
				echo '->
				<tr style="background:#c4c4c4;color: #333">
					<td colspan="12" align="center" style="font-size: 14px;font-weight:bold">No se encontraron ventas que coincidieran con su búsqueda en el caso 1.<br>
					 '.$consulta.' 
					</td >
				</tr>
				';
			}
			break;
			

		case 2://CASE DE RVENTAS(LINEAS ETC)CASO DE RANGO datepicker
			$elije=$_REQUEST['elije'];
			$fechaini=$_REQUEST['fechainicio'];
			$fechafin=$_REQUEST['fin'];
			if($elije==1){
				$linea=$_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,l.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_sucursal s, mrp_unidades um
					where v.estatus=1 and l.idLin=".$linea." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and p.idUnidad=um.idUni and vp.idVenta=v.idVenta and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			} elseif($elije==2) {
				$familia= $_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,f.nombre filtro,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_sucursal s, mrp_unidades um
					where v.estatus=1 and l.idFam=f.idFam and f.idFam=".$familia." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and p.idUnidad=um.idUni and 
						vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			} elseif($elije==3){
				$departamento= $_REQUEST['lifade'];
				$consulta=$conection->query("select  v.estatus,v.idVenta,v.fecha,p.nombre,d.nombre filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
					where  v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and d.idDep=".$departamento." and p.idLinea=l.idLin and vp.idProducto=p.idProducto and 
						p.idUnidad=um.idUni and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and
						v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			} elseif($elije==4){
				$umedida= $_REQUEST['lifade'];
				$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,p.nombre,um.compuesto filtro,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_linea l,mrp_producto p,venta_producto vp, venta v,mrp_familia f,mrp_departamento d,mrp_sucursal s, mrp_unidades um
					where  v.estatus=1 and l.idFam=f.idFam and f.idDep=d.idDep and um.idUni=".$umedida." and p.idLinea=l.idLin and p.idUnidad=um.iduni and 
						vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and s.idSuc=v.idSucursal ".$sucursal." and 
						v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			}
			//
			//
			// echo
			// '
			// <tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
				// <th align="center">ID VENTA</th>
				// <th align="center">Fecha</th>
				// <th align="center">Producto</th>
				// <th align="center">Categoria</th>
				// <th align="center">Sucursal</th>
				// <th align="center">Cantidad</th>
				// <th align="center">Precio Unitario</th>
				// <th align="center">Descuento</th>
				// <th align="center">Subtotal</th>
				// <th align="center">Monto Descuento</th>
				// <th align="center">IVA</th>
				// <th align="center">Total</th>
			// </tr>';
			if($consulta->num_rows>0){
				$subtotal=0;
				$total=0;
				$iva=0;
				$des=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['filtro'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.$lista['cantidad'].' </td>
						<td align="center"> '.$lista['preciounitario'].' </td>
						<td align="center"> '.$lista['descuento'].'  '.$lista['tipodescuento'].'</td>
						<td align="center"> '.$lista['subtotal'].' </td>
						<td align="center"> '.$lista['montodescuento'].' </td>
						<td align="center"> '.$lista['impuestosproductoventa'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$subtotal=$subtotal+$lista['subtotal'];
					$total=$total+$lista['total'];
					$iva=$iva+$lista['impuestosproductoventa'];
					$des=$des+$lista['montodescuento'];
				}
				echo '->
				<tr style="background:#c4c4c4;color: #333" aling="center"><td></td><td></td><td></td><td></td>
					<td></td><td><td></td></td><td style="font-size: 14px;font-weight:bold;font: color:">Subtotales</td>
					<td  style="font-size: 14px;font-weight:bold"><center>$'.number_format($subtotal, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold" > <center>$'.number_format($des, 2, '.', ',').'</center></td>
					<td style="font-size: 14px;font-weight:bold" > <center>$'.number_format($iva, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold"></td>
				</tr>
				<tr style="background:#c4c4c4;color: #333">
					<td height="30%" colspan="12" align="left" style="font-size:14px; text-align: right;	height: 34px; font-weight:bold"> TOTAL DE VENTA $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			} else {
				echo '->
				<tr style="background:#c4c4c4;color: #333"">
					<td colspan="12" align="center" style="style="font-size: 14px;font-weight:bold">No se encontraron ventas que coincidieran con su búsqueda.</td >
				</tr>
				';
			}
			break;
			////////////////////////OTRO DOCUMENTO   DOC RVENTASPROVEEDOR///////////////////////////////////////////////////

		case 3://CASO 1 DE FECHAS AYER HOY ETC
			$fechaelije=$_REQUEST['fecha'];
			$proveedor=$_REQUEST['idprove'];
			//echo "fecha".$fechaelije;
			$fecha=date('Y-m-d ' );
			if ($fechaelije==1) {
				$fechabuscar=$fecha;//hoy
			}elseif($fechaelije==2) {
				$nuevafecha = strtotime ( '-24 hour' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//ayer
			}elseif($fechaelije==3){
				$nuevafecha = strtotime ( '-7 days' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//7 dias
				$consulta=$conection->query(" select v.estatus,v.idVenta,v.fecha,p.nombre,prove.razon_social,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_producto p,venta_producto vp, venta v,mrp_proveedor prove,mrp_sucursal s 
					where v.estatus=1 and p.idProveedor=prove.idPrv and prove.idPrv=".$proveedor." and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00'");
			} elseif($fechaelije==4) {
				$fechaini=date('Y-01-01');
				$fechafin=date('Y-12-31');
				///////////////////////////////////////////////////////////////////////
				$consulta=$conection->query(" select v.estatus,v.idVenta,v.fecha,p.nombre,prove.razon_social,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_producto p,venta_producto vp, venta v,mrp_proveedor prove,mrp_sucursal s
					where v.estatus=1 and p.idProveedor=prove.idPrv and prove.idPrv=".$proveedor." and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			} elseif($fechaelije==5) {
				$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,p.nombre,prove.razon_social,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_producto p,venta_producto vp, venta v,mrp_proveedor prove,mrp_sucursal s
					where v.estatus=1 and p.idProveedor=prove.idPrv and prove.idPrv=".$proveedor." and s.idSuc=v.idSucursal ".$sucursal." and vp.idProducto=p.idProducto and 
						vp.idVenta=v.idVenta ");
			}

			if($fechaelije!=4 && $fechaelije!=5 && $fechaelije!=3){
				$consulta=$conection->query(" select v.estatus,v.idVenta,v.fecha,p.nombre,prove.razon_social,s.nombre sucu, vp.cantidad,vp.preciounitario,vp.subtotal,
						vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
					from mrp_producto p,venta_producto vp, venta v,mrp_proveedor prove,mrp_sucursal s
					where v.estatus=1 and p.idProveedor=prove.idPrv and prove.idPrv=".$proveedor." and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00'");
			}			
			///////////////////////////////////////////////////////////////////////
			
			if($consulta->num_rows>0){
				$subtotal=0;
				$total=0;
				$iva=0;
				$des=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['razon_social'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.$lista['cantidad'].' </td>
						<td align="center"> '.$lista['preciounitario'].' </td>
						<td align="center"> '.$lista['descuento'].'  '.$lista['tipodescuento'].'</td>
						<td align="center"> '.$lista['subtotal'].' </td>
						<td align="center"> '.$lista['montodescuento'].' </td>
						<td align="center"> '.$lista['impuestosproductoventa'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$subtotal=$subtotal+$lista['subtotal'];
					$total=$total+$lista['total'];
					$iva=$iva+$lista['impuestosproductoventa'];
					$des=$des+$lista['montodescuento'];
				}
				echo '-><tr style="background:#c4c4c4;color: #333" aling="center"><td></td><td></td><td></td><td></td>
					<td></td><td></td><td></td><td style="font-size: 14px;font-weight:bold;font: color:">Subtotales</td>
					<td  style="font-size: 14px;font-weight:bold"><center>$'.number_format($subtotal, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold" > <center>$'.number_format($des, 2, '.', ',').'</center></td>
					<td style="font-size: 14px;font-weight:bold" > <center>$'.number_format($iva, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold"></td>
				</tr>
				<tr style="background:#c4c4c4;color: #333">
					<td height="30%" colspan="12" align="left" style="font-size:14px; text-align: right;	height: 34px; font-weight:bold"> TOTAL DE VENTA $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			} else {
				echo '->
				<tr style="background:#c4c4c4;color: #333"">
					<td colspan="12" align="center" style="style="font-size: 14px;font-weight:bold"> No se encontraron ventas que coincidieran con su búsqueda.</td >
				</tr>
				';
			}
			break;

		case 4://CASO 2 DE DATEPICKER
			$fechaini=$_REQUEST['fechainicio'];
			$fechafin=$_REQUEST['fin'];
			$proveedor=$_REQUEST['idprove'];
			$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,p.nombre,prove.razon_social,s.nombre sucu,vp.cantidad,vp.preciounitario,vp.subtotal,
					vp.impuestosproductoventa,vp.montodescuento,vp.descuento,vp.tipodescuento, vp.total
				from mrp_producto p,venta_producto vp, venta v,mrp_proveedor prove,mrp_sucursal s
				where v.estatus=1 and p.idProveedor=prove.idPrv and prove.idPrv=".$proveedor." and vp.idProducto=p.idProducto and vp.idVenta=v.idVenta and 
					s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00'");
			if($consulta->num_rows>0){
				$subtotal=0;
				$total=0;
				$iva=0;
				$des=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['razon_social'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.$lista['cantidad'].' </td>
						<td align="center"> '.$lista['preciounitario'].' </td>
						<td align="center"> '.$lista['descuento'].'  '.$lista['tipodescuento'].'</td>
						<td align="center"> '.$lista['subtotal'].' </td>
						<td align="center"> '.$lista['montodescuento'].' </td>
						<td align="center"> '.$lista['impuestosproductoventa'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$subtotal=$subtotal+$lista['subtotal'];
					$total=$total+$lista['total'];
					$iva=$iva+$lista['impuestosproductoventa'];
					$des=$des+$lista['montodescuento'];
				}
				echo '-><tr style="background:#c4c4c4;color: #333" aling="center"><td></td><td></td><td></td><td></td>
					<td></td><td></td><td></td><td style="font-size: 14px;font-weight:bold;font: color:">Subtotales</td>
					<td  style="font-size: 14px;font-weight:bold"><center>$'.number_format($subtotal, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold" > <center>$'.number_format($des, 2, '.', ',').'</center></td>
					<td style="font-size: 14px;font-weight:bold" > <center>$'.number_format($iva, 2, '.', ',').'</center></td>
					<td  style="font-size: 14px;font-weight:bold"></td>
				</tr>
				<tr style="background:#c4c4c4;color: #333">
					<td height="30%" colspan="12" align="left" style="font-size:14px; text-align: right;	height: 34px; font-weight:bold"> TOTAL DE VENTA $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			}else{
				echo '->
				<tr style="background:#c4c4c4;color: #333"">
					<td colspan="12" align="center" style="style="font-size: 14px;font-weight:bold">No se encontraron ventas que coincidieran con su búsqueda.</td >
				</tr>
				';
			}
			break;

			////////////////////////////termina doc rventas proveedor///////////////////////////////
					////////////////////////////////////////////////////////////////////
			/////////////////////INICIA DOC RVENTAPAGOS/////////////////////////////////////////////
		case 5:
			$fechaelije=$_REQUEST['fecha'];
			$forma=$_REQUEST['idforma'];
			//echo "fecha".$fechaelije;
			$fecha=date('Y-m-d ' );
			if ($fechaelije==1) {
				$fechabuscar=$fecha;//hoy
			}elseif($fechaelije==2){
				$nuevafecha = strtotime ( '-24 hour' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//ayer
			}elseif($fechaelije==3){
				$nuevafecha = strtotime ( '-7 days' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-d ' , $nuevafecha );
				$fechabuscar=$nuevafecha;//7 dias
				$consulta=$conection->query(" select v.estatus,v.idVenta,v.fecha,s.nombre sucu,f.nombre,(case vp.idFormapago WHEN 1 THEN (vp.monto-v.cambio) ELSE vp.monto END) monto,
						(select sum(vpro.total) from venta_producto vpro where vpro.idVenta=v.idVenta) total
					from venta_pagos vp,venta v,forma_pago f,venta_producto vpro,mrp_sucursal s
					where v.estatus=1 and vp.idVenta=v.idVenta and f.idFormapago=vp.idFormapago and vpro.idVenta=v.idVenta and f.idFormapago=".$forma." and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fecha." 23:00:00' GROUP  BY v.idVenta");
			}elseif($fechaelije==4){
				$fechaini=date('Y-01-01');
				$fechafin=date('Y-12-31');
				///////////////////////////////////////////////////////////////////////

				$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,s.nombre sucu,f.nombre,(case vp.idFormapago WHEN 1 THEN (vp.monto-v.cambio) ELSE vp.monto END) monto,
						(select sum(vpro.total) from venta_producto vpro where vpro.idVenta=v.idVenta)  total 
					from venta_pagos vp,venta v,forma_pago f,venta_producto vpro,mrp_sucursal s
					where v.estatus=1 and vp.idVenta=v.idVenta and f.idFormapago=vp.idFormapago and vpro.idVenta=v.idVenta and f.idFormapago=".$forma." and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00' GROUP  BY v.idVenta");
			} elseif($fechaelije==5) {
				$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,s.nombre sucu,f.nombre,(case vp.idFormapago WHEN 1 THEN (vp.monto-v.cambio) ELSE vp.monto END) monto,
						(select sum(vpro.total) from venta_producto vpro where vpro.idVenta=v.idVenta)  total 
					from venta_pagos vp,venta v,forma_pago f,venta_producto vpro,mrp_sucursal s
					where v.estatus=1 and vp.idVenta=v.idVenta and f.idFormapago=vp.idFormapago and s.idSuc=v.idSucursal ".$sucursal." and vpro.idVenta=v.idVenta and 
						f.idFormapago=".$forma."  GROUP  BY v.idVenta ");
			} if($fechaelije!=4 && $fechaelije!=5 && $fechaelije!=3) {
				$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,s.nombre sucu,f.nombre,(case vp.idFormapago WHEN 1 THEN (vp.monto-v.cambio) ELSE vp.monto END) monto,
						(select sum(vpro.total) from venta_producto vpro where vpro.idVenta=v.idVenta)  total 
					from venta_pagos vp,venta v,forma_pago f,venta_producto vpro,mrp_sucursal s
					where v.estatus=1 and vp.idVenta=v.idVenta and f.idFormapago=vp.idFormapago and vpro.idVenta=v.idVenta and f.idFormapago=".$forma." and 
						s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechabuscar." 1:00:00' and '".$fechabuscar." 23:00:00' GROUP  BY v.idVenta");
			}
			///////////////////////////////////////////////////////////////////////
			//
			// echo
			// '
			// <tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
				// <th align="center">ID VENTA</th>
				// <th align="center">Fecha</th>
				// <th align="center">Sucursal</th>
				// <th align="center">Forma de Pago</th>
				// <th align="center">Monto pagado</th>
				// <th align="center">Total de Venta</th>
			// </tr>';
			if($consulta->num_rows>0){
				$total=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['monto'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$total=$total+$lista['monto'];
				}
				echo '->
				<tr style="background:#c4c4c4;color: #333" >
					<td height="30%" colspan="6"  style="font-size:14px; text-align: center;	height: 34px; font-weight:bold"> TOTAL DE FORMA DE PAGO $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			}else{
				echo '->
				<tr style="background:#c4c4c4;color: #333" >
					<td colspan="6" align="center" style="style="font-size: 14px;font-weight:bold">No se encontraron ventas que coincidieran con su búsqueda.</td >
				</tr>
				';
			}
			break;

		case 6:
			$fechaini=$_REQUEST['fechainicio'];
			$fechafin=$_REQUEST['fin'];
			$forma=$_REQUEST['idforma'];

			$consulta=$conection->query("select v.estatus,v.idVenta,v.fecha,s.nombre sucu,f.nombre,(case vp.idFormapago WHEN 1 THEN (vp.monto-v.cambio) ELSE vp.monto END) monto,
					(select sum(vpro.total) from venta_producto vpro where vpro.idVenta=v.idVenta) total  
				from venta_pagos vp,venta v,forma_pago f,venta_producto vpro,mrp_sucursal s
				where v.estatus=1 and vp.idVenta=v.idVenta and f.idFormapago=vp.idFormapago and vpro.idVenta=v.idVenta and f.idFormapago=".$forma." and 
					s.idSuc=v.idSucursal ".$sucursal." and v.fecha BETWEEN '".$fechaini." 1:00:00' and '".$fechafin." 23:00:00' GROUP  BY v.idVenta");
			if($consulta->num_rows>0){
				$total=0;
				while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
					echo '
					<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
						<td align="center"> '.$lista['idVenta'].' </td >
						<td align="center"> '.$lista['fecha'].' </td>
						<td align="center"> '.$lista['sucu'].' </td>
						<td align="center"> '.utf8_encode($lista['nombre']).' </td>
						<td align="center"> '.$lista['monto'].' </td>
						<td align="center"> '.$lista['total'].' </td>
					</tr>';
					$total=$total+$lista['monto'];
				}
				echo '
				->
				<tr style="background:#c4c4c4;color: #333" align="center">
					<td height="30%" colspan="6" align="center" style="font-size:14px; text-align: center;	height: 34px; font-weight:bold"> TOTAL DE FORMA DE PAGO $'.number_format($total, 2, '.', ',').'</td >
				</tr>
				';
			}else{
				echo '->
				<tr style="background:#c4c4c4;color: #333"">
					<td colspan="6" align="center" style="font-size: 14px;font-weight:bold">No se encontraron ventas que coincidieran con su búsqueda.</td >
				</tr>
				';
			}
			break;
			////////////////////////////////////////////////////////////////////////////////////////////
	}	
?>