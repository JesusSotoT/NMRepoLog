function buscapromocion()
{
	
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=" o.fechaCreacion between '"+$("#finicio").val()+"' and '"+$("#ffin").val()+"' ";
		
	$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php',
		type: 'POST',
		data: {funcion:"gridlidquidacion",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}


function LiquidarCliente(id,$oferta,$ruta,cantidad,liquidada)
{
		
		if(liquidada=="Liquidada")
		{
		
		$('#dialog').dialog({
		modal: true,
		minWidth: 450,
		draggable: true,
		resizable: false,
		title:"Liquidar cliente",
		open: function(){	
			//var idProveedor=$('input[type="text"]:first').first().val();
		$.ajax({
		type: 'POST',
		url:'../../../../modulos/sms/views/configrutas/liquidarcliente.php',
		data:{id:id,cantidad:cantidad},
		success: function(contenido)
		{   
			$('#dialog').empty().append(contenido);
		}});
		},//open
		buttons:[{text: 'Salir',click: function(){$('#dialog').dialog('close');}}]}).height('auto');	
			
		}
		else {
		
		$('#dialog').dialog({
		modal: true,
		minWidth: 450,
		draggable: true,
		resizable: false,
		title:"Liquidar cliente",
		open: function(){	
			//var idProveedor=$('input[type="text"]:first').first().val();
		$.ajax({
		type: 'POST',
		url:'../../../../modulos/sms/views/configrutas/liquidarcliente.php',
		data:{id:id,cantidad:cantidad},
		success: function(contenido)
		{   
			$('#dialog').empty().append(contenido);
		
	
		}});
		},//open
		buttons:[{text:'Aceptar',click: function(){ 
		
		if ($('#factura').is(':checked')){var factura=1;}else{var factura=0;}
		
		$.ajax(
		{
		url:'../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php',
		type: 'POST',
		data: {funcion:"liquidacliente",ruta:$ruta,id:id,cantidad:$("#cantidad").val(),comentarios:$("#comentarios").val(),factura:factura},
		success: function(callback)
		{	
     		alert("Has liquidado el cliente con éxito");$('#dialog').dialog('close');
     		window.location='../../views/configrutas/form_liquidacion.php?oferta='+$oferta+'&idr='+$ruta;
		}
		});
			
					
		}},{text: 'Salir',click: function(){$('#dialog').dialog('close');}}]}).height('auto');	
		
		}
}


function paginacionGridOfertaruta(pagina,filtro)
{
	$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php',
		type: 'POST',
		data: {funcion:"gridlidquidacion",pagina:pagina,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}



function paginacionGridOfertaruta2(pagina,idOferta)
{
$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php',
		type: 'POST',
		data: {funcion:"gridliquidacionruta",pagina:pagina,oferta:idOferta},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

