<!-- Carga las librerias -->
<meta charset="utf-8">
<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->


<script src="js/comandas/comandera.js"></script>
<script src="js/comandas/comandas.js"></script>
<script type="text/javascript"></script>
<?php 
	if($comanda['rows'][1]['tipo'] == "1" || $comanda['rows'][1]['tipo'] == "2"){
		$for = 2;
	} else {
		$for = 1;
	}
	for ($x=0; $x < $for; $x++) { ?>
<div style="text-align:left;font-size:12px; font-style: bold;">
	<?php foreach ($comanda['rows'] as $key => $value) {
		$total_persona = 0;
		$codigo = $value['codigo'];
		$impuestos = 0; 
	
	// Valida que tenga logo
		if (!empty($comanda['logo'])) { ?>
		
			<div style="text-align: center; text-shadow: solid; font-style: bold;">
				<input type="image" src="<?php echo $comanda['logo'] ?>" style="width:180px; font-style: bold;"/>
			</div><?php
		} ?>
		
		<?php
		
		if($que_mostrar["switch_info_ticket"] == 1) {
	// Para llevar
		if ($que_mostrar["mostrar_info_empresa"] == 1) { ?>
					<table align="center" style="margin-top:15px; width:100% ; font-style: bold;">
						<tbody style="width: 100%;">

						<tr>
							<td>
								<div id="receipt_header" style="text-align:center; font-style: bold;">
									<div id="company_name" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
									<?php if(!empty($organizacion[0]['RFC'])) { ?>
									<div id="company_name" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">RFC: <?php echo $organizacion[0]['RFC'];?></div>
									<?php } ?>
									<div id="company_address" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo utf8_decode($datos_sucursal[0]['direccion']." ".$datos_sucursal[0]['municipio'].",".$datos_sucursal[0]['estado']);?></div>


								<?php 
									if($organizacion[0]['paginaweb']!='-'){
										echo '<div id="paginaWeb" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial; font-style: solid;">'.$organizacion[0]['paginaweb'].'</div>';	
									}
								?>
								<div id="sucursal" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; border-bottom:3px solid; font-style: bold;">Sucursal:<?php echo $datos_sucursal[0]["nombre"]; ?></div>
								<div id="sale_ini" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial;">
									Apertura: <?php echo $objeto['f_ini'] ;?>
								</div>
								<div id="sale_fin" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold; ">Cierre: <?php echo $fecha_fin;?></div>
							</div>
						</td>
						</tr>
						<tr>
							<td>
								<div id="receipt_general_info" style="text-align:center;">
									<div id="employee" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
										Mesero: <?php  echo $objeto['mesero']; ?>
									</div>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
			<?php } ?>
			<table align="center" style="margin-top:15px; width:100% ; font-style: bold;">
			<tbody style="width: 100%;">
				<tr><td>
					<div style="width: 5%; float: left; font-style: bold;">&nbsp;</div>
					<?php if ($value['tipo'] != 2 && $value['tipo'] != 1 && is_numeric($comanda['rows'][1]["nombre_usuario"])) { ?>
							 			<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Mesa: #<?php echo $comanda['rows'][1]["nombre_usuario"]; ?></div>
									<?php } else { ?>
										<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Mesa: <?php echo $comanda['rows'][1]["nombre_usuario"] ?></div>
									<?php } ?>
									<div id="comand" style="width: 45%; float: left; text-align: right; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo $value['codigo']; ?></div>
									</td></tr></tbody></table>

		<table align="center" style="margin-top:15px; width:100% ; font-style: bold;">
						<tbody style="width: 100%;">
			<?php
		if($value['tipo'] == "1"){ 
			if($que_mostrar["mostrar_nombre"] == 1) { ?>
		     	<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
		          	Cliente: <?php echo $objeto['nombre'] ?>
		     	</div>
		     <?php }
		     if($que_mostrar["mostrar_domicilio"] == 1) {
		     	if($value['domicilio']){ ?>
		     		<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
			    		Domicilio: <?php echo $value['domicilio']; ?>
			    	</div><?php
				}
			 }
			 if($que_mostrar["mostrar_tel"] == 1) {
			 	if($comanda['tel']){ ?>
			 		<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
			    		Tel: <?php echo $comanda['tel']; ?>
			  		</div><?php
			 	}
			 }
		}


		 //FIN para llevar
		
	// Servicio a domicilio
		if($value['tipo'] == "2"){ 
			if($que_mostrar["mostrar_nombre"] == 1) { ?>
		     	<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
		          	Cliente: <?php echo $objeto['nombre'] ?>
		    	 </div>
		     <?php }
		     if($que_mostrar["mostrar_domicilio"] == 1) {
		     	if($value['domicilio']){ ?>
		     		<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
			    		Domicilio: <?php echo $value['domicilio']; ?>
			    	</div><?php
			 	}
			 }
			 if($que_mostrar["mostrar_tel"] == 1) {
			 	if($comanda['tel']){ ?>
			 		<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
			    		Tel: <?php echo $comanda['tel']; ?>
			  		</div><?php
			 	}
			 }
		}
		?>
		</td></tr>
			</tbody>
					</table>
		<?php
		} //FIN Servicio a domicilio
		?>

			
		<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:10px; border-bottom:3px solid; font-style: bold;">
			Orden No: <?php echo $key ?>
		</div>
		<table border='0' style="width: 100%;  font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;" align="center">
						<tbody style="width: 100%;">
				<th class="col-xs-3" style="font-style: bold; text-align: center;">Cant.</th>
				<th class="col-xs-3" style="font-style: bold; text-align: center;">Prod.</th>
				<th class="col-xs-3" style="text-align: center; font-style: bold;">P. U.</th>
				<th class="col-xs-3" style="text-align: center; font-style: bold;">Total</th>
				</tbody>
				</table>
		
		<?php
		foreach ($value['pedidos'] as $c => $cc) { ?>

			<div class="row" style="font-size:9px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
			<table border='0' style="width: 100%;  font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;" align="center">
						<tbody style="width: 100%;">
				<td class="col-xs-3" style="text-align: center; font-style: bold;"><?php echo $cc['cantidad'] ?></td>
				<td class="col-xs-3" style="font-style: bold; text-align: center;"><?php echo $cc['nombre'] ?></td>
				<td class="col-xs-3" style="text-align: center; font-style: bold;">$<?php echo $cc['precioUnitarito'] ?></td>
				<td class="col-xs-3" style="text-align: center; font-style: bold;">$<?php echo number_format(round(($cc['precioventa'] * $cc['cantidad']), 2),2,'.',',') ?></td>
				</tbody>
				</table>
			</div>
			<?php if(!empty($cc['promociones'])) { 
			foreach ($cc['promociones'] as $key5 => $value5) { ?>
			<table border='0' style="width: 100%;  font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;" align="center">
						<tbody style="width: 100%;">
					<td class="row" style="font-size:9px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
						<td class="col-xs-3" style="text-align: center; font-style: bold;"></td>
						<td class="col-xs-3" style="font-style: bold; "><?php echo $value5['nombre'] ?></td>
						<td class="col-xs-3" style="font-style: bold;"></td>
						<td class="col-xs-3" style="text-align: center font-style: bold;" >$ 0</td>
					</td>
					</tbody>
					</table>
			<?php }
			} ?>
			
			<?php
			
			if($cc['costo_extra']){
				$costo_extra = 0;
				
				foreach ($cc['costo_extra'] as $k => $v) { ?>
				
					<div class="row" style="font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
						<div class="col-xs-3"></div>
						<div class="col-xs-6" style="font-style: bold;">=> Extra: <?php echo $v['nombre'] ?></div>
						<div class="col-xs-3" style="text-align: center; font-style: bold;">$ <?php echo number_format(round(($v['costo'] * $value['cantidad']), 2),2,'.',',') ?></div>
					</div><?php
					
					$costo_extra += round(($v['costo'] * $cc['cantidad']), 2);		
				}
				
			// Calcula totales
				$total_persona += $costo_extra;
				$total_comanda += $costo_extra;
			} //Fin costo extra
			
			if($cc['costo_complementos']){
				$costo_extra = 0;
				
				foreach ($cc['costo_complementos'] as $k => $v) { ?>
					<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
						<div class="col-xs-3" style="font-style: bold;"></div>
						<div class="col-xs-6" style="font-style: bold;">=> Complemento: <?php echo $v['nombre'] ?></div>
						<div class="col-xs-3" style="text-align: center; font-style: bold;">$ <?php echo number_format(round(($v['costo'] * $cc['cantidad']), 2),2,'.',',') ?></div>
					</div><?php
					
					$costo_extra += round(($v['costo'] * $cc['cantidad']), 2);
				}
			
			// Calcula totales
				$total_persona += $costo_extra;
				$total_comanda += $costo_extra;
			} //Fin costo complementoss
		
			$total_persona += ($cc['precioventa'] * $cc['cantidad']);
			$total_comanda += ($cc['precioventa'] * $cc['cantidad']);
			$impuestos += ($cc['$impuestos'] * $cc['cantidad']);
			$promedio_comensal += $total_persona;
		} //Fin foreach pedidos
		
		if($comanda['mostrar'] == 1){ 
			$propina = $total_persona * 0.10; ?>
			<div style="text-align: right; border-top:1px solid;font-size:13px; font-style: bold; font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:5px">
	       		Propina sugerida: $<?php echo number_format(round($propina, 2),2,'.',','); ?>
	      	</div><?php
		} if($que_mostrar["mostrar_iva"] == 1){  ?>
	
			<div style="text-align: right; margin-right: 15px; font-size:13px; font-style: bold; font-family: Tahoma,'Trebuchet MS',Arial;">
			<br><br>
	       		IVA incluido.
	      	</div><?php
		} ?> 

		<?php 
			$tipocambio = ($total_comanda/$tipocambio);
		 ?>
	 	
		<div style="text-align:right; border-bottom:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:2px; font-style: bold;">
			Total: <strong>$<?php echo number_format($total_persona,2,'.',',') ?></strong><br>

			<?php  
				if ($comanda['mostrar_dolares'] == 1) {
					?>
						Dólar americano: <strong>$<?php echo number_format($tipocambio,2,'.',',') ?></strong>
					<?php	
				}
			?>

		</div>
		<div style="text-align:center; margin-top:10px; font-style: bold;">
			<img id="<?php echo $codigo ?>" style="width:190px;margin-left:-3px; font-style: bold;"/>
		</div>
		<div id="company_name" style=" text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Documento sin ninguna validez oficial</div>
		<div id="company_name" style="text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">by Foodware.</div>
		<div style="height: 20px; font-style: bold;">&nbsp;</?>
		<script>
	// Carga el codigo de barras
			comandas.codigo_barras({
				id: '<?php echo $codigo ?>', 
				codigo: '<?php echo $codigo ?>'
			});
		</script><?php
	}

	$promedio_comensal = ($promedio_comensal / $objeto['num_comensales']); ?>

	</div>
	</tbody>
	</table>
	</div>

</div>

<script>
// Guarda el promedio por comensa
	comandera.guardar_promedio_comensal({
		promedio : <?php echo $promedio_comensal ?>,
		comanda : <?php echo $objeto['idComanda'] ?>,
		personas : <?php echo $objeto['num_comensales']?>
	});

// Guarda el total de la comanda
	comandas.actualizar_comanda({
		id: <?php echo $objeto['idComanda'] ?>, 
		total: <?php echo $total_comanda ?>
	});
</script
<?php } ?>