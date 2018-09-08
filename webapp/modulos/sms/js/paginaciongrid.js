function buscapromocion()
{
	
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=' o.fechaCreacion between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" ';
		
	$.ajax(
	{
		url:'../../../../modulos/sms/funcionesBD/grid.php',
		type: 'POST',
		data: {funcion:"gridofertas",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}



function paginacionGridOferta(pagina,filtro)
{
	$.ajax(
	{
		url:'../../../sms/funcionesBD/grid.php',
		type: 'POST',
		data: {funcion: "gridofertas", pagina: pagina,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function cargaInterfaceAgregar ()
{
	window.location="../offer/form.php";
}