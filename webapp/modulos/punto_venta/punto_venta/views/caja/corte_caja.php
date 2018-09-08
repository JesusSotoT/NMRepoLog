<?php
	if(!isset($_SESSION))
    	session_start();
?>


<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.numeric.js"></script>
<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>

<script type="text/javascript" src="../../../punto_venta/js/corte_caja.js"></script>
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

    $(".numeric").numeric({ precision: 12, scale: 2 });
    
    $(".throwback").click(function(){
    	window.location="../caja/listado_cortes.php";
    });

});
</script>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
<?php date_default_timezone_set("Mexico/General"); $hoy = getdate();?>


<body>
<div id="formulario">
	
	<input id='id_corte' 
	<?php
	if(isset($_REQUEST['id']))
	{
		echo "value='".$_REQUEST['id']."' ";
	}
	else 
	{
		echo "value='NULL' ";
	}
	?>
	type='hidden'>
	<div id="registro_nuevo">
		    <div class="tipo">
		    <a href="javascript:window.print();">
		    <img border="0" src="../../../../netwarelog/repolog/img/impresora.png">
		    </a>
		    <b>Corte de caja</b>
		    </div>
	    <br>
	</div>

		<center>	
		<div class="campos" style='width: 90%;'>	
			
		<div style="width: 90%; text-align: right"><input class='throwback' type="button" value="Regresar al listado"></div>
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Filtros" 			style="padding: 10px; width: 90%; text-align: left;">	
			<div style="display: table; width: 350px;">
				
				<div id="notifica_fecha_div" style='height: 15px;'></div>
				
				<div>
					<?php
					if(isset($_REQUEST['f_ini']) && isset($_REQUEST['f_fin']))
					{
					?>
						<div title="Inicio"  style="display: table-cell; width: 50%; padding: 10px; ">
							<b><label>Desde: </label></b><br> 	<input id="fecha_inicio" type="text" style="width: 100%;" readonly value='<?php echo $_REQUEST['f_ini'];?>'>
						</div>
						<div title="Fin"     style="display: table-cell; width: 50%; padding: 10px; ">
							<b><label>Hasta: </label></b>		<br>	<input id="fecha_fin"  	 type="text" style="width: 100%;" readonly value='<?php echo $_REQUEST['f_fin'];?>'>
						</div>
					<?php
					}
					else
					{
					?>
						<div title="Inicio"  style="display: table-cell; width: 50%; padding: 10px; ">
							<b><label>Filtrar desde: </label></b><br> 	<input id="fecha_inicio" type="text" style="width: 100%;" readonly>
						</div>
						<div title="Fin"     style="display: table-cell; width: 50%; padding: 10px; ">
							<b><label>Hasta: </label></b>		<br>	<input id="fecha_fin"  	 type="text" style="width: 100%;" readonly value='<?php echo $hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];?>'>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>		
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<center>
		 	<div class="listadofila" title="Corte de caja" 	style="display: table; padding: 10px; padding-bottom: 15px; width: 95%; text-align: left; border-top: 1px solid #006efe; border-bottom: 1px solid #006efe;">	
				
				<div id='aviso_canceladas'>
				</div>
				
				<div title="Pagos"     	style="display: table; width: 100%; padding-right: 10px; ">
					<h3>Pagos: </h3>	
					<div id="pagos_div"		style="width: 100%;"></div>
				</div>
				
				<div title="Productos"	style="display: table; width: 100%; padding-right: 10px; ">
					<h3>Productos: </h3>
					<span id="productos_div" style="width: 100%;"></span>
				</div>
				
			</div>
		</center>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Resumen"  style="padding: 10px; width: 80%; text-align: left;" id="saldos_div">	
				<p>
				<div style="width:70%; display: table;">
					
					<div style="display: table-cell; width: 50%">
						<div title="Saldo inicial de caja"  		style="width: 80%;">
							<label>Saldo inicial de caja: </label>	<br>			$<input id="saldo_inicial" 	type="text" class="numeric" style="width: 95%;"		 maxlength="10" readonly>
						</div> <p>
						<div title="Monto de ventas en el periodo"  style="width: 80%;">
							<label>Monto de ventas en el periodo: </label> <br>		$<input id="monto_ventas" 	type="text" class="numeric" style="width: 95%;" maxlength="10" readonly>
						</div> <p>
						<div title="Saldo disponible en caja"  		style="width: 80%;">
							<label>Saldo disponible en caja: </label>	<br>		$<input id="saldo_disponible" 	type="text" class="numeric" style="width: 95%;" maxlength="10" readonly>
						</div>
					</div>
					
					<div style="display: table-cell; width: 50%">
						<div title="Retiro de caja"  	style="width: 80%;">
							<label>Retiro de caja: </label>		 <br>				$<input id="retiro_caja" 	type="text" class="numeric" style="width: 95%; background-color: #FFCCDD;" maxlength="10">
						</div> <p>
						<div title="Deposito de caja"	style="width: 80%;">
							<label>Depósito de caja: </label>	 <br>				$<input id="deposito_caja"	type="text" class="numeric" style="width: 95%; background-color: #A9F5A9;" maxlength="10">
						</div>
					</div>
					
				</div>
				
		</div>		

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
		
		<div id='alerta_guardar' style='width: 80%;'></div>
		<div style="text-align: right; width: 80%" id='btn_guardar'>
			<input id="send" type="button" value="Guardar" onClick="guardarCorte()" />
		</div>
		<br><br><br><br><br>
	
	</div>
	</center>


</div>
</body>
