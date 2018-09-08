<script src="js/jquery-1.10.2.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src='js/select2/select2.min.js'></script>
<style>
	.nminputbutton_color2
	{
		width:250px;
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
</style>
<script language='javascript'>
$(function()
 {
 guardando(0);
var anterior = $('#anterior_corte').val();
if(parseInt(anterior))
{
	$("#corte option[value='"+anterior+"']").attr("selected","selected");  
}
		// Inicia seccion de asignacion de valores iniciales
			/*jshint ignore:start*/
				<?php
					$datos  = ( $data['clientes'] 	!= -1) ? "$( '#clientes'     ).val( " . $data['clientes'] . ");" : "";
					$datos .= ( $data['ventas']  	!= -1) ? "$( '#ventas'       ).val( " . $data['ventas']   . ");" : "";
					$datos .= ( $data['iva']  		!= -1) ? "$( '#IVA'          ).val( " . $data['iva']      . ");" : "";
					$datos .= ( $data['caja']  		!= -1) ? "$( '#caja'         ).val( " . $data['caja']     . ");" : "";
					$datos .= ( $data['bancos']  	!= -1) ? "$( '#bancos'       ).val( " . $data['bancos']   . ");" : "";

					echo $datos;
				?>
			/* jshint ignore:end*/
		// Termina seccion de asignacion de valores iniciales

		// Inicia seccion de control de botones de modificacion
			$("select.s2").each(function(){
				if( parseInt( $(this).val(),10 ) !== -1 )
				{
					$(this).attr("disabled",'disabled').after("<input type='hidden' name='" + $(this).attr("name") + "' value='" + $(this).val() + "'>");
					$(":button[data-id=" + $(this).attr("id") + "]").remove();
				}
				else
				{
					$("#ventas,#clientes,#IVA,#caja,#bancos").select2({"width":"300px"});
					$("#hide"+$(this).attr("id")).hide();
				}
			});
		// Termina seccion de control de botones de modificacion

		// Inicia seccion de boton de modificacion de cuentas
			$("#showVentas,#showClientes,#showIva,#showCaja,#showBancos").click(function(){
				if(confirm("Esta seguro de asignar la cuenta de " + $(this).data("id") + "?.\nEsta accion no podra ser modificada en ningun momento."))
				{
					$("#hide" + $(this).data("id") ).show();
					$( "#" + $(this).data('id') + " option[value=-1]" ).prop("disabled",true);
					$( "#" + $(this).data('id') ).select2({"width": "300px"});
					$(this).hide();
				}
			});
		// Termina seccion de boton de modificacion de cuentas

		// Inicia seccion de cancelacion de modificacion de cuentas
			$("#cancelventas,#cancelclientes,#cancelIVA,#cancelcaja,#cancelbancos").click(function(){
				var type = ucFirst($(this).data("id"));
				$("#show" + type).show();
				$("#hide" + $(this).data("id") ).hide();
				$("#" + $(this).data('id') + " option[value=-1]").prop("disabled",false);
				$("#" + $(this).data('id') ).select2('destroy').select2({'width':'300px'});
				$('#' + $(this).data('id') ).val('-1');
			});
		// Termina seccion de cancelacion de modificacion de cuentas

		// Inicia Validacion de desigualdad
			$("#ventas,#clientes,#IVA,#caja,#bancos").on('change',function(){
				var ventas  = $("#ventas").val();
				var IVA  = $("#IVA").val();
				var caja  = $("#caja").val();
				var bancos = $("#bancos").val();
				var clientes = $("#clientes").val();
				var id = $(this).attr('id');
				
				switch(id)
				{
					case "ventas":
						if(ventas == clientes || ventas == IVA || ventas == caja || ventas == bancos)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "IVA":
						if(IVA == clientes  || IVA == ventas || IVA == caja || IVA == bancos)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "clientes":
						if(clientes == ventas || clientes == IVA || clientes == caja || clientes == bancos)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;	

					case "caja":
						if(caja == ventas || caja == IVA || caja == bancos || caja == clientes )
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;		
						
					case "bancos":
						if(bancos == ventas || bancos == IVA || bancos == caja || bancos == clientes )
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;				
				}
			});
		// Termina Validacion de desigualdad		
	});
	function ucFirst(string) {
		return string.substring(0, 1).toUpperCase() + string.substring(1).toLowerCase();
    }

function valida(cont)
{
	//alert($("#clientes").val());
	if($("#clientes").val() == '' || !$("#clientes").val() || $("#clientes").val() == '-1')
	{
		alert('Agregue una cuenta a de clientes.')
    	// seleccionamos el campo incorrecto
    	$('#clientes').focus();
    	guardando(0);
    	return false;
	}

	if($("#ventas").val() == '' || !$("#ventas").val() || $("#ventas").val() == '-1')
	{
		alert('Agregue una cuenta de ventas.')
    	// seleccionamos el campo incorrecto
    	$('#ventas').focus();
    	guardando(0);
    	return false;
	}

	if($("#IVA").val() == '' || !$("#IVA").val() || $("#IVA").val() == '-1')
	{
		alert('Agregue una cuenta de IVA.')
    	// seleccionamos el campo incorrecto
    	$('#IVA').focus();
    	guardando(0);
    	return false;
	}

	/*if($("#caja").val() == '' || !$("#caja").val() || $("#caja").val() == '-1')
	{
		alert('Agregue una cuenta de caja.')
    	// seleccionamos el campo incorrecto
    	$('#caja').focus();
    	guardando(0);
    	return false;
	}


	if($("#bancos").val() == '' || !$("#bancos").val() || $("#bancos").val() == '-1')
	{
		alert('Agregue una cuenta de bancos.')
    	// seleccionamos el campo incorrecto
    	$('#bancos').focus();
    	guardando(0);
    	return false;
	}*/

	var anterior = $('#anterior_corte').val();
	var p = 0;
	if(!parseInt(anterior))
	{
		p = confirm('Esta seguro de guardar la configuracion?\nLa opcion del historial de ventas y las cuentas no podran ser modificados despues');
		if(!p)
		{
			guardando(0);
			return false
		}
	}

	if(parseInt(anterior))
	{
		p = confirm('Esta seguro de guardar los cambios a la configuracion?');
		if(!p)
		{
			guardando(0);
			return false
		}
	}	
}
function guardando(mostrar)
{
	if(mostrar)
	{
		$('#nmloader_div',window.parent.document).show();
	}
	else
	{
		$('#nmloader_div',window.parent.document).hide();
	}
}
</script>
<?php
if(intval($data['polizas_por']))
{
	$readonly = 'disabled';
}
$chkdHistorial1 = '';
$chkdHistorial2 = '';
$chkdConectar = '';
if(intval($data['historial']))
{
	$chkdHistorial1 = "checked";
}
else
{
	$chkdHistorial2 = "checked";
}
if(intval($data['conectar']))
{
	$chkdConectar = 'checked';
}
?>


<link rel="stylesheet" href="js/select2/select2.css">
<?php



	$optionsList = "<option value='-1'>NINGUNO</option>";
	while($Cuentas = $Accounts->fetch_array())
	{
		$optionsList .= "<option value='".$Cuentas['account_id']."'>".$Cuentas['description']."(".$Cuentas['manual_code'].")</option>";
	}

?>

<form name='newCompany' method='post' action='index.php?c=Config&f=saveConfigPDV' onsubmit='return valida(this)'>
	<h3 id='title' class="text-center">Configuraci&oacute;n de Punto de Venta.</h3>
	<div class="container">
		<div class="row">
			<div class="col-md-2">
			</div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nombre de la organizaci&oacute;n:</label>
							<input type='text' class="form-control" name='NameCompany' size='50' readonly value='<?php echo $name['nombreorganizacion']; ?>'>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Ejercicio Vigente:</label>
							<input type='text'  class="form-control" name='NameExercise' size='50' readonly value='<?php echo $data['EjercicioActual']; ?>'>
						</div>
					</div>
				</div>
				<section>
					<h4>Conectar al punto de venta</h4>
					<div class="row">
						<div class="col-md-6">
							<div class="checkbox">
								<label style="padding-left: 0 !important;">Historial de ventas del periodo actual:</label>
								<label style="padding-left: 0 !important; margin-left: 20px;">
							    	<input type='radio' name='historial' id='historial' value='1' <?php echo $readonly." ".$chkdHistorial1; ?>>
							    	Si
							  	</label>
							  	<label style="padding-left: 0 !important; margin-left: 20px;">
							    	<input type='radio' name='historial' id='historial' value='0' <?php echo $readonly." ".$chkdHistorial2; ?>>
							    	No
							  	</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="checkbox">
								<label style="padding-left: 0 !important; margin-left: 20px;">
							    	<input type='checkbox' name='conectar' id='conectar' value='1' <?php echo $chkdConectar; ?>>
							    	Conectar al Punto de Venta
							  	</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Crear Poliza por:</label>
								<select name='corte' id='corte' class="form-control">
									<option value='1'>Corte de Caja</option>
								</select>
								<input type='hidden' name='ejercicio' id='ejercicio' value='<?php echo $data['EjercicioActual']; ?>'>
								<input type='hidden' name='anterior_corte' id='anterior_corte' value='<?php echo $data['polizas_por']; ?>'>
							</div>
						</div>
					</div>
				</section>
				<section>
					<h4>Configurar Cuentas Afectables para el Punto de Venta</h4>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Cuenta de Clientes (Default):</label>
								<button type='button' class="btn btn-default btnMenu" id="showClientes" data-id='clientes'>Asignar cuenta de clientes</button>
								<div id="hideclientes">
									<select name='clientes' id='clientes' class='form-control s2'>
										<?php
											echo $optionsList;
										?>
									</select>
									<button type='button' class="btn btn-default btnMenu" id="cancelclientes" data-id="clientes">Cancelar</button>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Cuenta de Ventas (Default):</label>
								<button type='button' class="btn btn-default btnMenu" id="showVentas" data-id='ventas'>Asignar cuenta de ventas</button>
								<div id="hideventas">
									<select name='ventas' id='ventas' class='form-control s2'>
										<?php
											echo $optionsList;
										?>
									</select>
									<button type='button' class="btn btn-default btnMenu" id="cancelventas" data-id="ventas" >Cancelar</button>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Cuenta de IVA:</label>
								<button type='button' class="btn btn-default btnMenu" id="showIva" data-id='IVA'>Asignar cuenta de IVA</button>
								<div id="hideIVA">
									<select name='IVA' id='IVA' class='form-control s2'>
										<?php
											echo $optionsList;
										?>
									</select>
									<button type='button' class="btn btn-default btnMenu" id="cancelIVA" data-id="IVA" >Cancelar</button>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-4 col-md-offset-8">
							<button type='submit' class="btn btn-primary btnMenu" name='save'  class="nminputbutton" onclick='guardando(1)'>Guardar</button>
						</div>
					</div>
				</section>
			</div>
			<div class="col-md-2">
			</div>
		</div>
	</div>
</form>
