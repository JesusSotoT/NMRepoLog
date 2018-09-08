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
<div class="row">

	<div class="col-md-4 col-md-offset-1" style="min-height: 100%" >
		<h2 align="center" style="color: red;">Vista previa:</h2>
		<?php if (!empty($logo)) { ?>
			<div id="logo" style="text-align: center">
				<input type="image" src="<?php echo $logo ?>" style="width:90%; max-width: 350px"/>
			</div>
		<?php } ?>
		<?php if (!empty($organizacion[0]['nombreorganizacion'])) { ?>
			<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
		<?php } ?>
		<?php if (!empty($organizacion[0]['RFC'])) { ?>
			<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">RFC: <?php echo $organizacion[0]['RFC'];?></div>
		<?php } ?>
		<?php if (!empty($datos_sucursal[0]['nombre'])) { ?>
			<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">Sucursal: <?php echo $datos_sucursal[0]['nombre'];?></div>
		<?php } ?>
		<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo utf8_decode($datos_sucursal[0]['direccion']." ".$datos_sucursal[0]['municipio'].", ".$datos_sucursal[0]['estado']);?></div>
		<?php 
			if($organizacion[0]['paginaweb']!='-'){
				echo '<div class="info_correo" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;">'.$organizacion[0]['paginaweb'].'</div>';	
			}
		?>
		<?php if (!empty($datos_sucursal[0]['tel_contacto'])) { ?>
			<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">Telefono: <?php echo $datos_sucursal[0]['tel_contacto'];?></div>
		<?php } ?>
		<?php if (!empty($img_correo['informacion_adicional'])) { ?>
			<div id="info_adi" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><br><?php echo $img_correo['informacion_adicional'] ?><br></div>
		<?php } ?>
		
		
	</div>
	<div class="col-md-6 col-md-offset-1" style="min-height: 100%" >
		<div class="col-md-5">
			
			<div class="row" style="margin-top: 50px;">
				<div class="input-group">
					<div class="input-group-addon" style="text-align: left; font-weight: bold;">
						Mostrar logo:
					</div>
					<select
					onchange="pedidos.actualizar_configuracion({mostrar_logo_correo: $('#mostrar_logo_correo').val()});
					if($('#mostrar_logo_correo').val() == 1) {$('#logo').show();} else {$('#logo').hide();}"
					id="mostrar_logo_correo"
					class="selectpicker">
						<option value="1">Si</option>
						<option value="2">No</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 50px;">
				<div class="input-group">
					<div class="input-group-addon" style="text-align: left; font-weight: bold;">
						Mostrar información:
					</div>
					<select
					onchange="pedidos.actualizar_configuracion({mostrar_info_correo: $('#mostrar_info_correo').val()});
					if($('#mostrar_info_correo').val() == 1) {$('.info_correo').show();} else {$('.info_correo').hide();}"
					id="mostrar_info_correo"
					class="selectpicker">
						<option value="1">Si</option>
						<option value="2">No</option>
					</select>
				</div>
			</div>
			<div class="row">
				<h3 style="margin: 0; margin-top: 40px;">Menú Digital:</h3>
				<div  style="text-align: center; margin-bottom: 10px;" >
					<img id="img_menu" src="<?php echo $img_correo['menu_digital'] ?>" style="width:90%;<?php if (empty($img_correo['menu_digital'])) { ?>display:none;<?php } ?>"/>
					<div id="arc_menu" style="<?php if (empty($img_correo['menu_digital'])) { ?>display:none;<?php } ?>"><i class="fa fa-file" aria-hidden="true" style="font-size: 60px; margin-top: 10px;"></i><br>Pdf</div>
				</div>
				<div class="row">
					<form id="myFormMenu"  method="post" enctype="multipart/form-data">
	                    <div class="row">
	                    <div class="col-sm-6">
	                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
	                        <div style="padding-left:3%">
	                          <input type="file" size="40" name="myfile">
	                        </div>
	                    </div>
	                    </div>
	                    <div class="row">
	                    <div class="col-sm-6">
	                      <div style="padding-left:3%">
	                        <button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Guardar archivo</button>
	                      </div>
	                    </div>
	                    </div>
	                    <div class="row"  id="div-share-menu" style="text-align: center; <?php if (empty($img_correo['menu_digital'])) { ?>display:none;<?php } ?>">
	                    	<iframe id="share-menu" src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F<?php echo $_SESSION['accelog_nombre_instancia'];?>%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F<?php echo $img_correo['menu_digital'] ?>&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId" width="99" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
	                    </div>
	                </form>
				</div>
			</div>
		</div>

		<div class="col-md-5 col-md-offset-1">
			<h3 style="margin: 0; margin-top: 20px;">Promociones:</h3>
			<div  style="text-align: center; margin-bottom: 10px;" >
				<img id="img_prom" src="<?php echo $img_correo['imagen_promo'] ?>" style="width:90%;<?php if (empty($img_correo['imagen_promo'])) { ?>display:none;<?php } ?>"/>
				<div id="arc_prom" style="<?php if (empty($img_correo['imagen_promo'])) { ?>display:none;<?php } ?>"><i class="fa fa-file" aria-hidden="true" style="font-size: 60px; margin-top: 10px;"></i><br>Pdf</div>
			</div>
			<div class="row">
				<form id="myFormProm"  method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-sm-6">
                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
                        <div style="padding-left:3%">
                          <input type="file" size="40" name="myfile">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                      <div style="padding-left:3%">
                        <button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Guardar archivo</button>
                      </div>
                    </div>
                    </div>
                    <div class="row" id="div-share-prom" style="text-align: center; <?php if (empty($img_correo['imagen_promo'])) { ?>display:none;<?php } ?>">
                    	<iframe id="share-prom" src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F<?php echo $_SESSION['accelog_nombre_instancia'];?>%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F<?php echo $img_correo['imagen_promo'] ?>&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId" width="99" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                    </div>
                </form>
			</div>
			<h3 style="margin: 0; margin-top: 20px; ">Felicitaciones:</h3>
			<div  style="text-align: center; margin-bottom: 10px;">
				<img id="img_fel" src="<?php echo $img_correo['imagen_felicitaciones'] ?>" style="width:90%;<?php if (empty($img_correo['imagen_felicitaciones'])) { ?>display:none;<?php } ?>"/>
				<div id="arc_fel" style="<?php if (empty($img_correo['imagen_felicitaciones'])) { ?>display:none;<?php } ?>"><i class="fa fa-file" aria-hidden="true" style="font-size: 60px; margin-top: 10px;"></i><br>Pdf</div>
			</div>
			<div class="row">
				<form id="myFormFel"  method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-sm-6">
                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
                        <div style="padding-left:3%">
                          <input type="file" size="40" name="myfile">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                      <div style="padding-left:3%">
                        <button type="submit" class="btn btn-primary btnMenu" id="btnimagen">Guardar archivo</button>
                      </div>
                    </div>
                    </div>
                    <div class="row" id="div-share-fel" style="text-align: center; <?php if (empty($img_correo['imagen_felicitaciones'])) { ?>display:none;<?php } ?>">
                    	<iframe id="share-fel" src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F<?php echo $_SESSION['accelog_nombre_instancia'];?>%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F<?php echo $img_correo['imagen_felicitaciones'] ?>&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId" width="99" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                    </div>
                </form>
			</div>
		</div>
		<div class="row" style="width: 90%;"> 
			<h3 style="margin-top: 10px;">Información Adicional:</h3>
			<textarea id="info-adicional" class="form-control" rows="5"></textarea>
			<button onclick="guardar_info();" class="btn btn-primary" style="margin-top: 5px; float: right;">Guardar información adicional</button>
		</div>
	</div>
</div> 
<script>
	var accelog_nombre_instancia = '<?php echo $_SESSION['accelog_nombre_instancia'] ?>';
	<?php if($img_correo['type_fel'] == 'pdf') { ?>
		$("#img_fel").hide();
		$("#arc_fel").show();
	<?php } else if(!empty($img_correo['imagen_felicitaciones'])) { ?>
		$("#img_fel").show();
		$("#arc_fel").hide();
	<?php } else { ?>
		$("#img_fel").hide();
		$("#arc_fel").hide();
	<?php }?>

	<?php if($img_correo['type_promo'] == 'pdf') { ?>
		$("#img_prom").hide();
		$("#arc_prom").show();
	<?php } else if(!empty($img_correo['imagen_promo'])) { ?>
		$("#img_prom").show();
		$("#arc_prom").hide();
	<?php } else { ?>
		$("#img_prom").hide();
		$("#arc_prom").hide();
	<?php }?>

	<?php if($img_correo['type_menu'] == 'pdf') { ?>
		$("#img_menu").hide();
		$("#arc_menu").show();
	<?php } else if(!empty($img_correo['menu_digital'])) { ?>
		$("#img_menu").show();
		$("#arc_menu").hide();
	<?php } else { ?>
		$("#img_menu").hide();
		$("#arc_menu").hide();
	<?php }?>

	<?php if($img_correo['mostrar_logo_correo'] == 2) {?>
		$("#logo").hide();
	<?php } ?> 

	<?php if($img_correo['mostrar_info_correo'] == 2) {?>
		$(".info_correo").hide();
	<?php } ?> 
	function guardar_info(){
		$.ajax({
			url : 'ajax.php?c=comandas&f=guardar_info',
			type : 'POST',
			dataType : 'json',
			data : {
				'info' : $("#info-adicional").val(),
			},
		}).done(function(data) {
			console.log('done entregado');
			console.log(data);
			$('#info_adi').html('<br>'+$("#info-adicional").val()+'<br>');

			var $mensaje = 'Información adicional guardada';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});

		}).fail(function(data) {
			console.log('fail entregado');
			console.log(data);

				var $mensaje = 'Error al guardar información adicional';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	}
	$("#myFormProm").on("submit", function(e){
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("myFormProm"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "ajax.php?c=comandas&f=uploadfileProm",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
       			processData: false
            })
                .done(function(res){
                  console.log(res);
                  $("#div-share-prom").show();
                    $('#img_prom').attr("src", res.direccion);
                    $('#share-prom').attr("src", "https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F"+accelog_nombre_instancia+"%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F"+res.nombre+"&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId");
                    if(res.type == 'pdf'){
                    	console.log("lala");
                    	$("#img_prom").hide();
						$("#arc_prom").show();
					} else {
						console.log("lelo");
						$("#img_prom").show();
						$("#arc_prom").hide();
					}
                });
        }); 
	$("#myFormFel").on("submit", function(e){
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("myFormFel"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "ajax.php?c=comandas&f=uploadfileFel",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
       			processData: false
            })
                .done(function(res){
                  console.log(res);
                  $("#div-share-fel").show();
                    $('#img_fel').attr("src",res.direccion);
                    $('#share-fel').attr("src", "https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F"+accelog_nombre_instancia+"%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F"+res.nombre+"&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId");
                    if(res.type == 'pdf'){
                    	$("#img_fel").hide();
						$("#arc_fel").show();
					} else {
						$("#img_fel").show();
						$("#arc_fel").hide();
					}
                });
        });
	$("#myFormMenu").on("submit", function(e){
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("myFormMenu"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "ajax.php?c=comandas&f=uploadfileMenu",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
       			processData: false
            })
                .done(function(res){
                  console.log(res);
                  	$("#div-share-menu").show();
                    $('#img_menu').attr("src",res.direccion);
                    $('#share-menu').attr("src", "https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fwww.netwarmonitor.com%2Fclientes%2F"+accelog_nombre_instancia+"%2Fwebapp%2Fmodulos%2Frestaurantes%2Fimages%2Fcorreo%2Fimages%2Fcorreo%2F"+res.nombre+"&layout=button&size=large&mobile_iframe=true&width=99&height=28&appId");
                    if(res.type == 'pdf'){
                    	$("#img_menu").hide();
						$("#arc_menu").show();
					} else {
						$("#img_menu").show();
						$("#arc_menu").hide();
					}
                });
        });
</script>