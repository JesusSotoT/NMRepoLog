<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>

function gTxtModal(){
	$('#cargando').show()
 	$('.ui-button').attr('disabled','disabled')
	var postFunc;
	var locat;
	//alert($('#generar').attr('tipo'))
	if($('#periodo').val() != '0')
	{
		if($('#generar').attr('tipo') == 'balanzas')
		{
			postFunc = 'generarXMLBalanza';
			locat =  'balanzaComprobacionXML'
		}
		if($('#generar').attr('tipo') == 'cuentas')
		{
			postFunc = 'generarXMLCatalogo';
			locat =  'catalogoXML'	
		}

		if($('#generar').attr('tipo') == 'a29')
		{
			$.post("ajax.php?c=Reports&f=generarA29",
			 {
		 		Ejercicio: $('#ejercicio').val(),
				Periodo_inicial: $('#periodo_inicial').val(),
				Periodo_final: $('#periodo_final').val(),
				Prov: $('input:radio[name=prov]:checked').val(),
				Proveedor_inicial: $('#proveedor_inicial').val(),
				Proveedor_final: $('#proveedor_final').val()
		 	},
	 		function(data)
	 		{
	 			if(parseInt(data))
	 			{
	 				//alert(data)
	 				var car = $('#ejercicio').val().split('-')
					window.location.replace("index.php?c=Reports&f=a29Txt&sub="+car[1]);
	 			}
	 			else
	 				alert("Hubo un error: \n"+data)
	 			
	 		});
		}
		else
		{
			$.post("ajax.php?c=Reports&f="+postFunc,
			 {
		 		Ejercicio: $('#ejercicio').val(),
				Periodo: $('#periodo').val()
		 	},
	 		function(data)
	 		{
	 			var car = $('#ejercicio').val().split('-')
				window.location.replace("index.php?c=Reports&f="+locat+"&sub="+car[1]);
	 		});
		}
	}
	else
	{
		alert("Seleccione un Periodo");
	}
}

$(function()
 {
 //$("#ejercicio").val($("#actual").val())
 $('#ejercicio option[value="'+$("#actual").val()+'"]').attr("selected", "selected");

$('#generar').click(function(){
	$('#generaTXT').modal('show');
	});
});
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

</style>

<div id="generaTXT" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar TXT's</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-6">
                		<label>Ejercicio:</label>
                		<input type='hidden' id='actual' value='<?php echo $ejercicio_actual ?>' class='nminputselect'>
                		<select name='ejercicio' id='ejercicio' class='form-control'>
							<?php  
							while($e = $ejercicios->fetch_array())
							{
								echo "<option value='".$e['Id']."-".$e['NombreEjercicio']."'>".$e['NombreEjercicio']."</option>";
							}
							?>
						</select>
                	</div>
                </div>
                <div class="row">
                	<div class="col-md-6">
                		<label>Del periodo:</label>
                		<select id='periodo_inicial' class='form-control'>
							<option value='1'>Enero</option>
							<option value='2'>Febrero</option>
							<option value='3'>Marzo</option>
							<option value='4'>Abril</option>
							<option value='5'>Mayo</option>
							<option value='6'>Junio</option>
							<option value='7'>Julio</option>
							<option value='8'>Agosto</option>
							<option value='9'>Septiembre</option>
							<option value='10'>Octubre</option>
							<option value='11'>Noviembre</option>
							<option value='12'>Diciembre</option>
						</select>
                	</div>
                	<div class="col-md-6">
                		<label>Al periodo:</label>
                		<select id='periodo_final' class='form-control'>
							<option value='1'>Enero</option>
							<option value='2'>Febrero</option>
							<option value='3'>Marzo</option>
							<option value='4'>Abril</option>
							<option value='5'>Mayo</option>
							<option value='6'>Junio</option>
							<option value='7'>Julio</option>
							<option value='8'>Agosto</option>
							<option value='9'>Septiembre</option>
							<option value='10'>Octubre</option>
							<option value='11'>Noviembre</option>
							<option value='12'>Diciembre</option>
						</select>
	            	</div>
                </div>
                <div class="row">
                	<div class="col-md-6">
                		<input type='radio' id='prov' name='prov' value='todos' checked> Todos
                	</div>
                	<div class="col-md-6">
                		<input type='radio' id='prov' name='prov' value='algunos'> Algunos
	            	</div>
                </div>
                <div class="row">
                	<div class="col-md-6">
                		<label>Del proveedor:</label>
                		<select name='proveedor_inicial' id='proveedor_inicial' class='form-control'>
							<?php  
							while($p_ini = $proveedor_inicial->fetch_array())
							{
								echo "<option value='".$p_ini['idPrv']."'>".$p_ini['razon_social']."</option>";
							}
							?>
						</select>
                	</div>
                	<div class="col-md-6">
                		<label>Al proveedor:</label>
                		<select name='proveedor_final' id='proveedor_final' class='form-control'>
							<?php  
							while($p_fin = $proveedor_final->fetch_array())
							{
								echo "<option value='".$p_fin['idPrv']."'>".$p_fin['razon_social']."</option>";
							}
							?>
						</select>
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
                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:gTxtModal();">Generar TXT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
