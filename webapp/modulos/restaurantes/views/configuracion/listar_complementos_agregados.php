<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title"><strong>Complementos</strong></h4>
    </div>
    <div class="panel-body">
    	<table class="table table-striped table-bordered" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		            <th align="center"><strong>Cantidad</strong></th>
		            <th align="center"><strong>Insumo</strong></th>
		            <th><strong>Unidad</strong></th>
		            <th align="center"><strong>Costo</strong></th>
		            <th align="center"><strong>Sucursal</strong></th>		            
		        </tr>
		    </thead>
		    <tbody><?php			          	
			    foreach ($datos as $k => $v) { ?>
			    	<tr>
			    		<td>
			    			<div class="input-group">
			    				<span class="input-group-addon"  id="loader_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
			    				<input 
			    					onkeyup="configuracion.actualizar_cantidad_complementos({id:<?php echo $v['id'] ?>, cantidad:$(this).val()})" 
			    					type="number" 
			    					min="0"
			    					value="<?php echo $v['cantidad'] ?>" 
			    					id="cantidad_<?php echo $v['id'] ?>" 
			    					class="form-control"/>
							</div>
			    		</td>
			    		<td align="center">
			    			<?php echo $v['nombre'] ?>
			    		</td>
			    		<td>
			    			<?php echo $v['unidad'] ?>
			    		</td>
			    		<td align="center">
			    			$ <?php echo number_format($v['costo'], 2, '.', ''); ?>
			    		</td>
			    		<td>
			    			<select id="sucursal_<?php echo $v['id']; ?>" onchange="configuracion.actualizar_sucursales_complementos(<?php echo $v['id']; ?>)" class="selectpicker" data-width="80%" multiple>
								<?php 
									$arraSuc = explode(',', $v['sucursales']);

									foreach ($sucursales as $key => $value) {										
										if (in_array($value['idSuc'], $arraSuc)){									
											echo '<option value="'.$value['idSuc'].'" selected>'.$value['nombre'].'</option>';
										}else{
											echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
										}																				
									}
								 ?>														
							</select>
			    		</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
    </div>
</div>
<script>
	$('.selectpicker').selectpicker('refresh');


</script>
