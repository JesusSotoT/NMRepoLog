<?php
// $mesas es un array con las comandas viene desde el controlador 
//print_r($mesas);
	foreach ($mesas as $key => $value) { ?>
		<div class="pull-left" style="padding:5px">
			<button id="btn_<?php echo $value['idcomanda'] ?>" type="button" class="btn btn-default btn-lg"  onclick="comandas.asignar({id_mesa:<?php echo $value['idcomanda'] ?>})" style="width:160px; font-size:14px">
				<label style=" font-size:10px"><?php echo $value['idcomanda'] ?></label>
				<h6><?php echo $value['nombre_mesa'] ?></h6>
				<i class="fa fa-motorcycle"></i> <?php echo $value['mesero'] ?>
			</button>
		</div><?php		
	} ?>