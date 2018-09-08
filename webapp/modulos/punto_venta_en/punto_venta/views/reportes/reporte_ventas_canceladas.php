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

<script type="text/javascript" src="../../../punto_venta/js/reporte_ventas_canceladas.js"></script>
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


<body>
<div id="reporte">

	<div>
		<div class="tipo">
		    <a href="javascript:window.print();">
		    <img border="0" src="../../../../netwarelog/repolog/img/impresora.png">
		    </a>
		    <b>Reporte de ventas canceladas</b>
		    </div>
		<br>
	</div>

		<center>	
		<div class="campos" style='width: 90%;'>	
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Filtros" style="padding: 20px; width: 90%; text-align: left;">	
			<center>
				<div id="filtros" style='display: table; width: 90%;'>
	    	
		    		<fieldset>
					<legend>Filtros</legend>
			
					<div style="display: table; width: 100%; margin-bottom: 10px; text-align: left;">
						<div title="Inicio"  	style="display: table-cell; padding-right: 10px; 						width: 20%">
							<b><label>Desde: </label></b><br> 	<input id="filtro_fecha_inicio" type="text" style="width: 100%;" readonly>
						</div>
						<div title="Fin"     	style="display: table-cell; padding-left: 10px; padding-right: 10px; 	width: 20%; border-right: 1px solid #CCCCCC;">
							<b><label>Hasta: </label></b>		<br>	<input id="filtro_fecha_fin" type="text" style="width: 100%;" readonly>
						</div>
						<div title="Cliente" 	style="display: table-cell; padding-left: 10px; 						width: 50%;">
							<b><label>Cliente: </label></b>		<br>	<div id="filtro_cliente_div" style="width: 100%;"></div>
						</div>	
					</div>
					
					<div style="display: table; width: 100%; margin-bottom: 10px; text-align: left;">
						<div title="Vendedor"	style="display: table-cell; padding-right: 10px;						width: 33%; border-right: 1px solid #CCCCCC;">
							<b><label>Vendedor: </label></b>		<br>	<div id="filtro_empleado_div" style="width: 100%;"></div>
						</div>
						<div id='filtro_sucursal' title="Sucursal"   style="display: table-cell; padding-left: 10px; padding-right: 10px;	width: 33%; border-right: 1px solid #CCCCCC;" id="filtro_sucursal">
							<b><label>Sucursal: </label></b>		<br>	<div id="filtro_sucursal_div" style="width: 100%;"></div>
						</div>
						<div title="Producto"   style="display: table-cell; padding-left: 10px; 					 	width: 34%; ">
							<b><label>Producto: </label></b>		<br>	<div id="filtro_producto_div" style="width: 100%;"></div>
						</div>
					</div>
					
					<div style="display: table; width: 100%; text-align: right">
						
							<input id="btn_limpiar" type="button" value="Limpiar filtros" 	style="background-color: #FFBBAA;" onclick="limpiaFiltros();">
						
							<input id="btn_filtrar" type="button" value="          Filtrar          " 			style="background-color: #A9F5D0;" onclick="filtraReporte();">
						
					</div>
					</fieldset>	
				</div>
			</center>	
		</div>			
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<center>
		 	<div class="listadofila" title="Reporte" 	style="display: table; padding: 20px; padding-bottom: 15px; width: 95%; text-align: left; border-top: 1px solid #FF0040; border-bottom: 1px solid #FF0040;">	
				
				<div title="Reporte"     	style="display: table; width: 100%; padding-right: 10px; ">
					<h3>Reporte de ventas canceladas: </h3>	
					<div id="reporte_div"		style="width: 100%;"></div>
				</div>
				
			</div>
		</center>
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Agrupar por" style="padding: 20px; width: 90%; text-align: left;">	
			<center>
				<div id="agrupaciones" style='display: table; width: 90%;'>
	    	
		    		<fieldset>
					<legend>Agrupar por:</legend>
			
					<div style="display: table; width: 100%; margin-bottom: 10px; text-align: left;">
						<div title="Agrupar por vendedor"  	style="display: table-cell; padding-right: 10px; 						width: 25%; border-right: 1px solid #CCCCCC;">
							<input id='rad_vendedor' type="radio" name="agrupar" value="vendedor" onclick="agrupa();"> Vendedor
						</div>
						<div title="Agrupar por cliente"     	style="display: table-cell; padding-left: 10px; padding-right: 10px;	width: 25%; border-right: 1px solid #CCCCCC;">
							<input id='rad_cliente' type="radio" name="agrupar" value="cliente" onclick="agrupa();"> Cliente
						</div>
						<div title="Agrupar por sucursal" 	style="display: table-cell; padding-left: 10px; 						width: 25%; border-right: 1px solid #CCCCCC;" id='agrupamiento_sucursal'>
							<input id='rad_sucursal' type="radio" name="agrupar" value="sucursal" onclick="agrupa();"> Sucursal
						</div>	
						<div title="Agrupar por producto" 	style="display: table-cell; padding-left: 10px; 						width: 25%; ">
							<input id='rad_producto' type="radio" name="agrupar" value="producto" onclick="agrupa();"> Producto
						</div>	
					</div>
					
					</fieldset>	
				</div>
			</center>	
		</div>	
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
	
	</div>
	</center>


</div>
</body>
