<div style="text-align:left;font-size:14px">
	<div>
		<input type="image" src="../webapp/netwarelog/archivos/1/organizaciones/<?php echo $objeto['logo'] ?>" style="width:180px"/>
	</div>
	<div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">
		Comanda No: <?php echo $objeto['comanda'] ?>
	</div><?php
	
	foreach ($objeto['pedidos'] as $key => $value) { ?>
		<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">
				Pedido: <?php echo $key+1 ?>
		</div>
		<div style="margin-left:15px">
			<table style="font-size:11px;font-family:Arial;border-collapse:collpase">
				<tr>
					<td><?php echo $value['cantidad'] ?></td>
					<td><?php echo $value['nombre'] ?></td>
					<td>$<?php echo $value['precioventa'] ?></td>
				</tr>
			</table>
		</div><?php
	
		if($value['extras']){ ?>
			<div style="margin-left:15px">
				<table style="font-size:11px;font-family:Arial;border-collapse:collpase">
					<tr>
						<td></td>
						<td>=> Extras:</td>
					</tr><?php
					foreach ($value['extras'] as $k => $v) { 
						$costo_extra+=$v['costo'] ; ?>
						
						<tr>
							<td></td>
							<td></td>
							<td><?php echo $v['nombre'] ?></td>
							<td>$ <?php echo $v['costo'] ?></td>
						</tr><?php
					} ?>
				</table>
			</div><?php
		} ?>
		<div style="margin-left:15px">
			<table style="font-size:11px;font-family:Arial;border-collapse:collpase">
				<tr>
					<td>Sub total: $<strong><?php echo ($value['cantidad']*$value['precioventa'])+$costo_extra ?></strong></td>
				</tr>
			</table>
		</div><?php
	} 
	
// Valida si se debe de mostrar la propina
	if(!empty($objeto['propina'])){ ?>
		<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			Propina sugerida: <?php echo $objeto['propina'] ?>
      </div><?php
	} ?>
	
	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
		Total: <strong>$ <?php echo $objeto['total'] ?></strong>
	</div>
<!-- Al cargar el codigo  de barras manda lamar a la funcion que imprime -->
	<div style="margin-top:10px;">
		<input type="image" src="../webapp/modulos/punto_venta/barcode/barras.php?c=barcode&barcode=<?php echo $objeto['codigo'] ?>&text=<?php echo $objeto['codigo'] ?>&width=190" onload="window.print();" style="width:190px;margin-left:-3px;" id="barcode"/>
	</div>	
</div>