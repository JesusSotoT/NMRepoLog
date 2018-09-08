<?php
	if(!isset($_SESSION))
    	session_start();
	if(isset($_REQUEST['clrssn']))
	{
		if($_REQUEST['clrssn'] == 1)
		{
			if(isset($_SESSION["abono_array"]))
				unset($_SESSION["abono_array"]);
			if(isset($_SESSION["fecha_abono_array"]))
				unset($_SESSION["fecha_abono_array"]);
			if(isset($_SESSION["id_forma_pago_array"]))
				unset($_SESSION["id_forma_pago_array"]);
			if(isset($_SESSION["forma_pago_array"]))
				unset($_SESSION["forma_pago_array"]);
			if(isset($_SESSION["referencia_array"]))
				unset($_SESSION["referencia_array"]);
		}
	}
?>


<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.numeric.js"></script>
<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>

<script type="text/javascript" src="../../../punto_venta/js/cxp.js"></script>
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
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	
	 $("#fecha_cargo").datepicker({
	 	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fecha_vencimiento").datepicker("option","minDate", selected)
        }
    });
    
    $("#fecha_vencimiento").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate:"+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fecha_cargo").datepicker("option","maxDate", selected)
        }
    });

    $(".throwback").click(function(){
    	window.location="../cxp/listado_cxp.php";
    });
    
   $(".numeric").numeric({ precision: 12, scale: 2 });
    
    //Monto
	$("#monto").keypress(function(){ $("#saldo_actual").val($("#monto").val() - $("#saldo_abonado").val()); });
	$("#monto").keyup(function(){ $("#saldo_actual").val($("#monto").val() - $("#saldo_abonado").val()); });
	
	//Abono
	$("#saldo_abonado").keypress(function(){ $("#saldo_actual").val($("#monto").val() - $("#saldo_abonado").val()); });
	$("#saldo_abonado").keyup(function(){ $("#saldo_actual").val($("#monto").val() - $("#saldo_abonado").val()); });
    
    <?php
			if(isset($_REQUEST['id']))
			{
	?>
				$("#fecha_cargo").disable();
				$("#fecha_vencimiento").disable();
				$("#concepto").disable();
				$("#monto").disable();
				$("#saldo_abonado").disable();
				$("#saldo_actual").disable();
	<?php
			}
			else
			{
	?>				
				//Saldo abonado
				$("#saldo_abonado").val(0);
				
				//Saldo actual
				$("#saldo_actual").disable();
				$("#saldo_actual").val($("#monto").val() - $("#saldo_abonado").val());		
    <?php
			}
	?>
    
});
</script>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

<body>
<div id="formulario_ofertas">
	
	<?php
		if(isset($_SESSION["abono_array"]))
		{
			$fecha_abono = $_SESSION["fecha_abono_array"];
			$abono = $_SESSION["abono_array"];
			$id_forma_pago = $_SESSION["id_forma_pago_array"];
			$forma_pago = $_SESSION['forma_pago_array'];
			$referencia = $_SESSION["referencia_array"];
					
			for($i=0;$i<count($_SESSION["abono_array"]);$i++)
			{
				if(isset($_REQUEST["chk".$i]))
				{
					unset($fecha_abono[$i]);
					unset($abono[$i]);
					unset($id_forma_pago[$i]);
					unset($referencia[$i]);
					unset($forma_pago[$i]);
					
				}
			}
			$_SESSION["fecha_abono_array"] = array_values($fecha_abono);
			$_SESSION["abono_array"] = array_values($abono); 
			$_SESSION["id_forma_pago_array"] = array_values($id_forma_pago);
			$_SESSION["forma_pago_array"] = array_values($forma_pago);
			$_SESSION["referencia_array"] = array_values($referencia);
			
			echo "<input type='hidden' id='carga_pre_pagos' value='1'>";
		}
		else
		{
			echo "<input type='hidden' id='carga_pre_pagos' value='0'>";
		}
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
		
		<input type="hidden" id="id_cxp" 	<?php
												if(isset($_REQUEST['id']))
													echo "value='".$_REQUEST['id']."'";
												else
													echo "value='0'";
											?> >
											
		<input type="hidden" id="fven" 		<?php
												if(isset($_REQUEST['fven']))
													echo "value='".$_REQUEST['fven']."'";
												else
													echo "value='0'";
											?> >
			
		<div style="width: 80%;">
			<div style="width: 90%; text-align: right"><input class='throwback' type="button" value="Regresar al listado"></div>
		</div>
		
		
		<center>
			<div style="padding-right: 10px; padding-left: 10px; color: #006efe" id='estado_formulario'>
		<?php
			if(!isset($_REQUEST['id']))
			{
		?>
		
			<b>Crear nueva cuenta por pagar</b><p>
			
		<?php
			}
		?>
		</div>
		</center>

		<div class="campos" style='width: 90%;'>		
	
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

			<center>
				<div class="listadofila" 						style="display: table; padding: 10px; width: 70%; text-align: left;">	
					<div title="Fecha"  style="display: table-cell; width: 30%; padding: 10px; border-right: 1px solid #DDDDDD;">
						<div><label>Fecha de cargo*</label><br> 			<input id="fecha_cargo" 	   	type="text" style="width: 100%;" readonly <?php if(!isset($_REQUEST['id'])){ $hoy = getdate(); echo "value='".$hoy['year']."-".$hoy['mon']."-".($hoy['mday']-1)."'";}?>></div>
						<div><label>Fecha de vencimiento*</label><br>	<input id="fecha_vencimiento"  	type="text" style="width: 100%;" readonly></div>
					</div>
					
					<div title="Saldos" style="display: table-cell; width: 70%; padding: 10px;">
						<div><label>Concepto*</label><br> 				<input id="concepto" 	  		type="text" style="width: 100%;">					</div>
						<div style="display: table; width: 100%;">
							<div style="display: table-cell;"><label>Monto*</label><br>			<input id="monto"  			type="text" class="numeric">	</div>
							<div style="display: table-cell;"><label>Saldo abonado</label><br>  <input id="saldo_abonado" 	type="text" class="numeric">	</div>
							<div style="display: table-cell;"><label>Saldo actual*</label><br>	<input id="saldo_actual"  	type="text" readonly>			</div>
						</div>
					</div>
				</div>
			</center>
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
			<?php
				if(isset($_REQUEST['id']))
				{
			?>
			<center>
			 	<div class="listadofila" title="Agregar pago" 	style="display: table; padding: 10px; width: 70%; text-align: left; border-top: 1px solid #006efe;">	
					<center>
						<div style="padding-right: 10px; padding-left: 10px;">
						<b>Agregar pago</b><p>
						</div>
					</center>
					
					<div style="padding-right: 10px; padding-left: 10px; width: 100%;">
						<div style="width: 20%; display: table-cell; padding-right: 10px;"><label>Fecha de abono</label><br> 	<input style="width: 100%;" readonly id="fecha_abono" 	type="text" <?php $hoy = getdate(); echo "value='".$hoy['year']."-".$hoy['mon']."-".$hoy['mday']."'";?>></div>
						<div style="width: 40%; display: table-cell; padding-right: 10px;">
							
								<div>
									<label>Forma de pago</label>	<br> 	<div id="formas_pago_div" style="width: 100%;"></div>	
								</div>
								<div>
									<div id='referencia_div'></div>
								</div>			
						</div>
						<div style="width: 20%; display: table-cell; padding-right: 10px;"><label>Abono</label>			<br>	<input id="abono"  			type="text" 	class="numeric">				</div>
						
						<div style="width: 20%; display: table-cell; padding-right: 20px;"><br><input type="button" value="Agregar pago" onclick="agregaPago();" style="width: 100%">	</div>
					</div>
					
				</div>
			</center>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

			<center>
				<div id="pre_pagos_div" class="listadofila" title="Pagos a agregar" style=" width: 70%; padding-bottom: 10px;"></div>
				<div id="pagos_div" class="listadofila" title="Pagos" style=" width: 70%; padding-bottom: 10px;"></div>
			</center>
			<?php
				}
			?>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

	<?php
		if(!isset($_REQUEST['id']))
		{
	?>
		<div style="text-align: right; width: 80%">
			<input id="send" type="button" value="Guardar" onClick="crearCuenta()" />
		</div>
	<?php
		}
		else
		{
	?>
		<div style="text-align: right; width: 80%">
			<input class="throwback" type="button" value="Volver" />
		</div>
	<?php
		}
	?>
	
	</div>
	</center>


</div>
</body>