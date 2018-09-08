<?php $idventa=$_REQUEST["idventa"];
include("funcionesPv.php"); 
$organizacion=datosorganizacion();
$venta=datosventa($idventa);
$productos=productosventa($idventa);
$pagos=pagos($idventa);
$impuestos_venta=array();
?>
<link rel="stylesheet" rev="stylesheet" href="css/netpos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/netpos_print.css"  media="print"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript">  
$(function(){

	window.print();
});
</script>
<style>
.small_button a{
	color:white;
	text-decoration:none;
	font-family:Arial, Helvetica, sans-serif;
	}
@media print
  {
 .item_number{display:none;}
  }
</style>


<div id="receipt_wrapper">
	
	<div id="receipt_header">
		<div id="company_name"><?php echo $organizacion->nombreorganizacion;?></div>
		<div id="company_address"><?php echo utf8_decode($organizacion->domicilio." ".$organizacion->municipio.",".$organizacion->estado);?></div>
		
			<?php if(strcmp($venta->estatus,"Cancelada")==0){?>
			<div id="company_phone">		
			<?php echo "Venta ".$venta->estatus;?>
			</div>
			<?php
			 }  ?>
			
		<div id="sale_receipt"><?php echo  $organizacion->RFC;?></div>	
		<div id="sale_receipt">Ticket de compra</div>
		<div id="sale_time"><!--Fecha y hora--><?php echo formatofecha($venta->fecha);?></div>
	</div>
	
	<div id="receipt_general_info">
		<div id="customer">Cliente:<?php echo $venta->cliente; ?></div>
		<div id="sale_id">Id venta:<?php  echo $venta->folio; ?></div>
		<div id="employee">Empleado:<?php  echo $venta->empleado; ?></div>
	</div>

	<table id="receipt_items">
	<tr>
	<th style="width:25%;" class='item_number'>#</th>
	<th style="width:25%;">Producto</th>
	<th style="width:17%;">Precio</th>
	<th style="width:16%;text-align:center;">Cantidad</th>
	<th style="width:16%;text-align:center;">Descuento</th>
	<th style="width:17%;text-align:right;">Total</th>
	</tr>
	<?php $total=0; while($producto=mysql_fetch_object($productos)){	
		$stotal=($producto->preciounitario*$producto->cantidad)+$producto->impuestos_venta-$producto->montodescuento;
		$total+=$stotal;
		
		$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$producto->idProducto);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{	
					$impuestos_venta[$ri->impuesto]=(($stotal*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);	
				}
				else
				{
					$impuestos_venta[$ri->impuesto]=(($stotal*$ri->valor)/100);	
				}
			}
		}
	?>
	
	<?php
			if(strlen($producto->descorta)>0){  $descripcion=$producto->descorta; }else { $descripcion=$producto->nombre;  }
	?>

		<tr>
		<td class='item_number'><?php echo $producto->codigo; ?></td>
		<td><span class='long_name'><?php echo utf8_decode(substr($descripcion,0,12)); ?></span><span class='short_name'><?php echo substr($producto->nombre,0,12); ?></span></td>
		<td>$<?php echo number_format($producto->preciounitario,2,".",","); ?></td>
		<td style='text-align:center;'><?php echo $producto->cantidad; ?></td>
		<td style='text-align:center;'>$<?php echo number_format( $producto->montodescuento,2,".",","); ?></td>
		<td style='text-align:right;'>$<?php echo number_format(($producto->preciounitario*$producto->cantidad)-$producto->montodescuento,2,".",","); ?></td>
		</tr>
	<?php
	}
	?>
	<tr>
	<td colspan="4" style='text-align:right;border-top:2px solid #000000;'>Subtotal:</td>
	<td colspan="2" style='text-align:right;border-top:2px solid #000000;'>$<?php echo number_format($total,2,".",","); ?></td>
	</tr>
	
	
	<?php
	$totalimpuestos=0;
	foreach($impuestos_venta as $impuesto=>$valorimpuesto)
			{
				$totalimpuestos+=$valorimpuesto;
	?>		
		<tr>
			<td colspan="4" style='text-align:right;'><?php echo $impuesto;?></td>
			<td colspan="2" style='text-align:right;'>$<?php echo number_format( $valorimpuesto,2,".",","); ?></td>
		</tr>
	<?php	
			}
	?>
<!--
		<tr>
			<td colspan="4" style='text-align:right;'>Impuestos</td>
			<td colspan="2" style='text-align:right;'>$<?php echo number_format( $venta->impuestos,2,".",","); ?></td>
		</tr>
-->	
	<tr>
	<td colspan="4" style='text-align:right;'>Total:</td>
	<td colspan="2" style='text-align:right'>$<?php echo number_format($total+$totalimpuestos,2,".",","); ?></td>
	</tr>

    <tr><td colspan="6">&nbsp;</td></tr>

	<?php while($pago=mysql_fetch_object($pagos)){ ?>
		<tr>
		<td colspan="2" style="text-align:right;">Forma de pago:</td>
		<td colspan="2" style="text-align:right;"><?php echo $pago->nombre; ?> </td>
		<td colspan="2" style="text-align:right">$<?php echo number_format($pago->monto,2,".",","); ?>  </td>
	    </tr>
	<?php } ?>

    <tr><td colspan="6">&nbsp;</td></tr>

	<tr>
		<td colspan="4" style='text-align:right;'>Cambio</td>
		<td colspan="2" style='text-align:right'>$<?php echo number_format($venta->cambio,2,".",","); ?></td>
	</tr>

	</table>
	
	
</div>



