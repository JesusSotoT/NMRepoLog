	
<table id="cargosapp" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th colspan="9" style="background-color:#BDBDBD;color:white;text-align: center;size: 20px;"><b >CARGOS</b></th>
		</tr>
		<tr>
			
			<th style="width: 30px;"></th>
			<th  style="width: 40px;text-align: center;">Fecha</th>
			<th style="width: 30px;text-align: center;">ID</th>
			<th style="width: 50px;text-align: center;">Concepto</th>
			<th style="width: 30px;text-align: center;">Moneda</th>
			<th style="width: 30px;text-align: center;">Monto</th>
			<th style="width: 50px;text-align: center;">Saldo Abonado</th>
			<th style="width: 50px;text-align: center;">Pago</th>
			<th style="width: 30px;text-align: center;">Saldo Actual</th>
			
			
			
		
			
		</tr>
	</thead>
	<tbody>
		<?php
		$tipocambio = $_REQUEST['cambio'];	
		if($_REQUEST['cambio']<1){
			$tipocambio=1;
		} 
		if($cargos->num_rows>0){
		while($l = $cargos->fetch_assoc())
		{
	
		 if(round(floatval($l['saldo'])) > 0){
		 	
			/* en el documento 3 no manda cambio
			 * por loque debe tomar el cambio con el que se dio de alta
			 */
			if($_REQUEST['doc'] == 3){
				if($_REQUEST['mone']!=1){
					$tipocambio= $l['tipo_cambio'];
				}
			}
		 ?>
		
			<tr>
				<td style="width: 30px">
					<input onclick="calculocxc()" type='checkbox' name="cargosapp[]" data-value="<?php echo $l['id']; ?>" class="listacheck" value="<?php echo number_format(($l['saldo']/$tipocambio),2,'.','').'/'.$l['id'].'/'.number_format(($l['saldo']/$tipocambio),2,'.',''); ?>" >
				</td>
				<td  style="width: 40px;text-align: center;"><?php echo $l['fecha_pago'];?></td>
				<td style="width: 30px;text-align: center;"><?php echo $l['id'];?></td>
				<td style="width: 50px;text-align: center;"><?php echo $l['concepto'];?></td>
				<td style="width: 30px;text-align: center;"><?php echo $l['moneda'];?></td>
				<td style="width: 30px;text-align: right;"><?php echo number_format($l['cargo'],2,'.',',');?></td><!-- monto total -->
				<td style="width: 30px;text-align: right;"><?php echo number_format($l['pagos'],2,'.',',');?></td>
				<td style="width: 30px;text-align: center;"><input class="txtmonto" style="text-align: right;" type="text" onkeypress="return tcvalida(event,this);" onkeyup="montosaldo(this.value,<?php echo $l['id'];?>)" value="<?php echo number_format(($l['saldo']/$tipocambio),2,'.',''); ?>" id="txt<?php echo $l['id'];?>"/></td>
				<td style="width: 50px;text-align: right;"><?php echo number_format(($l['saldo']/$tipocambio),2,'.',',');?></td>

				
				
				
			</tr>
		<?php
			} 
		}
} ?>
	</tbody>
</table>
<table id="facturasapp" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th colspan="11" style="background-color:#BDBDBD;color:white;text-align: center;size: 20px;"><b>FACTURAS</b></th>
		</tr>
		<tr>
			<th style="width: 30px"></th>
			<th style="text-align: center;">Fecha</th>
			<th style="text-align: center;">Fecha Vencimiento</th>
			<th style="text-align: center;">ID Venta</th>
			<th style="text-align: center;">UUID</th>
			<th style="text-align: center;">Concepto</th>
			<th style="width: 30px;text-align: center;">Moneda</th>
			<th style="width: 50px;text-align: center;">Monto</th>
			<th style="width: 30px;text-align: center;">Saldo Abonado</th>
			<th style="width: 30px;text-align: center;">Pago</th>
			<th style="width: 30px;text-align: center;">Saldo Actual</th>
			
			
			
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
		if($facturas->num_rows>0){
		while($l = $facturas->fetch_assoc())
		{ 
			$desc = $l['desc_concepto'];
			$vencimiento = new DateTime($l['fecha_factura']);
			$vencimiento->add(new DateInterval('P'.$l['diascredito'].'D'));
			$estilo = '';
			if(strtotime($vencimiento->format('Y-m-d')) < strtotime(date()))
				$estilo = "style='color:red;'";
			
			if(!floatval($l['rq_tipo_cambio']))
				$nuevoImp = floatval($l['imp_factura']);
			else
				$nuevoImp = floatval($l['imp_factura'])*floatval($l['rq_tipo_cambio']);

			$saldo = $nuevoImp - floatval($l['pagos']);
			if(round(floatval($saldo),2) > 0)
			{	
				 
				if($_REQUEST['doc'] == 3){
					if($_REQUEST['mone']!=1){
						
						$tipocambio= $l['rq_tipo_cambio'];
					}
				}
				$moneda = $l['Moneda'];
				if(intval($_REQUEST['cobrar_pagar'])){?>
					<tr>
						<td style="width: 30px">
							<input type='checkbox' onclick="calculocxc()" class="listacheckfac" name="facturasapp[]" value="<?php echo number_format($saldo/$tipocambio,2,'.','').'/'.$l['id'].'/'.$l['xmlfile'].'/'.number_format( ($saldo/$tipocambio) ,2,'.','');?>" data-value="fac<?php echo $l['id']; ?>" >
						</td>
						<td style="text-align: center;"><?php echo $l['fecha_factura'];?></td>
						<td <?php echo $estilo;?> style="text-align: center;"><?php echo $vencimiento->format('Y-m-d');?></td>
						<td style="text-align: center;">
							<a href="../appministra/index.php?c=compras&f=ordenes&id_oc=<?php echo $l['id_oc'];?>&v=1" target='_blank'><?php echo $l['id_oc'];?></a>
						</td>
						<td style="text-align: center"><?php echo $l['no_factura'];?></td>
						<td style="text-align: center;"><?php echo $desc;?></td>
						<td style="width: 30px;text-align: center;"><?php echo $moneda;?></td>
						<td style="text-align: right;"><?php echo number_format($l['imp_factura'],2,'.',',');?></td>
						<td style="text-align: right;"><?php echo number_format($l['pagos'],2,'.',',');?></td>
						<td style="width: 30px;text-align: right;"><input class="txtmonto" style="text-align: right;" type="text" onkeypress="return tcvalida(event,this);" onkeyup="montosaldofac(this.value,<?php echo $l['id'];?>)" value="<?php echo number_format( ($saldo/$tipocambio) ,2,'.',''); ?>" id="txtfac<?php echo $l['id'];?>"/></td>
						<td style="width: 50px;text-align: right;"><?php echo number_format($saldo/$tipocambio,2,'.',',');?></td>
						
						
						
						
						
					</tr>
				<?php
				}
				else
				{
					if(intval($l['origen']) == 1)
						$url = "../cont/index.php?c=ventas&f=ordenes&id_oventa=".$l['id_oventa']."&v=1";
					if(intval($l['origen']) == 2)
						$url = "../pos/ticket.php?idventa=".$l['id_oventa']."&print=0";
				?>
					<tr>
						<td style="width: 30px">
							<input type='checkbox' onclick="calculocxc()" class="listacheckfac" name="facturasapp[]" value="<?php echo number_format($saldo/$tipocambio,2,'.','').'/'.$l['idres'].'/'.$l['xmlfile'].'/'.number_format($saldo/$tipocambio,2,'.','');?>" data-value="fac<?php echo $l['idres']; ?>" >
						</td>
						<td style="text-align: center;"><?php echo $l['fecha_factura']; ?></td>
						<td <?php echo $estilo;?> style="text-align: center;"><?php echo $vencimiento->format('Y-m-d');?></td>
						<td style="text-align: center;">
							<a href="<?php echo $url;?>" target='_blank'><?php echo $l['id_oventa'];?></a>
						</td>
						<td style="text-align: center;"><?php echo $l['folio'];?></td>
						<td style="text-align: center;"><?php echo $desc;?></td>
						<td style="width: 30px;text-align: center;"><?php echo $moneda;?></td>
						<td style="text-align: right;"><?php echo number_format($l['imp_factura'],2,'.',',');?></td>
						<td style="text-align: right;"><?php echo number_format($l['pagos'],2,'.',',');?></td>
						<td style="width: 30px">
							<input class="txtmonto" style="text-align: right;" type="text" onkeypress="return tcvalida(event,this);" onkeyup="montosaldofac(this.value,<?php echo $l['idres'];?>)" value="<?php echo number_format($saldo/$tipocambio,2,'.',''); ?>" id="txtfac<?php echo $l['idres'];?>"/>
						</td>
						<td style="width: 50px;text-align: right;"><?php echo number_format($saldo/$tipocambio,2,'.','');?></td>

						
						
						
						
						
						
					</tr>
				<?php
				}
			}
		}
		}
			?>
	</tbody>
</table>