<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
    	<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<!--  Morris  -->
	    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
	<!-- Datepicker -->
    	<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
   	<!-- gridstack -->
	    <link rel="stylesheet" href="../../libraries/gridstack.js-master/dist/gridstack.css"/>
	<!-- ** Sistema -->
		<link rel="stylesheet" type="text/css" href="css/comandas/comandas.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS			------ ************ ////////////////// -->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap JavaScript -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Select con buscador  -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	<!--  Morris  -->
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- Datepicker -->
	    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
	<!-- gridstack -->
		<script src="../../libraries/lodash.min.js"></script>
	    <script src="../../libraries/gridstack.js-master/dist/gridstack.js"></script>
	<!-- Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		<script type="text/javascript" src="js/comandas/comandera.js"></script>

		
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->

		<script>
///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

	//////// Cambia los select por select con buscador.
		// Como parametros puede recibir:
			// Array con los id de los select
		
			function select_buscador ($objeto) {
			// Recorre el arreglo y establece las propiedades del buscador
				$.each( $objeto, function( key, value ) {
					$("#"+value).select2({
						width : "150px"
					});
				});
			}

///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////

		function clickFlotante(){
			if($(".botonF1").attr("menu-add") == 1){
				$(".botonF1").attr("menu-add", "2");
		  		$('.btnF').addClass('animacionVer');
		  		$('.botonF1').css("background-color", "rgba(0,0,0,0.1)");
		  	} else {
		  		$(".botonF1").attr("menu-add", "1");
		  		$('.btnF').removeClass('animacionVer');
		  		$('.botonF1').css("background-color", "transparent");
		  	}
		}

		$(document).ready(function() {
		    $('.grid-stack-item').hover(function() {
		        $('#'+this.id+' .btnElimMesa').css('display', 'block');
		        $('#'+this.id+' .btnEditMesa').css('display', 'block');
		    }, function() {
		        $('#'+this.id+' .btnElimMesa').css('display', 'none');
		        $('#'+this.id+' .btnEditMesa').css('display', 'none');
		    });
		    $('.grid-silla').hover(function() {
		        $('#'+this.id+' .btnElimSilla').css('display', 'block');
		        $('#'+this.id+' .btnEditSilla').css('display', 'block');
		    }, function() {
		        $('#'+this.id+' .btnElimSilla').css('display', 'none');
		        $('#'+this.id+' .btnEditSilla').css('display', 'none');
		    });
		    $('.btnElimMesa').hover(function() {
		        $(this).css('background-color', 'red');
		    }, function() {
		        $(this).css('background-color', '#a94442');
		    });
		    $('.btnElimSilla').hover(function() {
		        $(this).css('background-color', 'red');
		    }, function() {
		        $(this).css('background-color', '#a94442');
		    });
		    $('.btnEditMesa').hover(function() {
		        $(this).css('background-color', '#005a8f');
		    }, function() {
		        $(this).css('background-color', '#5bc0de');
		    });
		    $('.btnEditSilla').hover(function() {
		        $(this).css('background-color', '#005a8f');
		    }, function() {
		        $(this).css('background-color', '#5bc0de');
		    });
		});
		</script>
		<style>
			.contenedor{
			  width:75px;
			  height:240px;
			  position:absolute;
			  right:0px;
			  top: 0px;
			}
			.botonF1{
			  width:45px;
			  height:45px;
			  border-radius:100%;
			  background: transparent;
			  right:14;
			  top:-6;
			  position:absolute;
			  margin-right:16px;
			  margin-bottom:16px;
			  border:none;
			  outline:none;
			  color:#808080;
			  font-size:25px;
			  /*box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);*/
			  transition:.3s;  
			}
			span{
			  transition:.5s;  
			}
			.botonF1:hover span{
			  transform:rotate(360deg);
			}
			.botonF1:active{
			  transform:scale(1.1);
			}
			.btnF{
			  width:51px;
			  height:51px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			  font-size:32px;
			  outline:none;
			  position:absolute;
			  z-index: 99;
			  right:0;
			  top:0;
			  margin-right:26px;
			  transform:scale(0);
			}
			.btnElimMesa{
			  width:30px;
			  height:30px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			  font-size:11px;
			  outline:none;
			  position:absolute;
			  left:5;
			  top:2;
			  transition:0.5s;
			}
			.btnElimSilla{
			  width:10px;
			  height:10px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			  font-size:5px;
			  outline:none;
			  position:absolute;
			  right:0;
			  top:0;
			  transition:0.5s;
			}
			.btnEditMesa{
			  width:30px;
			  height:30px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			  font-size:11px;
			  outline:none;
			  position:absolute;
			  right:5;
			  top:2	;
			  transition:0.5s;
			}
			.btnEditSilla{
			  width:10px;
			  height:10px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			  font-size:5px;
			  outline:none;
			  position:absolute;
			  right:0;
			  top:0	;
			  transition:0.5s;
			}
			.botonF2{
			  margin-top:50px;
			  transition:0.5s;
			}
			.botonF3{
			  background:#673AB7;
			  margin-top:110px;
			  transition:0.7s;
			}
			.botonF4{
			  background:#009688;
			  margin-top:170px;
			  transition:0.9s;
			}
			.botonF5{
			  background:#FF5722;
			  margin-top:230px;
			  transition:0.99s;
			}
			.animacionVer{
			  transform:scale(1);
			}
			.rowH {
			    display: table;
			}

			.rowH [class*="col-"] {
			    float: none;
			    display: table-cell;
			    vertical-align: top;
			}
		</style>
	</head>
	<body>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default" style="margin: 0">
					<div class="panel-heading">
						<div class="row">							
							<div class="col-xs-4 col-md-4" >
								<h2 style="margin:0"><?php echo $area["area"]?></h2>
							</div>
			
							<div class="col-xs-6  col-md-6" style="text-align: center;" style="border:solid;">
								<strong>Area:</strong>
					      		<select onchange="recargar()" class="selectpicker" data-width="500px" id="area">
									 
							      		<?php
							      			$idsucaux = $aux = 0;
											foreach ($areas as $key => $value) {
												$idsuc = $value['idsuc']; 
												if($idsuc != $idsucaux){
													if($aux == 1)
														echo '</optgroup>';
													echo '<optgroup label="'.$value['sucursal'].'" value="'.$value['idsuc'].'">';	
													$aux = 1;
												}
																																													
												?>
												<option <?php if($area['id'] == $value['id']) {?> selected <?php } ?> value="<?php echo $value['id'] ?>">
													<?php echo $value['area'] ?>
												</option> <?php	


												$idsucaux = $value['idsuc'];											
											} ?>
									 
								</select>
								<button 
									id="btn_add_area" 
									class="btn btn-info btn-lg" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									style="font-size:14px; " 
									data-tooltip="tooltip"
									title="Agregar area"
									data-toggle="modal"
									data-target="#modal_agregar_area"
									data-placement="bottom"
									>
									...
								</button>
							</div>
							<div class="col-xs-1 col-md-1" style="text-align: center;">
					      		<div class="contenedor">
									<button class="botonF1" menu-add="1" onclick="clickFlotante()">
									 	<i class="fa fa-wrench"></i>
									</button>
									<button data-tooltip="tooltip" title="Agregar mesas" data-toggle="modal" data-target="#modal_agregar" data-placement="left" class="btnF botonF2 btn-success">
									 	<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="border: solid 15px; border-color: #2C2146; min-height: 500px; margin:0">
			<div class="col-xs-12">
				<div class="GtableTablesContent grid-stack" id="contenedor" style="width:100%; margin-top:1%"><?php
					
					
					foreach ($mesas as $key => $row) { ?>
						<div 
							class="grid-stack-item" 
							id="<?php echo $row['mesa'] ?>" 
							data-gs-x="<?php echo $row['x'] ?>" 
							data-gs-y="<?php echo $row['y'] ?>" 
							<?php if($row['id_tipo_mesa'] != 7 && $row['id_tipo_mesa'] != 8) { ?> 
								data-gs-no-resize="1"
								data-gs-width="<?php echo $row['width'] ?>" 
								data-gs-height="<?php echo $row['height'] ?>"
							<?php } else { ?>
								data-gs-width="<?php echo $row['width_barra'] ?>" 
								data-gs-height="<?php echo $row['height_barra'] ?>"
							<?php } ?>
							>
								<div style="width:100%; height:100%;text-align: center;" class="grid-stack-item-content">
									<?php if($row['id_tipo_mesa'] == 7) { ?>
								
										<section style="border-radius: 8px; float:right; width: calc(100% - 1px); height: 100%; background-color: #9A673A" >
											<div style="font-size: 10px; color: white; " > <?php echo $row['nombre_mesa'] ?>
											<div style="width: 100%; height: 100%; overflow: auto">
												<?php foreach ($row['sillas'] as $key => $value) { ?>
													<div id="<?php echo $value['mesa'] ?>" class="grid-silla" style="position:relative; background-color: #423228; margin: 3px; border-radius: 15%; width: 20px; height: 20px;  float:left;">
														<div style="font-size: 15px; cursor: pointer;color: white; width: 90%; position:absolute; transform: translate(-50%, -50%); left: 50%; top: 50%;" ><?php echo $value['nombre_mesa'] ?></div>

															<!--<button data-tooltip="tooltip" title="Eliminar silla" onclick="eliminar_mesa({mesa: <?php echo $value['mesa'] ?>});" data-placement="right" class="btnElimSilla" style="display:none; background-color:#a94442;" ><i class="fa fa-remove" aria-hidden="true"></i></button>-->
														<button data-tooltip="tooltip" title="Editar silla" onclick="vista_editar_mesa({mesa: <?php echo $value['mesa'] ?>});" data-placement="right" class="btnEditSilla" style="display:none; background-color:#5bc0de;" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
													</div>
												<?php } ?>
												
											
											</div>
										</section>
									<?php } else if($row['id_tipo_mesa'] == 8) { ?>
										<section style="border-radius: 15px; position: absolute; top: 0; left: 0; bottom: 0; right: 0; margin: auto; width: 90%; height: 90%; border: solid 3px; border-color: #77407b;" >&nbsp</section>
									<?php } else if($row['id_tipo_mesa'] == 9) { ?>
										<section style="border-radius: 15%; margin-left: auto; margin-right: auto; width: 90%; height: 100%; background-color: #423228" >&nbsp</section>
									<?php } else { ?>
										<img style="max-width: 100%; <?php if($row['id_tipo_mesa'] == 6) { ?> width: 101px; height: 97px;<?php } else {?> width: auto; height:100% <?php } ?> " src="<?php echo $row['imagen'] ?>">
									<?php } ?>
									<button <?php if($row['id_tipo_mesa'] != 9) { ?> data-tooltip="tooltip" <?php } ?> title="Eliminar <?php if($row['id_tipo_mesa'] == 6) { ?>sillon<?php } else if($row['id_tipo_mesa'] == 7) { ?>barra<?php } else { ?>mesa<?php } ?>" onclick="eliminar_mesa({mesa: <?php echo $row['mesa'] ?>});" data-placement="right" class="btnElimMesa" style="z-index: 99; display:none; background-color:#a94442;" ><i class="fa fa-remove" aria-hidden="flase"></i></button>
									<?php if($row['id_tipo_mesa'] != 8 && $row['id_tipo_mesa'] != 7) { ?><button <?php if($row['id_tipo_mesa'] != 9) { ?> data-tooltip="tooltip" <?php } ?> title="Editar <?php if($row['id_tipo_mesa'] == 6) { ?>sillon<?php } else if($row['id_tipo_mesa'] == 7) { ?>barra<?php } else { ?>mesa<?php } ?>" onclick="vista_editar_mesa({mesa: <?php echo $row['mesa'] ?>});" data-placement="left" class="btnEditMesa" style="z-index: 99; display:none; background-color:#5bc0de;" ><i class="fa fa-pencil" aria-hidden="true"></i></button><?php } ?>
									<div id="datos_<?php echo $row['mesa'] ?>" style="color: white;	
										  width: 55%;
										  position: absolute;
										  font-size: 11px;
										  transform: translate(-50%, -50%);
										  left: 50%;
										  top: 50%;">
										  		<?php if($row['id_tipo_mesa'] != 7) { ?>
										  			<?php if (!empty($row['mesero']) && $row['id_tipo_mesa'] != 8 && $row['id_tipo_mesa'] != 9) { ?>
														<div id="mesero_<?php echo $row['mesa'] ?>" style="font-size:12px">
															<?php echo $row['mesero']; ?>	
														</div>
													<?php } ?>
										       		<div style="<?php if($row['id_tipo_mesa'] == 8) { ?> cursor: pointer; font-size: 14px; color: #12123f; font-weight:bold; <?php } else { ?> font-size: 18px; <?php } ?> " <?php if($row['id_tipo_mesa'] == 8) { ?>onclick="click_area(<?php echo $row['id_area']?>); <?php } ?>" ><?php echo $row['nombre_mesa'] ?></div>
										      	<?php } ?>
									</div>
								</div>
								
								<!--<div class="grid-stack-item-content panel panel-default">
									<div class="panel-heading" style="cursor: move">
										<div class="row">
											<div class="col-xs-5">
												<i class="fa fa-user fa-lg text-primary"></i> <?php echo $row['personas']; ?>
											</div>
											<div class="col-xs-7" id="div_tiempo_<?php echo $row['mesa'] ?>">
												<!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia 
											</div>
										</div>
									</div>
									<div 
										id="mesa_<?php echo $row['mesa'] ?>"
										id_comanda="<?php echo $row['idcomanda'] ?>"
										data-toggle="modal" 
										data-target="#modal_comandera">
											<a href="javascript:void(0)" style="color: #000000">
												<div class="panel-body">
													<div class="GtableTableIcon" align="center">
														<div class="row">
															<div class="col-xs-6">
																<h2><?php echo $row['nombre_mesa'] ?></h2>
																<div id="div_total_<?php echo $row['mesa'] ?>">
																	<!-- En esta div se carga el total de la comanda 
																</div>
															</div>
															<div class="col-xs-6" style="padding-top: 1%"><?php
																if (!empty($row['mesero'])) { ?>
																	<i class="fa fa-hand-o-up fa-lg text-primary"></i> 
																	<p id="mesero_<?php echo $row['mesa'] ?>">
																		<?php echo $row['mesero']; ?>	
																	</p><?php
																} ?>
															</div>
														</div> 
													</div>
												</div>
											</a>
									</div>
								</div>-->
						</div><?php
					}// Fin foreach ?>

				</div>
			</div>	
		</div>
		<!-- Modal agregar -->
		<div id="modal_agregar" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Agregar mesas</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Aqui puedes agregar todo tipo de mesas <strong>¡Masivamente!</strong>. Escribe el numero de 
					      		<strong>mesas</strong> y  elige el <strong>tipo de mesa</strong> que deseas agregar, 
					      		tambien puedes seleccionar el <strong>empleado</strong> si deseas <strong>asignarle</strong>
					      		las mesas que vas a crear
					    	</p>
					    </blockquote>
					    <div  class="col-md-6" id="div_mesero">
				      		<h3><small>Mesero:</small></h3>

				      		<select class="selectpicker" data-live-search="true" id="empleado_agregar">
								<option selected value="">-- Sin asignar --</option><?php
								
								foreach ($empleados as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['usuario'] ?>
									</option> <?php
								} ?>
								
							</select>
						</div>

							
								


					    <div class="row" id="div_agregar">
					    	<div id="div_num_mesas"  >
			      				<h3><small id="text-num" >Número de personas:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-slack"></i></span>
									<input id="num_mesas" type="number" class="form-control">
								</div>
					    	</div>
									
									<div class="col-xs-6">
			      				<h3><small>Tipo de mesa:</small></h3>
			      				<div class="input-group input-group-md">

									<select style="width:100%" class="form-control" onchange="changeImg()" data-live-search="true" id="tipo_mesa">
									
								
								
									

								<?php	 foreach ($tipo_mesas as $key => $value) { ?>
								
										<option <?php if($key == 1) {?> selected <?php } ?> value="<?php echo $key ?>">
										
											<?php echo $value['tipo_mesa'] ?>
										</option> 
									<?php	}   ?>
									
								</select>
								
								

									
						</div>	

						

					    	<div id="div_area" style="display: none" class="input-group input-group-xs">
			      				<h3><small >Area:</small></h3>
			        			<select class="selectpicker" style="float:right" data-live-search="true" id="select_area_modal">
			        				<option selected value="" >-- Sin asignar --</option><?php
									foreach ($areas as $key => $value) { ?>
										<option value="<?php echo $value['id'] ?>">
											<?php echo $value['area'] ?>
										</option> <?php
									} ?>
								</select>
					    	</div>

					    	
					
						
							
					    		<div id="div_nombre_barra" class="input-group input-group-xs" >

			   						<h3><small >Nombre de la barra:</small></h3>
								 <input  style="width: 90%" class="form-control"   id="nombre_barra"></input>
								 </div>

											
								
	

								</div>
							
						
					    	</div>

					    	
					    </div>
					    <div class="row" style="position: relative">
					    	<div class="col-xs-6 col-md-offset-3">
			      				<h3><small>Imagen:</small></h3>
			        			<div style="width: 100%; height: 140px; text-align: center;">
			        				<div id="barra_area" style="display: none; border-radius: 15px; width: 100px; height: 40px; border: solid 3px; border-color: #77407b; position: absolute; top: 0; left: 0; bottom: 0; right: 0; margin: auto;" >&nbsp</div>
			        				<div id="barra" style=" border-radius: 8px; width: 40px; height: 140px; margin-left: auto; margin-right: auto; background-color: #9A673A" >&nbsp</div>
									<img id="img_mesa" style="display: none; width:auto; height: 100%; margin-left: auto; margin-right: auto;" src="<?php echo $tipo_mesas[0]['imagen']?>">
									<div id="silla" style="display: none; position: absolute; top: 0; left: 0; bottom: 0; right: 0; margin: auto; border-radius: 15%; width: 40px; height: 40px; background-color: #423228" >&nbsp</div>
								</div>
					    	</div>
					    </div>
					    <div class="row" style="text-align:center; margin-top:15px">
			      				<button id="btn_agregar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="agregar_mesas({total_mesas: <?php echo $total_mesas ?>,total_sillas: <?php echo $total_sillas ?>, total_sillones: <?php echo $total_sillones ?>, total_barras: <?php echo $total_barras ?> ,nombre_barra:$('#nombre_barra').val(),empleado: $('#empleado_agregar').val(), idDep: $('#area').val(), num_mesas:$('#num_mesas').val(), area: $('#select_area_modal').val(), nombre_area: $('#select_area_modal option:selected').text(), tipo_mesa: tipo_mesas[$('#tipo_mesa').val()]['id']})" class="btn btn-success" type="button">
			        				<i class="fa fa-plus"></i> Agregar
			        			</button>
					    </div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal agregar-->
	<!-- Modal editar -->
		<div id="modal_editar" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-mm">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_editar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Editar mesa</h4>
	      			</div>
	      			<div class="modal-body">
	      				<div class="row">
						    <div class="col-xs-4">
					      		<h3 id="div_editar"><small>Mesero:</small></h3>
					      		<select class="selectpicker" data-live-search="true" id="empleado_editar">
									<option selected value="0">-- Sin asignar --</option><?php
									
									foreach ($empleados as $key => $value) { ?>
										<option value="<?php echo $value['id'] ?>">
											<?php echo $value['usuario'] ?>
										</option> <?php
									} ?>
									
								</select>
							</div>
						</div>
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small id="text-nom-edit"></small></h3>
			      				<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-edit"></i></span>
									<input id="nombre_mesa" type="text" class="form-control">
								</div>
					    	</div>
					    	<div id="div_tm_edit" class="col-xs-6">
			      				<h3><small>Tipo de mesa:</small></h3>
			      				<div class="input-group input-group-lg">
									<select class="form-control" onchange="changeImgEdit()" data-live-search="true" id="tipo_mesa_editar">
									<?php
									
									foreach ($tipo_mesas as $key => $value) { ?>
										<?php if($value['id']!=8) { ?>
											<option value="<?php echo $key ?>">
												<?php echo $value['tipo_mesa'] ?>
											</option> 
										<?php } ?>
									<?php } ?>
									
								</select>
								</div>
							</div>
					    </div>
					    <div class="row" style="position: relative">
					    	<div class="col-xs-6 col-md-offset-3">
			      				<h3><small>Imagen:</small></h3>
			        			<div style="width: 100%; height: 140px; text-align: center;">
			        				<div id="barra_editar" style="margin-left: auto; margin-right: auto; border-radius: 8px; width: 40px; height: 140px; background-color: #9A673A" >&nbsp</div>
			        				<div id="silla_editar" style="position: absolute; top: 0; left: 0; bottom: 0; right: 0; margin: auto; border-radius: 15%; width: 40px; height: 40px; background-color: #423228" >&nbsp</div>
									<img id="img_mesa_editar" style="width:auto; height: 100%; margin-left: auto; margin-right: auto;" src="<?php echo $tipo_mesas[0]['imagen']?>">
								</div>
					    	</div>
					    </div>
					    <div class="row" style="text-align:center; margin-top:15px">
			      				<button id="btn_editar" mesa="" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="editar_mesa({empleado: $('#empleado_editar').val(), nombre_mesa:$('#nombre_mesa').val(), tipo_mesa: tipo_mesas[$('#tipo_mesa_editar').val()]['id']})" class="btn btn-success" type="button">
			        				<i class="fa fa-pencil"></i> Editar
			        			</button>
					    	</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal editar-->
	<!-- Modal agregar_area -->
		<div id="modal_agregar_area" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-mm">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Areas</h4>
	      			</div>
	      			<div class="modal-body">
						<div id="exTab2">	
							<ul class="nav nav-tabs">
								<li class="active"><a  href="#tab_add_area" data-toggle="tab">Agregar</a></li>
								<li><a href="#tab_edit_area" data-toggle="tab">Editar</a></li>
								<li><a href="#tab_delete_area" data-toggle="tab">Eliminar</a></li>
							</ul>
							<div class="tab-content ">
								<div class="tab-pane active" id="tab_add_area">
						          	<blockquote style="font-size: 14px">
								    	<p>
								      		Aqui puedes agregar areas. Escribe el nombre de la
								      		<strong>area</strong> para despues poder utilizarla.
								    	</p>
									</blockquote>
									<h3 id="div_agregar_area"><small>Nombre del area:</small></h3>									
									<input id="nom_area" type="text" class="form-control">

									<h3 id=""><small>Sucursal:</small></h3>
									<select name="" id="sucursaladd" class="selectpicker form-control"  data-width="100%">
										<?php 
											foreach ($sucursales as $key => $value) {
												echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';												
											}
										 ?>										
									</select>

									<button id="btn_agregar_area" style="margin-top: 10px" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="agregar_area({nom_area: $('#nom_area').val(),suc_area: $('#sucursaladd').val()})" class="btn btn-success" type="button">
										<i class="fa fa-plus"></i> Agregar
									</button>
								</div>
								<div class="tab-pane" id="tab_edit_area">
						          	<blockquote style="font-size: 14px">
										<p>
											Aqui puedes editar areas. Selecciona el
											<strong>area</strong> que desea editar.
										</p>
									</blockquote>
									<h3 id="div_edit_area"><small>Seleccione el area a editar:</small></h3>
									<div class="row" style="margin:0">
									<select onchange="select_area()" class="selectpicker" style="float:right" data-live-search="true" id="select_edit_area" data-width="100%"><?php
										foreach ($areas as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>" suc="<?php echo $value['idsuc']; ?>">
												<?php echo $value['area'] ?>
											</option> <?php
										} ?>
									</select>
									<h3 id=""><small>Nombre del area:</small></h3>
									<input id="nom_area_edit" type="text" class="form-control" value="<?php echo $area[0]['area']?>">
									</div>

									<h3 id=""><small>Sucursal:</small></h3>
									<select name="" id="sucursaledit" class="selectpicker form-control"  data-width="100%">
										<?php 
											foreach ($sucursales as $key => $value) {
												echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';												
											}
										 ?>										
									</select>

									<button id="btn_edit_area" style="margin-top: 10px" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="edit_area({nom_area: $('#nom_area_edit').val(), area: $('#select_edit_area').val()})" class="btn btn-primary" type="button">
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar
									</button>
								</div>
						        <div class="tab-pane" id="tab_delete_area">
						        	<blockquote style="font-size: 14px">
										<p>
											Aqui puedes eliminar areas. Selecciona el
											<strong>area</strong> que desea eliminar.
										</p>
									</blockquote>
									<h3 id="div_delete_area"><small>Seleccione el area a eliminar:</small></h3>
									<div class="row" style="margin:0">
									<select class="selectpicker" style="float:right" data-live-search="true" id="select_delete_area"><?php
										foreach ($areas as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>">
												<?php echo $value['area'] ?>
											</option> <?php
										} ?>
									</select>
								</div>
									<button id="btn_delete_area" style="margin-top: 10px" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="delete_area({area: $('#select_delete_area').val()})" class="btn btn-danger" type="button">
										<i class="fa fa-trash-o"></i> Eliminar
									</button> 
								</div>
							</div>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal agregar_area-->
	</body>
</html>
<script>
// Convierte los id con draggable en divs ue se pueden arrastrar	
	convertir_draggable(<?php echo json_encode($mesas) ?>);
	$(document).ready(function(){
    $('[data-tooltip="tooltip"]').tooltip(); 
});
// Creamos un arreglo con los id de los select
	$objeto = [];
	$objeto[0] = 'area';
	$objeto[1]= 'empleado_agregar';
	$objeto[2] = 'tipo_mesa';
	$objeto[3]= 'empleado_editar';
	$objeto[4] = 'tipo_mesa_editar';
	$objeto[5] = 'select_area_modal';
	$objeto[6] = 'select_edit_area';
	$objeto[7] = 'select_delete_area';

	var $x = 0;
	var $y = 0;
	var tipo_mesas = <?php echo json_encode($tipo_mesas) ?>;

	var serialize_widget_map = function (items) {
		console.log('---------> Items');
		console.log(items);
		
	// Guarda sus cordenadas
	    $.each(items, function(index, value){
	    	console.log("value");
	    	if(value['width'] == 1 || value['height'] == 1){
	    		$('#datos_'+value['el'][0]['id']).css('font-size', '9px');
	    	} else {
	    		$('#datos_'+value['el'][0]['id']).css('font-size', '14px');
	    	}
			guardar_cordenadas({id:value['el'][0]['id'], x:value['x'], y:value['y'], width:value['width'], height:value['height']});
		});
	    
	};

//Guarda la posicion de las mesas al cambiar
	$('.grid-stack').on('change', function (e, items) {
	    serialize_widget_map(items);
	});
// Mandamos llamar la funcion que crea el buscador
	select_buscador($objeto);

///////////////// ******** ---- 	edit_area		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// nom_area -> nombre del area
			// area -> id del area

			function edit_area($objeto) {
				var sucE = $("#sucursaledit").val();
				$objeto['idsuc'] = sucE;

				console.log('--------> objeto edit_area');
				console.log($objeto);
				
				// ** Validaciones
				if (!$objeto['nom_area']) {
					var $mensaje = 'Escribe el nombre del area';
					$('#div_edit_area').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				// Loader en el boton reiniciar
				var $btn = $('#btn_edit_area');
				$btn.button('loading');
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=edit_area',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE edit_area');
						console.log(resp);

						// Regresa el boton a su estado normal
						$btn.button('reset');

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al editar el area';
							$('#div_edit_area').notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}

						// Exito :D
						if (resp['status'] == 1) {
							var $mensaje = 'Area editada';
							$('#div_edit_area').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							var pathname = window.location.pathname;
							$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + resp['result']);
						}
					}).fail(function(resp) {
						console.log('---------> Fail agregar_area');
						console.log(resp);

						// Quita el loader
						$btn.button('reset');

						var $mensaje = 'Error al editar el area';
						$('#div_edit_area').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});					
			}

///////////////// ******** ---- 	FIN	edit_area		------ ************ //////////////////

///////////////// ******** ---- 	delete_area		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// area -> id del area

			function delete_area($objeto) {
				console.log('--------> objeto delete_area');
				console.log($objeto);

				if(confirm("¿Seguro que desea eliminar esta area? Toma en cuenta que tambien se eliminaran las mesas.")){
					// Loader en el boton reiniciar
					var $btn = $('#btn_edit_area');
					$btn.button('loading');
					$.ajax({
						data : $objeto,
						url : 'ajax.php?c=comandas&f=delete_area',
						type : 'GET',
						dataType : 'json',
						}).done(function(resp) {
							console.log('--------> RESPONSE delete_area');
							console.log(resp);

							// Regresa el boton a su estado normal
							$btn.button('reset');

							// Error :(
							if (resp['status'] == 0) {
								var $mensaje = 'Error al eliminar el area';
								$('#div_delete_area').notify($mensaje, {
									position : "left",
									autoHide : true,
									autoHideDelay : 5000,
									className : 'error',
								});

								return 0;
							}

							// Exito :D
							if (resp['status'] == 1) {
								var $mensaje = 'Area eliminada';
								$('#div_delete_area').notify($mensaje, {
									position : "top center",
									autoHide : true,
									autoHideDelay : 5000,
									className : 'success',
								});
								var pathname = window.location.pathname;
								$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas');
							}
						}).fail(function(resp) {
							console.log('---------> Fail agregar_area');
							console.log(resp);

							// Quita el loader
							$btn.button('reset');

							var $mensaje = 'Error al eliminar el area';
							$('#div_delete_area').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});
						});	
				}		
			}

///////////////// ******** ---- 	FIN	delete_area		------ ************ //////////////////

///////////////// ******** ---- 	agregar_area		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// nom_area -> nombre del area

			function agregar_area($objeto) {
				console.log('--------> objeto agregar_area');
				console.log($objeto);

				//alert($objeto);
				//return 0;				

				// ** Validaciones
				if (!$objeto['nom_area']) {
					var $mensaje = 'Escribe el nombre del area';
					$('#div_agregar_area').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				// Loader en el boton reiniciar
				var $btn = $('#btn_agregar_area');
				$btn.button('loading');
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=agregar_area',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE agregar_area');
						console.log(resp);

						// Regresa el boton a su estado normal
						$btn.button('reset');

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al agregar el area';
							$('#div_agregar_area').notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}

						// Exito :D
						if (resp['status'] == 1) {
							var $mensaje = 'Area agregada';
							$('#div_agregar_area').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							var pathname = window.location.pathname;
							$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + resp['result']);
						}
					}).fail(function(resp) {
						console.log('---------> Fail agregar_area');
						console.log(resp);

						// Quita el loader
						$btn.button('reset');

						var $mensaje = 'Error al agregar el area';
						$('#div_agregar_area').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});					
			}

///////////////// ******** ---- 	FIN	agregar_area		------ ************ //////////////////

///////////////// ******** ---- 	agregar_mesas		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// tipo_mesa -> tipo de mesa a agregar
			// num_mesas -> numero de mesas a aagregar o numero de personas
			// empleado -> id empleado
			// idDep -> id del area a donde se va asignar la mesa
			// area -> id del area para crear
			// nombre_area -> nombre del area
			// total_barras -> numero total de barras
			// total_mesas -> numero total de mesas
			// total_sillones -> numero total de sillones

			function agregar_mesas($objeto) {
			


				console.log('--------> objeto agregar_mesas');
				console.log($objeto);

				// ** Validaciones
				if ($objeto['tipo_mesa'] == 8) {
					if(!$objeto['area']){
						console.log('if');
						var $mensaje = 'Elige un area valida';
						$('#div_agregar').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});

						return 0;
					}
				} else {
					if (!$objeto['num_mesas']) {
						var $mensaje = 'Escribe el numero de mesas';
						$('#div_agregar').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});

						return 0;
					}
				}

				if (!$objeto['tipo_mesa']) {
					var $mensaje = 'Elige un tipo de mesa valido';
					$('#div_agregar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				$objeto['nombre_area'] = $objeto['nombre_area'].trim();
				// Loader en el boton reiniciar
				var $btn = $('#btn_agregar');
				$btn.button('loading');
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=agregar_mesas',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE agregar_mesas');
						console.log(resp);

						// Regresa el boton a su estado normal
						$btn.button('reset');

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al agregar las mesas';
							$('#div_agregar').notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}

						// Exito :D
						if (resp['status'] == 1) {
							var $mensaje = 'Mesas agregadas';
							$('#div_agregar').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							var pathname = window.location.pathname;
							$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + $("#area").val());
						}
					}).fail(function(resp) {
						console.log('---------> Fail agregar_mesas');
						console.log(resp);

						// Quita el loader
						$btn.button('reset');

						var $mensaje = 'Error al agregar las mesas';
						$('#div_agregar').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});					
			}

///////////////// ******** ---- 	FIN	agregar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	editar_mesa		------ ************ //////////////////
	// Como parametros puede recibir:
			// tipo_mesa -> tipo de mesa a agregar
			// nombre_mesa -> nomnbre de la mesa a aagrega
			// empleado -> id empleado

			function editar_mesa($objeto) {
				console.log('--------> objeto editar_mesa');
				console.log($objeto);

				// ** Validaciones
				if (!$objeto['nombre_mesa']) {
					var $mensaje = 'Escribe el nombre de la mesa';
					$('#div_editar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				if (!$objeto['tipo_mesa']) {
					var $mensaje = 'Elige un tipo de mesa valido';
					$('#div_editar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				$objeto['mesa'] = $('#btn_editar').attr('mesa');
				var $btn = $('#btn_editar');
				$btn.button('loading');
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=editar_mesa',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE editar_mesa');
						console.log(resp);

						// Regresa el boton a su estado normal
						$btn.button('reset');

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al editar la mesa';
							$('#div_agregar').notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}

						// Exito :D
						if (resp['status'] == 1) {
							var $mensaje = 'Mesa editada correctamente';
							$('#div_agregar').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							var pathname = window.location.pathname;
							$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + $("#area").val());
						}
					}).fail(function(resp) {
						console.log('---------> Fail editar_mesa');
						console.log(resp);

						// Quita el loader
						$btn.button('reset');

						var $mensaje = 'Error al editar la mesa';
						$('#div_agregar').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});					
			}

///////////////// ******** ---- 	FIN	editar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	eliminar_mesa		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// mesa -> id de la mesa a eliminar

			function eliminar_mesa($objeto) {
				console.log('--------> objeto eliminar_mesa');
				console.log($objeto);

				if(confirm('¿Seguro de eliminar esta mesa?')){
					$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=eliminar_mesa',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE eliminar_mesa');
						console.log(resp);

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al eliminar la mesa';
							$('#'+$objeto.mesa).notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}

						// Exito :D
						if (resp['status'] == 1) {
							var $mensaje = 'Mesa eliminada';
							$('#'+$objeto.mesa).notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							var pathname = window.location.pathname;
							$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + $("#area").val());
						}
					}).fail(function(resp) {
						console.log('---------> Fail eliminar_mesa');
						console.log(resp);

						var $mensaje = 'Error al eliminar la mesa';
						$('#'+$objeto.mesa).notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});	
				}
			}

///////////////// ******** ---- 	FIN	eliminar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	vista_editar_mesa		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// mesa -> id de la mesa a editar

			function vista_editar_mesa($objeto) {
				console.log('--------> objeto vista_editar_mesa');
				console.log($objeto);

				//$("#modal_editar").modal()
					$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=vista_editar_mesa',
					type : 'GET',
					dataType : 'json',
					}).done(function(resp) {
						console.log('--------> RESPONSE vista_editar_mesa');
						console.log(resp);

						// Error :(
						if (resp['status'] == 0) {
							var $mensaje = 'Error al cargar datos de la mesa';
							$('#'+$objeto.mesa).notify($mensaje, {
								position : "left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});

							return 0;
						}
						if(resp['status'] == 1){
							changeImgEdit();
							
							
							$('#empleado_editar').val(resp['result']['idempleado']);

							for(var x=0; x<tipo_mesas.length; x++){
								if(tipo_mesas[x]['id'] == resp['result']['tipo_mesa']){
									$('#tipo_mesa_editar').val(x);
									x = tipo_mesas.length+1;
								}
							}
							$('#empleado_editar').trigger('change');
							$('#tipo_mesa_editar').trigger('change');
							$('#btn_editar').attr('mesa', resp['result']['id_mesa']);
							$("#modal_editar").modal();
							$('#nombre_mesa').val(resp['result']['nombre']);
						}

					}).fail(function(resp) {
						console.log('---------> Fail vista_editar_mesa');
						console.log(resp);

						var $mensaje = 'Error al cargar datos de la mesa';
						$('#'+$objeto.mesa).notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					});	
			}

///////////////// ******** ---- 	FIN	vista_editar_mesa		------ ************ //////////////////

///////////////// ******** ---- 		convertir_draggable		------ ************ //////////////////
//////// Convierte los id con draggable en divs ue se pueden arrastrar
	// Como parametros recibe:

			function convertir_draggable($objeto) {
				console.log('--------> Objet dragables');
				console.log($objeto);

			// Convierte los id con draggable en divs ue se pueden arrastrar
				$(function() {
					var options = {
						float : true,
						cell_height : 47,
						vertical_margin : 5,
						scroll : false,
						width: 20,
						resizable : {
							autoHide : true
						},
						alwaysShowResizeHandle : /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
					};

					$('.grid-stack').gridstack(options);

				// Agrega el dragable al droppable
					grid = $('.grid-stack').data('gridstack');
					
				// Acomoda los dragables en sus posiciones si no tienen
					$.each($objeto, function(index, value) {
						// si la mesa no iene cordenadas le asigna unas y la guarda
						if (value['y'] == -1 && value['x'] == -1) {
							// Si X es mayor al numero de columnas(5 equivale a 4 columnas) la regresa al 1 e incrementa Y
							if ($x > 10) {
								$x = 0;
								$y += 2;
							}

							var result = grid.move($('#' + value['mesa']), $x, $y);

							// Guarda sus cordenadas
							guardar_cordenadas({
								id : value['mesa'],
								x : $x,
								y : $y,
								width: value['width'],
								height: value['height']
							});

							$x += 2;
						}
					});
				});
			}

///////////////// ******** ---- 		FIN convertir_draggable		------ ************ //////////////////

///////////////// ******** ---- 		guardar_cordenadas		------ ************ //////////////////
//////// Guarda las cordenadas de la mesa actual en la BD
	// Como parametros recibe:
			// id -> id de la mesa que se movera
			// X -> numero de la columno
			// Y -> numero de la fila
			function guardar_cordenadas($objeto) {
				console.log("guardar_cordenadas");
				console.log($objeto);
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=guardar_cordenadas',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						// Error: Manda un mensaje con el error
						if (!resp) {
							alert('Error: \n Error al guardar las cordenadas de la mesa');
							return 0;
						}

						// ** Cambia el color de fondo por unos segundos para indicar el cambio de cordenadas
						$("#draggable_" + $objeto['id']).animate({
							backgroundColor : "#77DD77"
						}, 500);

						// Devuelve al color original
						$("#draggable_" + $objeto['id']).animate({
							backgroundColor : "",
							color : "#337AB7"
						}, 1000);
					}
				});
			}

///////////////// ******** ---- 		FIN guardar_cordenadas		------ ************ //////////////////

///////////////// ******** ---- 		select_area		------ ************ //////////////////
//////// Guarda las cordenadas de la mesa actual en la BD
	// Como parametros recibe:
			function select_area() {				 				
				var option = $('#select_edit_area option:selected').attr('suc');
				$("#sucursaledit").val(option);						
				$("#sucursaledit").select2();
				var aux = $( "#select_edit_area option:selected" ).text().trim();

				var n = aux.indexOf("-");
				aux =  aux.substr(n+2);

				$('#nom_area_edit').val(aux);								
			}

///////////////// ******** ---- 		FIN select_area		------ ************ //////////////////


///////////////// ******** ---- 	recargar		------ ************ //////////////////
//////// Carga de nuevo la vista del mapa de mesas
	// Como parametros puede recibir:
			// boton -> boton en el que carga el loader

			function recargar($objeto) {				
				console.log('--------> objeto recargar');
				console.log($objeto);

				// Loader en el boton recargar
				if($("#area").val() != null){
					console.log(window.location.pathname);
					// Carga de nuevo la vista
					var pathname = window.location.pathname;
					$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + $("#area").val());
				}
				else{
					var $mensaje = 'Actualmente se encuentra en esa zona';
					$('#area').notify($mensaje, {
					position : "botton right",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					});
				}
				
			}

///////////////// ******** ---- 	FIN recargar			------ ************ //////////////////

///////////////// ******** ---- 	click_area		------ ************ //////////////////
//////// Carga de nuevo la vista del mapa de mesas
	// Como parametros puede recibir:
			// boton -> boton en el que carga el loader

			function click_area(area) {
				console.log('--------> objeto click_area');
				console.log(area);
				var pathname = window.location.pathname;
				$("#tb2285-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=editar_mapa_mesas&area=' + area);
				
			}

///////////////// ******** ---- 	FIN click_area			------ ************ //////////////////

///////////////// ******** ---- 	changeImg		------ ************ //////////////////
//////// Carga de nuevo la vista del mapa de mesas
	// Como parametros puede recibir:

			function changeImg($objeto) {
				console.log('--------> objeto changeImg');
				if(tipo_mesas[$('#tipo_mesa').val()]['id'] == 7){

					$('#barra').css('display', 'block');
					$('#img_mesa').css('display', 'none');
					$('#div_mesero').css('display', 'block');
					$('#barra_area').css('display', 'none');
					$('#silla').css('display', 'none');
					$('#div_area').css('display', 'none');
					$('#div_num_mesas').css('display', 'block');
					$('#text-num').text('Número de personas:');
					//aqui muestra el campo de nombre de la barra en caso de que corresponda el select picker de tipo de mesa con funcion show
				    $('#div_nombre_barra').show();
				} else if(tipo_mesas[$('#tipo_mesa').val()]['id'] == 8) {
					$('#barra_area').css('display', 'block');
					$('#div_area').css('display', 'block');
					$('#silla').css('display', 'none');
					$('#barra').css('display', 'none');
					$('#img_mesa').css('display', 'none');
					$('#div_mesero').css('display', 'none');
					$('#div_num_mesas').css('display', 'none');
						//aqui elimina el campo de nombre de la barra en caso de que corresponda el select picker de tipo de mesa con funcion  hide
					$('#div_nombre_barra').hide();
				} else if(tipo_mesas[$('#tipo_mesa').val()]['id'] == 9) {
					$('#barra').css('display', 'none');
					$('#silla').css('display', 'block');
					$('#img_mesa').css('display', 'none');
					$('#div_mesero').css('display', 'block');
					$('#barra_area').css('display', 'none');
					$('#div_area').css('display', 'none');
					$('#div_num_mesas').css('display', 'block');
					$('#text-num').text('Número de sillas:');
					$('#div_nombre_barra').hide();
				} else{
					$("#img_mesa").attr("src", tipo_mesas[$('#tipo_mesa').val()]['imagen']);
					$('#barra').css('display', 'none');
					$('#text-num').text('Número de mesas:');
					$('#silla').css('display', 'none');
					$('#barra_area').css('display', 'none');
					$('#img_mesa').css('display', 'block');
					$('#div_mesero').css('display', 'block');
					$('#div_area').css('display', 'none');
					$('#div_num_mesas').css('display', 'block');
					$('#div_nombre_barra').hide();
				}
				
			}

///////////////// ******** ---- 	FIN changeImg			------ ************ //////////////////

///////////////// ******** ---- 	changeImgEdit		------ ************ //////////////////
//////// Carga de nuevo la vista del mapa de mesas
	// Como parametros puede recibir:

			function changeImgEdit($objeto) {
				console.log('--------> objeto changeImgEdit');
				if(tipo_mesas[$('#tipo_mesa_editar').val()]['id'] == 7){
					$('#barra_editar').css('display', 'block');
					$('#img_mesa_editar').css('display', 'none');
					$('#text-nom-edit').text('Número de personas:');
					$('#nombre_mesa').attr('type', 'number');
					$('#div_tm_edit').css('display', 'block');
					$('#silla_editar').css('display', 'none');
					$('#div_nombre_barra').show();
				} else if(tipo_mesas[$('#tipo_mesa_editar').val()]['id'] == 6){
					$("#img_mesa_editar").attr("src", tipo_mesas[$('#tipo_mesa_editar').val()]['imagen']);
					$('#barra_editar').css('display', 'none');
					$('#img_mesa_editar').css('display', 'block');
					$('#text-nom-edit').text('Nombre del sillon:');
					$('#nombre_mesa').attr('type', 'text');
					$('#nombre_mesa').val();
					$('#div_tm_edit').css('display', 'block');
					$('#silla_editar').css('display', 'none');
					$('#div_nombre_barra').hide();
				} else if(tipo_mesas[$('#tipo_mesa_editar').val()]['id'] == 9){
					$("#img_mesa_editar").attr("src", tipo_mesas[$('#tipo_mesa_editar').val()]['imagen']);
					$('#barra_editar').css('display', 'none');
					$('#img_mesa_editar').css('display', 'none');
					$('#text-nom-edit').text('Número de silla:');
					$('#nombre_mesa').attr('type', 'number');
					$('#div_tm_edit').css('display', 'none');
					$('#silla_editar').css('display', 'block');
					$('#div_nombre_barra').hide();
				} else{
					$("#img_mesa_editar").attr("src", tipo_mesas[$('#tipo_mesa_editar').val()]['imagen']);
					$('#barra_editar').css('display', 'none');
					$('#img_mesa_editar').css('display', 'block');
					$('#text-nom-edit').text('Número de mesa:');
					$('#nombre_mesa').attr('type', 'number');
					$('#div_tm_edit').css('display', 'block');
					$('#silla_editar').css('display', 'none');
					$('#div_nombre_barra').hide();
				}
				
			}

///////////////// ******** ---- 	FIN changeImgEdit			------ ************ //////////////////
$(function() {
	$("#sucursaladd").select2();
});
</script>