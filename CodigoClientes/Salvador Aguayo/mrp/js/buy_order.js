/*function x()
{
	var cantidad = $("#cantidad_producto").val();
	var nombre = $("#producto").val();
	var nombre_texto = $("#producto option:selected").text();
	
		$.ajax({
					url:'../../../../modulos/mrp/index.php/buy_order/unidadx',
					type: 'POST',
					data: {id: nombre, nombre: nombre_texto, cantidad: cantidad},
					success: function(callback)
					{	
						//document.getElementById("preview").innerHTML = "";
						
						$("#hola").html(callback);
					}
			}); 
} 

*/

var arraycallback = Array();

function ver_producto()
{
	$('#agregar_producto').prop('disabled', true);
	$('#agregar_componente').prop('disabled', true);
	$("#preloader_preview").show();
	var cantidad = $("#cantidad_producto").val();
	var nombre = $("#producto").val();
	var nombre_texto = $("#producto option:selected").text();
	
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	if (cantidad == "") // Agrega a la cadena de alerta los mensajes
		cadena_alerta_productos += " - Olvidaste introducir una cantidad.\n"; 
	if (nombre == "")
		cadena_alerta_productos += " - Olvidaste introducir el producto a ordenar.\n";	
		
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		$("#preloader_preview").hide();
		alert(cadena_alerta_productos);
		$('#agregar_producto').prop('disabled', false);
		$('#agregar_componente').prop('disabled', false);
	}
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{	 
		$.ajax({
					url:'../../../../modulos/mrp/index.php/buy_order/verProducto',
					type: 'POST',
					data: {id: nombre, nombre: nombre_texto, cantidad: cantidad},
					success: function(callback)
					{	
						//document.getElementById("preview").innerHTML = "";
						
						$(".preview").empty();
						$(".preview").html(callback);
					   	$(".acordeon").accordion({ collapsible: true});
					   	$(".numeric").numeric({allow:"."});
					   	$("#preloader_preview").hide();
					   	$('#agregar_producto').prop('disabled', false);
					   	$('#agregar_componente').prop('disabled', false);
					   	$(".preloader_preview_elemento").hide();
					}
			}); 
		
	}
}

function ver_componente()
{
	$('#agregar_producto').prop('disabled', true);
	$('#agregar_componente').prop('disabled', true);
	$("#preloader_preview").show();
	var cantidad = $("#cantidad_producto").val();
	var nombre = $("#componente").val();
	var nombre_texto = $("#componente option:selected").text();
	
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	if (cantidad == "") // Agrega a la cadena de alerta los mensajes
		cadena_alerta_productos += " - Olvidaste introducir una cantidad.\n"; 
	if (nombre == "")
		cadena_alerta_productos += " - Olvidaste introducir el componente a ordenar.\n";	
		
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		$("#preloader_preview").hide();
		alert(cadena_alerta_productos);	
		$('#agregar_componente').prop('disabled', false);
		$('#agregar_producto').prop('disabled', false);
	}
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{	 
		$.ajax({
					url:'../../../../modulos/mrp/index.php/buy_order/verComponente',
					type: 'POST',
					data: {id: nombre, nombre: nombre_texto, cantidad: cantidad},
					success: function(callback)
					{	
						$(".preview").empty();
						$(".preview").html(callback);
					   	$(".acordeon").accordion({ collapsible: true});
					   	$(".numeric").numeric({allow:"."});
					   	$("#preloader_preview").hide();
					   	$('#agregar_componente').prop('disabled', false);
					   	$('#agregar_producto').prop('disabled', false);
					   	$(".preloader_preview_elemento").hide();
					}
				});
	}
}
//Funcion de agregado de producto a grid de pedidos !-->
function agregar_componente() 
{ 
	$('#agregar_producto').prop('disabled', true);
	$('#agregar_componente').prop('disabled', true);
	$("#preloader_grid").show();
	// Obtiene los valores de los campos de cantidad, unidad y nombre de producto
	//var cantidad = $("#hdnConversion").val();
	var cantidad = $("#cantidad_compuesto").attr('value');
	//alert(cantidad);
	//return;
	//var unidad = $("#hdnUnidad").val(); obtiene la unidad del input oculto
	var unidad = $("#unidad_producto").val();
	var unidad_test = document.getElementById("uni").innerHTML;
	var unidad_texto = $("#unidad_producto option:selected").text();
	var nombre = $("#producto_id").val();
	var nombre_texto = $("#nombre_producto").val();
	var proveedor = $("#proveedor_producto").val();
	var proveedor_test = $("#proveedor_producto").text();
	var proveedor_texto = $("#proveedor_producto option:selected").text();
	var costo = $("#ultimo_costo").val();	
	 
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	if (proveedor_test == "No hay un proveedor para este producto")
	{
		cadena_alerta_productos = " No puedes ordenar un producto sin proveedor\n";	
	} 
	else
	{
		if (cantidad == "") // Agrega a la cadena de alerta los mensajes
		cadena_alerta_productos += " - Olvidaste introducir una cantidad.\n"; 
		if (unidad == "" || unidad_test == "Selecciona primero proveedor")
			cadena_alerta_productos += " - Olvidaste introducir una unidad.\n";
		if (nombre == "")
			cadena_alerta_productos += " - Olvidaste introducir el producto a ordenar.\n";	
		if (proveedor == "")
			cadena_alerta_productos += " - Olvidaste introducir el proveedor del producto.\n";	
		if (costo == "")
			cadena_alerta_productos += " - Olvidaste introducir un costo de compra\n";	
	}
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		$("#preloader_grid").hide();
		alert(cadena_alerta_productos);
		$('#agregar_producto').prop('disabled', false);
		$('#agregar_componente').prop('disabled', false);
	}
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{	

		$.ajax({
					async: false,
					url:'../../../../modulos/mrp/index.php/buy_order/agregaProducto',
					type: 'POST',
					data: {cantidad: cantidad, unidad: unidad, unidad_texto:unidad_texto, nombre: nombre, proveedor:proveedor, costo:costo, nombre_texto:nombre_texto, proveedor_texto: proveedor_texto},
					success: function(callback)
					{	
						$("#grid").html();
						$("#grid").html(callback);
						$("#preloader_grid").hide();
						$('#agregar_producto').prop('disabled', false);
						$('#agregar_componente').prop('disabled', false);
						
							$(".preview").empty();

					}
				});
		
	}
};
function agregar_producto() 
{ 
	$('#agregar_producto').prop('disabled', true);
	$('#agregar_componente').prop('disabled', true);
	$("#preloader_grid").show();
	// Obtiene los valores de los campos de cantidad, unidad y nombre de producto
	var contador = $("#contador_componentes").val();
	
	var cantidad = new Array();
	var unidad = new Array();
	var unidad_test = new Array();
	var unidad_texto = new Array();
	var nombre = new Array();
	var nombre_texto = new Array();
	var proveedor = new Array();
	var proveedor_test = new Array();
	var proveedor_texto = new Array();
	var costo = new Array();
	
	for(var i=0; i<contador; i++)
	{
		cantidad[i] = $("#cantidad_compuesto_"+(i+1)).html();
		unidad[i] = $("#unidad_producto_"+(i+1)).val();
		unidad_test[i] = document.getElementById("uni_"+(i+1)).innerHTML;
		unidad_texto[i] = $("#unidad_producto_"+(i+1)+" option:selected").text();
		nombre[i] = $("#producto_id_"+(i+1)).val();
		nombre_texto[i] = $("#nombre_producto_"+(i+1)).val();
		proveedor[i] = $("#proveedor_producto_"+(i+1)).val();
		proveedor_test[i] = $("#proveedor_producto_"+(i+1)).text();
		proveedor_texto[i] = $("#proveedor_producto_"+(i+1)+" option:selected").text();
		costo[i] = $("#ultimo_costo_"+(i+1)).val();
	}
	 
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	for(var i=0; i<contador;i++)
	{
		if (proveedor_test[i] == "No hay un proveedor para este producto")
		{
			cadena_alerta_productos = " - El producto "+nombre_texto[i]+" no tiene proveedor\n";	
		} 
		else
		{
			if (cantidad[i] == "") // Agrega a la cadena de alerta los mensajes
				cadena_alerta_productos += " - El producto "+nombre_texto[i]+" no tiene cantidad.\n"; 
			if (unidad[i] == "" || unidad_test[i] == "Selecciona primero proveedor")
				cadena_alerta_productos += " - El producto "+nombre_texto[i]+" no tiene unidad.\n";
			if (nombre[i] == "")
				cadena_alerta_productos += " - Olvidaste introducir el producto a ordenar.\n";	
			if (proveedor[i] == "")
				cadena_alerta_productos += " - No seleccionaste proveedor para el producto "+nombre_texto[i]+".\n";	
			if (costo[i] == "")
				cadena_alerta_productos += " - El producto "+nombre_texto[i]+" no tiene costo de compra\n";	
		}
	}
	
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		alert(cadena_alerta_productos);
		$("#preloader_grid").hide();
		$('#agregar_producto').prop('disabled', false);
		$('#agregar_componente').prop('disabled', false);
	}
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{	 
		for(var i=0; i<contador;i++)
		{
			$.ajax({
						async: false,
						url:'../../../../modulos/mrp/index.php/buy_order/agregaProducto',
						type: 'POST',
						data: {cantidad: cantidad[i], unidad: unidad[i], unidad_texto:unidad_texto[i], nombre: nombre[i], proveedor:proveedor[i], costo:costo[i], nombre_texto:nombre_texto[i], proveedor_texto: proveedor_texto[i]},
						success: function(callback)
						{	
							$("#grid").html();
							$("#grid").html(callback);
							$("#preloader_grid").hide();
							$('#agregar_producto').prop('disabled', false);
							$('#agregar_componente').prop('disabled', false);
							
							$(".preview").empty();
						}
					});
		}
		
	}
};

/////////////////////////////////////////////////////////////////////////////////////////////	
function imprime_grid()
{
	$.ajax({
					url:'../../../../modulos/mrp/index.php/buy_order/imprimeGrid',
					type: 'POST',
					success: function(callback)
					{	
						$("#grid").html();
						$("#grid").html(callback);
					}
				});
}

function buscaFamilia(idDepartamento)
{
	
	 // $.ajax({
					// url:'../../../mrp/index.php/buy_order/familia',
					// type: 'POST',
					// data: {id:idDepartamento},
					// success: function(callback)
					// {	
						// $("#fam_producto").html(callback);
					     // $.ajax({
							// url:'../../../mrp/index.php/buy_order/productoFiltroDepartamento',
							// type: 'POST',
							// data: {id:idDepartamento},
							// success: function(callback)
							// {
								// //alert("ok");
								// $("#producto_div").html(callback);
								// $.ajax({
									// url:'../../../mrp/index.php/buy_order/componenteFiltroDepartamento',
									// type: 'POST',
									// data: {id:idDepartamento},
									// success: function(callback)
									// {
										// //alert("ok");
										// $("#componente_div").html(callback);
										// $("#preloader_filtros").hide();
									// }
								// });
							// }
						// });
					// }
				// });
}		
// function cargaLineas2($iddepa,$idFamilia)
// { $("#preloader_filtros").show();
	// $.ajax({
		// type: 'POST',
		// url:'../../../../modulos/mrp/index.php/buy_order/',
		// data:{funcion:"cargaLineas2",iddepa:$iddepa,idFamilia:$idFamilia},
		// success: function(resp){  $("#span-lineas").html(resp);   $("#preloader_filtros").hide();    }});//end ajax	
// }
// function cargaFamilias2($idDepartamento)
// { $("#preloader_filtros").show();
		// $.ajax({
		// type: 'POST',
		// url:'../../../../modulos/mrp/index.php/buy_order/',
		// data:{funcion:"cargaFamilias2",idDepartamento:$idDepartamento},
		// success: function(resp){  $("#span-familias").html(resp); $("#preloader_filtros").hide();      }});//end ajax	
// }
// function cargaProductos($idLinea)
// { $("#preloader_filtros").show();
		// $.ajax({
		// type: 'POST',
		// url:'../../../../modulos/mrp/index.php/buy_order/',
		// data:{funcion:"cargaProductos",idLinea:$idLinea},
		// success: function(resp){  $("#span-productos").html(resp);   $("#preloader_filtros").hide();    }});//end ajax	
// }
// function loadproductos($iddepa,$idfamilia,$idLinea)
// { $("#preloader_filtros").show();
		// $.ajax({
		// type: 'POST',
		// url:'../../../../modulos/mrp/index.php/buy_order/',
		// data:{funcion:"productosexistencias",iddepa:$iddepa,idfamilia:$idfamilia,idlinea:$idLinea},
		// success: function(resp){  $("#span-productos").html(resp);     $("#preloader_filtros").hide();  }});//end ajax	
// 	
// }	

/////////////////////////////////////////////////////////////////////////////////////////////

function buscaFamiliaFiltroProveedor(idOrd, idDepartamento, idProveedor)
{
	$("#preloader_filtros").show();
	 $.ajax({
					url:'../../../../mrp/index.php/buy_order/familiaFiltroProveedor',
					type: 'POST',
					data: {id:idDepartamento, idPrv:idProveedor, idOrd: idOrd},
					success: function(callback)
					{	
						$("#fam_producto").html(callback);
					     $.ajax({
							url:'../../../../mrp/index.php/buy_order/productoFiltroDepartamentoProveedor',
							type: 'POST',
							data: {id:idDepartamento, idPrv:idProveedor, idOrd: idOrd},
							success: function(callback)
							{
								//alert("ok");
								$("#producto_div").html(callback);
								$("#preloader_filtros").hide();
								
							}
						});
					}
				});
}	



function buscaLineaFiltroProveedor(idOrd, idFamilia, idProveedor)
{
	$("#preloader_filtros").show();
	 $.ajax({
					url:'../../../../mrp/index.php/buy_order/lineaFiltroProveedor',
					type: 'POST',
					data: {id:idFamilia, idPrv:idProveedor, idOrd: idOrd},
					success: function(callback)
					{	
						$("#lin_producto").html(callback);
					     $.ajax({
							url:'../../../../mrp/index.php/buy_order/productoFiltroFamiliaProveedor',
							type: 'POST',
							data: {id:idFamilia, idPrv:idProveedor, idOrd: idOrd},
							success: function(callback)
							{
								//alert("ok");
								$("#producto_div").html(callback);
								$("#preloader_filtros").hide();
							}
						});
					}
				});
}	
 
function buscaProductoFiltroLineaProveedor(idOrd, idLinea, idProveedor)
{
	$("#preloader_filtros").show();
	$.ajax({
					
					url:'../../../../mrp/index.php/buy_order/productoFiltroLineaProveedor',
					type: 'POST',
					data: {id:idLinea, idPrv:idProveedor, idOrd: idOrd},
					success: function(callback)
					{
						//alert("ok");
						$("#producto_div").html(callback);
						$("#preloader_filtros").hide();
					}
				});
}		

/////////////////////////////////////////////////////////////////////////////////////////////	
function buscaLinea(idFamilia)
{ 
	$("#preloader_filtros").show();
	 $.ajax({
					url:'../../../mrp/index.php/buy_order/linea',
					type: 'POST',
					data: {id:idFamilia},
					success: function(callback)
					{	
					     $("#lin_producto").html(callback);
					     $.ajax({
							url:'../../../mrp/index.php/buy_order/productoFiltroFamilia',
							type: 'POST',
							data: {id:idFamilia},
							success: function(callback)
							{
								$("#producto_div").html(callback);
								$.ajax({
									url:'../../../mrp/index.php/buy_order/componenteFiltroFamilia',
									type: 'POST',
									data: {id:idFamilia},
									success: function(callback)
									{
										//alert("ok");
										$("#componente_div").html(callback);
										$("#preloader_filtros").hide();
									}
								});
							}
						});
					}
				});
}

function filtraLinea(idLinea)
{
	$("#preloader_filtros").show();
	 $.ajax({
					url:'../../../mrp/index.php/buy_order/productoFiltroLinea',
					type: 'POST',
					data: {id:idLinea},
					success: function(callback)
					{	
					     $("#producto_div").html(callback);
					     $.ajax({
									url:'../../../mrp/index.php/buy_order/componenteFiltroLinea',
									type: 'POST',
									data: {id:idLinea},
									success: function(callback)
									{
										//alert("ok");
										$("#componente_div").html(callback);
										$("#preloader_filtros").hide();
									}
								});
					}
				});
}



function filtraProveedor(idProducto)
{
	 $.ajax({
					url:'../../../mrp/index.php/buy_order/proveedorFiltroProducto',
					type: 'POST',
					data: {id:idProducto},
					success: function(callback)
					{	
					     $("#proveedor_producto_div").html(callback);
					}
				});
}

function filtraUltimoCostoYUnidad(idProveedor, idProducto)
{
	 $("#preloader_filtros").show();
	 	$.ajax({
					url:'../../../../mrp/index.php/buy_order/filtraUltimoCosto',
					type: 'POST',
					data: {idPrv:idProveedor, idPro:idProducto},
					dataType: 'json',
					success: function(callback)
					{	
					     $("#ultimo_costo").val(callback);
					     $.ajax({
									url:'../../../../mrp/index.php/buy_order/filtraUnidad',
									type: 'POST',
									dataType: 'json',
									data: {idPrv:idProveedor, idPro:idProducto},
									success: function(callback)
									{	
					     				$("#uni").html(callback[0]);
					     				$("#preloader_filtros").hide();
					     				$('#unidad_producto [orden=1]').attr({'selected':'selected'});

					     				//Conversion

					     				arraycallback = callback;
					     				conversion(callback);

					     				$('#unidad_producto').bind('change', function(event) {
					     					conversion(callback);
					     				});

					     				//Conversion
									}
								});
					}
				});
}

function filtraUltimoCostoYUnidadPreview(idProveedor, idProducto)
{
	$("#preloader_preview_componente_costo").show();
	$("#preloader_preview_componente_unidad").show();
	 	$.ajax({
					url:'../../../mrp/index.php/buy_order/filtraUltimoCosto',
					type: 'POST',
					data: {idPrv:idProveedor, idPro:idProducto},
					success: function(callback)
					{	
					    $("#ultimo_costo").val(callback);
					    $("#preloader_preview_componente_costo").hide();
					    
					     $.ajax({
									url:'../../../mrp/index.php/buy_order/filtraUnidad',
									type: 'POST',
									dataType : 'json',
									data: {idPrv:idProveedor, idPro:idProducto},
									success: function(callback)
									{	
					     				$("#uni").html(callback[0]);
					     				$('#unidad_producto [orden=1]').attr({'selected':'selected'});

					     				//Conversion

					     				arraycallback = callback;
					     				conversion(callback);

					     				$('#unidad_producto').bind('change', function(event) {
					     					conversion(callback);
					     				});

										$("#preloader_preview_componente_unidad").hide();
									}
								});
					}
				});
}

function conversion(callback){
	var valor = $('#cantidad_producto').val();
	
	var conversion = 0;
	

	if(valor != 0){

		var conversionCompra = $('#unidad_producto'+' option:selected').attr('conversion');
		var ordenCompra = $('#unidad_producto'+' option:selected').attr('orden');

		var conversionVenta = callback[1]["conversion"];
		var ordenVenta = callback[1]["orden"];
		var unidad = callback[1]["compuesto"];


		var conversionCompraOperacion = (valor * conversionCompra);
		var conversionVentaOperacion = (conversionCompraOperacion / conversionVenta);


		//alert('compra ->' +conversionCompraOperacion);

		if(ordenCompra <= ordenVenta)
		{
			console.log("1: "+conversionCompra +"*"+valor+'/'+ conversionVenta);
			conversion = parseFloat(conversionCompra * valor / conversionVenta);
			//alert( 'Venta -> '+parseFloat(conversionCompra / conversionVenta)+' '+unidad);
		}else
		{
			if(ordenVenta == 1)
			{
				console.log("2: "+valor +'*'+ conversionCompra);
				conversion = parseFloat(conversionCompraOperacion);
				//alert( 'Venta -> '+parseFloat(conversionCompraOperacion)+' '+unidad);
			}else
			{
				console.log("3: "+(conversionCompraOperacion + '/' + conversionVenta));
				conversion = parseFloat(conversionVentaOperacion);
				//alert( 'Venta -> '+parseFloat(conversionVentaOperacion)+' '+unidad);
			}
		}

	}

	$('#hdnConversion').val(conversion);
	$('#hdnUnidad').val(callback[1]["idunidad"]);
}

function filtraUltimoCostoYUnidadPreviewCompuesto(idProveedor, idProducto, contador)
{
	$("#preloader_preview_producto_costo_"+contador).show();
	$("#preloader_preview_producto_unidad_"+contador).show();
	
	 	$.ajax({
					url:'../../../mrp/index.php/buy_order/filtraUltimoCostoProductoCompuesto',
					type: 'POST',
					data: {idPrv:idProveedor, idPro:idProducto, contador:contador},
					success: function(callback)
					{	
					    $("#ultimo_costo_"+contador).val(callback);
					    $("#preloader_preview_producto_costo_"+contador).hide();
					     $.ajax({
									url:'../../../mrp/index.php/buy_order/filtraUnidadProductoCompuesto',
									type: 'POST',
									data: {idPrv:idProveedor, idPro:idProducto, contador:contador},
									success: function(callback)
									{	
					     				$("#uni_"+contador).html(callback);
										$("#preloader_preview_producto_unidad_"+contador).hide();
									}
								});
					}
				});
}

function generar()
{
	var comprueba = false;
	
		$.ajax({
						async: false,
						url:'../../../../modulos/mrp/index.php/buy_order/compruebaSesionProductos',
						type: 'POST', 
						data: {},
						success: function(callback)
						{	
							if(callback == 1)
								comprueba = true;
							else
								comprueba = false;
						}
					});
					
	$("#preloader_generar").show();
	$("#send").prop('disabled', true);
	var sucursal_solicita = $("#sucursal_solicita").val();
	var almacen_solicita = $("#almacen").val();
	var fecha_pedido = $("#fecha_pedido").val();
	var fecha_entrega = $("#fecha_entrega").val();
	var elaborado_por = $("#elaborado_por").val();
	
	var cadena_alerta_productos = "";
	//alert($("#compruba_productos_session").val());
	// Verifica que el cliente no haya olvidado rellenar campos vitales
	if ( comprueba == false )
	{
		$("#preloader_generar").hide();
		alert("No se ingresó ningún producto")
		$('#send').prop('disabled', false);
	}
	else
	{
		if (sucursal_solicita == "") // Agrega a la cadena de alerta los mensajes
			cadena_alerta_productos += " - Olvidaste introducir la sucursal que solicita.\n";
		if (almacen_solicita == "") // Agrega a la cadena de alerta los mensajes
			cadena_alerta_productos += " - Olvidaste introducir el almacen que solicita.\n"; 	 
		if (fecha_pedido == "")
			cadena_alerta_productos += " - Olvidaste introducir una fecha de pedido.\n";	
		if (elaborado_por == "")
			cadena_alerta_productos += " - Por favor introduce quién elaboró esta orden.\n";
			
		if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de campos
		{
			$("#preloader_generar").hide();
			alert(cadena_alerta_productos);
			$('#send').prop('disabled', false);
		}
		else //Si está vacía, todo está en orden y se procede a preguntar por confirmacion para la orden
		{	
			var r = confirm("Se generarán las órdenes de compra por proveedor.\n¿Deseas continuar?");
			if (r==true)
		  	{
		  		$.ajax({
						url:'../../../../modulos/mrp/index.php/buy_order/registraOrden',
						type: 'POST', 
						data: {almacen: almacen_solicita, sucursal_solicita: sucursal_solicita, fecha_pedido: fecha_pedido, fecha_entrega: fecha_entrega, elaborado_por: elaborado_por},
						success: function(callback)
						{	
							//alert(callback);
							alert("Orden generada con exito");
							$("#formulario_ordenes").html(callback);  
							$("#preloader_generar").hide();
							$('#send').prop('disabled', false);
							
						}
					});
		  	}
		  	else
		  	{
		  		$("#preloader_generar").hide();
				$('#send').prop('disabled', false);
		  	}
		}
	}
}
 
function guardarCambiosOrden()
{
	$('#guardar').prop('disabled', true);
	$("#preloader_editar").show();
	var fecha_entrega = $("#fecha_entrega").val();
	var autorizado_por = $("#autorizado_por").val();
	var id_orden = $("#id_orden").val();
	
	//var redir = false;
	
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	if (autorizado_por == "") // Agrega a la cadena de alerta los mensajes
		cadena_alerta_productos += " - Olvidaste introducir un autorizador.\n"; 
	if (fecha_entrega == "")
		cadena_alerta_productos += " - Olvidaste introducir una fecha de entrega.\n";
		
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		$("#preloader_editar").hide();
		alert(cadena_alerta_productos);
		$('#guardar').prop('disabled', false);		
	}	
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{
		var cadena = autorizado_por + " / " + fecha_entrega + " / ";
		var arreglo_costos = new Array();
		var arreglo_ids = new Array();
		var arreglo_cantidades = new Array();
		
		for (var i=0 ; i<$("#contador_productos").val(); i++)
		{
			arreglo_cantidades[i] = $("#cantidad_"+i).val();
			arreglo_costos[i] = $("#costo_producto_"+i).val();
			arreglo_ids[i] = $("#id_producto_orden_"+i).val();
			cadena += arreglo_costos[i] + " / " + arreglo_ids[i] + " / ";
		}
		 
		$.ajax({
					//async: false,
					url:'../../../../mrp/index.php/buy_order/editaOrden',
					type: 'POST', 
					data: {id_orden: id_orden, fecha_entrega:fecha_entrega, autorizado_por: autorizado_por, arreglo_costos:arreglo_costos, arreglo_ids:arreglo_ids, arreglo_cantidades: arreglo_cantidades},
					success: function(callback)
					{	
						//alert(callback);
						alert("Orden editada con exito");
						$("#edicion_orden").html(callback);  
						$("#preloader_editar").hide();  
						$('#guardar').prop('disabled', false);
						var url="../../../../webapp/modulos/mrp/index.php/buy_order/grid";
						var frop = parent.document.getElementById("opciones");
				        frop.src = url;
					}
			}); 
			
		
	}
}

function imprimir()
{
	var ficha = document.getElementById("orden_imprimible");

	var ventimp = window.open(' ','popimpr');
	ventimp.document.write(ficha.innerHTML);
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
	//document.getElementById("orden_imprimible").print();
}



function ChangeOrder(id)
{
	
	$("#sub_"+id).html($.number($("#cantidad_"+id).val().replace(",","")*quitapuntos($("#costo_producto_"+id).val().replace(",","")) ,2));
	
	var cont=$("#contador_productos").val();
	var total=0;
	
	for(var i=0;i<cont;i++)
	{
		total+=parseFloat($("#sub_"+i).html().replace(",",""));	
	}
	
	$("#precio_neto").html(total.toFixed(2));
	$("#precio_iva").html(parseFloat(total*0.16).toFixed(2));
	$("#precio_total").html(parseFloat(total*1.16).toFixed(2));

}


function quitapuntos(numero)
{
	var e=0;
	 while (numero.toString().indexOf(".") != -1)
	{
		if(e==0)
		{
		  numero= numero.toString().replace(".","*");
		}
		else
		{
		  numero= numero.toString().replace(".","");
		}
		e++;
	}
	numero=numero.replace("*",".");
	return numero;
}

function registrar_producto_interface_edicion()
{

	conversion(arraycallback);

	$('#agregar').prop('disabled', true);
	$("#preloader_agregar").show();
	var cantidad = $("#hdnConversion").val();
	var unidad = $("#hdnUnidad").val();
	var nombre = $("#producto").val();
	var costo = $("#ultimo_costo").val();
	var id = $("#id_orden").val();
	var autorizador_temporal = $("#autorizado_por").val();
	var fecha_entrega = $("#fecha_entrega").val();
	
	var cadena_alerta_productos = "";
	// Verifica que el cliente no haya olvidado poner cantidad, unidad y nombre de producto
	if (cantidad == "") // Agrega a la cadena de alerta los mensajes
		cadena_alerta_productos += " - Olvidaste introducir una cantidad.\n"; 
	if (unidad == "")
		cadena_alerta_productos += " - Olvidaste introducir una unidad.\n";
	if (nombre == "")
		cadena_alerta_productos += " - Olvidaste introducir el producto a ordenar.\n";	
	if (costo == "")
		cadena_alerta_productos += " - Olvidaste introducir un costo de compra\n";	
		
	if (cadena_alerta_productos != "") //Si la cadena NO esta vacía, el cliente olvidó rellenar uno de los campos
	{
		$("#preloader_agregar").hide();
		alert(cadena_alerta_productos);
		$('#agregar').prop('disabled', false);
	}	
	else //Si está vacía, todo está en orden y se procede a guardar ese producto en el grid de pedidos
	{	 
		$.ajax({
					url:'../../../../mrp/index.php/buy_order/registraProductoInterfaceEdicion',
					type: 'POST',
					data: {id: id, cantidad: cantidad, unidad: unidad, nombre: nombre, costo:costo, autorizador: autorizador_temporal, entrega: fecha_entrega},
					success: function(callback)
					{	
						alert("Producto agregado a la orden con exito");
						$("#edicion_orden").html(callback);
						$("#preloader_agregar").hide();
						$('#agregar').prop('disabled', false);
					}
				});
	}
}

function erase_loading()
{	
	//$('#borrar').prop('disabled', true);
	//alert(baseurl);
	$("#preloader_grid").show();
	
	var sucursal_solicita = $("#sucursal_solicita").val();
	var fecha_pedido = $("#fecha_pedido").val();
	var fecha_entrega = $("#fecha_entrega").val();
	var elaborado_por = $("#elaborado_por").val();
	
	//alert(sucursal_solicita);
	
	$.ajax({
					url:baseurl+'index.php/buy_order/guardaTemporal',
					type: 'POST',
					data: {suc: sucursal_solicita, fec: fecha_pedido, ent: fecha_entrega, aut: elaborado_por},
					success: function(callback)
					{	
						$('#borrar').prop('disabled', true);
					}
			});
	//alert("Se borrará el producto de la lista.");
}




function cargaalmacenes(sucursal)
{
	
	
		//alert(sucursal);
		$.ajax({
		url:'../../../../modulos/mrp/index.php/buy_order/cargaalmacenes',
		type: 'POST',
		data: {id:sucursal},
		success: function(callback)
		{	 
			$("#almacenes").html(callback);							
		}	
		});	
}
 function cargaproduct(almacen){
 //alert(almacen);
 	 $.post("../../../../modulos/mrp/sinfiltro.php",{
		almacen:almacen},
	function(respues) {
		//alert(respues);
		$("#producto").html(respues);	
		
   	});
	 // $.ajax({
		// url:'../../../../modulos/mrp/index.php/buy_order/buscaproductoSinFiltro',
		// type: 'POST',
		// data: {almacen:almacen},
		// success: function(callback)
		// {	 
			// alert(callback);
			// //$("#producto").html(callback);							
		// }	
		// });	
} //
function cargaexistencias(id)
{
	$("#preloader").show();
	$.ajax({
		type: 'POST',
		url:'../../../mrp/index.php/buy_order/existenciasSucursal',
		data:{funcion:"existenciassucursal",id:id},
		success: function(resp){ 
			$("#preloader").hide(); 
			$("#detalle-producto").html(resp);  
		}});//end ajax	
}

//  
 
	function enviar(orden){
		//alert('Los rudos, los rudos, los rudooooos!!!');

		//contenido = $('#orden_imprimible').html();
			contenido = "<div>si jala</div>";
			$('#prueba').attr('src', '../../../index.php/buy_order/enviar?orden='+orden);
	/*		$.ajax({
				type: 'POST',
				dataType: 'html',
				url:'../../../index.php/buy_order/enviarprueba',
				data:{orden:orden},
				success: function(resp){ 
					//$('#prueba').html(resp);
					//alert('se hizo el pdf');
				}});//end ajax*/
			

	}