function paginacionGridOferta(pagina)
{
	$.ajax(
	{
		url:'../../../modulos/sms/funcionesBD/grid.php',
		type: 'POST',
		data: {funcion: "gridofertas", pagina: pagina},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}
