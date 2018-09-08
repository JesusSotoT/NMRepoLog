<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- CSS -->
<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<!-- JS  -->
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script src="../../libraries/dataTable/js/datatables.min.js"></script>
<script src='../../libraries/datepicker/js/bootstrap-datepicker.min.js'></script>
<script src='../../libraries/datepicker/js/bootstrap-datepicker.es.js'></script>
<script src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script src="../cont/massxml/massxml.js"></script>

<!-- JS Embebido -->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#buscar").show();
	$("#buscando").hide();
	$('[data-toggle="tooltip"]').tooltip(); 
	$('#inicial,#final').datepicker({
				format: "yyyy-mm-dd",
				language: "es"
			});
	$('[data-toggle="tooltip"]').tooltip().click(function(event) {
		  event.preventDefault();
		});
	//Ocultar boton de eliminar cuando no esta seleccionado temporales.
	$('#asignadas').on('change',function(){
		if ($(this).val() != 1) {
			$('#contenedor-eliminar').hide();
		} else {
			$('#contenedor-eliminar').show();
		}
	});
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	$("#pagos").hide()
});
</script>
<style>
.preview{
	font-size:1.2em !important;
	padding: 0 0.2em;
}
.hide-excel{
	display: none;
}
#tabla-data>tbody>tr>td{
	hyphens:auto;
	word-break:break-word;
	text-align:center;
	vertical-align: middle;
}
#tabla-data>thead>tr>th{
	word-break:break-word;
	text-align:center;
	vertical-align:middle;
	padding:.5em 1.2em;
}
.small-col{
	max-width: 1em !important;
}
table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable 
thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable 
thead .sorting_desc_disabled:after{
	font-size: 0.8em !important;
	right:1px !important;
}
table.dataTable thead .sorting::after, table.dataTable thead .sorting_asc::after, table.dataTable 
thead .sorting_desc::after, table.dataTable thead .sorting_asc_disabled::after, table.dataTable 
thead .sorting_desc_disabled::after{
	font-size: 0.8em !important;
	right:1px !important;	
}
</style>
<div class="container" style='width:100%;'>
	<div class='row' style='border-bottom:4px double #eee;'>
		<div class="col-xs-12 col-md-4" style='text-align:left;'>
			<img src='images/logo_acontia.jpg' style='width:60px;'>
		</div>
		<div class="col-xs-12 col-md-4" style='text-align:center;'>
			<b class='empresa' style='font-size:20px;'>Almacen Digital</b>
		</div>
		<div class="col-xs-12 col-md-4" style='text-align:center;'>
			<input type="button" value="Descarga Masiva SAT" onclick="loadXMLDownloaderPage()" class="btn btn-primary">
		</div>
	</div>
	<div class='row' style='margin-top:20px;'>
		<div class="col-xs-12 col-md-3 col-md-offset-3" style='text-align:center;'>
			<div class="input-group">
        <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon1"></span>
        <input type="text" class="form-control" style="top:1px;" id='inicial' placeholder="Fecha Inicial">
    	</div>
    </div>
    <div class="col-xs-12 col-md-3" style='text-align:center;'>     
			<div class="input-group">
			  <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon2"></span>
			  <input type="text" class="form-control" style="top:1px;" id='final' placeholder="Fecha Final">
			</div>
		</div>
		<div class="col-xs-12 col-md-2" style='text-align:center;'>     
			<select id='asignadas' class='form-control' onchange='agregar_funcion()'>
				<option value='1'>Temporales</option>
				<option value='2'>Asignadas</option>
				<option value='3'>Canceladas</option>
				<option value='4'>Pagos</option>
			</select>
		</div>
		<div class="col-xs-12 col-md-1" style='text-align:center;'>     
			<button id='buscar' class='btn btn-default' onclick='buscar(0)'>Buscar</button>
		</div>
	</div>
	<div class='row' style='border-top:4px double #eee;margin-top:20px;padding-top:20px;'>
		<section>
			<form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
				<div class="row">
					<div class="col-sm-10 col-md-2 col-md-offset-3">
						<label>Subir factura(s) xml o zip:</label>
						<?php $tip = "Solo se admiten archivos con extension zip o xml, en caso de ser zip estos deben llamarse igual que la carpeta que contiene los xmls."; ?>
					</div>
					<div class="col-sm-2 col-md-1">
						<button class="btn-default btn-block" style="width: auto !important;" data-toggle="tooltip" title="<?php echo $tip; ?>">?</button>
					</div>
					<div class="col-sm-12 col-md-2">
					<div class="form-group">
					    <input type='file' name='factura[]' id='factura' onchange='check_file()'>
					    <p class="help-block">Selecciona el archivo xml o .zip.</p>
					 </div>
					</div>
					<div class="col-sm-12 col-md-1">
						<input type='hidden' name='plz' id='plz' value='<?php echo $numPoliza['id']; ?>'>
						<span id='verif' style='color:green;display:none;'>Verificando...</span>
					</div>
					<div class="col-sm-12 col-md-3">
						<input type='submit' id='buttonFactura' value='Asociar Facturas' class="btn btn-primary btn-block">
					</div>
				</div>
			</form>
		</section>
	</div>
	<div class='row table-responsive' style='margin-top:1em;position:relative;' id='normales'>
		<table id="tabla-data" class="table table-striped table-hover" cellspacing="0" >
				<thead style='background-color:#337ab7;color:white;'>
				<tr>
					<th>Fecha</th>
					<th>RFC</th>
					<th>Emisor</th>
					<th>Receptor</th>
					<th style="width:1em !important;"></th>
					<th>TipoFactura</th>
					<th>FormaPago</th>
					<th>MetodoPago</th>
					<th>Moneda</th>
					<th>Subtotal</th>
					<th>Impuestos IVA</th>
					<th>Total</th>
					<th>Serie</th>
					<th>Folio</th>
					<th>UUID Factura</th>
					<th>Fecha Subida</th>
					<th>Version</th>
					<th>Status</th>
					<th><input type="checkbox" id="checkAll"></th>
					<th class="hide-excel">Domicilio Emisor</th>
					<th class="hide-excel">Domicilio Receptor</th>
				</tr>
			</thead>
			<tbody id='trs'></tbody>
		</table>
	</div>
	<div class='row table-responsive' style='margin-top:1em;position:relative;' id='pagos'>
		<table id="tabla-data-pagos" class="table table-striped table-hover" cellspacing="0" >
				<thead style='background-color:#337ab7;color:white;'>
				<tr>
					<th>UUID Comp.</th>
					<th>Fecha</th>
					<th>RFC</th>
					<th>Emisor</th>
					<th>Receptor</th>
					<th class="small-col" style="width:1em !important;"></th>
					<th>TipoFactura</th>
					<th>Serie</th>
					<th>Folio</th>
					<th>UUID Factura</th>
					<th>FormaPagoDoc</th>
					<th>MonedaDoc</th>
					<th>ImpSaldoAnt.</th>
					<th>ImpSaldoInsoluto</th>
					<th>ImpPagado</th>
					<th>NumParcialidad</th>
					<th>Fecha Subida</th>
				</tr>
			</thead>
			<tbody id='trs_pagos'></tbody>
		</table>
	</div>
	<div class='row' style='margin-top:20px;border-top:4px double #eee;'>
		<div class="col-md-2 col-md-offset-8" style="text-align: center;">
			<h4>Totales:</h4>
		</div>
		<div class="col-md-2" style="text-align: center;">
			<h5 id="totales">$ 0.00</h5>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-6">
			<div class="row">
				<div class="col-md-6" id="contenedor-descargar">
					<!-- <button class="btn btn-info btn-block" onclick="descargarXMLs()">
						Descargar Seleccionados
					</button> -->
				</div>
				<div class="col-md-6" id="contenedor-eliminar">
					<button class="btn btn-danger btn-block" onclick="eliminarSeleccionados()">
						Eliminar Seleccionados
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="hiddenContainer"></div>
<script src="js/almacen.js"></script>