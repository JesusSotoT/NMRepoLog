<?php
// $mesas es un array con las comandas viene desde el controlador 
	foreach ($mesas as $key => $value) { ?>
		<div class="pull-left" style="padding:5px">
			<button id="btn_<?php echo $value['mesa'] ?>" type="button" class="btn btn-default btn-lg"  onclick="comandas.asignar({id_mesa:<?php echo $value['mesa'] ?>})" style="width:110px; font-size:13px">
				<h3><?php echo $value['nombre_mesa'] ?></h3>
				<i class="fa fa-user"></i> <?php echo $value['mesero'] ?>
			</button>
		</div><?php		
	} ?>