function buscacorte()
{
	
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=' fechainicio between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" ';
		
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/grid_corte.php',
		type: 'POST',
		data: {funcion:"grid_corte",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}

function buscacxp()
{
	
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" ';
			
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/gridP.php',
		type: 'POST',
		data: {funcion:"gridcxp",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}


function buscacxc()
{
	
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	
	var filtro=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" ';
		
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/gridC.php',
		type: 'POST',
		data: {funcion:"gridcxc",pagina:1,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}

function limpiafiltroscortes()
{
	$("#finicio").val("");
	$("#ffin").val("");
	
	$("#preloader").show();	
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/grid_corte.php',
		type: 'POST',
		data: {funcion:"grid_corte",pagina:1,filtro:1},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}

function limpiafiltroscxp()
{
	$("#finicio").val("");
	$("#ffin").val("");
	
	$("#preloader").show();
	
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/gridP.php',
		type: 'POST',
		data: {funcion:"gridcxp",pagina:1,filtro:1},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}

function limpiafiltroscxc()
{
	$("#finicio").val("");
	$("#ffin").val("");
	
	$("#preloader").show();
	
	$.ajax(
	{
		url:'../../../../modulos/punto_venta/funcionesBD/gridC.php',
		type: 'POST',
		data: {funcion:"gridcxc",pagina:1,filtro:1},
		success: function(callback)
		{	
     		$("#grid").html(callback);
     		$("#preloader").hide();
		}
	});
}
////////////////////////////////////////////////////////////
function paginacionGridCxp(pagina,filtro)
{
	$.ajax(
	{
		url:'../../../punto_venta/funcionesBD/gridP.php',
		type: 'POST',
		data: {funcion: "gridcxp", pagina: pagina,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function cargaCxp()
{
	window.location="../cxp/cuenta.php";
}
////////////////////////////////////////////////////////////
function paginacionGridCxc(pagina,filtro)
{
	$.ajax(
	{
		url:'../../../punto_venta/funcionesBD/gridC.php',
		type: 'POST',
		data: {funcion: "gridcxc", pagina: pagina,filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function cargaCxc()
{
	window.location="../cxc/cuenta.php";
}
////////////////////////////////////////////////////////////
function paginacionGridCortes(pagina,filtro)
{
	$.ajax(
	{
		url:'../../../punto_venta/funcionesBD/grid_corte.php',
		type: 'POST',
		data: {funcion: "grid_corte", pagina: pagina, filtro:filtro},
		success: function(callback)
		{	
     		$("#grid").html(callback);
		}
	});
}

function agregaCorte()
{
	window.location="../caja/corte_caja.php";
}
