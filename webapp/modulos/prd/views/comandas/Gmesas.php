<?php
	date_default_timezone_set('America/Mexico_City');
?>
<html>
	<head>
		
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	<!-- tooltipster -->
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/tooltipster.css">
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-light.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-noir.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-punk.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-shadow.css" />
	<!-- jqueryui -->
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap-theme.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- jquery Mobile -->
		<!-- <link rel="stylesheet" href="../../libraries/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"> -->
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

	<!-- gridstack -->
	    <link rel="stylesheet" href="../../libraries/gridstack.js-master/dist/gridstack.css"/>
	    
	<!-- ** Sistema -->
		<link rel="stylesheet" type="text/css" href="css/comandas/comandas.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap-3.3.7/js/bootstrap.min.js"></script>	
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- JQuery Mobile -->
		<!-- <script src="../../libraries/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>

	<!-- gridstack -->
		<script src="../../libraries/lodash.min.js"></script>
	    <script src="../../libraries/gridstack.js-master/dist/gridstack.js"></script>
		
	<!-- ** Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		<script type="text/javascript" src="js/comandas/comandera.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<title></title>
		<style>
            /*Mesa simple*/
            #GtableTable0 {
                background: #98ac31;
                /*width:150px;*/
                /*height:150px;*/
                /*display:inline-block;*/
                margin: 2px;/*
                 border-radius:100px;
                 padding-bottom:5px;*/
            }
            /*Para llevar*/
            #GtableTable1 {
                background: #135A90;
                /*width:150px;
                 display:inline-block;
                 margin:2px;
                 border-radius:100px;
                 padding-bottom:5px;*/
            }
            /*Servicio a domicilio*/
            #GtableTable2 {
                background: #11AB9F;
                /*width:150px;
                 display:inline-block;
                 margin:2px;
                 border-radius:100px;
                 padding-bottom:5px;*/
            }
            /*Mesas juntas*/
            #GtableTable3 {
                background: #1C8EA4;
                /*background:#5BC0DE;*/
                /*height:150px;*/
                /*display:inline-block;*/
                /*margin:2px;*/
                /*border-radius:10px;*/
                /*padding-bottom:5px;*/
                /*padding:10px*/
            }
            .GtableTableTable {
                font-size: 12px;
                font-family: verdana;
                color: #ffffff;
                font-weight: 600;
                margin-top: 6px;
            }
            .GtableTablePeople {
                font-size: 12px;
                font-family: verdana;
                color: #ffffff;
                font-weight: 600;
                margin-top: 1px;
            }
            .GtableTablePlace {
                font-size: 12px;
                font-family: verdana;
                color: #ffffff;
                font-weight: 600;
                margin-top: 1px;
            }
            .GtableTableIcon {
                /*margin-top:10px;*/
            }
            .btnKey {
                /*	background:#616f6f; */
                width: 60px;
                height: 60px;
                text-align: center;
                font-family: verdana;
                color: #424242;
                font-weight: 600;
                font-size: 15px;
            }
            .btnKeynum {
                /*	background:#616f6f; */
                width: 60px;
                height: 60px;
                text-align: center;
                font-family: verdana;
                color: #424242;
                font-weight: 600;
                font-size: 15px;
            }
            .btnBigKey {
                /*	background:#616f6f; */
                width: 209px;
                height: 60px;
                text-align: center;
                font-family: verdana;
                color: #424242;
                font-weight: 600;
                font-size: 15px;
            }
            .imagejuntarmesas {
                /*	background-image : url(imgcomandas/juntamesas.png); */
                background-repeat: no-repeat;
                position: absolute;
                left: -7px;
                top: -8px;
                width: 257px;
                height: 128px;
            }
            .teclado {
                background-color: #98ac31;
                /* sombra interna */
                -moz-box-shadow: inset 0 0 10px #000000;
                -webkit-box-shadow: inset 0 0 10px #000000;
                box-shadow: inset 0 0 10px #000000;
                /* termina sombra interna*/
                border-radius: 20px;
            }
            .qui {

                overflow-x: hidden;
            }

		</style>
		<script>
			var ftable = 0;
			var jtables = new Array();
			var ctables = new Array();
			var capital = false;
			var tablesContent = "";
			var name = "";
			var address = "";
			var menu = 0;
			var fKey = false;
			var ajax = null;
			var $x = 0;
			var $y = 0;
			var grid = '';
			var vista = 0;
			var vista_lista = '';

			$(document).ready(function() {
				comandas.init();

			// Se dispara al precionar una tecla
				$(".btnKeynum").click(function() {
					$("#perro").append($(this).html());
					$("#perro").keyup();
				});

				$(".btnKeynumdel").click(function() {
					$("#perro").empty();
					$("#perro").focus();
				});

				if (window.parent.estadomenu)
					$("#tdocultar", window.parent.document).trigger("click");

				var piframe = $("#tb2156-u .frurl", window.parent.document).height();

			// Cambia el ancho de la div segun el tamaño de la pantalla
				$(".GtableRight").height(piframe - 40);
				
			// Junta las mesas
				$(".joinTables").click(function() {
					if (confirm("Deseas Juntar Mesas?")) {
						alert("Selecciona Las Mesas a Juntar!!!");

						ftable = 1;

						$(".btnCancel").css('visibility', 'visible');

						tablesContent = $(".GtableTablesContent").html();
					}
				});

				$(".btnConfirm").click(function() {
					$(".btnConfirm").css('visibility', 'visible');
					$(".btnCancel").css('visibility', 'visible');

					switch(menu) {

				// Junta las mesas
					case 0:
						$.ajax({
							data : {
								jtables : jtables.join()
							},
							url : 'ajax.php?c=comandas&f=joinTables',
							type : 'GET',
							success : function(callback) {
								console.log(callback);

								$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
							}
						});
						break;
					}
				});

				$(".btnCancel").click(function() {
					ftable = 0;
				
				// Muestra la Div de areas
					$("#areas").show();

					$(".btnCancel").css('visibility', 'hidden');
					$(".btnConfirm").css('visibility', 'hidden');
					$(".GtableTablesContent").html(tablesContent);

					menu = 0;
					name = "";
					address = "";
					fKey = false;
					jtables.length = 0;
					ctables.length = 0;
				});
			});

			function reloadTableEvents() {
				$('.GtableTable0').off('click');
				$(".GtableTable0").click(function() {
					console.log('---> bandera');
					console.log(ftable);

				// Carga la vista de la comanda en la mesa normal
					if (!ftable) {
					// inicializamos variables
						var $pathname = window.location.pathname;
						var $id_mesa = $(this).attr('idmesa');
						var $nombre_buscador = $(this).attr('nombre_buscador');
						var $reservacion = 0;
						var $tipo = $(this).attr('tipo');

						// Valida si existe una reservacion
						if ($(this).attr('id_reservacion')) {
							$reservacion = $(this).attr('id_reservacion');
						}

					// ** Valida que la mesa no este eliminada
						$.ajax({
							data : {
								id : $id_mesa,
								nombre : $nombre_buscador
							},
							url : 'ajax.php?c=comandas&f=validar_mesa',
							type : 'GET',
							dataType : 'json',
						}).done(function(resp) {
							console.log('--------> RESPONSE validar_mesa');
							console.log(resp);

							$id_mesa = resp['id_mesa'];

						// Exito :D, redirecciona a la comandera
							if (resp['status'] == 1) {
								// var $objeto = {};
								// $objeto['idmesa'] = $id_mesa;
								// $objeto['tipo'] = $tipo;
								// $objeto['id_reservacion'] = $reservacion;
								
								console.log('Entra!!!! :D    --mesa: ' + $id_mesa + ' --tipo: ' + $tipo + ' --$reservacion: ' + $reservacion);
								// $("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + $pathname + '?c=comandas&f=mesa&idmesa=' + $id_mesa + '&tipo=' + $tipo + '&id_reservacion=' + $reservacion);

								return 0;
							}

						// La mesa no existe :(
							if (resp['status'] == 2) {
								var $mensaje = 'La mesa no existe';
								$('#perro').notify($mensaje, {
									position : "top center",
									autoHide : true,
									autoHideDelay : 5000,
									className : 'warn',
								});

								return 0;
							}
						}).fail(function(resp) {
							console.log('---------> Fail validar_mesa');
							console.log(resp);

							var $mensaje = 'Error al cargar la mesa';
							$('#div_agregar').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
							});
						});
					}

				// Si se pretende juntar mesas
					if (ftable == 1) {
						if ($(this).css('background-color') == "rgb(97, 111, 111)") {
						// El numero maximo de mesas son 3
						// if(jtables.length<3){
							if ($(this).attr('idcomanda') == 0) {
								$(this).css('background-color', '#FF6961');
								$(this).css('color', '#FFFFFF');
								jtables.push($(this).attr('idmesa'));
								ctables.push($(this).attr('idcomanda'));
							} else {
								var econtador = 0;

								for (var contador = 0; contador < ctables.length; contador++) {
									if (ctables[contador] != 0)
										econtador++;
								}

								if (!econtador) {
									$(this).css('background-color', '#FF6961');
									$(this).css('color', '#FFFFFF');
									jtables.push($(this).attr('idmesa'));
									ctables.push($(this).attr('idcomanda'));
								} else
									alert("Esta mesa ya tiene una comanda, No se puede juntar!!!");
							}
						} else {
						// Pinta la mesa cuando se da un clic en juntar mesas
							$(this).css('background-color', '#616f6f');

						// Revisa si la mesa ya esta en el arreglo
							for (var contador = 0; contador < jtables.length; contador++) {
								if ($(this).attr('idmesa') == jtables[contador]) {
									jtables.splice(contador, 1);
									ctables.splice(contador, 1);
									break;
								}
							}
						}
						
						if (jtables.length > 1)
							$(".btnConfirm").css('visibility', 'visible');
						else
							$(".btnConfirm").css('visibility', 'hidden');
					}

				// Eliminar mesa
					if (ftable == 2) {
						$.ajax({
							data : {
								idmesa : $(this).attr('idmesa')
							},
							url : 'ajax.php?c=comandas&f=removeTable',
							type : 'GET',
							success : function(callback) {
								$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
							}
						});
					}
				});

				$(".tr_mesa").click(function() {
					if (!ftable) {
						var pathname = window.location.pathname;
						
					// Valida si existe una reservacion
						var $reservacion = 0;
						if ($(this).attr('id_reservacion')) {
							$reservacion = $(this).attr('id_reservacion');
						}
						
						$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=mesa&idmesa=' + $(this).attr('idmesa') + '&tipo=' + $(this).attr('tipo') + '&id_reservacion=' + $reservacion);

						return 0;
					}

				// Si se pretende juntar mesas
					if (ftable == 1) {
						if ($(this).css('background-color') == "rgb(97, 111, 111)") {
							if ($(this).attr('idcomanda') == 0) {
								$(this).css('background-color', '#FF6961');
								$(this).css('color', '#FFFFFF');
								jtables.push($(this).attr('idmesa'));
								ctables.push($(this).attr('idcomanda'));
							} else {
								var econtador = 0;

								for (var contador = 0; contador < ctables.length; contador++) {
									if (ctables[contador] != 0)
										econtador++;
								}

								if (!econtador) {
									$(this).css('background-color', '#FF6961');
									$(this).css('color', '#FFFFFF');
									jtables.push($(this).attr('idmesa'));
									ctables.push($(this).attr('idcomanda'));
								} else
									alert("Esta mesa ya tiene una comanda, No se puede juntar!!!");
							}
						} else {
						// Pinta la mesa cuando se da un clic en juntar mesas
							$(this).css('background-color', '#616f6f');

						// Revisa si la mesa ya esta en el arreglo
							for (var contador = 0; contador < jtables.length; contador++) {
								if ($(this).attr('idmesa') == jtables[contador]) {
									jtables.splice(contador, 1);
									ctables.splice(contador, 1);
									break;
								}
							}
						}

					// Si hay dos mesas o mas muestra el boton de confirmar
						if (jtables.length > 1)
							$(".btnConfirm").css('visibility', 'visible');
						else
							$(".btnConfirm").css('visibility', 'hidden');

						return 0;
					}

				// Eliminar mesa
					if (ftable == 2) {
						$.ajax({
							data : {
								idmesa : $(this).attr('idmesa')
							},
							url : 'ajax.php?c=comandas&f=removeTable',
							type : 'GET',
							success : function(callback) {
								$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
							}
						});

						return 0;
					}
				});

			// Click en mesa para llevar
				$('.GtableTable1').off('click');
				$(".GtableTable1").click(function() {
				// Carga la vista de la comanda en la mesa para llevar
					if (!ftable) {
						var pathname = window.location.pathname;
						$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=mesa&idmesa=' + $(this).attr('idmesa') + '&tipo=1');

						return 0;
					}

					if (ftable == 2) {
						$.ajax({
							data : {
								idmesa : $(this).attr('idmesa')
							},
							url : 'ajax.php?c=comandas&f=removeTable',
							type : 'GET',
							success : function(callback) {
								$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
							}
						});

						return 0;
					}
				});

			// Click en la mesa de servicio a domicilio
			
				$(".repa").click(function() {
						var id_repartidor = '';
						$('#inpmesa,#inpcomanda,#inpidrep,#inprepartidor').val('');

						var idmesa = $(this).attr('idmesaR');
						var idcomanda = $(this).attr('idcomandaR');

						// if si existe comanda hacer esto si
						if(idcomanda != ''){
							$.ajax({
								url: 'ajax.php?c=comandas&f=verAsignado',
								type: 'POST',
								data: {idcomanda:idcomanda},
								dataType: 'json',
								async:false,
								success : function(callback) {
									$.each(callback, function(index, value) {
										 id_repartidor = value['id_repartidor'];
									});
								}
							})

							if(id_repartidor != ''){
								//$(inpidrep).val(id_repartidor);
								//$(inprepartidor).val('');
								alert('Ya se asigno un repartidor');
								return false;
								
							}else{
								$('#inpidrep,#inprepartidor').val('');
							}

							$('#inpmesa').val(idmesa);
							$('#inpcomanda').val(idcomanda);
							//alert('Aqui se sgrega al repartidor y la mesa es'+ idmesa+ 'la comanda es '+ idcomanda);
							$('#modal_repartidores').modal('show');
							return false; 
						}	
				});
				
				$('.GtableTable2').off('click');
				$(".GtableTable2").click(function() {

				if(jQuery('#repa').data('clicked')) {
				    return false;
				} else {
				
				// Carga la vista de la comanda en la mesa de servicio a domicilio
					if (!ftable) {
						var pathname = window.location.pathname;
						$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=mesa&idmesa=' + $(this).attr('idmesa') + '&tipo=2');

						return 0;
					}

					if (ftable == 2) {
						$.ajax({
							data : {
								idmesa : $(this).attr('idmesa')
							},
							url : 'ajax.php?c=comandas&f=removeTable',
							type : 'GET',
							success : function(callback) {
								$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
							}
						});

						return 0;
					}
				 }				
				});
			//FIN Click en la mesa de servicio a domicilio

				setTablesArrows();
			}

			function reloadKeyboardEvents() {
				$('.btnKey').off('click');
				$(".btnKey").click(function() {
					if (!capital)
						$(".txtKey").append($(this).html().toLowerCase());
					else
						$(".txtKey").append($(this).html().toUpperCase());
					$(".txtKey").keyup();
				});

				$('.btnBigKey').off('click');
				$(".btnBigKey").click(function() {
					switch($(this).html()) {
					case "Mayusculas":
						if ($(this).css('background-color') == "rgb(247, 190, 129)") {
							$(this).css('background-color', '#5CB85C');
							capital = true;
						} else {
							$(this).css('background-color', '#616f6f');
							capital = false;
						}
						break;

					case "Espacio":
						$(".txtKey").append(" ");
						break;

					case "Borrar":
						$(".txtKey").html($(".txtKey").html().slice(0, -1));
						break;
					}
					$(".txtKey").keyup();
				});

				$('.txtKey').off('keyup');
				$(".txtKey").keyup(function(event) {
					if (ajax)
						ajax.abort();
					ajax = $.ajax({
						data : {
							name : $(this).val()
						},
						url : 'ajax.php?c=comandas&f=getNames',
						type : 'GET',
						dataType : 'json',
						success : function(callback) {
							$(".txtKeyContent").html("");
							$('.txtKeyContent').show();
							$.each(callback['rows'], function(index, value) {
								$(".txtKeyContent").append("<a href=\"javascript:void(0)\" style=\"text-decoration:none;color:#585858;padding-top:10px\"><div style=\"border:1px solid #D8D8D8;margin-top:5px;padding:6px;border-radius:5px;background:#F2F2F2\" class=\"btnName\">" + value['nombre'] + "</div></a>");
							});

							$('.btnName').off('click');
							$(".btnName").click(function(event) {
								$('.txtKey').val($(this).html());
								$('.txtKeyContent').hide();
							});
						}
					});
				});
			}

			function setTablesArrows() {
				// if($(".GtableTablesContent").height()<$(".GtableTablesContent").prop('scrollHeight')){
				// $(".tablesLeft").css('visibility', 'visible');
				// $(".tablesRight").css('visibility', 'visible');
				// }else{
				// $(".tablesLeft").css('visibility', 'hidden');
				// $(".tablesRight").css('visibility', 'hidden');
				// }
			}

			function loadKeyboard(param) {
				var text = "";

				if (!param) {
					text = "Escribe un Nombre:";
					tablesContent = $(".GtableTablesContent").html();
				} else
					text = "Escribe una direccion:";

				$(".btnConfirm").css('visibility', 'visible');
				$(".btnCancel").css('visibility', 'visible');

			// Teclado
				$div = '<div class="keyboard">';
				$div += '	<div>';
				$div += '		<table>';
				$div += '			<tr>';
				$div += '				<td style="font-size:13px;font-family:verdana;color:#848484" class="txtLabel">' + text + '</td>';
				$div += '				<td>';
				$div += '					<textarea rows="2" cols="50" class="txtKey" style="border:1px solid #BDBDBD;border-radius:10px;padding:5px;resize:none" maxlength="110"></textarea>';
				$div += '					<div class="txtKeyContent" style="position:absolute;background:#FAFAFA;font-size:15px;color:#FAFAFA;padding:10px;display:none;width:360px;margin-left:445px"></div>';
				$div += '				</td>';
				$div += '			</tr>';
				$div += '		</table>';
				$div += '	</div>';
				$div += '	<a href="javascript:void(0)" style="text-decoration:none">';
				$div += '		<table style="width:63%;">';
				$div += '			<tr>';
				$div += '				<td class="btnBigKey teclado">Mayusculas</td>';
				$div += '				<td class="btnBigKey teclado">Espacio</td>';
				$div += '				<td class="btnBigKey teclado">Borrar</td>';
				$div += '			</tr>';
				$div += '		</table>';
				$div += '	</a>';
				$div += '	<a href="javascript:void(0)" style="text-decoration:none">';
				$div += '		<div class="teclado" style="width:62%;">';
				$div += '			<table>';
				$div += '				<tr>';
				$div += '					<td class="btnKey">0</td><td class="btnKey">1</td>';
				$div += '					<td class="btnKey">2</td><td class="btnKey">3</td>';
				$div += '					<td class="btnKey">4</td><td class="btnKey">5</td>';
				$div += '					<td class="btnKey">6</td><td class="btnKey">7</td>';
				$div += '					<td class="btnKey">8</td><td class="btnKey">9</td>';
				$div += '				</tr>';
				$div += '				<tr>';
				$div += '					<td class="btnKey">Q</td>';
				$div += '					<td class="btnKey">W</td>';
				$div += '					<td class="btnKey">E</td>';
				$div += '					<td class="btnKey">R</td>';
				$div += '					<td class="btnKey">T</td>';
				$div += '					<td class="btnKey">Y</td>';
				$div += '					<td class="btnKey">U</td>';
				$div += '					<td class="btnKey">I</td>';
				$div += '					<td class="btnKey">O</td>';
				$div += '					<td class="btnKey">P</td>';
				$div += '				</tr>';
				$div += '				<tr>';
				$div += '					<td class="btnKey">A</td>';
				$div += '					<td class="btnKey">S</td>';
				$div += '					<td class="btnKey">D</td>';
				$div += '					<td class="btnKey">F</td>';
				$div += '					<td class="btnKey">G</td>';
				$div += '					<td class="btnKey">H</td>';
				$div += '					<td class="btnKey">J</td>';
				$div += '					<td class="btnKey">K</td>';
				$div += '					<td class="btnKey">L</td>';
				$div += '					<td class="btnKey">&Ntilde;</td>';
				$div += '				</tr>';
				$div += '				<tr>';
				$div += '					<td class="btnKey">Z</td>';
				$div += '					<td class="btnKey">X</td>';
				$div += '					<td class="btnKey">C</td>';
				$div += '					<td class="btnKey">V</td>';
				$div += '					<td class="btnKey">B</td>';
				$div += '					<td class="btnKey">N</td>';
				$div += '					<td class="btnKey">M</td>';
				$div += '					<td class="btnKey">.</td>';
				$div += '					<td class="btnKey">p</td>';
				$div += '					<td class="btnKey">Q</td>';
				$div += '				</tr>';
				$div += '			</table>';
				$div += '		</div>';
				$div += '	</a>';
				$div += '</div>';
			// FIN Teclado

			// Agreagamos el teclado a GtableTablesContent
				$(".GtableTablesContent").html($div);

				if (!param)
					reloadKeyboardEvents();
			}

			function buscaMesa() {

				$('#numbersKey').dialog({
					modal : true,
					draggable : true,
					resizable : true,
					title : "Mesa",
					//position:['top',20],
					width : 225,
					height : 380,
					buttons : {
						"Cancelar" : function() {
							$('#etapas').dialog('close');
						}
					}
				}).height('auto');
			}

			function buscamesaboton2() {
				var nombre = $("#perro").val();

				$("#sendcomattr").attr({
					'nombre_buscador' : nombre,
					'tipo' : 0
				})
				$("#sendcomattr").click();
			}

			function borra() {
				$("#perro").val('');
				return;
				$("#perro").html($("#perro").html().slice(0, -1));
			}

///////////////// ******** ---- 		info_estados		------ ************ //////////////////
//////// Obtiene la informacion de los estados
//////// Crea un select con la informacion de los estados
	// Como parametros puede recibir:

			function info_estados($objeto) {
				$.ajax({
					url : 'ajax.php?c=comandas&f=info_estados',
					type : 'POST',
					dataType : 'json',
					data : $objeto,
				}).done(function(response) {
					console.log('-	-	-	info estados	-	-	-');
					console.log(response);
					// Recorre el array cono registros y los va agregando al select
					$.each(response, function(key, value) {
						$llena = $('#estado').append('<option value="' + value.idestado + '">' + value.estado + '</option>');
					});

					$('#estado').selectpicker('refresh');
				});
			}

///////////////// ******** ---- 	FIN	info_estados		------ ************ //////////////////

///////////////// ******** ---- 		info_municipios		------ ************ //////////////////
//////// Obtiene la informacion de los municipios
//////// Crea un select con la informacion de los municipios
		// Como parametros puede recibir:
			// id-> id del estado

			function info_municipios($objeto) {
				$.ajax({
					url : 'ajax.php?c=comandas&f=info_municipios',
					type : 'POST',
					dataType : 'json',
					data : $objeto,
				}).done(function(response) {
					console.log('-	-	-	info municipios	-	-	-');
					console.log(response);
					// Limpia municipios
					$('#municipio').html('');

					$('#municipio').append('<option value="">-- Seleccionar --</option>')
					// Recorre el array cono registros y los va agregando al select
					$.each(response, function(key, value) {
						$('#municipio').append('<option value="' + value.idmunicipio + '">' + value.municipio + '</option>');
					});

					$('#municipio').selectpicker('refresh');
				});
			}

///////////////// ******** ---- 	FIN	info_municipios		------ ************ //////////////////

///////////////// ******** ---- 		agregar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// id-> id del formulario

			function agregar_cliente($objeto) {
				var $datos = {};
				var $requeridos = [];
				var error = 0;
				var $mensaje = 'Campos incorrectos: \n';
				var filtro_mail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				var filtro_tel = /^[0-9]{10}$/;

			// ** Validaciones
			// Obtiene todos los input del formulario
				var $inputs = $('#' + $objeto.formulario + ' :input');
				// console.log($inputs);

			// Recorre los input para asignarlos a un arreglo
				$inputs.each(function() {
					var required = $(this).attr('required');
					var valor = $(this).val();
					var id = this.id;

				// Valida que el campo no este vacio si es requerido
					if (required == 'required' && valor.length <= 0) {
						error = 1;

						$requeridos.push(id);
					}

				// Valida el E-mail
					if (id == 'mail' && valor.length > 0) {
						if (!filtro_mail.test(valor)) {
							error = 1;
							$mensaje += '\n * Direccion de E-mail invalida * \n ';
						}
					}

				// Valida el Telefono
					if (id == 'tel' && valor.length > 0) {
						if (!filtro_tel.test(valor)) {
							error = 1;
							$mensaje += '\n * Telefono invalido * \n ';
						}
					}

				// Valida el Codigo postal
					if (id == 'cp' && valor > 99999) {
						if (!filtro_tel.test(valor)) {
							error = 1;
							$mensaje += '\n * Codigo postal invalido * \n ';
						}
					}

					$datos[this.id] = $(this).val();
				});

				if ($requeridos.length > 0) {
					$mensaje += '\n Debes llenar los siguientes campos: \n';
					// Recorre el array con los campos requeridos para crear el mensaje
					$.each($requeridos, function(index, value) {
						$mensaje += '-->' + this + ' \n';
					});
				}

			// Si hay algun error no realiza el ajax y muestra el mensaje con los errores
				if (error == 1) {
					alert($mensaje);
					return 0;
				}

				// Inserta el registro en la base de datos, devuelve un mensaje si es exitoso o no
				$.ajax({
					url : 'ajax.php?c=comandas&f=agregar_cliente',
					type : 'POST',
					dataType : 'json',
					data : $datos,
				}).done(function(response) {
					console.log(response);
					if (response) {
						// Cierra la ventana modal
						$('#cerrar_modal').click();

						domicilio = $datos['direccion'];
						num_int = $datos['num_int'];
						num_ext = $datos['num_ext'];

						// Si existe un numero interior lo agrega al domicilio
						if (num_int.length > 0) {
							domicilio += ' Num. ' + num_ext + ' Int. ' + num_int;
							// Si no existe un numero interior solo pone el numero exterior
						} else {
							domicilio += ' Num. ' + num_ext;
						}

						// Agrega la direccion a la caja de texto
						$('.txtKey').val(domicilio);

						$mensaje = 'Cliente agregado con exito';
					} else {
						$mensaje = 'Error al agregar cliente';
					}

					alert($mensaje);
				});
			}

///////////////// ******** ---- 	FIN	agregar_cliente		------ ************ //////////////////

///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

//////// Cambia los select por select con buscador.
	// Como parametros puede recibir:
			// Array con los id de los select

			function select_buscador($objeto) {
				// Recorre el arreglo y establece las propiedades del buscador
				$.each($objeto, function(key, value) {
					$("#" + value).select2({
						width : "150px"
					});
				});
			}

///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////

///////////////// ******** ---- 		guardar_cordenadas		------ ************ //////////////////
//////// Guarda las cordenadas de la mesa actual en la BD
	// Como parametros recibe:
			// id -> id de la mesa que se movera
			// X -> numero de la columno
			// Y -> numero de la fila
			function guardar_cordenadas($objeto) {
				console.log($objeto);
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=guardar_cordenadas',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						// Error: Manda un mensaje con el error
						if (!resp) {
							alert('Error: \n Error al guardar las cordenadas de la mesa');
							return 0;
						}

						// ** Cambia el color de fondo por unos segundos para indicar el cambio de cordenadas
						$("#draggable_" + $objeto['id']).animate({
							backgroundColor : "#77DD77"
						}, 500);

						// Devuelve al color original
						$("#draggable_" + $objeto['id']).animate({
							backgroundColor : "",
							color : "#337AB7"
						}, 1000);
					}
				});
			}

///////////////// ******** ---- 		FIN guardar_cordenadas		------ ************ //////////////////

///////////////// ******** ---- 		areas		------ ************ //////////////////
//////// Obtiene el listado de las areas en las que estan las mesas
	// Como parametros recibe:
			// id -> id del area

			function areas($objeto) {
				console.log($objeto);

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=areas',
					type : 'POST',
					dataType : 'json',
					success : function(resp) {
						console.log('---------> Areas');
						console.log(resp);
						$("#tb2156-u .frurl", window.parent.document).attr("src", $("#tb2156-u .frurl", window.parent.document).attr("src"));
					}
				});
			}

///////////////// ******** ---- 		FIN areas		------ ************ //////////////////

///////////////// ******** ---- 		buscar_reservaciones		------ ************ //////////////////
//////// Consulta si hay reservaciones para la hora actual
	// Como parametros recibe:
			// fecha-> fecha y hora a bsucar

			function buscar_reservaciones($objeto) {
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=buscar_reservaciones',
					type : 'POST',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						// Valida que existan reservaciones
						if (resp['rows']) {
							// Recorre el array
							$.each(resp['rows'], function(index, value) {
								// Pone el color de fondo a las mesas reservadas
								$('[idmesa=' + value["mesa"] + ']').css('background-color', '#FDFD96');
								// Elimina la clase panel-default
								$('#panel_' + value["mesa"]).removeClass('panel-default');
								// Agrega la clase panel-warning
								$('#panel_' + value["mesa"]).addClass('panel-warning');

								// Agrega el atributo de reservacion
								$('[idmesa=' + value["mesa"] + ']').attr('id_reservacion', value["id"]);
							});

							// Consulta los pedidos y las mesas para recargar si hay pedidos o mesas ocupadas
							comandas.buscaProductos();
						}
					}
				});
			}

///////////////// ******** ---- 		FIN buscar_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 		convertir_draggable		------ ************ //////////////////
//////// Convierte los id con draggable en divs ue se pueden arrastrar
	// Como parametros recibe:

			function convertir_draggable($objeto) {
				console.log('--------> Objet dragables');
				console.log($objeto);

			// Convierte los id con draggable en divs ue se pueden arrastrar
				$(function() {
					var options = {
						float : true,
						disableResize : true,
						cell_height : 77,
						vertical_margin : 5,
						scroll : false,
						resizable : {
							autoHide : true,
							handles : 'null'
						},
						alwaysShowResizeHandle : /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
					};

					$('.grid-stack').gridstack(options);

				// Agrega el dragable al droppable
					grid = $('.grid-stack').data('gridstack');
					
				// Acomoda los dragables en sus posiciones si no tienen
					$.each($objeto, function(index, value) {
						// si la mesa no iene cordenadas le asigna unas y la guarda
						if (value['y'] == -1 && value['x'] == -1) {
							// Si X es mayor al numero de columnas(5 equivale a 4 columnas) la regresa al 1 e incrementa Y
							if ($x > 10) {
								$x = 0;
								$y += 2;
							}

							var result = grid.move($('#' + value['mesa']), $x, $y);

							// Guarda sus cordenadas
							guardar_cordenadas({
								id : value['mesa'],
								x : $x,
								y : $y
							});

							$x += 2;
						}
					});
				});
			}

///////////////// ******** ---- 		FIN convertir_draggable		------ ************ //////////////////

///////////////// ******** ---- 	eliminar_mesa		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y elimina la mesa si es correcta
	// Como parametros puede recibir:
			//	pass -> contraseña a bsucar

			function eliminar_mesa($objeto) {
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=configuracion&f=pass',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						if (resp != $objeto['pass']) {
							var $mensaje = 'Contraseña incorrecta';
							$('#pass').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
						}

						if (resp == $objeto['pass']) {
							// Cierra la ventana modal
							$('#btn_cerrar').click();

							alert("Selecciona la mesa a eliminar!!!");

							ftable = 2;
						}
					}
				});
			}

///////////////// ******** ---- 	FIN	eliminar_comanda		------ ************ //////////////////

///////////////// ******** ---- 	iniciar_sesion		------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
			//	pass -> contraseña a bsucar
			// empleado -> ID del empleado

			function iniciar_sesion($objeto) {
				console.log('--------> objeto Iniciar sesion');
				console.log($objeto);

			// ** Validaciones
			// Valida si se debe de pedir el pass o no
				if($objeto['pedir_pass'] != 2){
					if (!$objeto['pass']) {
						var $mensaje = 'Introduce el pass';
						$('#pass_empleado').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});
	
						return 0;
					}
				}
			// ** Fin validaciones

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=iniciar_sesion',
					type : 'GET',
					dataType : 'json',
				}).done(function(resp) {
					console.log('--------> RESPONSE Iniciar sesion');
					console.log(resp);
					
				// Cierra la ventana modal y filtra por los permisos del empleado
					if (resp['status'] == 1) {
						$('#btn_cerrar_pass').click();
						$('#btn_cerrar_inicio').click();
						
						areas({permisos : resp['permisos']});

						return 0;
					}

				// Cierra la ventana modal y trae todas las mesas
					if (resp['status'] == 2) {
						$('#btn_cerrar_pass').click();
						$('#btn_cerrar_inicio').click();
			
						areas();

						return 0;
					}
						
				// Contraseña incorrecta :p
					if (resp['status'] == 0) {
						var $mensaje = 'Contraseña incorrecta';
						$('#pass_empleado').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});
						
						return 0;
					}
				}).fail(function(resp) {
					console.log('---------> Fail iniciar_sesion');
					console.log(resp);
		
					var $mensaje = 'Error al iniciar sesion';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});
			}

///////////////// ******** ---- 			FIN	iniciar_sesion			------ ************ //////////////////

///////////////// ******** ---- 			cerrar_sesion				------ ************ //////////////////
//////// Cierra la sesion del empleado
	// Como parametros puede recibir:

			function cerrar_sesion($objeto) {
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=cerrar_sesion',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						if (resp == 0) {
							var $mensaje = 'Error al cerrar sesion';
							$('#btn_cerrar_sesion').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});
						}

						areas();
					}
				});
			}

///////////////// ******** ---- 	FIN	cerrar_sesion		------ ************ //////////////////

///////////////// ******** ---- 	cambiar_vista		------ ************ //////////////////
//////// Cambia la vista de las mesas de cuadricula a listado
	// Como parametros puede recibir:
			// Div: div donde se cargaran las mesas

			function cambiar_vista($objeto) {
				console.log('-------> $objeto cambiar_vista');
				console.log($objeto);
					
				if (vista == 0) {// Cambia la vista a Listado
					vista = 1;

				// Consulta solo si no se ha cargado nada de informacion a la vista de lista
					if (vista_lista == '') {
						$.ajax({
							data : $objeto,
							url : 'ajax.php?c=comandas&f=cambiar_vista',
							type : 'GET',
							dataType : 'html',
							success : function(resp) {
								console.log('-------> Done cambiar_vista');
								console.log(resp);
							
							// Oculta la div de las mesas para llevar y servicio a domicilio
								$('#accordion_grid_domicilio').hide();
								
								$('#' + $objeto['div']).html(resp);

								vista_lista = $('#' + $objeto['div']).html();

								// Error: Manda un mensaje con el error
								if (!resp) {
									var $mensaje = 'Error: \n Error al cambiar la vista';

									$('#' + $objeto['div']).notify($mensaje, {
										position : "top center",
										autoHide : true,
										autoHideDelay : 5000,
										className : 'error',
									});

									return 0;
								}

							// Consulta los pedidos y las mesas para recargar si hay pedidos o mesas ocupadas
								comandas.buscaProductos();
							}
						});
					} else {
					
					// Oculta la div de las mesas para llevar y servicio a domicilio
						$('#accordion_grid_domicilio').hide();
						
						$('#' + $objeto['div']).html(vista_lista);

					// Consulta los pedidos y las mesas para recargar si hay pedidos o mesas ocupadas
						comandas.buscaProductos();
					}
				} else {// Cambia la vista a cuadricula
					vista = 0;

					var pathname = window.location.pathname;
					$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
				}
			}

///////////////// ******** ---- 	FIN	cambiar_vista		------ ************ //////////////////

///////////////// ******** ---- 	info_comandas			------ ************ //////////////////
//////// Consulta la informacion de las comandas y la agrega a las divs
	// Como parametros puede recibir:

			function info_comandas($objeto) {
				console.log('-------> $objeto info_comandas');
				console.log($objeto);
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=info_comandas',
					type : 'GET',
					dataType : 'json',
				}).done(function(resp) {
					console.log('-------> Done info_comandas');
					console.log(resp);

					$.each(resp['result']['rows'], function(index, value) {
						$('#div_tiempo_' + value['idmesa']).html('<i class="fa fa-clock-o fa-lg"></i> ' + value['tiempo']);
						$('#div_total_' + value['idmesa']).html('<i class="fa fa-credit-card-alt"></i> $' + value['total']);
					});
				}).fail(function(resp) {
					console.log('---------> Fail info_comandas');
					console.log(resp);

					// Quita el loader
					$btn.button('reset');

					var $mensaje = 'Error al obtener la informacion';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN	info_comandas		------ ************ //////////////////

///////////////// ******** ---- 	reiniciar_mesas		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y reinicia la posicion de las mesas
	// Como parametros puede recibir:
			//	pass -> contraseña a bsucar

			function reiniciar_mesas($objeto) {
				console.log('--------> objeto reiniciar_mesas');
				console.log($objeto);

				// Loader en el boton reiniciar
				var $btn = $('#btn_reiniciar_mesas');
				$btn.button('loading');

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=configuracion&f=pass',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						// Pass incorrecto
						if (resp != $objeto['pass']) {
							var $mensaje = 'Contraseña incorrecta';
							$('#pass_reiniciar').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});

							// Regresa el boton a su estado normal
							$btn.button('reset');

							return 0;
						}

						// Todo bien :D
						if (resp == $objeto['pass']) {
							$.ajax({
								data : $objeto,
								url : 'ajax.php?c=comandas&f=reiniciar_mesas',
								type : 'GET',
								dataType : 'json',
								success : function(resp) {
									console.log('--------> RESPONSE reiniciar_mesas');
									console.log(resp);

									// Regresa el boton a su estado normal
									$btn.button('reset');

									// Error :(
									if (resp['status'] == 0) {
										var $mensaje = 'Error al reiniciar las asignaciones';
										$('#pass_reiniciar').notify($mensaje, {
											position : "left",
											autoHide : true,
											autoHideDelay : 5000,
											className : 'error',
										});

										return 0;
									}

									// Cierra la ventana modal y filtra por los permisos del empleado
									if (resp['status'] == 1) {
										// Cierra la ventana de pass
										$('#btn_cerrar_mesas').click();

										var $mensaje = 'Mesas reiniciadas';
										$.notify($mensaje, {
											position : "top center",
											autoHide : true,
											autoHideDelay : 5000,
											className : 'success',
										});

										// Carga de nuevo la vista
										var pathname = window.location.pathname;
										$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
									}
								}//sucess ajax reiniciar
							});
							//ajax reiniciar
						}
					}
				});
			}

///////////////// ******** ---- 	FIN	reiniciar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	agregar_mesas		------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y agrega mesas masivamente
	// Como parametros puede recibir:
			// pass -> contraseña a bsucar
			// num_mesas -> numero de mesas a aagregar
			// num_comensales -> numero de comensales a aagregar

			function agregar_mesas($objeto) {
				console.log('--------> objeto agregar_mesas');
				console.log($objeto);

				// ** Validaciones
				if (!$objeto['num_mesas']) {
					var $mensaje = 'Escribe el numero de mesas';
					$('#div_agregar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				if (!$objeto['num_comensales']) {
					var $mensaje = 'Escribe el numero de comensales';
					$('#div_agregar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				if (!$objeto['pass']) {
					var $mensaje = 'Escribe la contraseña';
					$('#div_agregar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				// Loader en el boton reiniciar
				var $btn = $('#btn_agregar');
				$btn.button('loading');

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=configuracion&f=pass',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log(resp);

						// Pass incorrecto
						if (resp != $objeto['pass']) {
							var $mensaje = 'Contraseña incorrecta';
							$('#div_agregar').notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
							});

							// Regresa el boton a su estado normal
							$btn.button('reset');

							return 0;
						}

						// Pass correcto :D
						if (resp == $objeto['pass']) {
							$.ajax({
								data : $objeto,
								url : 'ajax.php?c=comandas&f=agregar_mesas',
								type : 'GET',
								dataType : 'json',
							}).done(function(resp) {
								console.log('--------> RESPONSE agregar_mesas');
								console.log(resp);

								// Regresa el boton a su estado normal
								$btn.button('reset');

								// Error :(
								if (resp['status'] == 0) {
									var $mensaje = 'Error al agregar las mesas';
									$('#div_agregar').notify($mensaje, {
										position : "left",
										autoHide : true,
										autoHideDelay : 5000,
										className : 'error',
									});

									return 0;
								}

								// Exito :D
								if (resp['status'] == 1) {
									var $mensaje = 'Mesas agregadas';
									$('#div_agregar').notify($mensaje, {
										position : "top center",
										autoHide : true,
										autoHideDelay : 5000,
										className : 'success',
									});
								}
							}).fail(function(resp) {
								console.log('---------> Fail agregar_mesas');
								console.log(resp);

								// Quita el loader
								$btn.button('reset');

								var $mensaje = 'Error al agregar las mesas :(';
								$('#div_agregar').notify($mensaje, {
									position : "top center",
									autoHide : true,
									autoHideDelay : 5000,
									className : 'error',
								});
							});
						}
					}
				});
			}

///////////////// ******** ---- 	FIN	reiniciar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	recargar		------ ************ //////////////////
//////// Carga de nuevo la vista del mapa de mesas
	// Como parametros puede recibir:
			// boton -> boton en el que carga el loader

			function recargar($objeto) {
				console.log('--------> objeto recargar');
				console.log($objeto);

				// Loader en el boton recargar
				var $btn = $('#' + $objeto['boton']);
				$btn.button('loading');

				// Carga de nuevo la vista
				var pathname = window.location.pathname;
				$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
			}

///////////////// ******** ---- 	FIN recargar			------ ************ //////////////////

///////////////// ******** ---- 	buscar_direccion		------ ************ //////////////////
//////// Busca la direccion del cliente, si existe la escribe en el campo si no abre un formulario para agregarlo
	// Como parametros puede recibir:
		// nombre-> nombre escrito en el campo de texto

			function buscar_direccion($objeto) {
				console.log('--------> objeto buscar_direccion');
				console.log($objeto);

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=buscar_direccion',
					type : 'GET',
					dataType : 'json',
				}).done(function(resp) {
					console.log('--------> response buscar_direccion');
					console.log(resp);

				// Exito :D, Escribe la direccion del cliente si existe
					if (resp['result']['total'] > 0) {
						$("#" + $objeto['input']).val(resp['result']['rows'][0]['direccion']);
				// Abre el formulario para dar de alta el cliente y llena el campo de nombre
					} else {
						$('#nombre').val($objeto['nombre']);
					}
				}).fail(function(resp) {
					console.log('---------> Fail validar_mesa');
					console.log(resp);

					var $mensaje = 'Error al buscar los datos';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN buscar_direccion		------ ************ //////////////////

///////////////// ******** ---- 	servicio_domicilio			------ ************ //////////////////
//////// Crea una mesa temporal de servicio para domicilio
	// Como parametros puede recibir:
		// nombre -> nombre del cliente
		// direccion -> direccion
		// tel -> telefono
		// btn -> boton loader
		// nuevo -> 1 -> cliente nuevo

			function servicio_domicilio($objeto) {
				console.log('--------> objeto servicio_domicilio');
				console.log($objeto);				
		
			// Loader en el boton reiniciar
				var $btn = $('#' + $objeto['btn']);
				$btn.button('loading');
			
			// Agrega un cliente nuevo
				if($objeto['nuevo'] == 1){
					$objeto['direccion'] = $objeto['domicilio'];
					var cliente_nuevo = comandas.agregar_cliente($objeto);
			
					console.log('--------> var cliente_nuevo');
					console.log(cliente_nuevo);
			
					if(cliente_nuevo == 0){
						$btn.button('reset');
						
						return 0;
					}
				}
				
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=deliveryService',
					type : 'GET',
					dataType: 'html'
				}).done(function(resp) {
					console.log('-----> done servicio_domicilio');
					console.log(resp);
					
					$("#div_ejecutar_scripts").html(resp);
					
					$btn.button('reset');
					
				// Cierra la modal para llevar
					$("#modal_servicio_domicilio").click();
					$("#modal_editar_cliente_domicilio").click();
					$("#modal_comandera").modal("show");
				}).fail(function(resp) {
					console.log('---------> Fail servicio_domicilio');
					console.log(resp);
		
					var $mensaje = 'Error al crear la mesa';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN servicio_domicilio		------ ************ //////////////////

///////////////// ******** ---- 	edit_cliente ch@			------ ************ //////////////////
//////// Crea una mesa temporal de servicio para domicilio
	// Como parametros puede recibir:
		// nombre -> nombre del cliente
		// direccion -> direccion
		// tel -> telefono
		// btn -> boton loader
		// nuevo -> 1 -> cliente nuevo
		
		/*
			function edit_cliente(id) {

				alert(id);
				$("#modal_edit_cliente").modal('show');

				
				var nombre = $objeto['nombre'];	

				var id_viacontacto = $objeto['id_viacontacto'];
				var id_zonareparto = $objeto['id_zonareparto'];		

				$("#idClienteE").val($objeto['idClienteE']);

				$("#cliente_servicio_domicilioE").val($objeto['nombre']);
				$("#domicilio_servicio_domicilioE").val($objeto['domicilio']);		
				$("#tel_servicio_domicilioE").val($objeto['tel']);

				$("#via_contacto_domicilioE").val(id_viacontacto);
				$("#zona_reparto_domicilioE").val(id_zonareparto);
				$('.selectpicker').selectpicker('refresh'); // actualiza select
				
			}
		*/

///////////////// ******** ---- 	FIN edit_cliente		------ ************ //////////////////

///////////////// ******** ---- 		para_llevar				------ ************ //////////////////
//////// Crea una mesa temporal para llevar
	// Como parametros puede recibir:
		// name-> nombre del cliente
		// address-> direccion
		// tel -> telefono
		// btn -> boton loader
		// nuevo -> 1 -> cliente nuevo
		// tipo_operacion -> Tipo de operacion

			function para_llevar($objeto) {
				console.log('--------> objeto para_llevar');
				console.log($objeto);
				
			// Loader en el boton reiniciar
				var $btn = $('#' + $objeto['btn']);
				$btn.button('loading');
				
			// Agrega un cliente nuevo
				if($objeto['nuevo'] == 1){
					$objeto['direccion'] = $objeto['domicilio'];
					var cliente_nuevo = comandas.agregar_cliente($objeto);
			
					console.log('--------> var cliente_nuevo');
					console.log(cliente_nuevo);
			
					if(cliente_nuevo == 0){
						$btn.button('reset');
						
						return 0;
					}
				}
				
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=foodGo',
					type : 'GET',
					dataType : 'html',
				}).done(function(resp) {
					console.log('-----> done para llevar');
					console.log(resp);
		
					$("#div_ejecutar_scripts").html(resp);
					
					$btn.button('reset');
					
				// Cierra la modal para llevar
					$("#modal_para_llevar").click();
					$("#modal_editar_para_llevar").click();
					$("#modal_comandera").modal("show");
				}).fail(function(resp) {
					console.log('---------> Fail para llevar');
					console.log(resp);
		
					var $mensaje = 'Error al crear la mesa';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 		FIN para_llevar			------ ************ //////////////////

///////////////// ******** ---- 	asignar_repartidor ch@			------ ************ //////////////////
//////// Crea una mesa temporal de servicio para domicilio

			function asignar_repartidor(idrep,rep) { //ch@
				$("#inpidrep").val(idrep);
				$("#inprepartidor").val(rep);
			}

			function asignar(){
				var idcomanda = $("#inpcomanda").val();
				var idrep = $("#inpidrep").val();
				if($("#inpidrep").val() == ''){
					alert('Primero debe Seleccionar un Repartidor');
					return false;
				}
				/// confirm
				var r = confirm("¿Esta seguro de asignar el repartidor?");
				if (r == true){
					//alert('guardado');
					//return false;
					$.ajax({
						url: 'ajax.php?c=comandas&f=asignarRep',
						type: 'POST',
						data: {idrep:idrep,idcomanda:idcomanda},
					})
					.done(function() {
						console.log("success");
						$('#modal_repartidores').modal('hide');
					})
					.fail(function()   { console.log("error");    })
					.always(function() { console.log("complete"); });
				}
			}

///////////////// ******** ---- 	FIN asignar_repartidor		------ ************ //////////////////

///////////////// ******** ---- 			modal_login			------ ************ //////////////////
//////// Abre la modal de login, llena los campos y hace un focus
	// Como parametros puede recibir:
			// id-> ID del usuario
			// nombre-> nombre del usuario

			function modal_login($objeto) {
				console.log('--------> objeto modal_login');
				console.log($objeto);

			// llena los campos
				setTimeout(function() {
					$('#empleado').val($objeto['empleado']);
					$('#id_empleado').val($objeto['id']);
					$('#pass_empleado').focus();
				}, 500);
			}

///////////////// ******** ---- 		FIN modal_login			------ ************ //////////////////

///////////////// ******** ---- 			info_mesas			------ ************ //////////////////
//////// Consulta las mesas y las devuelve en un array
	// Como parametros puede recibir:
	
			function info_mesas() {
				comandas.info_mesas();
			}
			
///////////////// ******** ---- 		FIN info_estados		------ ************ //////////////////

///////////////// ******** ---- 		asignar_repartidor		------ ************ //////////////////
//////// Asigna el repartidor a la mesa
	// Como parametros puede recibir:
	
			function asignar_repartidor($objeto) {
				console.log('entrar asignar_repartidor');
				
				var id_repartidor = '';
				$('#inpmesa,#inpcomanda,#inpidrep,#inprepartidor').val('');
		
				var idmesa = $(this).attr('idmesaR');
				var idcomanda = $(this).attr('idcomandaR');
		
				// if si existe comanda hacer esto si
				if (idcomanda != '') {
					$.ajax({
						url : 'ajax.php?c=comandas&f=verAsignado',
						type : 'POST',
						data : {
							idcomanda : idcomanda
						},
						dataType : 'json',
						async : false,
						success : function(callback) {
							$.each(callback, function(index, value) {
								id_repartidor = value['id_repartidor'];
							});
						}
					})
		
					if (id_repartidor != '') {
						//$(inpidrep).val(id_repartidor);
						//$(inprepartidor).val('');
						alert('Ya se asigno un repartidor');
						return false;
		
					} else {
						$('#inpidrep,#inprepartidor').val('');
					}
		
					$('#inpmesa').val(idmesa);
					$('#inpcomanda').val(idcomanda);
					//alert('Aqui se sgrega al repartidor y la mesa es'+ idmesa+ 'la comanda es '+ idcomanda);
					$('#modal_repartidores').modal('show');
					return false;
				}
			}
			
///////////////// ******** ---- 	FIN asignar_repartidor		------ ************ //////////////////
		</script>
		
<!-- ** Funciones iniciales -->
		<script>
		// Trae la informacion de los estados y los agrega al select para el registro de usuario
			info_estados();
		// Consulta las reservaciones
			buscar_reservaciones();
		// Consulta el tiempo de las comandas
			info_comandas();
		</script>
<!-- ** FIN Funciones iniciales -->
	</head>
<!-- Modal Empleados-->
	<div 
		class="modal fade" 
		style="height:95%" 
		id="modal_inicio" 
		tabindex="-1" 
		role="dialog" 
		aria-labelledby="titulo_inicio" 
		data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button 
						id="btn_cerrar_inicio" 
						type="button" 
						class="close" 
						data-dismiss="modal" 
						aria-label="Close" 
						style="visibility: hidden">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="titulo_inicio">Seleccionar</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12" style="overflow: scroll;height:80%"><?php
							$clases[0]='default';
							$clases[1]='success';
							$clases[2]='warning';
							$clases[3]='primary';
							$clases[4]='danger';
							$clases[5]='info';
							
							$posi=0;
							foreach ($empleados as $key => $value) { ?>
								<div class="pull-left" style="padding:5px">
									<button 
										type="button" 
										class="btn btn-<?php echo $clases[$posi] ?> btn-lg"<?php
									// ** Valida que funcion se debe usar
									// 1 -> necesita pass, 2 -> no necesita
										if ($configuracion['pedir_pass'] == 1) { ?>
											onclick="modal_login({
												empleado:'<?php echo $value['usuario'] ?>', 
												id:'<?php echo $value['id'] ?>'
											})"
											data-toggle="modal" 
											data-target="#modal_pass"<?php
										} else { ?>
											onclick="iniciar_sesion({
												empleado: '<?php echo $value['id'] ?>', 
												pedir_pass:<?php echo $configuracion['pedir_pass'] ?>
											})"<?php
										} ?>
										style="width: 110px;">
										<i class="fa fa-user"></i> <br>
										<i style="font-size: 15px" ><?php echo substr($value['usuario'], 0, 8); ?></i>
									</button>
								</div><?php
								
								$posi++;
								$posi=($posi>5)?0:$posi;
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- FIN Modal Empleados-->
		
<!-- Modal pass-->
	<div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="titulo_pass" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button id="btn_cerrar_pass" type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="titulo_pass">Ingresar</h4>
				</div>
				<div class="modal-body">
					<input readonly="1" id="id_empleado" type="text" class="form-control" style="visibility:hidden" />
					<div class="row">
						<div class="col-xs-6">
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
								<input readonly="1" id="empleado" type="text" class="form-control" />
							</div>
						</div>
						<div class="col-xs-6">
							<div class="input-group">
								<span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
								<input autocomplete="off" name="empleado" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})" id="pass_empleado" type="password" class="form-control" autofocus="autofocus">
							</div>
						</div>
					</div>
				</div>
			
			<!-- Botones -->
				<div class="modal-footer">
					<button 
						type="button" 
						class="btn btn-primary" 
						onclick="iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})">
						<i class="fa fa-sign-in"></i> Entrar
					</button>
				</div>
			</div>
		</div>
	</div>
<!-- FIN Modal pass-->

<!-- Valida que este la sesion iniciada -->
<?php
	if (!$_SESSION['mesero']) {
		echo "	<script>
					$('#modal_inicio').modal({
					  keyboard: false,
					  show:true
					});
				</script>";
				
		return 0;
	}
?>
	<body>		
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
						<!-- Funciones -->
							<div class="col-xs-6" style="margin-top: 10px">
								<div class="panel-group" id="accordion_funciones" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default">
										<div 
											class="panel-heading" 
											id="heading_funciones" 
											role="tab" 
											role="button" 
											style="cursor: pointer" 
											data-toggle="collapse" 
											data-parent="#accordion_funciones" 
											href="#tab_funciones" 
											aria-controls="collapse" 
											aria-expanded="true">
											<h4 class="panel-title">
												<strong><i class="fa fa-wrench"></i> Funciones</strong>
											</h4>
										</div>
										<div 
											id="tab_funciones" 
											class="panel-collapse collapse" 
											role="tabpanel" 
											aria-labelledby="heading_funciones">
											<div class="panel-body">
												<button 
													class="btn btn-success btn-lg" 
													style="font-size:14px; width:170px; margin-top:0.5%" 
													data-toggle="modal" 
													data-target="#modal_agregar">
													<i class="fa fa-plus"></i> Agregar
												</button>
												<button 
													type="button" 
													class="btn btn-danger btn-lg remove" 
													onclick="comandas.vista_eliminar_mesas({
														div: 'div_eliminar_mesas'
													})"
													data-toggle="modal" 
													data-target="#modal_eliminar" 
													style="font-size:14px; width: 170px; margin-top: 0.5%">
													<i class="fa fa-trash-o"></i> Eliminar
												</button>
												<button 
													type="button" 
													class="btn btn-primary btn-lg" 
													data-toggle="modal" 
													data-target="#modal_servicio_domicilio" 
													style="font-size:14px; width: 170px; margin-top: 0.5%">
													<i class="fa fa-motorcycle"></i> A domicilio
												</button>
												<button 
													type="button" 
													class="btn btn-success btn-lg foodGo" 
													data-toggle="modal" 
													data-target="#modal_para_llevar" 
													style="font-size:14px; width: 170px; margin-top: 0.5%">
													<i class="fa fa-shopping-basket"></i> Para llevar
												</button>
												<button 
													type="button" 
													class="btn btn-info btn-lg" 
													onclick="comandas.vista_juntar_mesas({
														div: 'div_juntar_mesas'
													})"
													data-toggle="modal" 
													data-target="#modal_juntar_mesas" 
													style="font-size:14px; width: 170px; margin-top: 0.5%">
													<i class="fa fa-object-ungroup"></i> Juntar
												</button>
												<button 
													id="btn_vista" 
													onclick="cambiar_vista({div: 'contenedor'})" 
													class="btn btn-warning btn-lg" 
													style="font-size:14px; width: 170px; margin-top: 0.5%">
													<i class="fa fa-th-large"></i>	/	<i class="fa fa-list"></i> Vista
												</button>
												<button 
													class="btn btn-danger btn-lg" 
													style="font-size:14px; width: 170px; margin-top: 0.5%" 
													data-toggle="modal" 
													data-target="#modal_reiniciar_mesas">
													<i class="fa fa-retweet"></i> Reiniciar
												</button>
												<button 
													id="btn_actualizar" 
													class="btn btn-info btn-lg" 
													data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
													style="font-size:14px; width: 170px; margin-top: 0.5%" 
													onclick="recargar({boton: 'btn_actualizar'})">
													<i class="fa fa-refresh"></i> Actualizar
												</button>
												<button 
													class="btn btn-warning btn-lg" 
													style="font-size:14px; width: 170px; margin-top: 0.5%" 
													onclick="info_comandas({
															status: '0', 
															empleado: '*', 
															mesa: '*', 
															f_ini: '<?php echo date('Y-m-d').' 00:01' ?>', 
															f_fin: '<?php echo date('Y-m-d H:i')?>'
													});">
													<i class="fa fa-clock-o"></i> / <i class="fa fa-usd"></i> Ver
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="margin-top: 10px">
								<div class="panel-group" id="accordion_areas" role="tablist" aria-multiselectable="true">
									<div class="panel panel-default">
										<div 
											class="panel-heading" 
											id="heading_areas" 
											role="tab" 
											role="button" 
											style="cursor: pointer" 
											data-toggle="collapse" 
											data-parent="#accordion_areas" 
											href="#tab_areas" 
											aria-controls="collapse_areas" 
											aria-expanded="true">
											<h4 class="panel-title">
												<strong><i class="fa fa-cutlery"></i> Zonas</strong>
											</h4>
										</div>
										<div 
											id="tab_areas" 
											class="panel-collapse collapse" 
											role="tabpanel" 
											aria-labelledby="heading_areas">
											<div class="panel-body">
												<button 
													onclick="areas()" 
													type="button" 
													class="btn btn-danger btn-lg" 
													style="font-size:14px; width:170px; margin-top:0.5%">
													<i class="fa fa-star"></i> Todas
												</button><?php
												
											// Array con las clases para el cambio de color de los botones
												$clase=Array();
												$clase[0]='#779ECB; color: white';//Azul con letras blancas
												$clase[1]='#CFCFC4';//Gris
												$clase[2]='#77DD77; color: white';//Verde con letras blancas
												$clase[3]='#836953; color: white';//Cafe con letras blancas
												$clase[4]='#FFB347';//Naranja
												$clase[5]='#FDFD96';//Amarillo
												
											//** Creamos los botones de las areas
												// areas es un array que viene desde el modelo
												foreach ($areas as $key => $value) {
													$value['area'] = (empty($value['area'])) ? '* Sin asignar' : $value['area'] ; ?>
														
													<button 
														onclick="areas({id:'<?php echo $value['id'] ?>'})" 
														type="button" 
														class="btn btn-lg" 
														style="font-size:14px; width:170px; margin-top:0.5%; background-color: <?php echo $clase[$key] ?>">
														<i class="fa fa-cutlery"></i> <?php echo $value['area'] ?>
													</button><?php
												} ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" align="center" style="padding-bottom: 15%"> <!-- style="overflow: scroll;height:55%" -->
				<div class="row">
					<div class="col-md-7">
						<div style="float:left;margin-left:11%;background:#2E9AFE;border-radius:8px;height:46px;width:13%;margin-top:4px;visibility:hidden" class="btnConfirm"><div style="margin-top:13px;font-family:verdana;font-size:12px;color:#ffffff"><a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">Aceptar</a></div></div>				
						<div style="float:left;margin-left:2%;height:46px;width:30%;margin-top:4px" class="btnAddTable"><div style="margin-top:13px;font-family:verdana;font-size:17px;font-weight:600;color:#303030">Mesas Disponibles </div></div>
						<div style="float:left;margin-left:5px;background:#2E9AFE;border-radius:8px;height:46px;width:13%;margin-top:4px;visibility:hidden" class="btnCancel"><div style="margin-top:13px;font-family:verdana;font-size:12px;color:#ffffff"><a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">Cancelar</a></div></div>
					</div>
					<div class="col-md-3">
						<div class="input-group input-group-lg">
							<input 
								id="perro" 
								onkeypress="if(((document.all) ? event.keyCode : event.which)==13) buscamesaboton2()" 
								type="search" 
								class="form-control" 
								placeholder="Buscar...">
							<span class="input-group-btn">
								<button onclick="buscamesaboton2()" class="btn btn-success" type="button">
									<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</div>
					<div class="col-md-1">
						<button onclick="cerrar_sesion();" 
							id="btn_cerrar_sesion" 
							type="button" 
							class="btn btn-default btn-lg" 
							data-toggle="modal" 
							data-target="#modal_inicio">
							<i class="fa fa-sign-out"></i> Salir
						</button>
					</div>
				</div>
				<div>
					<br /><br />
					<div id="numbersKey" style="display:none;">
						<div class="tecado">
							<table id="table" class="teclado">
								<tr>
									<td class="btnKeynum">1</td>
									<td class="btnKeynum">2</td>
									<td class="btnKeynum">3</td>
								</tr>
								<tr>
									<td class="btnKeynum">4</td>
									<td class="btnKeynum">5</td>
									<td class="btnKeynum">6</td>
								</tr>
								<tr>
									<td class="btnKeynum">7</td>
									<td class="btnKeynum">8</td>
									<td class="btnKeynum">9</td>
								</tr>
								<tr>
									<td class="btnKeynum">.</td>
									<td class="btnKeynum">0</td>
									<td class="btnKeynumdel" >Borrar</td>
								</tr>
							</table>
						</div>
						<div class="form-group">
							<input id="sendcomattr" type="button" class="GtableTable0 btn btn-success" style="display:none;">
						</div>
					</div>
				</div>
				<div class="panel-group" id="accordion_grid_domicilio" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default panel-lg">
						<div
							class="panel-heading"
							id="heading_grid_domicilio"
							role="tab"
							role="button"
							style="cursor: pointer"
							data-toggle="collapse"
							data-parent="#accordion_grid_domicilio"
							href="#tab_domicilio"
							aria-controls="collapse_grid_domicilio"
							aria-expanded="true">
							<h4 class="panel-title">
								<strong>
									<i class="fa fa-motorcycle"></i> Servicio a domicilio / 
									<i class="fa fa-shopping-basket"></i> Para llevar</strong>
							</h4>
						</div>
						<div
							id="tab_domicilio"
							class="panel-collapse collapse in"
							role="tabpanel"
							aria-labelledby="heading_grid_domicilio">
							<div class="panel-body" id="panel_domicilio">
								<div class="grid-stack" id="contenedor_domicilio">
									<!-- Aqui se cargan las mesas para llevar y a domicilio -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="GtableTablesContent grid-stack" id="contenedor" style="width:100%; margin-top:1%"><?php
					session_start();
					foreach ($_SESSION['tables'] as $key => $row) {
						switch($row['tipo']){
						//** Mesa normal(Individuales o juntas)
							case 0:
							// Mesa individual
								if($row['idmesas'] == ''){ ?>
									<div 
										class="grid-stack-item" 
										id="<?php echo $row['mesa'] ?>" 
										data-gs-x="<?php echo $row['x'] ?>" 
										data-gs-y="<?php echo $row['y'] ?>" 
										data-gs-no-resize="1" 
										data-gs-width="2" 
										data-gs-height="2">
										<div class="grid-stack-item-content panel panel-default">
											<div class="panel-heading" style="cursor: move">
												<div class="row">
													<div class="col-xs-5">
														<i class="fa fa-user fa-lg text-primary"></i> <?php echo $row['personas']; ?>
													</div>
													<div class="col-xs-7" id="div_tiempo_<?php echo $row['mesa'] ?>">
														<!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia -->
													</div>
												</div>
											</div>
											<div 
												onclick="comandera.mandar_mesa_comandera({
													id_mesa: <?php echo $row['mesa'] ?>,
													tipo: 0,
													id_comanda: $(this).attr('id_comanda'),
													tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
												})"
												id="mesa_<?php echo $row['mesa'] ?>"
												id_comanda="<?php echo $row['idcomanda'] ?>"
												data-toggle="modal" 
												data-target="#modal_comandera">
												<a href="javascript:void(0)" style="color: #000000">
													<div class="panel-body">
														<div class="GtableTableIcon" align="center">
															<div class="row">
																<div class="col-xs-6">
																	<h2><?php echo $row['nombre_mesa'] ?></h2>
																	<div id="div_total_<?php echo $row['mesa'] ?>">
																		<!-- En esta div se carga el total de la comanda -->
																	</div>
																</div>
																<div class="col-xs-6" style="padding-top: 1%"><?php
																	if (!empty($row['mesero'])) { ?>
																		<i class="fa fa-hand-o-up fa-lg text-primary"></i> 
																		<p id="mesero_<?php echo $row['mesa'] ?>">
																			<?php echo $row['mesero']; ?>	
																		</p><?php
																	} ?>
																</div>
															</div> 
														</div>
													</div>
												</a>
											</div>
										</div>
									</div><?php
							// * Mesa compuesta
								}else{
									$ids = explode(',',$row['idmesas']);
									$personas = explode(',',$row['mpersonas']);
									$size = count($ids); 
									$total_personas=0;
			
								// Calcula el total de personas
									foreach ($personas as $key => $value) {
										$total_personas += $value;
									} ?>
									
									<div 
										class="grid-stack-item" 
										id="<?php echo $row['mesa'] ?>"  
										data-gs-x="<?php echo $row['x'] ?>" 
										data-gs-y="<?php echo $row['y'] ?>" 
										data-gs-width="2" 
										data-gs-height="2">
										<div class="grid-stack-item-content panel panel-info">
											<div class="panel-heading" style="cursor: move">
												<div class="row">
													<div class="col-xs-7">
														<div class="input-group">
															<div class="input-group-addon">
																<i class="fa fa-user"></i>
															</div>
															<input type="text" disabled="disabled" class="form-control" value="<?php echo $total_personas; ?>">
														</div>
													</div>
														<div class="col-xs-5">
														<i class="fa fa-object-ungroup fa-3x"></i>
													</div>
												</div>
											</div>
											<div 
												onclick="comandera.mandar_mesa_comandera({
														id_mesa: <?php echo $row['mesa'] ?>,
														tipo: 0,
														id_comanda: $(this).attr('id_comanda'),
														separar: 1,
														tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
													})"
													id="mesa_<?php echo $row['mesa'] ?>"
													id_comanda="<?php echo $row['idcomanda'] ?>"
													data-toggle="modal" 
													data-target="#modal_comandera">
												<a href="javascript:void(0)" style="color: #000000">
													<div class="panel-body">
														<div class="GtableTableIcon" align="center">
															<div class="row">
																<div class="col-xs-6" style="margin-top: -20px">
																	<h2>
																		<div class="input-group">
																			<textarea disabled="disabled" rows="2" class="form-control" style="cursor: pointer"><?php echo $row['nombre_mesa'] ?></textarea>
																		</div>
																	</h2>
																	<div id="div_total_<?php echo $row['mesa'] ?>">
																		<!-- En esta div se carga el total de la comanda -->
																	</div>
																</div>
																<div class="col-xs-6" style="vertical-align: middle"><?php
																if (!empty($row['mesero'])) { ?>
																	<div class="col-xs-6" style="padding-top: 17%">
																		<i class="fa fa-hand-o-up fa-lg text-primary"></i>
																		<p id="mesero_<?php echo $row['mesa'] ?>">
																			<?php echo $row['mesero']; ?>	
																		</p>
																	</div><?php
																} ?>
																</div>
															</div> 
														</div>
													</div>
												</a>
											</div>
										</div>
									</div><?php
								} //Else
							break;//** FIN Mesa normal(Individuales o juntas)
							
						//** Para llevar
							case 1: ?>
								<div class="grid-stack-item" id="<?php echo $row['mesa'] ?>"  data-gs-x="<?php echo $row['x'] ?>" data-gs-y="<?php echo $row['y'] ?>" data-gs-width="2" data-gs-height="2">
									<div class="grid-stack-item-content panel panel-danger">
										<div class="panel-heading" style="cursor: move">
											<div class="row">
												<div class="col-xs-12">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-home"></i>
														</div>
														 <!--  <input type="text" disabled="disabled" class="form-control" value="<?php echo $row['domicilio']; ?>"> cambio -->		
														 <input type="text" disabled="disabled" class="form-control" value="<?php echo $row['nombre_mesa']; ?>"> <!-- cambio -->													
													</div>
												</div>
											</div>
										</div>
										<div  
											onclick="comandera.mandar_mesa_comandera({
												id_mesa: <?php echo $row['mesa'] ?>,
												tipo: 1,
												id_comanda: $(this).attr('id_comanda'),
												tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
											})"
											id="mesa_<?php echo $row['mesa'] ?>"
											id_comanda="<?php echo $row['idcomanda'] ?>"
											data-toggle="modal" 
											data-target="#modal_comandera">
											<a href="javascript:void(0)" style="color: #000000">
											<div class="panel-body">
												<div class="GtableTableIcon" align="center">
													<div class="row">
														<div class="col-xs-6">
															<i class="fa fa-shopping-basket fa-3x"></i>
															<div id="div_total_<?php echo $row['mesa'] ?>">
																<!-- En esta div se carga el total de la comanda -->
															</div>
														</div>
														<div class="col-xs-6" style="padding-top: 10%">
															<i class="fa fa-male fa-lg text-primary"></i> 
															<p id="mesero_<?php echo $row['mesa'] ?>">
																<?php echo $row['mesero']; ?>	
															</p>
														</div>
													</div>
												</div>
											</div> </a>
										</div>
									</div>

								</div>
								<script>
									$("#"+<?php echo $row['mesa'] ?>).appendTo("#contenedor_domicilio");
									comandera.datos_mesa_comanda.tipo_operacion = <?php echo $configuracion['tipo_operacion'] ?>
								</script><?php
							break;//** FIN Para llevar
						
						//** Servicio a domicilio
							case 2: ?>
								<div 
									class="grid-stack-item" 
									id="<?php echo $row['mesa'] ?>"  
									data-gs-x="<?php echo $row['x'] ?>" 
									data-gs-y="<?php echo $row['y'] ?>" 
									data-gs-width="2" 
									data-gs-height="2">
									<div class="grid-stack-item-content panel panel-primary">
										<div class="panel-heading" style="cursor: move">
											<div class="row">
												<div class="col-xs-12">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-home"></i>
														</div>
														<input type="text" disabled="disabled" class="form-control" value="<?php echo $row['domicilio']; ?>">
													</div>
												</div>
											</div>
										</div>
										<div>
											<a href="javascript:void(0)" style="color: #000000">
											<div class="panel-body">
												<div class="GtableTableIcon" align="center">
													<div class="row">
														<div 
															class="col-xs-6"
															onclick="comandera.mandar_mesa_comandera({
																id_mesa: <?php echo $row['mesa'] ?>,
																tipo: 2,
																id_comanda: $(this).attr('id_comanda'),
																nombre: '<?php echo  $row['nombre'] ?>',
																domicilio: '<?php echo $row['domicilio'] ?>',
																tel: '<?php echo $row['tel'] ?>',
																tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
															})"
															data-toggle="modal" 
															id="mesa_<?php echo $row['mesa'] ?>"
															data-target="#modal_comandera"
															id_comanda="<?php echo $row['idcomanda'] ?>">
															<i class="fa fa-motorcycle fa-3x"></i>
															<div id="div_total_<?php echo $row['mesa'] ?>">
																<!-- En esta div se carga el total de la comanda -->
															</div>
														</div>
														<div class="col-xs-6" style="padding-top: 10%">
															<i 
																class="fa fa-male fa-3x fa-lg text-primary repa" 
																id="mesaR_<?php echo $row['mesa'] ?>" 
																idmesaR="<?php echo $row['mesa'] ?>" 
																idcomandaR="<?php echo $row['idcomanda'] ?>" ></i> 
																<p id="mesero_<?php echo $row['mesa'] ?>">
																	<?php echo $row['mesero']; ?>	
																</p>
														</div>
													</div>
												</div>
											</div> </a>
										</div>
									</div>
								</div>
								<script>
									$("#"+<?php echo $row['mesa'] ?>).appendTo("#contenedor_domicilio")
								</script><?php
							break;//** FIN Servicio a domicilio
						} // Switch
					}// Fin foreach ?>
					
					<script>
							// reloadTableEvents();
						// Consulta el estatus de las mesas cada 10 segundos
							setInterval(info_mesas, 10000);
							
						// Consulta el tiempo de las comandas cada minuto
							setInterval(info_comandas, 60000);
					</script>
				</div>
			</div>
		</div>
	<!-- Modal Cliente no existe-->
		<div class="modal fade" id="myModal" style="z-index:1051" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Registro de cliente</h4>
					</div>
				<form id="datos_cliente">
					<div class="modal-body">
							<blockquote style="font-size: 14px">
						    	<p>
						      		El cliente <strong>no existe</strong>, si deseas agregarlo llena los campos con los datos
						      		correspondientes y pulsa <strong>"Guardar"</strong>. Si no solamente pulsa <strong>"Cerrar"</strong> o <strong>"X"</strong>.
						    	</p>
						    </blockquote>
						    
							<h5><strong>Los campos con * son obligatorios</strong></h5>
						<!-- Nombre y direccion -->
							<div class="row">
							<!-- Nombre -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>* Nombre: </label>
										<input id="nombre" required="1" type="text" class="form-control" placeholder="Pedro paramo">
									</div>
								</div>
								
							<!-- Direccion -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>* Direccion: </label>
										<input id="direccion" required="1" type="text" class="form-control" placeholder="Algun lugar">
									</div>
								</div>
							</div><br />
							
						<!-- Numero interior y exterior -->
							<div class="row">
							<!-- Num. int. -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>Num. int.: </label>
										<input id="num_int" type="number" class="form-control" placeholder="0000">
									</div>
								</div>
								
							<!-- Num. Ext. -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>* Num. Ext.: </label>
										<input id="num_ext" required="1" type="number" class="form-control" placeholder="0000">
									</div>
								</div>
							</div><br />
							
						<!-- Colonia y codigo postal -->
							<div class="row">
							<!-- Colonia -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>* Colonia: </label>
										<input id="colonia" required="1" type="text" class="form-control" placeholder="Colonia">
									</div>
								</div>
								
							<!-- Codigo postal -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>* CP: </label>
										<input id="cp" required="1" type="number" maxlength="5" max="99999" class="form-control" placeholder="00000">
									</div>
								</div>
							</div><br />
							
						<!-- Estados y Municipios -->
							<div class="row">
							<!-- Estados -->
								<div class="col-xs-6">
									<label>* Estado: </label><br />
									<select required="1" class="selectpicker" data-live-search="true" id="estado" onchange="info_municipios({id_estado: $('#estado').val()})">
										<option value="">-- Seleccionar --</option>
									</select>
								</div>
								
							<!-- Municipios -->
								<div class="col-xs-6">
									<label>* Municipio: </label><br />
									<select class="selectpicker" data-live-search="true" required="1" id="municipio">
										<option value="">-- Seleccionar --</option>
									</select>
								</div>
							</div><br />
							
						<!-- E-mail y Telefono -->
							<div class="row">
							<!-- E-mail -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>E-mail: </label>
										<input id="mail" type="email" class="form-control" placeholder="ejemplo@ejem.com">
									</div>
								</div>
								
							<!-- Telefono -->
								<div class="col-xs-6">
									<div class="input-group">
										<label>Telefono: </label>
										<input id="tel" type="tel" class="form-control" placeholder="0123456789">
									</div>
								</div>
							</div><br />
						</div>
					
					<!-- Botones -->
						<div class="modal-footer">
							<button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">
								Cerrar
							</button>
							<button type="button" class="btn btn-primary"  onclick="agregar_cliente({formulario: 'datos_cliente'})">
								Guardar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!-- FIN Modal Cliente no existe-->
		
	<!-- Modal eliminar mesa -->
		<div id="modal_eliminar" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Eliminar mesa</h4>
	      			</div>
	      			<div class="modal-body">
	      				<div class="row">
	      					<div class="col-md-12" id="div_eliminar_mesas">
	      						<!-- En esta div se cargan las mesas -->
	      					</div>
	      				</div>
	      			</div>
				    <div class="modal-footer">
	        			<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
							<input 
								id="pass" 
								onkeypress="if(((document.all) ? event.keyCode : event.which)==13) eliminar_mesa({pass:$('#pass').val()})" 
								type="password" 
								class="form-control">
							<span class="input-group-btn">
			        			<button 
			        				onclick="comandas.eliminar_mesas({
			        					pass: $('#pass').val(),
			        					mesas: comandas.mesas_seleccionadas,
			        					btn: 'btn_eliminar_mesas'
			        				})" 
			        				class="btn btn-danger" 
			        				type="button"
			        				id="btn_eliminar_mesas"
			        				data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
			        				<i class="fa fa-trash"></i> Eliminar
			        			</button>
			      			</span>
						</div>
				   	</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal eliminar mesa -->
	
	<!-- Modal juntar mesas -->
		<div id="modal_juntar_mesas" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Juntar mesas</h4>
	      			</div>
	      			<div class="modal-body">
	      				<div class="row">
	      					<div class="col-md-12" id="div_juntar_mesas">
	      						<!-- En esta div se cargan las mesas -->
	      					</div>
	      				</div>
	      			</div>
				    <div class="modal-footer">
						<button
							onclick="comandas.juntar_mesas({
								mesas: comandas.mesas_seleccionadas,
								btn: 'btn_juntar_mesas'
							})"
							class="btn btn-info btn-lg"
							type="button"
							id="btn_juntar_mesas"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
							<i class="fa fa-object-ungroup"></i> Juntar
						</button>
					</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal eliminar mesa -->
	
	<!-- Modal reiniciar mesas -->
		<div id="modal_reiniciar_mesas" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Autorizar</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Esta funcion <strong>reinicia</strong> la posicion de las mesas 
					      		a su estado original y las alinea a la cuadricula
					    	</p>
					    </blockquote>
	      				<h3><small>Introduce la contraseña:</small></h3>
	        			<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
							<input id="pass_reiniciar" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) reiniciar_mesas({pass:$('#pass_reiniciar').val()})" type="password" class="form-control">
							<span class="input-group-btn">
			        			<button 
			        				id="btn_reiniciar_mesas" 
			        				data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
			        				onclick="reiniciar_mesas({pass:$('#pass_reiniciar').val()})" class="btn btn-primary" type="button">
			        				<i class="fa fa-refresh"></i> Reiniciar
			        			</button>
			      			</span>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal reiniciar mesas -->
	
	<!-- Modal agregar -->
		<div id="modal_agregar" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Agregar mesas</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Aqui puedes agregar mesas <strong>¡Masivamente!</strong>. Escribe el numero de 
					      		<strong>mesas</strong> y <strong>comensales</strong> que deseas agregar, 
					      		tambien puedes seleccionar el <strong>empleado</strong> si deseas <strong>asignarle</strong>
					      		las mesas que vas a crear
					    	</p>
					    </blockquote>
			      		<h3><small>Mesero:</small></h3>
			      		<select class="selectpicker" data-live-search="true" id="empleado_agregar">
							<option selected value="">-- Sin asignar --</option><?php
							
							foreach ($empleados as $key => $value) { ?>
								<option value="<?php echo $value['id'] ?>">
									<?php echo $value['usuario'] ?>
								</option> <?php
							} ?>
							
						</select>
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Numero de mesas:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-slack"></i></span>
									<input id="num_mesas" type="number" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Numero de comensales:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input 
										onchange="comandera.guardar_comensales({
											comensales: $(this).val(),
											comanda: comandera['datos_mesa_comanda']['id_comanda']
										})" 
										id="num_comensales" 
										type="number" 
										class="form-control">
								</div>
					    	</div>
					    </div>
	      				<h3><small>Contraseña:</small></h3>
	        			<div class="input-group input-group-lg" id="div_agregar">
							<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
							<input id="pass_agregar" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) agregar_mesas({empleado: $('#empleado_agregar').val(),pass:$('#pass_agregar').val(), num_mesas:$('#num_mesas').val(), num_comensales:$('#num_comensales').val()})" type="password" class="form-control">
							<span class="input-group-btn">
			        			<button id="btn_agregar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="agregar_mesas({empleado: $('#empleado_agregar').val(),pass:$('#pass_agregar').val(), num_mesas:$('#num_mesas').val(), num_comensales:$('#num_comensales').val()})" class="btn btn-success" type="button">
			        				<i class="fa fa-plus"></i> Agregar
			        			</button>
			        			<button id="btn_finalizar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="recargar({boton: 'btn_finalizar'})" class="btn btn-primary" type="button">
			        				<i class="fa fa-check"></i> Finalizar
			        			</button>
			      			</span>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal agregar-->
	
	<!-- Modal servicio a domicilio -->
		<div id="modal_servicio_domicilio" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg" style="width: 90%">
	    		<div class="modal-content" style="height:750px; overflow: scroll;">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Servicio a domicilio</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Si el <strong>cliente</strong> ya existe, solo buscalo en la lista y pulsa 
					      		<button class="btn btn-success"><i class="fa fa-check"></i></button>. 
					      		Si no, captura sus datos y pulsa <button class="btn btn-success"><i class="fa fa-plus"></i> OK</button>
					    	</p>
					    </blockquote>
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="cliente_servicio_domicilio" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Domicilio:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-home"></i></span>
									<input id="domicilio_servicio_domicilio" type="text" class="form-control">
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-4">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="tel_servicio_domicilio" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Via de contacto:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="via_contacto_domicilio">
										<option value="">-- Ninguna --</option><?php
										foreach ($vias_contacto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
    								<span class="input-group-btn">
							    		<button 
								    		class="btn btn-primary btn-lg"
											data-toggle="modal" 
											data-target="#modal_via_contacto">
							    			<i class="fa fa-plus"></i>
							    		</button>
						    		</span>
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Zona Geografica:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="zona_reparto_domicilio">
										<option value="">-- Ninguna --</option><?php
										foreach ($zonas_reparto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
									<span class="input-group-btn">
							    		<button 
								    		class="btn btn-primary btn-lg"
											data-toggle="modal" 
											data-target="#modal_zona_reparto">
							    			<i class="fa fa-plus"></i>
							    		</button>
						    		</span>
								</div>
					    	</div>
					   	</div>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-xs-6" style="padding-top: 45px" align="right">
								<button
								id="btn_servicio_domicilio"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
								onclick="servicio_domicilio({
											nuevo: 1,
											btn: 'btn_servicio_domicilio',
											nombre: $('#cliente_servicio_domicilio').val(),
											domicilio: $('#domicilio_servicio_domicilio').val(),
											via_contacto: $('#via_contacto_domicilio').val(),
											zona_reparto: $('#zona_reparto').val(),
											tel: $('#tel_servicio_domicilio').val(),
						        			tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
										})"
								class="btn btn-success btn-lg">
									<i class="fa fa-plus"></i> Ok
								</button>
							</div>
					    </div>
	      			</div>
	      			<div class="modal-footer">
						<table id="tabla_servicio_domicilio" class="table table-striped table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><strong><i class="fa fa-user"></i></strong></th>
									<th align="center"><strong><i class="fa fa-home"></i></strong></th>
									<th align="center"><strong><i class="fa fa-phone"></i></strong></th>
									<th align="center"><strong><i class="fa fa-map"></i></strong></th>
									<th align="center"><strong><i class="fa fa-share-alt"></i></strong></th>
									<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
									<th align="center"><strong><i class="fa fa-check"></i></strong></th>
								</tr>
							</thead>
							<tbody><?php
								foreach ($clientes as $key => $value) {
									$value['servicio_domicilio'] = 1; 
									$datos_cliente = json_encode($value);
									$datos_cliente = str_replace('"', "'", $datos_cliente); ?>
									
									<tr id="tr_servicio_domicilio_<?php echo $value['id'] ?>">
										<td id="nom_<?php echo $value['id'];  ?>"><?php echo $value['nombre'] ?></td>
										<td id="dir_<?php echo $value['id'];  ?>"><?php echo $value['direccion'] ?></td>
										<td id="tel_<?php echo $value['id'];  ?>" align="center"><?php echo $value['tel'] ?></td>
										<td id="zon_<?php echo $value['id'];  ?>"><?php echo $value['zona_reparto'] ?></td>
										<td id="via_<?php echo $value['id'];  ?>"><?php echo $value['via_contacto'] ?></td>
										<td align="center">
											<button 
								        		id="btn_edit_<?php echo $value['id'] ?>" 
								        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								        		onclick="comandera.llenar_campos(<?php echo $datos_cliente ?>)"
												data-toggle="modal" 
												data-target="#modal_editar_cliente_domicilio"
								        		class="btn btn-primary">
								        		<i class="fa fa-pencil-square-o"></i>
								        	</button>
										</td>
										<td align="center">
											<button 
								        		id="btn_servicio_domicilio_<?php echo $value['id'] ?>" 
								        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								        		onclick="servicio_domicilio({
								        					nombre:'<?php echo $value['nombre'] ?>',
								        					btn:'btn_servicio_domicilio_<?php echo $value['id'] ?>',
								        					domicilio:'<?php echo $value['direccion'] ?>',
								        					via_contacto:'<?php echo $value['via_contacto'] ?>',
								        					zona_reparto:'<?php echo $value['zona_reparto'] ?>',
								        					tel:'<?php echo $value['tel'] ?>',
						        							tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
								        				})" 
								        		class="btn btn-success">
								        		<i class="fa fa-check"></i>
								        	</button>
										</td>
									</tr><?php
								} ?>
							</tbody>
						</table>
						<script>comandas.convertir_dataTable({id:'tabla_servicio_domicilio'})</script>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal servicio a domicilio -->
	
	<!-- Modal para llevar -->
		<div id="modal_para_llevar" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Para llevar</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Si el <strong>cliente</strong> ya existe, solo buscalo en la lista y pulsa 
					      		<button class="btn btn-success"><i class="fa fa-check"></i></button>. 
					      		Si no, captura sus datos y pulsa <button class="btn btn-success"><i class="fa fa-plus"></i> OK</button>
					    	</p>
					    </blockquote>
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="cliente_para_llevar" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Domicilio:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-home"></i></span>
									<input id="domicilio_para_llevar" type="text" class="form-control">
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-6">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="tel_para_llevar" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-6">
					    		<h3><small>Via de contacto:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="via_contacto">
										<option value="">-- Ninguna --</option><?php
										foreach ($vias_contacto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
    								<span class="input-group-btn">
							    		<button 
								    		class="btn btn-primary btn-lg"
											data-toggle="modal" 
											data-target="#modal_via_contacto">
							    			<i class="fa fa-plus"></i>
							    		</button>
						    		</span>
								</div>
					    	</div>
					    	<div class="row">
					    		<div class="col-md-6"></div>
						    	<div class="col-xs-6" style="padding: 30px" align="right">
						        	<button
						        		id="btn_para_llevar" 
						        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						        		onclick="para_llevar({
						        					nuevo: 1,
						        					btn: 'btn_para_llevar',
						        					tel: $('#tel_para_llevar').val(), 
						        					nombre: $('#cliente_para_llevar').val(), 
						        					via_contacto: $('#via_contacto').val(), 
						        					domicilio: $('#domicilio_para_llevar').val(),
						        					tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
						        				})" 
						        		class="btn btn-success btn-lg" 
						        		type="button">
						        		<i class="fa fa-check"></i> Ok
						        	</button>
						    	</div>
					    	</div>
					    </div>
	      			</div>
	      			<div class="modal-footer">
						<table id="tabla_para_llevar" class="table table-striped table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><strong><i class="fa fa-user"></i></strong></th>
									<th align="center"><strong><i class="fa fa-home"></i></strong></th>
									<th align="center"><strong><i class="fa fa-phone"></i></strong></th>
									
									<th align="center"><strong><i class="fa fa-check"></i></strong></th>
								</tr>
							</thead>
							<tbody><?php
								foreach ($clientes as $key => $value) { 
									$value['para_llevar'] = 1; 
									$datos_cliente = json_encode($value);
									$datos_cliente = str_replace('"', "'", $datos_cliente); ?>
									
									<tr id="tr_para_llevar_<?php echo $value['id'] ?>">
										<td><?php echo $value['nombre'] ?></td>
										<td><?php echo $value['direccion'] ?></td>
										<td align="center"><?php echo $value['tel'] ?></td>
										<td align="center">
											<button 
								        		id="btn_edit_<?php echo $value['id'] ?>" 
								        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								        		onclick="comandera.llenar_campos(<?php echo $datos_cliente ?>)"
												data-toggle="modal" 
												data-target="#modal_editar_para_llevar"
								        		class="btn btn-primary">
								        		<i class="fa fa-pencil-square-o"></i>
								        	</button>
										</td>
										<td align="center">
											<button 
								        		id="btn_para_llevar_<?php echo $value['id'] ?>" 
								        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								        		onclick="para_llevar({
					        								btn: 'btn_para_llevar_<?php echo $value['id'] ?>',
								        					nombre: '<?php echo $value['nombre'] ?>',
								        					domicilio: '<?php echo $value['direccion'] ?>',
						        							via_contacto: '<?php echo $value['via_contacto'] ?>', 
								        					tel: '<?php echo $value['tel'] ?>',
						        							tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
								        				})" 
								        		class="btn btn-success">
								        		<i class="fa fa-check"></i>
								        	</button>
										</td>
									</tr><?php
								} ?>
							</tbody>
						</table>
						<script>comandas.convertir_dataTable({id:'tabla_para_llevar'})</script>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal para llevar -->


	<!-- MODULO REP-->
	  <!-- Modal para Ligar repartidor ch@-->
<div class="modal fade" id="modal_repartidores" role="dialog">
	 <div class="modal-dialog">
	    <div class="modal-content">
	      	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h3>Repartidores</h3>
	      	</div>
	      	<div class="modal-body form">
	          <div class="form-body">
	          	<div>
	          	    <input id = "inpmesa" type="hidden" readonly>
					<input id = "inpcomanda" type="hidden" readonly> 
					<input id = "inpidrep" type="hidden" readonly> 
	          		<label class="control-label">Repartidor:</label> <input class="form-control" id = "inprepartidor" type="text" readonly>
	          	</div>
	          		<div style="height:320px; overflow: scroll;">
						<table id="tabla_servicio_domicilio" class="table table-striped table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><strong><i class="fa fa-user"></i></strong></th>
									<th align="center"><strong><i class="fa fa-user"></i></strong></th>
									<th align="center"><strong><i class="fa fa-check"></i></strong></th>
								</tr>
							</thead>
							<tbody><?php /// clinetes sera cambiado por reportidores disponibles
							//echo json_encode($empleados);
								foreach ($repartidores as $key => $value) { ?>
									<tr id="tr_servicio_domicilio_<?php echo $value['id'] ?>">
										<td><?php echo $value['id'] ?></td>
										<td><?php echo $value['usuario'] ?></td>
										<td align="center">
											<button  
								        		onclick="asignar_repartidor(<?php echo $value['id']?>,'<?php echo $value['usuario']?>','<?php  $time = time();  echo date("d-m-Y H:i:s", $time); ?>') " 
								        		>
								        		<i class="fa fa-check"></i>
								        	</button>
										</td>
									</tr><?php
								} ?>
							</tbody>
						</table>
					</div>
	          </div>

	        </div>
	        <div class="modal-footer">
	            <button type="button" id="btnSave" onclick="asignar()" class="btn btn-primary">Asignar</button>
	            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

	 <!-- MODULO REP FIN-->    	
	 
	<!-- Modal via de contacto -->
		<div id="modal_via_contacto" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Agregar via de contacto</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Introduce el <strong>nombre</strong> de la nueva via de contacto y pulsa 
					      		<button class="btn btn-success"><i class="fa fa-check"></i> Ok</button> o <strong>enter</strong>
					    	</p>
					    </blockquote>
	      				<h3><small>Nombre:</small></h3>
	        			<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-font"></i></span>
							<input id="nombre_via_contacto" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.agregar_via_contacto({nombre: $('#nombre_via_contacto').val(), btn: 'btn_via_contacto'})" type="text" class="form-control">
							<span class="input-group-btn">
					      		<button
					      			id="btn_via_contacto"
					      			class="btn btn-success btn-lg" 
					      			data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					      			onclick="comandas.agregar_via_contacto({nombre: $('#nombre_via_contacto').val(), btn: 'btn_via_contacto'})">
					      			<i class="fa fa-check"></i> Ok
					      		</button>
			      			</span>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal modal_via_contacto -->	

	<!-- Modal zona de reparto -->
		<div id="modal_zona_reparto" class="modal fade" role="dialog">
	 		<div class="modal-dialog">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Agregar zona de reparto</h4>
	      			</div>
	      			<div class="modal-body">
						<blockquote style="font-size: 14px">
					    	<p>
					      		Introduce la <strong>Zona</strong> de reparto y pulsa 
					      		<button class="btn btn-success"><i class="fa fa-check"></i> Ok</button> o <strong>enter</strong>
					    	</p>
					    </blockquote>
	      				<h3><small>Nombre:</small></h3>
	        			<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-font"></i></span>
							<input id="nombre_zona_reparto" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.agregar_zona_reparto({nombre: $('#nombre_zona_reparto').val(), btn: 'btn_zona_reparto'})" type="text" class="form-control">
							<span class="input-group-btn">
					      		<button
					      			id="btn_zona_reparto"
					      			class="btn btn-success btn-lg" 
					      			data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					      			onclick="comandas.agregar_zona_reparto({nombre: $('#nombre_zona_reparto').val(), btn: 'btn_zona_reparto'})">
					      			<i class="fa fa-check"></i> Ok
					      		</button>
			      			</span>
						</div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal zona de reparto -->	

	<!-- Modal comandera -->
		<div id="modal_comandera" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 95%">
	    		<div class="modal-content">
	      			<div class="modal-header">
	      				<div class="row">
	      					<div class="col-md-3" style="padding-top: 5px">
			        			<h4>
			        				<i class="fa fa-cutlery"></i> <i id="comanda_text"> xxxx</i> / 
			        				<i class="fa fa-object-group"></i> <i id="mesa_text"> Nombre de mesa</i>
			        				<div style="width: 100px; float: left">
										<i class="fa fa-user"></i> 
										<input 
											type="number" 
											min="1"
											id="num_comensales_comandera"
											onchange="comandera.guardar_comensales({
												comensales: $(this).val(),
												comanda: comandera['datos_mesa_comanda']['id_comanda']
											})"
											style="width: 50px" 
											align="center" />
									</div>
			        			</h4>
	      					</div>
	      					<div class="col-md-9" id="contenedor_funciones_comandera" align="right">
	      						<!-- En esta div se cargan las funciones de la comandera -->
	      					</div>
	      				</div>
	      			</div>
	      			<div class="modal-body" id="div_comandera">
	      				<!-- En esta div se carga la comandera -->
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal comandera -->
	
	<!-- Modal editar cliente domicilio -->
		<div id="modal_editar_cliente_domicilio" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 95%">
	    		<div class="modal-content">
	      			<div class="modal-header">
	      				<div class="row">
	      					<div class="col-md-3" style="padding-top: 5px">
			        			<h4>
			        				Editar cliente: 
									<input type="number" min="1" id="id_cliente_domicilio" style="width: 50px" align="center" readonly="1"/>
			        			</h4>
	      					</div>
	      				</div>
	      			</div>
	      			<div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="editar_cliente_servicio_domicilio" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Domicilio:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-home"></i></span>
									<input id="editar_domicilio_servicio_domicilio" type="text" class="form-control">
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-4">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="editar_tel_servicio_domicilio" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Via de contacto:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="editar_via_contacto_domicilio">
										<option value="">-- Ninguna --</option><?php
										foreach ($vias_contacto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Zona Geografica:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="editar_zona_reparto_domicilio">
										<option value="">-- Ninguna --</option><?php
										foreach ($zonas_reparto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
								</div>
					    	</div>
					   	</div>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-xs-6" style="padding-top: 45px" align="right">
								<button
								id="editar_btn_servicio_domicilio"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								onclick="servicio_domicilio({
											btn: 'editar_btn_servicio_domicilio',
											nombre: $('#editar_cliente_servicio_domicilio').val(),
											domicilio: $('#editar_domicilio_servicio_domicilio').val(),
											via_contacto: $('#editar_via_contacto_domicilio').val(),
											zona_reparto: $('#editar_zona_reparto').val(),
											tel: $('#editar_tel_servicio_domicilio').val(),
											servicio_domicilio: 1,
						        			tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
									})"
								class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i> Ok
								</button>
							</div>
					    </div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal editar cliente domicilio -->
	
	<!-- Modal editar para llevar -->
		<div id="modal_editar_para_llevar" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 95%">
	    		<div class="modal-content">
	      			<div class="modal-header">
	      				<div class="row">
	      					<div class="col-md-3" style="padding-top: 5px">
			        			<h4>
			        				Editar cliente
			        			</h4>
	      					</div>
	      				</div>
	      			</div>
	      			<div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="editar_cliente_para_llevar" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Domicilio:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-home"></i></span>
									<input id="editar_domicilio_para_llevar" type="text" class="form-control">
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-4">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="editar_tel_para_llevar" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Via de contacto:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="editar_via_contacto_para_llevar">
										<option value="">-- Ninguna --</option><?php
										foreach ($vias_contacto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Zona Geografica:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="editar_zona_reparto_para_llevar">
										<option value="">-- Ninguna --</option><?php
										foreach ($zonas_reparto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
								</div>
					    	</div>
					   	</div>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-xs-6" style="padding-top: 45px" align="right">
								<button
								id="editar_btn_servicio_domicilio"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								onclick="para_llevar({
											btn: 'editar_btn_servicio_domicilio',
											nombre: $('#editar_cliente_para_llevar').val(),
											domicilio: $('#editar_domicilio_para_llevar').val(),
											via_contacto: $('#editar_via_contacto_para_llevar').val(),
											zona_reparto: $('#editar_zona_reparto_para_llevar').val(),
											tel: $('#editar_tel_para_llevar').val(),
											para_llevar: 1,
						        			tipo_operacion: <?php echo $configuracion['tipo_operacion'] ?>
									})"
								class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i> Ok
								</button>
							</div>
					    </div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal editar cliente domicilio -->
	
	<!-- modal_reiniciar -->
		<div 
			class="modal fade"
			keyboard="false" 
			data-backdrop="static" 
			id="modal_reiniciar" 
			tabindex="-1" 
			role="dialog" 
			aria-labelledby="titulo_reiniciar">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
	      		<div class="modal-header">
	       			<h4 class="modal-title">Continuar</h4>
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
	<?php  
	/* para editar cliente
	<!-- Modal eidtar cliente SD -->
		<div id="modal_edit_cliente" class="modal fade" role="dialog">
	 		<div class="modal-dialog modal-lg">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button id="btn_cerrar_agregar" type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Editar Cliente</h4>
	      			</div>
	      			<div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-6">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="idClienteE" type="hidden">
									<input id="cliente_servicio_domicilioE" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-6">
			      				<h3><small>Domicilio:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-home"></i></span>
									<input id="domicilio_servicio_domicilioE" type="text" class="form-control">
								</div>
					    	</div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-4">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="tel_servicio_domicilioE" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Via de contacto:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="via_contacto_domicilioE">
										<option value="">-- Ninguna --</option><?php
										foreach ($vias_contacto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
    								<span class="input-group-btn">
							    		<button 
								    		class="btn btn-primary btn-lg"
											data-toggle="modal" 
											data-target="#modal_via_contacto">
							    			<i class="fa fa-plus"></i>
							    		</button>
						    		</span>
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Zona Geografica:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-address-book-o"></i></span>
									<select class="selectpicker" data-width="20%" id="zona_reparto_domicilioE">
										<option value="">-- Ninguna --</option><?php
										foreach ($zonas_reparto as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
										} ?>
									</select>
									<span class="input-group-btn">
							    		<button 
								    		class="btn btn-primary btn-lg"
											data-toggle="modal" 
											data-target="#modal_zona_reparto">
							    			<i class="fa fa-plus"></i>
							    		</button>
						    		</span>
								</div>
					    	</div>
					   	</div>
	      			</div>
	      			<div class="modal-footer">
			            <button type="button" class="btn btn-danger" data-dismiss="modal" data-target="#modal_servicio_domicilio">Cerrar</button>
			            <button type="button" class="btn btn-primary" onclick="editCliente()">Editar</button>
			         </div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal eidtar cliente SD -->
	*/
	?>

	</body>
</html>

<script type="text/javascript">
	
/*
	function editCliente(){
		$('#modal_edit_cliente').modal('hide');
		var idCliente = $("idClienteE").val();
		var cliente_servicio_domicilioE = $("cliente_servicio_domicilioE").val();
		var domicilio_servicio_domicilioE = $("domicilio_servicio_domicilioE").val();
		var tel_servicio_domicilioE = $("tel_servicio_domicilioE").val();
		var via_contacto_domicilioE = $("via_contacto_domicilioE").val();
		var zona_reparto_domicilioE = $("zona_reparto_domicilioE").val();		
	}
*/
	var serialize_widget_map = function (items) {
		console.log('---------> Items');
		console.log(items);
		
	// Guarda sus cordenadas
	    $.each(items, function(index, value){
			guardar_cordenadas({id:value['el'][0]['id'], x:value['x'], y:value['y']});
		});
	    
	};

//Guarda la posicion de las mesas al cambiar
	$('.grid-stack').on('change', function (e, items) {
	    serialize_widget_map(items);
	});

// Convierte los id con draggable en divs ue se pueden arrastrar
	convertir_draggable(<?php echo json_encode($_SESSION['tables']) ?>);
	
// Carga la vista de la comandera despues de medio segundo
	setTimeout(function() {
		comandera.vista_comandera({div: 'div_comandera'});
	}, 500);

// Agrega el grid de las mesas al cuerpo del acordion
	// $("#contenedor_domicilio").appendTo("#panel_domicilio");
</script>
<div id="div_ejecutar_scripts" style="display: none">
	<!-- en esta div se ejecutan los scripts mediante la carga de contenido html -->
</div>