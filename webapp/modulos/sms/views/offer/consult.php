<?php
//include_once("../../../../netwarelog/catalog/conexionbd.php");
if(!isset($_SESSION))
     session_start();

error_reporting(E_ALL);

?>

<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../css/popup.css" 				   		   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../sms/js/jquery.alphanumeric.js"></script>
<script src="../../../sms/js/ui.datepicker-es-MX.js"></script>

<script type="text/javascript" src="../../../sms/js/consult.js"></script>

<link rel="stylesheet" href="../../../sms/js/countdown/jquery.countdown.css">
<style type="text/css">
#defaultCountdown { width: 240px; height: 45px; }
</style>
<script type="text/javascript" src="../../../sms/js/countdown/jquery.countdown.js"></script>

<script type="text/javascript">
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

$(function () {
	var fexp = $("#fexp").val();
	var arrfexp = fexp.split("-");
	
	var month = arrfexp[1]-1;
	var day = arrfexp[2];
	var year = arrfexp[0];
	
	var austDay = new Date();   
    austDay = new Date( year, month, day );
    $('#defaultCountdown').countdown({until: austDay});
    
    $("#throwback").click(function(){
    	window.location="../offer/listado_ofertas.php";
    });
    
	var x = new Date();
	x.setFullYear(year,month,day);
	var today = new Date();
	
	if (x>today)
	{
		//alert("Hoy es antes de "+day+" de "+(month+1)+" de "+year);
	}
	else
	{
		$('#formulario_ofertas *').disable();
		$('#throwback').enable();
		$('#estatus').html("Esta oferta ya terminó");
		 $('#formulario_ofertas').css('opacity', '.6');
	}
	
	$('#preloader_preview').hide();
	
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
	
	$("#fecha_hoy").val(today);
    
});
</script>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

<body>
<?php 
	if (isset($_REQUEST['id']))
		if(is_numeric($_REQUEST['id']))
	  		echo "<input type='hidden' id='idOferta' value=".$_REQUEST['id'].">";

	if(isset($_REQUEST['fexp']))
		echo "<input type='hidden' value=".$_REQUEST['fexp']." id='fexp'>";
?>
	
	<div id="registro_nuevo">
		    <div class="tipo">
		    <a href="javascript:window.print();">
		    <img border="0" src="../../../../netwarelog/repolog/img/impresora.png">
		    </a>
		    <b>Registro nuevo</b>
		    </div>
	    <br>
	</div>
	
	<center>
		<div style="width: 70%; display: table">	
		<B><div style="width: 80%; margin-bottom: 10px; color: #FF0000; text-align: center; font-size: 14px;" id="estatus"></div></B>
		<div style="width: 100%; text-align: right; margin-bottom: 20px;"><input id='throwback' type="button" value="Regresar al listado"></div>
		</div>
	</center>
	
<div id="formulario_ofertas">
	
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
	
	<center>	
		
	<div style="width: 70%; display: table">	
		
		<div>
			
			<div class="listadofila" title="Fechas de oferta" style="width: 70%; text-align: left; vertical-align: middle; padding: 10px; border-bottom: 1px solid #BBBBBB; font-size: 14px; display: table-cell;">
				<div style="text-align: left;">
					<div style='display: table-cell'>Oferta número:&nbsp;</div>  <div style='display: table-cell; color: #006efe;'><?php echo $_REQUEST['id']; ?></div>
					<input type='hidden' id='oferta_id' value= <?php echo $_REQUEST['id']; ?>>
				</div>
				<div style="text-align: left;">
					<div style='display: table-cell'>Sucursal:&nbsp;</div>  <div style='display: table-cell; color: #006efe;'> <div id="sucursal"></div></div>
				</div>
				<div style="text-align: left;">
					<div style='display: table-cell'>Responsable:&nbsp;</div>  <div style='display: table-cell; color: #006efe;'> <div id="usuario_genera"></div></div>
				</div>
			</div>
			
			<div class="listadofila" title="Fechas de oferta" style="width: 30%; text-align: right; vertical-align: middle; padding: 10px; border-bottom: 1px solid #BBBBBB; font-size: 14px; display: table-cell;">
				    <div style="text-align: right;">
				    	<div style='display: table-cell; text-align: right;'>Esta oferta termina en:&nbsp;</div>
						<div id="defaultCountdown" style="text-align: right;"> </div>
				    </div>
			    
			</div>
			
		</div>
		<input type='hidden' id='sucursal_id'>
	
	</div>
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->	
		
	<div style="width: 70%; display: table; padding-top: 10px">
	
	
		<div class="listadofila" title="Fechas de oferta" style="text-align: left; display: table-cell; vertical-align: middle; width: 25%; padding: 10px; border-right: 1px solid #BBBBBB;">
			
				<label>Inicio de la oferta: </label>
			    <br>
			    <input readonly id="fecha_inicio" type="text" style="width: 100%;" />
				<p>
			    <label>Expiracion de la oferta: </label>
			    <br>
			    <input readonly id="fecha_fin" type="text" style="width: 100%;"/>
			    <input type='hidden' id='fecha_hoy'>
		    
		</div>	
	
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
	
		<div class="listadofila" title="Producto" 	  	  style="text-align: left; display: table-cell; vertical-align: middle; width: 75%; padding: 10px">
			
			<div title="cantidad"    style="display: table-cell; vertical-align: middle; width: 20%; text-align: left; padding-right: 10px">
				<div>
				    <label id="lbl357">Cantidad: </label>
				    <font color="silver">*</font>
				    <br>
				    <input class="positive" type="text" id="cantidad" style="width: 100%"/>
			    </div>
			</div>
			
			
			<div title="unidad/precio" style="display: table-cell; vertical-align: middle; width: 80%; text-align: left;">
				<div>
					<label id="lbl357">Producto a ofertar: </label><br>
					<input type="text" id="producto" style="width: 100%"/>
					<input type="hidden" id="producto_id" style="width: 100%"/>
				</div>
				<p>
				<div style="width: 100%; display: table;">
					<div style="display: table-cell; vertical-align: middle; width: 70%; text-align: left; padding-right: 10px;">
						    <label id="lbl357">Unidad: </label><br>
							<input type="text" id="unidad" style="width: 100%"/>
							<input id="unidad_id" type="hidden">
					</div>
					
					<div style="display: table-cell; vertical-align: middle; width: 30%; text-align: right;">
						    <label id="lbl357">Precio: </label><br>
							<input type="text" id="precio" style="width: 100%; text-align: right;" />
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

	<div style="width: 70%; display: table; padding-top: 20px;">
		
		<div class="listadofila" title="Clientes" 	  	  style="display: table; vertical-align: middle; padding: 10px">
				<div>
				    <label id="lbl357"><b>Clientes que recibieron la oferta: </b></label>
				    <p>
				    <center>
				    	<div id="clientes"></div>
				    </center>
			    </div>
		</div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Producto" 	  	  style="display: table; vertical-align: middle; padding: 10px">
				<div>
				    <p>
				    <center>
				    	<img class='preloader' id='preloader_preview' src='../../images/preloader.gif'>
				    	<p>
				    	<div id="producto_preview"></div>
				    </center>
			    </div>
		</div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

	</div>
	</center>


</div>
</body>
<p><p><p>