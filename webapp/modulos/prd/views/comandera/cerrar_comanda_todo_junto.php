<!-- Carga las librerias -->
<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

<script src="js/comandas/comandas.js"></script>

<div style="text-align:left;font-size:14px"><?php
// Valida que tenga logo
	if (!empty($comanda['logo'])) { ?>
		<div>
			<input type="image" src="<?php echo $comanda['logo'] ?>" style="width:180px"/>
		</div><?php
	} ?>
		
	<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">
		Mesa: <?php echo $comanda['rows'][0]['nombre_mesa']; ?>
	</div><?php
	
	foreach ($comanda['rows'] as $key => $value) {
		if($persona != $value['npersona']){ ?>
			<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">
				Orden No: <?php echo $value['npersona'] ?>
			</div><?php
			
			$persona = $value['npersona'];
			$codigo = $value['codigo'];
		}
	
	// Cabecera
		if ($bandera != 1) {
			$bandera = 1;
		
		// Para llevar
			if($value['tipo'] == "1"){ ?>
			     <div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			          Nombre: <?php echo $value['nombreu']; ?>
			     </div><?php
			     
			     if($value['domicilio']){ ?>
			     	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
				    	Domicilio: <?php echo $value['domicilio']; ?>
				    </div><?php
				 }
				 
				 if($comanda['tel']){ ?>
				 	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
				    	Tel: <?php echo $comanda['tel']; ?>
				  	</div><?php
				 }
			} //FIN para llevar
			
		// Servicio a domicilio
			if($value['tipo'] == "2"){ ?>
			     <div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			          Nombre: <?php echo $value['nombreu']; ?>
			     </div><?php
			     
			     if($value['domicilio']){ ?>
			     	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
				    	Domicilio: <?php echo $value['domicilio']; ?>
				    </div><?php
				 }
				 
				 if($comanda['tel']){ ?>
				 	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
				    	Tel: <?php echo $comanda['tel']; ?>
				  	</div><?php
				 }
			} //FIN Servicio a domicilio
		} //FIN cabecera ?> 
		
		<div class="row" style="font-size:12px; font-family:Arial;">
			<div class="col-xs-1"><?php echo $value['cantidad'] ?></div>
			<div class="col-xs-6"><?php echo $value['nombre'] ?></div>
			<div class="col-xs-4" align="right"><?php echo round(($value['precioventa'] * $value['cantidad']), 2) ?></div>
		</div><?php
		
		if($value['costo_extra']){
			$costo_extra = 0;
			
			foreach ($value['costo_extra'] as $k => $v) { ?>
				<div class="row" style="font-size:12px; font-family:Arial;">
					<div class="col-xs-1"></div>
					<div class="col-xs-6">=> Extra: <?php echo $v['nombre'] ?></div>
					<div class="col-xs-4" align="right">$ <?php echo round(($v['costo'] * $value['cantidad']), 2) ?></div>
				</div><?php
				
				$costo_extra += round(($v['costo'] * $value['cantidad']), 2);
			}
		
		// Calcula totales
			$total_persona += $costo_extra;
			$total_comanda += $costo_extra;
		} //Fin costo extra
		
		$total_persona += ($value['precioventa'] * $value['cantidad']);
		$total_comanda += ($value['precioventa'] * $value['cantidad']);
		$impuestos += ($value['$impuestos'] * $value['cantidad']);
		$promedio_comensal += $total_persona;
	} // FIN foreach
	
	$promedio_comensal = ($promedio_comensal / $objeto['num_comensales']); 
	
	if($comanda['mostrar'] == 1){ 
		$propina = $total_comanda * 0.10; ?>
		<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
       		Propina sugerida: <?php echo round($propina, 2); ?>
      	</div><?php
	} ?>
	 
	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
		Total: <strong>$<?php echo $total_comanda ?></strong>
	</div>
	<div style="margin-top:10px;">
		<img id="<?php echo $codigo ?>" style="width:190px;margin-left:-3px;"/>
	</div>
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