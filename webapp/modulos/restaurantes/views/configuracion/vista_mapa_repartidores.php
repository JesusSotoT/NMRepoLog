<html>
	<head>
<!-- **	/////////////////////////- -				 CSS 					--///////////////////// **-->
		
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap min para imprimir CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	
	<!-- Sistema -->
	    <link rel="stylesheet" href="css/configuracion/mapa.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				JS 						--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	
	<!-- Sistema -->
		<script src="js/configuracion/mapa.js"></script>
		

<!-- **	//////////////////////////- -				FIN JS 					--///////////////////// **-->

		<title>Mapa servicio a domicilio</title>
	</head>
	<body>
        <div class="row">
            <div class="col-md-5">
            	<div class="alert alert-warning" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    <span class="sr-only">Warn:</span>
                    Pulsa el icono <i class="fa fa-area-chart"></i> para dibujar las areas,
                    al terminar pulsa el icono <i class="fa fa-hand-paper-o"></i>
               </div>
            </div>
            <div class="col-md-3">
            	<div class="input-group input-group-lg">
					<select onchange="mapa.listar_areas_mapa({id_area: $('#area').val()})" class="selectpicker" data-width="20%" id="area">
						<?php
						foreach ($areas as $key => $value) { ?>
							<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
						} ?>
					</select>
					<span class="input-group-btn">
			    		<button 
				    		class="btn btn-primary btn-lg"
							data-toggle="modal" 
							data-target="#modal_zona_reparto">
			    			<i class="fa fa-plus"></i>
			    		</button>
		    		</span>
				</div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-success btn-lg" onclick="mapa.guardar_areas_mapa({poligonos: map.getPolygonsAsCoordinates(), area: $('#area').val()})">
                    <i class="fa fa-check"></i> Guardar
                </button>
                <button class="btn btn-danger btn-lg" onclick="mapa.eliminar_areas_mapa()">
                    <i class="fa fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
        <div class="row">
			<div class="col-md-4 form-group">
				<input id="buscador" type="text" class="form-control" />
				<input id="lat_buscador" type="hidden" />
				<input id="lng_buscador" type="hidden" />
			</div>
			<div class="col-md-2">
				<button id="dirigente" class="btn btn-primary btn-block">Buscar</button>
			</div>
			<div class="col-md-1">
				<button id="localizador" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-primary btn-block">Localizar</button>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12">
                <div id="google-map"></div>
            </div>
        </div>
        <!-- Modal zona de reparto -->
		<div id="modal_zona_reparto" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Agregar zona de reparto</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Introduce la <strong>Zona</strong> de reparto y pulsa 
					      		<button class="btn btn-success"><i class="fa fa-check"></i> Ok</button> o <strong>enter</strong>
					    	</p>
					    </blockquote>
	      				<h3><small>Nombre:</small></h3>
	        			<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-font"></i></span>
							<input id="nombre_zona_reparto" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.agregar_zona_reparto({nombre: $('#nombre_zona_reparto').val(), btn: 'btn_zona_reparto'})" type="text" class="form-control">
							<span class="input-group-btn">
					      		<button
					      			id="btn_zona_reparto"
					      			class="btn btn-success btn-lg" 
					      			data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					      			onclick="mapa.agregar_zona_reparto({nombre: $('#nombre_zona_reparto').val(), btn: 'btn_zona_reparto'})">
					      			<i class="fa fa-check"></i> Ok
					      		</button>
			      			</span>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal zona de reparto -->	
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpwgA5xlOvsDDyR5gFhx5662NQmDfM0jw&libraries=drawing,places&callback=setMap" async defer></script>
        <script>
			setTimeout(function() {
        		mapa.listar_areas_mapa({id_area: $('#area').val()});
			}, 1000);
        </script>
    </body>
</html>