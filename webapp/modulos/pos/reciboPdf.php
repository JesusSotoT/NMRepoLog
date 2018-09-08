<?php
	include "../SAT/PDF/html2pdf/html2pdf.class.php";
	include "../SAT/PDF/phpqrcode/qrlib.php";
	include "../SAT/PDF/EnLetras.php";
	include("controllers/caja.php");

	session_start();
	error_reporting(0);

	$data=$_SESSION['ticketventaenv'];
	$idventa=$_REQUEST["idventa"];
	$print = $_REQUEST["print"];

	// Cargando los datos del Ticket de Venta
	$cajaController = new Caja;
	$organizacion = $cajaController->datosorganizacion();
	$venta = $cajaController->datosventa($idventa);
	$cliente = $cajaController->datoscliente($venta[0]['idCliente']);
	$datosSucursal = $cajaController->datosSucursal($idventa);
	$productos=$cajaController->productosventa($idventa);
	$impuestos_venta = json_decode($venta[0]['jsonImpuestos']);
	$impuestos_venta = $cajaController->object_to_array($impuestos_venta);
	$pagos = $cajaController->pagos($idventa);
	$configTikcet = $cajaController->configTikcet();
	$color = "#D8D8D8";

//	function ponerColor($color){
//		$this->color=$color;
//	}

	// Generando el código QR para el Recibo
	unlink('images/qrventas/qrticket.png');
	$texto="netwarmonitor.mx/clientes/".$_SESSION['accelog_nombre_instancia']."/kiosko";
	$err = creaQR($texto,$idventa);

	function creaQR($texto,$idventa) {
		$ruta = 'images/qrventas/qrticket.png';
		QRcode::png($texto, $ruta);
		return $ruta;
	}
//	$codigoQrVenta = '<tr><td align="center"><img src="'.$err.'" alt=""></td></tr>';
	$codigoQrVenta = '<img style="-webkit-user-select:none" src="'.$err.'" alt="">';

	// Cargando el logo tipop o imagen de la empresa
	$imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion[0]['logoempresa'];
	$imagesize=getimagesize($imagen);
	$porcentaje=0;
	if($imagesize[0]>200 && $imagesize[1]>90){
		if($imagesize[0]>$imagesize[1]){
			$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
			$imagesize[0]=200;
			$imagesize[1]=(($porcentaje*200)/100);
		}else{
			$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
			$imagesize[0]=200;
			$imagesize[1]=(($porcentaje*200)/100);
		}
	}

	$src="";
	if($imagen!="" && file_exists($imagen))
		$src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px;display:block;margin:0 auto 0 auto;"/>';

	$nimps=explode('<div class="row">', $data['imps']);
	$table='<table>';
		foreach ($nimps as $kk => $vv) {
			if($vv!=''){
				$table.='<tr>';
				$nimps2=explode('<div class="col-sm-6"><label>', $vv);

				foreach ($nimps2 as $kkk => $vvv) {
					$vvv=preg_replace('/(<\/label><\/div>)/', '', $vvv);
					$vvv=preg_replace('/(<\/div>)/', '', $vvv);
					$table.='<td style="text-align:right;">'.$vvv.'</td>';
				}
				$table.='</tr>';
			}
		}
	$table.='</table>';
    $obj=new EnLetras();
    $total_letra=strtoupper($obj->ValorEnLetras($venta[0]['monto'],'pesos','M.N.'));

	$html='
	<style>
		@font-face {
			font-family: SourceSansPro;
			src: url(SourceSansPro-Regular.ttf);
		}

		.clearfix:after {
			content: "";
			display: table;
			clear: both;
		}

		a {
			color: #0087C3;
			text-decoration: none;
		}

		body {
			position: relative;
			width: 19cm;
			height: 29.7cm;
			margin: 0 auto;
			color: #555555;
			background: #FFFFFF;
			font-family: Arial, sans-serif;
			font-size: 10px;
			font-family: SourceSansPro;
		}

		header {
			padding: 10px 0;
			margin-bottom: 20px;
			border-bottom: 1px solid #AAAAAA;
		}

		#logo {
			float: left;
			margin-top: 8px;
		}

		#logo img {
			height: 70px;
		}

		#company {
			float: right;
			text-align: right;
		}

		#details {
			margin-bottom: 50px;
		}

		#client {
			padding-left: 6px;
			border-left: 6px solid #0087C3;
			float: left;
		}

		#client .to {
			color: #777777;
		}

		h2.name {
			font-size: 1.4em;
			font-weight: normal;
			margin: 0;
		}

		#invoice {
			float: right;
			text-align: right;
		}

		label{
			width:150px;
		}

		#invoice h1 {
			color: #0087C3;
			font-size: 2.4em;
			line-height: 1em;
			font-weight: normal;
			margin: 0  0 10px 0;
		}

		#invoice .date {
			font-size: 1.1em;
			color: #777777;
		} 

		table {
			width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 20px;
			font-size:12px;
		}

		table th,
		table td {
			padding: 8px;
			background: #EEEEEE;
			text-align: center;
			border-bottom: 1px solid #FFFFFF;
		}

		table th {
			white-space: nowrap;
			font-weight: bold;
		}

		table td {
			text-align: right;
		}

		table td h3{
			color: #57B223;
			font-size: 1.2em;
			font-weight: normal;
			margin: 0 0 0.2em 0;
		}

		table .no {
			color: #000;
			background: #DDDDDD;
		}

		table .desc {
			text-align: left;
		}

		table .unit {
			background: #DDDDDD;
		}

		table .qty {
		}

		table .total {
			background: #333;
			color: #FFFFFF;
		}

		table td {word-wrap:break-word;}

		table td.unit,
		table td.qty,
		table td.total {
			font-size: 1.2em;
		}

		table tbody tr:last-child td {
			border: none;
		}

		table tfoot td {
			padding: 10px 20px;
			background: #FFFFFF;
			border-bottom: none;
			font-size: 1.2em;
			white-space: nowrap;
			border-top: 1px solid #AAAAAA;
		}

		table tfoot tr:first-child td {
			border-top: none;
		}

		table tfoot tr:last-child td {
			color: #57B223;
			font-size: 1.4em;
			border-top: 1px solid #57B223;
		}

		table tfoot tr td:first-child {
			border: none;
		}

		#thanks{
			font-size: 2em;
			margin-bottom: 50px;
		}

		#notices {
			padding-left: 6px;
			border-left: 6px solid #0087C3;
		}

		#notices .notice {
			font-size: 1.2em;
		}

		footer {
			color: #777777;
			width: 100%;
			height: 30px;
			position: absolute;
			bottom: 0;
			border-top: 1px solid #AAAAAA;
			padding: 8px 0;
			text-align: center;
		}
@page {
    size: A4;
    margin: 0;
}
@media print .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    
}
	</style> ';

	$htmlN = '
	<html>
		<head>
			<title>Recibo de pago</title>
			<meta charset="utf-8">
			<style type="text/css" media="screen">
@page {
    size: A5;
    margin: 0;
}
@media print {
	.page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
}
			</style>
		</head>

		<body>
			<table>
				<tr>
					<td><div style="width:100px;height:20px">'.$src.'</div></td>
					<td>
						<div style="margin-left:76px;" >
							<table>
								<tr> <td style="text-align: center;">RECIBO DE PAGO</td> </tr>
								<tr>
									<td>
										<div style="margin-left:12px">
											<table text-align="center">
												<tr><td style="width:400px;font-size:14px; text-align:center;"><b>'.$organizacion[0]['nombreorganizacion'].'</b></td></tr>
												<tr><td style="width:90px;font-size:11px; text-align:center;">'.$organizacion[0]['RFC'].'</td></tr>
												<tr><td style="width:218px;font-size:10px; text-align:center;">'.$organizacion[0]['domicilio'].'</td></tr>
												<tr><td style="width:218px;font-size:10px; text-align:center;">'.$organizacion[0]['colonia'].', C.P.'.$organizacion[0]['cp'].'</td></tr>
												<tr><td style="width:138px;font-size:10px; text-align:center;">'.$organizacion[0]['municipio'].', '.$organizacion[0]['estado'].'</td></tr>
											</table>
										</div>
									</td>
									<td>
										<div style="margin-left:5px">
											<table>
												<tr><td style="font-weight:bold;font-size:20px">Folio</td></tr>
												<tr><td style="font-size:28px;">'.$venta[0]["recibo"].'</td></tr>
											</table>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<br />

			<div style="font-size:10px;margin-bottom:15px">Fecha de expedición: '.$venta[0]['fecha'].'</div>

			<div style="font-weight:bold;font-size:11px;background:'.$color.'">DATOS CLIENTE RECEPTOR:</div></td></tr>
				<table style="border:1px '.$color.'">
					<tr>
						<td>
							<table>
								<tr>
									<td style="font-size:10px">Nombre:</td>
									<td style="font-size:10px;width:347px;">'.$venta[0]['cliente'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">Direcci&oacute;n:</td>
									<td style="font-size:10px">'.$cliente[0]['direccion'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">Colonia:</td>
									<td style="font-size:10px">'.$cliente[0]['colonia'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">Ciudad:</td>
									<td style="font-size:10px">'.$cliente[0]['ciudad'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">Estado:</td>
									<td style="font-size:10px">'.$cliente[0]['estado'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">C.P.:</td>
									<td style="font-size:10px">'.$cliente[0]['cp'].'</td>
								</tr>

								<tr>
									<td style="font-size:10px">R.F.C.:</td>
									<td style="font-size:10px;">'.$cliente[0]['rfc'].'</td>
								</tr>
							</table>
						</td>
						<td style="font-size:8px;width:392px;border-left:1px dashed '.$color.';padding-left:7px">
							<div style="font-weight:bold;font-size:10px;background:'.$color.';width:307px;margin-top:0px">Recib&iacute;:________________________________________________</div>
							<div style="font-weight:bold;font-size:10px;background:'.$color.';width:307px;margin-top:0px">Firma:_____________________ Fecha: _____________________ </div>

							<div style="font-weight:bold;font-size:10px;background:'.$color.';width:307px;margin-top:0px"></div>

							<div style="font-weight:bold;font-size:10px;background:'.$color.';width:307px;margin-top:0px">Periodo a declarar:______________________________________</div>

						</td>
					</tr>
				</table>
			</div>

			<div>
				<table style="border:1px '.$color.'">
					<tr>
						<td style="font-size:10px"> </td>
						<td style="width:670px;font-size:10px">  </td>
					</tr>
				</table>
			</div>

			<div>
				<table>
					<tr>
						<td style="font-weight:bold;font-size:11px;background:'.$color.';width:553px">Descripción</td>
						<td style="font-weight:bold;font-size:11px;background:'.$color.';width:173px">Importe</td>
					</tr>
				</table>
			</div>';	

	$htmlC = '
			<div>
				<table > ';
					$ptotal = 0; 
					foreach ($productos as $k => $v) {
						$ptotal += ($v['preciounitario']*$v['cantidad']);
						$htmlC.='
						<tr>
							<td style="font-size:11px;border-bottom:2px dashed ;border-right:2px dashed ;width:550px">'.$v['nombre'].'</td>
							<td style="font-size:11px;border-bottom:2px dashed ;width:170px;text-align:right">'.number_format((float)($v['preciounitario']*$v['cantidad']),2,'.',',').'</td>
						</tr> ';
					}
	
					$totalimpuestos = 0;
					foreach ($impuestos_venta as $key2 => $value2) {
						if ($value2 <> 0 ) {
							$imprimeimpuestos.='
								<tr>
									<td style="font-weight:bold;font-size:11px;background:'.$color.';width:453px; text-align:right">'.$key2.'</td>
									<td style="font-weight:bold;font-size:11px;background:'.$color.';width:153px; text-align:right"> $ '.number_format((float)$value2,2,'.',',').'</td>
								</tr>';
						}
						$totalimpuestos+=$value2;
					}
					$htmlC.= '
				</table>
			</div>';

	if ($totalimpuestos == 0 ) {
		$imprimesubtotal = '';
		$imprimeimpuestos = '';
	} else {
		$imprimesubtotal = '
			<tr>
				<td style="font-weight:bold;font-size:11px;background:'.$color.';width:453px; text-align:right">SUBTOTAL</td>
				<td style="font-weight:bold;font-size:11px;background:'.$color.';width:153px; text-align:right"> $ '.number_format((float)$ptotal,2,'.',',').'</td>
			</tr>';
	}

	$htmlX = '
			<div>
				<table>
					<tr>
						<td style="font-size:8px;width:102px">' . $codigoQrVenta . '</td>

						<td style="font-size:11px;">
							<table>'.$imprimesubtotal.$imprimeimpuestos.'<tr>
									<td style="font-weight:bold;font-size:11px;background:'.$color.';width:453px; text-align:right">TOTAL</td>
									<td style="font-weight:bold;font-size:11px;background:'.$color.';width:153px; text-align:right"> $ '.number_format((float)$venta[0]['monto'],2,'.',',').'</td>
								</tr>
								<tr>
									<td style="font-size:11px;border-bottom:0px '.$color.';padding-left:30px"></td>
									<td style="font-size:11px;border-bottom:0px '.$color.';padding-left:5px"></td>
								</tr>

								<tr>
									<td style="font-size:12px;border-bottom:1px dashed '.$color.';padding-left:5px">Total en letra: ('.$total_letra.')</td>
									<td style="font-size:11px;border-bottom:1px dashed '.$color.';border-right:1px dashed '.$color.';padding-left:30px;padding-right:10px"></td>
								</tr>
							</table>
						</td>
					</tr>					
				</table>
			</div>

			<div style="font-size:8px;padding-left:100px">
				<table>
					<tr>
						<td></td>
						<td style="padding-left:50px;border-collapse:collapse"></td>
					</tr>
				</table>
			</div>
		</body>
	</html>'; 

	

	$_SESSION['ticketventaenv']='';
	unset($_SESSION['ticketventaenv']);
	//echo $htmlN.$htmlC.$htmlX;
?>

	<!DOCTYPE html>
	<html>
	<head>
		<title></title>
		<style>
			p {
				margin: 1px;
			}
		</style>
	</head>
	<body>
		<table style="width: 100%" >
			<caption>
				
			</caption>

			<thead>
				<tr>
					<td colspan="2" style="width:100px;height:20px">
						<?php echo $src ?>
						<!-- <img src="'<?php echo $imagen; ?>'" style="width:'<?php echo $imagesize[0]; ?>'px;height:'<?php echo $imagesize[1]; ?>'px;display:block;margin:0 auto 0 auto;"/> -->
					</td>
					<td colspan="2" >
						<p style="text-align:center; font-weight: bold;">RECIBO DE PAGO</p>
						<p style="text-align:center;"><?php echo $organizacion[0]['nombreorganizacion'] ?></p>
						<p style="text-align:center;"><?php echo $organizacion[0]['RFC'] ?></p>
						<p style="text-align:center;"><?php echo $organizacion[0]['domicilio'] ?></p>
						<p style="text-align:center;"><?php echo $organizacion[0]['colonia'].', C.P.'.$organizacion[0]['cp'] ?></p>
						<p style="text-align:center;"><?php echo $organizacion[0]['municipio'].', '.$organizacion[0]['estado'] ?></p>
					</td>
					<td colspan="1">
						<p style="margin-bottom:15px"></p>
						<p style="font-weight:bold;font-size:20px; text-align:center;">Folio</p>
						<p style="font-size:28px; text-align:center;"> <?php echo $venta[0]["recibo"] ?></p>
						<p style="margin-bottom:15px"></p>
						<p style="text-align:center;">Fecha de expedición: </p>
						<p style="font-size:10px; text-align:center;"><?php echo $venta[0]['fecha'] ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="font-weight:bold; margin-top:18px"></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2" style="font-weight:bold; margin-top:12px">DATOS CLIENTE RECEPTOR:</td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td>Nombre: <span style="font-size: 80% "><?php echo $venta[0]['cliente'] ?></span> </td>
					<td>RFC: <span style="font-size: 80% "><?php echo $venta[0]['rfc'] ?></span> </td>
					<td colspan="3" style="border-bottom: 1px solid black;">Recibí: </td>
				</tr>
				<tr>
					<td colspan="2">Dirección: <span style="font-size: 80% "><?php echo $cliente[0]['direccion'] ?></span></td>
					<td colspan="3" style="border-bottom: 1px solid black;">Fecha: </td>
				</tr>
				<tr>
					<td>Colonia: <span style="font-size: 80% "><?php echo $cliente[0]['colonia'] ?></span></td>
					<td>C.P: <span style="font-size: 80% "><?php echo $cliente[0]['cp'] ?></span></td>
					<td colspan="3" style="border-bottom: 1px solid black;">Periodo a declarar: </td>
				</tr>
				<tr>
					<td>Ciudad: <span style="font-size: 80% "><?php echo $cliente[0]['ciudad'] ?></span></td>
					<td>Estado: <span style="font-size: 80% "><?php echo $cliente[0]['estado'] ?></span></td>
					<td rowspan="2" colspan="3" style="border-bottom: 1px solid black;">Firma: </td>
				</tr>
				<tr style="height: 18px;">
				</tr>
			</tbody>
		</table>

		<table style="width: 100%" >
			<thead>
				<tr style="background-color: #CCC">
					<th colspan="2" style="width: 70%; text-align:center;">Descripción</th>
					<th style="width: 30%; text-align:center;">Importe</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($productos as $k => $v) {?>
					<tr style="background-color: #EEE; border-bottom: 1px solid black; ">
						<td colspan="2" style="width: 70%; text-align:left;"><?php echo $v['nombre'] ?></td>
						<td style="width: 30%; text-align:right;"><?php echo number_format((float)($v['preciounitario']*$v['cantidad']),2,'.',',') ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td rowspan="5" style="width: 40%; text-align:left;"><?php echo $codigoQrVenta; ?></td>
					<td style="width: 30%; text-align:left;"></td>
					<td style="width: 30%; text-align:right;"></td>
				</tr>




				<tr>
						<td style="width: 30%; text-align:left;"><?php echo "Subtotal: "; ?></td>
						<td style="width: 30%; text-align:right;"><?php echo number_format((float)$ptotal,2,'.',','); ?></td>
					</tr>
				<?php 
				$totalimpuestos = 0;
				foreach ($impuestos_venta as $key2 => $value2) {
					if ($value2 <> 0 ) {
				 ?>
					 <tr>
						<td style="width: 30%; text-align:left;"><?php echo $key2; ?></td>
						<td style="width: 30%; text-align:right;"><?php echo number_format((float)$value2,2,'.',','); ?></td>
					</tr>
				<?php 
					}
				}
				 ?>
				 	<tr>
						<td style="width: 30%; text-align:left;"><?php echo "Total: "; ?></td>
						<td style="width: 30%; text-align:right;"><?php echo number_format((float)$venta[0]['monto'],2,'.',','); ?></td>
					</tr>
				 	<tr>
				 		<td colspan="2" style="width: 60%; text-align:center;"><?php echo $total_letra; ?></td>
				 	</tr>
			</tbody>
		</table>
	</body>
	</html>

<script type="text/javascript">
	window.print();	
</script>

 