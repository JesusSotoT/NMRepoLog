<button
	class="btn btn-lg btn-warning"
	style="height: 50px; width: 100px"
	onclick="comandera.productos = ''; comandera.area_inicio()">
	<div class="row">
		<div>
			<i class="fa fa-undo fa-lg"></i><br />
		</div>
	</div>
</button><?php

foreach ($familias as $key => $value) { ?>
	<button
		class="btn btn-lg btn-departamento"
		onclick="comandera.listar_lineas({
			familia: <?php echo $value['idFam'] ?>,
			div: 'div_departamentos',
			div_productos: 'div_productos'
		})">
		<div class="row">       
			<div style="width:200px;">          
				<?php echo substr($value['nombre'], 0, 20)  ?>  
			</div>    
		</div> 
	</button><?php
}
?>