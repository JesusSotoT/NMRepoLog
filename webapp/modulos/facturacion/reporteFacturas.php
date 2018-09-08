<link rel="stylesheet" type="text/css" href="../../modulos/cont/css/jquery-ui.css"/>
<script type="text/javascript" src="../../modulos/cont/js/jquery-ui.js"></script>


<script>
	$( document ).ready(function() {

			$(".trencabezado").append("<td>Ver</td><td>Cancelar</td><td>Nota de credito</td><td>Reenviar</td>");
			$(".trcontenido").append("<td align='center'><img src='../../modulos/facturacion/pdf.png' style='cursor:pointer;' border='0' onclick='verFactura(this,0)'/><img src='../../modulos/facturacion/xml.png' style='cursor:pointer;' border='0' onclick='verFactura(this,1)'/>"+
				"</td><td align='center'><img src='img/cancel.png' style='cursor:pointer;' border='0' onclick='cancelFactura(this)'/></td><td><img src='../../modulos/facturacion/notacre.png' style='cursor:pointer;width:20px;height:20px;' border='0' onclick='notasdecredito(this)'/></td><td align='center'>"+
				"<img src='img/email2.png' style='cursor:pointer;' border='0' onclick='emailFactura(this)'/></td>");	

				$('.trcontenido').each(function() {
					var folio = $(this).children('td:nth-child(3)').html();
					folio=atob(folio);
					if(folio.match(/^({")|^({\\")/)){
						folio=folio.replace(/\\/g,'');

							try {
							   var fol= JSON.parse(folio);
							  	//console.log(fol);
							   $(this).children('td:nth-child(3)').html(fol.Basicos.folio);
							   $(this).children('td:nth-child(5)').html(fol.Receptor.nombre);
							   $(this).children('td:nth-child(6)').html(fol.Receptor.rfc);

							} catch (e) {
							   $(this).children('td:nth-child(3)').html('Folio invalido');
							}

							  
						/*x=0;
						data =  validajson(folio,x);
						if(data[0]=1){
							$(this).children('td:nth-child(3)').html(fol.Basicos.folio);
						}else{
							$(this).children('td:nth-child(3)').html('invalido;');
						} */
						//var fol = jQuery.parseJSON(folio);
						//$(this).children('td:nth-child(3)').html(fol.Basicos.folio);
					}else{
						$(this).children('td:nth-child(3)').html('&nbsp;');
						fol='';
					}
							

					var rfc = $(this).children('td:nth-child(6)').html();
					if(rfc==''){
						$(this).children('td:nth-child(6)').html('XAXX010101000');
						//$(this).children('td:nth-child(10)').html('<td align="center"></td>');
						$(this).children('td:nth-child(12)').html('<td align="center"></td>');
					}
					var cancel = $(this).children('td:nth-child(7)').html();
					if(cancel==1){
						$(this).css('color','#096')

					}
					if(cancel==2){
						$(this).children('td:nth-child(10)').html('<td align="center"></td>');
					}
					if(cancel==3){
						$(this).css('color','#ff7070')
						$(this).children('td:nth-child(11)').html('<td align="center"></td>');
						$(this).children('td:nth-child(12)').html('<td align="center"></td>');
					}if(cancel==10){
						$(this).css('color','#FF9B3D');
						//$(this).children('td:nth-child(11)').html('<td align="center"></td>');
					}
					/*var fnc = $(this).children('td:nth-child(8)').html();
					if(fnc=='C'){
						$(this).children('td:nth-child(10)').html('<td align="center"></td>');

					} */

					var nombre = $(this).children('td:nth-child(5)').html();
					if(nombre==''){
						$(this).children('td:nth-child(5)').html('&nbsp;');
					}

					var folioFiscal = $(this).children('td:nth-child(2)').html();
					if(folioFiscal==''){
						$(this).children('td:nth-child(2)').html('&nbsp;');
					}
					var pres = $(this).children('td:nth-child(4)').html();
					if(pres==''){
						$(this).children('td:nth-child(4)').html('&nbsp;');
					}
					var proviene = $(this).children('td:nth-child(9)').html();
					if(proviene==1){
						$(this).children('td:nth-child(9)').html('Kiosco');
					}else{
						$(this).children('td:nth-child(9)').html('&nbsp;');
					}	
				});		

				
	});
		
	function validaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;	
		if (rfcStr.length == 12){
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}else{
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}
		var validRfc=new RegExp(valid);
		var matchArray=strCorrecta.match(validRfc);
		if (matchArray==null) {
			return 0;
		}
		else
		{
			return 1;
		}
	}

	
	function verFactura(obj,tipo){
		var foliof=$(obj).parent().parent().children("td:nth-child(2)").html();
		var rfc = $(obj).parent().parent().children("td:nth-child(6)").html();
		var id = $(obj).parent().parent().children("td:nth-child(1)").html();
		$.ajax({
			url: '../../modulos/facturacion/getInfoFacturas.php',
			type: 'POST',
			dataType: 'json',
			data: {accion:"cuponNombre",rfc:rfc,id:id},
		})
		.done(function(data) {	
			console.log(data);

			if(data.cupon==false){
				if(tipo==0){
					window.open("../../modulos/facturas/"+foliof+".pdf");
				}
				if(tipo==1){
					if(data.formas=='3'){
						window.open("../../modulos/cont/xmls/facturas/temporales/"+foliof+".xml");
					}else{
						window.open("../../modulos/facturas/"+foliof+".xml");
					}
				}
			}else{
				if(tipo==0){
					window.open("../../modulos/facturas/"+foliof+"__"+data.receptor+"__"+data.cupon+".pdf");
				}
				if(tipo==1){
					window.open("../../modulos/facturas/"+foliof+"__"+data.receptor+"__"+data.cupon+".xml");
				}
			}

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		



		if(tipo==0){
		//	window.open("../../modulos/facturas/"+foliof+".pdf");
		}
		if(tipo==1){
		//	window.open("../../modulos/facturas/"+foliof+".xml");
		}
		
	}
	
	function cancelFactura(obj){
		//alert('entro');
		//return;
       var id=$(obj).parent().parent().children("td:first").html();
       $("#dialog").dialog({width:'400', resizable:false, modal:false, draggable:false, position:['top',0],
	       buttons : {
		       "Cancelar Factura" : function() {
		       	$("#dialogcancel").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0]});
		           $.ajax({
		               data:  {id:id, accion:"cancelfact"},
		               url:   '../../modulos/facturacion/getInfoFacturas.php',
		               type:  'post',
		               dataType:'json',
		               success:  function (resp) {
		               	if(resp.success==1){
		               		$.ajax({
				               data:  {funcion:"cancelaFacturacion",id:id},
				               url:   '../../modulos/punto_venta/funcionesPv.php',
				               type:  'post',
				               dataType:'json',
				               success:  function (data) {
				               		alert(resp.mensaje);
				               		$("#dialogcancel").dialog('close');
				               		$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
				               }
			               	});
		               		
		               	}else{
		               		alert(' '+resp.error+' - '+resp.mensaje);
		               		$("#dialogcancel").dialog('close');
		               		$.ajax({
		               			url: '../../modulos/facturacion/getInfoFacturas.php',
		               			type: 'post',
		               			dataType: 'json',
		               			data: {id:id, accion:"enviadaCancelar"},
		               		})
		               		.done(function(data) {
		               			console.log("success");
		               		})
		               		.fail(function() {
		               			console.log("error");
		               		})
		               		.always(function() {
		               			console.log("complete");
		               		});
		               		
		               		//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
		               	}

		               	return false;
		               	if(callback==100){
		               		$("#dialogcancel").dialog('close');
		               		alert('Has exedido el limite de timbrados');
		               		return false;
		               	}
		               	if(callback=='172'){
		               		$("#dialogcancel").dialog('close');
		               		alert('No se pudo cancelar la factura, favor de esperar de 24 a 72 horas para cancelar.');
		               		return false;
		               	}
		             
		                   $("#dialogcancel").dialog('close');
		                   $("#dialogrefacturar").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0],
			       				buttons : {
				       			"si" : function() {

				       				$("#selectfact").css("display","block");
				       				$("#idventah").val(id);
				       				$("#dialogrefacturar").dialog('close');
				       				loadSelect()},
				       			"no" : function() {
				       				$(".ui-dialog-buttonpane").css('display','none');
				       				$("#dialogrefacturar").html('Procesando informacion, favor de esperar...');
				       				$.ajax({
						               data:  {id:id, accion:"genericFact"},
						               url:   '../../modulos/facturacion/getInfoFacturas.php',
						               type:  'post',
						               success:  function (response) {
						               	if(response==100){
						               		$("#dialogrefacturar").dialog('close');
						               		alert('Has exedido el limite de timbrados');
						               		return false;
						               	}
						               	$("#dialogrefacturar").dialog('close');
						               	$("#dialogrefacturar").html('Deseas Refacturar?');
						               				//alert(response);
								                   //alert("Factura Cancelada, se genero Factura Generica!!");
								                   $('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
						               }
						           });
				       				
				       					
				       			}

			       				}
	   						});
		               }
		           });
		           $("#dialog").dialog('close');
		       },
		      /* "Crear nota de credito" : function() {
		       	//$('#formularionota').dialog();
		       	//$('#dialog').append('<label>Monto<label><input type="text" id="costo">');

		      	$("#dialog").html('Generando nota de credito, favor de esperar...');
		       	$(".ui-dialog-buttonpane").css('display','none');
		           $.ajax({
		               data:  {id:id, accion:"guardanc"},
		               url:   '../../modulos/facturacion/getInfoFacturas.php',
		               type:  'post',
		               dataType:'json',
		               success:  function (resp) {
		               	console.log(resp);
		               	if(resp.success==0){
		               		$("#dialo").dialog('close');
							alert('Ha ocurrido un error al crear la nota de crédito. Error '+resp.error+' - '+resp.mensaje);
							$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
						
						}
						if(resp.success==1){
							azu=resp.azurian;
							uid=resp.datos.UUID;
							correo=resp.correo;
							$.ajax({
								type: 'POST',
								url:'../../modulos/punto_venta/funcionesPv.php',
								data:{funcion:"guardarFacturacion",
									UUID:resp.datos.UUID,
									noCertificadoSAT:resp.datos.noCertificadoSAT,
									selloCFD:resp.datos.selloCFD,
									selloSAT:resp.datos.selloSAT,
									FechaTimbrado:resp.datos.FechaTimbrado,
									idComprobante:resp.datos.idComprobante,
									idFact:resp.datos.idFact,
									idVenta:resp.datos.idVenta,
									noCertificado:resp.datos.noCertificado,
									tipoComp:resp.datos.tipoComp,
									trackId:resp.datos.trackId,
									monto:resp.monto,
									cliente:'',
									idRefact:'c',
									azurian:resp.azurian},
								success: function(resp){
									$.ajax({
										async: false,
										type: 'POST',
										url:'../../modulos/punto_venta/funcionesPv.php',
										data:{funcion:"envioFactura",uid:uid,correo:correo,azurian:azu},
										success: function(resp){  
											
										}
									});  
									console.log(resp);
									alert('Se ha creado la nota de credito correctamente');
									$("#dialo").dialog('close');
									$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
								}
							});
						}
						
						return false;
		               	//alert(callback);
		               	//../repolog/repolog.php?i=10
		               	$('.frurl').attr('src','../repolog/repolog.php?i=10');
		               		$("#dialog").dialog('close');
		                   
		                    $("#dialogrefacturar").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0],
			       				buttons : {
				       			"si" : function() {
				       				$("#selectfact").css("display","block");
				       				$("#dialogrefacturar").dialog('close');
				       				$("#idventah").val(id);
				       				loadSelect()},
				       			"no" : function() {
				       				$("#dialogrefacturar").dialog('close');
				       				$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
				       			}
			       				}
	   						});		                   
		               }
		           }); 
		           $("#dialog").dialog('close');
		       } */
	     	}
	   	});
	}
   	
	function emailFactura(obj){
		var id=$(obj).parent().parent().children("td:first").html();
		var foliof=$(obj).parent().parent().children("td:nth-child(2)").html();
	    $.ajax({
            data:  {id:id, accion:"email", pdf:"../../modulos/facturacion/"+foliof+".pdf"},
            url:   '../../modulos/facturacion/getInfoFacturas.php',
            type:  'post',
            success:  function (response) {
            	//alert(response)
               $("#dialogmail").html(response);
               $("#dialogmail").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0],
			       buttons : {
				       "ok" : function() {
				       		 $("#dialogmail").dialog('close');
				       }
			       }
	   			});
            }
        });
	}
	
	function saveRfc(){
		
		if($("#cont_formfact").css('display')!="none"){

			var idventa=$('#idventah').val();
			if(idventa==0)
				return 0;
	  		var contador=0;
	  		var texto="Porfavor llene los siguientes campos:\n";
		  	if($("#rfc").val()==""){
		  		texto+="* RFC\n";
		  		contador++;
		  	}	
		  	if($("#pais").val()==""){
		  		texto+="* Pais\n";
		  		contador++;
		  	}
		  	if($("#razons").val()==""){
		  		texto+="* Razon Social\n";
		  		contador++;
		  	}
		  	if($("#correos").val()==""){
		  		texto+="* Correo o correos";
		  		contador++;
		  	}else{
		  		var bandera=false;
		  		var correos= $("#correos").val().split(";");
		
		  		for(var cont=0;cont<correos.length;cont++){
		  			if(correos[cont].indexOf('@', 0) == -1 || correos[cont].indexOf('.', 0) == -1) {
		    			bandera=true;
		    			break;
					}
		  		}
		  		if(bandera){
		  			alert("Hay un correo escrito incorrecto");
		  			return;	
		  		}
		  	}
		
			if(!contador){
				if(validaRfc($('#rfc').val())==0){
					alert("El RFC es incorrecto!!")
					return false;
				}
				$("#dialogespera").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0]});
				$("#selectfact").css('display','none');	
				$('#btnff').css('display','none');
  				$('#loadbtnch').css('display','block');
				$.ajax({
						url:'../../modulos/facturacion/getInfoFacturas.php',
						type: 'POST',
						data: {id:idventa, accion:"refacturarInsert", rfc: $("#rfc").val(), razons: $("#razons").val(), regimenf: $("#regimenf").val(), calle: $("#calle").val(), numext: $("#numext").val(), colonia: $("#colonia").val(), municipio: $("#municipio").val(), ciudad: $("#ciudad").val(), cp: $("#cp").val(), estado: $("#estado").val(), pais: $("#pais").val(), correos: $("#correos").val()},
						success: function(callback)
						{	
							$("#dialogespera").dialog('close');
							if(callback!="-1"){
								alert("Se ha refacturado correctamente!!");
								$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
							}
							else{
								alert("Error el RFC ya existe porfavor coloque otro diferente!!!");
							}
							$('#loadbtnch').css('display','none');
							$('#btnff').css('display','block');
						}
					});
				}
				else
					alert(texto);
		}else{
			$("#dialogespera").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0]});
			var idventa=$('#idventah').val();
			$("#selectfact").css('display','none');
			$.ajax({
				url:'../../modulos/facturacion/getInfoFacturas.php',
				type: 'POST',
				data: {id:idventa, idFact: $("#combo_fact").val(), accion:"refacturarListo"},
				success: function(callback)
				{		
					$("#dialogespera").dialog('close');
					alert("Se ha refacturado correctamente!!")
					$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
				}
			});	
		}
		$("#rfc").val("");
		$("#razons").val("");
		$("#regimenf").val("");
		$("#calle").val("");
		$("#numext").val("")
		$("#colonia").val("");
		$("#municipio").val("");
		$("#ciudad").val("");
		$("#cp").val("");
		$("#estado").val("");
		$("#pais").val("");
		$("#correos").val("");
	}
	
	function agregarNuevo(){
		if($("#cont_formfact").css('display')=="none"){
			$("#cont_formfact").css('display','block');	
			$("#agregarLink").html("Buscar RFC");
		}else{
			$("#agregarLink").html("Agregar Nuevo RFC");
			$("#cont_formfact").css('display','none');
		}
	}
	
	function closeWindowRfc(){
		$("#selectfact").css('display','none');	
		$("#rfc").val("");
		$("#razons").val("");
		$("#regimenf").val("");
		$("#calle").val("");
		$("#numext").val("")
		$("#colonia").val("");
		$("#municipio").val("");
		$("#ciudad").val("");
		$("#cp").val("");
		$("#estado").val("");
		$("#pais").val("");
		$("#correos").val("");
	}
	function notasdecredito(obj){
		$("#monto").val('');
		var id=$(obj).parent().parent().children("td:first").html();

		var montofactura=$(obj).parent().parent().children("td:nth-child(4)").html();
		var folio=$(obj).parent().parent().children("td:nth-child(3)").html();
		var idfac=$(obj).parent().parent().children("td:first").html();

		$.ajax({
			data:{accion:'timbresFacts', id:id},
			//data:{accion:'getFactswwww', id:id},
       		url:'../../modulos/facturacion/getInfoFacturas.php',
       		type: 'POST',
       		success: function(callback){  
       		
				//if(callback==0){	
					//alert('No tienes timbres de factura disponibles, se han hagotadfrforkforkorso');
					//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
			//	}else{
	/*			}
   			}
		});*/
		$("#notasdecredito").dialog({width:'400', resizable:false, modal:false, draggable:false, position:['top',0],
	       buttons : {
		       "Crear nota de Credito" : function() {
		       	var monto = $('#monto').val();
		        
		        monto=parseInt(monto);
		       	montofactura=parseInt(montofactura);

		       	if(monto > montofactura){
		       		alert('No puedes hacer una nota por un monto mayor a la factura');
		       		return;
		       	}

		       	montosiniva = (monto/1.16) //subtotal o importe 
		       	iva = (montosiniva*0.16) // puro iva
		       	total=montosiniva+iva;

		       $('#nmloader_div', window.parent.document).show();
		       $(".ui-dialog-buttonpane").hide();
		           $.ajax({
		               data:  {id:id, iva:iva, montosiniva:montosiniva, total:total, folio:folio, accion:"guardanc"},
		               url:   '../../modulos/facturacion/getInfoFacturas.php',
		               type:  'post',
		               dataType:'json',
		               success:  function (resp) {
		               	console.log(resp);
		               	if(resp.success==0){
		               		/*$(".ui-dialog-buttonpane").show();
		               		$('#nmloader_div', window.parent.document).hide();
		               		$("#dialo").dialog('close');
							alert('Ha ocurrido un error al crear la nota de crédito. Error '+resp.error+' - '+resp.mensaje); */
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
							//return false;
							if(resp.trackId!=''){

								$.ajax({
									url: '../../modulos/facturacion/getInfoFacturas.php',
									type: 'post',
									dataType: 'json',
									data: {accion: 'guardaTrackid',
										   xxxx: resp.trackId, 
										   folio:folio, 
										   id:id},
								})
								.done(function(data) {
									console.log(data);
								})
								.fail(function() {
									console.log("error");
								})
								.always(function() {
									console.log("complete");
								});
								
							}
							$(".ui-dialog-buttonpane").show();
		               		$('#nmloader_div', window.parent.document).hide();
		               		$("#dialo").dialog('close');
							alert('Ha ocurrido un error al crear la nota de crédito. Error '+resp.error+' - '+resp.mensaje);
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
							//return false;
						
						}
						if(resp.success==1){

							

							
							
							azu=resp.azurian;
							uid=resp.datos.UUID;
							correo=resp.correo;
							$.ajax({
								type: 'POST',
								url:'../../modulos/punto_venta/funcionesPv.php',
								data:{funcion:"guardarFacturacion",
									UUID:resp.datos.UUID,
									noCertificadoSAT:resp.datos.noCertificadoSAT,
									selloCFD:resp.datos.selloCFD,
									selloSAT:resp.datos.selloSAT,
									FechaTimbrado:resp.datos.FechaTimbrado,
									idComprobante:resp.datos.idComprobante,
									idFact:resp.datos.idFact,
									idVenta:resp.datos.idVenta,
									noCertificado:resp.datos.noCertificado,
									tipoComp:resp.datos.tipoComp,
									trackId:resp.datos.trackId,
									monto:resp.monto,
									cliente:'',
									idRefact:'c',
									azurian:resp.azurian,
									total:total,
									//idfac:idfac,
								},
								success: function(resp){
									$.ajax({
										async: false,
										type: 'POST',
										url:'../../modulos/punto_venta/funcionesPv.php',
										data:{funcion:"envioFactura",uid:uid,correo:correo,azurian:azu},
										success: function(resp){  
											
										}
									});  
									console.log(resp);
									$(".ui-dialog-buttonpane").show();
									$('#nmloader_div', window.parent.document).hide();
									alert('Se ha creado la nota de credito correctamente');
									$("#notasdecredito").dialog('close');
									//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
								}
							});
						}
						
						return false;
		               	//alert(callback);
		               	//../repolog/repolog.php?i=10
		               	$('.frurl').attr('src','../repolog/repolog.php?i=10');
		               		$("#dialog").dialog('close');
		                   
		                    $("#dialogrefacturar").dialog({width:'400', resizable:false, modal:false, draggable:false,position:['top',0],
			       				buttons : {
				       			"si" : function() {
				       				$("#selectfact").css("display","block");
				       				$("#dialogrefacturar").dialog('close');
				       				$("#idventah").val(id);
				       				loadSelect()},
				       			"no" : function() {
				       				$("#dialogrefacturar").dialog('close');
				       				$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=10');
				       			}
			       				}
	   						});		                   
		               }
		           }); 

		       },//termina el crear nota
		       //"Cancelar Factura" : function() {},
		   }
		});
			//}
   			}
		});
	}
</script>
<div id="hey"></div>
<div id="dialog" style="display:none;">Deseas cancelar la factura o crear una nota de credito?</div>
<div id="dialogmail" style="display:none;"></div>
<div id="dialogrefacturar" style="display:none;">Deseas Refacturar?</div>
<div id="dialogespera" style="display:none;">Refacturando, favor de esperar...</div>
<div id="dialognc" style="display:none;">Generando nota de credito, favor de esperar...</div>
<div id="dialogcancel" style="display:none;">Cancelando factura, favor de esperar...</div>
<div id="notasdecredito" style="display:none;">
	<div>
		<label>Ingresa el monto por el cual deseas realizar la nota de credito.</label><br /><br />
		<label>Monto: $</label>
		<input type="text" id="monto" class="nminputtext">
	</div>
</div>

<div style="width:100%;height:50px;background-color:#11000;display:none;" id="selectfact">
	<div style="position:fixed;z-index:100;top:0px;left:0px;height:100%;width:100%;background-color:#000;opacity:0.75"></div>
	
	<div id="TB_window" style="margin-left: -190px; width: 380px; margin-top: -240px; display: block; position: fixed; background: #ffffff; z-index: 102; color: #000000; border: 4px solid #525252; text-align: left; bottom: 10%; left: 50%;font: 12px Arial, Helvetica, sans-serif; color: #333333;">
		<div id="TB_title" style="background-color: #e8e8e8;font-size: 1.25em;font-weight: bold;height: 27px;">
			<div id="TB_ajaxWindowTitle" style="float: left;padding: 7px 0 5px 10px;margin-bottom: 1px;">Seleccionar RFC</div>
			<div id="TB_closeAjaxWindow" style="padding: 7px 10px 5px 0;margin-bottom: 1px;text-align: right;float: right;"><a href="#" id="TB_closeWindowButton" onclick="closeWindowRfc()">X</a></div>
		</div>
		<div id="TB_ajaxContent" style="width:350px;height:300px;clear: both;padding: 2px 15px 15px 15px;overflow: auto;text-align: left;line-height: 1.4em;">
			<div class="field_row clearfix" style="display: inline-block;margin-top:10px">		
				<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">RFC Existente:</label>	
				<div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
					<script>
						function loadSelect(){
							var idventa=$('#idventah').val();
							$.ajax({
								url:'../../modulos/facturacion/getInfoFacturas.php',
								type: 'POST',
								data: {id:idventa, accion:"loadSelect"},
								success: function(callback)
								{
									$('#selectdiv').html(callback)			
								}
							});	
						}
					</script>
					<div id="selectdiv"></div>
				</div>
			</div>
			
			<!--<div><a href="javascript:void(0)" onclick="agregarNuevo()" id="agregarLink">Agregar Nuevo RFC</a></div>
-->
			<div id="cont_formfact" style="display:none">
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;color:#ff0000">Nuevo RFC:</label>	
					<div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;"><input type="text" name="rfc" value="" id="rfc"/></div>
				</div>
	
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;color:#ff0000">Razon Social:</label>
						<div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;"><input type="text" name="razons" value="" id="razons"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">&nbsp;</label>	<div class="form_field1" style="float: left;padding: 3px;background-color:#f2f2f2;">
					<label for="country" style="color:#ccc;font-size:10px;">(mas de un correo separados por ;)</label></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;color:#ff0000">Correos:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
					<textarea rows="6" cols="16" id="correos" name="correos"></textarea></div>
				</div>
	
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Regimen Fiscal:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
					<input type="text" name="regimenf" value="" id="regimenf"></div>
				</div>

				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Calle:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="calle" value="" id="calle"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Numext:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="numext" value="" id="numext"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Colonia:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="colonia" value="" id="colonia"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Municipio:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="municipio" value="" id="municipio"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Ciudad:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="ciudad" value="" id="ciudad"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Cp:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="cp" value="" id="cp"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;">Estado:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
						<input type="text" name="estado" value="" id="estado"></div>
				</div>
				
				<div class="field_row clearfix" style="display: inline-block;margin-top:10px">	
					<label for="country" style="float: left;width: 100px;text-align: left;line-height: 2.3;color:#ff0000">País:</label><div class="form_field" style="float: left;padding: 3px;background-color:#f2f2f2;">
					<input type="text" name="pais" value="" id="pais"></div>
					<input type="hidden" name="idventah" value="0" id="idventah"></div>
				</div>	
				
			</div>
			
			<div class="field_row clearfix" style="display: inline-block;margin-top:10px">
				<input type="button" name="submit" value="Enviar" id="btnff" class="submit_button float_right" onclick="saveRfc()" style="padding: 5px;color: #fff;background-color: #91C313;border: 2px solid #ddd;padding: 5px;color: #fff;background-color: #91C313;border: 2px solid #ddd">
				<div id="loadbtnch" style="float:right;display:none"><img id="imgloadch" src="/webapp/modulos/posclasico/images/spinner_small.gif" style="margin-right:8px;" /></div>
			</div>
			
		</div>
	</div>
</div>
</div>

