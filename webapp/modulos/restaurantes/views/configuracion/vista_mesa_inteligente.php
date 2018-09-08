	<style>
        #vista_chida {
           /* Location of the image */
            background-image: url(../../../restaurantes_externo/imagenes/mesa_inteligente/<?php echo $datos_qr['imagen_fondo']; ?>);
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
	<!-- jquery-ui -->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
		<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- jquery.scrollTo.js -->
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>

	<!-- ** Sistema -->
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				pedidos.autocompleteProductos();

				pedidos.listar_ajustes();

				$('#btnAsignar').bind('click', function() {
					pedidos.asignaridPropina();
				});
			});
		</script>
<div class="row" style="margin: 0;">
	<div id="exTab2">	
		<ul class="nav nav-tabs" style="    padding: 0px 5px;">
			<li class="active"><a  href="#tab_conf_qr" data-toggle="tab">Configuración QR</a></li>
			<li><a href="#tab_conf_vcli" data-toggle="tab">Configuración Vista Cliente</a></li>
		</ul>
		<div class="tab-content ">
			<div class="tab-pane active" id="tab_conf_qr">
	          	<div class="row">
					<div class="col-md-4 col-md-offset-1" style="min-height: 100%" >
						<h2 align="center" style="color: red;">Vista previa vertical:</h2>
						<?php if (!empty($logo)) { ?>
							<div class="logo" style="text-align: center">
								<input type="image" src="<?php echo $logo ?>" style="width:90%; max-width: 350px"/>
							</div>
						<?php } ?>
						<?php if (!empty($organizacion[0]['nombreorganizacion'])) { ?>
							<div class="info_correo" style="text-align: center; font-weight: bold; margin-top: 5px; font-size:20px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
						<?php } ?>
						<div style="text-align: center; font-weight: bold; margin-top: 5px; font-size:20px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa x</div>
						<div id="qr" style="margin-top: 5px; text-align: center">
							<img style="width:90%; max-width: 350px" src="images/mesa_inteligente/qr.png"/>
						</div>
						
					</div>
					<div class="col-md-6 col-md-offset-1" style="min-height: 100%" >
						<div class="row">
							<div class="col-md-5">
								
								<div class="row" style="margin-top: 50px;">
									<div class="input-group">
										<div class="input-group-addon" style="text-align: left; font-weight: bold;">
											Mostrar logo:
										</div>
										<select
										onchange="pedidos.actualizar_configuracion({mostrar_logo_qr: $('#mostrar_logo_qr').val()});
										if($('#mostrar_logo_qr').val() == 1) {$('.logo').show();} else {$('.logo').hide();}"
										id="mostrar_logo_qr"
										class="selectpicker">
											<option value="1">Si</option>
											<option value="2">No</option>
										</select>
									</div>
								</div>
								<div class="row" style="margin-top: 50px;">
									<div class="input-group">
										<div class="input-group-addon" style="text-align: left; font-weight: bold;">
											Vista:
										</div>
										<select
										onchange="pedidos.actualizar_configuracion({tipo_vista_qr: $('#tipo_vista_qr').val()});"
										id="tipo_vista_qr"
										class="selectpicker">
											<option value="1">Vertical</option>
											<option value="2">Horizontal</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-5 col-md-offset-1">
								<div class="row" style="margin-top: 50px;">
									<div class="input-group">
										<div class="input-group-addon" style="text-align: left; font-weight: bold;">
											Mostrar nombre empresa:
										</div>
										<select
										onchange="pedidos.actualizar_configuracion({mostrar_info_qr: $('#mostrar_info_qr').val()});
										if($('#mostrar_info_qr').val() == 1) {$('.info_correo').show();} else {$('.info_correo').hide();}"
										id="mostrar_info_qr"
										class="selectpicker">
											<option value="1">Si</option>
											<option value="2">No</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="width: 90%; margin-top: 15px"> 
							<h2 align="center" style="color: red;">Vista previa horizontal:</h2>
							<div class="col-md-6" style="height: 265px;" >
								<div class="row" style="margin: 0; display: table; height: 100%;">
									<div class="row" style="margin: 0; display: table-cell; vertical-align: middle">
										<?php if (!empty($logo)) { ?>
											<div class="logo" style="text-align: center">
												<input type="image" src="<?php echo $logo ?>" style="height:190px; max-height: 190px"/>
											</div>
										<?php } ?>
										<?php if (!empty($organizacion[0]['nombreorganizacion'])) { ?>
											<div class="info_correo" style="text-align: center; font-weight: bold; margin-top: 5px; font-size:20px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-md-6" style="height: 265px;" >
								<div class="row" style="margin: 0; display: table; height: 100%;">
									<div class="row" style="margin: 0; display: table-cell; vertical-align: middle">
										<div style="text-align: center; font-weight: bold; margin-top: 5px; font-size:20px;font-family: Tahoma,'Trebuchet MS',Arial;">Mesa x</div>
										<div id="qr" style="margin-top: 5px; text-align: center">
											<img style="height: 220px; max-height: 220px" src="images/mesa_inteligente/qr.png"/>
										</div>
									</div>
								</div>
							</div>
							
							
						</div>
					</div>
				</div> 
			</div>
			<div class="tab-pane" id="tab_conf_vcli">
				<div class="row" style="margin: 0; text-align: center">
					<h2 align="center" style="color: red;">Vista previa:</h2>
				</div>
				<div class="row" id="vista_chida" style="margin: 0; overflow: auto; height: 420px;">
					<div class="container" align="center" style="margin-bottom: 30px">
						<h3 class="opcion2"><?php echo $organizacion[0]['nombreorganizacion'] ?><br> Mesa x</h3>
						<div class="row" style="margin: 0">
							<div id="btn-1" style="display:none;" class="btns col-xs-6" >
								<!-- Menu digital -->
								<div 
									style="cursor: pointer" 
									class="col-xs-10 col-xs-offset-1 opcion" >
									<i class="fa fa-book fa-5x"></i>
									<p>Ver menu</p>
								</div>
							</div>
							<div id="btn-2" style="display:none;" class="btns col-xs-6" >
								<!-- Llamar al mesero -->
								<div 
									style="cursor: pointer" 
									class="col-xs-10 col-xs-offset-1 opcion" >
									<i class="fa fa-bell-o fa-5x"></i>
									<p>Llamar al mesero</p>
								</div>
							</div>
							<div id="btn-3" style="display:none;" class="btns col-xs-6" >
								<!-- Ordenar -->
								<div 
									style="cursor: pointer" 
									class="col-xs-10 col-xs-offset-1 opcion" >
									<i class="fa fa-shopping-basket fa-5x"></i>
									<p>Ordenar</p>
								</div>
							</div>
							<div id="btn-4" style="display:none;" class="btns col-xs-6" >
								<!-- Imprimir comanda -->
								<div 
									style="cursor: pointer" 
									class="col-xs-10 col-xs-offset-1 opcion" >
									<i class="fa fa-ticket fa-5x"></i>
									<p>Ver cuenta</p>
								</div>
							</div>
						
						
						
						</div>
					</div>
				</div>
				<div class="row" style="margin:0; margin-top: 15px">
					<div class="col-xs-4" >
						<div class="input-group">
							<div class="input-group-addon" style="text-align: left; font-weight: bold;">
								Opciones:
							</div>
							<select id="mostrar_opciones_menu" onchange="change();"  class="selectpicker" multiple>
							  <option value="1">Ver menu</option>
							  <option value="2">Llamar al mesero</option>
							  <option value="3">Ordenar</option>
							  <option value="4">Ver cuenta</option>
							</select>

						</div>
					</div>
					<div class="col-xs-8" >
						<div class="col-md-12 container-fluid" >
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4>Fondo interfaz</h4>
								</div>
								<div class="panel-body">
									<blockquote style="font-size: 14px">
										<p>
											Seleccione una imagen de fondo para el menu digital
										</p>
									</blockquote>
									<div class="row" id="imagenes_fondo">
										<?php foreach ($imagenes_fondo as $key => $value) { ?>
											<div class="col-xs-4" style="margin-top: 10px; cursor: pointer;" onclick="change_2('<?php echo $value['ruta']?>','<?php echo $value['archivo']?>', '<?php echo $key?>')">
												<div style="position: relative; border: solid 0.1px; height: 180px; background-image: url(<?php echo $value['ruta']; ?>); background-position: center center; background-repeat: no-repeat;background-size: cover; background-color: #464646;">
													<i id="check_<?php echo $key?>" class="check_tr fa fa-check-circle" aria-hidden="true" style="<?php if($datos_qr['imagen_fondo'] != $value['archivo']) { ?>display: none;<?php } ?> position: absolute; top: 10px; right: 10px; color: #00a9ff; font-size: 35px;"></i>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="row" style="float: right; margin-right: 10px;">
										<form id="myFormImage"  method="post" enctype="multipart/form-data">
						                    <div class="row">
							                    <div class="col-sm-12">
							                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
							                        <div style="padding-left:3%">
							                          <input type="file" size="40" name="myfile">
							                        </div>
							                    </div>
						                    </div>
						                    <div class="row" style="float: right;">
							                    <div class="col-sm-12">
							                      <div style="padding-left:3%">
							                        <button type="button" onclick="subir_img()" class="btn btn-primary btnMenu" id="btnimagen">Subir imagen</button>
							                      </div>
							                    </div>
						                    </div>
						                </form>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	<?php if($datos_qr['mostrar_logo_qr'] == 2) {?>
		$(".logo").hide();
	<?php } ?> 

	<?php if($datos_qr['mostrar_info_qr'] == 2) {?>
		$(".info_correo").hide();
	<?php } ?>
	var menus = "<?php echo $datos_qr['mostrar_opciones_menu']; ?>";
	$.each(menus.split(","), function(i,e){
	    $("#btn-"+e).show();
	    $("#mostrar_opciones_menu option[value='" + e + "']").prop("selected", true);
	});
	var imagen_fondo = "<?php echo $datos_qr['imagen_fondo']?>";
	function subir_img(){
        var formData = new FormData(document.getElementById("myFormImage"));
        formData.append("dato", "valor");
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
            url: "ajax.php?c=comandas&f=uploadfileImageFondo",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
   			processData: false
        })
        .done(function(res){
          	console.log(res); 
          	if(res.status == 1){
          		$("#imagenes_fondo").html("");
          		$.each(res.imagenes, function(i,e){
          			var img = '<div class="col-xs-4" style="margin-top: 10px; cursor: pointer;" onclick="change_2(';
          			img += "'"+e.ruta+"','"+e.archivo+"', '"+i+"')"+'">';
          			img +='<div style="position: relative; border: solid 0.1px; height: 180px; ';
          			img += 'background-image: url('+e.ruta+'); background-position: center center; ';
          			img += 'background-repeat: no-repeat;background-size: cover; background-color: #464646;"> ';
          			img += '<i id="check_'+i+'" class="check_tr fa fa-check-circle" aria-hidden="true" ; style="';
          			console.log(imagen_fondo+" - "+e.archivo);
          			if(imagen_fondo != e.archivo) {

          				img += 'display: none;';
          			}
          			img += ' position: absolute; top: 10px; right: 10px; color: #00a9ff; font-size: 35px;"></i></div></div>';
          			$("#imagenes_fondo").append(img);
				});
          		var $mensaje = 'Imagen subida con exito.';
          		$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
          	} else {
          		var $mensaje = res.mensaje;
          		$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
          	}
          	

        });
    }
	//$("#mostrar_opciones_menu").val([1,2]);
	function change(){
		console.log($("#mostrar_opciones_menu").val());
		$(".btns").hide();
			pedidos.actualizar_configuracion({mostrar_opciones_menu_2 : "1", mostrar_opciones_menu: $('#mostrar_opciones_menu').val()});
			$.each($("#mostrar_opciones_menu").val(), function(i,e){
		    	$("#btn-"+e).show();
			});
		
	} 

	function change_2(ruta, archivo, key){
		if(!$("#check_"+key).is(":visible") ){
			imagen_fondo = archivo;
			$(".check_tr").hide();
			$("#check_"+key).show();
			$("#vista_chida").css('background-image', "url("+ruta+")");
			pedidos.actualizar_configuracion({imagen_fondo: archivo});
		}
	}
</script>