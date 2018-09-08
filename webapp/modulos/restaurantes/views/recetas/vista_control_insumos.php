<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS				------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- dataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!-- Morris  -->
	    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <!-- Button Print css -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!-- Datepicker -->
    	<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS			------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS				------ ************ ////////////////// -->
	 
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap JavaScript -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- dataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>
	<!-- Morris  -->
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<!-- Datepicker -->
	    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
		
	<!--  Sistema  -->
		<script type="text/javascript" src="js/recetas/recetas.js"></script>
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS			------ ************ ////////////////// -->
	</head>
	
	<body>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div 
                	class="panel-heading" 
                	role="tab"
                	data-toggle="collapse" 
                	data-parent="#accordion" 
                	href="#tab_filtros" 
                	aria-controls="collapse_filtros"
            		style="cursor: pointer">
                    <h4 class="panel-title"><strong><i class="fa fa-filter"></i> Filtros</strong></h4>
                </div>
                <div id="tab_filtros" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_tab_filtros">
                    <div class="panel-body">
						<div class="row"><?php
							date_default_timezone_set('America/Mexico_City');
					
							$f_fin = $f_ini = date('Y-m-d'); ?>
							
							<div class="col-md-4" style="padding-top: 20px">
								<div class="input-group input-group-lg">
							    	<div class="input-group-addon">
							    		<i class="fa fa-calendar"></i> inicio: 
							    	</div>
									<input type="text" id="f_ini" class="form-control" value="<?php echo $f_ini; ?>" style="width: 300px">
							    </div>
							</div>
							<div class="col-md-2">
						    	<p>Sucursal:</p>
								<select id="sucursal" class="selectpicker" data-width="90%" multiple data-live-search="true"><?php
									
									foreach ($sucursales as $key => $value) { ?>
										<option value="<?php echo $value['id'] ?>">
											<?php echo $value['nombre'] ?>
										</option> <?php
									} ?>
								</select>
							</div>
							<div class="col-md-2">
						    	<p>Almacen:</p>
								<select id="almacen" class="selectpicker" data-width="90%" multiple data-live-search="true"><?php
									
									foreach ($almacenes as $key => $value) { ?>
										<option value="<?php echo $value['id'] ?>">
											<?php echo $value['nombre'] ?>
										</option> <?php
									} ?>
								</select>
							</div>
							<div class="col-md-2">
		                        <p>Graficar por:</p>
		                        <select id="grafica" class="selectpicker" data-width="90%">
		                            <option value="1">Dia</option>
		                            <option selected value="2">Semana</option>
		                            <option value="3">Mes</option>
		                            <option value="4">Año</option>
		                        </select>
		                    </div>
						</div>
						<div class="row">
							<div class="col-md-4" style="padding-top: 20px">
								<div class="input-group input-group-lg">
								   	<div class="input-group-addon">
								   		<i class="fa fa-calendar"></i> fin: &nbsp;&nbsp;&nbsp;&nbsp;
								   	</div>
									<input type="text" id="f_fin" class="form-control" value="<?php echo $f_fin; ?>" style="width: 300px">
							    </div>
							</div>
							<div class="col-md-4">
						    	<p>Insumos:</p>
								<select id="insumos" class="selectpicker" data-width="95%" multiple data-live-search="true"><?php
									if ($datos['insumos_normales']) { ?>
										<optgroup label="Insumos"><?php
											foreach ($datos['insumos_normales'] as $key => $value) { ?>
												<option value="<?php echo $value['idProducto'] ?>">
													<?php echo $value['nombre'] ?>
												</option> <?php
											} ?>
			                          	</optgroup><?php
									}
									
									if ($datos['insumos_preparados']) { ?>
										<optgroup label="Insumos preparados"><?php
											foreach ($datos['insumos_preparados'] as $key => $value) { ?>
												<option value="<?php echo $value['idProducto'] ?>">
													<?php echo $value['nombre'] ?>
												</option><?php
											} ?>
			                          </optgroup><?php
									} ?>
								</select>
							</div>
							<div class="col-md-2">
						    	<p>Tipo:</p>
								<select id="tipo" class="selectpicker" data-width="90%">
									<option value="">-Todos-</option>
									<option value="3">Insumo</option>
									<option value="4">Insumo preparado</option>
								</select>
							</div>
							<div class="col-md-1" style="padding-top: 15px">
								<button 
									id="btn_listar_salidas" 
									class="btn btn-default btn-lg" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									style="width: 135px" 
									onclick="recetas.listar_movimientos_inventario({
										f_ini : $('#f_ini').val(),
										f_fin : $('#f_fin').val(),
										sucursal : $('#sucursal').val(),
										almacen : $('#almacen').val(),
										grafica : $('#grafica').val(),
										insumos : $('#insumos').val(),
										tipo : $('#tipo').val(),
										btn : 'btn_listar_salidas',
										div : 'contenedor'
									});">
									<i class="fa fa-search"></i> Buscar
								</button>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
		<div class="row" style="margin: 0">
			<div id="contenedor" class="col-md-12">
				<!-- En esta div se carga el contenido desde el modelo -->
			</div>
		</div>
		<div id="modal_ver_receta" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 95%">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" onclick="$('#modal_editar_para_llevar').click()">&times;</button>
	      				<div class="row">
	      					<div class="col-md-3" style="padding-top: 5px">
			        			<h4>
			        				Ver producto
			        				<input type="number" value="" min="1" id="id_modal_ver_receta" style="display: none; width: 50px" align="center" readonly="1"/>
			        			</h4>
	      					</div>
	      				</div>
	      			</div>
	      			<div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-4">
					    		<img  style="max-width: 100%; width: 100%; height: auto;" id="img_ver_receta" src="">
					    	</div>
					    	<div class="col-xs-8">
					    		<h4 id="title_insumos">Insumos:</h4>
					    		<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Cantidad</strong></th>
											<th align="center"><strong>Insumo</strong></th>
											<th><strong>Unidad</strong></th>
											<th align="center"><strong>Costo Proveedor</strong></th>
											<th align="center"><strong>Costo Preparacion</strong></th>
										</tr>
									</thead>
									<tbody id="body_ver_receta">
										
									</tbody>
								</table>
								<h4 id="title_insumos_preparados">Insumos preparados:</h4>
								<table id="tabla_insumos_preparados" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Cantidad</strong></th>
											<th align="center"><strong>Insumo</strong></th>
											<th><strong>Unidad</strong></th>
											<th align="center"><strong>Costo Proveedor</strong></th>
											<th align="center"><strong>Costo Preparacion</strong></th>
										</tr>
									</thead>
									<tbody id="body_ver_receta_2">
										
									</tbody>
								</table>
					    	</div>
					    </div>
	      			</div>
				</div>
	  		</div>
		</div>
	</body>
</html>

<!-- Cambiamos los select por select con buscador -->
<script type="text/javascript">
// Convierte los calendarios
	recetas.convertir_calendario({
		id : 'f_ini'
	});
	recetas.convertir_calendario({
		id : 'f_fin'
	});

// Cambia el texto del select cuando esta vacio
	$('.selectpicker').selectpicker({
		noneSelectedText : 'Todos'
	}); 
</script>