<?php
foreach($productos['rows'] as $value){
// Comprueba si es platillo especial
	$clase = (!empty($value['especial'])) ? 'info' : 'default' ; ?>
	
	<button
		onclick="externo.detalles_producto({
			div : 'div_productos',
			id_producto : <?php echo $value['idProducto'] ?>,
			id_comanda : externo['datos_mesa_comanda']['id_comanda'],
			materiales : <?php echo $value['materiales'] ?>,
			tipo : '<?php echo $value['tipo'] ?>',
			departamento : '<?php echo $value['idDep'] ?>',
			persona : externo.datos_mesa_comanda['persona_seleccionada']
		})"
		class="btn btn-<?php echo $clase ?>" >    
		<div class="row">       
			<div style="width:100px;" class="wrapPro">          
				<label><?php echo substr($value['nombre'], 0, 10)  ?></label>       
			</div>    
		</div>    
		<div class="row">      
			<div>          
				<img 
					type="image" 
					alt=" " 
					style="width:80px; height:80px" 
					src="<?php echo $value['imagen'] ?>">      
			</div>    
		</div>    
		<div class="row">      
			<label>$ <?php echo $value['precioventa'] ?></label>    
		</div>  
	</button> <?php
} ?>