<script language='javascript' src='js/mask.js'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
function generarXMLModal(){
	$('#cargando').show()
	$('.ui-button').attr('disabled','disabled')
	var postFunc;
	var locat;
	var compl=1;
	//alert($('#generar').attr('tipo'))
	if($('#periodo').val() != '0')
	{
		if($('#generar').attr('tipo') == 'balanzas')
		{
			postFunc = 'generarXMLBalanza';
			locat =  'balanzaComprobacionXML'
			
			//Si el checkbox de complementario esta chequeado y el input text de fecha esta vacio
			//no guardara y arrojará un mensaje.
			if($('#bnc').is(':checked') && $('#datepicker').val() == '')
			{
				compl = 0
			}
		}
		if($('#generar').attr('tipo') == 'cuentas')
		{
			postFunc = 'generarXMLCatalogo';
			locat =  'catalogoXML'	
		}

		if($('#generar').attr('tipo') == 'polizas')
		{
			postFunc = 'generarXMLPolizas';
			locat =  'polizasXML'		
		}

		if($('#generar').attr('tipo') == 'auxcuentas')
		{
			postFunc = 'generarXMLauxCuentas';
			locat =  'auxCuentasXML'		
		}

		if($('#generar').attr('tipo') == 'folios')
		{
			postFunc = 'generarXMLFolios';
			locat =  'foliosXML'	
		}
		var flag = 1 //Bandera de si se reemplaza o no
		//Compara si el archivo ya existe----------------
		if(compl)
		{
			$.post("ajax.php?c=Reports&f=existeArchivo",
			 {
				Funcion: locat,
				Tipo: Number($('#bnc').is(':checked')),
				Ejercicio: $('#ejercicio').val(),
				Periodo: $('#periodo').val()

			 },
			function(data)
		 	{
		 		//alert("Bandera Inicial: "+flag)
		 		//alert("Existe Archivo: "+data)
				if(parseInt(data))
				{
					var c = confirm("Esta seguro que desea reemplazar?")
					if(!c)
					{  
						flag = 0//No se reemplaza
						$('#cargando').hide();
						$('.ui-button').removeAttr('disabled');
					}
				}
				//alert("Cambio de Bandera: "+flag)
				if(flag)
				{
					//alert(flag)
					var guarda = 1
					var patron = /[A-Z]{3}[0-6][0-9][0-9]{5}(\/)[0-9]{2}/;
					if((locat == 'polizasXML' || locat == 'auxCuentasXML' || locat == 'foliosXML') && ($("#tipoPol").val() == "AF" || $("#tipoPol").val() == "FC") && (!patron.test($("#numOrden").val()) || $("#numOrden").val() == ''))
					{
						guarda = 0
					}
					if(guarda)
					{
						$.post("ajax.php?c=Reports&f="+postFunc,
						{
							Tipo: Number($('#bnc').is(':checked')),
							Fecha: $("#datepicker").val(),
							Ejercicio: $('#ejercicio').val(),
							Periodo: $('#periodo').val(),
							tipoPol:$("#tipoPol").val(),
							numOrden:$("#numOrden").val(),
							numTramite:$("#numTramite").val(),
							tipocuenta:$("#tipocuenta").val(),
							agrup:$("#agrup").val()
				 		},
			 			function(n)
			 			{
			 				//alert(n)
			 				if(!parseInt(n))
			 				{
			 					noGuarda("No hay datos en este periodo para generar el xml.");
			 				}
			 				if(parseInt(n) == 1)
			 				{
			 					window.location.replace("index.php?c=Reports&f="+locat+"&sub="+$('#ejercicio').val());
			 				}	
			 				if(parseInt(n) == 2)
			 				{
			 					noGuarda("Existen cuenta(s) que no tienen digito agrupador, porfavor verifique.");
			 				}
			 			});
					}
					else
					{
						noGuarda("Capture el numero de orden correctamente.");
					}
				}
		 	});
		}
		else
		{
			noGuarda("Seleccione una fecha de modificacion para complementario.");
		}

	}
	else
	{
		noGuarda("Seleccione un Periodo.");
	}
}
$(function()
 {
 	$("#numTramite").hide()
 	/*$('#numOrden').mask('AAABCCCCCC/CC', {'translation': {
                                        A: {pattern: /[A-Z]/}, 
                                        B: {pattern: /[0-6]/},  
                                        C: {pattern: /[0-9]/}  
                                      }
                                });*/

	$('#numOrden').mask('AAACCCCCCC/CC', {'translation': {
                                        A: {pattern: /[A-Z]/}, 
                                        B: {pattern: /[0-6]/},  
                                        C: {pattern: /[0-9]/}  
                                      }
                                });                                

 	/*$('#numTramite').mask('AAAAAAAAAA', {'translation': {
                                        A: {pattern: /[0-9]/}  
                                      }
                                });*/

	$('#numTramite').mask('AACCCCCCCCCCCC', {'translation': {
                                        A: {pattern: /[A-Z]/}, 
                                        B: {pattern: /[0-6]/},  
                                        C: {pattern: /[0-9]/}  
                                      }
                                });                                
 	var t = '<?php echo $directorio; ?>'
 	switch(t)
 	{
 		case 'balanzas':
 					$("#complementario,#bnc,#agrup,#tipocu").show()
 					$("#tipoPol,#numOrden,#tipocuenta").hide()
 					break;
		case 'cuentas':
					$("#complementario,#bnc,#agrup").hide()
 					$("#tipoPol,#numOrden").hide()
 					$(".tipocuenta").show();
 					break; 					
 		case 'a29':
			 		$("#complementario,#bnc,#agrup").hide()
 					$("#tipoPol,#numOrden,.tipocuenta").hide()
 					break;
 		case 'polizas':
 					$("#complementario,#bnc,.tipocuenta,#agrup").hide()
 					$("#tipoPol,#numOrden").show()
 					break;
 		case 'auxcuentas':
 					$("#complementario,#bnc,.tipocuenta,#agrup").hide()
 					$("#tipoPol,#numOrden").show()
 					break;
 		case 'folios':
					$("#complementario,#bnc,.tipocuenta,#agrup").hide()
 					$("#tipoPol,#numOrden").show()
 					break; 								


 	}
 
 	$( "#datepicker" ).datepicker(
			{ 
				dateFormat: "dd-mm-yy",
				monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
				dayNamesMin: [ "Do","Lu","Ma","Mi","Ju","Vi","Sa"],
				minDate: new Date(2014, 12, 1)
			});
 $('#datepicker').hide()
//Si el ejercicio es mayor o igual a 2015, pueden haber balanzas complementarias
$('#ejercicio').change(function(event) 
{
	comp(t)
});
 $('#bnc').change(function(event) 
 {
	 	if($(this).is(':checked'))
	 	{
	 		$('#datepicker').show()
	 	}
	 	else
	 	{
	 		$('#datepicker').hide()
	 		$('#datepicker').val('')	
	 	}
 });	
 //$("#ejercicio").val($("#actual").val())
 $('#ejercicio option[value="'+$("#actual").val()+'"]').attr("selected", "selected");



$('#generar').click(function(){
		$('#generaXML').modal('show');
	});
comp(t)
$("#tipoPol").change(function(event) {
	if($(this).val() == 'AF' || $(this).val() == 'FC')
	{
		$("#numOrden").show()
		$("#numTramite").hide()
	}
	else
	{
		$("#numOrden").hide()	
		$("#numTramite").show()	
	}
	});
 	
 	antes2015()
 	$("#ejercicio").change(function(event) {
 		antes2015()
 	});
});

function antes2015()
{
	if(parseInt($('#ejercicio').val()) >= 2015)
 	{
 		$("#periodo").removeAttr('disabled')
 	}
 	else
 	{
 		$("#periodo").val('0')
 		$("#periodo").attr('disabled','disabled')
 	}
}

function noGuarda(mensaje)
{
	alert(mensaje);
	$('#cargando').hide();
	$('.ui-button').removeAttr('disabled');
}

function comp(t)
{
	if(parseInt($("#ejercicio").val()) >= 2015 && t == 'balanzas')
	{
		$('label,#bnc').show()	
	}
	else
	{
		$('label,#bnc').hide()
	}
}
</script>
<style>
#lista td
{
	width:146px;
	text-align: center;
	border:1px solid #BDBDBD;
}

#buscar
{
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
}

#cargando
{
	display:none;
	position:absolute;
	z-index:1;
}

#tbl_gen td
{
	height:30px;
}

.ui-datepicker-month
{
 color:black;
}

</style>

<div id="generaXML" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar XML's</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-6">
                		<strong>Ejercicio:</strong><br>
						<input type='hidden' id='actual' value='<?php echo $ejercicio_actual ?>' class='nminputselect'>
						<select name='ejercicio' id='ejercicio' class='form-control'>
							<?php  
							while($e = $ejercicios->fetch_array())
							{
								echo "<option value='".$e['NombreEjercicio']."'>".$e['NombreEjercicio']."</option>";
							}
							?>
						</select>
                	</div>
                	<div class="col-md-6">
	            		<strong>Periodo:</strong><br>
						<select id='periodo' class='form-control'>
							<option value='0'>Selecciona un periodo</option>
							<option value='01'>Enero</option>
							<option value='02'>Febrero</option>
							<option value='03'>Marzo</option>
							<option value='04'>Abril</option>
							<option value='05'>Mayo</option>
							<option value='06'>Junio</option>
							<option value='07'>Julio</option>
							<option value='08'>Agosto</option>
							<option value='09'>Septiembre</option>
							<option value='10'>Octubre</option>
							<option value='11'>Noviembre</option>
							<option value='12'>Diciembre</option>
							<?php
							if($directorio == "balanzas")
								echo "<option value='13'>Cierre del ejercicio</option>";
							?>
						</select>
	            	</div>
                </div>
                <div class="row">
	            	<div class="col-md-12">
	            		<strong class='tipocuenta' id='tipocu'>Ordenar por:</strong>
						<select class='tipocuenta form-control' id='tipocuenta'>
							<option value='0'>Mayor y Afectable</option>
							<option value='1'>Nivel 1 y 2</option>
						</select>
						<select class='form-control' id='agrup'>
							<option value='0'>Sólo afectables</option>
							<option value='1'>Agrupadoras y afectables</option>
						</select>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-12">
	            		<strong id='complementario'>Complementario:</strong>
						<input type='checkbox' name='bnc' id='bnc' value='1'>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-12">
	            		<select id='tipoPol' name='tipoPol' class='form-control'>
							<option value='AF'>Acto de fiscalizaci&oacute;n</option>
							<option value='FC'>Fiscalizaci&oacute;n compulsa</option>
							<option value='DE'>Devoluci&oacute;n</option>
							<option value='CO'>Compensaci&oacute;n</option>
						</select>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-12">
	            		<input class="form-control" type='text' id='datepicker' name='fecha_comp' value='' placeholder='Fecha'>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-12">
	            		<input class="form-control" type='text' id='numOrden' name='numOrden' value='' placeholder='Numero de Orden' title='Formato: AAA699999/99'>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-12">
	            		<input class="form-control" type='text' id='numTramite' name='numTramite' value='' placeholder='Numero de Tramite' title='Formato: 999999999'>
	            	</div>
	            </div>
	            <div class="row" id='cargando'>
	            	<div class="col-md-12">
	            		<label style='color:#91C313;'>Espere un momento...</label>
	            	</div>
	            </div>
	        </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:generarXMLModal();">Generar XML</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>