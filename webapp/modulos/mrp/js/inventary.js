$(function(){
	
	//alert(baseUrl);
	//$("#finicio").datepicker();
	//$("#ffin").datepicker();
	$("#preloader").hide();
	
	
});


function cargaalmacenes(sucursal)
{
		//alert(sucursal);
		$.ajax({
		url:'../../../../webapp/modulos/mrp/index.php/buy_order/cargaalmacenes',
		type: 'POST',
		data: {id:sucursal},
		success: function(callback)
		{	 
			$("#almacenes").html(callback);							
		}	
		});	
}


function cargaalmacenes2(sucursal)
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

function cargaalmacenes3(sucursal)
{
		//alert(sucursal);
		$.ajax({
		url:'../../../../modulos/mrp/index.php/buy_order/cargaalmacenes2',
		type: 'POST',
		data: {id:sucursal},
		success: function(callback)
		{	 
			$("#almacenes").html(callback);							
		}	
		});	
}



function recalculaUnidad(cantidad,idProducto,idUnidad)
{
	/*
	alert(idProducto);
	alert(idUnidad);
	*/
}


function AgregaraOrden(id,nombre)
{
	$text='<table width="100%">';
	$text+='<tr> <td>Nombre</td><td><input type="text" id="nombregrupo"></td></tr>';
	$text+='</table>';
	/*
	$('.dialog').dialog({
		modal: true,
		minWidth: 390,
		draggable: true,
		resizable: false,
		title:"Agregar "+nombre+" a orden de compra",
		open: function(){
		$(this).empty().append($text);},
		buttons:[{text:'Guardar',click: function(){ 
					
					alert(id);
				
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]}).height('auto');
			*/	
}

function Vermovimientos()
{
	$("#preloader").show();
	$.ajax({
				url:baseUrl+'index.php/inventary/index',
				type: 'POST',
				data: {producto:$("#producto").val(),almacen:$("#almacen").val(),proveedor:$("#proveedor").val(),inicio:$("#finicio").val(),fin:$("#ffin").val()},
				success: function(callback)
				{	 
						$("#movimientos").html(callback);
						$("#preloader").hide();
				}
			});

}

function Verexistencias()
{
	if($("#finicio").val()==""){ alert("Debes seleccionar la fecha inicio"); return false;}
	if($("#ffin").val()==""){ alert("Debes seleccionar la fecha fin"); return false;}
	
	$("#preloader").show();
	$.ajax({
				url:baseUrl+'index.php/inventary/historic',
				type: 'POST',
				data: {producto:$("#producto").val(),almacen:$("#almacen").val(),inicio:$("#finicio").val(),fin:$("#ffin").val()},
				success: function(callback)
				{	 
						$("#existencias").html(callback);
						$("#preloader").hide();
				}
			});

}



function VerExistencias(almacen)
{
	
	 //if($("#conexistencia").is(':checked')) { var existencia=1;}else {var existencia=0;}
	var existencia=$('input:radio[name=conexistencia]:checked').val();
    
   // alert($("#almacen").val());
	$("#preloader").show();
	$.ajax({
			url:baseUrl+'index.php/inventary/stockexis',
			type: 'POST',
			data: {idalmacen:$("#almacen").val(),producto:$("#search-producto").val(),existencia:existencia},
			success: function(callback)
			{	 
				//alert(callback);
					$("#orden tbody").html(callback);
					carga();
					$("#preloader").hide();
			}
		});
// 		

}