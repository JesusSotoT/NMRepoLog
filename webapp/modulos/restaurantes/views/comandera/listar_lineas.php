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

foreach ($lineas as $key => $value) { ?>
	<button
		class="btn btn-lg btn-departamento"
		onclick="comandas.buscar_productos({
			linea: '<?php echo $value['idLin'] ?>',
			comanda : comandera['datos_mesa_comanda']['id_comanda'],
			div : '<?php echo $objeto['div_productos'] ?>'
		})">
		<div class="row">       
			<div style="width:200px;">          
				<?php echo substr($value['nombre'], 0, 20)  ?>  
			</div>    
		</div> 
	</button><?php
}
?>