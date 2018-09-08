<?php
// Valida que halla mesas libres
	if (empty($mesas_libres)) {?>
		<div align="center">
			<h3><span class="label label-default">No hay mesas disponibles</span></h3>
		</div><?php
		
		return 0;
	}

// $mesas_libres es un array con las comandas viene desde el controlador 
	foreach ($mesas_libres as $key => $value) { ?>
		<div class="pull-left" style="padding:5px">
			<button 
				id="btn_juntar_mesa_<?php echo $value['id_mesa'] ?>" 
				type="button" 
				class="btn btn-default btn-lg"  
				onclick="comandas.seleccionar_mesa({
					id_mesa: <?php echo $value['id_mesa'] ?>
				})" 
				style="width:110px; font-size:13px">
				<h3><?php echo $value['nombre_mesa'] ?></h3>
				<i class="fa fa-user"></i> <?php echo $value['mesero'] ?>
			</button>
		</div><?php
	} 
?>