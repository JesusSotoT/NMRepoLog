$(document).ready (function(){
	cargaDatosConsulta();
	clientesRecibieronSMS();
});

function cargaDatosConsulta()
{
	
	var id = $("#idOferta").val();
	$.ajax(
	{
		async: false,
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "cargaDatosConsulta", id: id},
		success: function(callback)
		{	
			//alert("olk");
     		var arregloDatos = callback.split("$$$^^^###///");
			//alert(arregloDatos);
			$("#fecha_inicio").val(arregloDatos[4]);
			$("#fecha_fin").val(arregloDatos[5]);
			$("#fecha_creacion").val(arregloDatos[6]);
			$("#cantidad").val(arregloDatos[3]);
			$("#producto").val(arregloDatos[0]);
			$("#unidad").val(arregloDatos[1]);
			$("#precio").val(arregloDatos[2]);
			$("#usuario_genera").html(arregloDatos[7]);
			$("#producto_id").val(arregloDatos[9]);
			$("#sucursal").html(arregloDatos[11]);
			$("#sucursal_id").val(arregloDatos[10]);
			$("#unidad_id").val(arregloDatos[12]);
		}
	});
};

function clientesRecibieronSMS()
{
	//alert("ok");
	var id = $("#idOferta").val();
	$.ajax(
	{
		async: false,
		url:'../../../sms/funcionesBD/offer.php',
		type: 'POST',
		data: {funcion: "clientesRecibieronSMS", id: id},
		success: function(callback)
		{	
			//alert("ok2");
			$("#clientes").html(callback);
		}
	});
}

function filtraUltimoCostoYUnidadPreview(idProveedor, idProducto)
{
	$("#preloader_preview_componente_costo").show();
	$("#preloader_preview_componente_unidad").show();
	 	$.ajax({
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"filtraUltimoCosto", idPrv:idProveedor, idPro:idProducto},
					success: function(callback)
					{	
					    $("#ultimo_costo").val(callback);
					    $("#preloader_preview_componente_costo").hide();
					    
					     $.ajax({
									url:'../../../sms/funcionesBD/offer.php',
									type: 'POST',
									data: {funcion:"filtraUnidad", idPrv:idProveedor, idPro:idProducto},
									success: function(callback)
									{	
					     				$("#uni").html(callback);
										$("#preloader_preview_componente_unidad").hide();
									}
								});
					}
				});
	//alert("ok");
}

function filtraUltimoCostoYUnidadPreviewCompuesto(idProveedor, idProducto, contador)
{
	$("#preloader_preview_producto_costo_"+contador).show();
	$("#preloader_preview_producto_unidad_"+contador).show();
	
	 	$.ajax({
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"filtraUltimoCostoProductoCompuesto", idPrv:idProveedor, idPro:idProducto, contador:contador},
					success: function(callback)
					{	
					    $("#ultimo_costo_"+contador).val(callback);
					    $("#preloader_preview_producto_costo_"+contador).hide();
					    $.ajax({
									url:'../../../sms/funcionesBD/offer.php',
									type: 'POST',
									data: {funcion: "filtraUnidadProductoCompuesto", idPrv:idProveedor, idPro:idProducto, contador:contador},
									success: function(callback)
									{	
					     				$("#uni_"+contador).html(callback);
										$("#preloader_preview_producto_unidad_"+contador).hide();
									}
								});
					}
				});
}

function obtieneEquivalenciaUnidadCompuesto(idUnidad)
{
	$("#preloader_preview_componente_unidad").show();
	 	$.ajax({
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"obtieneEquivalenciaUnidadCompuesto", idUnidad:idUnidad},
					success: function(callback)
					{	
						var arregloDatos = callback.split("$$$^^^###///");
			
					    $("#equivalencia_componente_unidad").html("(Equivale a "+arregloDatos[0]+" "+arregloDatos[1]+")");
					    $("#preloader_preview_componente_unidad").hide();
					}
				});
}

function generarOrdenCompraProducto()
{
	var idSucursal = $("#sucursal_id").val();
	var fechaHoy = $("#fecha_hoy").val();
	var elaboradoPor = $("#usuario_genera").html();
	var idOferta = $("#oferta_id").val();
	
	var cantidad = $("#cantidad_compuesto").val();
	var idProducto  = $("#producto_id").val();
	var idProveedor = $("#proveedor_producto").val();
	var ultCosto = $("#ultimo_costo").val();
	var idUnidad = $("#unidad_producto").val();
	
	var contClientes = $("#cont_clientes").val();
	var cadena_alerta = "";
	
	if(idSucursal == 0)
	{
		if($("#sucursal_recibe").val() == "")
		{
			cadena_alerta += "- No se eligió una sucursal para recibir la orden.\n";
		}
		else
		{
			var idSucursalRecibe = $("#sucursal_recibe").val();
		}
	}
	else
	{
		var idSucursalRecibe = idSucursal;
	}
	
	if(cantidad == "")
		cadena_alerta += "- No elegiste cantidad.\n";
	if(idUnidad == "")
		cadena_alerta += "- No seleccionaste unidad.\n";
	if(idProducto == "")
		cadena_alerta += "- No seleccionaste producto.\n";
	if(idProveedor == "")
		cadena_alerta += "- No seleccionaste proveedor.\n";
	if(ultCosto == "")
		cadena_alerta += "- No seleccionaste costo.\n";
	if(fechaHoy == "")
		cadena_alerta += "- No hay fecha de pedido.\n";
	if(elaboradoPor == "")
		cadena_alerta += "- No hay un responsable de la oferta.\n";
		
	if(cadena_alerta == "")
	{
		$.ajax({
					async: false,
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"registraOrdenProducto", idProducto:idProducto, idSucursal: idSucursalRecibe, fechaHoy: fechaHoy, elaboradoPor: elaboradoPor, cantidad: cantidad, idProveedor: idProveedor, ultCosto: ultCosto, idUnidad: idUnidad, contClientes: contClientes, idOferta: idOferta},
					success: function(callback)
					{	
						alert(callback);
						window.location="../offer/listado_ofertas.php";
					}
				});
	}
	else
	{
		alert(cadena_alerta);
	}
	
}

function proceder()
{
	var idOferta = $("#oferta_id").val();
	
	$.ajax({
					async: false,
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"procederExistencias", idOferta:idOferta},
					success: function(callback)
					{	
						alert(callback);
						window.location="../offer/listado_ofertas.php";
					}
				});
}

function cargaAcordeon()
{
	$("#preloader_calcular").show();
	$('#throwback').prop('disabled', true);
	$('#calcular').prop('disabled', true);
	$('#forward').prop('disabled', true);
	var a_pedir = $("#cantidad_base").val();
	var id = $("#producto_id").val();
	var nombre = $("#producto").val();
	
	var idSucursal = $("#sucursal_id").val();
	
	if(a_pedir != 0)
	{
		if(a_pedir % 1 != 0)
		{
			alert("La cantidad no puede tener punto decimal");
			$('#throwback').prop('disabled', false);
			$('#calcular').prop('disabled', false);
			$('#forward').prop('disabled', false);
			$("#preloader_calcular").hide();
		}
		else
		{
			$.ajax({
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"prep_reccomponentes", id:id, a_pedir: a_pedir, nombre: nombre, idSuc: idSucursal},
					success: function(callback)
					{	
						$("#aqui_carga_acordeon").empty();
						$("#aqui_carga_acordeon").html(callback);
						
					   	$(".acordeon").accordion({ collapsible: true});
					   	$(".numeric").numeric();
					   	
					   	$("#preloader_preview").hide();
					   	$('#throwback').prop('disabled', false);
					   	$('#forward').prop('disabled', false);
					   	$('#calcular').prop('disabled', false);
					   	$("#preloader_calcular").hide();
					   	$(".preloader_preview_elemento").hide();
					}
				});
		}
	}
	else
	{
		alert("Introduce una cantidad para calcular el pedido neto");
		$('#throwback').prop('disabled', false);
		$('#calcular').prop('disabled', false);
		$('#forward').prop('disabled', false);
		$("#preloader_calcular").hide();
	}
}

function verProducto()
{
	$('#throwback').prop('disabled', true);
	$('#forward').prop('disabled', true);
	$("#preloader_preview").show();
	var cantidad = $("#cantidad").val();
	var idProducto = $("#producto_id").val();
	var producto = $("#producto").val();
	var idSucursal = $("#sucursal_id").val();
	var sucursal = $("#sucursal").html();
	var unidad = $("#unidad").val();
	var idUnidad = $("#unidad_id").val();
	
	var total_pedido = 0;
	var cont_clientes = $("#cont_clientes").val();
	
	for(var i=0; i<cont_clientes; i++)
	{
		if($("#estatus_"+i).val() == '-1')
		{
			total_pedido += parseFloat($("#cantidad_"+i).val());
		}
	}

	var mensaje;
	
	$.ajax({
				url:'../../../sms/funcionesBD/offer.php',
				type: 'POST',
				data: {funcion: "verProducto", idProducto: idProducto, producto:producto, cantidad: cantidad, idSucursal:idSucursal, sucursal:sucursal, total_pedido:total_pedido, idUnidad:idUnidad},
				success: function(callback)
				{	
					$("#producto_preview").empty();
					$("#producto_preview").html(callback);
					
					$(".acordeon").accordion({ collapsible: true});
					
				   	$("#preloader_preview").hide();
				   	$('#throwback').prop('disabled', false);
				   	$('#forward').prop('disabled', false);
				   	$(".preloader_preview_elemento").hide();
				   	$("#preloader_calcular").hide();
				}
		}); 
		
	//}
}

function generarOrdenesCompraComponentes()
{
	var alerta_unidades = false;
	var alerta_proveedores = false;
	var alerta_costos = false;
	
	var contadorProductos = $("#contador_componentes").val();

	var fechaHoy = $("#fecha_hoy").val();
	var elaboradoPor = $("#usuario_genera").html();
	var idOferta = $("#oferta_id").val();
	var contClientes = $("#cont_clientes").val();
	
	var idSucursal = $("#sucursal_id").val();
	
	var cadena_alerta = "";
	
	if(idSucursal == 0)
	{
		if($("#sucursal_recibe").val() == "")
		{
			cadena_alerta += "- No se eligió una sucursal para recibir la orden.\n";
		}
		else
		{
			var idSucursalRecibe = $("#sucursal_recibe").val();
		}
	}
	else
	{
		var idSucursalRecibe = idSucursal;
	}
	if(fechaHoy == "")
		cadena_alerta += "- No hay fecha de pedido.\n";
	if(elaboradoPor == "")
		cadena_alerta += "- No hay un responsable de la oferta.\n";
		
		
	var arrID = new Array();
	var arrPrv = new Array();
	var arrUni = new Array();
	var arrCos = new Array();
	var arrCnt = new Array();
	
	for(var i=1; i<=contadorProductos; i++)
	{
		if($("#proveedor_producto_"+i).html() == "No hay un proveedor para este producto")
		{
			alerta_proveedores = true;
		}
		if($("#uni_"+i).html() == "Selecciona primero proveedor" || $("#uni_"+i).html() == "Este producto no tiene unidades" || $("#unidad_producto_"+i).val() == "")
		{
			alerta_unidades = true;
		}
			
		if($("#ultimo_costo_"+i).html() == "Registre primero un proveedor para el producto" || $("#ultimo_costo_"+i).val() == "")
		{
			alerta_costos = true;
		}
		
		if(alerta_proveedores == false && alerta_unidades == false && alerta_costos == false)
		{
			arrID.push($("#producto_id_"+i).val());
			arrPrv.push($("#proveedor_producto_"+i).val());
			arrUni.push($("#unidad_producto_"+i).val());
			arrCos.push($("#ultimo_costo_"+i).val());
			arrCnt.push($("#cantidad_compuesto_"+i).html());
		}
	}
	
	if(alerta_proveedores != false)
		cadena_alerta += "- Hay productos sin proveedor.\n";
	if(alerta_unidades != false)
		cadena_alerta += "- Hay productos sin unidades.\n";
	if(alerta_costos != false)
		cadena_alerta += "- Hay productos sin costo.\n";
		
	if(cadena_alerta == "")
	{	
		$.ajax({
					async: false,
					url:'../../../sms/funcionesBD/offer.php',
					type: 'POST',
					data: {funcion:"registraOrdenProductoCompuesto", arrID:arrID, idSucursal: idSucursalRecibe, fechaHoy: fechaHoy, elaboradoPor: elaboradoPor, 
					arrPrv: arrPrv, arrCos: arrCos, arrUni: arrUni, contClientes: contClientes, 
					idOferta: idOferta, contadorProductos: contadorProductos, arrCnt: arrCnt},
					success: function(callback)
					{	
						alert(callback);
						window.location="../offer/listado_ofertas.php";
					}
				});
	}
	else
	{
		alert(cadena_alerta);
	}
	
	
}
