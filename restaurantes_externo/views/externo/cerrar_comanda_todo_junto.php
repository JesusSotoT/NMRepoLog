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
					<table align="center" style="width:100%; margin-top:15px">
						<tr style="width:100%">
							<td style="width:100%">
								<div id="receipt_header" style="text-align:center;">
									<div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
									<div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">RFC: <?php echo $organizacion[0]['RFC'];?></div>
									<div id="company_address" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo utf8_decode($datos_sucursal[0]['direccion']." ".$datos_sucursal[0]['municipio'].",".$datos_sucursal[0]['estado']);?></div>
								<?php 
									if($organizacion[0]['paginaweb']!='-'){
										echo '<div id="paginaWeb" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;">'.$organizacion[0]['paginaweb'].'</div>';	
									}
								?>
								<div id="sucursal" style="width: 100%;text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;border-bottom:3px solid;">Sucursal: <?php echo $datos_sucursal[0]["nombre"]; ?></div>
								
							</div>
						</td>
						</tr>
						<tr>
							<td>
								<div id="receipt_general_info" style="text-align:center;">
									<div style="width: 5%; float: left;">&nbsp;</div>
									<div id="employee" style="width: 55%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesero: <?php  echo $objeto['mesero']; ?></div>
									<div id="persons" style="width: 35%; float: left; text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php if($objeto['cerrar_persona'] == 1) { ?> Persona: <?php echo $objeto['persona'] ?> <?php } else { ?> Personas: <?php echo $objeto['personas'] ?><?php } ?></div>
									
								</div>
							</td>
						</tr>
					</table>
			<?php } ?>
			<table align="center" style="<?php if ($que_mostrar["mostrar_info_empresa"] != 1) { ?> margin-top:15px; <?php }?> width:100%">
				<tr><td>
					<div style="width: 5%; float: left;">&nbsp;</div>
					<?php if ($value['tipo'] != 2 && $value['tipo'] != 1 && is_numeric($value['nombreu'])) { ?>
							 			<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa: #<?php echo $comanda['rows'][0]['nombre_mesa']; ?></div>
									<?php } else { ?>
										<div id="mesa" style="width: 45%; float: left; text-align: left;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa: <?php echo $comanda['rows'][0]['nombre_mesa']; ?></div>
									<?php } ?>
									<div id="comand" style="width: 45%; float: left; text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $value['codigo']; ?></div><br>
				<?php
			if ($bandera != 1) {
				$bandera = 1;
			
			// Para llevar
				if($value['tipo'] == "1"){ 
					if($que_mostrar["mostrar_nombre"] == 1) { ?>
				     	<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial; ">
				        	Cliente: <?php echo $value['nombreu']; ?>
				     	</div><?php
				     }
				     if($que_mostrar["mostrar_domicilio"] == 1) { 
				     	if($value['domicilio']){ ?>
				     		<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
					    		Domicilio: <?php echo $value['domicilio']; ?>
					    	</div><?php
					 	}
					 }
					 if($que_mostrar["mostrar_tel"] == 1) { 
					 	if($comanda['tel']){ ?>
					 		<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
					    		Tel: <?php echo $comanda['tel']; ?>
					  		</div><?php
					 	}
					 }
				} //FIN para llevar
				
			// Servicio a domicilio
				if($value['tipo'] == "2"){ 
					if($que_mostrar["mostrar_nombre"] == 1) { ?>
				     	<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
				        	Cliente	: <?php echo $value['nombreu']; ?>
				     	</div><?php
				     }
				     if($que_mostrar["mostrar_domicilio"] == 1) { 
				     	if($value['domicilio']){ ?>
				     		<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
					    		Domicilio: <?php echo $value['domicilio']; ?>
					    	</div><?php
					 	}
					 }
					 if($que_mostrar["mostrar_tel"] == 1) { 
					 	if($comanda['tel']){ ?>
					 		<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
					    		Tel: <?php echo $comanda['tel']; ?>
					  		</div><?php
					 	}
					 }
				} //FIN Servicio a domicilio
			} ?>
		</td></tr></table>
		<?php
		}//FIN cabecera 

		if($persona != $value['npersona']){ ?>
			<?php if($total_persona > 0) { ?>
				<div style="text-align: right;border-bottom:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:10px">
					Total de la orden No. <?php echo $persona?>: <strong>$<?php echo $total_persona ?></strong>
				</div>
			<?php } ?>
			<div style="text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;margin-top:10px; border-bottom:3px solid;">
				Orden No: <?php echo $value['npersona'] ?>
			</div>
			
				<div class="row" style="font-weight: bold; font-size:15px; font-family: Tahoma,'Trebuchet MS',Arial;">
					<div class="col-xs-3">Cant.</div>
					<div class="col-xs-5">Producto</div>
					<div class="col-xs-4"  style="text-align: center">Total</div>
				</div>
			<?php
			
			$total_persona = 0;
			$persona = $value['npersona'];
			$codigo = $value['codigo'];
		}
		?> 
		<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;">
			<div class="col-xs-3" style="text-align: center"><?php echo $value['cantidad'] ?></div>
			<div class="col-xs-5"><?php echo $value['nombre'] ?></div>
			<div class="col-xs-4" style="text-align: center" >$<?php echo number_format(round(($value['precioventa'] * $value['cantidad']), 2),2,'.',',') ?></div>
		</div>

		<?php if(!empty($value['promociones'])) { 
			foreach ($value['promociones'] as $key5 => $value5) { ?>
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;">
					<div class="col-xs-3" style="text-align: center"></div>
					<div class="col-xs-5"><?php echo $value5['nombre'] ?></div>
					<div class="col-xs-4" style="text-align: center" >$ 0</div>
				</div>
		<?php }
		} ?>
		<?php
		
		if($value['costo_extra']){
			$costo_extra = 0;
			
			foreach ($value['costo_extra'] as $k => $v) { ?>
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;">
					<div class="col-xs-3"></div>
					<div class="col-xs-5">=> Extra: <?php echo $v['nombre'] ?></div>
					<div class="col-xs-4" align="right">$ <?php echo number_format(round(($v['costo'] * $value['cantidad']), 2),2,'.',',') ?></div>
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
				<div class="row" style="font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial;">
					<div class="col-xs-3"></div>
					<div class="col-xs-5">=> Complemento: <?php echo $v['nombre'] ?></div>
					<div class="col-xs-4" align="right">$ <?php echo number_format(round(($v['costo'] * $value['cantidad']), 2),2,'.',',') ?></div>
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
				<div style="text-align: right;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:10px">
					Total de la orden No. <?php echo $persona?>: <strong>$<?php echo number_format($total_persona,2,'.',',') ?></strong>
				</div>
			<?php } 
	} // FIN foreach
	
	$promedio_comensal = ($promedio_comensal / $objeto['num_comensales']); 
	
	if($comanda['mostrar'] == 1){ 
		$propina = $total_comanda * 0.10; ?>
		<div style="text-align: right; border-top:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:5px">
       		Propina sugerida: $<?php echo number_format(round($propina, 2),2,'.',','); ?>
      	</div><?php
	} if($que_mostrar["mostrar_iva"] == 1){  ?>
		<div style="text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
       		IVA incluido.
      	</div><?php
	} ?>


	
	<div style="text-align: right;border-bottom:1px solid;font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;padding:5px;margin-top:2	px">
		Total: <strong>$<?php echo number_format($total_comanda,2,'.',',') ?></strong>
	</div>
	<div style="text-align: center; margin-top:10px;">
		<img id="<?php echo $codigo ?>" style="width:190px;margin-left:-3px;"/>
	</div>
	<div id="company_name" style=" text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">Documento sin ninguna validez oficial</div>
	<div id="company_name" style="text-align: right; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">by Foodware.</div>
</div>
<?php } ?>