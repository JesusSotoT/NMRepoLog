
<?php
// Establece la zona horaria
	date_default_timezone_set('America/Mexico_City');
?><html>
	<head>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->
		
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- tooltipster -->
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/tooltipster.css">
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-light.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-noir.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-punk.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-shadow.css" />
	<!-- jqueryui -->
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<!-- gridstack -->
	    <link rel="stylesheet" href="../../libraries/gridstack.js-master/dist/gridstack.css"/>
		
		<style type="text/css">
			.btnBlockMesa{
			  width:30px;
			  height:30px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  font-size:18px;
			  outline:none;
			  position:absolute;
			  right:5;
			  top:2;
			  transition:0.5s;
			}
			.btnBlockSilla{
			  margin: 0;
			  padding: 0;
			  width:15px;
			  height:15px;
			  border-radius:100%;
			  border:none;
			  color:#FFF;
			  font-size:7px;
			  outline:none;
			  position:absolute;
			  right:0;
			  top:0;
			  transition:0.5s;
			}
		</style>
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -					JS 					--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- gridstack -->
		<script src="../../libraries/lodash.min.js"></script>
	    <script src="../../libraries/gridstack.js-master/dist/gridstack.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    
	<!-- Sistema -->
		<script src="js/reservaciones/reservaciones.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<title>Mapa de reservaciones</title>
		
<!-- ** Funciones iniciales -->
		<script>
			$(document).ready(function() {
				status_reservaciones();
				function status_reservaciones(){
					reservaciones.status_reservaciones({status:1, f_ini:'<?php echo date('Y-m-d').' 00:01' ?>', f_fin:'<?php echo date('Y-m-d').' 23:59' ?>'});
					
				}
				
			// Lista los pendientes en la lista de espera
				function listar_espera(){
					reservaciones.listar_pendientes({lista_espera:1, orden:' inicio ASC',status:-1, div:'div_lista_espera', f_ini:'<?php echo date('Y-m-d') ?>'});
				}

				function listar_reservaciones1(){
					reservaciones.listar_reservaciones({lista_espera:1, orden:' inicio ASC',status:-1, div:'div_lista_espera', f_ini:'<?php echo date('Y-m-d') ?>'});
				}

			// Consulta el status de las reservaciones cada 10 segundos
				setInterval(status_reservaciones, 10000);
				
			// Consulta la lsita de espera cada minuto
				setInterval(listar_espera, 60000);

			// Consulta las reservaciones 
				setInterval(listar_reservaciones1, 60000);
				$('[data-tooltip="tooltip"]').tooltip(); 
			});
		</script>
<!-- ** FIN Funciones iniciales -->
	</head>
	<body>		
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12" style="margin-top: 10px">
								<button type="button" class="btn btn-success btn-lg" onclick="$('#btn_actualizar_reservacion').hide(); $('#btn_terminar_reservacion').hide(); $('#btn_agregar_reservacion').show();" data-toggle="modal" data-target="#modal_agregar_reservacion" style="width: 170px; margin-top: 0.5%;">
									<i class="fa fa-plus"></i> Agregar
								</button>
								<button type="button" onclick="reservaciones.listar_pendientes({orden:' inicio DESC',status:-1, div: 'div_listar_pendientes', f_ini:'<?php echo date('Y-m-d') ?>'})" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_listar_reservaciones" style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-search"></i> Ver
								</button>
							</div>
						</div>
					</div>
				    
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9" align="center">
				<?php foreach ($areas as $key => $valor) { ?>
				<div class="GtableTablesContent grid-stack" id="contenedor-<?php echo $valor['id']?>" style="<?php if($valor['id'] != $area_princ['id']) { ?> display: none; <?php } ?>width:100%; margin-top:10px"><?php
					foreach ($mesas as $key => $row) {
						switch($row['tipo']){
						//** Mesa normal(Individuales o juntas)
							case 0:
							// Mesa individual
								if($row['idmesas'] == '' && $row['idDep'] == $valor['id']){ ?>
									<?php if($row['id_tipo_mesa'] != 7 || $row['id_tipo_mesa'] == 7 && !empty($row['sillas'])) { ?>
									<div 
										class="grid-stack-item <?php if($row['id_tipo_mesa'] != 7 && $row['id_tipo_mesa'] != 8) { ?>mesa<?php } ?>" 
										id="<?php echo $row['mesa'] ?>" 
										data-gs-x="<?php echo $row['x'] ?>" 
										data-gs-y="<?php echo $row['y'] ?>" 
										data-gs-no-resize="1"
										<?php if($row['id_tipo_mesa'] != 7 && $row['id_tipo_mesa'] != 8) { ?> 
											
											data-gs-width="<?php echo $row['width'] ?>" 
											data-gs-height="<?php echo $row['height'] ?>"
										<?php } else { ?>
											data-gs-width="<?php echo $row['width_barra'] ?>" 
											data-gs-height="<?php echo $row['height_barra'] ?>"
										<?php } ?>
										>
										<div style="cursor: move; width:100%; height:100%;text-align: center;" class="grid-stack-item-content">
											<div >
												<?php if($row['id_tipo_mesa'] == 7) { ?>
													<section style="border-radius: 8px; float:right; width: 100%; height: 100%; background-color: #9A673A" >
														<div style="width: 100%; height: 100%; overflow: auto">
															<?php foreach ($row['sillas'] as $key => $value) { ?>
																<div  
																class="mesa"
																
																style=" float:left;">
																	<div 
																		id="silla_<?php echo $value['mesa'] ?>" class="grid-silla" style="position:relative; background-color: #423228; margin: 3px; border-radius: 15%; width: 30px; height: 30px;">
																		<div
																		id="mesa_<?php echo $value['mesa'] ?>"
																		id_comanda="<?php echo $value['idcomanda'] ?>"
																		funcion="guardar" 
																		onclick="reservaciones.abrir_reservacion({
																		mesa:<?php echo $value['mesa'] ?>,
																		cliente:$(this).attr('cliente'),
																		des:$(this).attr('des'),
																		id:$(this).attr('id_reservacion'),
																		funcion:$(this).attr('funcion'),
																		fecha:$(this).attr('fecha'),
																		tipo_mesa: <?php echo $row['id_tipo_mesa'] ?>,
																		num_per: $(this).attr('num_per')})"	 style="font-size: 12px; cursor: pointer;color: white; width: 90%; position:absolute; transform: translate(-50%, -50%); left: 50%; top: 50%;" ><?php echo $value['nombre_mesa'] ?></div>
																		<button id="bloq_<?php echo $value['mesa'] ?>"
																		onclick="select_mesa(<?php echo $value['mesa'] ?>, <?php echo $value['id_tipo_mesa'] ?>)" mesa_status="<?php echo $value['mesa_status'] ?>" data-placement="top" class="btn-danger btnBlockSilla" style="z-index: 99; display:none;" ><i class="fa fa-lock" aria-hidden="true"></i></button>
																	</div>
																</div>
															<?php } ?>
														</div>
													</section>
												<?php } else if($row['id_tipo_mesa'] == 8) { ?>
													<section style="border-radius: 15px; position: absolute; top: 0; left: 0; bottom: 0; right: 0; margin: auto; width: 90%; height: 90%; border: solid 3px; border-color: #77407b;" >&nbsp</section>
												<?php } else if($row['id_tipo_mesa'] == 9) { ?>
													<section id="silla_<?php echo $row['mesa'] ?>" style="border-radius: 15%; margin-left: auto; margin-right: auto; width: 90%; height: 100%; background-color: #423228" >&nbsp</section>
												<?php } else { ?>
													<img id="img_<?php echo $row['mesa'] ?>" style="max-width: 100%; <?php if($row['id_tipo_mesa'] == 6) { ?> width: 101px; height: 97px;<?php } else {?> width: auto; height:100%; <?php } ?> " src="<?php echo $row['imagen'] ?>">
													<div id="div_total_<?php echo $row['mesa'] ?>" class="price" style="display:none; font-size: 12px; color: white; position: absolute; left 0; top: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px" >
														<!-- En esta div se carga el total de la comanda -->
													</div>
													<div id="div_tiempo_<?php echo $row['mesa'] ?>" class="time" style="display: none; font-size: 12px; color: white; position: absolute; right: 0; bottom: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px">
														<!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia -->
													</div>
												<?php } ?>
												<?php if($row['id_tipo_mesa'] != 8 && $row['id_tipo_mesa'] != 7) { ?> 
													<button data-tooltip="tooltip" title="Bloquear mesa" id="bloq_<?php echo $row['mesa'] ?>"
													onclick="select_mesa(<?php echo $row['mesa'] ?>, <?php echo $row['id_tipo_mesa'] ?>)" mesa_status="<?php echo $row['mesa_status'] ?>" data-placement="top" class="btn-danger <?php if($row['id_tipo_mesa'] != 9) { ?> btnBlockMesa <?php } else { ?> btnBlockSilla <?php } ?>" style="z-index: 99; display:none;" ><i class="fa fa-lock" aria-hidden="true"></i></button>
												<?php } ?>
												<div 
													 <?php if($row['id_tipo_mesa'] != 7 && $row['id_tipo_mesa'] != 8) { ?>
														funcion="guardar" 
														onclick="reservaciones.abrir_reservacion({
																mesa:<?php echo $row['mesa'] ?>,
																cliente:$(this).attr('cliente'),
																des:$(this).attr('des'),
																id:$(this).attr('id_reservacion'),
																funcion:$(this).attr('funcion'),
																fecha:$(this).attr('fecha'),
																tipo_mesa: <?php echo $row['id_tipo_mesa'] ?>,
																num_per: $(this).attr('num_per')})"
												<?php } ?>
													  id="mesa_<?php echo $row['mesa'] ?>"
													  id_comanda="<?php echo $row['idcomanda'] ?>"	
													  style="color: white;	
													  width: 55%;
													  cursor:pointer;
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
													       		<div id="div_nombre_mesa_<?php echo $row['mesa'] ?>" style="<?php if($row['id_tipo_mesa'] == 8) { ?> cursor: pointer; font-size: 12px; color: #12123f; font-weight:bold; <?php } else { ?> font-size: 18px; <?php } ?> " <?php if($row['id_tipo_mesa'] == 8) { ?>onclick="areas({id: <?php echo $row['id_area']?>}); <?php } ?>" ><?php echo $row['nombre_mesa'] ?></div>
													      	<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?><?php
							// * Mesa compuesta
								}else if($row['idDep'] == $valor['id']){
									$ids = explode(',',$row['idmesas']);
									$personas = explode(',',$row['mpersonas']);
									$size = count($ids); 
									$total_personas=0;
			
								// Calcula el total de personas
									foreach ($personas as $key => $value) {
										$total_personas += $value;
									} ?>
									
									<div 
										class="grid-stack-item mesa" 
										id="<?php echo $row['mesa'] ?>" 
										data-gs-x="<?php echo $row['x'] ?>" 
										data-gs-y="<?php echo $row['y'] ?>" 
										data-gs-no-resize="1"
										data-gs-width="2" 
										data-gs-height="2"
										>
										<div style="width:100%; height:100%;text-align: center;" class="grid-stack-item-content">
											<div >
													<img id="img_<?php echo $row['mesa'] ?>" style="width: auto; height:100%; max-width: 100%" src="images/mapademesas/libre_juntadas.png">
													<div id="div_total_<?php echo $row['mesa'] ?>" class="price" style="display:none; font-size: 12px; color: white; position: absolute; left 0; top: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px" >
														<!-- En esta div se carga el total de la comanda -->
													</div>
													<div id="div_tiempo_<?php echo $row['mesa'] ?>" class="time" style="display: none; font-size: 12px; color: white; position: absolute; right: 0; bottom: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px">
														<!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia -->
													</div>
												
												<div 
												funcion="guardar" 
														data-toggle="modal" 
														data-target="#modal_agregar_reservacion" 
														onclick="reservaciones.abrir_reservacion({
																mesa:<?php echo $row['mesa'] ?>,
																cliente:$(this).attr('cliente'),
																des:$(this).attr('des'),
																id:$(this).attr('id_reservacion'),
																funcion:$(this).attr('funcion'),
																fecha:$(this).attr('fecha'),
																tipo_mesa: <?php echo $row['id_tipo_mesa'] ?>,
																junta: 1,
																num_per: $(this).attr('num_per')})"
													  style="color: white;	
													  width: 55%;
													  cursor:pointer;
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
													       		<div id="div_nombre_mesa_<?php echo $row['mesa'] ?>" style="<?php if($row['id_tipo_mesa'] == 8) { ?> cursor: pointer; font-size: 12px; <?php } else { ?> font-size: 18px; <?php } ?> " <?php if($row['id_tipo_mesa'] == 8) { ?>onclick="areas({id: <?php echo $row['id_area']?>}); <?php } ?>" ><?php echo $row['nombre_mesa'] ?></div>
													      	<?php } ?>
												</div>
											</div>
										</div>
									</div><?php
								} //Else
							break;//** FIN Mesa normal(Individuales o juntas)

						} // Switch
					} // Fin foreach mesas?>
					
					
				</div>
				<?php } // Fin foreach areas?>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3><small>Nombre:</small></h3>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) reservaciones.guardar({mesa: 0, des: $('#des_espera').val() , btn:'add_reservacion', cliente:$('#nombre_espera').val(), num_per:$('#num_per_espera').val()})" id="nombre_espera" type="text" class="form-control"/>
							<span class="input-group-btn">
								<button 
									id="add_reservacion" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									onclick="reservaciones.guardar({
												mesa: 0,
												des: $('#des_espera').val(), 
												btn: 'add_reservacion', 
												cliente: $('#nombre_espera').val(),
												num_per:$('#num_per_espera').val()
											})" 
									type="button" 
									class="btn btn-success" 
									title="Agregar reservacion">
									<i class="fa fa-check"></i>
								</button>
							</span>
						</div>
						<div class="input-group input-group-lg" style="margin-top:10px;">
							<span class="input-group-addon">Núm. Personas:</span>
							<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) reservaciones.guardar({mesa: 0, des: $('#des_espera').val() , btn:'add_reservacion', cliente:$('#nombre_espera').val(), num_per:$('#num_per_espera').val()})" id="num_per_espera" type="number" class="form-control"/>
							
						</div>
						<h3><small>Comentarios:</small></h3>
			      		<textarea class="form-control" id="des_espera" rows="1" placeholder="num. personas, zona, etc."></textarea>
					</div>
					<div class="panel-boody" id="div_lista_espera">
						<!-- En esta div se cargan las reservaiones pendientes de la fila de espera -->
					</div>
				</div>
			</div>
		</div>
	<!-- Modal Cliente no existe-->
		<div class="modal fade" id="myModal" style="z-index:1052" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="close_agregar_cliente" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Registro de cliente</h4>
					</div>
					<form id="datos_cliente">
						<div class="modal-body">
							<blockquote style="font-size: 14px">
						    	<p>
						      		En esta funcion puedes <strong>agregar</strong> un cliente nuevo, solo llena los datos. Los
						      		campos marcados con "<strong><i class="fa fa-asterisk"></i></strong>" son necesarios.
						    	</p>
						    </blockquote>
						    
							<h5><strong>Los campos con * son obligatorios</strong></h5>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne" align="left">
								<!-- Nombre y telefono -->
									<div class="row">
									<!-- Nombre -->
										<div class="col-md-5">
											<div class="input-group">
												<label>Nombre: </label>
												<input id="nombre" required="1" type="text" class="form-control" required="1" placeholder="Pedro paramo">
											</div>
										</div>
										
									<!-- Telefono -->
										<div class="col-md-5">
											<div class="input-group">
												<label>Telefono: </label>
												<input id="tel" type="number" class="form-control" required="1" placeholder="0123456789">
											</div>
										</div>
										
									<!-- Mas detalles -->
										<div class="col-md-2">
											<br />
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
												<i style="font-size: 15px" class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
											</a>
										</div>

									<!-- E-mail -->
										<div class="col-md-5">
											<div class="input-group">
												<label>E-mail: </label>
												<input id="mail" type="email" required="1" class="form-control" placeholder="ejemplo@ejem.com">
											</div>
										</div>
									</div>
								</div>
								<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;" align="left">
									<div class="panel-body">
										<!-- Direccion y Num. Ext -->
										<div class="row">
											<!-- Direccion -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Direccion: </label>
													<input id="direccion" type="text" class="form-control" placeholder="Algun lugar">
												</div>
											</div>
					
											<!-- Num. Ext. -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Num. Ext.: </label>
													<input id="num_ext" type="number" class="form-control" placeholder="0000">
												</div>
											</div>
										</div>
										<br />
					
										<!-- Num. Int y Colonia -->
										<div class="row">
											<!-- Num. int. -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Num. int.: </label>
													<input id="num_int" type="number" class="form-control" placeholder="0000">
												</div>
											</div>
											<!-- Colonia -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Colonia: </label>
													<input id="colonia" type="text" class="form-control" placeholder="Colonia">
												</div>
											</div>
										</div>
					
										<!--  Codigo postal -->
										<div class="row">
											<!-- Codigo postal -->
											<div class="col-md-6">
												<div class="input-group">
													<label>CP: </label>
													<input id="cp" type="number" maxlength="5" max="99999" class="form-control" placeholder="00000">
												</div>
											</div>
										</div>
									</div>
								</div>
							<!-- FIN panel-collapse collapse -->
							</div>
						</div>
					<!-- Botones -->
						<div class="modal-footer">
							<button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">
								Cerrar
							</button>
							<button type="button" id="btn_guardar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-primary"  onclick="reservaciones.guardar_cliente({btn:'btn_guardar', formulario: 'datos_cliente'})">
								Guardar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!-- FIN Modal Cliente no existe-->
	<div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1100;" data-backdrop="static">
	    <div class="modal-dialog modal-sm">
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Espere un momento...</h4>
	        </div>
	        <div class="modal-body">
	          <div class="alert alert-default">
	            <div align="center"><label id="lblMensajeEstado"></label></div>
	            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
	                 <span class="sr-only">Loading...</span>
	             </div>
	        </div>
	        </div>
	      </div>
	    </div>
	  </div>
	<!-- Modal Editar cliente -->
		<div class="modal fade" id="myModal2" style="z-index:1052" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" id="close_editar_cliente" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Editar cliente</h4>
					</div>
					<form id="datos_cliente_edi">
						<div class="modal-body">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne" align="left">
								<!-- Nombre y telefono -->
									<div class="row">
									<!-- Nombre -->
										<div class="col-md-5">
											<div class="input-group">
												<label>Nombre: </label>
												<input id="nombre_edi" required="1" type="text" class="form-control" required="1" placeholder="Pedro paramo">
											</div>
										</div>
										
									<!-- Telefono -->
										<div class="col-md-5">
											<div class="input-group">
												<label>Telefono: </label>
												<input id="tel_edi" type="number" class="form-control" required="1" placeholder="0123456789">
											</div>
										</div>

									<!-- E-mail -->
										<div class="col-md-5">
											<div class="input-group">
												<label>E-mail: </label>
												<input id="mail_edi" type="email" required="1" class="form-control" placeholder="ejemplo@ejem.com">
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					<!-- Botones -->
						<div class="modal-footer">
							<button id="cerrar_modal2" type="button" class="btn btn-default" data-dismiss="modal">
								Cerrar
							</button>
							<button type="button" id="btn_guardar2" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-primary"  onclick="reservaciones.guardar_cliente({btn:'btn_guardar', formulario: 'datos_cliente_edi', tipo: 2})">
								Guardar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar cliente-->
	<!-- Modal agregar reservacion-->
		<div class="modal fade" style="z-index:1051" id="modal_agregar_reservacion" role="dialog" aria-labelledby="titulo_agregar_reservacion">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_agregar_reservacion" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_agregar_reservacion">Reservacion</h4>
					</div>
					<div class="modal-body" id="notificaciones_clientes">
						<div class="row">
							<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<select id="cliente" class="selectpicker" data-width="80%" data-live-search="true" onChange="ReloadSubcliente(this.value);">
										<option id="op-0" selected value="-1">- Seleccionar</option><?php
										
										foreach ($clientes as $key => $value) { ?>
											<option id="op-<?php echo $value["id"]?>" ed-nom='<?php echo $value["nombre"]?>' ed-tel='<?php echo $value["celular"]?>' ed-ema='<?php echo $value["email"]?>' value="<?php echo $value['id'] ?>">
												<?php echo $value['nombre'] ?>
											</option> <?php
										} ?>
										
									</select>
									<span class="input-group-btn">
										<button id="add_cliente" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" title="Agregar nuevo cliente">
											<i class="fa fa-user-plus"></i>
										</button>
									</span>
									<span class="input-group-btn">
										<button type="button" class="edit btn btn-info" style="display: none;" data-toggle="modal" onClick="edit_cliente(ed_id);" data-target="#myModal2" title="Editar cliente">
											<i class="fa fa-pencil-square-o"></i>
										</button>
									</span>
								</div>
			      				<h3><small>Núm. Personas:</small></h3>
			      				<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
									<input id="num_per" type="numbre" min="1" style="width: 80px;" class="form-control" value=""/>
								</div>
							</div>
							<div class="col-xs-5">
								<h3><small>Fecha / hora:</small></h3>
				        		<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-clock-o"></i></span><?php
								// Calcula una hora despues de la actual
									$fecha = strtotime ('1 hours', strtotime(date('Y-m-d H:i'))) ;
									$fecha = date ('Y-m-d H:i' , $fecha); 
									$fecha= str_replace(' ', 'T', $fecha); ?>
									
									<input id="fecha" type="datetime-local" class="form-control" value="<?php echo $fecha ?>"/>
								</div>
								<h3><small>Comentarios:</small></h3>
			      				<textarea class="form-control" id="des" rows="2" placeholder="Deja aqui tus comentarios"></textarea>
							</div>
						</div>
					</div>
				<!-- Botones -->
					<div class="modal-footer">
						<button id="btn_actualizar_reservacion" type="button" class="btn btn-primary btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="reservaciones.actualizar({des:$('#des').val(),btn:'btn_actualizar_reservacion', cliente:$('#cliente').val(),fecha:$('#fecha').val(),num_per:$('#num_per').val()})">
							<i class="fa fa-pencil"></i> Modificar
						</button>
						<button id="btn_agregar_reservacion" type="button" class="btn btn-success btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="reservaciones.guardar({mesa: mesa, des:$('#des').val(),btn:'btn_agregar_reservacion', cliente:$('#cliente').val(),fecha:$('#fecha').val(),num_per:$('#num_per').val()})">
							<i class="fa fa-check"></i> Ok
						</button>
						<button id="btn_terminar_reservacion" onclick="reservaciones.terminar({btn:'btn_terminar_reservacion'})" type="button" class="btn btn-success btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
							<i class="fa fa-check"></i> Terminar
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal agregar reservacion -->
	<!-- Modal reservaciones pendientes-->
		<div class="modal fade" id="modal_listar_reservaciones" role="dialog" aria-labelledby="titulo_listar_reservaciones">
			<div class="modal-dialog modal-lg" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_listar_reservaciones" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_listar_reservaciones">Reservaciones</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_listar_pendientes">
								<!-- En esta div se cargan las reservaciones pendientes -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal reservaciones pendientes-->
	<!-- Modal eliminar comanda -->
	<div id="modal_bloquear_mesa" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" onclick="$('#modal_bloquear_mesa').click()" class="close" >
						&times;
					</button>
					<h4 class="modal-title" id="title_bloq">Bloquear mesa</h4>
				</div>
				<div class="modal-body">
					<h3><small>Introduce la contraseña:</small></h3>
					<div class="input-group">
						<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
						<input
						onkeypress="(((document.all) ? event.keyCode : event.which)==13) reservaciones.bloquear_mesa({pass: $('#pass_bloquear_comanda').val(), idmesa: mesa_select, tipo_mesa: tipo_mesa_select})"
						id="pass_bloquear_comanda"
						type="password"
						class="form-control">
						<span class="input-group-btn">
							<button
								onclick="reservaciones.bloquear_mesa({pass: $('#pass_bloquear_comanda').val(), idmesa: mesa_select, tipo_mesa: tipo_mesa_select})"
								class="btn"
								type="button"
								id="modal_btn_bloq"
								>
								<i class="fa fa-lock"></i> Bloquear
							</button> 
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FIN Modal eliminar comanda -->
<!-- Ventana modal asignar mesa -->	
<div class="modal fade" id="modal_asignar_mesa" tabindex="-1" role="dialog" aria-labelledby="titulo_asignar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close"  onclick="$('#modal_asignar_mesa').modal('toggle');" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" align="left">Asignar mesa</h4>
			</div>
		<!-- Mensaje -->
			<div class="modal-body">
				<div class="row">
	      			<div class="col-md-12 col-xs-12" id="div_asignar_mesa">
	     				<!-- En esta div se cargan las mesas -->
	      			</div>
	      		</div>
			</div>
		<!-- Cancelar -->
		<div class="modal-footer">
				<button type="button" class="btn btn-danger" onclick="$('#modal_asignar_mesa').modal('toggle');">
					Cancelar
				</button>
			</div>
		</div>
	</div>
</div>
<!-- FIN Ventana modal asignar mesa -->
	</body>
</html>

<script type="text/javascript">
var area_select = <?php echo json_encode($area_princ['id'])?>;
var all_areas = <?php echo json_encode($areas)?>;
///////////////// ******** ---- 			areas				------ ************ //////////////////
//////// Obtiene el listado de las areas en las que estan las mesas
	// Como parametros recibe:
			// id -> id del area

			function areas($objeto) {
				console.log($objeto);
				$.each(all_areas, function(index, value) {
						if(value['id'] == $objeto['id']){
							$("#contenedor-" + value['id']).show();
							$("#titulo-area").text(value["area"]);
						} else {
							$("#contenedor-" + value['id']).hide();
						}
					});
				if($objeto['id'] == -1){
					$("#contenedor-llevar-domi").show();
					$("#titulo-area").html('<i class="fa fa-motorcycle" aria-hidden="true"></i> Servicio a domicilio /  <i class="fa fa-shopping-basket" aria-hidden="true"></i> Para llevar');
					$("#buttons-normal").hide();
					$("#buttons-domi-llevar").show();
				} else {
					$("#contenedor-llevar-domi").hide();
					$("#buttons-normal").show();
					$("#buttons-domi-llevar").hide();
				}
				area_select = $objeto['id'];
				// Actualiza la fecha
						var $fecha = new Date();
						var m = $fecha.getMonth() + 1;
						var $mes = (m < 10) ? '0' + m : m;
						var d = $fecha.getDate();
						var $dia = (d < 10) ? '0' + d : d;
						
						$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T';
					
						reservaciones.status_reservaciones({research: 1, status:1, f_ini:$fecha+'00:01', f_fin:$fecha+'23:59'});
			}

///////////////// ******** ---- 			FIN areas			------ ************ //////////////////

var serialize_widget_map = function (items) {
	console.log('---------> Items');
	console.log(items);
	
    $.each(items, function(index, value){
	   // Guarda sus cordenadas
		// guardar_cordenadas({id:value['el'][0]['id'], x:value['x'], y:value['y']});
	});
    
};

///////////////// ******** ---- 	info_comandas			------ ************ //////////////////
//////// Consulta la informacion de las comandas y la agrega a las divs
	// Como parametros puede recibir:

			function info_comandas($objeto) {
				console.log('-------> $objeto info_comandas');
				console.log($objeto);
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=info_comandas',
					type : 'GET',
					dataType : 'json',
				}).done(function(resp) {
					console.log('-------> Done info_comandas');
					console.log(resp);
					$(".price").hide();
					$(".time").hide();

					$.each(resp['result']['rows'], function(index, value) {
						if(option_ver == 1){
							$('#div_tiempo_' + value['idmesa']).show();
							$('#div_total_' + value['idmesa']).show();
						}
						$('#div_tiempo_' + value['idmesa']).html('<i class="fa fa-clock-o fa-lg"></i> ' + value['tiempo']);
						$('#div_total_' + value['idmesa']).html('<i class="fa fa-credit-card-alt"></i> $' + value['total']);
					});
				}).fail(function(resp) {
					console.log('---------> Fail info_comandas');
					console.log(resp);

					// Quita el loader
					$btn.button('reset');

					var $mensaje = 'Error al obtener la informacion';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN	info_comandas		------ ************ //////////////////

			var option_ver = 1;
					$(document).ready(function() {
		    $('.grid-stack-item').hover(function() {
		        $('#'+this.id+' .btnBlockMesa').css('display', 'block');
		         $('#'+this.id+' .btnBlockSilla').css('display', 'block');
		    }, function() {
		        $('#'+this.id+' .btnBlockMesa').css('display', 'none');
		         $('#'+this.id+' .btnBlockSilla').css('display', 'none');
		    });
		    $('.grid-silla').hover(function() {
		        $('#'+this.id+' .btnBlockSilla').css('display', 'block');
		    }, function() {
		        $('#'+this.id+' .btnBlockSilla').css('display', 'none');
		    });
		    
		});

			$(document).ready(function() {

			    $('.mesa').hover(function() {
			    	if(option_ver == 0){
				    	if($('#'+this.id+' .time').text().trim() != ""){
				        	$('#'+this.id+' .time').css('display', 'block');
				        }
				        if($('#'+this.id+' .price').text().trim() != ''){
				      		$('#'+this.id+' .price').css('display', 'block');
				    	}
				    }
			    }, function() {
			    	if(option_ver == 0){
			        	$('#'+this.id+' .time').css('display', 'none');
			        	$('#'+this.id+' .price').css('display', 'none');
			        }
			    });
			});

var ed_id = 0;
function ReloadSubcliente(element){
	if(element != -1){
		ed_id = element;
		$('.edit').show();
	} else {
		ed_id = 0;
		$('.edit').hide();
	}
}
var mesa_select = 0;
var tipo_mesa_select = 0;
function select_mesa(element, element2){
	mesa_select = element;
	tipo_mesa_select = element2;
	if($("#bloq_"+element).attr("mesa_status") == 4){
		$("#title_bloq").html("Desbloquear mesa");
		$("#modal_btn_bloq").html('<i class="fa fa-unlock-alt"></i> Desbloquear');
		$("#modal_btn_bloq").removeClass("btn-danger");
		$("#modal_btn_bloq").addClass("btn-success");
	} else {
		$("#title_bloq").html("Bloquear mesa");
		$("#modal_btn_bloq").html('<i class="fa fa-lock"></i> Bloquear');
		$("#modal_btn_bloq").removeClass("btn-success");
		$("#modal_btn_bloq").addClass("btn-danger");
	}
	if($("#mesa_"+element).attr('id_comanda') != 0){
				var $mensaje = 'Mesa ocupada imposible bloquearla';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				mesa_select = 0;
				tipo_mesa_select = 0;
				return 0;
	} else if($("#mesa_"+element).attr('funcion') == 'terminar'){
				var $mensaje = 'Mesa reservada imposible bloquearla';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				mesa_select = 0;
				tipo_mesa_select = 0;
				return 0;
	} else {
		$("#modal_bloquear_mesa").modal();
	}
}
///////////////// ******** ---- 		edit_cliente		------ ************ //////////////////
	// Abre un formulario con los campos necesarios para registrar un nuevo cliente
	
	function edit_cliente($objet) {
		console.log('edit_cliente');
		console.log($objet);
		console.log($("#op-"+$objet).attr('ed-nom'));
		$('#nombre_edi').val($("#op-"+$objet).attr('ed-nom'));
		$('#tel_edi').val($("#op-"+$objet).attr('ed-tel'));
		$('#mail_edi').val($("#op-"+$objet).attr('ed-ema'));
	}
///////////////// ******** ---- 		edit_cliente		------ ************ //////////////////

// Consulta el tiempo de las comandas cada minuto
	info_comandas();
	setInterval(info_comandas, 60000);

//Guarda la posicion de las mesas al cambiar
	$('.grid-stack').on('change', function (e, items) {
	    serialize_widget_map(items);
	});

// Convierte los id con draggable en divs ue se pueden arrastrar
	reservaciones.convertir_draggable(<?php echo json_encode($mesas) ?>);

// Lista los pendientes en la lista de espera
	reservaciones.listar_pendientes({lista_espera:1, orden:' inicio ASC',status:-1, div:'div_lista_espera', f_ini:'<?php echo date('Y-m-d') ?>'});
</script>