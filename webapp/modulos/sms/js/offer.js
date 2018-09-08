$(document).ready (function(){
	//consultaClientes();
	buscaProductos();
	buscaSucursales();
	//buscaClientes();
	buscaUnidades();
	cargaFiltros();
});

function buscaProductos()
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaProductos"},
		success: function(callback)
		{	
     		$("#productos").html(callback);
		}
	});
};

function buscaSucursales()
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaSucursales"},
		success: function(callback)
		{	
     		$("#sucursales").html(callback);
		}
	});
};


function consultaClientes()
{
	$.ajax(
	{
		async: false,
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "consultaClientes"},
		success: function(callback)
		{	
     		
     		var x = 1;
		}
	});
};

function buscaClientes()
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaClientes"},
		success: function(callback)
		{	
     		$("#clientes").html(callback);
     		$("#preloader_clientes").hide();
		}
	});
};

function buscaClientesAgain()
{
	var preloader = "<center><br><img class='preloader' id='preloader_clientes' src='../../images/preloader.gif'></center>";
	
	var contador = $("#cont_clientes").val();
	var id = new Array();
	
	$('#cliente').html(preloader);
	
	buscaClientes();
};

function filtraClientes()
{
	var preloader = "<center><br><img class='preloader' id='preloader_clientes' src='../../images/preloader.gif'></center>";
	$('#cliente').html(preloader);
	
	$('#filtrar_clientes_btn').prop('disabled', true);
	$("#preloader_clientes").show();
	
	/*var filtro =          $("#busca_cliente").val();
	var filtroGiro =      $("#filtro_giro").val();
	var filtroRubro =     $("#filtro_rubro").val();
	var filtroEstado =    $("#filtro_estado").val();
	var filtroMunicipio = $("#filtro_municipio").val();*/
	var grupo = $("#grupofil").val();

	//alert(filtro+"-"+filtroGiro+"-"+filtroRubro+"-"+filtroEstado+"-"+filtroMunicipio);
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		//data: {funcion: "buscaClientes", filtro: filtro, filtroGiro: filtroGiro, filtroRubro: filtroRubro, filtroEstado: filtroEstado, filtroMunicipio: filtroMunicipio},
		data: {funcion: "buscaClientes", grupo: grupo},

		success: function(callback)
		{	
     		$("#clientes").html(callback);
     		$("#preloader_clientes").hide();
     		$('#filtrar_clientes_btn').prop('disabled', false);
		}
	});
}

function buscaUnidades()
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaUnidades"},
		success: function(callback)
		{	
     		$("#unidades").html(callback);
		}
	});
};

function cargaFiltros()
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaGiros"},
		success: function(callback)
		{	
     		$("#filtro_giro_div").html(callback);
		}
	});
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaRubros"},
		success: function(callback)
		{	
     		$("#filtro_rubro_div").html(callback);
		}
	});
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaEstados"},
		success: function(callback)
		{	
     		$("#filtro_estado_div").html(callback);
		}
	});
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaMunicipios", idEst: 0},
		success: function(callback)
		{	
     		$("#filtro_municipio_div").html(callback);
		}
	});
};

function filtraMunicipio(idEst)
{
	//alert(idEst);
	$("#preloader_municipio").show();
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaMunicipios", idEst: idEst},
		success: function(callback)
		{	
     		$("#filtro_municipio").html(callback);
     		$("#preloader_municipio").hide();
		}
	});
}

function selectAll()
{
	var cont_clientes = $("#cont_clientes").val();
	if ($('#select_all').is(':checked'))
	{
		for(var i=0; i<cont_clientes; i++)
		{
			$.ajax(
			{
				async: false,
				url:'../../../sms/funcionesBD/offer.php',
				type: 'POST',
				data: {funcion: "refrescaSelects", id: $("#id_"+i).val(), checked: 1},
				success: function(callback)
				{	
		     		$('#chk'+i).prop('checked', true);
				}
			});
		}
	}
	else
	{
		for(var i=0; i<cont_clientes; i++)
		{
			$.ajax(
			{
				async: false,
				url:'../../../sms/funcionesBD/offer.php',
				type: 'POST',
				data: {funcion: "refrescaSelects", id: $("#id_"+i).val(), checked: 0},
				success: function(callback)
				{	
		     		$('#chk'+i).prop('checked', false);
				}
			});
		}
	}
}

function refrescaSelects(id, pos)
{
	$('#clientes').css('opacity', '.5');
	
	if ($('#chk'+pos).is(':checked'))
	{
		//alert(id + " " + pos + " se checo");
		$.ajax(
		{
			async: false,
			url:'../../../sms/funcionesBD/offer.php',
			type: 'POST',
			data: {funcion: "refrescaSelects", id: id, checked: 1},
			success: function(callback)
			{	
	     		var x = 1;
	     		$('#clientes').css('opacity', '1');
			}
		});
	}
	else
	{
		//alert(id + " " + pos + " se descheco");
		$.ajax(
		{
			async: false,
			url:'../../../sms/funcionesBD/offer.php',
			type: 'POST',
			data: {funcion: "refrescaSelects", id: id, checked: 0},
			success: function(callback)
			{	
	     		var x = 1;
	     		$('#clientes').css('opacity', '1');
			}
		});
	}
}

function generar()
{


	//

	$('#formulario_ofertas').disable();
	var contador = $("#cont_clientes").val();
	var contador_checks = 0;

	var org = $("#org").val();
	var cantidad = $("#cantidad").val();
	var cantidad_existente = $("#cantidad_existente").val();
	var unidad = $("#unidad option:selected").text();
	var producto = $("#producto option:selected").text();
	var precio = $("#precio").val();
	var f_inicio = $("#fecha_inicio").val();
	var f_fin = $("#fecha_fin").val();
	var elaborador = $("#elaborado_por").val();
	var sucursal = $("#sucursal").val();
	
	var cadena_alerta = "";
	
	if(org == "")
		cadena_alerta += "- No hay ninguna organizacion.\n";
	if(cantidad == "")
		cadena_alerta += "- No elegiste cantidad.\n";
	if(cantidad_existente == "")
		cadena_alerta += "- No elegiste numero de ofertas.\n";
	if($("#unidad").val() == "")
		cadena_alerta += "- No seleccionaste unidad.\n";
	if($("#producto").val() == "")
		cadena_alerta += "- No seleccionaste producto.\n";
	if($("#precio").val() == "")
		cadena_alerta += "- No seleccionaste precio.\n";
	if(f_inicio == "")
		cadena_alerta += "- No hay fecha de inicio.\n";
	if(f_fin == "")
		cadena_alerta += "- No hay fecha de expiración.\n";
	if(elaborador == "")
		cadena_alerta += "- No hay un responsable de la oferta.\n";


		
	var fecha_inicio = f_inicio.split('-');
	var fecha_fin = f_fin.split('-');
	
	//var preloader = "<center><br><img class='preloader' id='preloader_clientes' src='../../images/preloader.gif'></center>";
	//		$('#cliente').html(preloader);
			
		/*	$.ajax(
			{
				async:false,
				url:'../../../sms/funcionesBD/offer.php',
				type: 'POST',
				data: {funcion: "buscaClientes"},
				success: function(callback)
				{	
		     		$("#clientes").html(callback);
		     		$("#preloader_clientes").hide();
				}
			});
	*/
	var ides='';
	for(var i=0; i<contador; i++)
	{
		if($('#chk'+i).is(':checked'))
		{
			contador_checks++;
			ides+=$('#chk'+i).val()+',';
		}
	}

	if (contador_checks < 1)
	{
		alert("Por favor seleccione algun cliente");
	}
	else
	{
		if(cadena_alerta == "")
		{
			var df = verificaDisponibilidad(2);
			if(df==1){
				alert('-El numero de ofertas supera a la cantidad de productos en inventario, verificar maxima disponibilidad.');
				return false;
			}
			var cont_lista = 1;
			var contenido = "Se generará la oferta para <b>" + contador_checks + "</b> clientes: <p>";
			
			contenido += "<div style='height:100px; width:90%; max-width: 90%; border:1px solid #006efe; overflow:auto; padding: 10px;' >"
			for(var i=0; i<contador; i++)
			{
				if($('#chk'+i).is(':checked'))
				{
					contenido += cont_lista + ".- " + "[" + $("#rub_"+i).html() + "] " + $("#nom_"+i).html() + " (" + $("#estMun_"+i).val() + ") <br>";
					cont_lista++;
				}
			}
			contenido += "</div><p>"
			contenido += 'El mensaje que se enviará es el siguiente:<p> <textarea id="SMS" rows="4" style="width:95%; font-size:14px; border: 1px solid #000000;" onKeyPress="calculaSMS(this.value,\''+ides+'\')"; onKeyUp="calculaSMS(this.value,\''+ides+'\');"">';
			
			var mensaje = 'Del '+fecha_inicio[2]+ '/'+fecha_inicio[1]+' al '+fecha_fin[2]+ '/'+fecha_fin[1]+'! '+org+' te ofrece '+cantidad+' '+unidad+' de '+producto+' por $'+precio+'. Contesta "Si" espacio tu cantidad deseada para recibir el producto en tu negocio'; 
			
			contenido += mensaje;
			
			if(mensaje.length > 160)
			{
				contenido += "</textarea>   <div style='margin-top: -14px;'>" +
			 								"<div id='contador_SMS' style='display: table-cell; color: #FF000C;'>" + 
			 									mensaje.length +
			 								"</div>" +
			 								"<div style='display: table-cell;'> /160 </div>" +
			 							"<div> <br>";
			}
			else
			{
				contenido += "</textarea>   <div style='margin-top: -14px;'>" +
			 								"<div id='contador_SMS' style='display: table-cell; #000000;'>" + 
			 									mensaje.length +
			 								"</div>" +
			 								"<div style='display: table-cell;'> /160 </div>" +
			 							"<div> <br>";
			}
				
			contenido += '<p> <hr> <div id="enviando_SMS" style="color: #01a05f; text-align: left;"></div>';
			contenido += '<div id="estatus_SMS" style="color: #FD000E; text-align: right;">';
			if(mensaje.length > 160)
			{
				$("#contador_SMS").css('color','#FF000C');
				contenido += 'El mensaje es demasiado largo. No se puede proceder';
			}
			else
			{
				contenido += '<div style="text-align: right;"><img class="preloader" id="preloader_generar" src="../../images/preloader.gif">  <input type="button" id="guardarEnviar" value="Enviar oferta!" onclick="guardarEnviar(\''+ides+'\');"></div>';
			}
			contenido += '</div>';
			$("#popup_generar").html(contenido);
			$("#preloader_generar").hide();	
			
			$('#popup').fadeIn('slow');
	        $('#formulario_ofertas').css('opacity', '.5');
	        return false;
	    }
	    else
	    {
	    	alert(cadena_alerta);
	    }
	}
}

function calculaSMS(sms,ides)
{

	if(sms.length > 160)
	{
		$("#contador_SMS").empty();
		$("#contador_SMS").html(sms.length);
		$("#contador_SMS").css('color','#FF000C');
		
		$("#estatus_SMS").html("El mensaje es demasiado largo. No se puede proceder");
	}
	else
	{
		$("#contador_SMS").empty();
		$("#contador_SMS").html(sms.length);
		$("#contador_SMS").css('color','#000000');
		
		$("#estatus_SMS").html('<div style="text-align: right;"><img class="preloader" id="preloader_generar" src="../../images/preloader.gif">  <input type="button" value="Enviar oferta!" id="guardarEnviar" onclick="guardarEnviar(\''+ides+'\');"></div>');
		$("#preloader_generar").hide();	
	}
	
}

function guardarEnviar(ides)
{

	$("#guardarEnviar").disable();
	$("#preloader_generar").show();	
	$("#enviando_SMS").html("Estamos procesando su oferta. Esto podría demorar unos minutos dependiendo la cantidad de clientes. Por favor sea paciente.");
	var contador = $("#cont_clientes").val();
	var contador_checks = 0;
	
	var cantidad = $("#cantidad").val();
	var cantidad_existente = $("#cantidad_existente").val();
	var unidad = $("#unidad").val();
	var producto = $("#producto").val();
	var precio = $("#precio").val();
	var f_inicio = $("#fecha_inicio").val();
	var f_fin = $("#fecha_fin").val();
	var elaborador = $("#elaborado_por").val();
	var sms = $("#SMS").val();
	var sucursal = $("#sucursal").val();
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "guardarEnviar", ides:ides, cantidad_existente: cantidad_existente, cantidad: cantidad, unidad: unidad, producto: producto, precio: precio, f_inicio: f_inicio, f_fin: f_fin, elaborador: elaborador, sms: sms, sucursal: sucursal},
		success: function(callback)
		{	
			$("#enviando_SMS").empty();
     		$("#preloader_generar").hide();
     		
     		window.location="../offer/listado_ofertas.php";
		}
	});
}






