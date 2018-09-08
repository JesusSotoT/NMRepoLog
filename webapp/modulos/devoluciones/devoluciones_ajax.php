<?php
	error_reporting(0);
	include("../../netwarelog/webconfig.php");
	$mysqli = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	if(isset($_POST["accion"])){
		switch($_POST["accion"]){
			case 'devolver':
				if(isset($_POST['idProducto']) && isset($_POST['idAlmacen']) && isset($_POST['values'])){
					if($_POST['idProducto']!="" && $_POST['idAlmacen']!="" && $_POST['values']!=""){
						$idProducto=explode(",",$_POST['idProducto']);	
						$idAlmacen=explode(",",$_POST['idAlmacen']);
						$values=explode(",",$_POST['values']);
						$idProveedor=$_POST['proveedor'];
						$valuesc=count($values);
						//$result = $mysqli->query("select a.idProveedor, a.idProducto, c.cantidad-if(SUM(d.nDevoluciones) is null,0,SUM(d.nDevoluciones)) cantidad, a.nombre from mrp_producto a inner join mrp_proveedor b on a.idProveedor=b.idPrv inner join mrp_stock c on c.idProducto=a.idProducto left join mrp_devoluciones_reporte d on d.idProducto=c.idProducto and d.idProveedor=a.idProveedor and d.idAlmacen=c.idAlmacen and d.estatus=0 where a.idProducto in(".$_POST['ids'].") and c.idAlmacen=".$_POST['almacen']." group by a.idProducto order by a.idProducto");

						for($contador=0;$contador<$valuesc;$contador++){
							$content=" Los siguientes productos exceden las cantidades:"." \n";
							$multiinsert='';
							$flag=0;
							$result = $mysqli->query("select e.idPrv, a.idProducto, a.idunidadCompra, c.idUnidad, ROUND(c.cantidad-if(SUM(d.nDevoluciones) is null,0,SUM(d.nDevoluciones)),2) cantidad, a.nombre, c.idAlmacen from mrp_producto a inner join mrp_stock c on c.idProducto=a.idProducto inner join mrp_producto_proveedor e on e.idProducto=c.idProducto left join mrp_devoluciones_reporte d on d.idProducto=c.idProducto and d.idProveedor=e.idPrv and d.idAlmacen=c.idAlmacen and d.estatus=0 where a.idProducto=".$idProducto[$contador]." and c.idAlmacen=".$idAlmacen[$contador]." and e.idPrv=".$idProveedor." order by a.idProducto");
							
							if($result->num_rows>0){
								$row=$result->fetch_assoc();
								date_default_timezone_set("Mexico/General");
								//.								
								$result1 = $mysqli->query("select conversion from mrp_unidades where idUni = ".$row['idUnidad']."");
								$row1=$result1->fetch_assoc();
								$result2 = $mysqli->query("select conversion from mrp_unidades where idUni = ".$row['idunidadCompra']."");
								$row2=$result2->fetch_assoc();
								$conversion = ($row2['conversion'] / $row1['conversion']);
								if($row['idUnidad'] == $row['idunidadCompra']){
										$cantidadaux = $row['cantidad']; 
								}else{
										$cantidadaux = $row['cantidad'] / $conversion ;
								}
								//.
								if($cantidadaux>=$values[$contador]){
									
									if($row['idUnidad'] == $row['idunidadCompra']){
										$valuescontador = $values[$contador];
									}else{
										$valuescontador = $values[$contador] * $conversion;
									}
									//. $values[$contador] -- es la variable que se ingresaba normalmente y se cambio por $valuescontador
									$multiinsert.="('".$valuescontador."','".$row['idProducto']."','".$row['idPrv']."','".$row['idAlmacen']."','".date("Y-m-d H:i:s")."'),";
								}
								else{
									$content.='* '.$row['nombre']." \n";
									$flag=1;
									echo $content;	
								}
							}
							if($flag==0){
							//echo $multiinsert;
							$multiinsert=trim($multiinsert,",");
							$result1 = $mysqli->query("INSERT INTO mrp_devoluciones_reporte (nDevoluciones,idProducto,idProveedor,idAlmacen,fechaDevolucion) VALUES ".$multiinsert."");
							$content="Se han mandado a buzon de devoluciones los productos";
						}//else
						}// for
						
						//	echo "No se pueden devolver mas productos de los almacenados";
						
						echo $content;
					}else
						echo "No hay Devoluciones";
				}
			break;
				
			case 'confirmar':
				$result = $mysqli->query("select nDevoluciones, idProducto, idAlmacen from mrp_devoluciones_reporte where id=".$_POST['id']);
				if($result->num_rows>0){
					$row=$result->fetch_assoc();
					$result = $mysqli->query("UPDATE mrp_stock SET cantidad=cantidad-".$row['nDevoluciones']." WHERE idProducto=".$row['idProducto']." and idAlmacen=".$row['idAlmacen']);
					$result = $mysqli->query("UPDATE mrp_devoluciones_reporte set estatus=1 where id=".$_POST['id']);
					echo "Se ha devuelto el producto correctamente";
				}
			break;
			
			case 'cancelar':
				$result = $mysqli->query("UPDATE mrp_devoluciones_reporte set estatus=2 where id=".$_POST['id']);
				echo "Se ha cancelado la devolucion";
			break;
			
			case 'listar': 
				$query="";
				if($_POST['idAlmacen']!=0)
					$query=" and a.idAlmacen=".$_POST['idAlmacen'];
				$result1 = $mysqli->query("select a.idProducto, a.cantidad, b.nombre, a.idUnidad, b.idunidadCompra, ROUND(e.costo,2) costo, a.idAlmacen, ROUND(if(SUM(c.valor) is null,0,((SUM(c.valor)/100)*e.costo)),2) impuesto, if(".$_POST['idAlmacen']."=0,CONCAT(\"(\",d.nombre,\")\"),\"\") almacen from mrp_stock a inner join mrp_producto b on b.idProducto=a.idProducto left join producto_impuesto c on c.idProducto=a.idProducto inner join almacen d on d.idAlmacen=a.idAlmacen inner join mrp_producto_proveedor e on e.idProducto=a.idProducto where e.idPrv=".$_POST['idProveedor'].$query." group by e.idProducto");
				if($result1->num_rows>0){
					//$total=0;
					//$impuestos=0;
					$contador=0;
					$cont=0;
					$cant=0;
					$costo2 =0;
					while($row1=$result1->fetch_assoc()){
						$result2 = $mysqli->query("select sum(nDevoluciones) cantidad from mrp_devoluciones_reporte where idProducto=".$row1['idProducto']." and idProveedor=".$_POST['idProveedor']." and idAlmacen=".$row1['idAlmacen']." and estatus=0");
						if($result2->num_rows>0){	
							$row2=$result2->fetch_assoc();
							$row1['cantidad']-=$row2['cantidad'];
						} 
					    ////////////////////////////////////////////////////////////////////////////////////////////////
						/// obtenemos el valor de conversion mediante el idUni que sea igual al idUnidad(compra)
						$result3 = $mysqli->query("select conversion from mrp_unidades where idUni = ".$row1['idUnidad']."");
						$row3=$result3->fetch_assoc();
						/// obtenemos el valor de conversion mediante el idUni que sea igual al idunidadCompra(provedor)
						$result4 = $mysqli->query("select conversion from mrp_unidades where idUni = ".$row1['idunidadCompra']."");
						$row4=$result4->fetch_assoc();
						
						$result5 = $mysqli->query("select compuesto from mrp_unidades where idUni = ".$row1['idunidadCompra']."");
						$row5=$result5->fetch_assoc();

						$result6 = $mysqli->query("select compuesto from mrp_unidades where idUni = ".$row1['idUnidad']."");
						$row6=$result6->fetch_assoc();

						$result7 = $mysqli->query("select costo from mrp_producto_proveedor where idProducto = ".$row1['idProducto']."");
						$row7=$result7->fetch_assoc();
						$costo = $row7['costo'];

						if($row1['idUnidad'] == $row1['idunidadCompra']){
							$costo2 = $row1['costo'];
							$cant = $row1['cantidad'];
						}else{
							$cant= $row1['cantidad'] * ($row3['conversion'] / $row4['conversion']);
							//$costo2 = $row1['costo'] * $row3['conversion'];
						}
						////////////////////////////////////////////////////////////////////////////////////////////////
						 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
						{
    						$color='nmcatalogbusquedacont_1 busqueda_fila';
						}
						else//Si es impar pinta esto
						{
    						$color='nmcatalogbusquedacont_2 busqueda_fila';
						}
							$cont++;
						//$subtotal=round($row1['cantidad']*$row1['costo'],2);
						$content.='<tr class="'.$color.'">
							<td align="center"><input id="cantidad_7" readonly="" value="'.$cant.'" class="form-control" maxlength="8"></td>
							<td align="center">'.$row5['compuesto'].'</td>
							<td align="center"><input type="text" onpaste="return false;" onkeypress="return justNumbers(event);" class="textDev form-control" value="0.00" producto="'.$row1['idProducto'].'" almacen="'.$row1['idAlmacen'].'" impuestos=" '.$row1['impuesto'].'" costo="'.$costo.'" cantidad=" '.$row1['cantidad'].'" idpu="precioU'.$contador.'"/></td>
							<td align="center">'.utf8_encode($row1['nombre']." ".$row1['almacen']).'</td>
							<td align="center">$<input maxlength="10" value="'.$costo.'" class="float form-control" id="costo_7" readonly=""></td>
							<td align="center">$<span id="precioU'.$contador.'">0.00</span></td>
							</tr>';
			 			//$total+=$subtotal;
		  				//$impuestos=$impuestos+($row1['impuesto']*$row1['cantidad']);
						$contador++;
					}//fin while
					//$impuestos=round($impuestos,2);
					//$totalIva=round($impuestos+$total,2);
					$content.='<tr class="'.$color.'">
					<td></td>
					<td></td>
					<td></td>
					<td align="right"><strong>Neto:</strong></td>
					<td align="center">$<span id="neto">0.00</span></td>
					</tr>
					<tr class="'.$color.'">
					<td></td>
					<td></td>
					<td></td>
					<td align="right"><strong>Impuestos:</strong></td>
					<td align="center">$<span id="iva">0.00</span></td>
					</tr>
					<tr class="'.$color.'">
					<td></td>
					<td></td>
					<td><input type="hidden" value="7*10*8*" id="ids"></td>
					<td align="right"><strong>Total:</strong></td>
					<td align="center">$<span id="totalIva">0.00</span></td>
					</tr>';
				}
				echo $content;
			break;
		}
		mysqli_close($connection);
	}
?>