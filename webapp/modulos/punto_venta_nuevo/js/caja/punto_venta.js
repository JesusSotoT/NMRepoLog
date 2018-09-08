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
		success: function(resp){
			$("#detalle-producto").html(resp);
			alert("Has ingresado la mercancia con exito");
			$("#cantidad").val("");
			$("#producto").val("");
			$("#sucursal").val("");
			$("#proveedor").val("");
			$("#costo").val("");
			$("#preloader2").hide();
		 }});//end ajax	
}

function loadproductos($iddepa,$idfamilia,$idLinea) {
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"productosexistencias",iddepa:$iddepa,idfamilia:$idfamilia,idlinea:$idLinea},
		success: function(resp){  $("#span-productos").html(resp);       }});//end ajax	
}


function cargaexistencias(id) {
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
	data:{funcion:"ventas",estatus:$("#estatus").val(),inicio:$("#inicio").val(),fin:$("#fin").val(),cliente:$("#cliente").val(),sucursal:$("#sucursal").val(),vendedor:$("#vendedor").val()},
	success: function(resp){
		$("#preloader").hide();
		$("#ventas").html(resp);
		carga();
	}});//end ajax
}

function Limpiaventas()
{
	$("#preloader").show();
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"ventas",inicio:$.datepicker.formatDate('yy-mm-dd', new Date()),fin:$.datepicker.formatDate('yy-mm-dd', new Date()),cliente:"",sucursal:"",vendedor:"",estatus:""},
		success: function(resp){
			$("#preloader").hide();
			$("#ventas").html(resp);
				$("#inicio").val($.datepicker.formatDate('yy-mm-dd', new Date()));
				$("#fin").val($.datepicker.formatDate('yy-mm-dd', new Date()));
				$("#vendedor").val("");
				$("#sucursal").val("");
				$("#cliente").val("");
			carga();
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
						$("#formapago").val('1');
						
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
	
	if(  $("#formapago").val()==4 && $("#referencia").val()=="")
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

	if($("#formapago").val() > 3 && $("#cantidadpago").val() > $('#cantidad-recibida').val().replace('$','').replace(',',''))
	{
		alert('El pago no debe ser superior al total'); return false;
			//alert($("#formapago").val()+" / "+$("#cantidadpago").val()+" / "+$('#cantidad-recibida').val().replace('$','')); return false;

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
		//	$("#cantidadpago").attr("disabled","disabled");  
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

function cargaLineas2($iddepa,$idFamilia)
{
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaLineas2",iddepa:$iddepa,idFamilia:$idFamilia},
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
function filtraProveedor($idProd){
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"proveedores",idProd:$idProd},
		success: function(resp){  $("#span-proveedor").html(resp);       }});//end ajax	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargaCosto($idprov,$idProd){
	$.ajax({
		type: 'POST',
		url:'funcionesPv.php',
		data:{funcion:"cargaCosto",idprov:$idprov,idProd:$idProd},
		success: function(resp){ $("#costo").val(resp);       }});//end ajax	
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
					var precio1 = $('#subtitulo').html().split("Precio:$");
					var val=0;
					if($("#tipodescuento").val() == '%' && parseFloat($("#descuento-producto").val()) > '100')
					{
						val=1;
					}

					var cantidad=0;
					if($("#cantidadarticulo").val())
					{
						cantidad2 = $("#cantidadarticulo").val();
					}
					else
					{
						cantidad2 = $("#cantidadarticulo").attr("placeholder");
					}

					if($("#tipodescuento").val() == '$' && parseFloat($("#descuento-producto").val()) > parseFloat(precio1[1]*cantidad2))
					{
						val=1;
					}
					
					if(!val)
					{
						cambiaCantidad( $("#idproducto-e").val(), $("#descuento-producto").val(), $("#tipodescuento").val() );
						$('#caja-dialog').dialog("close");	
					}
					else
					{
						alert("El descuento es mayor a la cantidad.")
					}
					
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

function pagar($total,$totalimpuestos,$simple,ffpp,s_id,nombre)
{
	$('#total input').css('display','none');
	$('#llve').css('display','block');
	s_id= $("#sp_s_id").val();
	sp_name= $("#sp_name").val();
	
	if(s_id>0){
		tit='Pagar - Venta suspendida';
		ss_id=s_id;
	}else{
		tit='Pagar';
		ss_id=0;
	}
	nombre = $("#cliente-caja").val();
	
	
	
	
	
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
					//console.log(resp);
					$(this).empty().append(resp);
					if((typeof ffpp)=='object'){
						
						$.each(ffpp, function( key, value ){
							$("#formapago option").each(function( index ) {
								dosaca=value.split('_');
								
								if(dosaca[0]=='Tarjeta de cru00e9dito'){
									dosaca[0]='Tarjeta de crédito';
								}
								if(dosaca[0]=='Cru00e9dito'){
									dosaca[0]='Crédito';
								}
								if($(this).text()==dosaca[0]){
									$(this).attr('selected','selected');
									$(this).change();
									$('#cantidadpago').val(dosaca[1]);
									$('#referencia').val(dosaca[2]);
									$("#btn-agregarpago").trigger("click");
								}
							});
						});

					}

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

						var codigo = $('#tb1238-u',window.parent.document).children('#frurl').contents().find('#codigo').val();

						if (  parseFloat(cantidadpagada) < parseFloat($("#super-total").val())  )
						{
							alert("No has cubierto el total de la venta"); return false;
						}
						
						if (  cantidadpagada==""  )
						{
							alert("No has cubierto el total de la venta"); return false;
						}

						if(codigo != '')
						{
							$.ajax({
								url: '../restaurantes/ajax.php?c=productocomanda&f=borrarProductoTemporal',
								type: 'POST',
								dataType: 'json',
								async:true,
								data: {'codigo': codigo},
							})
							.error(function(data) {
								alert('No se pudo borrar la comanda-p');
							})
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
							dataType:'json',
							//krmn
							data:{funcion:"guardarVenta",pagoautomatico:0,impuestos:$("#total-impuestos").val(),idFact:$("#rfc").val(),sucursal:$("#caja-sucursal").val(),almacen:$("#caja-almacen").val(),cambio:cambio,documento:$("#documento").val(),monto:($total),cliente:$("#hidencliente-caja").val(),empleado:$("#idvendedor").val(),ss_id:ss_id},
							success: function(resp){
								console.log(resp);
								if(resp.success=='500'){
									alert(resp.mensaje);
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								if(resp.success=='-1'){
									alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								if(resp.success=='3'){
									alert('Venta realizada con exito.');
									window.open("ticket.php?idventa="+resp.idVenta);
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								/* NUEVA FACTURACION Y RESPUESTA DE VENTA
								================================================ */
								if(resp.success==0 || resp.success==5){
									if(resp.success==0){
										alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);
									}
									window.open("ticket.php?idventa="+resp.idVenta);
									$.ajax({
										type: 'POST',
										url:'funcionesPv.php',
										data:{funcion:"pendienteFacturacion",
										azurian:resp.azurian,
										idFact:$("#rfc").val(),
										monto:($total),
										cliente:$("#hidencliente-caja").val(),
										trackId:resp.trackId,
										idVenta:resp.idVenta},
										success: function(resp){  
											window.location="../../modulos/punto_venta_nuevo/index.php";
										}
									});
								}
								if(resp.success=='1'){
									uid=resp.datos.UUID;
									correo=resp.correo;
									$.ajax({
										type: 'POST',
										url:'funcionesPv.php',
										data:{funcion:"guardarFacturacion",
										UUID:uid,
										noCertificadoSAT:resp.datos.noCertificadoSAT,
										selloCFD:resp.datos.selloCFD,
										selloSAT:resp.datos.selloSAT,
										FechaTimbrado:resp.datos.FechaTimbrado,
										idComprobante:resp.datos.idComprobante,
										idFact:resp.datos.idFact,
										idVenta:resp.datos.idVenta,
										noCertificado:resp.datos.noCertificado,
										tipoComp:resp.datos.tipoComp,
										trackId:resp.datos.trackId,
										monto:($total),
										cliente:$("#hidencliente-caja").val(),
										idRefact:0,
										azurian:resp.azurian},
										success: function(resp){
											window.open('../../modulos/facturas/'+uid+'.pdf');
											$.ajax({
												type: 'POST',
												url:'funcionesPv.php',
												data:{funcion:"envioFactura",uid:uid,correo:correo},
												success: function(resp){  
													
												}
											});
											console.log(resp);
											$("#loaderventa").hide();
											$('#caja-dialog').dialog('close');
											$("#boton-pagar").removeAttr("disabled");
											alert('Has registrado la venta con exito');
											window.location="../../modulos/punto_venta_nuevo/index.php";
										}
									});
}
								//alert("Has registrado la venta con exito");
								//window.location="../../modulos/punto_venta_nuevo/index.php";
								return false;
								// FIN NUEVA FACT
								
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
									alert("Has registrado la venta con exito");
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
								//alert("Has registrado la venta con exito");
								window.location="../../modulos/punto_venta_nuevo/index.php";
								
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
				},
				{
					text: 'Cancelar',click: function()
					{
						if(confirm('Esta seguro de cancelar?'))
						{
							$.ajax(
							{
								type: 'POST',
								url:'funcionesPv.php',
								//dataType:'json',
								data:
								{
									funcion:"cancelarVentaActual",
									success: function(resp)
									{ 
										$("#boton-pagar").removeAttr("disabled");
										$("#loaderventa").hide();
										$('#caja-dialog').dialog('close');
										window.location="../../modulos/punto_venta_nuevo/index.php";
									}
								}
							});//end ajax	
						}
					}
				},
				{
					text: 'Suspender',click: function()
					{ 
						if($("#hidencliente-caja").val()==""){
							alert("Nesesita seleccionar un cliente para suspender la venta!");
							return false;
						}
						
						var cantidadpagada=String($("#cantidad-recibida-hidden").val());
						cantidadpagada=cantidadpagada.replace("$","");
						cantidadpagada=cantidadpagada.replace(",","");

						var cambio=String($("#pagar-cambio").html());
						cambio=cambio.replace("Cambio:$","");
						cambio=cambio.replace(",","");

						$("#pagar-btn").button("disable");
						$("#loaderventa").show();
						
						$.ajax({
							type: 'POST',
							url:'funcionesPv.php',
							//dataType:'json',
							data:{funcion:"suspenderVenta",pagoautomatico:0,impuestos:$("#total-impuestos").val(),idFact:$("#rfc").val(),sucursal:$("#caja-sucursal").val(),almacen:$("#caja-almacen").val(),cambio:cambio,documento:$("#documento").val(),monto:($total),cliente:$("#hidencliente-caja").val(),empleado:$("#idvendedor").val(),totalimpuestos:$totalimpuestos,s_id:s_id,nombre:nombre},
							success: function(resp){ 

								$("#boton-pagar").removeAttr("disabled");
								alert('La venta se suspendio correctamente');
								/*ingresar datos facturcion*/
								$("#loaderventa").hide();
								$('#caja-dialog').dialog('close');
								$("#boton-pagar").removeAttr("disabled");
								window.location="../../modulos/punto_venta_nuevo/index.php";
							}});//end ajax	
						
					}//btn suspender
				}
				


			   	]}).height('auto');	//end caja-dialog	
	}});//end ajax		  	
	 // });// end btn pagar

	  //simple
	} else {
		
		var codigo = $('#tb1238-u',window.parent.document).children('#frurl').contents().find('#codigo').val();
		
		
		if(codigo != '')
		{
			$.ajax({
				url: '../restaurantes/ajax.php?c=productocomanda&f=borrarProductoTemporal',
				type: 'POST',
				dataType: 'json',
				async:true,
				data: {'codigo': codigo},
			})
			.error(function(data) {
				alert('No se pudo borrar la comanda-p');
			})
		}
		
		$.ajax({
							//$("#loaderventa").show();
							type: 'POST',
							url:'funcionesPv.php',
							dataType:'json',
							data:{funcion:"guardarVenta",pagoautomatico:1,impuestos:$totalimpuestos,idFact:$("#rfc").val(),sucursal:$("#caja-sucursal").val(),almacen:$("#caja-almacen").val(),cambio:0,documento:$("#documento").val(),monto:$total,cliente:$("#hidencliente-caja").val(),empleado:$("#idvendedor").val()},

							
							success: function(resp){ 
								console.log(resp);
								if(resp.success=='500'){
									alert(resp.mensaje);
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								if(resp.success=='-1'){
									alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								if(resp.success=='3'){
									alert('Venta realizada con exito.');
									window.open("ticket.php?idventa="+resp.idVenta);
									window.location="../../modulos/punto_venta_nuevo/index.php";
									return false;	
								}
								/* NUEVA FACTURACION Y RESPUESTA DE VENTA
								================================================ */
								if(resp.success==0 || resp.success==5){
									if(resp.success==0){
										alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);
									}
									window.open("ticket.php?idventa="+resp.idVenta);
									$.ajax({
										type: 'POST',
										url:'funcionesPv.php',
										data:{funcion:"pendienteFacturacion",
										azurian:resp.azurian,
										idFact:$("#rfc").val(),
										monto:($total),
										cliente:$("#hidencliente-caja").val(),
										trackId:resp.trackId,
										idVenta:resp.idVenta},
										success: function(resp){  
											window.location="../../modulos/punto_venta_nuevo/index.php";
										}
									});
								}
								if(resp.success=='1'){
									uid=resp.datos.UUID;
									correo=resp.correo;
									$.ajax({
										type: 'POST',
										url:'funcionesPv.php',
										data:{funcion:"guardarFacturacion",
										UUID:uid,
										noCertificadoSAT:resp.datos.noCertificadoSAT,
										selloCFD:resp.datos.selloCFD,
										selloSAT:resp.datos.selloSAT,
										FechaTimbrado:resp.datos.FechaTimbrado,
										idComprobante:resp.datos.idComprobante,
										idFact:resp.datos.idFact,
										idVenta:resp.datos.idVenta,
										noCertificado:resp.datos.noCertificado,
										tipoComp:resp.datos.tipoComp,
										trackId:resp.datos.trackId,
										monto:($total),
										cliente:$("#hidencliente-caja").val(),
										idRefact:0,
										azurian:resp.azurian},
										success: function(resp){
											window.open('../../modulos/facturas/'+uid+'.pdf');
											$.ajax({
												type: 'POST',
												url:'funcionesPv.php',
												data:{funcion:"envioFactura",uid:uid,correo:correo},
												success: function(resp){  
													
												}
											});
											console.log(resp);
											//$("#loaderventa").hide();
											//$('#caja-dialog').dialog('close');
											//$("#boton-pagar").removeAttr("disabled");
											alert('Has registrado la venta con exito');
											window.location="../../modulos/punto_venta_nuevo/index.php";
										}
									});
}
								//alert("Has registrado la venta con exito");
								//window.location="../../modulos/punto_venta_nuevo/index.php";
								return false;
								// FIN NUEVA FACT

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
										window.location="../../modulos/punto_venta_nuevo/index.php";
										
								}});//end ajax	
							}	//end guardar facturacion 
							else{	
								
								/*end ingresar datos facturacion*/

								//$('#caja-dialog').dialog('close');
								$("#boton-pagar").removeAttr("disabled");
								alert("Has registrado la venta con exito");
								window.location="../../modulos/punto_venta_nuevo/index.php";
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
myclock=""+hours+":"+minutes+":";+seconds+" "+dn+"";
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

$(".ui-button" ).click(function() {
	$('#llve').css('display','none');
	$('#total input').css('display','block');
});

function carga(){
	$(document).ready(function () {
		$('#orden').jTPS( {perPages:[5,12,15,50,'TODO'],scrollStep:1,scrollDelay:30,
			clickCallback:function () {    
                                        // target table selector
                                        var table = '#orden';
                                        // store pagination + sort in cookie
                                        document.cookie = 'jTPS=sortasc:' + $(table + ' .sortableHeader').index($(table + ' .sortAsc')) + ',' +
                                        'sortdesc:' + $(table + ' .sortableHeader').index($(table + ' .sortDesc')) + ',' +
                                        'page:' + $(table + ' .pageSelector').index($(table + ' .hilightPageSelector')) + ';';
                                    }
                                });
                        // reinstate sort and pagination if cookie exists
                        var cookies = document.cookie.split(';');
                        for (var ci = 0, cie = cookies.length; ci < cie; ci++) {
                        	var cookie = cookies[ci].split('=');
                        	if (cookie[0] == 'jTPS') {
                        		var commands = cookie[1].split(',');
                        		for (var cm = 0, cme = commands.length; cm < cme; cm++) {
                        			var command = commands[cm].split(':');
                        			if (command[0] == 'sortasc' && parseInt(command[1]) >= 0) {
                        				$('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click();
                        			} else if (command[0] == 'sortdesc' && parseInt(command[1]) >= 0) {
                        				$('#orden .sortableHeader:eq(' + parseInt(command[1]) + ')').click().click();
                        			} else if (command[0] == 'page' && parseInt(command[1]) >= 0) {
                        				$('#orden .pageSelector:eq(' + parseInt(command[1]) + ')').click();
                        			}
                        		}
                        	}
                        }
                        // bind mouseover for each tbody row and change cell (td) hover style
                        $('#orden tbody tr:not(.stubCell)').bind('mouseover mouseout',
                        	function (e) {
                                        // hilight the row
                                        e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                                    }
                                    );
                    });
}
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////