<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Recalculo de inventario</title>

	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
	<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- DataTable -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


</head>
<body>


	<div class="container well">

	

		<div class="row"> 
			<div class="col-md-12"><h3> Reporte de ventas a consigna</h3></div> 
		</div>
		<div class="panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-3">
                        <label>Desde</label>
                        <div id="datetimepicker1" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input id="desde" class="form-control" type="text" placeholder="">
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <label>Hasta</label>
                        <div id="datetimepicker2" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input id="hasta" class="form-control" type="text" placeholder="">
                        </div>

                    </div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="proveedor">Proveedor</label>
							<select id="proveedor" class="form-control">
								<!-- <option value=""> - Todos - </option> -->
								<?php 
								foreach ($proveedores as $key => $value) { 
								?>
									<option value="<?php echo $value['idPrv']; ?>"><?php echo $value['razon_social']; ?></option>
								<?php } 
								?>
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="almacen">Almacen</label>
							<select id="almacen" class="form-control">
								<?php 
								foreach ($almacenes as $key => $value) { 
								?>
									<option value="<?php echo $value['id']; ?>"><?php echo $value['nombre']; ?></option>
								<?php } 
								?>
							</select>
						</div>
					</div>
					
					

					<div class="col-sm-3 ">
						<div class="form-group">
							<label > </label>
							<button id="procesar" class="btn btn-default" style="width: 100%; height: 100%;">Procesar</button>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<br>
		<div id="reporte" class="row">
			<table id="tablaConsigna" class="table table-striped table-bordered sizeprint" style="width: 100%">
				<thead>
					<tr>
						<th > ID producto </th>
						<th> Código  </th>
						<th> Producto  </th>
						<th> Costo  unitario </th>
						<th> Precio </th>
						<th> Inventario Actual </th>
						<th> Entradas </th>
						<th> Devoluciones cliente </th>
						<th> Devoluciones a proveedor </th>
						<th> Costo faltantes </th>
						<th> Merma </th>
						<th> Precio mermas </th>
						<th> Total ventas por producto </th>
						<th> Importe de ventas (costo) </th>
				  	</tr>
				</thead>
				<tbody>
					<!-- <?php 
					foreach ($valorInventario as $key => $value) { 

					?>
						




						<tr id="<?php echo $value['id_producto'] ?>"
							class="p_<?php echo $value['codigo'] ?>" 
							caracteristicas="<?php echo  ( $value['id_producto_caracteristica'] )  ?>" 
							lote="<?php echo "0" ?>" 
							series='<?php
									echo "[]" ;
								?>'
							ajusteseries="0"
							cantidad="<?php echo $value['c'] ?>">
							<td > 
								 <?php 
								 	echo ($value['lote']) ? "{ ".$value['no_lote']." } " : "";
								 	echo $value['producto']; 
								 	echo ($value['id_producto_caracteristica'] != '\'0\'') ? "[".( $this->caract2nombre($caracteristicas,$value['id_producto_caracteristica']) )."]" : "";
								 ?> 
							</td>

							<td style="text-align: right;">
								<?php echo $value['costo_promedio'] ?>	
							</td>

							<td style="text-align: right;">
								<input type="text" value="<?php echo $value['costo_promedio'] ?>" class="form-control" style="text-align: right;">
							</td>

							<td style="text-align: right;">
								<input type="text" value="0" class="form-control" disabled style="text-align: right;">
							</td>
						</tr>
					<?php

					}
					?> -->
				</tbody>
				<tfoot>

				</tfoot>
			</table>
			
		</div>
	</div>
	
</body>
<script>
	$('#desde').datepicker({
        format: "yyyy-mm-dd",
        language: "es"
    });
    $('#hasta').datepicker({
        format: "yyyy-mm-dd",
        language: "es"
    });
    $('#procesar').click(function(event) {
    	$('#desde').val()
    	$('#hasta').val()
    	$('#proveedor').val()
    	$('#almacen').val()
    	$.ajax({
            url: 'ajax.php?c=inventario&f=reporteConsignacion',
            type: 'GET',
            dataType: 'json',
            data: 	{ 
            			desde : $('#desde').val(),
                    	hasta : $('#hasta').val(),
                    	proveedor : $('#proveedor').val(),
                    	almacen :  $('#almacen').val()
                	},
        })
        .done(function(resp) {
        	$('#tablaConsigna>tbody').empty()

        	$.each(resp, function(index, val) {
           		$('#tablaConsigna>tbody').append(`
           			<tr>
						<td> ${val.id} </td>
						<td> ${val.codigo} </td>
						<td> ${val.nombre} </td>
						<td> ${val.costo} </td>
						<td> ${val.precio} </td>
						<td> ${val.inventarioActual} </td>
						<td> ${val.entradasOC} </td>
						<td> ${val.devolucionesV} </td>
						<td> ${val.devolucionesOC} </td>
						<td> ${val.costoDevOC} </td>
						<td> ${val.merma} </td>
						<td> ${val.importeMerma} </td>
						<td> ${val.salidasV} </td>
						<td> ${val.importeV} </td>
						<td>  </td>
				  	</tr>
           		`);
           		
           	});

        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    });
</script>
</html>