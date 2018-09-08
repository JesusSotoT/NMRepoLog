<!DOCTYPE html>
<head>
	 <meta content="text/html;" http-equiv="content-type" charset="utf-8">
	 <script language='javascript' src='../../../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
	 <link rel="stylesheet" href="../../css/bootstrap/bootstrap.css">
</head>
<body>
	<?php
		$data = $_REQUEST['data'];
		$no_decode = $_REQUEST['no_decode'];
		if (!isset($no_decode)) {
			$data = stripslashes($data);// Quita las barras de un string con comillas escapadas
			$data = urldecode($data);
			$data = unserialize($data);
		}
		//print_r($data);
		if($data['tipocomprobante']=="Ingreso" || $data['tipocomprobante']=="Nomina"){
			$nombre = $data['nombre'][1];
			$rfc = $data['rfc'][1];
		}elseif($data['tipocomprobante']=="Egreso"){
			$nombre = $data['nombre'][0];
			$rfc = $data['rfc'][0];
		}else{
			$nombre = $data['nombre'];
			$rfc = $data['rfc'];
		}
		$fecha = explode("T", $data['FechaTimbrado'] );
	?>
	
<div style="background: #F2F2F2; width: 70%;padding:10px;">
	<h3><?php echo strtoupper($nombre); ?></h3>
	<label><b>RFC:</b>&nbsp;<?php echo $rfc; ?></label><br>
	<label><b>Factura:</b> <?php echo $data['tipocomprobante']; ?></label><br>
	<label><b>Fecha Timbrado:</b>&nbsp;<?php echo $fecha[0]."&nbsp; ",$fecha[1]." Hrs"; ?></label>
	<br></br>
	<table  cellpadding="2" cellspacing="2" style="width: 100%" class='table table-striped table-condensed'>
	<?php if(!$data['MesFin']){?>
		<tr><td colspan="4"><hr></td></tr>
		<tr>
			<th>Cantidad</th>
			<th>Unidad</th>
			<th>Descripcion</th>
			<th>Importe</th>
		</tr>
		<tbody>
		<?php 
		$maximo = count($data['cantidad']);
		$maximo = (intval($maximo)-1);
		if(is_array($data['cantidad'])){	
		for($c=0;$c<=($maximo);$c++){?>
		<tr>
			<td align="center">
				<?php echo $data['cantidad'][$c]; ?>
			</td>
			<td align="center">
				<?php echo $data['unidad'][$c]; ?>
			</td>
			<td >
				<?php echo $data['descripcion2'][$c]; ?>
			</td>
			<td align="right">
				<?php echo number_format($data['valorUnitario'][$c]* $data['cantidad'][$c],2,'.',','); ?>
			</td>
		 </tr> 
			<?php }
		}else{?>
			<tr>
			<td align="center">
				<?php echo $data['cantidad']; ?>
			</td>
			<td align="center">
				<?php echo $data['unidad']; ?>
			</td>
			<td >
				<?php echo $data['descripcion2']; ?>
			</td>
			<td align="right">
				<?php echo number_format($data['valorUnitario']* $data['cantidad'],2,'.',','); ?>
			</td>
		 </tr> 
		<?php } ?>
		<tr><td colspan="4"><hr></td></tr>	
		<tr style="color: #337ab7; ">
			<td colspan="3" align="right" ><b>Subtotal</b></td>
			<td align="right">
				<?php echo number_format($data['subtotal'],2,'.',','); ?>
			</td>
		</tr>
		
		<?php 
		$max = count($data['impuesto']);
		$max = (intval($max)-1);
			if(is_array($data['impuesto'])){ 
					for($i=0;$i<=$max;$i++){?>
					<tr style="color: #337ab7">
						<td colspan="3" align="right">
						<b>
							<?php echo $data['impuesto'][$i]; ?>
							</b>
						</td>
						<td align="right">
							<?php echo number_format($data['importe'][$maximo+($i+1)],2,'.',','); ?>
							
						</td>
					</tr>
			<?php 	}
				}else{ ?>
					<tr style="color: #337ab7">
						<td colspan="3" align="right">
						<b>
							<?php echo $data['impuesto']; ?>
							</b>
						</td>
						<td align="right">
							<?php echo number_format($data['importe'][$maximo+1],2,'.',','); ?>
						</td>
					</tr>
			<?php	} ?>
				<tr style="color: #337ab7">
						<td colspan="3" align="right">
						<b>
							Descuento
							</b>
						</td>
						<td align="right">
							<?php echo number_format($data['descuento'],2,'.',','); ?>
						</td>
					</tr>
					<tr style="background-color: #337ab7;color:white;font-weight:bold;">
						<td colspan="3" align="right">
						<b>
							Total
							</b>
						</td>
						<td align="right">
							<?php echo number_format($data['total'],2,'.',','); ?>
						</td>
	<?php } else{// xml de retencion de pagos ?>
		<tr style="background-color: #337ab7"><td colspan="4" align="center"><b style="color:white;font-size: 16px">Retencion<br>(<?php echo $data['complemento'];?>)</b></td></tr>
		<tr>
			<th align="center">Total Operacion</th>
			<th align="center">Total Gravado</th>
			<th align="center">Total Exento</th>
			<th align="center">	Total de Retenciones</th>
		</tr>
		<tbody>
			<tr>
				<td align="right"><?php echo number_format($data['montoTotGrav'],4,'.',',');?></td>
				<td align="right"><?php echo number_format($data['montoTotExent'],4,'.',',');?></td>
				<td align="right"><?php echo number_format($data['montoTotRet'],4,'.',',');?></td>
			</tr>
			<?php 
			if($data['BaseRet']){?>
			<tr style="background-color: #585858">
				<td colspan="4" align="center"><b style="color:white;font-size: 16px">Impuestos retenidos</b></td>
			</tr>
			<tr>
				<td align="center"><b>Importe Base</b></td>
				<td align="center"><b>Tipo Impuesto</b></td>
				<td align="center"><b>Impuesto Retenido</b></td>
				<td align="center"><b>Tipo de Pago</b></td>
			</tr>

			<?php
				$maximo = count($data['BaseRet']);
				$maximo = (intval($maximo)-1);
				if(is_array($data['BaseRet'])){	
					for($c=0;$c<=($maximo);$c++){?>
					<tr><td align="right"><?php echo number_format($data['BaseRet'][$c],4,'.',',');?></td>
						<td align="right"><?php echo number_format($data['Impuesto'][$c],4,'.',',');?></td>
						<td align="right"><?php echo number_format($data['montoRet'][$c],4,'.',',');?></td>
						<td align="right"><?php echo $data['TipoPagoRet'][$c]; ?></td>
					</tr>
			<?php }
				}else{?>
					<tr><td align="right"><?php echo number_format($data['BaseRet'],4,'.',',');?></td>
						<td align="right"><?php echo number_format($data['Impuesto'],4,'.',',');?></td>
						<td align="right"><?php echo number_format($data['montoRet'],4,'.',',');?></td>
						<td align="right"><?php echo $data['TipoPagoRet']; ?></td>
					</tr>
			<?php }
			}?>
			
		</tbody>
	<?php } ?>
		</table>
</div>
</body>
</html>