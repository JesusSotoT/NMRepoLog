<?php
// Valida que halla mesas libres
	if (empty($mesas_libres)) {?>
		<div align="center">
			<h3><span class="label label-default"><?php if($idioma == 1) { ?> No hay mesas disponibles <?php } else { ?> No vacant tables <?php }?></span></h3>
		</div><?php
		
		
		return 0;
	}

// $mesas_libres es un array con las comandas viene desde el controlador 
	foreach ($mesas_libres as $key => $value) { ?>
		<div class="pull-left" style="padding:5px">
			<button 
				id="btn_eliminar_mesa_<?php echo $value['mesa'] ?>" 
				type="button" 
				class="btn btn-default btn-lg"  
				onclick="comandas.seleccionar_mesa({
					id_mesa: <?php echo $value['mesa'] ?>
				})" 
				style="width:110px; height: 110px; font-size:13px">
				<h4 style="overflow-x: auto; overflow-y: hidden"><?php echo $value['nombre_mesa'] ?></h4>
				<i class="fa fa-user"></i> <?php echo $value['mesero'] ?>
			</button>
		</div><?php
	} 
?>