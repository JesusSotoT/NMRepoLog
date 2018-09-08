<?php
	if (empty($complementos)) { ?>
		<div align="center">
			<h3>
				<span class="label label-default">
					<?php if($idioma == 1) { ?> * No existen complementos * <?php } else { ?> * There are no complements * <?php }?>
				</span>
			</h3>
		</div><?php
		
		return 0;
	} 
	
	foreach($complementos as $value){ ?>
		<div class="pull-left" style="padding:5px">
			<button 
				onclick="comandera.agregar_complemento({
					complemento: <?php echo $value['id'] ?>,
					pedido: comandera.datos_mesa_comanda.pedido_seleccionado
				})"
				title="<?php echo $value['nombre'] ?>" 
				type="button" 
				class="btn btn-default" 
				style="width: 103px; height: 148px">
				<div class="row">
					<div class="col-md-12">
						<table>
							<tr>
								<td style="font-size: 12px" align="center">
									<?php echo substr($value['nombre'], 0, 25)  ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input 
							type="image" 
							alt=" " 
							style="width:80px;height:80px" 
							src="<?php echo $value['imagen'] ?>"/>
					</div>
				</div>
				<div class="row">      
					<label>$ <?php echo $value['precio'] ?></label>    
				</div> 
			</button>
		</div><?php
	}
?>
<script>
	console.log('======> Datos comandera');
	console.log(comandera.datos_mesa_comanda);
</script>