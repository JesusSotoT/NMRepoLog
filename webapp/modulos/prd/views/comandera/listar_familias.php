<?php
	foreach ($familias as $key => $value) { ?>
		<button 
			type="button" 
			class="btn btn-default btn-lg" 
			style="font-size:13px; width:130px; margin-top:1%" 
			onclick="comandera.listar_lineas({
				familia: <?php echo $value['idFam'] ?>,
				div: 'div_departamentos',
				div_productos: 'div_productos'
			})">
			<?php echo substr($value['nombre'], 0, 11); ?>
		</button><?php
	}
?>