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
<LINK href="../../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" />

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
				$("#proveedor").disable();
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
<link href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../../css/imprimir_bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
  	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
  	}
  	.row{
    	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
     	padding: 0.4em;
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
  	@media print{
		#imprimir,#filtros,#excel, #botones, input[type="button"], button, .btnMenu{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
	}
</style>
<body>
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

	<div class="container" style="width:100%" id="formulario_ofertas">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<h3 class="nmwatitles text-center">
					Registro nuevo<br>
					<a href="javascript:window.print();">
				    	<img class="nmwaicons" border="0" src="../../../../netwarelog/design/default/impresora.png">
				    </a>
				    <input type="hidden" id="id_cxp" <?php if(isset($_REQUEST['id'])) echo "value='".$_REQUEST['id']."'"; else echo "value='0'"; ?> >
					<input type="hidden" id="fven" <?php if(isset($_REQUEST['fven'])) echo "value='".$_REQUEST['fven']."'"; else echo "value='0'"; ?> >
				</h3>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-8">
						<input class='throwback btn btn-primary btnMenu' type="button" value="Regresar al listado">
					</div>
				</div>
				<?php
					if(!isset($_REQUEST['id']))
					{
				?>
						<div class="row" id='estado_formulario'>
							<div class="col-sm-12 text-center">
								<h4>Crear nueva cuenta por pagar</h4>
							</div>
						</div>
					<?php
					}
				?>
				<h4>Información de la orden</h4>
				<div class="row">
					<div class="col-sm-5">
						<h4>Fechas</h4>
						<div class="row">
							<div class="col-sm-12">
								<label>Fecha de cargo:*</label>
								<input id="fecha_cargo" class="form-control" type="text" readonly <?php if(!isset($_REQUEST['id'])){ $hoy = getdate(); echo "value='".$hoy['year']."-".$hoy['mon']."-".($hoy['mday']-1)."'";}?>>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<label>Fecha de vencimiento:*</label>
								<input id="fecha_vencimiento" class="form-control" type="text" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-7">
						<h4>Saldos</h4>
						<div class="row">
							<div class="col-sm-12">
								<label>Concepto:*</label>
								<input id="concepto" type="text" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Monto:*</label>
								<input id="monto" type="text" class="numeric form-control">
							</div>
							<div class="col-sm-4">
								<label>Saldo abonado:</label>
								<input id="saldo_abonado" type="text" class="numeric form-control">
							</div>
							<div class="col-sm-4">
								<label>Saldo actual:*</label>
								<input id="saldo_actual" type="text" readonly class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<label>Proveedor:</label>
								<input id="proveedor" type="text" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<?php
					if(isset($_REQUEST['id']))
					{
				?>
						<h4>Agregar pago</h4>
						<div class="row">
							<div class="col-sm-3">
								<label>Fecha de abono:</label>
								<input readonly id="fecha_abono" class="form-control" type="text" <?php $hoy = getdate(); echo "value='".$hoy['year']."-".$hoy['mon']."-".$hoy['mday']."'";?>>
							</div>
							<div class="col-sm-4">
								<label>Forma de pago:</label>
								<div id="formas_pago_div" style="width: 100%;"></div>
								<div id='referencia_div'></div>
							</div>
							<div class="col-sm-2">
								<label>Abono:</label>
								<input id="abono" type="text" class="numeric form-control">
							</div>
							<div class="col-sm-3">
								<label>&nbsp;</label>
								<input type="button" value="Agregar pago" onclick="agregaPago();" class="btn btn-primary btnMenu">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12" id="pre_pagos_div">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12" id="pagos_div">
							</div>
						</div>
				<?php
					}
				?>
				<?php
					if(!isset($_REQUEST['id']))
					{
				?>
						<div class="row" style="margin-bottom:5em;">
							<div class="col-xs-4 col-xs-offset-8">
								<input id="send" type="button" value="Guardar" onClick="crearCuenta()" class="btn btn-primary btnMenu"/>
							</div>
						</div>
				<?php
					}
					else
					{
				?>
						<div class="row" style="margin-bottom:5em;">
							<div class="col-xs-4 col-xs-offset-8">
								<input class="throwback btn btn-primary btnMenu" type="button" value="Volver" />
							</div>
						</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>

</div>
</body>