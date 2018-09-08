<link rel="stylesheet" type="text/css" href="../../modulos/cont/css/jquery-ui.css"/>
<script type="text/javascript" src="../../modulos/cont/js/jquery-ui.js"></script>

<script>
	var $nombre_mesa='';
	
	$(document).ready(function() {
		$(".trencabezado").append("<td>Cuenta</td><td>Pedido</td>");

	// Iconos de impresora
		$contenido = "	<td align='center'>";
		$contenido += "		<img src='../../modulos/restaurantes/images/impresora.jpeg' title='Cuenta' style='cursor:pointer;' border='0' onclick='closeComanda(this,0)'/>";
		$contenido += "	</td>";
		$contenido += "	<td align='center'>";
		$contenido += "		<img src='../../modulos/restaurantes/images/impresora2.jpeg' title='Pedidos' style='cursor:pointer;' border='0' onclick='imprimePedido(this,0)'/>";
		$contenido += "	</td>";

		$(".trcontenido").append($contenido);
	});

	function imprime(obj, tipo) {
		var idcomanda = $(obj).parent().parent().children("td:nth-child(1)").html();
	}

	function closeComanda(obj, tipo) {
		var idcomanda = $(obj).parent().parent().children("td:nth-child(1)").html();
		var idmesa = $(obj).parent().parent().children("td:nth-child(2)").html();
		var tipo = 0;
		var pbandera = 0;

		$.ajax({
			data : {
				idComanda : idcomanda,
				bandera : pbandera,
				idmesa : idmesa,
				tipo : tipo
			},
			url : '../../modulos/restaurantes/ajax.php?c=comandas&f=reImprimeComanda',
			type : 'GET',
			dataType : 'json',
			success : function(callback) {
				console.log(callback);
				
			// Valida que existan registros
				if(!callback['rows']){
					alert('Error al imprimir la comanda: \n -> No se detectaron registros');
					return 0;
				}
				
				var $nombre_mesa='['+callback['id_mesa']+'] '+callback['nombre_mesa'];
		        var txt_propina = '';
				var persona = 0;
				var totalPersona = 0;
		        var $promedio_comensal=0;
				var totalComanda = 0;
				var idComanda = idcomanda;
				var bandera = 0;
		        var logo = callback['logo'];

			// La comanda se cierra pagando todo junto
						if(callback['tipo']==0){
							var html ='	<div style="text-align:left;font-size:14px">'
								html +='	<div>';
								html +='		<input type="image" src="../../netwarelog/archivos/1/organizaciones/'+logo+'" style="width:180px"/>';
								html +='	</div>';
								html +='	<div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">';
								html +='		Comanda No:'+idComanda+' / Mesa: '+$nombre_mesa;
								html +='	</div>';
							  
							var bcontent="";
		                	var codigo="";
		                	
			                $.each(callback['rows'], function(index, value) {
			                 	console.log('Mesa: '+$nombre_mesa);
			                 	
			                	if(persona!=value['npersona']){
			                		html=html.replace(">Orden No:"+persona,">Orden No: "+persona);
			                 		html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">Orden No:'+value['npersona']+'</div>';
			                 		persona=value['npersona'];
			                 		totalPersona=0;
			                 	}
			                 	
			                 	if(!bandera){
			                 		bandera=1;
			              			
			              			codigo=value['codigo'];
			                 	}
			     
			                	html += '<div style="margin-left:15px">';
			                	html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
			                	html += '		<tr>';
			                	html += '			<td>'+value['cantidad']+'</td>';
			                	html += '			<td>'+value['nombre']+'</td>';
			                	html += '			<td>'+parseFloat(value['precioventa']).toFixed(2)+'</td>';
			                	html += '		</tr>';
			                	html += '	</table>';
			                	html += '</div>';
			                
			                // Si existen materiales con costo extra los agrega a la cuenta
			                	if(value['costo_extra']){
			                		html += '<div style="margin-left:15px">';
				                	html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
				                	html += '		<tr>';
				                	html += '			<td></td>';
				                	html += '			<td>=> Extras:</td>';
				                	html += '		</tr>';
				                	
				                	var $costo_extra=0;
				                	
				                // Lista los materiales extra con su costo
							       	$.each(value['costo_extra'], function(c, cc) {
										html += '	<tr>';
				                		html += '		<td></td>';
				                		html += '		<td></td>';
									    html += '		<td>'+cc['nombre']+': </td>';
									    html += '		<td>$ '+cc['costo']+'</td>';
									    html += '	</tr>';
									    
									    $costo_extra+=parseFloat(cc['costo']);
				                		console.log('---	-	-	-	-	-	-	$costo_extra: '+$costo_extra);
								   });
				               		
				                	html += '	</table>';
				                	html += '</div>';
				                	
				                	console.log('---	-	-	-	-	-	-	totalPersona: '+totalPersona+'--- totalComanda'+totalComanda);
				                	
			                		totalPersona+=parseFloat($costo_extra);
			                		totalComanda+=parseFloat($costo_extra);
				                	console.log('---	-	-	-	-	-	-	totalPersona: '+totalPersona+'--- totalComanda'+totalComanda);
			                	
			                	}
			                	totalPersona+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
			                	totalComanda+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
			                	
			      				$promedio_comensal+=totalPersona;
			                });
			                
			                html=html.replace(">Orden No:"+persona,">Orden No: "+persona);
			                html=html.replace(">Comanda No:"+idComanda,">Comanda No: "+idComanda+" / Mesa:"+$nombre_mesa);
			                var propina=totalComanda*.10;
			                html+=bcontent;
			                
			                if(callback['mostrar']==1){
			                	txt_propina='Propina sugerida: '+propina.toFixed(2);
			                	html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            					html += '	'+txt_propina;
            					html += '	</div>';
			                }
			                
							console.log('codigo pagando todo junto////	: '+codigo);
							
            				html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            				html += '		Total: <strong>$'+totalComanda+'</strong>';
            				html += '	</div>';
            				html += '	<div style="margin-top:10px;">';
            				html += '		<input type="image" src="../../modulos/punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" onload="window.print();" style="width:190px;margin-left:-3px;" id="barcode"/>';
            				html += '	</div>';
            				html += '</div>';
       						
       						
       						bandera=0;
       						bcontent="";
			                
			                var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
						    
						    $(ventana).ready(function(){
						    	ventana.document.write(html);  //imprimimos el HTML del objeto en la nueva ventana
						    	ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
						    	ventana.document.close();  //cerramos el documento
			                	setTimeout(closew,50000);
			                	function closew(){
			                		ventana.close();
			                	}
							});
						}
					// FIN La comanda se cierra pagando todo junto
				
				// La comanda se cierra pagando individual
						if(callback['tipo']==1){
		                	var cuser;
							var html = '<div style="text-align:left;font-size:14px"><div><input type="image" src="../../netwarelog/archivos/1/organizaciones/'+logo+'" style="width:180px"/></div>';
							cuser=html;
							var bcontent="";
		               		var codigo="";		               
		               		
		               		var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
		           
			                $.each(callback['rows'], function(index, value) {
			                	if(persona!=value['npersona']){
			                		if(persona!=0){
								        var propina=totalPersona*.10;
								        
								    // Numero de persona y total
								        cuser=cuser.replace(">Orden No:"+persona,">Orden No: "+persona+" "+$nombre_mesa);
			               				cuser+=bcontent;
					              
					              // Muestra la propina si es 1
						                if(callback['mostrar']==1){
						                	txt_propina='Propina sugerida: '+propina.toFixed(2);
						                	cuser += '<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				                			cuser += '	'+txt_propina;
				                			cuser += '</div>';
						                }
						            
						            // Total
			                			cuser += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			            				cuser += '		Total: <strong>$'+totalPersona+'</strong>';
			            				cuser += '	</div>';
            							
            							console.log('codigo pagando individual -	-	-	: '+codigo);
            							
			               			// Codigo de barras
			                			cuser += '<div style="margin-top:10px">';
			                			cuser += '	<input type="image" src="../../modulos/punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" style="width:190px" id="barcode"/>';
			                			cuser += '</div>';
			                			cuser += '<br/><br/><br/>';//Espacio entre personas
		           						
		           						bandera=0;
		           						bcontent="";
		           						totalPersona=0;
									    
									    $(ventana).ready(function(){
									    	ventana.document.write(cuser);  //imprimimos el HTML del objeto en la nueva ventana
									    	ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
									    	
						                	cuser=html;
										});
			                		}
			                		
			                 		cuser += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
			                 		cuser += '	Orden No:'+value['npersona']+' '+$nombre_mesa;
			                 		cuser += '</div>';
			                 		
			                 		persona=value['npersona'];
			                 		totalPersona=0;
			                 	}
			                 	
			                 	if(!bandera){
			                 		bandera=1;
			                 		if(value['tipo']=="1"){
			                 			bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div>';
			                 		}
			                 		if(value['tipo']=="2"){
			              				bcontent='<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Nombre: '+value['nombreu']+'</div><div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">Domicilio: '+value['domicilio']+'</div>';
			              			}
			              			codigo=value['codigo'];
			                 	}
			     
			                	cuser += '<div style="margin-left:15px"><table style="font-size:11px;font-family:Arial;border-collapse:collpase"><tr><td>'+value['cantidad']+'</td><td>'+value['nombre']+'</td><td>'+parseFloat(value['precioventa']).toFixed(2)+'</td></tr></table></div>';
			                	totalPersona+=parseFloat(value['precioventa']*parseFloat(value['cantidad']));
			                	
				            	$promedio_comensal+=totalPersona;
			                });
	                		
	                		if(persona!=0){
						        var propina=totalPersona*.10;
						        
						        cuser=cuser.replace(">Orden No:"+persona,">Orden No: "+persona+" "+$nombre_mesa);
	               				cuser+=bcontent;
					              
							// Muestra la propina si es 1
						    	if(callback['mostrar']==1){
						        	txt_propina='Propina sugerida: '+propina.toFixed(2);
						        	cuser += '<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
		                			cuser += ' '+txt_propina+'';
		                			cuser += '</div>';
						        }
					         
					          // Total
			                	cuser += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			            		cuser += '		Total: <strong>$'+totalPersona+'</strong>';
			            		cuser += '	</div>';
	                			
	                		// Codigo de barras
	                			cuser += '<div style="margin-top:10px">';
	                			cuser += '	<input type="image" src="../../modulos/punto_venta/barcode/barras.php?c=barcode&barcode='+codigo+'&text='+codigo+'&width=190" style="width:190px" id="barcode"/>';
	                			cuser += '</div>';
           						
           						bandera=0;
           						bcontent="";
           						totalPersona=0;
							    
							    $(ventana).ready(function(){
							    	ventana.document.write(cuser);  //imprimimos el HTML del objeto en la nueva ventana
							    	ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
							    	ventana.document.close();  //cerramos el documento
				                	cuser=html;
				                	
				                	setTimeout(closew,50000);
				                	function closew(){
				                		ventana.print();  //imprimimos la ventana
				                		ventana.close();
				                	}
								});
	                		}
						}
					// FIN La comanda se cierra pagando individual
				/*  if(callback['tipo']==2){
				 if(callback['rows'][0]['respuesta']=="ok"){

				 var outElement=$("#tb1594-u",window.parent.document).parent();
				 var caja=outElement.find("#tb1238-u");
				 var pestana=$("body",window.parent.document).find("#tb1238-3");
				 var openCaja=$("body",window.parent.document).find("#mnu1024").children().first().children().first();
				 var pathname = window.location.pathname;
				 var url=document.location.host+pathname;
				 //if(caja.length>0){
				 var campoBuscar=$(".frurl",caja).contents().find("#search-producto");
				 pestana.trigger("click");
				 campoBuscar.trigger("focus");
				 //campoBuscar.trigger("click");
				 campoBuscar.val(callback['rows'][0]['comanda']);
				 campoBuscar.trigger({type: "keypress", which: 13});

				 }
				 } */

			}
		});
	}

	function imprimePedido(obj, tipo) {

		var idcomanda = $(obj).parent().parent().children("td:nth-child(1)").html();
		var id = 1
		/*
		 Esta funcion es para mandar llamar las comandas de la zona que selecciones, y se vuelve a consultar cada 10 segundos
		 */

		$.ajax({
			url : '../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=reimprime',
			type : 'POST',
			dataType : 'json',
			data : {
				'tipo' : idcomanda
			},
		}).done(function(data) {
			var organizacion = data.organizacion;
			var altura = 290;
			var mm = 15.118110236;
			//5 mm
			var contProducto = 0;
			var persona = 0;
			//mm = 3.779527559 px;

			var html = '<div style="text-align:left;font-size:14px;">';
			$.each(data.comanda, function(index, val) {

				html += organizacion + '</br></br>';
				html += 'Comanda # ' + val["comanda"] + '</br>';
				html += 'Inicio del Pedido -> ' + val["inicioPedido"] + '</br>';
				html += 'Mesa -> ' + val["mesa"] + '</br>';
				html += '</br></br></br>';

				$.each(val["persona"], function(index, value) {

					persona = (index);
					html += 'Pedido Persona ' + persona + '</br>';
					html += '</br>';

					$.each(value.productos, function(index, producto) {
						contProducto = (index + 1);

						html += producto["descripcion"] + ' x' + producto["cantidad"] + '</br>';
					
					// Opcionales
						if (producto["opcionales"] != '') {
							var caracterContar = ',';
							var numeroApariciones = (producto["opcionalesDesc"].length - producto["opcionalesDesc"].replace(caracterContar, "").length) / caracterContar.length;
							//producto["opcionalesDesc"] = (producto["opcionalesDesc"]).replace(',',',</br>');
							html += '( <label style="color:red">' + producto["opcionalesDesc"] + '</label> )</br>';
							altura += (mm * numeroApariciones);
						}
					
					// Extra
						if (producto["adicionales"] != '') {
							var caracterContar = ',';
							var numeroApariciones = (producto["adicionalesDesc"].length - producto["adicionalesDesc"].replace(caracterContar, "").length) / caracterContar.length;
							//producto["adicionalesDesc"] = (producto["adicionalesDesc"]).replace(',',',</br>');
							html += '( <label style="color:red">' + producto["adicionalesDesc"] + '</label> )</br>';
							altura += (mm * numeroApariciones);
						}
				
					// Normales
						if (producto["normales"] || producto["normales_desc"]) {
							var caracterContar = ',';
							var numeroApariciones = (producto["normales_desc"].length - producto["normales_desc"].replace(caracterContar, "").length) / caracterContar.length;
							
							html += '( <label style="color:red">' + producto["normales_desc"] + '</label> )</br>';
							altura += (mm * numeroApariciones);
						}
					
					// Nota normal
						if (producto["nota_normal"]) {
							var caracterContar = ',';
							var numeroApariciones = (producto["nota_normal"].length - producto["nota_normal"].replace(caracterContar, "").length) / caracterContar.length;
							html += '( <label style="color:red">' + producto["nota_normal"] + '</label> )</br>';
							altura += (mm * numeroApariciones);
						}

						altura += mm;
						Math.round(altura);
					});
					html += '</br></br>';
				});
				//altura  = altura * (mm*(persona+1));
			});
			html += '</div>';

			var ventana = window.open('', '_blank', 'width=207.874015748,height=' + altura + ',leftmargin=0');
			//abrimos una ventana vacía nueva
			ventana.document.write(html);
			//imprimimos el HTML del objeto en la nueva ventana
			//ventana.document.close();  //cerramos el documento
			ventana.print();
			//imprimimos la ventana *

		});
		// fin del done

	}

</script>
