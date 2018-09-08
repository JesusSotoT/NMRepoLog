<?php include("funcionesPv.php");
$total=$_POST["total"];
$impuestos=$_POST["impuestos"];
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Pagar caja</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	
	<script>
	$(function(){ 
		
		$("#loaderventa").hide();
		
		$("#cantidad-recibida").numeric();
		
		$("#cantidadpago").numeric({allow:"."});
		$("#cantidadpago").focus();
		
		$("#hidden-referencia").hide();
		$("#referencia").hide();
		});	
		
	</script>
</head>
<body >

<table width="100%">

<tr>
	<td colspan="2">
		
		<table width="100%" style="font-size:12px;" border="0">
					<tr>
							<td align="left"><?php echo formasPago();?></td>
							<td align="left"><input type="text" maxlength="8" id="cantidadpago" placeholder="Cantidad" /></td>
							<td align="left"><input type="button" value="Agregar pago" id="btn-agregarpago" onclick="agregarpago(<?php echo ($total);?>);"></td>
					</tr>
					
					<tr>
							<td colspan="3" ><span id="hidden-referencia"></span><input type="text" id="referencia" maxlength="25" /></td>
					</tr>
		</table>
		
	</td>	
</tr>

<tr>
	<td width="35%">
		
					<table width="100%" border="0" style="font-size:12px;" id="tabla-pagos">
					<tr>
							<td align="center">
								
								<span id="pagos-caja">
										<?php $pagos=pagoscaja($total); echo $pagos[0];?>
							</span>
							</td>
							
					</tr>	
							
					</table>
	</td>	
	<td>
				<table width="100%">
					
				<!--	
					<tr>	
							<td align="right"><div class="pagar-subtotal">Subtotal:$<?php echo number_format($total,2,".",",");  ?></div></td>
					</tr>
				
					<tr>	
							<td align="right"><div class="pagar-iva">Iva:$<?php echo number_format(($total*iva()),2,".",",");  ?></div></td>
					</tr>
				-->	
					
					<tr>	
							<input maxlength="6"  type="hidden"  id="total-impuestos" value="<?php echo str_replace(",","",number_format($impuestos,2)); ?>">
							<td align="center"><input type="hidden" id="super-total" value="<?php  echo str_replace(",","",number_format($total,2));?>"><div id="pagar-total">Total:$<?php echo number_format($total,2,".",",");  ?></div></td>
					</tr>
					<tr>	
							<td  align="center">
								
								<input maxlength="6" value="<?php echo $pagos[2]; ?>" placeholder="Cantidad pagada" type="text" readonly="readonly" id="cantidad-recibida" name="cantidad-recibida">
							
								<input maxlength="6"  type="hidden"  value="<?php echo str_replace("$","",$pagos[1]); ?>"  id="cantidad-recibida-hidden" name="cantidad-recibida-hidden">
							
							</td>
					</tr>
					<tr>	
							<td  align="center"><div id="pagar-cambio">Cambio:<?php echo $pagos[3]; ?></div></td>
					</tr>
				</table>	
	</td>
</tr>	
</table>	

<table width="100%"  align="center" id="loaderventa">
<tr><td align="center"> 
<span style="font-weight: bold; font-size: 16px; color:gray;">
La venta se esta registrando , espere un momento ... <img src="img/preloader.gif" id="preloader">
</span>	
</td></tr>		
</table>
		
</body>
</html>