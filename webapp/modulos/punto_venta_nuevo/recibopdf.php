<?php
	session_start();
	error_reporting(0);

	$idventa = $_REQUEST["idventa"];
	$print = $_REQUEST["print"];
	include("funcionesConsulta.php");
	$organizacion=datosorganizacion();
	$venta=datosventa($idventa);
	$productos=productosventa($idventa);
	$pagos=pagos($idventa);
	$impuestos_venta=array();
	$mesa = 0;

	if(isset($_SESSION['mesa'])){
		$mesa = $_SESSION['mesa'];
	}else{
		$mesa = 0;
	}
?>

<meta charset="UTF-8">
<link rel="stylesheet" rev="stylesheet" href="css/netpos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/netpos_print.css"  media="print"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script id="scriptAccion" type="text/javascript">
	<?php
		header('Content-Type: text/html; charset=utf-8');
		if(!isset($print) || $print == 'true') { ?>
			$(function() {
				window.print();
			}); <?php
		}
	?>
</script>

<style>
	#letraschicas{
		font-size: 13px;
	}
	.small_button a{
		color:white;
		text-decoration:none;
		font-family:Arial, Helvetica, sans-serif;
	}
	.textWrap {
		text-align: justify;
		word-wrap: break-word;
		font-size: 10px;
	}
	@media print {
		.item_number{display:none;}
	}
</style>

<table id="receipt_header" border="0">
	<tr> <th style="width:90%; text-align:center;">RECIBO DE PAGO</th> </tr>
	<tr>
		<th>
			<div id="logo">
				<?php
					$imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion->logoempresa;
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
					echo $src;
				?>
				<br>
			</div>
		</th>

		<th>
			<div id="receipt_header" style="text-align: center;">	
				<div id="company_name" style="font-size:150%;font-weight:bold;"><?php echo $organizacion->nombreorganizacion;?></div>
				<div id="sale_receipt"><?php echo "R.F.C. ".$organizacion->RFC;?></div>

				<div id="company_address">
					<?php echo utf8_decode($organizacion->domicilio
						." ".$organizacion->municipio
						.",".$organizacion->estado); ?>
				</div>
				<br>

				<?php
					if(strcmp($venta->estatus,"Cancelada")==0){?>
						<div id="company_phone">
							<?php echo "Venta ".$venta->estatus;?>
						</div> <?php
					}
				?>
			</div>
		</th>

		<th style="width:20%;">
			<div id="sale_id">FOLIO:<br><?php  echo $venta->folio; ?></div>
		</th>
	</tr>

	<tr>
		<th>
			<div id="receipt_general_info" style="text-align:left;">
				<div id="titcliente"><b>Datos Cliente Receptor</b>
				<div id="nombre">Nombre:<?php echo $venta->cliente; ?></div>
				<div id="direccion">Direccion:</div>
				<div id="colonia">Colonia:</div>
				<div id="ciudad">Ciudad:</div>
				<div id="estado">Estado:</div>
				<div id="cp">C.P.:</div>
				<div id="rfc_cliente">R.F.C.:</div>
				<div id="customer">Cliente:</div>
				<br>
			</div>
			
		</th>

		<th>
			<div id="sale_id" style="float:right;">RECIBI:____________________<br><br></div>
			<div id="sale_id" style="float:left;">FECHA:____________________<br><br></div>
			<div id="sale_id">FIRMA:____________________<br></div>

			<div id="sale_id"><br><b>EMISION:</b>:<br><?php echo formatofecha($venta->fecha);?></div>
			<div id="sale_id"><b>VIGENCIA</b>:<br> </div>
			<div id="sale_id"><b>PERIODO A DECLARAR</b>:<br>  </div>
		</th>
	</tr>

</table>

<table id="receipt_items" border='0'>
	<tr>
		<!--<th style="width:25%;" class='item_number'>#</th>-->
		<!--<th style="width:16%;text-align:center;">Cantidad</th>-->
		<th style="width:58%;">Concepto</th>
		<!--<th style="width:17%;">Precio</th>-->

		<!--<th style="width:16%;text-align:center;">Descuento</th>-->
		<th style="width:17%;text-align:right;">Total</th>
	</tr>

	<?php $total=0; while($producto=mysql_fetch_object($productos)){
		$impuestos_venta='';
		$stotal=($producto->preciounitario*$producto->cantidad)-$producto->montodescuento;
		$total+=$stotal;

		$qi=mysql_query("select pi.idimpuesto,i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$producto->idProducto.' order by pi.idimpuesto DESC');
		if(mysql_num_rows($qi)>0) {
			while($ri = mysql_fetch_object($qi)) {
				$suma_impuestos =  $ri->valor;

				if ($ri->impuesto == 'IEPS') {
					$calculos = str_replace(",", "", number_format(((($producto->preciounitario * $producto->cantidad  - str_replace(",", "",$producto->montodescuento)  )* $ri->valor)) / 100, 2));
					$impuestos_venta2[$ri->impuesto] += $calculos;
					$ieps = $calculos;
				} else {

					if ($ieps != 0) {
						$calculos = str_replace(",", "", number_format((((($producto->preciounitario  * $producto->cantidad) + $ieps  - str_replace(",", "",$producto->montodescuento)) * $ri->valor) ) / 100, 2));
						$impuestos_venta2[$ri->impuesto] +=  $calculos;
					} else { 
						$calculos = str_replace(",", "", number_format(((($producto->preciounitario  * $producto->cantidad - str_replace(",", "",$producto->montodescuento)) * $ri->valor)) / 100, 2));
						$impuestos_venta2[$ri->impuesto] += $calculos;
						//echo "(".$ri->impuesto." -> ".$producto->preciounitario ."*". $producto->cantidad ."-". str_replace(",", "",$producto->montodescuento) ."*". $ri->valor." = ".$calculos.")";
					}
				}
			}
		}
	?>

	<?php
		if(strlen($producto->descorta)>0){  $descripcion=$producto->descorta." ".$descripcion=$producto->comentario; }else { $descripcion=$producto->nombre." ".$descripcion=$producto->comentario;  }
	?>

	<tr>
		<!--<td class='item_number'><?php echo $producto->codigo; ?></td>-->
		<!--<td style='text-align:center;'><?php echo $producto->cantidad; ?></td> -->
		<td style='text-align:center;' class="textWrap"><span class='short_name '><?php echo $descripcion; ?></span></td>
		<td style='text-align:right;'>$<?php echo number_format(($producto->preciounitario*$producto->cantidad)-$producto->montodescuento,2,".",","); ?></td>
	</tr>

	<?php
		if($producto->montodescuento>0){ ?>
			<tr>
				<td style='text-align:center;'>Desc:</td><td style='text-align:center;'>$<?php echo number_format( $producto->montodescuento,2,".",","); ?></td>
			</tr> <?php
		}
	}
	?>
	<tr>
		<!-- <td colspan="2" style='text-align:right;border-top:2px solid #000000;'><b>Subtotal:</b></td> -->
		<td colspan="1" style='text-align:right;border-top:2px solid #000000;'>$<?php echo number_format($total,2,".",","); ?></td>
	</tr>
	
	<?php
		$totalimpuestos=0;
		if($impuestos_venta2==''){
			$impuestos_venta2['IVA']=0.00;
		}
		$impuestos_venta=$impuestos_venta2;
		foreach($impuestos_venta as $impuesto=>$valorimpuesto) {
			$totalimpuestos+=$valorimpuesto; ?>

			<!--<tr>
				<td colspan="2" style='text-align:right;'><b><?php echo $impuesto;?></b></td>
				<td colspan="1" style='text-align:right;'>$<?php echo number_format( $valorimpuesto,2,".",","); ?></td>
			</tr> --> <?php
		}
	?>
<!--
		<tr>
			<td colspan="4" style='text-align:right;'>Impuestos</td>
			<td colspan="2" style='text-align:right;'>$<?php echo number_format( $venta->impuestos,2,".",","); ?></td>
		</tr>
	-->	
	<tr>
		<td colspan="2" style='text-align:right;'><b>Total:</b></td>
		<td colspan="1" style='text-align:right'>$<?php echo number_format($total+$totalimpuestos,2,".",","); ?></td>
	</tr>

	<tr><td colspan="6">&nbsp;</td></tr>

	<?php while($pago=mysql_fetch_object($pagos)){ ?>
		<!-- <tr>
			<td colspan="2" style="text-align:right;"><b><?php echo utf8_decode($pago->nombre); ?></b></td>
			<td colspan="1" style="text-align:right">$<?php echo number_format($pago->monto,2,".",","); ?>  </td>
		</tr> -->
	<?php } ?>

	<tr><td colspan="6">&nbsp;</td></tr>

	<!--<tr>
		<td colspan="2" style='text-align:right;'><b>Cambio</b></td>
		<td colspan="1" style='text-align:right'>$<?php echo number_format($venta->cambio,2,".",","); ?></td>
-->	</tr>

	<tr><td colspan="6">&nbsp;</td></tr>

	<!--<tr>
		<td colspan="2" style='text-align:right;'><b>Cambio</b></td>
		<td colspan="1" style='text-align:right'>$<?php echo number_format($venta->cambio,2,".",","); ?></td>
-->	</tr>


</table><br><br>


<?php
/*	include_once("../../netwarelog/catalog/conexionbd.php");
	$q=mysql_query("SELECT * FROM accelog_perfiles_me WHERE idmenu=146;");

	$q2=mysql_query("SELECT ticket_config FROM pvt_configura_facturacion WHERE id=1;");
	while ($fila = mysql_fetch_assoc($q2)) {
    	$sino = $fila['ticket_config'];
	}
	if($sino >0 && mysql_num_rows($q) > 0){
		 ?>
		 <hr size='2' color="black">
		 <table id='codigofact' width='100%' style="text-align: left;" border="0">
	<tr>
		<td>
			<div style="margin-top: 0px;" float="left">
				<h6 align="center">&nbsp;Para obtener su factura ingrese a la direcion:</h6>
			</div>
		<div style="margin-top: -15px;" float="left" class='textWrap'>
		<p align="center" style="font-size: 12px;">	
		<?php 
			$url="netwarmonitor.mx/clientes/".$_SESSION['accelog_nombre_instancia']."/facturar";
			if(strlen($url) >50)
			{	
				echo $url;
				/*$url1 = substr($url, 0,50);
				$url2 = substr($url, 51);

				echo $url1."</br>";
				echo $url2; */
/*			}else
			{
				echo $url;
			}
		?>	
		</p>
		</div>	
		</td>
	</tr>
	<tr>
		<td>
			<div style="margin-top: 0px;" float="left">
				<h6 align="center">&nbsp;Ingresando el Siguiente codigo:</h6>
			</div>	
			<div style="margin-top: -15px;" float="left">
				<p align="center" style="font-size: 19px;">
				<?php
				$longuitud=strlen($_SESSION['accelog_nombre_instancia']);
				$codinstancia=$_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];

				$fecha=str_replace('-', '', $venta->fecha );
				$fecha=str_replace(':', '', $fecha);
				$fecha=str_replace(' ', '', $fecha);
		//echo "Codigo sin convertir:".$codinstancia.$fecha.$venta->folio.";";	
				//$codigoHex=base64_encode($codinstancia.$fecha.$venta->folio);
				$codigoHex = $codinstancia.dechex($fecha.$venta->folio);
				$codigoFactura=$codigoHex;
				echo $codigoFactura;
		?> </p>
			</div>
	</td>
	</tr>
</table> 
		<?php }
*/		
		?>
</div>

