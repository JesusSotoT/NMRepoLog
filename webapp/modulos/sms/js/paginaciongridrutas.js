function paginacionGridOfertaruta(pagina,filtro)
{
	
	$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridruta.php',
		type: 'POST',
		data: {funcion:"gridofertasrutas",pagina:pagina,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function ListaRutas(idOferta,pagina)
{
//alert(idOferta);
$("fieldset").hide();

$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridruta.php',
		type: 'POST',
		data: {funcion:"gridrutas",pagina:pagina,oferta:idOferta},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function confiruta(idOferta)
{ 
	window.location='../../funcionesBD/confirutas.php?a='+idOferta;
}

function buscapromocion()
{
if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=' o.fechaCreacion between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" ';
		
	$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/gridruta.php',
		type: 'POST',
		data: {funcion:"gridofertasrutas",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}
