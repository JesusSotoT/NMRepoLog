<?php include("funcionesPv.php"); 

?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Punto de venta</title>
	<meta charset="utf-8" />
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="punto_venta.css" />
	
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/ui.datepicker-es-MX.js"></script>
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script  type="text/javascript" src="reportes/css/jTPS.js"></script>
    <link rel="stylesheet" type="text/css" href="reportes/css/csstest.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" />
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
			// $(function(){
// 			
		// $("#preloader").hide();
// 				
			// $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	// $("#fin").datepicker({dateFormat: "yy-mm-dd"});
	// $("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  // var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		// $('#fin').datepicker('setDate', parsedDate);
		// $('#fin').datepicker( "option", "minDate", parsedDate);
	// }});
// 				
			// });
			$(document).ready(function() {
					$("#preloader").hide();
	 $.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	 $("#inicio").datepicker({
	 	maxDate: 0,
	 	dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fin").datepicker("option","minDate", selected)
        }
    });
    
    $("#fin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        maxDate:0,
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#inicio").datepicker("option","maxDate", selected)
        }
    });

  });	
 
			
		</script>
	<style type="text/css">
		.btnMenu{
            border-radius: 0; 
            width: 100%;
            margin-bottom: 1em;
            margin-top: 1em;
        }
        .row
        {
            margin-top: 1em !important;
        }
        .select2-container{
            width: 100% !important;
        }
	</style>
</head>
<body>

	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="nmwatitles text-center">
                 	Registro de ventas
                </h3>
            </div>
        </div>
        <h3>Filtros</h3>
        <section>
        	<div class="row">
	            <div class="col-md-4">
	            	<label>Desde:</label>
	            	<input type="text" id="inicio" readonly="readonly" value="<?php echo DATE('Y-m-d'); ?>" class="form-control">
	            </div>
	            <div class="col-md-4">
	            	<label>Hasta:</label>
	            	<input type="text" id="fin" readonly="readonly" value="<?php echo DATE('Y-m-d'); ?>" class="form-control">
	            </div>
	            <div class="col-md-4">
	            	<label>Cliente:</label>
	            	<?php echo clientes(); ?>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-3">
	            	<label>Vendedor:</label>
	            	<?php echo vendedores(); ?>
	            </div>
	            <div class="col-md-3">
	            	<label>Estatus:</label>
	            	<select id="estatus" class="form-control">
	            		<option value="">-Todos-</option>
	            		<option value="1">Activa</option>
	            		<option value="0">Cancelada</option>
	            	</select>
	            </div>
	            <div class="col-md-3">
	            	<?php
						//si es simple//
						if(simple()){	  
				  		//end si es simple//	
					?>
							<label>Sucursal:</label>
					<?php 	echo sucursales(); 
					?>
					<?php
						//si es simple//
						}  
				  		//end si es simple//	
					?>
	            </div>
	            <div class="col-md-3" style="display:none;">
	            	<select id="registros" class="form-control">
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
						<option value="50">50</option>
						<option value="60">60</option>
						<option value="70">70</option>
						<option value="80">80</option>
						<option value="90">90</option>
						<option value="100">100</option>
					</select>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-4">
	            	<button onclick="filtraventas();" class="col-md-4 btn btn-primary btnMenu">Buscar</button>
	            </div>
	            <div class="col-md-4">
	            	<button onclick="Limpiaventas();" class="col-md-4 btn btn-primary btnMenu">Limpiar</button>
	            </div>
	            <div class="col-md-4" style="text-align: center;">
	            	<img src="img/preloader.gif" id="preloader">
	            </div>
	        </div>
        </section>
        <section>
        	<div class="row">
        		<div class="col-md-12">
        			<div class="table-responsive" id="ventas">
        				<?php echo ventas(); ?>
        			</div>
        		</div>
        	</div>
        </section>
    </div>
	
</body>
<div id="caja-dialog-confirmacion" ></div>
<div id="caja-dialog"></div>
<div id="caja-dialog-devolucion"></div>

 	<script>
    	carga();
    </script>
</html>	