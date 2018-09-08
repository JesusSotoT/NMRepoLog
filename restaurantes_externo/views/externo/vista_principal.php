<html>
	<head>
		<meta charset="utf-8" /> 
		<style>
            body {
               /* Location of the image */
                background-image: url(imagenes/mesa_inteligente/<?php echo $datos_qr['imagen_fondo']?>);
             /* Image is centered vertically and horizontally at all times */
                background-position: center center;
                /* Image doesn't repeat */
                background-repeat: no-repeat;
                /* Makes the image fixed in the viewport so that it doesn't move when
                 the content height is greater than the image height */
                background-attachment: fixed;
                /* This is what makes the background image rescale based on its container's size */
                background-size: cover;
                /* Pick a solid background color that will be displayed while the background image is loading */
                background-color: #464646;
            }

            .opcion {
				background:rgba(0, 0, 0, .6);
				padding:20px;
				margin:20px 0;
				color: white;
				box-shadow:0 5px 5px 3px rgba(0, 0, 0, 0.25);
			}
			.opcion2 {
				background:rgba(0, 0, 0, .6);
				padding:20px;
				margin:20px 0;
				color: white;
				box-shadow:0 5px 5px 3px rgba(0, 0, 0, 0.25);
			}
			.opcion:hover{
				background:rgba(255, 0, 0, .8);
			}
		</style>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body class="body">
<!-- ///////////////// ******** ---- 		CSS			------ ************ ////////////////// -->
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../webapp/libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../webapp/libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
	    <link rel="stylesheet" href="../webapp/libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	    
	    <link rel="stylesheet" href="../webapp/modulos/restaurantes/css/comandas/comandas.css">
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->
	    	
	    	
<!-- ///////////////// ******** ---- 		JS			------ ************ ////////////////// -->
	<!-- JQuery -->
		<script src="../webapp/libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../webapp/libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Notify  -->
		<script src="../webapp/libraries/notify.js"></script>
	<!-- printarea -->
		<script src="../webapp/libraries/PrintArea/js/jquery.printarea.js"></script>
		
	<!-- Sistema -->
		<script src="js/externo.js"></script>
			
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->
		<div class="container" align="center">
			<h3 class="opcion2">
				<?php echo $organizacion['nombreorganizacion'] ?>
				<br>
				<?php echo $datos_mesa['nombre'] ?>
				<?php if(!$datos_mesa) { ?>
				<br>
				Lo sentimos no la mesa no existe.
				<?php } ?>
			</h3><br /><br />
			<?php if($datos_mesa) { ?>
				<div id="row_todo" class="row" style="margin: 0; <?php if(empty($datos_mesa['password'])) { ?>display: none;<?php } ?>">
					<div  id="btn-1" style="display:none;"  class="col-xs-6" >
						<!-- Menu digital -->
						<div 
							style="cursor: pointer" 
							class="col-xs-10 col-xs-offset-1 opcion" 
							data-toggle="modal" 
							data-target="#modal_menu"
							onclick="externo.ver_menu({div: 'div_menu'})">
							<i class="fa fa-book fa-5x"></i>
							<p>Ver menu</p>
						</div>
					</div>
					<div  id="btn-2" style="display:none;"  class="col-xs-6" >
						<!-- Llamar al mesero -->
						<div 
							style="cursor: pointer" 
							class="col-xs-10 col-xs-offset-1 opcion" 
							onclick="externo.llamar_mesero({
										id_mesa: '<?php echo $datos_mesa['id_mesa'] ?>',
										notificacion: 1
									})">
							<i class="fa fa-bell-o fa-5x"></i>
							<p>Llamar al mesero</p>
						</div>
					</div>
					<div  id="btn-3" style="display:none;" class="col-xs-6" >
						<!-- Ordenar -->
						<div 
							data-toggle="modal" 
							data-target="#modal_login_ordenar"
							style="cursor: pointer" 
							class="col-xs-10 col-xs-offset-1 opcion" 
							onclick="$('#pass_ordenar').val('')">
							<i class="fa fa-shopping-basket fa-5x"></i>
							<p>Ordenar</p>
						</div>
					</div>
					<div  id="btn-4" style="display:none;" class="col-xs-6" >
						<!-- Imprimir comanda -->
						<div 
							style="cursor: pointer" 
							class="col-xs-10 col-xs-offset-1 opcion" 
							data-toggle="modal" 
							data-target="#modal_ticket"
							onclick="externo.cerrar_comanda({
										tel: '<?php echo $datos_mesa['tel'] ?>', 
										idComanda: '<?php echo $datos_mesa['id_comanda'] ?>',
										idmesa: '<?php echo $datos_mesa['id_mesa'] ?>', 
										tipo: 0, 
										bandera: 0, 
										sucursal: externo.datos_mesa['idSuc'],
										reimprime: 1
									})">
							<i class="fa fa-ticket fa-5x"></i>
							<p>Ver cuenta</p>
						</div>
					</div>
				
				
				
				</div>
			<?php  } ?>
		</div>
		<div id="modal_password" data-backdrop="static" class="modal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Asignar contrase&ntilde;a</h4>
					</div>
					<div class="modal-body">
						<h3><small>Contrase&ntilde;a:</small></h3>
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input
							id="pass_login"
							type="password"
							class="form-control">
						</div>
						<h3><small>Confirma contrase&ntilde;a:</small></h3>
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input
							id="pass_login_confirm"
							type="password"
							class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success" id="btn_save_pass" id_mesa="<?php echo $datos_mesa['id_mesa'] ?>" onclick="externo.save_pass()">Guardar</button>
					</div>
				</div>
			</div>
		</div>
		<div id="modal_login_ordenar" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" onclick="$('#modal_login_ordenar').modal('hide')" class="close" >
							&times;
						</button>
						<h4 class="modal-title">Ordenar</h4>
					</div>
					<div class="modal-body">
						<h3><small>Introduce la contrase&ntilde;a:</small></h3>
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input
							onkeypress="if(((document.all) ? event.keyCode : event.which)==13) externo.ordenar()"
							id="pass_ordenar"
							id_mesa="<?php echo $datos_mesa['id_mesa']; ?>"
							type="password"
							class="form-control">
							<span class="input-group-btn">
								<button
									class="btn btn-success"
									onclick="externo.ordenar()"
									type="button">
									<i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar
								</button> 
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal via de contacto -->
		<div id="modal_menu" class="modal fade" role="dialog">
		 	<div class="modal-dialog" role="document" style="width: 750px">
		    	<div class="modal-content">
		    		<div class="modal-header">
		    			<button type="button" class="close" data-dismiss="modal">&times;</button>
			        		<h4 class="modal-title">Menu</h4>
			      		</div>
			      	<div class="modal-body" id="div_menu">
			      		<!-- En esta div se carga el menu -->
			      	</div>
				</div>
			</div>
		</div>
		<!-- FIN Modal modal_menu -->
		<!-- Modal cuenta -->
		<div id="modal_ticket" class="modal fade" role="dialog">
		 	<div class="modal-dialog modal-sm" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		    			<button type="button" class="close" data-dismiss="modal">&times;</button>
			        		<h4 class="modal-title">Cuenta</h4>
			      		</div>
			      	<div class="modal-body" id="div_ticket">
			      		<!-- En esta div se carga el ticket -->
			      	</div>
				</div>
			</div>
		</div>
		<!-- FIN Modal cuenta -->
		<!-- Modal comandera -->
		<div id="modal_comandera" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 100%; margin: 0px">
	    		<div class="modal-content">
	      			<div class="modal-header">
	      				<div class="row" id="modal_cabecera">
	      					<div class="col-md-1 col-xs-1">
								<button 
									type="button" 
									class="btn btn-default btn-lg" 
									onclick="$('#modal_comandera').click();"
									style="height: 52px">
									<i class="fa fa-undo" aria-hidden="true"></i>
								</button>
	      					</div>
	      					<div class="col-md-3 col-xs-3" style="padding-top: 5px">
			        			<h4>
			        				<i class="fa fa-cutlery" style="color: #763F8B"></i> <i id="comanda_text"> xxxx</i> / 
			        				<i class="fa fa-th-large" style="color: #763F8B"></i> <i id="mesa_text"> Nombre de mesa</i>
			        				
			        			</h4>
	      					</div>
	      					<div class="col-md-1 col-xs-1" align="right">
                                <i
	                                class="fa fa-caret-left fa-4x"
	                                style="color: #DCB435"
	                                onclick="externo.mover_scroll({
		                                direccion: 'izquierda',
		                                div: 'div_departamentos',
		                                cantidad: 600
	                                })"> 
	                            </i>
                            </div>
	      				</div>
	      			</div>
	      			<div class="modal-body" id="div_comandera">
	      				<!-- En esta div se carga la comandera -->
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal comandera -->
		<script type="text/javascript">
			externo.datos_mesa = <?php echo json_encode($datos_mesa); ?>;
			var menus = "<?php echo $datos_qr['mostrar_opciones_menu']; ?>";
			$.each(menus.split(","), function(i,e){
			    $("#btn-"+e).show();
			});
			<?php if($datos_mesa && empty($datos_mesa['password'])) { ?>
				$("#modal_password").modal();
			<?php } ?>
			setTimeout(function() {
				externo.vista_comandera({div: 'div_comandera'});
			}, 500);
		</script>
	</body>
</html>
<div id="div_ejecutar_scripts" style="display: none">
	<!-- en esta div se ejecutan los scripts mediante la carga de contenido html -->
</div>
