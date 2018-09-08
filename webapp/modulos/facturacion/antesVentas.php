<script>
	$(document).ready(function(){
		
		/*$.ajax({
			data:{accion:'getSales', id:0},
       		url:'../../modulos/facturacion/getInfoFacturas.php',
       		type: 'POST',
       		success: function(callback){  
				$(".tdSelectFact").html(callback);
				loadEvent();
   			}
		});*/
		
		$(".btnfact").click(function(){
			if($(".selectFact").val()!=0){
				$.ajax({
					data:{accion:'allFacts', fecha:$(".selectFact option:selected").text(), id:0},
		       		url:'../../modulos/facturacion/getInfoFacturas.php',
		       		type: 'POST',
		       		success: function(callback){ 
		       		if(callback==100){
		               		alert('Has exedido el limite de timbrados');
		               		return false;
	               	} 
						alert(callback);
						$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');
		   			}
				});
			}
		});

		

		
		$(".trencabezado").append("<td>Acciones</td>");
		$('.trcontenido').each(function() {
			var idSale=$(this).children('td:nth-child(1)').html();
			$(this).append("<td align='center'><input type='button' style='width:100px;height:20px' value='Facturar' onclick='openDialog("+idSale+")'/><input class='checks' value='"+idSale+"' style='cursor:pointer' type='checkbox' onclick='aaa();' /></td>");
			
			var track = $(this).children('td:nth-child(5)').html();
			if(track !=0){
				$(this).children('td:nth-child(5)').html('<span class="label label-success">Busca en SAT</span>');
			}else{	
				$(this).children('td:nth-child(5)').html('');
			}
		});		
	});	
	
	function loadEvent(){
		$(".selectFact").change(function(){
			if($(".selectFact").val()!=0)
				$(".btnfact").show();
			else
				$(".btnfact").hide();
		});
	}
	
	function facturar(id){
		addo=$("#addo").val();
		rrfc=$("#cmbRFCs").val();
		$(".divSelector").html('<div style="margin-top:40px;">Facturando, favor de esperar...</div>');
		
		$.ajax({
			data:{accion:'oneFact', id:id,rrfc:rrfc,addo:addo},
       		url:'../../modulos/facturacion/getInfoFacturas.php',
       		type: 'POST',
       		dataType:'json',
       		success: function(resp){
       			console.log(resp);
       			if(resp.success==0){
					alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);
					$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
					$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');
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
							idFact:rrfc,
							idVenta:resp.datos.idVenta,
							noCertificado:resp.datos.noCertificado,
							tipoComp:resp.datos.tipoComp,
							trackId:resp.datos.trackId,
							monto:resp.datos.monto,
							cliente:resp.datos.idCliente,
							idRefact:id,
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
							alert('Se ha facturado correctamente');
							$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
							$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');

						}
					});
				}

				return false;
			}
		});
	}

	function aaa(){
			var checados = $( "input:checked" ).length;
			if(checados>=2){
				$('#btnaf').css('display','block');
			}else{
				$('#btnaf').css('display','none');
			}

		}
	
	function openDialog(id){
		window.scrollTo(0, 0);
		getRFCS(id);	
	/*	if ($('#permiso').length){
			$(".divSelector").hide();
		}else{
			$(".divSelector").show();
		} */
			$(".divSelector").show();
			$(".btnOpenDialog").attr("onclick","facturar("+id+")");
			$('#tabla_reporte').css('visibility','hidden');			

	}
	
	function getRFCS(id){
		$.ajax({
			data:{accion:'getFacts', id:id},
       		url:'../../modulos/facturacion/getInfoFacturas.php',
       		type: 'POST',
       		success: function(callback){  
				$(".comboRFC").html(callback);
			/* 	if ($('#permiso').length){
					$(".divSelector").hide();
					alert('No tienes timbres de factura disponibles, se han hagotado.');
					$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');
				} */
   			}
		});
	}
	
	function closeDialog(){
		$('#tabla_reporte').css('visibility','visible');
		$(".divSelector").hide();
		$(".btnOpenDialog").attr("onclick","");
	}

	function allfs(){
		//alert('okeokdoekdoekdokeodkeodkeo');
		$('#btnaf').hide();
		$('#loadingDiv').show();
		cadena='';
		$('input:checked').each(function(){
            cadena+=$(this).val()+',';
        });
		
		$.ajax({
			data:{accion:'allfs', id:cadena},
       		url:'../../modulos/facturacion/getInfoFacturas.php',
       		type: 'POST',
       		dataType:'json',
       		success: function(resp){ 
       			console.log(resp);
       			if(resp.success==0){
					alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);
					return false;
				}
				if(resp.success==1){
					
					azu=resp.azurian;
					uid=resp.datos.UUID;
					correo='';


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
							monto:resp.datos.monto,
							cliente:resp.datos.idCliente,
							azurian: resp.azurian,
							idRefact:'all'+cadena},
						success: function(resp){
							//alert('98989898');
							$('#btnaf').show();
							$('#loadingDiv').hide();
 							$.ajax({
								async: false,
								type: 'POST',
								url:'../../modulos/punto_venta/funcionesPv.php',
								data:{funcion:"envioFactura",uid:uid,correo:correo,azurian:azu},
								success: function(resp){  
									
								}
							}); 
							console.log(resp);
							alert('Se ha facturado correctamente');
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');

						}
					});
				}
       		}
		});
	}

</script>
<div clas="divbtnfact" style="width:100%" align="right">
	<table>
		<tr>
			<td class="tdSelectFact">
				<input id='btnaf' type="button" width="100px" height="60px" value="Facturar ventas seleccionadas" style="display:none" onclick="allfs();"/>
				<label id="loadingDiv" style="display:none;">Procesando...</label>
			</td>
			<td width="110px"><input type="button" width="100px" height="60px" value="Facturar ventas del dia (RFC generico)" style="display:none" class="btnfact"/></td>
		</tr>
		
	</table>
</div>

<div class="divSelector" style="background:#efefef;width:300px;height:130px;position:absolute;border-radius:5px;box-shadow:1px 1px 5px 1px rgba(0, 0, 0, .5);top:5%;left:35%;display:none" align="center">
	<div style="width:100%;height:100%">
		<table style="font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;font-size:12px;width:80%;height:100%">
			<tr>
				<td>Seleccione un RFC:</td>
				<td class="comboRFC"></td>
			</tr>
			<tr>
				<td>Observaciones</td>
				<td><textarea id="addo" style="width:170px;height:40px;" id="observaciones" placeholder=""></textarea></td>

			</tr>
	</tr>
<tr><td><input style="width:100px;height:20px" value="Cancelar" type="button" class="btnCloseDialog" onclick="closeDialog()"/></td>
		<td><input style="width:100px;height:20px" value="Facturar" type="button" class="btnOpenDialog" onclick=""/></td></tr></table></div>
</div>