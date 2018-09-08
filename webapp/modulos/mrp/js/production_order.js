$(function() {	
	$(".num").numeric();
	$("#preloader").hide();
});

//////////////model con los tipos de orden de produccion
function produccion(){
	if($("#fecha_inicio").val()=='' || $("#fecha_fin").val()=='') {
		alert("-Agrega las fechas de inicio y finalizacion");
		return false;
	}

	$('#contenido-opc').dialog({
		modal: true,
		draggable: true,
		resizable: true,
		title:"Seleciona tipo de orden",
		width:480,
		height:300,
		dialogClass:"mydialog",
		buttons:{
			"Aceptar": function() {
				var x = $('input:radio[name=opciOrd]:checked').val();
				if(x==1) {
					ensambla1(x);
				} else if(x==2) {
					ensambla(x);
				}
			}/*,
			"Guardar": function(){
				 alert('effefe');
			} */
		}
	})
	.height('auto');
}

function ensambla1(x){
	//alert(x);
	var r = confirm("Deseas continuar?");
		if (r == true)
		{
			$("#nmloader_div",window.parent.document).show();

		  	$.ajax({
			url:baseUrl+'index.php/production_order/ensamblaUno',
			type: 'POST',
			data:{},
			success: function(callback)
			{	 
				if(callback!=1)
				{
					alert(callback);
					$("#nmloader_div",window.parent.document).hide();
				}
				else
				{	
					GeneraOrdenesCompra(x);
					$('#nmloader_div',window.parent.document).hide();
					alert("Se han apartado tus productos para ensamblar tu producto.");
					window.location=baseUrl+'index.php/production_order/index';		
				}							
			}	
			});	
		}
		else
		{
		   //x = "You pressed Cancel!";
		}	
}

////////////////////////////
function ensambla(x)
{	//alert(x);
		var r = confirm("Este proceso, descontará los insumos para producir el producto y agregará al inventario los productos terminados, deseas continuar?");
		if (r == true)
		{
			$("#nmloader_div",window.parent.document).show();
		  	$.ajax({
			url:baseUrl+'index.php/production_order/ensambla',
			type: 'POST',
			data:{},
			success: function(callback)
			{	 
				if(callback!=1)	
				{
					alert(callback);
					$("#nmloader_div",window.parent.document).hide();
				}
				else
				{		
					GeneraOrdenesCompra(x);
					$('#nmloader_div',window.parent.document).hide();
					alert("Se han producido con exito tu producto.");
					window.location=baseUrl+'index.php/production_order/index';
				}							
			}	
			});	
		}
		else
		{
		   //x = "You pressed Cancel!";
		}	
}
//////////////Funcion cambio de estatus
function terminacancelado(id){
	//alert(id);
	///alert($("#estatus").val());
	var r = confirm("Deseas camabiar el estatus?");
		if (r == true)
		{
		  	$.ajax({
			url:baseUrl+'index.php/production_order/terminacancelado',
			type: 'POST',
			data:{id:id,estatus:$("#estatus").val()},
			success: function(callback)
			{	 
				if(callback!=1)
				{
					alert(callback);	
				}
				else
				{		
					//alert("Se");
					cambiaestatus(id);
					window.location=baseUrl+'index.php/production_order/index';

				}							
			}	
			});	
		}
		else
		{
		   //x = "You pressed Cancel!";
		}		

}
/////////////////////


function cargaalmacenes(sucursal)
{
		//alert(sucursal);
		$.ajax({
		url:baseUrl+'index.php/production_order/cargaalmacenes',
		type: 'POST',
		data: {id:sucursal},
		success: function(callback)
		{	 
			$("#almacenes").html(callback);							
		}	
		});	
}

function cambiaestatus(id)
{	
	//alert("entro
	$.ajax({
		url:baseUrl+'index.php/production_order/cambiaestatus',
		type: 'POST',
		data: {id:id,estatus:$("#estatus").val()},
		success: function(callback)
		{	 
				alert("Has cambiado el estatus de la orden de producción con éxito");
				//alert(callback);					
		}	
		});
	
}

function quitaelemento()
{
	var seleccionado=false;	
	var i=0;
	var productos=Array();
	$(".ck").each(function(){ 
		
	    if ($(this).is(':checked'))
	    {
	       seleccionado=true;
	       var id=$(this).val();
	       productos[i]=$("#ck_"+id).val();
	       i++;
	    }	
  });
  if(!seleccionado){alert("Selecciona los registros que deseas eliminar de la orden de compra"); return false;}
  
  $("#preloader").show();
	  $.ajax({
		url:baseUrl+'index.php/production_order/quitaelementos',
		type: 'POST',
		data: {productos:productos},
		success: function(callback)
		{	 
				$("#orden_produccion").html(callback);
				$(".accordion" ).accordion({active: false,heightStyle:"content",collapsible: true });
				$("#preloader").hide();
				//alert(callback);					
		}	
		});	
}

function Cargaunidades(idProducto)
{
	 $("#preloader").show();
	  $.ajax({
		url:baseUrl+'index.php/production_order/unidades',
		type: 'POST',
		data: {producto:idProducto},
		success: function(callback)
		{	 
				$("#cbounidades").html(callback);
				$("#preloader").hide();
				//alert(callback);					
		}	
		});	
}
//////////////////////////////////////////////////////////////////////////////////////////////
function GeneraOrdenesCompra(x)
{	//alert(x);
	if( $("#elaboro").val()=="" ){alert("Debes ingresar quien elaboro la orden"); return false;}
	
	var seleccionado=false;	
	var i=0;
	var ordenes=Array();
	$(".ck").each(function(){ 
		
	    if ($(this).is(':checked'))
	    {
	       seleccionado=true;
	       var id=$(this).val();
	       ordenes[i]=id+"_"+$("#proveedor_"+id).val()+"_"+$("#idunidad_"+id).val()+"_"+$("#costo_"+id).html()+"_"+$("#cantidad_"+id).html();
	       i++;
	    }	
  }); //alert('xxxxxxxxxxxx');
  //if(!seleccionado){alert("Selecciona los registros que deseas agregar a la orden de compra"); return false;}

	$("#btongoc").attr("disabled","disabled");
$("#preloader").show();
 //  alert($("#fecha_inicio").val());
 //  alert($("#fecha_fin").val());
  $.ajax({
		url:baseUrl+'index.php/production_order/createorder',
		type: 'POST',
		data: { ordenes:ordenes,
				elaboro:$("#elaboro").val(),
				fecha_inicio:$("#fecha_inicio").val(),
				x:x,
				fecha_fin:$("#fecha_fin").val()
			},
		success: function(callback)
		{	 	
				//alert(callback);
				//alert("Has creado las ordenes de compras correctamente");
				$("#preloader").hide();
				window.location=baseUrl+'index.php/production_order/index';						
		}	
		});	
			
}
function GeneraOrdenesCompra2(x)
{
	if( $("#elaboro").val()=="" ){alert("Debes ingresar quien elaboro la orden"); return false;}
	var seleccionado=false;	
	var i=0;
	var ordenes=Array();
	$(".ck").each(function(){ 
		
	    if ($(this).is(':checked'))
	    {
	       seleccionado=true;
	       var id=$(this).val();
	       ordenes[i]=id+"_"+$("#proveedor_"+id).val()+"_"+$("#idunidad_"+id).val()+"_"+$("#costo_"+id).html()+"_"+$("#cantidad_"+id).html();
	       i++;
	    }	
  }); 

	$("#btongoc").attr("disabled","disabled");
	$("#preloader").show();

  $.ajax({
		url:baseUrl+'index.php/production_order/createordercompra',
		type: 'POST',
		data: { ordenes:ordenes,
				elaboro:$("#elaboro").val(),
				fecha_inicio:$("#fecha_inicio").val(),
				x:x,
				fecha_fin:$("#fecha_fin").val()
			},
		success: function(callback)
		{	 	

				$("#preloader").hide();
				window.location=baseUrl+'index.php/production_order/index';						
		}	
		});	
			
}
////////////////////////////////////////////////////////////////////////

function Todos()
{
   // if ($("#all").is(':checked')){ $(".ck").each(function(){    $(this).attr('checked','checked');		}); }
   // else{$(".ck").each(function(){    $(this).removeAttr('checked'); });}
	
}

////////////////////////////////////////////////////////////////////////
function CotizaProveedor(proveedor,producto,cantidad)
{
	$("#preloader_"+producto).show();
	$.ajax({
		url:baseUrl+'index.php/production_order/cotiza',
		type: 'POST',
		dataType:'json',
		data: {producto:producto,proveedor:proveedor,cantidad:cantidad},
		success: function(callback)
		{	 
						$("#idunidad_"+producto).val(callback[3]);
						$("#unidad_"+producto).html(callback[0]);
						$("#costo_"+producto).html(callback[1]);
						$("#subtotal_"+producto).html(callback[2]);
						$("#preloader_"+producto).hide();				
		}
		});
	
}
////////////////////////////////////////////////////////////////////////
function add()
{
	if($("#sucursal").val()==""){ alert("Debes seleccionar una sucursal"); return false;}
	
	
	//alert( $("#almacen").val() );
	if($("#almacen").val()==""){ alert("Debes seleccionar un almacen"); return false;}
	
	if($("#cantidad").val()==""){ alert("Debes ingresar la cantidad"); return false;}
	if($("#unidad").val()==""){ alert("Debes seleccionar la unidad"); return false;}
	if($("#producto").val()==""){ alert("Debes seleccionar un producto"); return false;}
	
	$("#preloader").show();
		$.ajax({
		url:baseUrl+'index.php/production_order/creaorden',
		type: 'POST',

		data: {producto:$("#producto").val(),unidad:$("#unidad").val(),cantidad:$("#cantidad").val(),almacen:$("#almacen").val(),sucursal:$("#sucursal").val(),textounidad:$("#unidad  option:selected").html()},
		success: function(callback)
		{	 
						$("#orden_produccion").html(callback);
						$("#sucursal").attr("disabled","disabled");
						$("#almacen").attr("disabled","disabled");
						$("#cantidad").val("");$("#producto").val("");$("#unidad").val("");
						$(".accordion" ).accordion({active: false,heightStyle:"content",collapsible: true });
						$("#preloader").hide();		
						if($(".errexist").length > 0){
							notificacion();	
						}
							
		}
		});
}
/////////////////////////////////////////////////////////////////////////////////////////
function CreateOrden()
{ 	
	/*if($("#error").length > 0){
		var r = confirm('No tienes suficientes insumo para producir el producto, quieres realizar una orden de compra con el equivalente a lo que deseas producir?.');
		if (r == true)
		{
			window.location=baseUrl+'index.php/production_order/explosionCompra';
			return;
		}
	//		return false;
		} /* 
/*	alert($(".azul").length);
	//alert($(".errexist").length);

	//if (($(".errexist").length > 0 && $(".verde").length > 0) || ($(".errexist").length > 0 && $(".verde").length < 0 )){
		if(($(".errexist").length > 0 && $(".verde").length > 0) || ($(".errexist").length > 0 && $(".verde").length <= 0) || ($(".errexist").length < 0 && $(".azul").length < 3)){
  		errExist=1;
  		verde=1
  		alert('No tienes existencias');
		return false;
	} */
 


	$.ajax({
		url:baseUrl+'index.php/production_order/puede',
		type: 'POST',
		data: {},
		success: function(callback)
		{
			if(callback==0)
			{
				alert("Aún no has agregdo productos a tu listdo de orden de producción");
			}
			else{window.location=baseUrl+'index.php/production_order/explosion';}			
		}
		});
}