
<table  cellspacing="2" cellpadding="2" width="100%" class="table table-striped table-bordered" id="table">
	<thead >
		<th>Importe Base</th>
		<th>Tipo Impuesto</th>
		<th>Impuesto Retenido</th>
		<th>Tipo Pago</th>
	</thead>
	<tbody>
	<?php if($impuestos){
		while($in = $impuestos->fetch_assoc()){
		$impuesto = array('01' => 'ISR','02' => 'IVA','03' => 'IEPS');
			 
	?>
			
		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
			<td align="right"><?php echo number_format($in['importBase'],6,'.',',');?></td>
			<td align="center"><?php echo $impuesto[$in['tipoImpuesto']];?></td>
			<td align="right"><?php echo number_format($in['impuestoRetenido'],6,'.',',');?></td>
			<td align="center"><?php echo $in['tipoPago'];?></td>
		</tr>
	<?php 	}
	}else{ ?> 
		<tr>
			<td colspan="12" align="center">No tiene Impuestos</td>
		</tr>
	<?php } ?>
</tbody>
</table>