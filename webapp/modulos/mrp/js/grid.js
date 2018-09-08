$(document).ready(function(){
				
				
// busqueda(event,this.value,\''.$campo.'\');"></td>
				// <script>function busqueda(evt,val,campo,url){
				// input_keydown(evt,val,campo,url);
					// }
				// </script>
			});		

			function busqueda(evt,val,campo)
			{
				//alert('si');
				var key=evt.charCode? evt.charCode : evt.keyCode;
				
				if(key==13)
				{
						//modo,filtro,pagina,elimina
						var filtro='';
	
	if(val!="")
	{
	switch(campo)
	{
		case 'Id': filtro+=" and oc.idOrd='"+val+"'"; break;		
		case 'Proveedor': filtro+=" and p.razon_social like '%"+val+"%'"; break;
		case 'Fecha_pedido': filtro+=" and 	oc.fecha_pedido like '%"+val+"%'"; break;
		
		case 'Fecha_de_entrega': filtro+=" and oc.fecha_entregalike '%"+val+"%'"; break;
		case 'Elaboro': filtro+=" and oc.elaborado_por like '%"+val+"%'"; break;
		case 'Almacen': filtro+=" and a.nombre like '%"+val+"%'"; break;
		case 'Autorizacion': filtro+=" and oc.autorizado_por like '%"+val+"%'"; break;
	    case 'Estatus': filtro+=" and oc.estatus like '%"+val+"%'"; break;

	}	
	}			
	/*
						 		
	p.idProducto ID,
	p.codigo Codigo,
	p.nombre Nombre,
	d.nombre Departamento,
	f.nombre Familia,
	l.nombre Linea,
	p.precioventa Precio,
	c.color Color,
	t.talla Talla,
	p.maximo Máximo,
	p.minimo Minimo 
		 */
					
// 						
//alert(filtro);
		 // $.post("../../../mrp/index.php/buy_order/grid",
	 // {pagina:1,filtro:'hola',paginacion:10,elimina:false},
	  // function(respues) {
		                      $.ajax({
								  url:'../../../mrp/index.php/buy_order/grid',
								    type: 'POST',
								    data:{pagina:1,filtro:filtro,paginacion:10,elimina:false},
								    success: function(callback)
								{	
								 	if(callback == 'false')
								 	{
								 		alert('El registro no existe')
								 	}else
								 	{
								 		$("#grid").html(callback);
								 	}
										    
							}
							});
							 
				}
			}
function busquedaProduccion(evt,val,campo)
{
	var key=evt.charCode? evt.charCode : evt.keyCode;
				
	if(key==13){
		var filtro='';
	
		if(val!=""){
			switch(campo){
				case 'ID': filtro+="op.id="+val+""; break;		//bien
				case 'Fecha': filtro+="op.fecha like '%"+val+"%'"; break;
				//case 'Fecha_produccion': filtro+="op.fecha_terminacion like '%"+val+"%'"; break;
				case 'Fecha_produccion': filtro+="op.fecha_terminacion like '%"+val+"%'"; break;
				case 'Orden generada por': filtro+="op.generada_por like '%"+val+"%'"; break; // bien
				case 'Almacen': filtro+="a.nombre like '%"+val+"%'"; break; //bien
			    case 'Estatus':
					var patterns=["registrada","en proceso","terminada","cancelada"];
					var	types=Array();
					   	for(i=0;i<4;i++){
						    if(patterns[i].indexOf(val)>=0){
						    	types.push(i);
					  		}
					  	}
				  	filtro+="op.estatus in("+types.join()+")";  
			    break;

			}	
		}
		xx=window.location.href;
		if( xx.match(/index$/) ){
			baseUrl='../../';
		}else{
			baseUrl='../';
		}

              //alert(window.location.href);
	    $.ajax({
		  url:baseUrl+'index.php/production_order/index',
		    type: 'POST',
		    data:{pagina:1,filtro:filtro,paginacion:10,elimina:false},
		    success: function(callback){	
				//$("#grid").html(callback);
								if(callback == 'false'){
								 	alert('El Registro no existe')
								}else{
								 	$("#grid").html(callback);
								}				    
			}
		});
							 
	}
}

			

function paginacionGrid(controllerFunction)
{
	$.ajax({
	  url:controllerFunction,
	  type: 'POST',
	  data:{ajax:true},
	  success: function(callback)
	 {	
	  	$("#grid").html(callback);			    
	 }
	});
}

function Elimina(id,tabla)
{
           if(confirm("¿Esta seguro de querer eliminar el registro?"))
		   { 
		   		$.ajax({
					  url:"../../../../../modulos/mrp/index.php/product/elimina/"+id,
					  type: 'POST',
					  data:{ajax:true},
					  success: function(callback)
					 {	
						$("#grid").html(callback)				    
					 }
					});
		   } 
}

function Elimina(id,tabla)
{
           if(confirm("¿Esta seguro de querer eliminar el registro?"))
		   { 
		   		$.ajax({
					  url:"../../../../../modulos/mrp/index.php/product/elimina/"+id,
					  type: 'POST',
					  data:{ajax:true},
					  success: function(callback)
					 {	
						$("#grid").html(callback)				    
					 }
					});
		   } 
}

function EliminaOrden(id,tabla)
{
           if(confirm("¿Esta seguro de querer eliminar el registro?"))
		   { 
		   		$.ajax({
					  url:"../../../../../modulos/mrp/index.php/buy_order/eliminaOrden/"+id,
					  type: 'POST',
					  data:{ajax:true},
					  success: function(callback)
					 {	
			 			var n=callback.split("!!!AAABBBCCC!!!");
						var err = n[0]; 
						callback = n[1];
						
						if(err == "1")
					 		{
					 			alert("Orden eliminada exitosamente.");
					 		}		
					 	else if(err == "-1")
					 		{
					 			alert("La orden ya está registrada. No se puede borrar.");
					 		}
						$("#grid").html(callback)	
					 }
					});
		   }
}





