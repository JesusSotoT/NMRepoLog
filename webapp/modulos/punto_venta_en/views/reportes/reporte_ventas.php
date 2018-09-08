<?php
	if(!isset($_SESSION))
    	session_start();
?>


<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>
<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>

<script type="text/javascript" src="../../../punto_venta/js/reporte_ventas.js"></script>
<script type="text/javasctipt" src="../../../punto_venta/js/paginaciongrid.js"></script>
	 
<!--Script del autocompletado del campo de productos !-->
<script>

	$.fn.disable = function() {
    return this.each(function() {          
      if (typeof this.disabled != "undefined") {
        $(this).data('jquery.disabled', this.disabled);

	        this.disabled = true;
	      }
	    });
	};
	
	$.fn.enable = function() {
	    return this.each(function() {
	      if (typeof this.disabled != "undefined") {
	        this.disabled = $(this).data('jquery.disabled');
	      }
	    });
	};	
		
$(document).ready(function() {

    $(".numeric").numeric({allow:"."});
    
    $(".throwback").click(function(){
    	window.location="../caja/listado_cortes.php";
    });
    
    $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	 $("#filtro_fecha_inicio").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#filtro_fecha_fin").datepicker("option","minDate", selected)
        }
    });
    
    $("#filtro_fecha_fin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:0,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#filtro_fecha_inicio").datepicker("option","maxDate", selected)
        }
    });

});
</script>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
<?php date_default_timezone_set("Mexico/General"); $hoy = getdate();?>

<link rel="stylesheet" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="../../css/imprimir_bootstrap.css" type="text/css">
<style>

	.tit_tabla_buscar td
	{
		font-size:medium;
	}

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

	@media print
	{
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		#logo_empresa
		{
			display:block;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
	}
	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
  	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
	.table tr, .table td{
		border: none !important;
	}
</style>

<body>


	<div class="container" style="width:100%">
		<div class="row">
			<div class="col-md-12">
				<h3 class="nmwatitles text-center">
					Reporte de ventas<br>
					<section id="botones">
						<a href="javascript:window.print();"><img border="0" src="../../../../netwarelog/repolog/img/impresora.png"></a>		
					</section>
				</h3>
				<div class="row">
					<div class="col-md-1">
					</div>
					<div class="col-md-10" id="imp_cont">
						<section id="filtros">
							<div class="row">
								<div class="col-md-3">
									<label>Desde:</label>
									<input id="filtro_fecha_inicio" type="text" class="form-control" readonly>
								</div>
								<div class="col-md-3">
									<label>Hasta:</label>
									<input id="filtro_fecha_fin" type="text" class="form-control" readonly>
								</div>
								<div class="col-md-3">
									<label>Cliente:</label>
									<section id="filtro_cliente_div">
									</section>
								</div>
								<div class="col-md-3">
									<label>Vendedor:</label>
									<section id="filtro_empleado_div">
									</section>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label>Sucursal:</label>
									<section id="filtro_sucursal_div">
									</section>
								</div>
								<div class="col-md-3">
									<label>Producto:</label>
									<section id="filtro_producto_div">
									</section>
								</div>
								<div class="col-md-2">
									<input id="btn_limpiar" type="button" value="Limpiar filtros" class="btn btn-primary btnMenu" onclick="limpiaFiltros();">
								</div>
								<div class="col-md-2">
									<input id="btn_filtrar" type="button" value="Filtrar" class="btn btn-primary btnMenu" onclick="filtraReporte();">
								</div>
							</div>
						</section>
						<section>
							<div class="row" id="carga" style="display:none;">
								<div class="col-md-12">
									<label style="color:green;">Espere un momento...</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<div id="reporte_div" style="width: 100%;">
										</div>
									</div>
								</div>
							</div>
							<div class="row" id="agrupaciones" style="display:table;">
								<div class="col-md-12">

								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
