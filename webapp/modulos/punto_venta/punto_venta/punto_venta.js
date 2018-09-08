function cargasaldocaja(idSuc)
{
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"saldocaja",sucursal:idSuc},
		success: function(resp){   
		 $("#saldocaja").html(resp);

		
		 }});//end ajax		 
}


function Inproduct()
{
	if( $("#producto").val()=="" ){ alert("Debes seleccionar un producto"); return false; }
	if( $("#sucursal").val()=="" ){ alert("Debes seleccionar una sucursal"); return false; }
	if( $("#cantidad").val()=="" ){ alert("Debes seleccionar una cantidad"); return false; }
	
	$("#preloader2").show();
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"ingresamercancia",costo:$("#costo").val(),proveedor:$("#proveedor").val(),producto:$("#producto").val(),sucursal:$("#sucursal").val(),cantidad:$("#cantidad").val()},
		success: function(resp){  $("#detalle-producto").html(resp);   alert("Has ingresado la mercancia con exito");    
		 $("#cantidad").val("");
		 $("#producto").val("");
		 $("#sucursal").val("");
		  $("#proveedor").val("");
		   $("#costo").val("");
	$("#preloader2").hide();
		
		 }});//end ajax	
}


function loadproductos($idLinea)
{
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"productosexistencias",id:$idLinea},
		success: function(resp){  $("#span-productos").html(resp);       }});//end ajax	
	
}


function cargaexistencias(id)
{
		$("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"existenciassucursal",id:id},
		success: function(resp){ 
			$("#preloader").hide(); 
			$("#detalle-producto").html(resp);  
		}});//end ajax	
}

////////////////////////////
function filtraventas()
{
		$("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"ventas",registros:$("#registros").val(),estatus:$("#estatus").val(),inicio:$("#inicio").val(),fin:$("#fin").val(),cliente:$("#cliente").val(),sucursal:$("#sucursal").val(),vendedor:$("#vendedor").val()},
		success: function(resp){ 
			$("#preloader").hide(); 
			$("#ventas").html(resp);  
		}});//end ajax	
}

function Limpiaventas()
{
	$("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"ventas",inicio:"",fin:"",cliente:"",sucursal:"",vendedor:"",estatus:""},
		success: function(resp){ 
			$("#ventas").html(resp);  
				$("#inicio").val("");
				$("#fin").val("");
				$("#vendedor").val("");
				$("#sucursal").val("");
				$("#cliente").val("");
				$("#preloader").hide(); 
		}});//end ajax	
}

/////////////////////////////////////////////////
function detalleVenta(id)
{
 $.ajax({
		type: 'POST',
		url:'venta_detalle.php',
		data:{id:id},
		success: function(resp){   
		
		$('#caja-dialog').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 850,
				draggable: true,
				resizable: true,
				title:"Detalle venta",
				open: function(){ $(this).empty().append(resp); },
			   buttons:
				[
				{
					text:'Cancelar venta',click: function()
					{ 
						/*CANCELAR VENTA*/					
																	$('#caja-dialog-confirmacion').dialog({
																	position: ['top',0],
																	modal: true,
																	minWidth: 300,
																	draggable: true,
																	resizable: false,
																	title:"Cancelar venta",
																	open: function(){
																	$(this).empty().append("Estas seguro que deseas cancelar esta venta, no se podrá deshacer esta acción?");  
																 },
																   buttons:
																	[
																	{
																		text:'Si',click: function()
																		{ 
																			
																			
														
																					 $.ajax({
																						type: 'POST',
																						url:'funcionesPv.php',
																						data:{funcion:"cancelarventa",id:id},
																						success: function(resp){  
																							
																							$("#ventas").html(resp); 
																							$('#caja-dialog-confirmacion').dialog("close");
																							$('#caja-dialog').dialog("close");
																						}});//end ajax		
															
															
															
																		}
																   }
																   
																   ,
																   {
																   		
																   		text: 'No',click: function()
																   		{
																   			$(this).dialog("close");
																   		}
																   	}
																   	
																   	]}).height('auto');	//end caja-dialog	
														
														
						/*END CANCELAR VENTA*/
					}
			   }
			   ,
			   {
			   		text: 'Imprimir ticket',click: function()
			   		{
			   			window.open("ticket.php?idventa="+id);
			   			$(this).dialog("close");
			   		}
			   	}
			   ,
			   {
			   		text: 'Salir',click: function()
			   		{
			   			$(this).dialog("close");
			   		}
			   	}
			   	
			   	]}).height('auto');	//end caja-dialog	
		}});//end ajax	
}
//////////////////////////////////////////////////////////////////
function filtramovimientos()
{
	$("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"entradasalidas",registros:$("#registros").val(),inicio:$("#inicio").val(),fin:$("#fin").val(),producto:$("#producto").val(),movimiento:$("#movimiento").val()},
		success: function(resp){ 
			$("#preloader").hide(); 
			$("#movimientosmercancia").html(resp); 
		}});//end ajax	
}
//////////////////////////////////////////
function Limpiacampos()
{
	$("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"entradasalidas",inicio:"",fin:"",producto:"",movimiento:""},
		success: function(resp){ 
			$("#movimientosmercancia").html(resp);  
				$("#inicio").val("");
				$("#fin").val("");
				$("#producto").val("");
				$("#movimiento").val("");
				$("#preloader").hide(); 
		}});//end ajax	
	

}
//////////////////////////////////////////
function eliminaproductocaja(id)
{
	
	
		$('#caja-dialog-confirmacion').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 300,
				draggable: true,
				resizable: false,
				title:"Eliminar producto",
				open: function(){
				$(this).empty().append("Estas seguro que deseas eliminar el producto ?");  
			 },
			   buttons:
				[
				{
					text:'Si',click: function()
					{ 
						
						
	
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		dataType:'json',
		data:{funcion:"eliminaproductocaja",id:id},
		success: function(resp){  
			
			$("#contenidocaja").html(resp[1]); 
			$('#caja-dialog-confirmacion').dialog("close");
		}});//end ajax		
		
		
		
					}
			   }
			   
			   ,
			   {
			   		
			   		text: 'No',click: function()
			   		{
			   			$(this).dialog("close");
			   		}
			   	}
			   	
			   	]}).height('auto');	//end caja-dialog	
	
	
	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function eliminarpago($id)
{
		
		$('#caja-dialog-confirmacion').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 300,
				draggable: true,
				resizable: false,
				title:"Eliminar pago",
				open: function(){
				$(this).empty().append("Estas seguro que deseas eliminar el pago ?");  
			 },
			   buttons:
				[
				{
					text:'Si',click: function()
					{ 
						
							$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		dataType:'json',
		data:{funcion:"eliminarpago",id:$id,total:$("#super-total").val()},
		success: function(resp){ 
			
			$("#cantidad-recibida-hidden").val(resp[1]);
			$("#cantidad-recibida").val(resp[2]);				
			
			$("#pagos-caja").html(resp[0]);
			$("#cantidadpago").val(""); 
			$("#cantidadpago").focus(); 
			
			$("#hidden-referencia").hide();
			$("#referencia").hide();
			
			//alert( $("#super-total").val() );
			$("#pagar-cambio").html(resp[3]);
			//calcularcambio($("#super-total").val());
		
		}});//end ajax		
						
					$(this).dialog("close");
					}
			   }
			   
			   ,
			   {
			   		
			   		text: 'No',click: function()
			   		{
			   			$(this).dialog("close");
			   		}
			   	}
			   	
			   	]}).height('auto');	//end caja-dialog	
	
			
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function agregarpago($total)
{
		var cambio=$("#pagar-cambio").html().replace("Cambio:$","");
		var cambio=cambio.replace("$","");
		if( cambio>0  )
		{
				alert("Con los pagos efectuados se puede completar la venta"); return false;
		}
		
		
		if( $("#cantidadpago").val()=="" /*&&  $("#formapago").val()!=3*/ ){ alert("Ingresa la cantidad para agregar el pago"); $("#cantidadpago").focus(); return false;}
		
		if(  $("#formapago").val()==2 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar el número de cheque para registrar el pago"); return false;
		}
		
		if(  $("#formapago").val()==7 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar la referencia de la transferencia para registrar el pago"); return false;
		}
		
		
		if(  $("#formapago").val()==8 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar la referencia SPEI para registrar el pago"); return false;
		}
		
		if(  $("#formapago").val()==3 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar el número de la tarjeta de regalo para registrar el pago"); return false;
		}
		
		if(  $("#formapago").val()==4 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar el número de baucher para registrar el pago"); return false;
		}
		
		if(  $("#formapago").val()==5 && $("#referencia").val()==""  )
		{
			alert("Debes ingresar el número de baucher para registrar el pago"); return false;
		}
		
		
		if(  $("#formapago").val()==6 && $("#hidencliente-caja").val()==""  )
		{
			alert("Debes seleccionar el cliente para poder registrar un pago a credito"); return false;
		}
		
		
		if(  $("#formapago").val()==3 )
		{
			$.ajax({
			type: 'POST',
			url:'funcionesPv.php',
			data:{funcion:"checatarjetaregalo",numero:$("#referencia").val(),monto: $("#cantidadpago").val()},
			success: function(resp){

					if(isNaN(resp) )
					{
						alert(resp); return false;
						$("#referencia").val("");
					} 
				else{
						$.ajax({
						type: 'POST',
						url:'funcionesPv.php',
						dataType:'json',
						data:{funcion:"pagoscaja",total:$("#super-total").val(),referencia:$("#referencia").val(),monto:resp,idFormapago:$("#formapago").val(),formapago:$("#formapago option:selected").text()},
						success: function(resp){ 
							
							
							$("#pagar-cambio").html("Cambio:"+resp[3]);
							$("#cantidad-recibida").val(resp[2]);			
							$("#cantidad-recibida-hidden").val(resp[1]);	
							
							$("#pagos-caja").html(resp[0]);
							$("#cantidadpago").val(""); 
							$("#cantidadpago").focus(); 
							
							$("#hidden-referencia").hide();
							$("#referencia").hide();
							$("#referencia").val("");
							//calcularcambio($total);
						}});//end ajax	
					}
			}});//end ajax	
		}
		
		
		
		else
		{
			if(  $("#formapago").val()==6 )//pago a credito
			{
				
			$.ajax({
			type: 'POST',
			url:'funcionesPv.php',
			data:{funcion:"checalimitecredito",cliente:$("#hidencliente-caja").val(),monto: $("#cantidadpago").val()},
			success: function(resp){

					if(isNaN(resp) )
					{
						alert(resp); return false;
						$("#referencia").val("");
					} 
				else{
			
								$.ajax({
								type: 'POST',
								url:'funcionesPv.php',
								dataType:'json',
								data:{funcion:"pagoscaja",total:$("#super-total").val(),referencia:$("#referencia").val(),monto:$("#cantidadpago").val(),idFormapago:$("#formapago").val(),formapago:$("#formapago option:selected").text()},
								success: function(resp){ 
									
									$("#pagar-cambio").html("Cambio:"+resp[3]);
									$("#cantidad-recibida").val(resp[2]);
									$("#cantidad-recibida-hidden").val(resp[1]);			
									
									$("#pagos-caja").html(resp[0]);
									$("#cantidadpago").val(""); 
									$("#cantidadpago").focus(); 
									
									$("#hidden-referencia").hide();
									$("#referencia").hide();
									$("#referencia").val("");
									//calcularcambio($total);
			  
								}});//end ajax	
			 		}//end else si tiene limite de credito
				}});//end ajax	
			}//if pago a credito
			else
			{
						$.ajax({
			type: 'POST',
			url:'funcionesPv.php',
			dataType:'json',
			data:{funcion:"pagoscaja",total:$("#super-total").val(),referencia:$("#referencia").val(),monto:$("#cantidadpago").val(),idFormapago:$("#formapago").val(),formapago:$("#formapago option:selected").text()},
			success: function(resp){ 
				
				
				$("#pagar-cambio").html("Cambio:"+resp[3]);
							
				$("#cantidad-recibida").val(resp[2]);
				$("#cantidad-recibida-hidden").val(resp[1]);			
				
				$("#pagos-caja").html(resp[0]);
				$("#cantidadpago").val(""); 
				$("#cantidadpago").focus(); 
				
				$("#hidden-referencia").hide();
				$("#referencia").hide();
				$("#referencia").val("");
				//calcularcambio($total);
			
			}});//end ajax	
			}
		}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function seleccionaformadepago(id)
{
	
	if(id==2 || id==3  || id==7  || id==8      )
	{
		if(id==2){ $("#hidden-referencia").html("Número de cheque:");  $("#cantidadpago").removeAttr("disabled");  }
		if(id==7){ $("#hidden-referencia").html("Referencia transferencia:");  $("#cantidadpago").removeAttr("disabled");  }
		if(id==8){ $("#hidden-referencia").html("Referencia spei:");  $("#cantidadpago").removeAttr("disabled");  }
		
		if(id==3)
		{ 
			 $("#hidden-referencia").html("Número de tarjeta:");
			 //$("#cantidadpago").val("");
			// $("#cantidadpago").attr("disabled","disabled");  
		}
		//if(id==6){ $("#hidden-referencia").html("Fecha limite credito:");  }
		$("#referencia").val("");
		$("#hidden-referencia").show();
		$("#referencia").show();
	}
	else
	{
		if(id==4 || id==5 || id==6  ) //tarjeta de T.debito o T.credito o credito
	{
			
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"calculapago",total:$("#super-total").val()},
		success: function(resp){      	
			
			$("#cantidadpago").val(resp);
			$("#cantidadpago").attr("disabled","disabled");  
			if(id==6)
			{ 
				
				$("#hidden-referencia").hide();
				$("#referencia").hide();
			}
			
			if(id==4)
			{ 
				$("#hidden-referencia").html("Número de  baucher:");    
				$("#referencia").val("");
				$("#hidden-referencia").show();
				$("#referencia").show();
			}
			if(id==5)
			{ 
				$("#hidden-referencia").html("Número de  baucher:");    
				$("#referencia").val("");
				$("#hidden-referencia").show();
				$("#referencia").show();
			}
			
			
		 }});//end ajax	
			
	}
	else{	
			$("#cantidadpago").removeAttr("disabled");
			$("#hidden-referencia").hide();
			$("#referencia").hide();
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cargaProducto($idProducto)
{
$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaProducto",idProducto:$idProducto ,almacen: $("#caja-almacen").val()},
		success: function(resp){  $("#detalle-producto").html(resp);       }});//end ajax		
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cargaLineas($idFamilia)
{
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaLineas",idFamilia:$idFamilia},
		success: function(resp){  $("#span-lineas").html(resp);       }});//end ajax	
}

function cargaLineas2($idFamilia)
{
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaLineas2",idFamilia:$idFamilia},
		success: function(resp){  $("#span-lineas").html(resp);       }});//end ajax	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cargaFamilias($idDepartamento)
{
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaFamilias",idDepartamento:$idDepartamento},
		success: function(resp){  $("#span-familias").html(resp);       }});//end ajax	
}

function cargaFamilias2($idDepartamento)
{
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaFamilias2",idDepartamento:$idDepartamento},
		success: function(resp){  $("#span-familias").html(resp);       }});//end ajax	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cargaProductos($idLinea)
{
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaProductos",idLinea:$idLinea},
		success: function(resp){  $("#span-productos").html(resp);       }});//end ajax	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function calcularcambio(total)
{
	//alert($("#cantidad-recibida").val());
	var cr=$("#cantidad-recibida-hidden").val().replace("$","");
	    cr=cr.replace(",","");
	    
	    
	var resultado=cr-total;	
	
	//alert(resultado);
	
	if(resultado>0)
	{
		$("#pagar-cambio").html("Cambio:$"+parseFloat(resultado).toFixed(2));
	}
	else
	{
		$("#pagar-cambio").html("Cambio:$0.00");
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cambiaCantidad(id,descuento,tipodescuento)
{
		if($("#cantidadarticulo").val()=="")
		{
			$("#cantidadarticulo").val($("#cantidadanterior").val());	
		}
		
		$("#boton-pagar").attr("disabled","disabled");
		
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		dataType:'json',
		data:{funcion:"cambiarcantidad",id:id,cantidad:$("#cantidadarticulo").val(),descuento:descuento,tipodescuento:tipodescuento},
		success: function(resp){  
			
			$("#contenidocaja").html(resp[1]); 
			$("#hidensearch-producto").val("");
			$("#search-producto").val("");
			
			$("#boton-pagar").removeAttr("disabled");
		}});//end ajax		
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function editArticulo($id)
{
	
	
	 $.ajax({
		type: 'POST',
		url:'editaArticulocompra.php',
		data:{idArticulo:$id,almacen: $("#caja-almacen").val()},
		success: function(resp){  
		$('#caja-dialog').empty().append(resp);	
		

	
	$('#caja-dialog').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 600,
				draggable: true,
				resizable: false,
				title:"Editar articulo",
				open: function(){
					
						$("#cantidadarticulo").focus();
						
						$('#cantidadarticulo').keypress(function(event){
 
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13')
	{
		
		$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"checarExistencia",idArticulo:$("#idproducto-e").val(),cantidad:$("#cantidadarticulo").val(),almacen: $("#caja-almacen").val()},
		success: function(resp){
			if(!isNaN(resp))
			{  
			cambiaCantidad( $("#idproducto-e").val(), $("#descuento-producto").val(), $("#tipodescuento").val() );
			$('#caja-dialog').dialog("close");	
			}else{alert(resp);}
		}});//end ajax		

	}
	
 
});
						
	//alert("a");
					
				}
				,
				buttons:
				[			
			   {
			   		text: 'Aceptar',click: function()
			   		{
				   		
				   		
				   			$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"checarExistencia",idArticulo:$("#idproducto-e").val(),cantidad:$("#cantidadarticulo").val(),almacen: $("#caja-almacen").val()},
		success: function(resp){
			if(!isNaN(resp))
			{  
			cambiaCantidad( $("#idproducto-e").val(), $("#descuento-producto").val(), $("#tipodescuento").val() );
			$('#caja-dialog').dialog("close");	
			}else{alert(resp);}
		}});//end ajax		

				   		
				   		
			   		}
			   	}
			 ]}).height('auto');	//end caja-dialog
	
	}});//end ajax		
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function buscarProducto()
{
	 $.ajax({
		type: 'POST',
		url:'articulo.php',
		data:{},
		success: function(resp){   
	$('#caja-dialog').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 750,
				draggable: true,
				resizable: false,
				title:"Buscar art&iacute;culo",
				open: function()
				{
					$(this).empty().append(resp);  
				},
				buttons:
				[
				{
					text:'Agregar a caja',click: function()
					{ 
							
							
							
							if(typeof($("#hidden-idproducto")) != "undefined")
							{
								if(isNaN($("#hidden-idproducto").val()))
								{
									alert("No has seleccionado el articulo para agregarlo a caja"); return false;
								}
							}
							
					/*
					$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"checarExistencia",idArticulo:$("#hidden-idproducto").val(),cantidad:1,almacen: $("#caja-almacen").val()},
		success: function(resp){
			if(!isNaN(resp))
			{  
			*/
			
			$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		dataType:'json',
		data:{funcion:"agregaraCaja",idArticulo:$("#hidden-idproducto").val(),almacen:$("#caja-almacen").val()},
		success: function(resp){  
			
			
			if(!isNaN(resp[0]))
							{  
									$("#contenidocaja").html(resp[1]); 
									$("#hidensearch-producto").val("");
									$("#search-producto").val("");
									$('#caja-dialog').dialog('close');
									$("#preloader").hide();
									$("#search-producto").focus();
							}
							else{ alert(resp[0]); $("#preloader").hide(); } 
			
		
			
			
		}});//end ajax		
			
		/*	
			}else{alert(resp);}
		}});//end ajax		
		*/			
							
							 
							
							//alert($("#hidden-idproducto").val());
					}
			   }
			   
			   	
			   	]}).height('auto');	//end caja-dialog	
	}});//end ajax		  		
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function tipoDocumento(id)
{
	if(id==2)
	{	
		$.ajax({				
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"checatimbres"},
		success: function(resp){ 
			if(resp==0)
			{
				$("#labelrfc").show();
				$("#selectrfc").show();
			}
			else
			{
				alert("No tienes timbres de factura disponibles, se han hagotado");
			}
		}});	
	}
	else
	{
		$("#labelrfc").hide();
		$("#selectrfc").hide();
	}	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function pagar($total,$totalimpuestos,$simple)
{
	 
	 
	 
	 
	 
	 $("#boton-pagar").attr("disabled","disabled");
	 //simple
	 if($simple==0)
	 {
	 //end simple	
	 	
	 $.ajax({
		type: 'POST',
		url:'pagar.php',
		data:{total:$total,impuestos:$totalimpuestos},
		success: function(resp){   
	$('#caja-dialog').dialog({
				modal: true,
				minWidth: 600,
				draggable: true,
				resizable: false,
				title:"Pagar",
				closeOnEscape: false,
				open: function()
				{
					$(this).empty().append(resp);
					 $(".ui-dialog-titlebar-close").hide();  
				},
				buttons:
				[
				{
					id:'pagar-btn',text:'Pagar',click: function()
					{ 
							
							var cantidadpagada=String($("#cantidad-recibida-hidden").val());
							cantidadpagada=cantidadpagada.replace("$","");
							cantidadpagada=cantidadpagada.replace(",","");

							if (  parseFloat(cantidadpagada) < parseFloat($("#super-total").val())  )
							{
								alert("No has cubierto el total de la venta"); return false;
							}
							
							if (  cantidadpagada==""  )
							{
								alert("No has cubierto el total de la venta"); return false;
							}
							
							var cambio=String($("#pagar-cambio").html());
							cambio=cambio.replace("Cambio:$","");
							cambio=cambio.replace(",","");
							
						//alert( $("#rfc").val() );
						$("#pagar-btn").button("disable");
						$("#loaderventa").show();
							
							$.ajax({
							type: 'POST',
							url:'funcionesPv.php',
							//dataType:'json',
							data:{funcion:"guardarVenta",pagoautomatico:0,impuestos:$("#total-impuestos").val(),idFact:$("#rfc").val(),sucursal:$("#caja-sucursal").val(),almacen:$("#caja-almacen").val(),cambio:cambio,documento:$("#documento").val(),monto:($total),cliente:$("#hidencliente-caja").val(),empleado:$("#idvendedor").val()},
							success: function(resp){ 
								$("#boton-pagar").removeAttr("disabled");
								var errorventa=resp.split("xd-dx");
								
								var errorfacturar=0;
								if(errorventa.length<3)
								{	
									var err=errorventa[0].split("</b>");
									
									if(err.length>1)
									{
										var idventa=err[1];
										errorfacturar=1;  //facturacion con errores
									}
									else
									{ 	
										var idventa=errorventa[0];
										//no se solicito facturr
									}
								}
								else
								{
										var idventa=errorventa[6];
										errorfacturar=2; //facturacion sin errores
								}
							
								window.open("ticket.php?idventa="+idventa);
								/*ingresar datos facturcion*/
							if(errorfacturar!=0) //si se facturo 
							{
								
							
							$.ajax({
							type: 'POST',url:'funcionesPv.php',data:{funcion:"guardarFacturacion",idventa:idventa,idFact:$("#rfc").val(),datos:resp,monto:($total),cliente:$("#hidencliente-caja").val()},success: function(resp){  
								
								if(isNaN(resp))
								{
									alert(resp);
								}
								
								}});//end ajax	
							}	//end guardar facturacion 
								/*end ingresar datos facturacion*/
								$("#loaderventa").hide();
								$('#caja-dialog').dialog('close');
								$("#boton-pagar").removeAttr("disabled");
								alert("Has registrado la venta con exito");
								window.location="../../modulos/punto_venta/index.php";
							
							}});//end ajax	
					
					}//btn pagar
			   }
			   ,
			   {
			   		text: 'Salir',click: function()
			   		{
			   			$("#boton-pagar").removeAttr("disabled");
			   			$('#caja-dialog').dialog('close');
			   			$("#loaderventa").hide();
			   		}
			   	}
			   	
			   	]}).height('auto');	//end caja-dialog	
	}});//end ajax		  	
	 // });// end btn pagar
	 
	  //simple
	} else {
	
	
	
	
	
	
	$.ajax({
							//$("#loaderventa").show();
							type: 'POST',
							url:'funcionesPv.php',
							//dataType:'json',
							data:{funcion:"guardarVenta",pagoautomatico:1,impuestos:$totalimpuestos,idFact:$("#rfc").val(),sucursal:$("#caja-sucursal").val(),almacen:$("#caja-almacen").val(),cambio:0,documento:$("#documento").val(),monto:$total,cliente:$("#hidencliente-caja").val(),empleado:$("#idvendedor").val()},
							success: function(resp){ 
								$("#boton-pagar").removeAttr("disabled");
								var errorventa=resp.split("xd-dx");
								
								
								var errorfacturar=0;
								if(errorventa.length<3)
								{	
									var err=errorventa[0].split("</b>");
									if(err.length>1)
									{
										var idventa=err[1];
										errorfacturar=1;  //facturacion con errores
									}
									else
									{
										var idventa=errorventa[0];
										//no se solicito facturr
									}
								}
								else
								{
										var idventa=errorventa[6];
										errorfacturar=2; //facturacion sin errores
								}
							//	alert(idventa);
								window.open("ticket.php?idventa="+idventa);
								/*ingresar datos facturcion*/
							if(errorfacturar!=0) //si se facturo 
							{
								
							
							$.ajax({
							type: 'POST',url:'funcionesPv.php',data:{funcion:"guardarFacturacion",idventa:idventa,idFact:$("#rfc").val(),datos:resp,monto:($total),cliente:$("#hidencliente-caja").val()},success: function(resp){  
								
								if(isNaN(resp))
								{
									alert(resp);
								}
								$("#boton-pagar").removeAttr("disabled");
								alert("Has registrado la venta con exito");
								window.location="../../modulos/punto_venta/index.php";
								
								}});//end ajax	
							}	//end guardar facturacion 
							else{	
								
								/*end ingresar datos facturacion*/

								//$('#caja-dialog').dialog('close');
								$("#boton-pagar").removeAttr("disabled");
								alert("Has registrado la venta con exito");
								window.location="../../modulos/punto_venta/index.php";
								}
							}});//end ajax	
	
	
	
	}
	 //end simple
	 
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function showclock(){
if (!document.layers&&!document.all&&!document.getElementById)
return;

 var Digital=new Date();
 var hours=Digital.getHours();
 var minutes=Digital.getMinutes();
 var seconds=Digital.getSeconds();

var dn="PM";
if (hours<12)
dn="AM";
if (hours>12)
hours=hours-12;
if (hours==0)
hours=12;

 if (minutes<=9)
 minutes="0"+minutes;
 if (seconds<=9)
 seconds="0"+seconds;
//change font size here to your desire
myclock=""+hours+":"+minutes+":";
 +seconds+" "+dn+"";
if (document.layers){
document.layers.liveclock.document.write(myclock);
document.layers.liveclock.document.close();
}
else if (document.all)
liveclock.innerHTML=myclock;
else if (document.getElementById)
document.getElementById("liveclock").innerHTML=myclock;
setTimeout("showclock()",1000);
 }
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
