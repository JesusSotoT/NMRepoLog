<style>
	.table100{
	width : 100% !important    
}
.req{
	color:#FF0000; 
	font-weight:bold;
}
th, td{
	text-align: center !important;
	vertical-align: middle !important;
}
.fa-1x{
	color: red !important;
}
</style>
<?php 
//echo json_encode($datos);
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Configuracion</title>
		<link rel="stylesheet" href="">
	</head>

	<!-- DT CSS -->
	<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

	<!-- DT JS  -->
	<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
	<script src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src='../../libraries/datepicker/js/bootstrap-datepicker.min.js'></script>
	<script src='../../libraries/datepicker/js/bootstrap-datepicker.es.js'></script>
	<script src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>

	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	
	<!--Select 2 -->
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

	<script src="views/facturacion/simpleUpload.js"></script>

	<body> 
		<br> 
		<div id="divpasos" class="container well" style="height: 590px;">
			<div class="row" style="padding-bottom: 17px;">
				<div class="col-xs-12 col-md-12">
					<h3>Facturación</h3>
				</div>
			</div>
			<div class="row">                 
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#paso1">Datos de Facturación</a></li>
					<li><a data-toggle="tab" href="#paso2">Serie y Folio</a></li>
					<li><a data-toggle="tab" href="#multi_serie">Multi Serie</a></li>
				</ul>

				<div class="tab-content" style="min-height:650px;">
								
					<div id="paso1" class="tab-pane fade in active">
						<div class="panel panel-default" style="font-size: 13px;">
							<div class="panel-heading">
								<h3 class="panel-title">Captura los datos de Facturación</h3>
							</div>
							<div class="panel-body"> 
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">ID</label>  
										<input id="idDF" class="form-control" type="text" value="<?php echo $datos[0]['id']; ?>" readonly>              
									</div>                                                                                                                                    
									<div class="col-sm-4">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>RFC</label> 
										<input id="rfc" class="form-control" type="text" value="<?php echo $datos[0]['rfc']; ?>">                
									</div> 
									<div class="col-sm-4">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Régimen Fiscal</label> 
										<select id="regimen" class="form-control">
										<!-- <option value="0">Selecciona el Régimen Fiscal</option> -->
											<?php 
												foreach ($regimen as $k => $v) {
													if($datos[0]['regimen'] == $v['id']){
														echo '<option value="'.$v['id'].'" selected>('.$v['c_regimenfiscal'].') ' .$v['descripcion'].'</option>';
													}else{
														echo '<option value="'.$v['id'].'">('.$v['c_regimenfiscal'].') ' .$v['descripcion'].'</option>'; 
													}
													
												}
											 ?>                                                
										</select>                
									</div>                                        
									<div class="col-sm-6">
										<label class="control-label"><span class="obligatorio f032">*</span>Nombre o Razón Social</label>                                                                                            
										<input id="razon" class="form-control" type="text" value="<?php echo $datos[0]['razon_social']; ?>">     

									</div>
									<div class="col-sm-6">
										<label class="control-label"><span class="obligatorio f032">*</span>Domicilio</label>                                         
										<input id="domicilio" class="form-control" type="text" value="<?php echo $datos[0]['calle']; ?>">     
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032">*</span>País</label> 
										<input id="pais" class="form-control" type="text" value="<?php echo $datos[0]['pais']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032">*</span>Estado</label> 
										<input id="estado" class="form-control" type="text" value="<?php echo $datos[0]['estado']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032">*</span>Municipio</label> 
										<input id="municipio" class="form-control" type="text" value="<?php echo $datos[0]['municipio']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032">*</span>Colonia</label> 
										<input id="colonia" class="form-control" type="text" value="<?php echo $datos[0]['colonia']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032">*</span>Número exterior</label> 
										<input id="num_ext" class="form-control" type="text" value="<?php echo $datos[0]['num_ext']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Código Postal</label> 
										<input id="cp" class="form-control" type="text" value="<?php echo $datos[0]['cp']; ?>">                
									</div>
									
									<div class="col-sm-3">
										<label class="control-label"><span class="obligatorio f032">*</span>Ciudad</label> 
										<input id="ciudad" class="form-control" type="text" value="<?php echo $datos[0]['ciudad']; ?>">                
									</div>
									<div class="col-sm-3">
										<label class="control-label"><span class="obligatorio f032">*</span>Lugar de Expedición</label> 
										<input id="lugar_exp" class="form-control" type="text" value="<?php echo $datos[0]['lugar_exp']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Selecciona PAC</label> 
										<select id="pac" class="form-control">                                                
											<option value="1" <?php if($datos[0]['pac'] == 1) {echo ' selected'; } ?> >Azurian</option>
											<option value="2" <?php if($datos[0]['pac'] == 2) {echo ' selected'; } ?> >Formas Continuas</option>
											<!--<option value="3" <?php if($datos[0]['pac'] == 3) {echo ' selected'; } ?> >Konesh</option>-->
										</select>                 
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Usuario PAC</label> 
										<input id="userFC" class="form-control" type="text" value="<?php echo $datos[0]['fc_user']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Conraseña PAC</label> 
										<input id="passFC" class="form-control" type="password" value="<?php echo $datos[0]['fc_password']; ?>">                
									</div>
									
									<div class="col-sm-3">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Certificado (.cer): <?php echo $datos[0]['cer']; ?></label><br>                                                                                                      
										<a class="btn btn-default btn-xs" target="blank" href="../../netwarelog/descarga_archivo_fisico.php?d=1&f=<?php echo $datos[0]['cer'];?> &ne=pvt_configura_facturacion" title="Descargar archivo">
											<i class="fa fa-arrow-circle-down"></i>
										</a>                                            
										<!--
											<a class="btn btn-default btn-xs" target="blank" href="../../netwarelog/descarga_archivo_fisico.php?d=0&f=00001000000307601732.cer&ne=pvt_configura_facturacion" title="Ver archivo">
												<i class="fa fa-file-o"></i>
											</a> 
										-->                                           
										<input type="file" id="miarchi" size="100" name="Filedata" style="display: block;">
										<input type="hidden" id="cer" value="<?php echo $datos[0]['cer']; ?>">

									</div>
									<div class="col-sm-3">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Llave (.key): <?php echo $datos[0]['llave']; ?> </label><br> 
										<a class="btn btn-default btn-xs" target="blank" href="../../netwarelog/descarga_archivo_fisico.php?d=1&f=<?php echo $datos[0]['llave'];?> &ne=pvt_configura_facturacion" title="Descargar archivo">
											<i class="fa fa-arrow-circle-down"></i>
										</a>
										<!--
											<a class="btn btn-default btn-xs" target="blank" href="../../netwarelog/descarga_archivo_fisico.php?d=0&f=CSD_MATRIZ_IHA000314A38_20150716_115157.key&ne=pvt_configura_facturacion" title="Ver archivo">
												<i class="fa fa-file-o"></i>
											</a>  
										-->                                         
										<input type="file" id="miarchi2" size="100" name="Filedata" style="display: block;">  
										<input type="hidden" id="key" value="<?php echo $datos[0]['llave']; ?>">              
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span class="obligatorio f032 f033">*</span>Contraseña CSD</label> 
										<input id="clave" class="form-control" type="password" value="<?php echo $datos[0]['clave']; ?>">                
									</div>
									<div class="col-sm-2">
										<label class="control-label">Contraseña CIEC</label> 
										<input id="passCiec" class="form-control" type="password" value="<?php echo $datos[0]['pass_ciec']; ?>">                
									</div>                                    
									<div class="col-sm-2">
										<label class="control-label">Kiosko de Facturación</label>                                                                                         
										<select id="ticket" class="form-control">
											<option value="1" <?php if($datos[0]['ticket_config'] == 1) {echo ' selected'; } ?> >SI</option>
											<option value="0" <?php if($datos[0]['ticket_config'] == 0) {echo ' selected'; } ?> >NO</option>
										</select>                 
									</div>
									<div class="col-sm-2">
										<label class="control-label" >*No. Version</label> 
										<select  id="noVersion" class="form-control" >
											<option value="3.2" <?php echo ($datos[0]['version'] == 3.2) ? "selected" : ""; ?> >3.2</option>
											<option value="3.3" <?php echo ($datos[0]['version'] == 3.3) ? "selected" : ""; ?>>3.3</option>
										</select>
										<!-- <input id="noVersion" class="form-control" type="text" value="<?php echo $datos[0]['version']; ?>"> -->
									</div>
									
									<div class="row">
										<div class="col-sm-12" style="text-align: center;">
										<br>                                            
											<button class="btn btn-default" type="button" onclick="save();">Guardar <i class="fa fa-check" aria-hidden="true"></i> </button>
										</div>
									</div>
								</div>
							</div>
						</div> 
					</div><!-- Fin del Tab Paso1 -->

					<div id="paso2" class="tab-pane fade">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Capture la Serie y el Folio</h3>
							</div>
							<div class="panel-body">                                
								<div class="form-group">
									<div class="row">
									   <div style="padding-left:15px;"><h4>Datos para Factura</h4> </div>
									   <div class="col-sm-2">
											<label class="control-label">ID</label>
											<input id="idSF" class="form-control" type="text" value="<?php echo $serieFolio[0]['id']; ?>" readonly>
										</div>
										<div class="col-sm-2">
											<label class="control-label">*Serie</label>  
											<input id="serie" class="form-control" type="text" value="<?php echo $serieFolio[0]['serie']; ?>">              
										</div>
										<div class="col-sm-2">                
											<label class="control-label">*Folio</label>  
											<input id="folio" class="form-control" type="text" value="<?php echo $serieFolio[0]['folio']; ?>">
										</div>
									</div>
									<div class="row">
										<div style="padding-left:15px;"><h4>Datos para Recibos y Honorarios</h4></div>
										<div class="col-sm-2">
											<label class="control-label">ID</label>
											<input id="idSFH" class="form-control" type="text" value="<?php echo $serieFolio[0]['id']; ?>" readonly>
										</div>
										<div class="col-sm-2">
											<label class="control-label">*Serie</label>  
											<input id="serie_h" class="form-control" type="text" value="<?php echo $serieFolio[0]['serie_h']; ?>">              
										</div>
										<div class="col-sm-2">                
											<label class="control-label">*Folio</label>  
											<input id="folio_h" class="form-control" type="text" value="<?php echo $serieFolio[0]['folio_h']; ?>">
										</div>
									</div>
									<div class="row">
										<div style="padding-left:15px;"><h4>Datos para Notas de Crédito</h4></div>
										<div class="col-sm-2">
											<label class="control-label">ID</label>
											<input id="idSFN" class="form-control" type="text" value="<?php echo $serieFolio[0]['id']; ?>" readonly>
										</div>
										<div class="col-sm-2">
											<label class="control-label">*Serie</label>  
											<input id="serie_nc" class="form-control" type="text" value="<?php echo $serieFolio[0]['serie_nc']; ?>">              
										</div>
										<div class="col-sm-2">                
											<label class="control-label">*Folio</label>  
											<input id="folio_nc" class="form-control" type="text" value="<?php echo $serieFolio[0]['folio_nc']; ?>">
										</div> 
									</div>



									<div class="row">
										<div style="padding-left:15px;"><h4>Datos para complementos de pago</h4></div>
										<div class="col-sm-2">
											<label class="control-label">ID</label>
											<input id="idSFCP" class="form-control" type="text" value="<?php echo $serieFolio[0]['id']; ?>" readonly>
										</div>
										<div class="col-sm-2">
											<label class="control-label">*Serie</label>  
											<input id="serie_cp" class="form-control" type="text" value="<?php echo $serieFolio[0]['serie_cp']; ?>">              
										</div>
										<div class="col-sm-2">                
											<label class="control-label">*Folio</label>  
											<input id="folio_cp" class="form-control" type="text" value="<?php echo $serieFolio[0]['folio_cp']; ?>">
										</div> 
									</div>




																				
									<div class="col-sm-12" style="text-align: center;">  
										<label class="control-label" style="color:white">.</label><br>
										<button class="btn btn-default" onclick="saveSF();">Guardar<i class="fa fa-check" aria-hidden="true"></i></button>
									</div>
								</div>  
							</div>
						</div>
					</div><!-- Fin del Tab Paso2 -->
					<div id="multi_serie" class="tab-pane fade">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Gestion de Series y Folios</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tbl_multi_serie" class="table table-striped table-hover" style="width:100%;">
										<thead>
											<tr>
												<th>ID</th>
												<th>Serie</th>
												<th>Folio</th>
												<th>Eliminar</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
									<div class="col-md-2 col-md-offset-10">
										<button class="btn btn-primary btn-block" onclick="agregar();">Agregar</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>  <!-- Fin del div de los tabs -->
			</div>
		</div>

	<!-- Modal -->
	<div id="mdl_multi_serie" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 id="ms_title" class="modal-title">Modificar</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="row form-group">
	      		<div class="col-md-6 col-sm-12">
	      			<label for="ms_serie">Serie:</label><br>
	      			<input id="ms_serie" type="text" class="form-control">
	      		</div>
	      		<div class="col-md-6 col-sm-12">
	      			<label for="ms_folio">Folio:</label><br>
	      			<input id="ms_folio" type="text" class="form-control">
	      		</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	      	<button id="ms_btn" class="btn btn-primary" onclick="guardar_ms();">Guardar</button>
	        <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>

	  </div>
	</div>

	</body>
</html>

	<script>
	$(document).ready(function() {
		$("#regimen, #ticket, #pac").select2();


		$(".obligatorio").hide();
		if($('#noVersion').val() == "3.3") $(".f033").show();
		else if ($('#noVersion').val() == "3.2") $(".f032").show();

		rellenar_tabla();
		
	});

	function save(){

		var file = $('#miarchi').val();
		var file2 = $('#miarchi2').val();

		if(file == '' || file == null){
			$('#cer').val('<?php echo $datos[0]['cer']; ?>');
		}else{
			$('#cer').val(file);
		}
		if(file2 == '' || file2 == null){
			 $('#key').val('<?php echo $datos[0]['llave']; ?>');
		}
		else{
			$('#key').val(file2);
		}

		var cer = $('#cer').val();
		var key = $('#key').val();

		var id = $('#idDF').val();
		var rfc = $('#rfc').val();
		var regimen = $('#regimen').val();
		var pais = $('#pais').val();
		var razon = $('#razon').val();
		var domicilio = $('#domicilio').val();
		var num_ext = $('#num_ext').val();
		var colonia = $('#colonia').val();
		var ciudad = $('#ciudad').val();
		var municipio = $('#municipio').val();
		var estado = $('#estado').val();
		var cp = $('#cp').val();
		var clave = $('#clave').val();
		var lugar_exp = $('#lugar_exp').val();
		var ticket = $('#ticket').val();
		var pac = $('#pac').val();
		var userFC = $('#userFC').val();
		var passFC = $('#passFC').val();
		var passCiec = $('#passCiec').val();
		var noVersion = $('#noVersion').val();

		//VALIDACION
		if( noVersion == '3.2'){
			if(id == '' || rfc == '' || regimen == '' || razon == '' || domicilio == ''  || pais == '' || estado == '' ||  municipio == '' || colonia == '' || num_ext == '' || cp == '' || ciudad == '' ||  lugar_exp == '' || pac == '' || userFC =='' || passFC =='' || clave=='' ||  passCiec == '' || ticket == '' ){
				alert('¡Faltan Campos Obligatorios para facturación 3.2!');
				return false;
			}
		}
		else if(noVersion == '3.3') {
			if(id == '' || rfc == '' || regimen == '' || cp == '' || pac == '' || userFC =='' || passFC =='' || clave==''  ){
				alert('¡Faltan Campos Obligatorios para facturación 3.2!');
				return false;
			}
		}

		if(cer != ''  || key != ''){
			console.log(cer+''+key);
			if(clave == ''){
				alert('Debe ingresar la clave');
				return false;
			}
			if(cer != ''){
				if(key == ''){
					alert('Debe cargar el archivo .key');
					return false; 
				}
				
			} 
			if(key != ''){
				if(cer == ''){
				   alert('Debe cargar el archivo .cer');
					return false; 
				}
				
			}
		}

		
		$('input[type=file]').simpleUpload('views/facturacion/subirarchivo.php', {
			start: function(file){
				//upload started
				console.log("upload started");
			},

			progress: function(progress){
				//received progress
				console.log("upload progress: " + Math.round(progress) + "%");
			},

			success: function(data){
				//upload successful
				console.log(data);
				var objresp = $.parseJSON(data);
				console.log(objresp);
				
			},

			error: function(error){
				alert('Error al subir el archivo');
				console.log("upload error: " + error.name + ": " + error.message);
			}
		});
		$.ajax({
				url: 'ajax.php?c=facturacion&f=save',
				type: 'post',
				data:{id:id,
					rfc:rfc,
					regimen:regimen,
					pais:pais,
					razon:razon,
					domicilio:domicilio,
					num_ext:num_ext,
					colonia:colonia,
					ciudad:ciudad,
					municipio:municipio,
					estado:estado,
					cp:cp,
					cer:cer,
					key:key,
					clave:clave,
					lugar_exp:lugar_exp,
					ticket:ticket,
					pac:pac,
					userFC:userFC,
					passFC:passFC,
					passCiec:passCiec,
					noVersion:noVersion,
				}
		})
		.done(function(data) {
			console.log(data);
			if( data.substring(0, 1) !=  "["){
				alert(data);
			}
			else {
				alert("¡Registro exitoso!");
			}         
			location.reload(); 
		});
	 
	}
	function saveSF(){
		var idSF = $("#idSF").val();
		var serie = $("#serie").val();
		var folio = $("#folio").val();
		var serie_h = $("#serie_h").val();
		var folio_h = $("#folio_h").val();
		var serie_nc = $("#serie_nc").val();
		var folio_nc = $("#folio_nc").val();
		var serie_cp = $("#serie_cp").val();
		var folio_cp = $("#folio_cp").val();

		$.ajax({
				url: 'ajax.php?c=facturacion&f=saveSF',
				type: 'post',
				dataType: 'json',
				data:{idSF:idSF,
					serie:serie,
					folio:folio,
					serie_h:serie_h,
					folio_h:folio_h,
					serie_nc:serie_nc,
					folio_nc:folio_nc,
					serie_cp:serie_cp,
					folio_cp:folio_cp
				}
		})
		.done(function(data) {
			alert('¡Registro Exitoso!');
			location.reload();             
		}) 

	}
	function verCer(file){
		window.open('../SAT/cliente/'+file+'', '_blank');
	}

	
	$('#noVersion').change(function(event) {
		 $(".obligatorio").hide();
		if($(this).val() == "3.3"){
			$(".f033").show();
		}else if ($(this).val() == "3.2"){
			$(".f032").show();
		}
	});
	
	$('#pac').change(function(){  
		var pac = $('#pac').val();
		if(pac == 1){            
			$("#userFC").attr('disabled', true).val('');
			$("#passFC").attr('disabled', true).val('');
		}else{            
			$("#userFC").attr('disabled', false);
			$("#passFC").attr('disabled', false);
		}
	});

	function rellenar_tabla(){
		$.post("ajax.php?c=facturacion&f=obtener_series",
		function(data){
			//console.log(data);
			$('#tbl_multi_serie').DataTable().destroy();
	    table = $('#tbl_multi_serie').DataTable({
	    	//"paging": false,
	        language: {
	          search: "Buscar:",
	          lengthMenu:"Mostrar _MENU_ elementos",
	          zeroRecords: "No hay datos.",
	          infoEmpty: "No hay datos que mostrar.",
	          info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
	          paginate: {
	            first:    "Primero",
	            previous: "Anterior",
	            next:     "Siguiente",
	            last:     "Último"
	          }
	        },
	        "order": [[ 0, "asc" ]],
	        data:data,
	        columns: [
	          { data: 'id' },
	          { data: 'serie' },
	          { data: 'folio' },
	          { data: 'delete'}
	        ]
	    });
		}, "JSON");
	}

	function modificar(id, serie, folio){
		$('#ms_btn').removeAttr('data-id');
		$('#ms_title').html("Modificar");
		$('#ms_btn').attr('data-id', id);
		$('#ms_serie').val(serie);
		$('#ms_folio').val(folio);
		$('#mdl_multi_serie').modal('show');
	}

	function agregar(){
		$('#ms_btn').removeAttr('data-id');
		$('#ms_serie').val("");
		$('#ms_folio').val("");
		$('#ms_title').html("Agregar");
		$('#mdl_multi_serie').modal('show');
	}

	function eliminar_ms(id){
		if (confirm("¿Realmente desea eliminar el registro?")) {
			$.post("ajax.php?c=facturacion&f=eliminar_serie",
			{
				id: id
			},
			function(data){
				if (data == true) {
  				alert("El registro se elimino exitosamente.");
  			} else {
  				alert("El registro no se pudo eliminar.");
  				console.log(data);
  			}
				rellenar_tabla();
			}, "JSON");
		}
	}

	function guardar_ms(){
		var id = $('#ms_btn').attr('data-id'), serie = $('#ms_serie').val(), folio = $('#ms_folio').val();
		if (typeof id !== typeof undefined && id !== false) {
    	$.post('ajax.php?c=facturacion&f=modificar_serie',
  		{
  			id: id,
  			serie: serie,
  			folio: folio
  		},
  		function(data){
  			if (data == true) {
  				alert("El registro se modifico exitosamente.");
  			} else {
  				alert("El registro no se pudo modificar.");
  				console.log(data);
  			}
  		}, "JSON");
		} else {
			$.post('ajax.php?c=facturacion&f=agregar_serie',
  		{
  			serie: serie,
  			folio: folio
  		},
  		function(data){
  			if (data == true) {
  				alert("El registro se agrego exitosamente.");
  			} else {
  				alert("El registro no se pudo agregar.");
  				console.log(data);
  			}
  		}, "JSON");
		}
		rellenar_tabla();
	}
	
</script>




