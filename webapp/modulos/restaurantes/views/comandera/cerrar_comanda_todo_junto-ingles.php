<!-- Carga las librerias -->
<meta charset="utf-8">
<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->

<link rel="stylesheet" rev="stylesheet" href="css/netpos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/netpos_print.css"  media="print"/>
<script id="scriptAccion" type="text/javascript"></script>

<script src="js/comandas/comandas.js"></script>
<h1>chais</h1>
<div style="text-align:left;font-size:12px">
<?php
	if($comanda['rows'][0]['tipo'] == "1" || $comanda['rows'][0]['tipo'] == "2"){
		$for = 2;
	} else {
		$for = 1;
	}
	for ($x=0; $x < $for; $x++) { 
		$total_persona = 0;
		$total_comanda = 0;
		$propina = 0;
		$persona = 0;
		$bandera = 2;
// Valida que tenga logo 
$ya_mesa = 0;
	if (!empty($comanda['logo'])) { ?>
		<div style="text-align: center">
			<input type="image" src="<?php echo $comanda['logo'] ?>" style="width:180px"/>
		</div><?php
	}
	
	foreach ($comanda['rows'] as $key => $value) {

	// Cabecera
		if($que_mostrar["switch_info_ticket"] == 1 && $ya_mesa==0) {
			$ya_mesa = 1;
			if ($que_mostrar["mostrar_info_empresa"] == 1) { ?>
					
								<div id="receipt_header" style="text-align:center; font-style: bold;">
									<div id="company_name" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
									<?php if(!empty($organizacion[0]['RFC'])) { ?>
									<div id="company_name" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">RFC: <?php echo $organizacion[0]['RFC'];?></div>
									<?php } ?>
									<div id="company_address" style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo utf8_decode($datos_sucursal[0]['direccion']." ".$datos_sucursal[0]['municipio'].",".$datos_sucursal[0]['estado']);?></div>
								<?php 
									if($organizacion[0]['paginaweb']!='-'){
										echo '<div id="paginaWeb" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial; font-style: bold;">'.$organizacion[0]['paginaweb'].'</div>';	
									}
								?>
								<div id="sucursal" style="width: 100%;text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial;border-bottom:3px solid; font-style: bold;">Sucursal: <?php echo $datos_sucursal[0]["nombre"]; ?></div>
								
								<div id="sale_ini" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Apertura: <?php echo $objeto['f_ini'];?></div>
								<div id="sale_fin" style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Cierre: <?php echo $fecha_fin;?></div>
							</div>
						</td>
						</tr>
						<tr>
							<td>
								<div id="receipt_general_info" style="text-align:center; font-style: bold;">
									<div style="width: 5%; float: left; font-style: bold;">&nbsp;</div>
									<div id="employee" style="width: 55%; float: left; text-align: left; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Mesero: <?php  echo $objeto['mesero']; ?></div>
									<div id="persons" style="width: 35%; float: left; text-align: right;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php if($objeto['cerrar_persona'] == 1) { ?> Persona: <?php echo $objeto['persona'] ?> <?php } else { ?> Personas: <?php echo $objeto['personas'] ?><?php } ?></div>
									
								</div>
							</td>
						</tr>
						</tbody>
					</table>
			<?php } ?>
			<table align="center" style=" font-style: bold; <?php if ($que_mostrar["mostrar_info_empresa"] != 1) { ?> margin-top:15px; <?php }?> width:100%">
			<tbody style="width: 100%;">
				<tr><td>
					<div style="width: 5%; float: left; font-style: bold;">&nbsp;</div>
					<?php if ($value['tipo'] != 2 && $value['tipo'] != 1 && is_numeric($value['nombreu'])) { ?>
							 			<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Mesa: #<?php echo $comanda['rows'][0]['nombre_mesa']; ?></div>
									<?php } else { ?>
										<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Mesa: <?php echo $comanda['rows'][0]['nombre_mesa']; ?></div>
									<?php } ?>
									<div id="comand" style="width: 45%; float: left; text-align: right; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;"><?php echo $value['codigo']; ?></div><br>
				<?php
			if ($bandera != 1) {
				$bandera = 1;
			
			// Para llevar
				if($value['tipo'] == "1"){ 
					if($que_mostrar["mostrar_nombre"] == 1) { ?>
				     	<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
				        	Cliente: <?php echo $value['nombreu']; ?>
				     	</div><?php
				     }
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
				} //FIN para llevar
				
			// Servicio a domicilio
				if($value['tipo'] == "2"){ 
					if($que_mostrar["mostrar_nombre"] == 1) { ?>
				     	<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
				        	Cliente	: <?php echo $value['nombreu']; ?>
				     	</div><?php
				     }
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
				} //FIN Servicio a domicilio
			} ?>
		</td></tr>
	
			</tbody>

		</table>
		<?php
		}//FIN cabecera 

		if($persona != $value['npersona']){ ?>
			<?php if($total_persona > 0) { ?>

				

				<div style="text-align: right;border-bottom:1px solid;font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:10px; font-style: bold;">
					Total de la orden No. <?php echo $persona?>: <strong>$<?php echo $total_persona ?></strong>
				</div>
			<?php } ?>
			<div style="text-align: center; font-size:10px;font-family: Tahoma,'Trebuchet MS',Arial;margin-top:10px; border-bottom:3px solid; font-style: bold;">
				Orden No: <?php echo $value['npersona'] ?>
			</div>
			<table border='0' style="width: 100%;  font-style: bold; font-weight: bold; font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;" align="center">
						<tbody style="width: 100%;">
			
		
		<th style="width:20%; text-align: center;">Cant</th>
		<th style="width:40%; text-align: center;">Producto</th>
		<th style="text-align:center;">P. U.</th>
		<th style="width:20%;text-align:center;">Total</th>

	</tbody>
	</table>

			<?php
			
			$total_persona = 0;
			$persona = $value['npersona'];
			$codigo = $value['codigo'];
		}
		?> 
		<?php $cant = sizeof($value['promociones']); 
			if($cant > 0){
				$value['cantidad'] = $cant;
			}
		?>

		<?php 
			///// extras en la misma linea ch@
			if($que_mostrar["una_linea"] == 2){
				if($value['costo_extra']){
					$costo_extra = 0;
					foreach ($value['costo_extra'] as $k => $v) {
						//$costoT = $v['costo'] * $value['cantidad'];
						$value['precioventa'] += $v['costo'];
					}

				}
				if($value['costo_complementos']){
					$costo_extra = 0;
					foreach ($value['costo_complementos'] as $k => $v) {
						$value['precioventa'] += $v['costo'];
					}
				}
			}				
			///// extras en la misma linea ch@ fin
		 ?>
		<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
		<table border='0' style="width: 100%;  font-size:9px;  font-family: Tahoma,'Trebuchet MS',Arial;" align="center">
						<tbody style="width: 100%;">
			<td class="col-xs-3" style="width: 20%; text-align: center; "><?php echo $value['cantidad'] ?></td>
			<td class="col-xs-3" style="width: 40%; text-align: center;"><?php echo $value['nombre'] ?></td>
			<td class="col-xs-3" style=" width: 30%; text-align: center;">$<?php echo $value['precioUnitarito']; ?></td>
			<td class="col-xs-3" style=" width: 20%;text-align: center; font-style: bold;" >$<?php echo number_format(round(($value['precioventa'] * $value['cantidad']), 2),2,'.',',') ?></td>
				<tr>
			</tr>

		</tbody>
		</table>

		<?php if(!empty($value['promociones'])) { 
			foreach ($value['promociones'] as $key5 => $value5) { ?>
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
					<div class="col-xs-3" style="text-align: center font-style: bold;"></div>
					<div class="col-xs-3" style="font-style: bold; text-align: center;"><?php echo $value5['nombre'] ?></div>
					<div class="col-xs-3"></div>
					<div class="col-xs-3" style="text-align: center; font-style: bold;" >$ 0</div>
				</div>
		<?php }
		} ?>
		<?php
		
		if($value['costo_extra']){
			$costo_extra = 0;
			
			foreach ($value['costo_extra'] as $k => $v) { ?>
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
					<div class="col-xs-3"></div>
					<div class="col-xs-6" style="font-style: bold;">=> Extra: <?php echo $v['nombre'] ?></div>
					<div class="col-xs-3" align="right" style="font-style: bold; text-align: center;">$ <?php echo number_format(round(($v['costo'] * $value['cantidad']), 2),2,'.',',') ?></div>
				</div><?php
				
				$costo_extra += round(($v['costo'] * $value['cantidad']), 2);
			}
		
		// Calcula totales
			$total_persona += $costo_extra;
			$total_comanda += $costo_extra;
		} //Fin costo extra
		
		if($value['costo_complementos']){
			$costo_extra = 0;
			
			foreach ($value['costo_complementos'] as $k => $v) { ?>
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">
					<div class="col-xs-3"></div>
					<div class="col-xs-6" style="font-style: bold;">=> Complemento: <?php echo $v['nombre'] ?></div>
					<div class="col-xs-3" align="right" style="font-style: bold;">$ <?php echo number_format(round(($v['costo'] * $value['cantidad']), 2),2,'.',',') ?></div>
				</div><?php
				
				$costo_extra += round(($v['costo'] * $value['cantidad']), 2);
			}
		
		// Calcula totales
			$total_persona += $costo_extra;
			$total_comanda += $costo_extra;
		} //Fin costo complementoss
		
		$total_persona += ($value['precioventa'] * $value['cantidad']);
		$total_comanda += ($value['precioventa'] * $value['cantidad']);
		$impuestos += ($value['$impuestos'] * $value['cantidad']);
		$promedio_comensal += $total_persona;

		if($total_persona > 0 && $key == count($comanda["rows"])-1) { ?>
				<div style="border-top: 1px solid;text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:10px; font-style: bold;">
					Total de la orden No. <?php echo $persona?>: <strong>$<?php echo number_format($total_persona,2,'.',',') ?></strong>
				</div>


	
	
			<?php } 

	} // FIN foreach
	
	$promedio_comensal = ($promedio_comensal / $objeto['num_comensales']); 
	
	if($comanda['mostrar'] == 1){ 
		$propina = $total_comanda * 0.10; ?>
		<div style="text-align: right; border-top:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:5px font-style: bold;">
       		Propina sugerida: $<?php echo number_format(round($propina, 2),2,'.',','); ?>
      	</div><?php
	} if($que_mostrar["mostrar_iva"] == 1){  ?>
		<div style="text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;font-style: bold; margin-right: 15px;">
       		IVA incluido.
      	</div><?php
	} ?>
	
	<?php 
		$tipocambio = ($total_comanda/$tipocambio);
	 ?>
	
	<div style="text-align: right;border-bottom:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:2	px; font-style: bold;">
		Total: <strong>$<?php echo number_format($total_comanda,2,'.',',') ?></strong><br>
		
		<?php  
			if ($comanda['mostrar_dolares'] == 1) {
				?>
					Dólar americano: <strong>$<?php echo number_format($tipocambio,2,'.',',') ?></strong>
				<?php	
			}
		?>
			
	</div>
	<div style="text-align: center; margin-top:10px; font-style: bold;">
		<img id="<?php echo $codigo ?>" style="width:190px;margin-left:-3px; font-style: bold;"/>
	</div>
	<div id="company_name" style=" text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">Documento sin ninguna validez oficial</div>
	<div id="company_name" style="text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; font-style: bold;">by Foodware.</div>

	

</div><?php

if (!empty($objeto['id_comanda_padre'])) { ?>
	<script>
	// Guarda el total de la comanda
		comandas.actualizar_comanda({
			id: '<?php echo $objeto['id_comanda_padre'] ?>', 
			total: 'total - <?php echo $total_comanda ?>', 
		});
	</script><?php
} ?>

<script>
// Carga el codigo de barras
	comandas.codigo_barras({
		id: '<?php echo $codigo ?>', 
		codigo: '<?php echo $codigo ?>'
	});
	
// Guarda el promedio por comensa
	comandera.guardar_promedio_comensal({
		promedio : '<?php echo $promedio_comensal ?>',
		comanda : '<?php echo $objeto['idComanda'] ?>',
		personas : '<?php echo $objeto['num_comensales']?>'
	});

// Guarda el total de la comanda
	comandas.actualizar_comanda({
		id: '<?php echo $objeto['idComanda'] ?>', 
		total: '<?php echo $total_comanda ?>', 
	});
</script>
<?php } ?>