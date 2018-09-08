
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
				setInterval(status_reservaciones, 100000);
				
			// Consulta el estatus de las mesas cada 10 segundos
				setInterval(reservaciones.status_mesas, 100000);
				
			// Consulta la lsita de espera cada minuto
				setInterval(listar_espera, 60000);

			// Consulta las reservaciones 
				setInterval(listar_reservaciones1, 60000);
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
								<button id="btn_reservaciones" onclick="reservaciones.calendario({desde_foodware:1})" class="btn btn-warning btn-lg" style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-calendar"></i> Calendario
								</button>
							</div>
						</div>
					</div>
				    <div class="panel-body">
						<div class="row">
							<div class="col-md-12">
					    		<button onclick="reservaciones.areas()" type="button" class="btn btn-danger btn-lg" style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-star"></i> Todas
								</button><?php
								
							// Array con las clases para el cambio de color de los botones
								$clase=Array();
								$clase[0]='#779ECB; color: white';//Azul con letras blancas
								$clase[1]='#CFCFC4';//Gris
								$clase[2]='#77DD77; color: white';//Verde con letras blancas
								$clase[3]='#836953; color: white';//Cafe con letras blancas
								$clase[4]='#FFB347';//Naranja
								$clase[5]='#FDFD96';//Amarillo
								
							//** Creamos los botones de las areas
								// areas es un array que viene desde el modelo
								foreach ($areas as $key => $value) {
									$value['area'] = (empty($value['area'])) ? '* Sin asignar' : $value['area'] ; ?>
										
									<button 
										onclick="reservaciones.areas({id:'<?php echo $value['id'] ?>'})" 
										type="button" 
										class="btn btn-lg" 
										style="width: 170px; margin-top: 0.5%; background-color: <?php echo $clase[$key] ?>">
										<i class="fa fa-cutlery"></i> <?php echo substr($value['area'], 0, 10) ?>
									</button><?php
								} ?>
							</div>
						</div>
				  	</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9" align="center">
				<div class="grid-stack" id="contenedor" style="width:100%;height:400%;margin-top:1%"><?php
					foreach ($_SESSION['mesas'] as $key => $row) {
						switch($row['tipo']){
						//** Mesa normal(Individuales o juntas)
							case 0:
							// Mesa individual
								if($row['idmesas']==''){ ?>
									<div class="grid-stack-item" id="<?php echo $row['mesa'] ?>"  data-gs-x="<?php echo $row['x'] ?>" data-gs-y="<?php echo $row['y'] ?>" data-gs-no-resize="1" data-gs-width="2" data-gs-height="2">
										<div id="panel_<?php echo $row['mesa'] ?>" class="grid-stack-item-content panel panel-default">
											<div class="panel-heading" style="cursor: move">
												<div class="row">
													<div class="col-xs-5">
														<i class="fa fa-user fa-lg text-primary"></i> <?php echo $row['personas']; ?>
													</div>
													<div class="col-xs-7" id="div_tiempo_<?php echo $row['mesa'] ?>">
														<!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia -->
													</div>
												</div>
											</div>
											<div>
												<div class="panel-body">
													<div align="center">
														<div id="mesa_<?php echo $row['mesa'] ?>" funcion="guardar" class="row" style="cursor: pointer" data-toggle="modal" data-target="#modal_agregar_reservacion" onclick="reservaciones.abrir_reservacion({mesa:<?php echo $row['mesa'] ?>,cliente:$(this).attr('cliente'),des:$(this).attr('des'),id:$(this).attr('id_reservacion'),funcion:$(this).attr('funcion'),fecha:$(this).attr('fecha')})">
															<div class="col-xs-6">
																<h2><?php echo $row['nombre_mesa'] ?></h2>
																<div id="div_total_<?php echo $row['mesa'] ?>">
																	<!-- En esta div se carga el total de la comanda -->
																</div>
															</div>
															<div class="col-xs-6" style="padding-top: 1%"><?php
																if (!empty($row['mesero'])) { ?>
																	<i class="fa fa-hand-o-up fa-lg text-primary"></i> <?php echo $row['mesero'];
																} ?>
															</div>
														</div> 
													</div>
												</div>
											</div>
										</div>
									</div><?php
							// * Mesa compuesta
								}else{
									$ids=explode(',',$row['idmesas']);
									$personas=explode(',',$row['mpersonas']);
									$size=count($ids); 
									$total_personas=0;
			
								// Calcula el total de personas
									foreach ($personas as $key => $value) {
										$total_personas+=$value;
									} ?>
									
									<div class="grid-stack-item" id="<?php echo $row['mesa'] ?>"  data-gs-x="<?php echo $row['x'] ?>" data-gs-y="<?php echo $row['y'] ?>" data-gs-width="2" data-gs-height="2">
										<div class="grid-stack-item-content panel panel-info">
											<div class="panel-heading" style="cursor: move">
												<div class="row">
													<div class="col-xs-7">
														<div class="input-group">
															<div class="input-group-addon">
																<i class="fa fa-user"></i>
															</div>
															<input type="text" disabled="disabled" class="form-control" value="<?php echo $total_personas; ?>">
														</div>
													</div>
														<div class="col-xs-5">
														<i class="fa fa-object-ungroup fa-3x"></i>
													</div>
												</div>
											</div>
											<div id="mesa_<?php echo $row['mesa'] ?>">
												<div class="panel-body">
													<div align="center">
														<div class="row">
															<div class="col-xs-6" style="margin-top: -20px">
																<h2>
																	<div class="input-group">
																		<textarea disabled="disabled" rows="2" class="form-control" style="cursor: se-resize"><?php echo $row['idmesas'] ?></textarea>
																	</div>
																</h2>
																<div id="div_total_<?php echo $row['mesa'] ?>">
																	<!-- En esta div se carga el total de la comanda -->
																</div>
															</div>
															<div class="col-xs-6" style="vertical-align: middle"><?php
															if (!empty($row['mesero'])) { ?>
																<div class="col-xs-6" style="padding-top: 17%">
																	<i class="fa fa-hand-o-up fa-lg text-primary"></i> <?php echo $row['mesero']; ?>	
																</div><?php
															} ?>
															</div>
														</div> 
													</div>
												</div>
											</div>
										</div>
									</div><?php
								} //Else
							break;//** FIN Mesa normal(Individuales o juntas)
						} // Switch
					}// Fin foreach ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) reservaciones.guardar({des: $('#des_espera').val() , btn:'add_reservacion', cliente:$('#nombre_espera').val()})" id="nombre_espera" type="text" class="form-control"/>
							<span class="input-group-btn">
								<button 
									id="add_reservacion" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									onclick="reservaciones.guardar({
												des: $('#des_espera').val(), 
												btn: 'add_reservacion', 
												cliente: $('#nombre_espera').val()
											})" 
									type="button" 
									class="btn btn-success" 
									title="Agregar reservacion">
									<i class="fa fa-check"></i>
								</button>
							</span>
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
					
										<!-- Num. Int y E-mail -->
										<div class="row">
											<!-- Num. int. -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Num. int.: </label>
													<input id="num_int" type="number" class="form-control" placeholder="0000">
												</div>
											</div>
											<!-- E-mail -->
											<div class="col-md-6">
												<div class="input-group">
													<label>E-mail: </label>
													<input id="mail" type="email" class="form-control" placeholder="ejemplo@ejem.com">
												</div>
											</div>
										</div>
					
										<!-- Colonia y codigo postal -->
										<div class="row">
											<!-- Colonia -->
											<div class="col-md-6">
												<div class="input-group">
													<label>Colonia: </label>
													<input id="colonia" type="text" class="form-control" placeholder="Colonia">
												</div>
											</div>
					
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
									<select id="cliente" class="selectpicker" data-width="80%" data-live-search="true">
										<option selected value="-1">- Seleccionar</option><?php
										
										foreach ($clientes as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>">
												[<?php echo $value['id'] ?>] <?php echo $value['nombre'] ?>
											</option> <?php
										} ?>
										
									</select>
									<span class="input-group-btn">
										<button id="add_cliente" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" title="Agregar nuevo cliente">
											<i class="fa fa-user-plus"></i>
										</button>
									</span>
								</div>
			      				<h3><small>Comentarios:</small></h3>
			      				<textarea class="form-control" id="des" rows="2" placeholder="Deja aqui tus comentarios"></textarea>
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
							</div>
						</div>
					</div>
				<!-- Botones -->
					<div class="modal-footer">
						<button id="btn_actualizar_reservacion" type="button" class="btn btn-primary btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="reservaciones.actualizar({des:$('#des').val(),btn:'btn_actualizar_reservacion', cliente:$('#cliente').val(),fecha:$('#fecha').val()})">
							<i class="fa fa-pencil"></i> Modificar
						</button>
						<button id="btn_agregar_reservacion" type="button" class="btn btn-success btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="reservaciones.guardar({des:$('#des').val(),btn:'btn_agregar_reservacion', cliente:$('#cliente').val(),fecha:$('#fecha').val()})">
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
	</body>
</html>

<script type="text/javascript">
var serialize_widget_map = function (items) {
	console.log('---------> Items');
	console.log(items);
	
    $.each(items, function(index, value){
	   // Guarda sus cordenadas
		// guardar_cordenadas({id:value['el'][0]['id'], x:value['x'], y:value['y']});
	});
    
};

//Guarda la posicion de las mesas al cambiar
	$('.grid-stack').on('change', function (e, items) {
	    serialize_widget_map(items);
	});

// Convierte los id con draggable en divs ue se pueden arrastrar
	reservaciones.convertir_draggable(<?php echo json_encode($_SESSION['mesas']) ?>);

// Lista los pendientes en la lista de espera
	reservaciones.listar_pendientes({lista_espera:1, orden:' inicio ASC',status:-1, div:'div_lista_espera', f_ini:'<?php echo date('Y-m-d') ?>'});
</script>