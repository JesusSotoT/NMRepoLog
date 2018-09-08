function buscacorte()
{
	
	if( $("#finicio").val() === "" )
	{
		alert("Debes seleccionar la fecha inicio"); return false;
	}
	if( $("#ffin").val() === "" )
	{
		alert("Debes seleccionar la fecha fin"); return false;
	}
	
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
  /*
  if($("#finicio").val() != "" && $("#ffin").val() != "" && $("#idProveedor option:selected").val()!=''){
    $("#preloader").show();
    var proveedor_filter= $("#idProveedor option:selected").val();
    var filtro=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" and c.idProveedor ='+proveedor_filter+'';
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
  
  */	var chek=$('#saldadas').is(':checked');

		if(chek==true){
			estatus=1;
			convecidas=' or estatus=0';
		}else{
			estatus=0;
			convecidas='';
		}
	if($("#idProveedor option:selected").val()==''){
		/*var chek=$('#saldadas').is(':checked');
		alert(chek);
		if(chek==true){
			estatus=1;
		}else{
			estatus=0;
		} */

	/* if( $("#finicio").val() === "" )
	  {
		  alert("Debes seleccionar la fecha inicio"); return false;}
	    if( $("#ffin").val() === "" )
	    {
		    alert("Debes seleccionar la fecha fin"); return false;
	    } */
	    if($("#finicio").val() === "" || $("#ffin").val() === ""){
	    	fechas='';
	    }else{
	    	fechas=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" and ';
	    }
	
	    $("#preloader").show();
	    var proveedor_filter= $("#idProveedor option:selected").val();
	    var filtro=fechas+'estatus='+estatus+convecidas;

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
	  }else{
	  	
	  	if(chek==true){
	  		x='estatus!=3';
	  	}else{
	  		x=estatus+convecidas;
	  	}

	  	if($("#finicio").val() === "" || $("#ffin").val() === ""){
	    	fechas='';
	    }else{
	    	fechas=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'" and ';
	    }

	    $("#preloader").show();
	    var proveedor_filter= $("#idProveedor option:selected").val();
	    var filtro=fechas+'c.idProveedor = '+proveedor_filter+' and estatus='+x;

	    //saldoactual(proveedor_filter);
	    $.ajax(
	      {
		      url:'../../../../modulos/punto_venta/funcionesBD/gridP.php',
		      type: 'POST',
		      data: {funcion:"gridcxp",pagina:1,filtro:filtro},
		      success: function(callback)
		    {	
			    $("#grid").html(callback);
			    $("#preloader").hide();
			   // saldoactual(proveedor_filter);
		    }
	    });
	    
	  }
}


function buscacxc()
{
	var idcliente = $('#clienteselect').val(); 

	if( $("#finicio").val() === "" )
	{
		alert("Debes seleccionar la fecha inicio"); return false;
	}
	if( $("#ffin").val() === "" )
	{
		alert("Debes seleccionar la fecha fin"); return false;
	}
	
	

	$("#preloader").show();
	 
	 if(idcliente==0){
	 	filtroCliente ='';
	 }else{
	 	filtroCliente = ' and idCliente='+idcliente;
	 }
	
	var filtro=' c.fechacargo between "'+$("#finicio").val()+'" and "'+$("#ffin").val()+'"'+filtroCliente;
		
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
	//$("#clienteselect").val(0);
	$('#clienteselect options[value="A"]').attr('selected', 'selected');
	
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
	
	$.post("../../../punto_venta/funcionesBD/haysuspendidas.php",
		{
			Sucursal: 1,
		},
		function(data)
		{
			if(parseInt(data,10) === 0)
			{
				window.location="../caja/corte_caja.php";
			}
			else
			{
				var preg = confirm("Hay ventas suspendidas, esta seguro que quiere cerrar caja?");
				if(preg)
				{
					window.location="../caja/corte_caja.php";
				}
		}
	});
}
function saldoactual(idProveedor){

	alert(idProveedor);

	$.ajax({
		      url:'../../../../modulos/punto_venta/funcionesBD/gridP.php',
		      type: 'POST',
		      data: {funcion:"saldoproveedor",idProveedor:idProveedor},
		      success: function(callback)
		    {	alert(callback);
			    $("#adeudototal").html(callback);
			    
		    }
	    });
}
function ordencompra(id){
		
		 if(id==0){
		 	alert('Esta cuenta no esta vinculada a una orden de compra.');
		 	return;
		 }else{

				$.ajax({
					      url:'../../../../modulos/punto_venta/funcionesBD/gridP.php',
					      type: 'POST',
					      data: {funcion:"ordendecompra",id:id},
					      success: function(callback)
					    {	
					    	//alert(callback); 
					    	$('#oc').html(callback);
							$('#oc').dialog({
								modal: true,
								draggable: true,
								resizable: true,
								title:"Orden de Compra "+id,
								width:480,
								height:300,
								dialogClass:"mydialog",
								
							}).height('auto');	


					    }
				    });	
		 }
	
}


