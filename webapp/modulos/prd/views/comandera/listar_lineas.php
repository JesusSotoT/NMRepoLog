<?php
	foreach ($lineas as $key => $value) { ?>
		<button 
			type="button" 
			class="btn btn-default btn-lg" 
			style="font-size:13px; width:130px; margin-top:1%" 
			onclick="comandas.buscar_productos({
				linea: '<?php echo $value['idLin'] ?>',
				comanda : comandera['datos_mesa_comanda']['id_comanda'],
				div : '<?php echo $objeto['div_productos'] ?>'
			})">
			<?php echo substr($value['nombre'], 0, 11); ?>
		</button><?php
	}
?>