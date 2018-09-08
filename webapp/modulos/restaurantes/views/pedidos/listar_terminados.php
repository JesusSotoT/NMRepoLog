<?php
$exist = 0;
// Valida que existan pedidos terminados
if (empty($terminados)) {?>
	<div align="center">
		<h3><span class="label label-default">* Aqui aparecen los pedidos terminados *</span></h3>
	</div><?php
	
	return 0;
} ?>
<div class="row" style="height: 100%;">
	<div class="col-md-1 col-xs-1" style="height: 100%; display: table;">
		<div style="display: table-cell;
    		vertical-align: middle;">
		    <i
			    class="fa fa-caret-left"
			    style="color: #DCB435; float:right; font-size: 11vw;"
			    onclick="pedidos.mover_scroll({
				    direccion: 'izquierda',
				    div: 'div_ter',
				    cantidad: 600
			    })">
			</i>
		</div>
	</div>
	<div class="col-md-10 col-xs-10 div_scroll_x" id="div_ter" style="height: 100%;">
		<?php foreach ($terminados as $key => $value) {
			if(!empty($value['persona']) &&!empty($value['persona'][1]['productos'])){ $exist = 1;?>
			<div
				class="btn btn-coman" id="ter-<?php echo $key?>" style="height: 65vh; cursor: auto; margin: 5px">
				<div class="row" style="width:250px;">       
				   <div class="row" style="height: 8vh; padding:0;border:solid #714789;  border-top-left-radius: 2em;  border-top-right-radius: 2em;margin:0; ">
				   		<div class="col-md-6 col-xs-6" style="text-align:left;  font-size: 2.5vh;">
				   			<span style="margin-left: 15px"><strong style="font-weight: bold">Comanda: </strong><?php echo $key?></span><br>
				   		</div>
				   		<div class="col-md-6 col-xs-6" style="text-align:right;  font-size: 2.5vh;">
				   			<span style="margin-right: 15px"><strong style="font-weight: bold"><?php echo $value['hora']?></strong></span>
				   		</div>
				   		<div class="col-md-12 col-xs-12" style="text-align:center;  font-size: 2.5vh;text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
				   			<span ><strong style="font-weight: bold"><?php if($value['tipo'] != 1 && $value['tipo'] != 2) { ?>Mesa <?php } ?><?php echo $value['mesa']?></strong></span><br>
				   		</div>
				   </div>
				   <div class="row" style="height: 54vh;padding:0;border-bottom :solid #714789; border-left :solid #714789; border-right :solid #714789; border-bottom-left-radius: 2em;  border-bottom-right-radius: 2em;margin:0;">
					   		<div class="row" style="height: 46vh; margin: 0; overflow-y: auto;">
				   			<?php foreach ($value['persona'] as $k => $v) { ?>
				   				<span style="font-weight: bold; ">Persona <?php echo $k ?></span>
			   					<?php foreach ($v['productos'] as $kk => $vv) {
			   						// Formateamos el pedido
									$pedido = $vv;
									$pedido['comanda'] = $key;
									$pedido['persona'] = $k;
									$pedido = json_encode($pedido);
									$pedido = str_replace('"', "'", $pedido);
									
									$preparacion = (!empty($vv['opcionalesDesc'])) ? 
										'<footer style="font-size: 12px">'.$vv['opcionalesDesc'].'</footer>' : '' ; 
									$preparacion .= (!empty($vv['adicionalesDesc'])) ?
										'<footer style="font-size: 12px">'.$vv['adicionalesDesc'].'</footer>' : '' ; 
									$preparacion .= (!empty($vv['sin_desc'])) ? 
										'<footer style="font-size: 12px">'.$vv['sin_desc'].$vv['nota_sin'].'</footer>' : '' ;
									$preparacion .= (!empty($value['desc_kit'])) ? 
										'<footer style="font-size: 12sipx">'.$value['desc_kit'].'</footer>' : '' ;  ?>
							   		<div class="row" id="pedido_ter_<?php echo $vv['producto'] ?>" style="padding-top: 10px; margin: 0;">
										<div class="col-md-10 col-xs-10">
												<p style="font-size: 15px; font-weight: bold; white-space: normal; text-align:left;"><?php echo '1x '.$vv['descripcion'] ?></p>
										</div>
										<div class="col-md-2 col-xs-2" style="padding:0;">
											<button style="padding: 3px 6px;" id="react_<?php echo $key?>" onclick="pedidos.reactivar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-warning btn-loader">
												<i class="fa fa-arrow-left"></i>
											</button>
										</div>
										<div class="col-md-12 col-xs-12" style="font-size: 15px; text-align: left">
											<p><?php echo $preparacion ?></p>
										</div>
									</div>	
							   	<?php } ?>
							<?php } ?>
				   		</div>
				   		<div class="row" style="height: 7vh; margin-top: 1vh;">
				   			<buttom class="btn btn-warning" onclick="pedidos.reactivartodo(<?php echo $key?>);" style="width: 60%; font-size: 2.4vh">REACTIVAR</buttom>
				   		</div>
					</div>
				</div> 
				
			</div>
		<?php } }?>
	</div>
	<div class="col-md-1 col-xs-1" id="div_mover_scroll" style="height: 100%; display:table">
		<div style="display: table-cell;
    		vertical-align: middle;">
		    <i
			    class="fa fa-caret-right fa-4x"
			    style="color: #DCB435;font-size: 11vw;"
			    onclick="pedidos.mover_scroll({
				    direccion: 'derecha',
				    div: 'div_ter',
				    cantidad: 600
			    })">
			</i>
		</div>
	</div>
</div>
<?php if($exist == 0){?>
	<script type="text/javascript">
		$('#div_terminados').html('<div align="center"><h3><span class="label label-default">* Aqui aparecen los pedidos terminados *</span></h3></div>');
	</script>
<?php }?>