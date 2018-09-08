<?php
// Valida que halla mesas libres
	if (empty($mesas_juntar)) {?>
		<div align="center">
			<h3><span class="label label-default"><?php if($idioma == 1) { ?> No hay mesas disponibles <?php } else { ?> No vacant tables <?php }?></span></h3>
		</div><?php
		
		return 0;
		
	} ?>
	<div id="exTab2">	
		<ul class="nav nav-tabs">
			<?php foreach ($areas as $key => $valor) { ?>
				<li <?php if($valor['id'] == $area_princ['id']) { ?> class="active" <?php } ?>><a  href="#tab_area_<?php echo $valor['id']?>" data-toggle="tab"><?php echo $valor['area']?></a></li>
			<?php } ?>
		</ul>
		<div class="tab-content ">
			<?php foreach ($areas as $key => $valor) { $si_exist = 0;?>

				<div class="tab-pane <?php if($valor['id'] == $area_princ['id']) { ?>active<?php } ?>" id="tab_area_<?php echo $valor['id']?>">
	          		<?php foreach ($mesas_juntar as $key2 => $row) { 
	          			if($row['idDep'] == $valor['id']){ $si_exist = 1;?>

	          				<div class="pull-left" style="padding:5px">
								<button 
							id="btn_juntar_mesa_<?php echo $row['mesa'] ?>" 
							type="button" 
							class="btn btn-default btn-lg"  
							onclick="comandas.seleccionar_mesa({
								id_mesa: <?php echo $row['mesa'] ?>,
								nombre: '<?php echo $row['nombre_mesa'] ?>',
								id_mesas: '<?php echo $row['idmesas'] ?>',
							})" 
							style="<?php if($row['junta']) { ?> width:180px;  <?php } else { ?> width:110px; <?php } ?> position:relative; height: 110px; font-size:13px;">
												<?php if($row['status'] == 1) { ?>
															<?php if($row['junta'] == 1) { 
																$img = "images/mapademesas/ocupada_juntadas.png";
															} else if($row['id_tipo_mesa'] == 1) { 
																$img = "images/mapademesas/ocupada_cuadrada_2p.png";
															} else if($row['id_tipo_mesa'] == 2) { 
																$img = "images/mapademesas/ocupada_cuadrada.png";
															} else if($row['id_tipo_mesa'] == 3) { 
																$img = "images/mapademesas/rectangulo_2p_ocupada.png";
															} else if($row['id_tipo_mesa'] == 4) { 
																$img = "images/mapademesas/ocupada_redonda_4p.png";
															} else if($row['id_tipo_mesa'] == 5) { 
																$img = "images/mapademesas/ocupada_redonda_2p.png";
															} else if($row['id_tipo_mesa'] == 6) { 
																$img = "images/mapademesas/sillon_ocupado.png";
															}  ?>
												 		
													<?php if($row['id_tipo_mesa'] != 9) { ?>
														<img style="max-width:100%; width: auto; height:100%" src="<?php echo $img ?>">
													<?php } else { ?>
														<section style="border-radius: 15%; margin-left: auto; margin-right: auto; width: 50px; height: 50px; background-color: #6b4583" >&nbsp</section>
													<?php } ?>
												<?php } else { ?>
													<?php if($row['id_tipo_mesa'] == 9) { ?>
														<section style="border-radius: 15%; margin-left: auto; margin-right: auto; width: 50px; height: 50px; background-color: #423228" >&nbsp</section>
													<?php } else { ?> 
														<img style="max-width:100%; width: auto; height:100%" src="<?php if($row['junta']) { ?>images/mapademesas/libre_juntadas.png <?php } else { ?><?php echo $row['imagen'] ?><?php } ?>">
													<?php } ?>							
												<?php } ?>
												<div style="font-size: 14px;color: white;	
																		  width: 55%;
																		  position: absolute;
																		  transform: translate(-50%, -50%);
																		  left: 50%;
																		  top: 50%;"><?php echo $row['nombre_mesa'] ?></div>
								</button>
							</div>

	          		<?php 
	          			}?>
	          		<?php
					// Valida que halla mesas en esa area
						if ($si_exist == 0 && $key2 == (count($mesas_juntar) - 1)) {?>
							<div align="center">
								<?php if($idioma == 1) { ?> No hay mesas disponibles <?php } else { ?> No vacant tables <?php }?></span>
							</div><?php
						} ?>
	          		<?php } ?>
				</div>

			<?php } ?>
			
		</div>
	</div>