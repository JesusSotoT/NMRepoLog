<!-- Carga las librerias -->
<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

<script src="js/comandas/comandera.js"></script>
<script src="js/comandas/comandas.js"></script>

<div style="text-align:left;font-size:14px"><?php
	foreach ($comanda['rows'] as $key => $value) {
		$total_persona = 0;
		$codigo = $value['codigo'];
		$impuestos = 0; 
	
	// Valida que tenga logo
		if (!empty($comanda['logo'])) { ?>
			<div>
				<input type="image" src="<?php echo $comanda['logo'] ?>" style="width:180px"/>
			</div><?php
		} ?>
		
		<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">
			Orden No: <?php echo $key ?> /  <?php echo $objeto['nombre'] ?>
		</div><?php
	
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
		
		foreach ($value['pedidos'] as $c => $cc) { ?>
			<div class="row" style="font-size:12px;font-family:Arial;">
				<div class="col-xs-1"><?php echo $cc['cantidad'] ?></div>
				<div class="col-xs-6"><?php echo $cc['nombre'] ?></div>
				<div class="col-xs-4" align="right"><?php echo round(($cc['precioventa'] * $cc['cantidad']), 2) ?></div>
			</div><?php
			
			if($cc['costo_extra']){
				$costo_extra = 0;
				
				foreach ($cc['costo_extra'] as $k => $v) { ?>
					<div class="row" style="font-size:12px;font-family:Arial;">
						<div class="col-xs-1"></div>
						<div class="col-xs-6">=> Extra: <?php echo $v['nombre'] ?></div>
						<div class="col-xs-4" align="right">$ <?php echo round(($v['costo'] * $value['cantidad']), 2) ?></div>
					</div><?php
					
					$costo_extra += round(($v['costo'] * $cc['cantidad']), 2);		
				}
				
			// Calcula totales
				$total_persona += $costo_extra;
				$total_comanda += $costo_extra;
			} //Fin costo extra
			
			$total_persona += ($cc['precioventa'] * $cc['cantidad']);
			$total_comanda += ($cc['precioventa'] * $cc['cantidad']);
			$impuestos += ($cc['$impuestos'] * $cc['cantidad']);
			$promedio_comensal += $total_persona;
		} //Fin foreach pedidos
		
		if($comanda['mostrar'] == 1){ 
			$propina = $total_persona * 0.10; ?>
			<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
	       		Propina sugerida: <?php echo round($propina, 2); ?>
	      	</div><?php
		} ?>
	 	
		<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			Total: <strong>$<?php echo $total_persona ?></strong>
		</div>
		<div style="margin-top:10px;">
			<img id="<?php echo $codigo ?>" style="width:190px;margin-left:-3px;"/>
		</div>
		
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
</script>