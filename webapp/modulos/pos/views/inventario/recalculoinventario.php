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
    
<!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
    <script src="../../libraries/export_print/pdfmake.min.js"></script>
    <script src="../../libraries/export_print/vfs_fonts.js"></script>

</head>
<body>


	<div class="container well">

	<div class="col-sm-1">
        <a class="btn btn-default" href="index.php?c=ajustesinventario&f=indexGrid"><i class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</a>
    </div>

		<div class="row"> 
			<div class="col-md-12"><h3> Ajuste de inventario</h3></div> 
		</div>
		<div class="panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="tipoAjuste">Tipo de ajuste</label>
							<select id="tipoAjuste" class="form-control">
								<option value="0">Existencias</option>
								<option value="1">Costos</option>
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
					<div class="col-sm-3">
						<div class="form-group">
							<label for="proveedore">Proveedor</label>
							<select id="proveedor" class="form-control" >
								<option value=""> - Todos - </option>
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
							<label for="productos">Productos</label>
							<select id="productos" class="form-control" name="states[]" multiple="multiple">
								<!-- <option value=""> - Todos - </option> -->
								
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
			
		</div>
	</div>
	
</body>
<script src="js/recalcualoinventario.js"></script>
</html>