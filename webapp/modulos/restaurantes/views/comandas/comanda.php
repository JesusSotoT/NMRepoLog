<html>
	<head>
		<title>Comanda</title>
		<style>
			.GtableTable{
				background:#98ac31;
				width:100px;
				height:100px;
				display:inline-block;
				margin:2px;
				border-radius:10px;
			}
			.GtableTableLabel{
				font-size:12;
				font-family:verdana;
				color:#424242;
				font-weight:600;
				margin-top:75px;
			}
			.btnItemDeparmentestilo{
				background-color:#779ECB;
			    border-radius:10px;
			    width:90%;
			    height:30px;
			    margin-top:2px;
			}
			.flechas{
				margin:4px;
			}
			.qui{

	            overflow-x:hidden;
			}
		</style>
<!-- **	/////////////////////////- -				CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	
<!-- **	/////////////////////////- -				FIN CSS 			--///////////////////// **-->

<!-- **	/////////////////////////- -				 JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Codigo de barras  -->
		<script src="../../libraries/JsBarcode.all.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
		
	<!-- Systema -->
		<script src="js/comandas/comandas.js"></script>
		<script src="js/configuracion/configuracion.js"></script>
		<script src="js/comandas/reimprime.js"></script>
		
<!-- **	/////////////////////////- -				FIN JS 				--///////////////////// **-->
		<script>
		//30029300
			var persons=0;
			var posicion_color=1;
			var cpersons="";
			var fback=0;
			var fperson=0;
			var apersons = new Array();
			var bbutton=true;
			var person=0;
			var loading='<div style="margin-top:20%"><input type="image" style="width:100px;height:100px" src="imgcomandas/loading.gif"/></div><div style="font-family:verdana;font-size:13px;color:#2E2E2E;font-weight:600">Cargando...</div>';
			var personContent="";
			var $nombre_mesa='';
			var $id_pedido='';
			
			$(document).ready(function(){
				
				var $mensaje = "Recuerda escribir el numero de comensales";
				$("#arriba").notify($mensaje, {
					position : "bottom left",
					autoHide : true,
					autoHideDelay : 5000,
					className : "warn"
				});
	
				var piframe=$("#tb2156-u .frurl",window.parent.document).height();
				var info=piframe*.23;
				var content=piframe*.76;
				
				$(".GtableUser").height(content-50);
				$(".GtableMenu").height(content-50);
				$(".GtableProducts").height(content-50);
				
				setUsersArrows();
				setMenuArrows();
				setProductsArrows();
				
			// Agrega una nueva persona
				$(".btnAddPerson").click(function(){
					var topPos = $('#div_person').scrollTop();
					$("#div_person").animate({
				        scrollTop: topPos + 200
				    },800);
				    
					if(bbutton){
						bbutton=false;
						var idcomanda=$(this).attr('idcomanda');
						
					// Loader
						$(".GtableUserContent").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
			
						$.ajax({
							data:{idcomanda:idcomanda, persons:persons},
				       		url:'ajax.php?c=comandas&f=incrementPersons',
				       		type: 'GET',
				       		dataType: 'json',
				       		success: function(callback){
				       		// Ejecuta una funcion que regresa un color de fondo diferente al de la persona anterior
				       			var fondo = color_fondo();
				       		
				       		// Icono y numero de persona
				       			imagen='	<div class="pull-left" style="padding:5px">';
								imagen+='		<button id="person_'+callback['idperson']+'" type="button" class="btn btn-lg btnPerson" style="font-size: 25px;'+fondo+'" idperson="'+callback['idperson']+'" idcomanda="'+idcomanda+'">';
								imagen+='			<i class="fa fa-pencil-square-o"></i> '+callback['idperson'];
								imagen+='		</button>';
								imagen+='	</div>';
				       		
				       		// Se agrega la div a la capa de personas
								$("#div_person").append(imagen);
							
							// Cambiamo el color de fondo de la div de alimentos por persona
								$("#div_color").css("background-color",'#FFF');
							
							// Se crea un mensaje para  agregar a la div de productos
								$mensaje='<div align="left">';
								$mensaje+='	<h4>';
								$mensaje+='		<span class="label label-default">';
								$mensaje+='			* Clic sobre una orden </br></br>';
								$mensaje+='			&nbsp;para agregar productos';
								$mensaje+='		</span>';
								$mensaje+='	</h4>';
								$mensaje+='</div>';
							
							// Remplazamos el contenido por el nuevo mensaje
								$(".GtableUserContent").html($mensaje);
								
				  				window.persons++;
				  				window.bbutton=true;
				  			
				  			// Actualiza el numero de comensales
				  				$("#num_comensales").val(callback['idperson']);
				  			
				  			// Oculta el boton de cuenta por persona
				  				$("#btn_cerrar_persona").hide();
				  				
				  				setUsersArrows();
				       			reloadPersonEvents();
				   			}
						});
					}
				});
			// FIN agregar nueva persona
		
			// Elimina una persona
				$(".btnDeletePerson").click(function(){
					if(confirm("Da doble click sobre la orden hasta que se ponga roja y presiona el boton verde")){
						$(".btnDeletePerson").hide();
						$(".btnAddPerson").hide();
						fperson=true;
					}
				});
			// FIN Elimina una persona
		
			// Elimina la comanda
				$(".btnDeleteComanda").click(function(){
					if(confirm("Estas seguro de borrar la comanda?")){
						$mensaje='Eliminando Comanda ...';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error'
						});
						
					// Valida que exista reservacion
						var $reservacion=0;
						if($(this).attr('id_reservacion')){
							$reservacion=$(this).attr('id_reservacion');
						}
						
						$.ajax({
							data:{
								idcomanda:$(this).attr('idcomanda'), 
								id_reservacion:$reservacion, 
								idmesa:"<?php echo $idmesa; ?>"
							},
				       		url:'ajax.php?c=comandas&f=deleteComanda',
				       		type: 'GET',
				       		success: function(callback){
				       			console.log(callback);
				       			
				       			var pathname = window.location.pathname;
								$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
								//$("#tb2156-u .frurl",window.parent.document).attr("src", $("#tb2156-u .frurl",window.parent.document).attr("src"));
				   			}
						});
					}	
				});
			// FIN Elimina una persona
			
			// Retrocede al menu de mesas o al listado de personas
				$(".btnBack").click(function(){
					var pathname = window.location.pathname;
					
				// Si no existe Fback regresa al menu de mesas
					if(!fback)
						$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
				// Regresa al listado de personas
					else{
						setUsersArrows();
						fback=0;
						person=0;
						$(".btnAddPerson").show();
						$(".btnDeletePerson").show();
						
					// Cambiamo el color de fondo de la div de alimentos por persona
							$("#div_color").css("background-color",'#FFF');
						
						// Se crea un mensaje para  agregar a la div de productos
							$mensaje='<div align="center">';
							$mensaje+='	<h4>';
							$mensaje+='		<span class="label label-default">';
							$mensaje+='			* Clic sobre una orden <br><br>';
							$mensaje+='			&nbsp;para agregar productos';
							$mensaje+='		</span>';
							$mensaje+='	</h4>';
							$mensaje+='</div>';
							
				  		// Oculta el boton de cuenta por persona
				  			$("#btn_cerrar_persona").hide();
				  				
						// Remplazamos el contenido por el nuevo mensaje
							$(".GtableUserContent").html($mensaje);
					}
				});
			// FIN Retrocede al menu de mesas o al listado de personas
				
				$(".btnAddComanda").click(function(){
					if(confirm("Deseas crear la comanda?")){
						$.ajax({
							data:{idmesa:$(this).attr('idmesa')},
				       		url:'ajax.php?c=comandas&f=addComanda',
				       		type: 'GET',
				       		success: function(callback){
				       			 //alert(callback);
				       			 $("#tb2156-u .frurl",window.parent.document).attr("src", $("#tb2156-u .frurl",window.parent.document).attr("src"));
				   			}
						});	
					}
				});
	
				$(".btnConfirm").click(function(){
					$.ajax({
						data:{idcomanda:$(this).attr('idcomanda'), idspersons:apersons.join()},
			       		url:'ajax.php?c=comandas&f=deletePersons',
			       		type: 'GET',
			       		success: function(callback){  
							//alert(callback);
							$("#tb2156-u .frurl",window.parent.document).attr("src", $("#tb2156-u .frurl",window.parent.document).attr("src"));
			   			}
					});
				});
				
				$(".btnCancel").click(function(){
					$(".btnConfirm").hide();
					$(".btnCancel").hide();
					$(".btnAddPerson").show();
					$(".btnDeletePerson").show();
					fperson=false;
				});
				
				$(".btnUpdate").click(function(){
					$("#tb2156-u .frurl",window.parent.document).attr("src", $("#tb2156-u .frurl",window.parent.document).attr("src"));
				});
				
				$(".btnDepartment").click(function(){
					$(".btnFamily").hide();
					$(".btnLine").hide();
					$(".infoDeparment").html("Todos");
					$(".infoFamily").html("Todos");
					$(".infoLine").html("Todos");
					
					$.ajax({
			       		url:'ajax.php?c=comandas&f=getDeparments',
			       		type: 'GET',
			       		dataType: 'json',
			       		success: function(callback){
			       			console.log('reponse getDeparments');
			       			console.log(callback);
								
			       			$(".GtableMenuContent").html("");
			       			$(".GtableProductsContent").html("");
			       			
							$.each(callback["deparments"]["rows"], function(index, value) {
								$departamento='	<button type="button" class="btn btn-default btn-lg btnItemDeparment" style="font-size:13px; width:130px; margin-top:1%" iddeparment="'+value['idDep']+'">';
								$departamento+=value['nombre'].substring(0, 11);
								$departamento+='</button>';
								
								$(".GtableMenuContent").append($departamento);
							});
							
							$.each(callback["products"]["rows"], function(index, value) {
								if(value['especial']){
									var $clase='info';
								}else{
									var $clase='default';
								}
								
								$div='	<div class="pull-left GtableProduct" style="padding:5px" idproducto="'+value['idProducto']+'" idcomanda="<?php echo $row['id'] ?>" materiales="'+value['materiales']+'" tipo="'+value['tipo']+'" iddep="'+value['idDep']+'">';
								$div+='		<button type="button" class="btn btn-'+$clase+'" title="'+value['materiales']+'" style="width: 103px;height: 148px">';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					<table>';
								$div+='						<tr>';
								$div+='							<td style="font-size: 12px" align="center">';
								$div+='								'+value['nombre'].substring(0, 25);
								$div+='							</td>';
								$div+='						</tr>';
								$div+='					</table>';
								$div+='				</div>';
								$div+='			</div>';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					<input type="image" alt=" " style="width:80px;height:80px" src="'+value['imagen']+'"/>';
								$div+='				</div>';
								$div+='			</div>';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					$ '+value['precioventa'];
								$div+='				</div>';
								$div+='			</div>';
								$div+='		</button>';
								$div+='	</div>';
								
								$(".GtableProductsContent").append($div);
							});
							
							reloadMenuEvents();
							setMenuArrows();
							setProductsArrows();
			   			}
					});
				});
				
				$(".btnFamily").click(function(){
					$(".infoFamily").html("Todos");
					$(".infoLine").html("Todos");
					eventGetFamilies($(this));
				});
				
				$(".btnLine").click(function(){
					$(".infoLine").html("Todos");
					eventGetLines($(this));
				});
				
				$(".btnEnd").click(function(){
				// Valida que se ingrese el numero de comensales antes de cerrar la comanda
				// ch@
				var tipo = $(this).attr('tipo');
				var repa = $(this).attr('repa');

				/* Validacion para Domicilio con repartidor
				if(tipo == 2 && repa == ''){
					alert('Debe Tener un Repartidor Asignado');
					return false;
				}
				*/

					if($('#num_comensales').val()==''){
						alert('Ingresa el numero de comensales');
						
						$('#num_comensales').focus();
					}else{
						$(".GtableCloseComanda").css('visibility', 'visible');
					}
				});
			
			// La comanda se cierra pagando individual
				$(".btnIndividual").click(function(){
					closeComanda({bandera: 1});
				});
			
			// La comanda se cierra pagando todo junto
				$(".btnAll").click(function(){
					closeComanda({bandera: 0});
				});
				
			// La comanda se cierra pagando directo en caja
				$(".btnPagar").click(function(){
					$(".btnProcess").attr('cerrar_comanda', 1);
					$(".btnProcess").click();
				
				// Retarda el cerrado de la comanda para que se alcancen a descontar los pedidos
					setTimeout(function() {
						closeComanda({bandera: 2});
						$(".btnProcess").attr('cerrar_comanda', 0);
					}, 500);
				});
				
			// La comanda se manda a caja
				$(".btn_mandar_caja").click(function(){
					closeComanda({bandera: 3});
				});
			
			// Procesa los pedidos
				$(".btnProcess").click(function(){
					var $objeto = {};
					$objeto['idcomanda'] = $(this).attr('idcomanda');
					$objeto['cerrar_comanda'] = $(this).attr('cerrar_comanda');
					
					console.log('-------> objeto pedido');
					console.log($objeto);
					
					$.ajax({
						data:$objeto,
			       		url:'ajax.php?c=comandas&f=process',
			       		type: 'GET',
			       		dataType: 'json',
			       		success: function(response){
			       			console.log('-------> response pedido');
			       			console.log(response);
			       		
			       		// Redirecciona solo si no es Fast food
			       			if(response['tipo_operacion'] != 3){
				       			console.log('-------> Entra...vale cazzo :(');
				       			console.log(response);
			       			
			       			// Valida si viene de cerrrar comanda o no
			       				if($objeto['cerrar_comanda'] != 1){
					       			console.log('===========> Procesa el pedido normalmente');
					       			console.log($objeto);
				       			
			       					var pathname = window.location.pathname;
			       					$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
			       				}
			       			}
			   			},
			   			error: function( jqXHR, textStatus, errorThrown ) {
				            if (jqXHR.status === 0) {
				                alert('No conectado, verifica la conexion.');
				            } else if (jqXHR.status == 404) {
				                alert('No se encontro la pagina [404]');
				            } else if (jqXHR.status == 500) {
				                alert('Problemas en el servidor [500].');
				            } else if (textStatus === 'parsererror') {
				                alert('Fallo en el Json');
				            } else if (textStatus === 'timeout') {
				                alert('Tiempo de respuesta agotado');
				            } else {
				                alert('Error: ' + jqXHR.responseText);
				           }
			           }
					});
				});
				
				$(".btnClose").click(function(){
					$(".GtableCloseComanda").css('visibility', 'hidden');
				});

				$(".btnRegresar").click(function(){
					var pathname = window.location.pathname;
					$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
				});
				
			});
			
			function reloadPersonEvents(){
				$('.btnPerson').off('click');
				$(".btnPerson").click(function(){
					if(!fperson){
						fback = 1;
						$(".btnAddPerson").hide();
						$(".btnDeletePerson").hide();
						cpersons = $("#div_person").html();
		
						person = $(this).attr('idperson');
						$('#btn_kits').attr('persona', person);
						$('#text_cerrar_persona').html(person);
					
					// Oculta el boton de cuenta por persona
				  		$("#btn_cerrar_persona").show();
						
						loadUserProducts(0,person,$(this).attr('idcomanda'),'getItemsPerson',0,0);
					
					// Obtiene el color de fondo de la persona en RGB
						var color = $('#person_'+person).css( "background-color" );
						console.log('---------->>> color');
						console.log(color);
						
					// Convierte el color a Hexadecimal
						color = rgb2hex(color);
						function rgb2hex(color) {
						    color = color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
						    function hex(x) {
						        return ("0" + parseInt(x).toString(16)).slice(-2);
						    }
						    return "#" + hex(color[1]) + hex(color[2]) + hex(color[3]);
						}
					
					// Cambia el color de la division
						$("#div_color").css("background-color",color);
					}else{
						if($(this).children().css('backgroundColor')=='rgb(255, 255, 255)'){
							$(this).children().css('background','#ff0000');
							apersons.push($(this).attr('idperson'));
						}
						else{
							$(this).children().css('background','#ffffff');
							
							for(var contador=0;contador<apersons.length;contador++){
								if($(this).attr('idperson')==apersons[contador]){
									apersons.splice(contador,1);
									break;
								}
							}
						}
						
						$(".btnConfirm").show();
						$(".btnCancel").show();
					}
				});
			}
			
			function reloadActionsEvents(){
				// Se cambiaron las funciones de lessproduct y deleteproduct
			}
			
			function reloadMenuEvents(){
				$('.btnItemDeparment').off('click');
				$(".btnItemDeparment").click(function(){
					console.log('-------> clic btnItemDeparment');
					console.log($(this));
					$(".infoDeparment").html($(this).children().children().html());
					eventGetFamilies($(this));
				});
				
				$('.btnItemFamily').off('click');
				$(".btnItemFamily").click(function(){
					console.log('-------> get Family');
					console.log($(this));
					
					$(".infoFamily").html($(this).children().children().html());
					eventGetLines($(this));
				});
				
				$('.btnItemLine').off('click');
				$(".btnItemLine").click(function(){
					console.log('-----------> Entra a btnItemLine');
					$(".infoLine").html($(this).children().children().html());
					$.ajax({
						data:{idComanda:"<?php echo $row['id']; ?>", idLine:$(this).attr('idline')},
			       		url:'ajax.php?c=comandas&f=getProducts',
			       		type: 'GET',
			       		dataType: 'json',
			       		success: function(callback){  
							$(".GtableProductsContent").html("");
							$.each(callback["rows"], function(index, value) {
								if(value['especial']){
									var $clase='info';
								}else{
									var $clase='default';
								}
								
								$div='	<div class="pull-left GtableProduct" style="padding:5px" idproducto="'+value['idProducto']+'" idcomanda="<?php echo $row['id'] ?>" materiales="'+value['materiales']+'" tipo="'+value['tipo']+'" iddep="'+value['idDep']+'">';
								$div+='		<button type="button" class="btn btn-'+$clase+'" title="'+value['materiales']+'" style="width: 103px;height: 148px">';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					<table>';
								$div+='						<tr>';
								$div+='							<td style="font-size: 12px" align="center">';
								$div+='								'+value['nombre'].substring(0, 25);
								$div+='							</td>';
								$div+='						</tr>';
								$div+='					</table>';
								$div+='				</div>';
								$div+='			</div>';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					<input type="image" alt=" " style="width:80px;height:80px" src="'+value['imagen']+'"/>';
								$div+='				</div>';
								$div+='			</div>';
								$div+='			<div class="row">';
								$div+='				<div class="col-md-12">';
								$div+='					$ '+value['precioventa'];
								$div+='				</div>';
								$div+='			</div>';
								$div+='		</button>';
								$div+='	</div>';
								
								$(".GtableProductsContent").append($div);
							});
							reloadMenuEvents();
			   			}
					});
				});
			
			// Click sobre el producto
				$('.GtableProduct').off('click');
				$(".GtableProduct").click(function(){
					var iddep=$(this).attr('iddep');
					
					if(fback){
							personContent=$(".GtableProductsContent").html();
							var idproduct=$(this).attr('idproducto');
							var idcomanda=$(this).attr('idcomanda');
							
							$.ajax({
								data:{idProduct:idproduct},
					       		url:'ajax.php?c=comandas&f=getItemsProduct',
					       		type: 'GET',
					       		dataType: 'json',
					       		success: function(callback){
					       			console.log('-	-	-	-	-			Busca Materiales		-	-	-	-	');
					       			console.log(callback);
					       		
					       		// Valida que el producto tenga materiales, si tiene arma la vista de los
					       			// opcionales, extra y los normales, si no carga el producto a la persona
					       			if(callback["total"] > 0){
					       				$(".GtableProductsContent").html('');
									
										var opcional='';
										
										var $cabecera_sin='';
										var $cabecera_extra='';
										var $cabecera_opcional='';
										
										var $sin='';
										var $extra='';
										var $opcional='';
										
										var $sin_nota='';
										var $extra_nota='';
										var $opcional_nota='';
									
									// Contenedor de productos opcionales y extra
										$div='	<div style="width:100%">';
										$div+='		<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff;display:inline-block">';
										$div+='			<div align="center" class="btnSaveItems" idproduct="'+idproduct+'" idperson="'+person+'" idcomanda="'+idcomanda+'">';
										$div+='				<button type="button" class="btn btn-success">';
										$div+='					<i class="fa fa-check"></i> Guardar';
										$div+='				</button>';
										$div+='			</div>';
										$div+='		</a>';
										$div+='		<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff;display:inline-block">';
										$div+='			<div align="center" class="btnCancelItems">';
										$div+='				<button type="button" class="btn btn-danger">';
										$div+='					<i class="fa fa-ban"></i> Cancelar';
										$div+='				</button>';
										$div+='			</div>';
										$div+='		</a>';
										$div+='		</div><br />';
										$div+='		<div class="row" id="div">';
														$.each(callback["rows"], function(index, value) {
															console.log('---> Opcional: '+value['opcional']);
															
															var $opcionales = value['opcionales'];
															
														// Producto Sin
															if($opcionales.indexOf('1') != -1){
															// Vacia la nota para qeu no aparesca Undefine
																if(!value.nota_sin){
																	value.nota_sin = '';
																}
																
																$cabecera_sin = '	<div class="col-md-4">';
																$cabecera_sin += '		<div class="panel panel-warning">';
																$cabecera_sin += '			<div class="panel-heading">';
																$cabecera_sin += '				Sin:';
																$cabecera_sin += '			</div>';
																$cabecera_sin += '			<div class="panel-body" id="sin">';
																$cabecera_sin += '			</div>';
																$cabecera_sin += '		</div>';
																$cabecera_sin += '	</div>';
																
																$sin += '	<div class="row">';
																$sin += '		<div class="col-md-12" align="left">';
																$sin += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
																$sin += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
																$sin += '					<table>';
																$sin += '						<tr>';
																$sin += '							<td><input type="checkbox" class="itemCheck" value="'+value['idProducto']+'" opcional="1"/></td>';
																$sin += '							<td><div style="font-size:11px;font-family:verdana">&nbsp'+value['nombre']+'</div></td>';
																$sin += '						</tr>';
																$sin += '					</table>';
																$sin += '				</a>';
																$sin += '			</div>';
																$sin += '		</div>';
																$sin += '	</div>';
																
																$sin_nota = '<br/><div class="row">';
																$sin_nota += '		<div class="col-md-12">';
																$sin_nota += '			<div class="input-group">';
													    		$sin_nota += '				<div class="input-group-addon">';
													    		$sin_nota += '					<i class="fa fa-pencil"></i>';
													      		$sin_nota += '				</div>';
																$sin_nota += '				<textarea id="nota_sin" class="form-control" style="cursor: se-resize">'+value.nota_sin+'</textarea>';
													    		$sin_nota += '			</div>';
																$sin_nota += '		</div>';
																$sin_nota += '	</div>';
															}
														
														// Producto extra
															if($opcionales.indexOf('2') != -1){
															// Vacia la nota para qeu no aparesca Undefine
																if(!value.nota_extra){
																	value.nota_extra = '';
																}
																
																$cabecera_extra = '	<div class="col-md-4">';
																$cabecera_extra += '		<div class="panel panel-info">';
																$cabecera_extra += '			<div class="panel-heading">';
																$cabecera_extra += '				Extra:';
																$cabecera_extra += '			</div>';
																$cabecera_extra += '			<div class="panel-body" id="extra">';
																$cabecera_extra += '			</div>';
																$cabecera_extra += '		</div>';
																$cabecera_extra += '	</div>';
																
																$extra += '	<div class="row">';
																$extra += '		<div class="col-md-12" align="left">';
																$extra += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
																$extra += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
																$extra += '					<table>';
																$extra += '						<tr>';
																$extra += '							<td><input type="checkbox" class="itemCheck" value="'+value['idProducto']+'" opcional="2"/></td>';
																$extra += '							<td><div style="font-size:11px;font-family:verdana">&nbsp'+value['nombre']+'</div></td>';
																$extra += '						</tr>';
																$extra += '					</table>';
																$extra += '				</a>';
																$extra += '			</div>';
																$extra += '		</div>';
																$extra += '	</div>';
																
																$extra_nota = '<br/><div class="row">';
																$extra_nota += '		<div class="col-md-12">';
																$extra_nota += '			<div class="input-group">';
													    		$extra_nota += '				<div class="input-group-addon">';
													    		$extra_nota += ' 					<i class="fa fa-pencil"></i>';
													      		$extra_nota += '				</div>';
																$extra_nota += '				<textarea id="nota_extra" class="form-control" style="cursor: se-resize">'+value.nota_extra+'</textarea>';
													    		$extra_nota += '			</div>';
																$extra_nota += '		</div>';
																$extra_nota += '	</div>';
															}
														
														// Opcionales
															if($opcionales.indexOf('3') != -1){
															// Vacia la nota para que no aparesca Undefine
																if(!value.nota_opcional){
																	value.nota_opcional = '';
																}
																
																$cabecera_opcional = '	<div class="col-md-4">';
																$cabecera_opcional += '		<div class="panel panel-success">';
																$cabecera_opcional += '			<div class="panel-heading">';
																$cabecera_opcional += '				Opcional:';
																$cabecera_opcional += '			</div>';
																$cabecera_opcional += '			<div class="panel-body" id="opcional">';
																$cabecera_opcional += '			</div>';
																$cabecera_opcional += '		</div>';
																$cabecera_opcional += '	</div>';
																
																$opcional += '	<div class="row">';
																$opcional += '		<div class="col-md-12" align="left">';
																$opcional += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
																$opcional += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
																$opcional += '					<table>';
																$opcional += '						<tr>';
																$opcional += '							<td><input type="checkbox" class="itemCheck" value="'+value['idProducto']+'" opcional="3"/></td>';
																$opcional += '							<td><div style="font-size:11px;font-family:verdana">&nbsp'+value['nombre']+'</div></td>';
																$opcional += '						</tr>';
																$opcional += '					</table>';
																$opcional += '				</a>';
																$opcional += '			</div>';
																$opcional += '		</div>';
																$opcional += '	</div>';
																
																$opcional_nota = '<br/><div class="row">';
																$opcional_nota += '		<div class="col-md-12">';
																$opcional_nota += '			<div class="input-group">';
													    		$opcional_nota += '				<div class="input-group-addon">';
													    		$opcional_nota += '					<i class="fa fa-pencil"></i>';
													      		$opcional_nota += '				</div>';
																$opcional_nota += '				<textarea id="nota_opcional" class="form-control" style="cursor: se-resize">'+value.nota_opcional+'</textarea>';
													    		$opcional_nota += '			</div>';
																$opcional_nota += '		</div>';
																$opcional_nota += '	</div>';
															}
														});
										$div+='		</div>';
										$div+='	</div>';
										
								// ** Contenedores
									// Crea el contenedor de los productos
										$(".GtableProductsContent").append($div);
										
									// Agrega las cabeceras a la Div
										$("#div").append($cabecera_sin);
										$("#div").append($cabecera_extra);
										$("#div").append($cabecera_opcional);
										
									// Agrega los productos "Sin"
										$("#sin").append($sin);
									// Agrega los productos "Extra"
										$("#extra").append($extra);
									// Agrega los productos "opcionales"
										$("#opcional").append($opcional);
									
								// ** notas
									// Agrega la nota "Sin"
										$("#sin").append($sin_nota);
									// Agrega la nota "Extra"
										$("#extra").append($extra_nota);
									// Agrega la nota "Normal"
										$("#opcional").append($opcional_nota);
									
									// Cambia el fondo del producto al checar o quitar el check
										$('.itemProductCheck').off('click');
										$(".itemProductCheck").click(function(){
											if($(this).css('background-color') != 'rgb(216, 216, 216)'){
												$(this).css('background-color','#D8D8D8');
												$(this).find('input').prop('checked', false);
											}else{
												$(this).css('background-color','#81F781');
												$(this).find('input').prop('checked', true);
											}
										});
									
									// Agrega el producto al comensal
										$('.btnSaveItems').off('click');
										$(".btnSaveItems").click(function(){
											var opcionales = new Array();
											var extras = new Array();
											var sin = new Array();
											
											var $nota_opcional = $('#nota_opcional').val();
											var $nota_extra = $('#nota_extra').val();
											var $nota_sin = $('#nota_sin').val();
											
											var idperson = $(this).attr('idperson');
											var idcomanda = $(this).attr('idcomanda');
											
										// Cera los arreglos de opcionales y extra de los check seleccionados
											$('.itemCheck').each(function(){
											// Valida que este checado
												if($(this).is(':checked')){
												// Agrega los productos "sin" al array
													if($(this).attr('opcional') == 1){
														sin.push($(this).val());
													}
											
												// Agrega los productos "extra" al array
													if($(this).attr('opcional') == 2){
														extras.push($(this).val());
													}
												
												// Agrega los productos "opcionales" al array
													if($(this).attr('opcional') == 3){
														opcionales.push($(this).val());
													}
												}
											});
									 
									        console.log('$nota_extra: '+$nota_extra);
									        console.log('$nota_opcional: '+$nota_opcional);
									        console.log('$nota_sin: '+$nota_sin);
									        console.log('opcionales: -------------------->>>>');
									        var $o = opcionales.join(',');
									        console.log($o);
									        console.log('extras: -------------------->>>>');
									        var $e = extras.join(',');
									        console.log($e);
									        console.log('sin: -------------------->>>>');
									        console.log(sin);
									        var $s = sin.join(',');
									        console.log($s);
											
										// Loader
											var $loader = '	<div align="center">';
											$loader += '		<i class="fa fa-refresh fa-5x fa-spin"></i>';
											$loader += '	</div>';
											$(".GtableUserContent").html($loader);
			
											$.ajax({
												data:{
													idproduct:$(this).attr('idproduct'), 
													idperson:idperson, 
													idcomanda:idcomanda, 
													opcionales:opcionales.join(','), 
													extras:extras.join(','), 
													sin:sin.join(','), 
													iddep:iddep,
													nota_opcional:$nota_opcional, 
													nota_extra:$nota_extra, 
													nota_sin:$nota_sin
												},
									       		url:'ajax.php?c=comandas&f=addItemsProduct',
									       		type: 'GET',
									       		dataType: 'json',
		       								}).done(function(callback){
									       		console.log('-	-	-	-			addItemsProduct			-	-	-	-	-');
									       		console.log(callback);
									       	
									       	// Valida que tenga insumos
									       		if(callback['msg']){
									       			alert(callback['msg']);
									       		}
									       		
									       		var $pedido='';
									       		
									       		$(".GtableUserContent").html("");
									       		
									       		if(callback["rows"]){
									       		// Recorre los pedidos para agregarlos a la persona
													$.each(callback["rows"], function(index, value) {
														var status='';
														var $pedido='';
														
													// Se elimino de cocina
														if(value['status']==3){
															status='disabled="1" style="background-color:#FF6961"';
														}
														
													// pedido procesado, solo se puede modificar por el admin
														if(value['status']==0||value['status']==1||value['status']==2||value['status']==4){
															status='style="background-color:#77DD77"';
															var status_admin='disabled="1"';
															
															$boton='	<button class="btn btn-default" onclick="$id_pedido='+value['id']+'" type="button" data-toggle="modal" data-target="#modal_autorizar_pedido">';
															$boton+='		<i class="fa fa-key"></i> &nbsp;';
															$boton+='	</button>';
													// Pedido normal
														}else{
															$boton='	<button '+status+'class="btn btn-default" id="btn_eliminar_pedido_'+value['id']+'" type="button" onclick="deleteProduct({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})">';
															$boton+='		<i class="fa fa-trash"></i> &nbsp;';
															$boton+='	</button>';
														}
														
											    		$pedido+='	<div class="input-group">';
														$pedido+='		<input min="1" id="num_pedidos'+value['id']+'" type="number" class="form-control" style="width: 60px" value="1">';
														$pedido+='		<span class="input-group-btn">';
														$pedido+='	        <button '+status+' class="btn btn-default" onclick="sumar_pedido({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})" type="button">+</button>';
														$pedido+='	        <button '+status+' disabled="1" id="cantidad_'+value['id']+'" class="btn btn-default" type="button">'+value['cantidad']+'</button>';
														$pedido+='	        <button id="btn_restar_'+value['id']+'" '+status+' '+status_admin+' class="btn btn-default" onclick="lessProduct({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})" type="button">-</button>';
														$pedido+='		</span>';
														$pedido+='		<input '+status+' type="text" disabled="1" class="form-control" value="'+value['nombre']+'">';
														$pedido+='		<span '+status+' class="input-group-addon" id="basic-addon1">'+value['precio']+'</span>';
														$pedido+='		<span class="input-group-btn" id="span_accion_'+value['id']+'">';
														$pedido+='		</span>';
														$pedido+='	</div>';
														
														$(".GtableUserContent").append($pedido);
														$("#span_accion_"+value['id']).append($boton);
													});
									       		}
												
												$(".GtableProductsContent").html(personContent);
												
												reloadMenuEvents();
											}).fail(function(resp){
												console.log('================= Fail agregar materiales');
												console.log(resp);
											});
										});
										
										
										
										
										
										$('.btnCancelItems').off('click');
										$(".btnCancelItems").click(function(){
											$(".GtableProductsContent").html(personContent);
											
											reloadMenuEvents();
										});
									
					       		// Carga el producto a la persona si no tiene materiales
					       			}else{
					       				console.log('idproducto: '+idproduct+'  idcomanda: '+idcomanda+'  iddep: '+iddep);
					       				loadUserProducts(idproduct, person, idcomanda, 'addProduct', 0, iddep);
					       				
					       				$(".GtableProductsContent").html(personContent);
					       				
										reloadMenuEvents();
					       			}
					   			}
							});
					}else
						alert("Seleccione una orden para agregar productos");
				});
			}
			
			function setValue(val){
				persons=val+1;
			}
			
			function setUsersArrows(){
				if($(".GtableUserContent").height()<$(".GtableUserContent").prop('scrollHeight')){
					$(".userLeft").css('visibility', 'visible');
					$(".userRight").css('visibility', 'visible');
				}else{
					$(".userLeft").css('visibility', 'hidden');
					$(".userRight").css('visibility', 'hidden');
				}
			}
			
			function setMenuArrows(){
				if($(".GtableMenuContent").height()<$(".GtableMenuContent").prop('scrollHeight')){
					$(".menuLeft").css('visibility', 'visible');
					$(".menuRight").css('visibility', 'visible');
				}else{
					$(".menuLeft").css('visibility', 'hidden');
					$(".menuRight").css('visibility', 'hidden');
				}	
			}
			
			function setProductsArrows(){
				if($(".GtableProductsContent").height()<$(".GtableProductsContent").prop('scrollHeight')){
					$(".productsLeft").css('visibility', 'visible');
					$(".productsRight").css('visibility', 'visible');
				}else{
					$(".productsLeft").css('visibility', 'hidden');
					$(".productsRight").css('visibility', 'hidden');
				}	
			}
			
			function eventGetFamilies(obj){
                $("#divloading").fadeIn("slow");
				console.log('-----------> objeto eventGetFamilies');
				console.log(obj);
				
				$(".btnLine").hide();
				var iddeparment=obj.attr('iddeparment');
				$(".btnFamily").attr('iddeparment',iddeparment);
				
				$.ajax({
					data:{idDeparment:iddeparment},
		       		url:'ajax.php?c=comandas&f=getFamilies',
		       		type: 'GET',
		       		dataType: 'json',
		       		success: function(callback){ 
						console.log('----------->response eventGetFamilies');
						console.log(callback);
						
                		$("#divloading").fadeOut("slow");
						
		       			$(".GtableMenuContent").html("");
		       			$(".GtableProductsContent").html("");
		       			$(".btnFamily").show();
		       		
		       		// Crea botones de cada familia y las agrega a la div
						$.each(callback["families"]["rows"], function(index, value) {
							$familia='	<button type="button" class="btn btn-default btn-lg btnItemFamily" style="font-size:13px; width:130px; margin-top:1%" idfamily="'+value['idFam']+'">';
							$familia+=		value['nombre'].substring(0, 11);
							$familia+='	</button>';
							
							$(".GtableMenuContent").append($familia);
						});
					
					// Carga los productos filtrados por departamento
						$.each(callback["products"]["rows"], function(index, value) {
							if(value['especial']){
								var $clase='info';
							}else{
								var $clase='default';
							}
						
							$div='	<div class="pull-left GtableProduct" style="padding:5px" idproducto="'+value['idProducto']+'" idcomanda="<?php echo $row['id'] ?>" materiales="'+value['materiales']+'" tipo="'+value['tipo']+'" iddep="'+value['idDep']+'">';
							$div+='		<button type="button" class="btn btn-'+$clase+'" title="'+value['materiales']+'" style="width: 103px;height: 148px">';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					<table>';
							$div+='						<tr>';
							$div+='							<td style="font-size: 12px" align="center">';
							$div+='								'+value['nombre'].substring(0, 25);
							$div+='							</td>';
							$div+='						</tr>';
							$div+='					</table>';
							$div+='				</div>';
							$div+='			</div>';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					<input type="image" alt=" " style="width:80px;height:80px" src="'+value['imagen']+'"/>';
							$div+='				</div>';
							$div+='			</div>';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					$ '+value['precioventa'];
							$div+='				</div>';
							$div+='			</div>';
							$div+='		</button>';
							$div+='	</div>';
							
							$(".GtableProductsContent").append($div);
						});
						
						reloadMenuEvents();
						setMenuArrows();
						setProductsArrows();
		   			}
				});	
			}
			
			function eventGetLines(obj){
				var idfamily=obj.attr('idfamily');
				$(".btnLine").attr('idfamily',idfamily);
				$.ajax({
					data:{idFamily:idfamily},
		       		url:'ajax.php?c=comandas&f=getLines',
		       		type: 'GET',
		       		dataType: 'json',
		       		success: function(callback){
		       			console.log('-	-	-	-	-	-		eventGetLines		-	-	-	-	-		-');
		       			console.log(callback);
		       			
						$(".GtableMenuContent").html("");
						$(".GtableProductsContent").html("");
						$(".btnLine").show();
						
						$.each(callback["lines"]["rows"], function(index, value) {
							$linea='	<button type="button" class="btn btn-default btn-lg btnItemLine" style="font-size:13px; width:130px; margin-top:1%" idline="'+value['idLin']+'">';
							$linea+=		value['nombre'].substring(0, 11);
							$linea+='	</button>';
							
							$(".GtableMenuContent").append($linea);
						});
						
						$.each(callback["products"]["rows"], function(index, value) {
							if(value['especial']){
								var $clase='info';
							}else{
								var $clase='default';
							}
						
							$div='	<div class="pull-left GtableProduct" style="padding:5px" idproducto="'+value['idProducto']+'" idcomanda="<?php echo $row['id'] ?>" materiales="'+value['materiales']+'" tipo="'+value['tipo']+'" iddep="'+value['idDep']+'">';
							$div+='		<button type="button" class="btn btn-'+$clase+'" title="'+value['materiales']+'" style="width: 103px;height: 148px">';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					<table>';
							$div+='						<tr>';
							$div+='							<td style="font-size: 12px" align="center">';
							$div+='								'+value['nombre'].substring(0, 25);
							$div+='							</td>';
							$div+='						</tr>';
							$div+='					</table>';
							$div+='				</div>';
							$div+='			</div>';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					<input type="image" alt=" " style="width:80px;height:80px" src="'+value['imagen']+'"/>';
							$div+='				</div>';
							$div+='			</div>';
							$div+='			<div class="row">';
							$div+='				<div class="col-md-12">';
							$div+='					$ '+value['precioventa'];
							$div+='				</div>';
							$div+='			</div>';
							$div+='		</button>';
							$div+='	</div>';
							
							$(".GtableProductsContent").append($div);
						});
						
						reloadMenuEvents();
						setMenuArrows();
						setProductsArrows();
		   			}
				});
			}
			
			function loadUserProducts(idproduct,idperson,idcomanda,idfunction,idorder,iddep){
				console.log('-------> Objeto loadUserProducts');
				console.log('idproduct:'+idproduct+',idperson:'+idperson+',idcomanda:'+idcomanda+',idfunction:'+idfunction+',idorder:'+idorder+',iddep:'+iddep);
				
			// loader
				$(".GtableUserContent").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
				
				$.ajax({
					data:{idproduct:idproduct, idperson:idperson, idcomanda:idcomanda, idorder:idorder, iddep:iddep},
		       		url:'ajax.php?c=comandas&f='+idfunction,
		       		type: 'GET',
		       		dataType: 'json',
		       	}).done(function(callback){
		       		console.log('-----------> done loadUserProducts');
		       		console.log(callback);
		       		
		       		if(idfunction == "addProduct"){
		       			if(callback["msg"] != null && callback["status"] == false){
		       				alert(callback["msg"]);
		       			}
		       			
		       			$(".btnProcess").css('visibility', 'visible');
		       		}
		       		
					$(".GtableUserContent").html("");
					$.each(callback["rows"], function(index, value) {
						var status='';
						var $pedido='';
						
					// Se elimino de cocina
						if(value['status']==3){
							status='disabled="1" style="background-color:#FF6961"';
						}
							
					// pedido procesado, solo se puede modificar por el admin
						if(value['status'] == 0 || value['status'] == 1 || value['status'] == 2 || value['status'] == 4){
							status='style="background-color:#77DD77"';
							var status_admin='disabled="1"';
							
							$boton='	<button class="btn btn-default" onclick="$id_pedido='+value['id']+'" type="button" data-toggle="modal" data-target="#modal_autorizar_pedido">';
							$boton+='		<i class="fa fa-key"></i> &nbsp;';
							$boton+='	</button>';
					// Pedido normal
						}else{
							$boton='	<button '+status+'class="btn btn-default" id="btn_eliminar_pedido_'+value['id']+'" type="button" onclick="deleteProduct({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})">';
							$boton+='		<i class="fa fa-trash"></i> &nbsp;';
							$boton+='	</button>';
						}
							
				    	$pedido+='	<div class="input-group">';
						$pedido+='		<input min="1" id="num_pedidos'+value['id']+'" type="number" class="form-control" style="width: 60px" value="1">';
						$pedido+='		<span class="input-group-btn">';
						$pedido+='	        <button '+status+' class="btn btn-default" onclick="sumar_pedido({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})" type="button">+</button>';
						$pedido+='	        <button '+status+' disabled="1" id="cantidad_'+value['id']+'" class="btn btn-default" type="button">'+value['cantidad']+'</button>';
						$pedido+='	        <button id="btn_restar_'+value['id']+'" '+status+' '+status_admin+' class="btn btn-default" onclick="lessProduct({idorder:'+value['id']+',idperson:'+idperson+',idcomanda:'+idcomanda+'})" type="button">-</button>';
						$pedido+='		</span>';
						$pedido+='		<input '+status+' type="text" disabled="1" class="form-control" value="'+value['nombre']+'">';
						$pedido+='		<span '+status+' class="input-group-addon" id="basic-addon1">'+value['precio']+'</span>';
						$pedido+='		<span class="input-group-btn" id="span_accion_'+value['id']+'">';
						$pedido+='		</span>';
						$pedido+='	</div>';
							
						$(".GtableUserContent").append($pedido);
						$("#span_accion_"+value['id']).append($boton);
					});
					
					reloadActionsEvents();
					setUsersArrows();
		   		}).fail(function(resp) {
		   			console.log("Fail loadUserProducts");
		   			console.log(resp);
		   			
		   			alert('Error al agregar el producto');
				});
			}
			
///////////////// ******** ---- 	color_fondo		------ ************ //////////////////
	///// Establece el color de fondo
	
			function color_fondo(){
			// Variable global
				if(posicion_color>6){
					posicion_color=1;
				}
				
				var col='';

			    colarray = [];
			    
			    colarray[1] = 'background-color: #4a72b2';
			    colarray[2] = 'background-color: #e6b54a';
			    colarray[3] = 'background-color: #87868a';
			    colarray[4] = 'background-color: #6eaa6f';
			    colarray[5] = 'background-color: #76aadb';
			    colarray[6] = 'background-color: #f4e16a';
				
				col = colarray[posicion_color];
				
				posicion_color++;
				
				return col;
			}
			
///////////////// ******** ---- 	FIN color_fondo		------ ************ //////////////////
		

///////////////// ******** ---- 	separar_mesas		------ ************ //////////////////
	//////// Separa las mesas unidas
		// Como parametros puede recibir:
			// $objeto-> un objeto con el contenido del regitro de la mesa
					// idprincipal-> id de union en la tabla com_union
					// idmesa-> el id de la mesa en la que se guardaran los pedidos
					// idcomanda-> el id de la comanda
		
			function separar_mesas($objeto){
				$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=separar_mesas',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('-----> Response separar_mesas');
					    console.log(resp);
					   
					// Si todo sale bien redirecciona a la ventana de mesas
					    if(resp.length<=0){
					    // Redirecciona a la ventana de mesas
							var pathname = window.location.pathname;
						
							$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
					// Si no manda un mensaje de error
						}else{
					    	alert('Error: \n'+resp);
					    }
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	separar_mesas		------ ************ //////////////////
		

///////////////// ******** ---- 	guardar_promedio_comensal		------ ************ //////////////////
	//////// Registra el promedio por comensal de la comanda
		// Como parametros puede recibir:
			// 	promedio -> promedio por comensal de la comanda a registrar
			//	comanda -> id de la comanda
		
			function guardar_promedio_comensal($objeto){
				$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=guardar_promedio_comensal',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('-----> Response promedio comensal');
					    console.log(resp);
					   
					// Error: Manda un mensaje con el error
					    if(!resp){
					    	alert('Error: \n Error al actualizar el promedio por comensal en la BD');
					    }
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	guardar_promedio_comensal		------ ************ //////////////////
		

///////////////// ******** ---- 	detalles_mesa		------ ************ //////////////////
	//////// Obtiene los datos de la mesa
		// Como parametros puede recibir:
			//	id -> id de la mesa
		
			function detalles_mesa($objeto){
				console.log('-----> objeto detalles_mesa');
				console.log($objeto);
				
				$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=detalles_mesa',
				    type: 'GET',
				    dataType:'json',
				}).done(function(resp){
						console.log('-----> done detalles_mesa');
				    	console.log(resp);
				    	
				    	if(resp['nombre']){
				    		$nombre_mesa = resp['nombre'];
				    	}
				    	
					// Error: Manda un mensaje con el error
					    if(!resp['mesero']){
					// Todo bien :D, Imprime el nombre del mesero
					    }else{
					    	var mesa = $("#mesa").text();
					    	
					    	$('#mesa').html($nombre_mesa);
					    	
					    	$('#mesero').html(resp['mesero']);
					    }
				}).fail(function(resp){
						console.log('-----> fail detalles_mesa');
				    	console.log(resp);
				});
			}
			
///////////////// ******** ---- 	FIN	detalles_mesa		------ ************ //////////////////

///////////////// ******** ---- 		mesas_libres		------ ************ //////////////////
	//////// Consulta en la BD las mesas libres
		// Como parametros recibe:
			// id -> id de la mesa
		
		function mesas_libres($objeto) {
			$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=mesas_libres',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
				    	console.log('-----> Response Mesas libres');
				    	console.log(resp);
				   	}
			});
		}

///////////////// ******** ---- 		FIN mesas_libres		------ ************ //////////////////


///////////////// ******** ---- 		mudar_comanda		------ ************ //////////////////
	//////// Muda la comanda de mesa
		// Como parametros recibe:
			// id -> id de la mesa
		
		function mudar_comanda($objeto) {
		// Envia un mensaje que la comanda se esta mudando
			var $mensaje = 'Mudando comanda ...';
			$('#mesa_'+$objeto['mesa']).notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});
							
			$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=mudar_comanda',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
				    	console.log('---	-	-	-	-	Mudar comanda');
				    	console.log(resp);
				    	
				    	if(resp==true){
			       			var pathname = window.location.pathname;
			       			$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
				    	}else{
				    		var $mensaje = 'Error al mudar la comanda';
							$('#mesa_'+$objeto['mesa']).notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});
				    	}
				   	}
			});
		}

///////////////// ******** ---- 		FIN mudar_comanda		------ ************ //////////////////

///////////////// ******** ---- 	eliminar_comanda		------ ************ //////////////////
	//////// Obtiene la contraseña de seguridad y elimina la mesa si es correcta
		// Como parametros puede recibir:
			//	pass -> contraseña a bsucar
		
			function eliminar_comanda($objeto){
				$.ajax({
					data:$objeto,
					url : 'ajax.php?c=configuracion&f=pass',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
				    	console.log(resp);
				    	
				    	if(resp!=$objeto['pass']){
				    		var $mensaje = 'Contraseña incorrecta';
							$('#pass').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
				    	}
				    		
				    	if(resp==$objeto['pass']){
				    		var $mensaje = 'Eliminando...';
							$('#pass').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});
							
						// Valida que exista reservacion
							var $reservacion=0;
							if($objeto['reservacion']){
								$reservacion=$objeto['reservacion'];
							}
							
							$objeto['id_reservacion']=$reservacion;
							$objeto['idmesa']=<?php echo $idmesa; ?>;
				       		
				       		console.log('-	-	-	-	-	Objeto desde Eliminar');
							console.log($objeto);
							
				    		$.ajax({
								data:{
									idcomanda:$objeto['comanda'], 
									id_reservacion:$reservacion, 
									idmesa:"<?php echo $idmesa; ?>"
								},
				       			url:'ajax.php?c=comandas&f=deleteComanda',
				       			type: 'GET',
				       			success: function(callback){
				       				console.log('-	-	-	-	-	Response desde Eliminar');
				       				console.log(callback);
				       				
				       				var pathname = window.location.pathname;
									$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
				   				}
							});
				    	}
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	eliminar_comanda		------ ************ /////////////////

///////////////// ******** ---- 		closeComanda			------ ************ //////////////////
//////// Cierra la comanda
	// Como parametros puede recibir:
			// bandera -> Como se debe cerrar la comanda (0 -> todo junto, 1 -> individual, 2 -> se paga en caja, 3 -> se manda a caja) 
			
			function closeComanda($objeto){
				console.log('--------- > objeto entrada cerrar Comanda');
		       	console.log($objeto);
		       	
				var $servicio = '<?php echo $tipo; ?>';
				var $nombre = '<?php echo $nombre; ?>';
				var $direccion = '<?php echo $direccion; ?>';
				var $tel = '<?php echo $tel; ?>';
				var $id_reservacion = '<?php echo $id_reservacion; ?>';
				
				var bandera = $objeto['bandera'];
				var idmesa = "<?php echo $idmesa; ?>";
				var tipo = "<?php echo $_GET['tipo']; ?>";
				
			// Valida si la comanda se cierra por persona o normal
				if($objeto['cerrar_persona'] == 1){
					console.log('Cerrar comanda personal '+$objeto['id_comanda']);
					$objeto['idComanda'] = $objeto['id_comanda'];
				}else{
					$objeto['idComanda'] = "<?php echo $row['id']; ?>";
				}
				
			// Valida que el numero de comensales sea 1 o mas
				var $num_comensales = $('#num_comensales').val();
				if($num_comensales < 1){
					$num_comensales = 1;
				}
			
			// Armamos el array para el Ajax
				$objeto['bandera'] = bandera;
				$objeto['idmesa'] = idmesa;
				$objeto['tipo'] = tipo;
				$objeto['tel'] = $tel;
				$objeto['id_reservacion'] = $id_reservacion;
			
				console.log('--------- > objeto antes ajax cerrar Comanda');
		       	console.log($objeto);
				
				$(".GtableCloseComanda").css('visibility', 'hidden');
				
				$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=closeComanda',
				    type: 'GET',
				    dataType:'json',
				}).done(function(callback) {
		       		console.log('--------- > Done Cerrar comanda');
		       		console.log(callback);
		       			
		                var txt_propina = '';
		                var persona = 0;
		                var totalPersona = 0;
		                var $promedio_comensal = 0;
		                var totalComanda = 0;
		                var idComanda = "<?php echo $row['id']; ?>";
		                var bandera = 0;
		                var logo = callback['logo'];
		                var sub_total = 0;
		                var impuestos = 0;
		                
					// La comanda se cierra pagando todo junto
						if(callback['tipo'] == 0){
							var html = '<script src="../../libraries/JsBarcode.all.min.js"><\/script><script src="js/comandas/comandas.js"><\/script>        <div style="text-align:left;font-size:14px">';
							 
							html += '	<div>';
							html += '		<input type="image" src="../../netwarelog/archivos/1/organizaciones/' + logo + '" style="width:180px"/>';
							html += '	</div>';
							
			
							html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
							html += '	Mesa: ' + callback['rows'][0]['nombre_mesa'];
							html += '</div>';
			
							var bcontent = "";
		                	var codigo = "";
		                	// var $limite = 0;
		                	
			                $.each(callback['rows'], function(index, value) {
			                 	console.log('Mesa: '+$nombre_mesa);
			                 	
			                	if(persona!=value['npersona']){
			                		html = html.replace(">Orden No:"+persona,">Orden No: "+persona);
			                 		html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">Orden No:'+value['npersona']+'</div>';
			                 		persona = value['npersona'];
			                 		totalPersona = 0;
			                 	}
			                 	
			                 	if(!bandera){
			                 		bandera=1;
			                 		
			                 	// Para llevar
			                 		if(value['tipo'] == "1"){
			                 			bcontent = '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			                 			bcontent += '		Nombre: '+value['nombreu'];
			                 			bcontent += '	</div>';
			                 			
			                 			if(value['domicilio']){
				              				bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				bcontent += '		Domicilio: '+value['domicilio'];
				              				bcontent += '	</div>';
			                 			}
			                 			
			                 			if(callback['tel']){
				              				bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				bcontent += '		Tel: '+callback['tel'];
				              				bcontent += '	</div>';
			                 			}
			                 		}
			                 		
			                 	// Servicio a domicilio
			                 		if(value['tipo'] == "2"){
			              				bcontent = '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			              				bcontent += '		Nombre: '+value['nombreu'];
			              				bcontent += '	</div>';
			                 			
			                 			if(value['domicilio']){
				              				bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				bcontent += '		Domicilio: '+value['domicilio'];
				              				bcontent += '	</div>';
			                 			}
			                 			
			                 			if(callback['tel']){
				              				bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				bcontent += '		Tel: '+callback['tel'];
				              				bcontent += '	</div>';
			                 			}
			              			}
			              			
			              			codigo = value['codigo'];
			                 	}
			     
			                	html += '<div style="margin-left:15px">';
			                	html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
			                	html += '		<tr>';
			                	html += '			<td>'+value['cantidad']+'</td>';
			                	html += '			<td>'+value['nombre']+'</td>';
			                	html += '			<td>'+parseFloat(value['precioventa'] * value['cantidad']).toFixed(2)+'</td>';
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
				                	
				                	var $costo_extra = 0;
				                	
								// Lista los materiales extra con su costo
									$.each(value['costo_extra'], function(c, cc) {
										html += '	<tr>';
				                		html += '		<td></td>';
				                		html += '		<td></td>';
									    html += '		<td>'+cc['nombre']+': </td>';
									    html += '		<td>$ '+parseFloat(cc['costo'] * value['cantidad'])+'</td>';
									    html += '	</tr>';
									    
									    $costo_extra += parseFloat(cc['costo'] * value['cantidad']);
									    
				                		console.log('---	-	-	-	-	-	-	$costo_extra: ' + $costo_extra);
				                	});
				               		
									html += '	</table>';
				                	html += '</div>';
				                	
				                	console.log('--- totalPersona: '+totalPersona+'--- totalComanda'+totalComanda+'--- impuestos'+impuestos);
				                	
			                		totalPersona += parseFloat($costo_extra);
			                		totalComanda += parseFloat($costo_extra);
			                		
				                	console.log('--- totalPersona: '+totalPersona+'--- totalComanda'+totalComanda+'--- impuestos'+impuestos);
			                	
			                	}
				                
			                	totalPersona += parseFloat(value['precioventa'] * parseFloat(value['cantidad']));
			                	totalComanda += parseFloat(value['precioventa'] * parseFloat(value['cantidad']));
			                	impuestos += parseFloat(value['impuestos'] * parseFloat(value['cantidad']));
			                	
			      				$promedio_comensal += totalPersona;
			                });
			                
			      			$promedio_comensal = ($promedio_comensal/$num_comensales);
			      			
						// Registra el promedio por comensal en la comanda
			                guardar_promedio_comensal({promedio:$promedio_comensal, comanda: idComanda, personas:$num_comensales});
			                
			                html = html.replace(">Orden No:"+persona,">Orden No: "+persona);
			                html = html.replace(">Comanda No:"+idComanda,">Comanda No: "+idComanda+" / Mesa:"+$nombre_mesa);
			                var propina = totalComanda * 0.10;
			                html += bcontent;
			                
			                if(callback['mostrar'] == 1){
			                	txt_propina='Propina sugerida: '+propina.toFixed(2);
			                	html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            					html += '	'+txt_propina;
            					html += '	</div>';
			                }
			                
			                totalComanda = totalComanda.toFixed(2);
			                sub_total = (totalComanda - impuestos).toFixed(2);
			                impuestos = impuestos.toFixed(2);
						
						// Sub total
            				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            				// html += '		Sub total: <strong>$'+sub_total+'</strong>';
            				// html += '	</div>';
//             				
						// // Impuestos
            				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            				// html += '		Impuestos: <strong>$'+impuestos+'</strong>';
            				// html += '	</div>';
			                
            				html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
            				html += '		Total: <strong>$'+totalComanda+'</strong>';
            				html += '	</div>';
            				html += '	<div style="margin-top:10px;">';
            				html += '		<img id="'+codigo+'" style="width:190px;margin-left:-3px;"/>';
            				html += '	</div>';
            				html += '</div>';
            			
            			// Actualiza el total de la comanda si es por persona
            				if($objeto['cerrar_persona'] == 1 && totalComanda){
            					comandas.actualizar_comanda({id: $objeto['idComanda'], total: totalComanda});
            				}
            				
            			// Codigo de barras
       						html += '<script>comandas.codigo_barras({id:\''+codigo+'\', codigo:\''+codigo+'\'});<\/script>';
       						console.log(html);
       						
       						bandera = 0;
       						bcontent = "";
			                
			                var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
						    
						    $(ventana).ready(function(){
						    	ventana.document.write(html);  //imprimimos el HTML del objeto en la nueva ventana
						    	// ventana.resizeTo(207.87,ventana.document.body.firstElementChild.clientHeight);
						    	ventana.document.close();  //cerramos el documento
						    
						    // Redirecciona
			                	setTimeout(closew,1000);
				                function closew(){
				                	ventana.print();  //imprimimos la ventana
				                	ventana.close();
				                	var pathname = window.location.pathname;
				                	
								// Valida si la comanda se cierra por persona o normal
									if($objeto['cerrar_persona'] != 1){
									// Si es reimprimir no dirige al mapa de mesas
										if($objeto['reimprime'] != 1){
											console.log('======> salta reimprime');
											console.log(callback['tipo_operacion']);
										// Recarga la pagina en lugar de redirigir al mapa de mesas
											if(callback['tipo_operacion'] == 3){
												
												console.log('======> Entra tipo operacion 3');
												
												$('#modal_recargar').modal('show');
												window.location.reload();
											}else{
												console.log('======> se pasa por los webos el tipo de operacion');
												$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
											}
										}
									}else{
										console.log('======> Recarga cerrar_persona');
										window.location.reload();
									}
				                }
							});
						}
					// FIN La comanda se cierra pagando todo junto
					
					// La comanda se cierra pagando individual
						if(callback['tipo'] == 1){
							var html ='<script src="../../libraries/JsBarcode.all.min.js"><\/script><script src="js/comandas/comandas.js"><\/script>';
		                	
			                $.each(callback['rows'], function(index, value) {
			                 	totalPersona = 0;
			              		codigo = value['codigo'];
			              		impuestos = 0;
			                	
			                	html +='	<div>';
								html +='		<input type="image" src="../../netwarelog/archivos/1/organizaciones/'+logo+'" style="width:180px"/>';
								html +='	</div>';
								html +='	<div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">';
								html +='		Orden:'+index+' / Mesa: '+$nombre_mesa;
								html +='	</div>';
			                	
		                	// Servicio a Domicilio o para llevar
			                	if($servicio == 2){
				                	html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
				                	html += '	Nombre: '+$nombre;
				                	html += '</div>';
				                	html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
				                	html += '	Direccion: '+$direccion;
				                	html += '</div>';
			                	}
			                	
			                 	console.log('Mesa: '+$nombre_mesa);
			                 	if(!bandera){
			                 		bandera = 1;
			                 		
			                 	// Para llevar
			                 		if(value['tipo']=="1"){
			              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			              				html += '		Nombre: '+value['nombre_usuario'];
			              				html += '	</div>';
			              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			              				html += '		Domicilio: '+value['domicilio'];
			              				html += '	</div>';
			              				
			              				if(callback['tel']){
				              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				html += '		Tel: '+callback['tel'];
				              				html += '	</div>';
			                 			}
			                 		}
			                 		
			                 	// Servicio a domicilio
			                 		if(value['tipo']=="2"){
			              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			              				html += '		Nombre: '+value['nombre_usuario'];
			              				html += '	</div>';
			              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			              				html += '		Domicilio: '+value['domicilio'];
			              				html += '	</div>';
			              				
			              				if(callback['tel']){
				              				html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				              				html += '		Tel: '+callback['tel'];
				              				html += '	</div>';
			                 			}
			              			}
			                 	}
			     			
			     			// Pedidos de la persona
			                	$.each(value['pedidos'], function(i, v) {
			                		totalPersona += parseFloat(v['precioventa'] * v['cantidad']);
			                		impuestos += parseFloat(v['impuestos'] * v['cantidad']);
			                		
			                		html += '<div style="margin-left:15px">';
				                	html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
				                	html += '		<tr>';
				                	html += '			<td>'+v['cantidad']+'</td>';
				                	html += '			<td>'+v['nombre']+'</td>';
				                	html += '			<td>'+parseFloat(v['precioventa'] * v['cantidad']).toFixed(2)+'</td>';
				                	html += '		</tr>';
				                	html += '	</table>';
				                	html += '</div>';
				                	
				                 // Si existen materiales con costo extra los agrega a la cuenta
									if(v['costo_extra']){
										html += '<div style="margin-left:15px">';
					                	html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
					                	html += '		<tr>';
					                	html += '			<td></td>';
					                	html += '			<td>=> Extras:</td>';
					                	html += '		</tr>';
					                	
					                	var $costo_extra = 0;
					                	
									// Lista los materiales extra con su costo
										$.each(v['costo_extra'], function(c, cc) {
											html += '	<tr>';
					                		html += '		<td></td>';
					                		html += '		<td></td>';
										    html += '		<td>'+cc['nombre']+': </td>';
										    html += '		<td>$ '+parseFloat(cc['costo'] * v['cantidad'])+'</td>';
										    html += '	</tr>';
										    
										    $costo_extra += parseFloat(cc['costo'] * v['cantidad']);
				                			totalPersona += parseFloat($costo_extra);
										    
					                		console.log('---	-	-	-	-	-	-	$costo_extra: ' + $costo_extra);
					                	});
					               		
										html += '	</table>';
					                	html += '</div>';
					                	
					                	console.log('--- totalPersona: '+totalPersona+'---- impuestos'+impuestos);
				                	}
			                	});
			                	
			      				$promedio_comensal += totalPersona;
				                sub_total = (totalPersona - impuestos).toFixed(2);
				                propina = parseFloat(totalPersona * 0.10).toFixed(2);
				                impuestos = impuestos.toFixed(2);
							
							// Sub total
	            				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
	            				// html += '		Sub total: <strong>$'+sub_total+'</strong>';
	            				// html += '	</div>';
// 	            				
							// // Impuestos
	            				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
	            				// html += '		Impuestos: <strong>$'+impuestos+'</strong>';
	            				// html += '	</div>';
				                
	            				html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
	            				html += '		Total: <strong>$'+totalPersona+'</strong>';
	            				html += '	</div>';
	            				
				                if(callback['mostrar'] == 1){
				                	txt_propina = 'Propina sugerida: '+propina;
				                	html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
	            					html += '	'+txt_propina;
	            					html += '	</div>';
				                }
				                
	            				html += '	<div style="margin-top:10px;">';
	            				html += '		<img id="'+codigo+'" style="width:190px;margin-left:-3px;"/>';
	            				html += '	</div>';
	            				html += '</div>';
	            			
	            			// Codigo de barras
	       						html += '<script>comandas.codigo_barras({id:\''+codigo+'\', codigo:\''+codigo+'\'});<\/script>';
			                });//FIN Each Personas
			                
			      			$promedio_comensal = ($promedio_comensal/$num_comensales);
			      			
						// Registra el promedio por comensal en la comanda
			                guardar_promedio_comensal({promedio:$promedio_comensal, comanda: idComanda, personas:$num_comensales});
       						console.log(html);
			                
			                var ventana=window.open('','_blank','width=207.874015748,height=10,leftmargin=0');  //abrimos una ventana vacía nueva
						    
						    $(ventana).ready(function(){
						    	ventana.document.write(html);  //imprimimos el HTML del objeto en la nueva ventana
						    	ventana.document.close();  //cerramos el documento
						    
						    // Redirecciona
			                	setTimeout(closew,1000);
				                function closew(){
				                	ventana.print();  //imprimimos la ventana
				                	ventana.close();
				                	var pathname = window.location.pathname;
				                
				                // Valida si la comanda se cierra por persona o normal
									if($objeto['cerrar_persona'] != 1){
									// Si es reimprimir no dirige al mapa de mesas
										if($objeto['reimprime'] != 1){
										// Recarga la pagina en lugar de redirigir al mapa de mesas
											if(callback['tipo_operacion'] == 3){
												console.log('======> Entra tipo operacion 3');
												window.location.reload();
											}else{
												$("#tb2156-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
											}
										}
									}else{
										console.log('======> Recarga cerrar_persona');
										window.location.reload();
									}
				                }
							});
						}
					// FIN La comanda se cierra pagando individual
					
					// La comanda se cierra pagando directo en caja
						if(callback['tipo'] == 2){
						// Todo bien regresa el codigo de la comanda
							if(callback['rows'][0]['respuesta'] == "ok"){
							// Inicializamos variables
								var codigo = callback['rows'][0]['comanda'];

								console.log('------> codigo');
								console.log(codigo);
								
								var outElement = $("#tb2156-u",window.parent.document).parent();
								var caja = outElement.find("#tb2051-u");
								var pestana = $("body",window.parent.document).find("#tb2051-1");
								var openCaja = $("body",window.parent.document).find("#mnu_2051");
								var pathname = window.location.pathname;
								var url = document.location.host+pathname;
							
								if(caja.length>0){
								// Valida que exista un codigo
									if(!codigo){
										$mensaje = 'Error al obtener el codigo de la comanda';
										$.notify($mensaje, {
											position : "top center",
											autoHide : true,
											autoHideDelay : 5000,
											className : 'error',
											arrowSize : 15
										});
										
										return 0;
									}
									
									$objeto['id'] = $objeto['idComanda'];
									$objeto['status'] = 2;
				
								// Cambia el status de la comanda a cerrada y redirecciona al mapa de mesas
									$.ajax({
										data : $objeto,
										url : 'ajax.php?c=comandas&f=actualizar_comanda',
										type : 'GET',
										dataType : 'json',
									}).done(function(resp) {
										console.log('---------> Success actualizar_comanda');
										console.log(resp);
										
									// Valida si la comanda se cierra por persona o normal
										if($objeto['cerrar_persona'] != 1){
										// Si es reimprimir no dirige al mapa de mesas
											if($objeto['reimprime'] != 1){
												console.log('======> salta reimprime');
												console.log(callback['tipo_operacion']);
											// Recarga la pagina en lugar de redirigir al mapa de mesas
												if(callback['tipo_operacion'] == 3){
													
													console.log('======> Entra tipo operacion 3');
													$('#modal_reiniciar').modal({
														keyboard: false,
														show: true
													});
													
													setTimeout(function() {
													// Abre la pestaña de caja						
														openCaja.trigger('click');
														pestana.trigger('click');
														
													// Selecciona el campo de busqueda
														var campoBuscar = $(".frurl",caja).contents().find("#search-producto");
														var campoCliente=$(".frurl",caja).contents().find("#cliente-caja"); // ch@
														campoBuscar.trigger("focus");
													
													// Agrega el codigo de la comanda y busca sus productos
														campoBuscar.val(codigo);
														campoCliente.val($nombre); // ch@
														campoBuscar.trigger({type: "keypress", which: 13});
													}, 500);
												}else{
												// Abre la pestaña de caja						
													openCaja.trigger('click');
													pestana.trigger('click');
													
												// Selecciona el campo de busqueda
													var campoBuscar = $(".frurl",caja).contents().find("#search-producto");
													var campoCliente = $(".frurl",caja).contents().find("#cliente-caja"); // ch@
													campoBuscar.trigger("focus");
												
												// Agrega el codigo de la comanda y busca sus productos
													campoBuscar.val(codigo);
													
													console.log('======> nombre cliente: ' + $nombre);
													campoCliente.val($nombre); // ch@
													campoBuscar.trigger({type: "keypress", which: 13});
													
												// Redirecciona al mapa de mesas
													console.log('======> Redirecciona al mapa de mesas');
													var pathname = window.location.pathname;
													$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
												}
											}
										}else{
											console.log('======> Recarga cerrar_persona');
											window.location.reload();
										}
									}).fail(function(resp) {
										console.log('---------> Fail actualizar_comanda');
										console.log(resp);

										$mensaje = 'Error al actualizar la comanda';
										$.notify($mensaje, {
											position : "top center",
											autoHide : true,
											autoHideDelay : 5000,
											className : 'error',
											arrowSize : 15
										});
									}); // Fin actualizar comanda
								}else{
									alert("No se Puede Cerrar Comanda, Favor de Abrir La Caja");
								}
							}
						}
					// FIN La comanda se cierra pagando directo en caja
				
					// La comanda se manda a caja
						if(callback['tipo'] == 3){
						// Todo bien :D, Redirecciona al mapa de mesas
							if(callback['rows'][0]['respuesta'] == "ok"){
								var pathname = window.location.pathname;
								$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://'+document.location.host+pathname+'?c=comandas&f=menuMesas');
							}else{
								alert("Error al cerrar la comanda");
							}
						}
		   		}).fail(function(resp) {
					console.log('---------> Fail Closecomanda');
					console.log(resp);
			
					$mensaje = 'Error al cerrar la comanda';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});// Fin ajax
			}
			
///////////////// ******** ---- 	FIN closeComanda		------ ************ //////////////////

///////////////// ******** ---- 		cerrar_personalizado		------ ************ //////////////////
	//////// Carga la vista para cerrar la comanda de manera personalizada
		// Como parametros recibe:
			// servicio -> si es para llevar, a domicilio o normal
			// nombre -> nombre del cliente si es servicio a domicilio
			// dirreccion -> direccion del cliente
			// id_reservacion -> id de la reservacion
			// num_comensales -> numero de comensales de la comanda
			// idcomanda -> ID de la comanda
			// idmesa -> ID de la mesa
			// tipo -> tipo de comanda
		
		function cerrar_personalizado($objeto) {
			console.log('-----> $objeto cerrar_personalizado');
			console.log($objeto);
    		
		// Loader
			$('#'+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
			
			$.ajax({
				data:$objeto,
			    url:'ajax.php?c=comandas&f=vista_cerrar_personalizado',
			    type: 'GET',
			    dataType:'html',
			    success: function(resp){
			    	console.log('-----> Response cerrar_personalizado');
			    	console.log(resp);
			    	
			    	$('#'+$objeto['div']).html(resp);
							
				// Error: Manda un mensaje con el error
				    if(!resp){
				    	var $mensaje='Error: \n Error al cargar la comanda';
				
						$('#'+$objeto['div']).notify(
							$mensaje,
							{
								position:"top center",
						  		autoHide: true,
								autoHideDelay: 5000, 
								className: 'error',
							}
						);
						
						return 0;
				    }
			   	}
			});
		}

///////////////// ******** ---- 		FIN cerrar_personalizado		------ ************ //////////////////


///////////////// ******** ----  		deleteProduct					------ ************ //////////////////
	// Elimina la orden de la comanda y lista los productos de la persona
		// Como parametro puede recibi:
			// idorder -> ID del pedido
			// idcomanda -> ID de la comanda
			// idperson -> numero de  persona
		
		function deleteProduct($objeto) {
			console.log('---	-	-	-	-	$objeto deleteProduct');
			console.log($objeto);
			
		// Loader
			$(".GtableUserContent").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
			
			$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=deleteProduct',
				    type: 'GET',
				    dataType:'json',
			}).done(function(resp){
				console.log('---	-	-	-	-	Done deleteProduct');
				console.log(resp);
				    	
				$(".GtableUserContent").html('');
				
				$.each(resp["rows"], function(index, value) {
					var status='';
					var $pedido='';
					
				// Se elimino de cocina
					if(value['status']==3){
						status='disabled="1" style="background-color:#FF6961"';
					}
					
				// pedido procesado, solo se puede modificar por el admin
					if(value['status']==0||value['status']==1||value['status']==2||value['status']==4){
						status='style="background-color:#77DD77"';
						var status_admin='disabled="1"';
						
						$boton='	<button class="btn btn-default" onclick="$id_pedido='+value['id']+'" type="button" data-toggle="modal" data-target="#modal_autorizar_pedido">';
						$boton+='		<i class="fa fa-key"></i> &nbsp;';
						$boton+='	</button>';
				// Pedido normal
					}else{
						$boton='	<button '+status+'class="btn btn-default" id="btn_eliminar_pedido_'+value['id']+'" type="button" onclick="deleteProduct({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})">';
						$boton+='		<i class="fa fa-trash"></i> &nbsp;';
						$boton+='	</button>';
					}
							
				    $pedido+='	<div class="input-group">';
					$pedido+='		<input min="1" id="num_pedidos'+value['id']+'" type="number" class="form-control" style="width: 60px" value="1">';
					$pedido+='		<span class="input-group-btn">';
					$pedido+='	        <button '+status+' class="btn btn-default" onclick="sumar_pedido({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})" type="button">+</button>';
					$pedido+='	        <button '+status+' disabled="1" id="cantidad_'+value['id']+'" class="btn btn-default" type="button">'+value['cantidad']+'</button>';
					$pedido+='	        <button id="btn_restar_'+value['id']+'" '+status+' '+status_admin+' class="btn btn-default" onclick="lessProduct({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})" type="button">-</button>';
					$pedido+='		</span>';
					$pedido+='		<input '+status+' type="text" disabled="1" class="form-control" value="'+value['nombre']+'">';
					$pedido+='		<span '+status+' class="input-group-addon" id="basic-addon1">'+value['precio']+'</span>';
					$pedido+='		<span class="input-group-btn" id="span_accion_'+value['id']+'">';
					$pedido+='		</span>';
					$pedido+='	</div>';
							
					$(".GtableUserContent").append($pedido);
					$("#span_accion_"+value['id']).append($boton);
				});
						
				reloadActionsEvents();
				setUsersArrows();
			}).fail(function(resp) {
				console.log('---------> Fail delte producto');
				console.log(resp);
				
				reloadPersonEvents();
				loadUserProducts();
				
			// Manda un mensaje de error
				$mensaje = 'Error al eliminar el producto';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			});
		}

///////////////// ******** ---- 		FIN deleteProduct		------ ************ //////////////////


///////////////// ******** ----  		lessProduct					------ ************ //////////////////
	// Resta la cantidad de la orden y lista los productos de la persona
		// Como parametro puede recibi:
			// idorder -> ID del pedido
			// idcomanda -> ID de la comanda
			// idperson -> numero de  persona
		
		function lessProduct($objeto) {
			console.log('---	-	-	-	-	$objeto lessProduct');
			console.log($objeto);
			
		// Loader
			$(".GtableUserContent").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
			
			$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=lessProduct',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
				    	console.log('---	-	-	-	-	response lessProduct');
				    	console.log(resp);
				    	
				    	$(".GtableUserContent").html('');
				    	
				    	$.each(resp["rows"], function(index, value) {
							var status='';
							var $pedido='';
							
						// Se elimino de cocina
							if(value['status']==3){
								status='disabled="1" style="background-color:#FF6961"';
							}
							
						// pedido procesado, solo se puede modificar por el admin
							if(value['status']==0||value['status']==1||value['status']==2||value['status']==4){
								status='style="background-color:#77DD77"';
								var status_admin='disabled="1"';
								
								$boton='	<button class="btn btn-default" onclick="$id_pedido='+value['id']+'" type="button" data-toggle="modal" data-target="#modal_autorizar_pedido">';
								$boton+='		<i class="fa fa-key"></i> &nbsp;';
								$boton+='	</button>';
						// Pedido normal
							}else{
								$boton='	<button '+status+'class="btn btn-default" id="btn_eliminar_pedido_'+value['id']+'" type="button" onclick="deleteProduct({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})">';
								$boton+='		<i class="fa fa-trash"></i> &nbsp;';
								$boton+='	</button>';
							}
							
				    		$pedido+='	<div class="input-group">';
							$pedido+='		<input min="1" id="num_pedidos'+value['id']+'" type="number" class="form-control" style="width: 60px" value="1">';
							$pedido+='		<span class="input-group-btn">';
							$pedido+='	        <button '+status+' class="btn btn-default" onclick="sumar_pedido({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})" type="button">+</button>';
							$pedido+='	        <button '+status+' disabled="1" id="cantidad_'+value['id']+'" class="btn btn-default" type="button">'+value['cantidad']+'</button>';
							$pedido+='	        <button id="btn_restar_'+value['id']+'" '+status+' '+status_admin+' class="btn btn-default" onclick="lessProduct({idorder:'+value['id']+',idperson:'+$objeto['idperson']+',idcomanda:'+$objeto['idcomanda']+'})" type="button">-</button>';
							$pedido+='		</span>';
							$pedido+='		<input '+status+' type="text" disabled="1" class="form-control" value="'+value['nombre']+'">';
							$pedido+='		<span '+status+' class="input-group-addon" id="basic-addon1">'+value['precio']+'</span>';
							$pedido+='		<span class="input-group-btn" id="span_accion_'+value['id']+'">';
							$pedido+='		</span>';
							$pedido+='	</div>';
							
							$(".GtableUserContent").append($pedido);
							$("#span_accion_"+value['id']).append($boton);
						});
						
						reloadActionsEvents();
						setUsersArrows();
				   	}
			});
		}

///////////////// ******** ---- 		FIN lessProduct		------ ************ //////////////////

///////////////// ******** ----  		sumar_pedido					------ ************ //////////////////
	// Aumenta la cantidad de la orden y lista los productos de la persona
		// Como parametro puede recibi:
			// idorder -> ID del pedido
			// idcomanda -> ID de la comanda
			// idperson -> numero de  persona
		
		function sumar_pedido($objeto) {
			var $cantidad = parseInt($('#cantidad_'+$objeto['idorder']).html());
			var $num_pedidos = parseInt($('#num_pedidos'+$objeto['idorder']).val());
			$objeto['cantidad'] = $cantidad;
			$objeto['num_pedidos'] = $num_pedidos;
			
			console.log('---	-	-	-	-	$objeto sumar_pedido');
			console.log($objeto);
				
			for (i = 0; i < $num_pedidos; i++) {
			
				console.log('---	-	-	-	-	entrar for');
				console.log($objeto);
				$.ajax({
					data:$objeto,
					url:'ajax.php?c=comandas&f=sumar_pedido',
					type: 'GET',
					dataType:'json',
			    }).done(function(resp){
			    	console.log('---	-	-	-	-	done sumar_pedido');
			    	console.log(resp);
			    
			    // Error
			    	if(resp['status'] == 2){
			    		var $mensaje='Error al cargar aumentar la cantidad';
						$(".GtableUserContent").notify(
							$mensaje,
							{
								position:"top center",
								autoHide: true,
								autoHideDelay: 5000, 
								className: 'error',
							}
						);
						
						return 0;
					}
					
					if(resp['status'] == 1){
						$cantidad += 1;
						$('#cantidad_'+$objeto['idorder']).html($cantidad);
						$('#num_pedidos'+$objeto['idorder']).val(1);
					}
				}).fail(function(resp) {
					console.log('---------> Fail sumar_pedido');
					console.log(resp);
					
					reloadPersonEvents();
					
				// Manda un mensaje de error
					$mensaje = 'Error al aumentar la cantidad';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});
			}
		}

///////////////// ******** ---- 		FIN sumar_pedido		------ ************ //////////////////
		

///////////////// ******** ---- 	autorizar		------ ************ //////////////////
	//////// Obtiene la contraseña de seguridad y autoriza la asignacion de la mesa
		// Como parametros puede recibir:
			//	pass -> contraseña a bsucar
		
			function autoriza_asignacion($objeto){
				console.log('--------> Objet autoriza_asignacion');
				console.log($objeto);
			
				$.ajax({
					data:$objeto,
					url : 'ajax.php?c=configuracion&f=pass',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('--------> response pass autoriza_asignacion');
				    	console.log(resp);
				    	
				    	if(resp!=$objeto['pass']){
				    		var $mensaje = 'Contraseña incorrecta';
							$('#pass_asignacion').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
							
							return 0;
				    	}
				    		
				    	if(resp==$objeto['pass']){
				    	// Cierra la ventana de autoricacion
				    		$('#btn_cerrar_autorizacion').click();
				    	// Muestra la ventana para seleccionar al empleado
				    		$('#modal_asignar').modal();
				    	}
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	autorizar		------ ************ /////////////////
		
///////////////// ******** ---- 	asignar_mesa		------ ************ //////////////////
	//////// Asigna la mesa al mesero
		// Como parametros puede recibir:
			// empleado -> ID del mesero
			// mesa -> ID de la mesa
		
			function asignar_mesa($objeto){
				console.log('--------> Objet asignar_mesa');
				console.log($objeto);
			
				$.ajax({
					data:$objeto,
				    url:'ajax.php?c=comandas&f=asignar_mesa',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('--------> response asignar_mesa');
				    	console.log(resp);
				    	
				    	if(!resp){
				    		var $mensaje = 'Error al asignar';
							$('#pass_asignacion').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
							
							return 0;
				    	}
				    		
				    	if(resp['status']==1){
				    		var $mensaje = 'Asignacion guardada con exito';
				    		$.notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
							
				    	// Cierra la ventana de asignacion
				    		$('#btn_cerrar_asignacion').click();
				    		
				    	// Limpia el campo de password
				    		$('#pass_asignacion').val('');
				    		
				    	}
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	asignar_mesa		------ ************ /////////////////

///////////////// ******** ---- 	autorizar_pedido		------ ************ //////////////////
	//////// Obtiene la contraseña de seguridad y autoriza la modificacion del pedido
		// Como parametros puede recibir:
			//	pass -> contraseña a bsucar
		
			function autorizar_pedido($objeto){
				console.log('--------> Objet autorizar_pedido');
				console.log($objeto);
			
				$.ajax({
					data:$objeto,
					url : 'ajax.php?c=configuracion&f=pass',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('--------> response pass autorizar_pedido');
				    	console.log(resp);
				    	
				    	if(resp!=$objeto['pass']){
				    		var $mensaje = 'Contraseña incorrecta';
							$('#pass_pedido').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
							
							return 0;
				    	}
				    		
				    	if(resp==$objeto['pass']){
				    	// Cierra la ventana de autoricacion
				    		$('#btn_cerrar_pedido').click();
				    	
				    	// Limpia el campo de pass
				    		$('#pass_pedido').val('');
				    		
				    	// Habilita el boton para restar pedidos
				    		$('#btn_restar_'+$id_pedido).attr("disabled", false);
				    	}
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	autorizar_pedido		------ ************ /////////////////

///////////////// ******** ---- 	guardar_comensales		------ ************ //////////////////
	//////// Guarda el numero de comensales de la comanda
		// Como parametros puede recibir:
			//	comanda -> ID de la comanda
			// comensales -> numero de comensales
		
			function guardar_comensales($objeto){
				console.log('--------> Objet guardar_comensales');
				console.log($objeto);
			
				$.ajax({
					data:$objeto,
					url : 'ajax.php?c=comandas&f=guardar_comensales',
				    type: 'GET',
				    dataType:'json',
				    success: function(resp){
						console.log('--------> response guardar_comensales');
				    	console.log(resp);
				    	
				    	if(!resp){
				    		var $mensaje = 'Error al guardar los comensales';
							$('#arriba').notify($mensaje, {
								position : "bottom left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});
							
							return 0;
				    	}
				    	
				    	if(resp['status']==1){
				    		var $mensaje = 'Comensales guardados';
							$("#arriba").notify($mensaje, {
								position : "bottom left",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'success',
							});
				    	}
				   	}
				});
			}
			
///////////////// ******** ---- 	FIN	guardar_comensales		------ ************ /////////////////
		</script>
	</head>
	<body>
	<script>
	// Funciones iniciales
		detalles_mesa({id: <?php echo $idmesa ?>});
	</script><?php
	
	$id_comanda=$row['id']; ?>
		
<!-- Contenedor -->
	<div class="panel panel-default" style="height:100%">
		<div style=" z-index: 100; position:absolute;width:98%;height:98%;background:#ffffff;opacity:0.92;filter:alpha(opacity=92);visibility:hidden" align="center" class="GtableCloseComanda">
			<div style="background:#A4A4A4;width:70%;border-radius:10px;padding:5px 0px">
				<div style="width:98%;height:30px" align="right">
					<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
						<div style="width:30px;height:30px;font-weight:600;border-radius:20px;background:#424242;font-size:23px;color:#ffffff" align="center" class="btnClose">
							x
						</div>
					</a>
				</div>
				<div style="font-size:16px;font-family:verdana;font-weight:600;color:#ffffff;width:80%">
					Seleccione el tipo de cuenta que se va a realizar
				</div>
				<div style="margin-top:20px; padding:0% 5%" class="row">
					<div class="col-md-2">
						<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
							<div style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" align="center" class="btnIndividual">
								<div style="padding-top:10px">Individual</div>
							</div>
						</a>
					</div>
					<div class="col-md-2">
						<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
							<div style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" align="center" class="btnAll">
								<div style="padding-top:10px">Todo Junto</div>
							</div>
						</a>
					</div><?php
					// Valida que tenga permiso para pagar directo de caja
						$permiso_pagar = in_array(2156, $_SESSION["accelog_menus"]);
						if (!empty($permiso_pagar)) { ?>
							<div class="col-md-2">
								<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
									<div style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" align="center" class="btnPagar">
										<div style="padding-top:10px">Pagar</div>
									</div>
								</a>
							</div><?php
						} ?>
					
					<div class="col-md-2">
						<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
							<div style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" align="center" class="btn_mandar_caja">
								<div style="padding-top:10px">Mandar a caja</div>
							</div>
						</a>
					</div>
					<div 
						class="col-md-2" 
						onclick="cerrar_personalizado({
									servicio:'<?php echo $tipo; ?>',
									nombre:'<?php echo $nombre; ?>',
									direccion:'<?php echo $direccion; ?>',
									id_reservacion:'<?php echo $id_reservacion; ?>',
									num_comensales:$('#num_comensales').val(),
									idComanda:'<?php echo $row['id']; ?>',
									idmesa:'<?php echo $idmesa; ?>',
									tipo:'<?php echo $_GET['tipo']; ?>',
									div:'contenedor_personalizar'
								})" 
						data-toggle="modal" 
						data-target="#div_personalizar">
						<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
							<div style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" align="center">
								<div style="padding-top:10px">Dividir</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div style="position:absolute;width:98%;height:98%;background:#ffffff;opacity:0.92;filter:alpha(opacity=92);visibility:hidden" align="center" class="GtableActualizarComanda">
			<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff" class="btnRegresar">
				<div style="background:#00BFFF;width:20%;height:9%;border-radius:10px;padding-top:20px;margin-top:15%;font-family:verdana;font-size:20px;color:#ffffff">
					Regresar
				</div>
			</a>
		</div><?php
		
		$flag=false;
		$mesa=0;
		$ipo='Simple';
	
	// Mesa normal
		if($_GET['tipo']==0)
			$mesa=$idmesa;
								
	// Mesas juntas
		if($_GET['tipo']==-1){
			$ipo='Compuesta';
			$mesa='['.$idmesa.' Compuesta]';
		}
							
	// Obtiene el ID de la comanda
		$objeto['comanda']=$row['id']; 
		
		$num_comensales = (!empty($row['comensales'])) ? $row['comensales'] : $row['personas'] ;
		
		$flag=true; ?>
		
		<div id="arriba" class="panel-heading">
			<div class="row" style="margin-bottom: 1%;">
			<!-- Info comanda -->
				<div class="col-md-4" style=" margin-top: 0.7%; font-size: 25px">
					<div style="width: 100px; float: left">
						<i class="fa fa-cutlery"></i> <?php echo $row['id'] ?>
					</div>
					<div style="width: 100px; float: left">
						<i class="fa fa-object-group"></i> <?php echo $row['nombre_mesa'] ?>
					</div>
					<div style="width: 100px; float: left">
						<i class="fa fa-user"></i> <input type="number" min="1" id="num_comensales" onchange="guardar_comensales({comanda:<?php echo $row['id'] ?>, comensales:$('#num_comensales').val()})" style="width: 50px" align="center" value="<?php echo $num_comensales ?>" />
					</div>
				</div>
			<!-- FIN Info comanda -->
			<!-- Funciones --><?php
				// Valida si se deben de ocultar los botones
					$style = ($configuraciones['tipo_operacion'] == 3) ? ' display: none;' : '' ; ?>
					
				<div class="col-md-8" align="left">
					<button type="button" class="btn btn-success btn-lg btnProcess" style="width: 130px; margin-top: 1%; <?php echo $style ?>" idcomanda="<?php echo $row['id'] ?>">
						<i class="fa fa-check"></i> Pedido
					</button>
					<button type="button" class="btn btn-warning btn-lg btnEnd" style="width: 130px; margin-top: 1%" idcomanda="<?php echo $row['id'] ?>" tipo="<?php echo $tipo ?>" repa="<?php echo $repa ?>">
						<i class="fa fa-credit-card"></i> Cuenta
					</button>
					<button type="button" class="btn btn-default btn-lg" style="width: 130px; margin-top: 1%; <?php echo $style ?>" data-toggle="modal" data-target="#modal_autorizar">
						<i class="fa fa-pencil"></i> Asignar
					</button>
					<button type="button" class="btn btn-primary btn-lg" style="width: 130px; margin-top: 1%; <?php echo $style ?>" data-toggle="modal" data-target="#div_mudar" idcomanda="<?php echo $row['id'] ?>">
						<i class="fa fa-exchange"></i> Mudar
					</button>
					<button 
						type="button" 
						class="btn btn-warning btn-lg" 
						style="width: 130px; margin-top: 1%"
						onclick="closeComanda({
									id_comanda:<?php echo $row['id'] ?>,
									bandera: 0,
									reimprime:1
								})">
						<i class="fa fa-search"></i> Ver
					</button><?php 
					
				// Valida que exista la reservacion
					$id_reservacion = (empty($id_reservacion)) ? 0 : $id_reservacion ; ?>
					<button type="button" class="btn btn-danger btn-lg" style="width: 130px; margin-top: 1%; <?php echo $style ?>" data-toggle="modal" data-target="#modal_eliminar" idcomanda="<?php echo $row['id'] ?>" id_reservacion="<?php echo $id_reservacion ?>">
						<i class="fa fa-trash"></i> Eliminar
					</button><?php
					if (!empty($mesas_juntas)) { ?>
						<button type="button" class="btn btn-default btn-lg" style="width: 130px; margin-top: 1%" data-toggle="modal" data-target="#div_separar" mesas_juntas="<?php echo $mesas_juntas ?>">
							<i class="fa fa-arrows-h"></i> Separar
						</button><?php
					} ?>
				</div>
			<!-- Funciones -->
			</div><!-- Div row -->
		</div><!-- FIN Div arriba -->
	<!-- Abajo -->
		<div id="abajo" class="panel-body" style="padding-top: 1%;">
		<!-- Categorias -->
			<div class="row">
				<div class="col-md-4" style="padding-top: 0.8%">
					<button class="btn btn-default btn-lg btnDepartment" >
						<i class="fa fa-home"></i> Area
					</button>
					<button class="btn btn-default btn-lg btnFamily" iddeparment="0" style="display:none">
						<i class="fa fa-angle-right"></i> Cat
					</button>
					<button class="btn btn-default btn-lg btnLine" idFamily="0" style="display:none">
						<i class="fa fa-angle-right"></i> Sub
					</button>
				</div>
				<div class="col-md-8 GtableMenuContent qui" align="left" style="overflow: scroll;height:63px;"><?php
					foreach($deparments['rows'] as $value){ ?>
						<button type="button" class="btn btn-default btn-lg btnItemDeparment" style="font-size:13px; width:130px; margin-top:1%" iddeparment="<?php echo $value['idDep'] ?>">
							<?php echo substr(utf8_decode($value['nombre']), 0, 11); ?>
						</button><?php
					}
							
					echo '<script>reloadMenuEvents();</script>'; ?>
				</div>
			</div>
		<!-- FIN Categorias -->
			<div class="row" style="padding-top: 1%">
			<!-- Div personas -->
				<div class="GtableUser col-md-5">
					<div style="height:53px">
						<button type="button" class="btn btn-default btn-lg btnBack">
							<i class="fa fa-angle-double-left"></i>&nbsp;
						</button><?php
						if($flag){ ?>
							<button type="button" class="btn btn-default btn-lg btnAddPerson" idcomanda="<?php echo $row['id'] ?>">
								<i class="fa fa-plus"></i> <i class="fa fa-pencil-square-o"></i>
							</button>
							<button type="button" class="btn btn-default btn-lg btnDeletePerson">
								<i class="fa fa-minus"></i> <i class="fa fa-pencil-square-o"></i>
							</button>
							<button type="button" style="display:none" class="btn btn-success btn-lg btnConfirm" idcomanda="<?php echo $row['id'] ?>">
								<i class="fa fa-check"></i>
							</button>
							<button type="button" style="display:none" class="btn btn-danger btn-lg btnCancel">
								<i class="fa fa-ban"></i>
							</button><?php
						} ?>
						<button 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							id="btn_cerrar_persona" 
							type="button" 
							class="btn btn-warning btn-lg"
							onclick="comandas.cerrar_comanda_persona({
								btn: 'btn_cerrar_persona',
								id_comanda: <?php echo $row['id'] ?>,
								id_mesa: <?php echo $idmesa ?>,
								persona: $('#btn_kits').attr('persona')
							})">
							<i class="fa fa-credit-card"></i>
							<i class="fa fa-pencil-square-o "></i> 
							<kbd id="text_cerrar_persona">0</kbd>
						</button>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="div_person"><?php
								$idcomanda=$row['id'];
								
								if($flag){
									$maxPerson=$row['personas'];
									
									$posicion_color=1;
									
									while($row = $persons->fetch_array()){ 
										if($posicion_color>6){
											$posicion_color=1;
										}
										
									    $colarray[1] = 'background-color: #4a72b2';
									    $colarray[2] = 'background-color: #e6b54a';
									    $colarray[3] = 'background-color: #87868a';
									    $colarray[4] = 'background-color: #6eaa6f';
									    $colarray[5] = 'background-color: #76aadb';
									    $colarray[6] = 'background-color: #f4e16a';
										
										$col = $colarray[$posicion_color];
										
										$posicion_color++; ?>
										
										<div class="pull-left" style="padding:5px">
											<button id="person_<?php echo  $row['npersona'] ?>" type="button" class="btn btn-lg btnPerson" style="font-size: 25px;<?php echo $col ?>" idperson="<?php echo $row['npersona'] ?>" idcomanda="<?php echo $idcomanda ?>">
												<i class="fa fa-pencil-square-o"></i> 
												<?php echo  $row['npersona'] ?>
											</button>
										</div><?php
										
										echo '	<script>	
											  		window.persons++;
											  		window.bbutton=true;
													posicion_color++;
												</script>';
									}
									
									echo '<script>reloadPersonEvents();setValue('.$maxPerson.')</script>';
								} ?>
							</div>
						</div>
					</div>
					<div class="row" id="div_color">
						<div class="col-md-12 GtableUserContent qui" id="GtableUserContent" style="overflow: scroll;height: 70%">
							<div align="left">
								<h4>
									<span class="label label-default">
										* Clic sobre una orden <br><br>
										&nbsp;para agregar productos
									</span>
								</h4>
							</div>
						</div>
						<script>
				  		// Oculta el boton de cuenta por persona
				  			$("#btn_cerrar_persona").hide();
				  		</script>
					</div>
				</div>	<!-- FIN Div personas -->
				<div class="col-md-7 GtableProducts">
					<div class="row">
					<!-- Kits -->
						<div class="col-md-3">
							<button 
								onclick="comandas.listar_kits({
									div: 'div_productos', 
									tipo: 6,
									persona: person,
									comanda: <?php echo $idcomanda ?>
								})"
								class="btn btn-warning btn-lg"
								style="width: 130px;">
								<i class="fa fa-dropbox fa-lg"></i> Kits
							</button>
						</div>
					<!-- Buscador  -->
						<div class="col-md-8">
							<div class="input-group input-group-lg">
						    	<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.buscar_productos({texto: $('#texto').val(), comanda:'<?php echo $idcomanda ?>', div:'div_productos'})" type="search" id="texto" class="form-control" placeholder="pasta, corte, desayuno, omelet, etc.">
						      	<span class="input-group-btn">
									<button onclick="comandas.buscar_productos({texto: $('#texto').val(), comanda:'<?php echo $idcomanda ?>', div:'div_productos'})" class="btn btn-default" type="button">
								    	&nbsp;<i class="fa fa-search"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
				<!-- Buscador -->
				
				<!-- Productos -->
					<div class="row" id="div_productos" style="padding-top: 0.5%;width:100%; height:87%; overflow: scroll">
						<div class="GtableProductsContent qui col-md-12"><?php
							if($flag){ ?>
								<div id="div_productos_cargados"><?php
									foreach($products['rows'] as $value){
									 // Comprueba si es platillo especial
										$clase = (!empty($value['especial'])) ? 'info' : 'default' ; ?>
										
										<div 
											class="pull-left GtableProduct" 
											style="padding:5px" 
											idproducto="<?php echo $value['idProducto'] ?>" 
											idcomanda="<?php echo $objeto['comanda'] ?>" 
											materiales="<?php echo $value['materiales'] ?>" 
											tipo="<?php echo $value['tipo'] ?>" 
											iddep="<?php echo $value['idDep'] ?>">
											<button 
												title="<?php echo $value['nombre'] ?>" 
												type="button" class="btn btn-<?php echo $clase ?>" 
												style="width: 103px;height: 148px">
												<div class="row">
													<div class="col-md-12">
														<table>
															<tr>
																<td style="font-size: 12px" align="center">
																	<?php echo substr($value['nombre'], 0, 25)  ?>
																</td>
															</tr>
														</table>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<input 
															type="image" 
															alt=" " 
															style="width:80px;height:80px" 
															src="<?php echo $value['imagen'] ?>"/>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														$ <?php echo $value['precioventa'] ?>
													</div>
												</div>
											</button>
										</div><?php
									} ?>
								</div>
								<script>reloadMenuEvents();</script>
								<div class="row" align="center">
									<div class="col-md-12">
										<button 
											id="btn_cargar_productos"
											class="btn btn-default btn-lg" 
											style="width: 95%" 
											data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
											onclick="comandas.buscar_productos({
														div: 'div_productos_cargados',
														btn: 'btn_cargar_productos',
														limite: $('#limite').val(), 
														vista: 'cargar_productos', 
														comanda:'<?php echo $idcomanda ?>'
													})">
											<i class="fa fa-undo"></i> Cargar mas productos
										</button>
									</div>
								</div><?php
							} ?>
						</div><!-- GtableProducts -->
					</div><!-- productos -->
				</div>
			</div>
		</div><!-- FIN Div abajo -->
	
	<!-- Establece el limite de los productos a cargar -->
		<input type="number" id="limite" value="200" style="display: none" />
		
	<!-- Ventana modal mudar comanda -->
		<div class="modal fade" id="div_mudar" tabindex="-1" role="dialog" aria-labelledby="titulo_mudar">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_separar" align="left">Mudar comanda</h4>
					</div>
						
				<!-- Mensaje -->
					<div class="modal-body">
						<div align="left">
							<blockquote style="font-size: 14px">
								<p>
									Selecciona la <strong> Mesa </strong>.Esta acción <strong> separara </strong> 
									las mesas y mudara los pedidos a la mesa que selecciones.
								</p>
							</blockquote>
						</div>
						<div align="center" id="mesas_libres"><?php
							if ($mesas_libres['total']>0) {
								foreach ($mesas_libres['rows'] as $key => $value) { ?>
									<button 
										id="mesa_<?php echo $value['id_mesa'] ?>" 
										onclick="mudar_comanda({
													mesa_origen:<?php echo $idmesa ?>,
													mesa: <?php echo $value['id_mesa'] ?>,
													comanda: <?php echo $objeto['comanda'] ?>
												})" 
										type="button" 
										class="btn btn-default btn-lg">
										<?php echo  $value['nombre_mesa'] ?>
									</button><?php
								}
							} else { ?>
								<div align="center">
									<h3><span class="label label-default">* No hay mesas disponibles *</span></h3>
								</div><?php
							} ?>
						</div>
					</div>
				<!-- Cancelar -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Ventana modal mudar comanda -->

	<!-- Ventana modal pagar personalizado -->
		<div class="modal fade" id="div_personalizar" tabindex="-1" role="dialog" aria-labelledby="titulo_personalizar">
			<div class="modal-dialog modal-lg" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="cerrar_modal_personalizar" type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_personalizar" align="left">Cerrar comanda</h4>
					</div>
				<!-- Contenedor -->
					<div class="modal-body" id="contenedor_personalizar">
						<!-- Esta div se llena con la interfaz de cerrar comanda personalizado -->
					</div>
				<!-- Botones pagar personalizado-->
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-7" id="div_extras">
								<!-- En esta div aaparecen los productos extra -->
							</div>
							<div class="col-md-5" align="right">
								<button id="btn_personalizado_ok" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" autocomplete="off" type="button" class="btn btn-success btn-lg" onclick="comandas.guardar_comanda_parcial({persona:$('#persona').val(),idpadre:<?php echo $id_comanda ?>,mesa:<?php echo $mesa ?>})">
									<i class="fa fa-check"></i> Ok
								</button>
								<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
									<i class="fa fa-ban"></i> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Ventana modal pagar personalizado -->

	<!-- Ventana modal separar mesas -->
		<div class="modal fade" id="div_separar" tabindex="-1" role="dialog" aria-labelledby="titulo_separar">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_separar" align="left">Separar mesas</h4>
					</div>
				<!-- Mensaje -->
					<div class="modal-body">
						<div align="left">
							<blockquote style="font-size: 14px">
								<p>
									Selecciona la <strong> Mesa </strong> en la que se <strong> guardaran </strong> los pedidos.
								</p>
							</blockquote>
						</div>
						<div align="center"><?php
							foreach ($mesas_juntas as $key => $value) {
						// Codifica el objeto para enviarlo a la funcion de separar
								$mesa=json_encode($value);
								$mesa=str_replace('"', "'", $mesa); ?>
								
								<button id="mesa_<?php echo $value['idmesa'] ?>" onclick="separar_mesas(<?php echo $mesa ?>)" type="button" class="btn btn-default btn-lg">
									<?php echo $value['idmesa'] ?>
								</button><?php
							} ?>
						</div>
					</div>
				<!-- Cancelar -->
					<div class="modal-footer">
							<button id="cerrar_modal" type="button" class="btn btn-danger" data-dismiss="modal">
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Ventana modal separar mesas -->
	
	<!-- Modal eliminar comanda -->
		<div id="modal_eliminar" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
		   		<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Eliminar comanda</h4>
					</div>
					<div class="modal-body">
						<h3><small>Introduce la contraseña:</small></h3>
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input onkeypress="(((document.all) ? event.keyCode : event.which)==13) eliminar_comanda({pass:$('#pass').val(), comanda:'<?php echo $id_comanda ?>', reservacion:'<?php echo $id_reservacion ?>'})" id="pass" type="password" class="form-control">
							<span class="input-group-btn">
					        	<button onclick="eliminar_comanda({pass:$('#pass').val(), comanda:'<?php echo $id_comanda ?>', reservacion:'<?php echo $id_reservacion ?>'})" class="btn btn-danger" type="button">
					        		<i class="fa fa-trash"></i> Eliminar
					        	</button>
					      	</span>
						</div>
			      	</div>
					<div class="modal-footer">
						<button id="btn_cerrar" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal eliminar comanda -->
	</div><!-- Contenedor -->
	
	<!-- Modal asignar -->
		<div class="modal fade" id="modal_asignar" tabindex="-1" role="dialog" aria-labelledby="titulo_asignar">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_asignacion"  type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_asignar" align="left">Asignar mesa</h4>
					</div>
				<!-- Mensaje -->
					<div class="modal-body">
						<div align="left">
							<blockquote style="font-size: 14px">
								<p>
									Selecciona el <strong> Mesero </strong> al que desea <strong> Asignar </strong> la mesa.
								</p>
							</blockquote>
						</div>
						<div align="center" id="mesas_libres" style="overflow: scroll;height: 55%"><?php
							foreach ($empleados as $key => $value) { ?>
								<div class="pull-left" style="padding:5px">
									<button type="button" class="btn btn-default btn-lg"  onclick="asignar_mesa({empleado:<?php echo $value['id'] ?>,mesa:<?php echo $idmesa ?>})" style="width: 110px;">
										<i class="fa fa-user"></i> <br>
										<?php echo substr($value['usuario'], 0, 9); ?>
									</button>
								</div><?php
							}?>
						</div>
					</div>
				<!-- Cancelar -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal asignar -->
	
	<!-- Modal Autorizar asignacion -->
		<div id="modal_autorizar" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
		   		<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_autorizacion" type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Autorizar asignacion</h4>
					</div>
					<div class="modal-body">
						<h3><small>Introduce la contraseña:</small></h3>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input id="pass_asignacion" type="password" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) autoriza_asignacion({pass:$('#pass_asignacion').val()})" class="form-control">
							<span class="input-group-btn">
					        	<button onclick="autoriza_asignacion({pass:$('#pass_asignacion').val()})" class="btn btn-success" type="button">
					        		<i class="fa fa-check"></i> Autorizar
					        	</button>
					      	</span>
						</div>
			      	</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal Autorizar asignacion -->
	
	<!-- Modal Autorizar_pedido -->
		<div id="modal_autorizar_pedido" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
		   		<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_pedido" type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Autorizar modificacion</h4>
					</div>
					<div class="modal-body">
						<h3><small>Introduce la contraseña:</small></h3>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
							<input id="pass_pedido" type="password" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) autorizar_pedido({pass:$('#pass_pedido').val()})" class="form-control">
							<span class="input-group-btn">
					        	<button onclick="autorizar_pedido({pass:$('#pass_pedido').val()})" class="btn btn-success" type="button">
					        		<i class="fa fa-check"></i> Autorizar
					        	</button>
					      	</span>
						</div>
			      	</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal Autorizar_pedido -->

	<!-- Modal kits -->
		<div class="modal fade" id="modal_kit" tabindex="-1" role="dialog" aria-labelledby="titulo_kit">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="cerrar_kit" type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_kit" align="left">Kit</h4>
					</div>
				<!-- Contenedor -->
					<div class="modal-body" id="div_productos_kit">
						<!-- En esta div se cargan los productos del kit -->
					</div>
				<!-- Botones-->
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-12" align="right">
								<button 
									id="btn_kits" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
									type="button" 
									class="btn btn-success btn-lg" 
									onclick="comandas.guardar_kit({btn: 'btn_kits', persona: $(this).attr('persona')})">
									<i class="fa fa-check"></i> Ok
								</button>
								<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
									<i class="fa fa-ban"></i> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN modal kits -->
	
	<!-- Ventana modal pagar personalizado -->
		<div class="modal fade" id="div_personalizar" tabindex="-1" role="dialog" aria-labelledby="titulo_personalizar">
			<div class="modal-dialog modal-lg" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="cerrar_modal_personalizar" type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_personalizar" align="left">Cerrar comanda</h4>
					</div>
					<!-- Contenedor -->
					<div class="modal-body" id="contenedor_personalizar">
						<!-- Esta div se llena con la interfaz de cerrar comanda personalizado -->
					</div>
					<!-- Botones pagar personalizado-->
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-12" align="right">
								<button id="btn_personalizado_ok" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" autocomplete="off" type="button" class="btn btn-success btn-lg" onclick="comandas.guardar_comanda_parcial({persona:$('#persona').val(),idpadre:<?php echo $id_comanda ?>,mesa:<?php echo $mesa ?>})">
									<i class="fa fa-check"></i> Ok
								</button>
								<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
									<i class="fa fa-ban"></i> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- FIN Ventana modal pagar personalizado -->
		
	<!-- modal_reiniciar -->
		<div class="modal fade" keyboard="false" data-backdrop="static" id="modal_reiniciar" tabindex="-1" role="dialog" aria-labelledby="titulo_reiniciar">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
	      		<div class="modal-header">
	       			<h4 class="modal-title">Autorizar</h4>
	      		</div>
	      		<div class="modal-body">
					<blockquote style="font-size: 14px">
				    	<p>
				      		La comanda se ha <strong>mandado a caja</strong> correctamente. Pulsa continuar
				    	</p>
				    </blockquote>
	     			<div class="row">
	     				<div class="col-md-8">
					        <button 
					        	class="btn btn-success" 
					        	type="button"
					        	onclick="window.location.reload()">
					        	<i class="fa fa-arrow-right"></i> Continuar
					       	</button>
						</div>
	     			</div>
	      		</div>
			</div>
			</div>
		</div>
	<!-- FIN modal_reiniciar -->
</body>
</html>
<script>
// Abre la primera orden que encuentre
	var abrir_orden = $("#person_1").click();
	if(abrir_orden['length'] < 1){
		var orden = 0;
		var limite = 2;
	
	// Busca la orden siguien y le da clic(solo realiza 20 intentos)
		while (orden == 0 && limite < 20) {
			abrir_orden = $("#person_"+limite).click();
			
		// Para el ciclo si encuentra la persona
			if(abrir_orden['length'] > 0){
				orden = 1;
			}
			
			limite++;
		}
	}
</script>