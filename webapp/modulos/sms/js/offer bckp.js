$(document).ready (function(){
	consultaClientes();
	buscaProductos();
	buscaClientes();
	buscaUnidades();
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
	var filtro = $("#busca_cliente").val();
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "buscaClientes", param: filtro},
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
				data: {funcion: "refrescaSelects", param: $("#id_"+i).val(), checked: 1},
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
				data: {funcion: "refrescaSelects", param: $("#id_"+i).val(), checked: 0},
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
			data: {funcion: "refrescaSelects", param: id, checked: 1},
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
			data: {funcion: "refrescaSelects", param: id, checked: 0},
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
	var contador = $("#cont_clientes").val();
	var contador_checks = 0;
	
	var org = $("#org").val();
	var cantidad = $("#cantidad").val();
	var unidad = $("#unidad option:selected").text();
	var producto = $("#producto option:selected").text();
	var precio = $("#precio").val();
	var f_inicio = $("#fecha_inicio").val();
	var f_fin = $("#fecha_fin").val();
	var elaborador = $("#elaborado_por").val();
	
	var cadena_alerta = "";
	
	if(org == "")
		cadena_alerta += "- No hay ninguna organizacion.\n";
	if(cantidad == "")
		cadena_alerta += "- No elegiste cantidad.\n";
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
	
	/*if (fecha_inicio[1] == 1)
		fecha_inicio[1] = "Enero";
	if (fecha_inicio[1] == 2)
		fecha_inicio[1] = "Febrero";
	if (fecha_inicio[1] == 3)
		fecha_inicio[1] = "Marzo";
	if (fecha_inicio[1] == 4)
		fecha_inicio[1] = "Abril";
	if (fecha_inicio[1] == 5)
		fecha_inicio[1] = "Mayo";
	if (fecha_inicio[1] == 6)
		fecha_inicio[1] = "Junio";
	if (fecha_inicio[1] == 7)
		fecha_inicio[1] = "Julio";
	if (fecha_inicio[1] == 8)
		fecha_inicio[1] = "Agosto";
	if (fecha_inicio[1] == 9)
		fecha_inicio[1] = "Septiembre";
	if (fecha_inicio[1] == 10)
		fecha_inicio[1] = "Octubre";
	if (fecha_inicio[1] == 11)
		fecha_inicio[1] = "Noviembre";
	if (fecha_inicio[1] == 12)
		fecha_inicio[1] = "Diciembre";
		
	if (fecha_fin[1] == 1)
		fecha_fin[1] = "Enero";
	if (fecha_fin[1] == 2)
		fecha_fin[1] = "Febrero";
	if (fecha_fin[1] == 3)
		fecha_fin[1] = "Marzo";
	if (fecha_fin[1] == 4)
		fecha_fin[1] = "Abril";
	if (fecha_fin[1] == 5)
		fecha_fin[1] = "Mayo";
	if (fecha_fin[1] == 6)
		fecha_fin[1] = "Junio";
	if (fecha_fin[1] == 7)
		fecha_fin[1] = "Julio";
	if (fecha_fin[1] == 8)
		fecha_fin[1] = "Agosto";
	if (fecha_fin[1] == 9)
		fecha_fin[1] = "Septiembre";
	if (fecha_fin[1] == 10)
		fecha_fin[1] = "Octubre";
	if (fecha_fin[1] == 11)
		fecha_fin[1] = "Noviembre";
	if (fecha_fin[1] == 12)
		fecha_fin[1] = "Diciembre";*/
	
	
	for(var i=0; i<contador; i++)
	{
		if($('#chk'+i).is(':checked'))
		{
			contador_checks++;
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
			contenido += "El mensaje que se enviará es el siguiente:<p>";
			var mensaje = '<div id="SMS"><font color="#006efe">Del <b>'+fecha_inicio[2]+ '/'+fecha_inicio[1]+'</b> al <b>'+fecha_fin[2]+ '/'+fecha_fin[1]+'</b>! <b>'+org+'</b> te ofrece ';
			mensaje += '<b>'+cantidad+' '+unidad+'</b> de <b>'+producto+'</b> por <b>$'+precio+'</b>. Contesta "Si" espacio tu cantidad deseada para recibir el producto en tu negocio</font></div>'; 
			
			contenido += mensaje;
			if(mensaje.length > 251)
			{
				contenido += '<p><hr><p><font color="#FD000E">El mensaje es demasiado largo. Pruebe cambiando el nombre del producto en la interface de productos. (Caracteres máximos: 160 / usados: '+((mensaje.length)-91) +')</font>';
			}
			else
			{
				contenido += '<p><hr><div style="text-align: right;"><img class="preloader" id="preloader_generar" src="../../images/preloader.gif">  <input type="button" value="Enviar oferta!" onclick="guardarEnviar();"></div>';
			}
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

function guardarEnviar()
{
	$("#preloader_generar").show();	
	var contador = $("#cont_clientes").val();
	var contador_checks = 0;
	
	var cantidad = $("#cantidad").val();
	var unidad = $("#unidad").val();
	var producto = $("#producto").val();
	var precio = $("#precio").val();
	var f_inicio = $("#fecha_inicio").val();
	var f_fin = $("#fecha_fin").val();
	var elaborador = $("#elaborado_por").val();
	var sms = $("#SMS").text();
	
	$.ajax(
	{
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "guardarEnviar", cantidad: cantidad, unidad: unidad, producto: producto, precio: precio, f_inicio: f_inicio, f_fin: f_fin, elaborador: elaborador, sms: sms},
		success: function(callback)
		{	
     		alert(callback);
     		$("#preloader_generar").hide();	
		}
	});
}






