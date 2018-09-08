<?php
include("funcionesPv.php"); 
?>
<!DOCTYPE HTML>
<html lang="es">
<html>
<head>
		<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		
	    <?php include('../../netwarelog/design/css.php');?>
	    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

		<!--<LINK href="../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" / -->

		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>	
		 <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" />	
		
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

		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="punto_venta.js" ></script>
		<script type="text/javascript" src="../punto_venta/js/ui.datepicker-es-MX.js"></script>

		<script src="../punto_venta/js/select2/select2.min.js"></script>
    	<link rel="stylesheet" type="text/css" href="../punto_venta/js/select2/select2.css" />
		<script>
			$(function(){
			$("#producto").removeClass("nminputselect");	
			$("#movimiento").removeClass("nminputselect");	
			$("#registros").removeClass("nminputselect");
			$("#producto").select2({
          		width : "250px"
         	});
         	$("#movimiento").select2({
          		width : "150px"
         	});
         	$("#registros").select2({
          		width : "70px"
         	});         	
			$("#preloader").hide();
				
			$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fin").datepicker({dateFormat: "yy-mm-dd"});
	$("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#fin').datepicker('setDate', parsedDate);
		$('#fin').datepicker( "option", "minDate", parsedDate);
	}});
				
			});
			
		</script>
</head>	
<body>

<div class="container" style="width:100%">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Movimientos productos<br>
            </h3>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                	<h4>Filtros</h4>
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Desde:</label>
                            <input type="text" id="inicio" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Hasta:</label>
                            <input type="text" id="fin" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Producto:</label>
                            <?php echo productos();?>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Movimiento:</label>
                            <select id="movimiento" class="" >
								<option  value="">-Todos-</option>
								 <option value="Venta">-Venta-</option> 
								 <option value="Compra">-Compra-</option> 
								 <option value="Traspaso">-Traspasos-</option> 
								 <option value="Ingreso inventario">-Ingreso inventario-</option>
								 <option value="Ingreso Producciones">-Ingreso Producciones-</option>
								 <option value="Salida Producciones">-Salida Producciones-</option> 
								 <option value="Se Agrego">-Se Agrego-</option> 
								 <option value="Sustraccion">-Sustraccion-</option> 
							</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <label>Resultados:</label>
                            <select id="registros" class="">
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
                        <div class="col-md-3 col-sm-3">
                        	<label>&nbsp;</label>
                        	<input type="button" value="Buscar" onclick="filtramovimientos();" class="btn btn-primary btnMenu"/>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        	<label>&nbsp;</label>
                        	<input type="button" value="Limpiar" onclick="Limpiacampos();" class="btn btn-primary btnMenu" />
                        </div>
                        <div class="col-md-3 col-sm-3" id="preloader">
                            <label style="color:green;">Espera un momento...</label>
                        </div>
                    </div>
                    <h4>&nbsp;</h4>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva" style="margin-bottom:5em;">
                            <div class="table-responsive" id="movimientosmercancia">
                                <?php echo entradasalidas(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>