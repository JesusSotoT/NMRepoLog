<html>
	<head>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->
		
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <!-- <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css"> -->
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>
	    
	<!-- Sistema -->
		<script src="js/recetas2/recetas2.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<title>Recetas</title>
	</head>
	<body>		
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12" style="margin-top: 10px">
								<button 
									id="btn_nueva" 
									onclick="recetas.vista_nueva({div:'div_recetas', btn:'btn_nueva', panel:'success'})" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-success btn-lg" 
									style="width: 170px; margin-top: 0.5%;">
									<i class="fa fa-plus"></i> Agregar
								</button>
								<button 
									id="btn_editar" 
									onclick="recetas.vista_editar({div:'div_editar', btn:'btn_editar', panel:'primary'})" 
									data-toggle="modal" 
									data-target="#modal_editar" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-primary btn-lg"  
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-pencil"></i> Modificar
								</button>
								<button 
									id="btn_eliminar" 
									onclick="recetas.vista_eliminar({div:'div_eliminar',btn:'btn_eliminar',panel:'danger'})" 
									data-toggle="modal" 
									data-target="#modal_eliminar" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-danger btn-lg" 
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-trash"></i> Eliminar
								</button>
								<button 
									id="btn_copiar" 
									onclick="recetas.vista_copiar({div:'div_copiar',btn:'btn_copiar',panel:'warning'})" 
									data-toggle="modal" 
									data-target="#modal_copiar" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-warning btn-lg" 
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-clone"></i> Copiar
								</button>
								<button 
									id="btn_importar" 
									onclick="" 
									data-toggle="modal" 
									data-target="#modal_importar" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-info btn-lg" 
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-file-excel-o"></i> Importar
								</button>
							</div>
						</div>
					</div>
				    <div class="panel-body">
						<div id="div_recetas" class="row" style="overflow: scroll;height:75%">
							<!-- En esta div se carga el contenido de las recetas -->
						</div>
				  	</div>
				</div>
			</div>
		</div>
	<!-- Modal Copiar-->
		<div class="modal fade" id="modal_copiar" role="dialog" aria-labelledby="titulo_copiar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_copiar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_copiar">Copiar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_copiar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal copiar-->
	<!-- Modal importar-->
		<div class="modal fade" id="modal_importar" role="dialog" aria-labelledby="titulo_copiar">
			<div class="modal-dialog" style="width: 50%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_copiar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_importar">Subir recetas mediante layout</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class='row' id='layout_row'>
								<div class='col-sm-6' style="text-align:center">
									<a class="btn btn-primary" href='importacion/recetas.xls'><i class="fa fa-download"></i> Descargar layout</a>
								</div>
								<div class='col-sm-6'>
									<form action='ajax.php?c=recetas&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
										<div class="input-group" style="width:95%">
							                <label class="input-group-btn">
							                    <span class="btn btn-primary">
							                        Cargar&hellip; <input type="file" style="display: none;" id='layout' name='layout'>
							                    </span>
							                </label>
							                <input type="text" class="form-control" readonly>
            							</div> <br>
										<button class="btn btn-success" style="float:right; margin-right: 5%;" type='submit'>Subir</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal importar-->
	<!-- Modal editar-->
		<div class="modal fade" id="modal_editar" role="dialog" aria-labelledby="titulo_editar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_editar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_editar">Modificar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_editar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	<!-- Modal eliminar-->
		<div class="modal fade" id="modal_eliminar" role="dialog" aria-labelledby="titulo_eliminar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_eliminar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_eliminar">Eliminar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_eliminar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	</body>
</html>
<script>
// Carga la vista para editar las recetas
	// $('#btn_nueva').click();
	$('#btn_editar').click();
	$(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
	    var input = $(this),
	        numFiles = input.get(0).files ? input.get(0).files.length : 1,
	        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	    input.trigger('fileselect', [numFiles, label]);
	  });

	  // We can watch for our custom `fileselect` event like this
	  $(document).ready( function() {
	      $(':file').on('fileselect', function(event, numFiles, label) {

	          var input = $(this).parents('.input-group').find(':text'),
	              log = numFiles > 1 ? numFiles + ' files selected' : label;

	          if( input.length ) {
	              input.val(log);
	          } else {
	              if( log ) alert(log);
	          }

	      });
	  });
	  
	});
	function validar(t) {
		if(t.layout.value == '') {
			alert("Agregue un archivo xls.");
			return false;
		}
	}
</script>