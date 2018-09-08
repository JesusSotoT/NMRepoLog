<?php
	if(!isset($_SESSION))
    	session_start();
?>


<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />

<style type="text/css">
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
        #imprimir,#filtros,#excel,#email_icon, #botones
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
    	border-bottom: 2px solid #ffffff;
	    box-shadow: 0 1px 0 #cdcdcd;
	    color: #07aa9e;
	    font-size: 18px;
	    font-weight: bold;
	    margin: 5px 10px 0 0;
	    padding: 8px 100px 3px 5px;
	    text-shadow: 0 1px 0 #ffffff;
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
</style>

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


<div class="container" style="width:100%" id="reporte">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
            	Reporte de ventas canceladas<br>
            	<section id="botones">
                	<a href="javascript:window.print();"><img border="0" src="../../../../netwarelog/repolog/img/impresora.png"></a>
            	</section>
            </h3>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                	<section>
	                	<h4>Filtros</h4>
	                    <div class="row">
	                        <div class="col-md-3 col-sm-3">
	                            <label>Desde:</label>
	                            <input id="filtro_fecha_inicio" type="text" style="width: 100%;" readonly>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>Hasta:</label>
	                            <input id="filtro_fecha_fin" type="text" style="width: 100%;" readonly>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>Cliente:</label>
	                            <div id="filtro_cliente_div" style="width: 100%;"></div>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>Vendedor:</label>
	                            <div id="filtro_empleado_div" style="width: 100%;"></div>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-3 col-sm-3">
	                            <label>Sucursal:</label>
	                            <div id="filtro_sucursal_div" style="width: 100%;"></div>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>Producto:</label>
	                            <div id="filtro_producto_div" style="width: 100%;"></div>
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>&nbsp;</label>
	                            <input id="btn_limpiar" type="button" value="Limpiar filtros" class="btn btn-primary btnMenu" onclick="limpiaFiltros();">
	                        </div>
	                        <div class="col-md-3 col-sm-3">
	                            <label>&nbsp;</label>
	                            <input id="btn_filtrar" type="button" value="Filtrar" class="btn btn-primary btnMenu" onclick="filtraReporte();">
	                        </div>
	                    </div>
	                </section>
                    <h4>Reporte de ventas canceladas</h4>
                    <div class="row" id="carga" style="display:none;">
                        <div class="col-md-12">
                            <label style="color:green;">Espera un momento...</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                            <div class="table-responsive" id="movimientosmercancia">
                            	<section id="reporte_div">
                            	</section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="reporte">

		
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Agrupar por" style="padding: 20px; width: 90%; text-align: left;">	
			<center>
				<div id="agrupaciones" style='display: table; width: 90%;'>
	    	
		    <!--		<fieldset>
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
					
					</fieldset>	-->
				</div>
			</center>	
		</div>	
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
	
	</div>
</body>
