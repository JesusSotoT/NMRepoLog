var pedidos = {
	contScroll : 0,
	scrollMaximo : 0,
	saltosScroll : 20,
	tipo : 0,
	tiempoRefresh : 5,  //Tiempo que tarda el disparar la funcion de buscar pedidos
	interval : '',
	intervalMinus : 0,
	autoPrint : false,
	request : null,
	lugar : '',
	scrollL : 0,
	vista_listado : 1,
	pedido_seleccionado : {},
	todasAreas : {},
	init : function() {
	//Obtenemos el scroll maximo para evitar que se sobrepase.
		window.onresize = function() {
			pedidos.getScrollMaxY();
		};

	//Aqui agregamos el evento click a los botones evitando que con firebug o similares puedan inspeccionar que funcion se manda llamar
		$('#flechaAbajo').unbind('click').bind('click', function() {
			pedidos.scrollAbajo();
		});

		$('#flechaArriba').unbind('click').bind('click', function() {
			pedidos.scrollArriba();
		});

		$('.terminarProducto').unbind('click').bind('click', function() {
			pedidos.terminarProducto(this);
		});

		$('.eliminarProducto').unbind('click').bind('click', function() {
			pedidos.cancelarProductoDialog(this);
		});

		$('#btnCancelDialog').unbind('click').bind('click', function(event) {
			$("#dialog").dialog("close");
		});

	//se oculta el menu lateral de netwarlog
		if ($('#tdocultar')) {
			$('#tdocultar').click();
		}

	},
	zona : function() {
		
		// TODAS LAS AREAS
		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=todasAreas',
			type : 'POST',
			dataType : 'json',
			async:false,
		}).done(function(resp) {			
			pedidos.todasAreas = resp;			
		});
		console.log('AREAS');
		console.log(pedidos.todasAreas);
		// TODAS LAS AREAS FIN

		//Esta funcion consulta las comandas dependiendo de la zona en la que se encuentran.
		$('.div-btn').bind('click', function() {
			var id = $(this).attr('id');
			pedidos.lugar = $(this).attr('lugar');

			var $objeto = [];
			$objeto['id'] = id;
			$objeto['lugar'] = pedidos.lugar;

			console.log('-----> objeto zona');
			console.log($objeto);

			$('#divPedidos_Comandas').empty();

			$('#autoPrint').dialog({
				buttons : [{
					text : "SI",
					class : 'btn btn-success col-sm-5',
					click : function() {
						pedidos.autoPrint = true;
						pedidos.comandas($objeto);
						$(".ped-conts").show();
						// pedidos.timer(0);
						$(this).dialog("close");
					}
				}, {
					text : "NO",
					class : 'btn btn-danger col-sm-5 pull-right',
					click : function() {
						pedidos.autoPrint = false;
						pedidos.comandas($objeto);
						$(".ped-conts").show();
						// pedidos.timer(0);
						$(this).dialog("close");
					}
				}]
			});
		});
	},
	comandas : function($objeto) {

		//// MODULO PRINTVALIDACION
		var moduloPrint = 0;
		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=moduloPrint',
			type : 'POST',
			dataType : 'html',
			async:false,
		}).done(function(resp) {							
			moduloPrint = resp;
		});
		//// MODULO PRINT VALIDACION FIN

		console.log('-----> objeto comandas');
		console.log($objeto);
		pedidos.tipo = $objeto['id'];
		pedidos.scrollL = $('#div_pend').scrollLeft();
	// Valida si se deben de imprimir los ticket o no
		if (pedidos.autoPrint) {
			var $ticket = 1;
		}

		// console.log('-----> Antes ajax comandas');
		// console.log('Tipo: ' + $objeto['id'] + ' 	ticket' + $ticket);
		// CH
		//moduloPrint = 1;
		//pedidos.todasAreas = [{"id":"3","lugar":"Barra"},{"id":"4","lugar":"Cocina"},{"id":"5","lugar":"Cereales"},{"id":"6","lugar":"Limpieza"}];
		if(moduloPrint == 1){
			for (var i = 0; i <= pedidos.todasAreas.length - 1; i++) {
				$.ajax({
						url : 'ajax.php?c=pedidosActivos&f=ver',
						type : 'POST',
						dataType : 'json',
						async:false,
						data : {
							'tipo' : pedidos.todasAreas[i].id,
							'ticket' : $ticket,
							'vista_listado' : pedidos.vista_listado,
							'moduloPrint':1
						},
					}).done(function(resp) {
						
						//Modulo de impresion
						datos = resp;
						if(datos['ticket'] == 1 && (datos['pedidos_nuevos'] != null && datos['pedidos_nuevos'] !="" )){
		  	 				pedidos.imprimir('', datos['pedidos_nuevos'], pedidos.todasAreas[i].lugar,1);
		  				}
		  				//Modulo de impresion fin
							if(pedidos.lugar == pedidos.todasAreas[i].lugar){
								$.ajax({
									url : 'ajax.php?c=pedidosActivos&f=ver2',
									type : 'POST',
									dataType : 'html',
									async:false,
									data:datos,
								}).done(function(resp) {							
									// Oculta la div de zonas
									$('.zona').hide('slow');

									// Muestra la div de los pedidos y agrega los pedidos
									$('#contenedor').show('slow');
									$('#titulo').html(pedidos.lugar);
									$('#div_pendientes').html(resp);
									
									// Crea el datatable si es un listado
									if(pedidos.vista_listado == 1){
										pedidos.convertir_dataTable({id:'tabla_listado_pedientes'});
									}
								});			  			
			  				}
						
					}).fail(function(resp) {
						console.log('-----> fail comandas');
						console.log(resp);
					});
			}
		}else{
			$.ajax({
				url : 'ajax.php?c=pedidosActivos&f=ver',
				type : 'POST',
				dataType : 'html',
				data : {
					'tipo' : $objeto['id'],
					'ticket' : $ticket,
					'vista_listado' : pedidos.vista_listado,
					'moduloPrint':0
				},
			}).done(function(resp) {

			// Oculta la div de zonas
				$('.zona').hide('slow');

			// Muestra la div de los pedidos y agrega los pedidos
				$('#contenedor').show('slow');
				$('#titulo').html(pedidos.lugar);
				$('#div_pendientes').html(resp);
				
			// Crea el datatable si es un listado
				if(pedidos.vista_listado == 1){
					pedidos.convertir_dataTable({id:'tabla_listado_pedientes'});
				}
			}).fail(function(resp) {
				console.log('-----> fail comandas');
				console.log(resp);
			});

		}
		
		// CH FIN

		
	},
	getScrollMaxY : function() {
	//alert('a');
		pedidos.scrollMaximo = ('scrollMaxY' in window) ? window.scrollMaxY : (document.documentElement.scrollHeight - document.documentElement.clientHeight);
		$(window.document).scrollTo(0);
	},
	scrollAbajo : function() {
		if (pedidos.scrollMaximo == 0) {
			pedidos.getScrollMaxY();
		}

		pedidos.contScroll = parseInt(pedidos.contScroll) + parseInt(pedidos.saltosScroll);

		if (pedidos.contScroll > pedidos.scrollMaximo) {
			pedidos.contScroll = pedidos.scrollMaximo;
			$(window.document).scrollTo(pedidos.scrollMaximo);
		} else {
			$(window.document).scrollTo(pedidos.contScroll);
		}

	},
	scrollArriba : function() {

		pedidos.contScroll = pedidos.contScroll - pedidos.saltosScroll;

		if (pedidos.contScroll < 0) {
			pedidos.contScroll = 0;
			$(window.document).scrollTo(0);
		} else {
			$(window.document).scrollTo(pedidos.contScroll);
		}
	},
	change_view : function($btn) {
		if($btn == 1) {
			$(".btn-view").attr('style', 'color: #714789; font-size:20px; background-color: white; border: solid;');
			$("#btn-pen").attr('style', 'color: white; background-color: #714789; font-size: 20px;');
			$("#div_pendientes").show();
			$("#div_terminados").hide();
			$("#div_eliminados").hide();
		} else if($btn == 2) {
			$(".btn-view").attr('style', 'color: #714789; font-size:20px; background-color: white; border: solid;');
			$("#btn-ter").attr('style', 'color: white; background-color: #714789; font-size: 20px;');
			$("#div_pendientes").hide();
			$("#div_terminados").show();
			$("#div_eliminados").hide();
		} else if($btn == 3) {
			$(".btn-view").attr('style', 'color: #714789; font-size:20px; background-color: white; border: solid;');
			$("#btn-can").attr('style', 'color: white; background-color: #714789; font-size: 20px;');
			$("#div_pendientes").hide();
			$("#div_terminados").hide();
			$("#div_eliminados").show();
		} 
	},
	cancelarProductoDialog : function(element) {
		$("#dialog").dialog();

		$('#btnAceptarDialog').unbind('click').bind('click', function(event) {
			pedidos.cancelarProducto(element);
		});
	},
	///////////////// ******** ----                 mover_scroll                        ------ ************ //////////////////
//////// Mueve el scroll de una div
    // Como parametros recibe:
        // direccion -> Izquierda, derecha, arriba, abajo
        // div -> Div del scroll
        // cantidad -> Cantidad ed pixeles a mover
        
    mover_scroll : function($objeto) {
        console.log('=========> objeto mover_scroll');
        console.log($objeto);
        
        var $cantidad = (!$objeto['cantidad']) ? 200 : $objeto['cantidad'];
        var posicion = $('#' + $objeto['div']).scrollLeft();
        
        console.log('=========> posicion --- Cantidad');
        console.log(posicion+'---'+$cantidad);
            
    // Anima de izquierda a derecha
        if ($objeto['direccion'] == 'izquierda') {
            $('#' + $objeto['div']).animate({
                scrollLeft : posicion - $cantidad
            }, 400);
        }
       
    // Anima de derecha a izquierda
        if ($objeto['direccion'] == 'derecha') {
            $('#' + $objeto['div']).animate({
                scrollLeft : posicion + $cantidad
            }, 400);
        }
        
    // Anima de arriba a abajo
        if ($objeto['direccion'] == 'abajo') {
            $('#' + $objeto['div']).animate({
                scrollTop : posicion - $cantidad
            }, 400);
        }
       
    // Anima de abajo a arriba
        if ($objeto['direccion'] == 'arriba') {
            $('#' + $objeto['div']).animate({
                scrollTop : posicion + $cantidad
            }, 400);
        }
        
        
    },
    
///////////////// ******** ----                 FIN mover_scroll                    ------ ************ //////////////////
	cancelarProducto : function(element) {
		console.log('----------> Objeto cancelarProducto');
		console.log(element);

		$("#dialog").dialog("close");

		var id = $(element).attr('id');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=eliminarProducto',
			type : 'POST',
			dataType : 'json',
			data : {
				'id' : id
			},
		}).done(function(data) {
			console.log('----------> Response cancelarProducto');
			console.log(data);

			if (data) {
				var comanda = id.replace("p", '');
				comanda = comanda.split('-');

				$('#' + id).hide('slow', function() {
					var parent = $(this).parent();
					$(this).remove();
					if ($(parent).html() == '') {
						$('#C' + comanda[0]).click();
						$(parent).parent().remove();
						// Remueve el div que contiene los pedidos de la comanda
						$('#comanda' + comanda[0]).remove();
					}
				});

				if ($('#comanda' + comanda[0] + ' > .pedido').html() === undefined) {
					$('#comanda' + comanda[0]).remove();
				}
			}
		});
	},
	terminarProducto : function(element) {
		var id = $(element).attr('id');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=productoTerminado',
			type : 'POST',
			dataType : 'json',
			data : {
				'id' : id
			},
		}).done(function(data) {
			console.log(data);

			if (data) {
				$('#' + id).hide('slow', function() {
					var parent = $(this).parent();

					$(this).remove();

					if ($(parent).html() == '') {
						$(parent).parent().remove();
					}

					var comanda = id.replace("p", '');
					comanda = comanda.split('-');

					if ($('#comanda' + comanda[0] + ' > .pedido').html() === undefined) {
						$('#T' + comanda[0]).click();
						$('#comanda' + comanda[0]).remove();
					}
				});
			} else {
				alert('Algo salio mal :(');
			}
		});

	},
	cancelarPedido : function(element) {
		var id = $(element).attr('id');

		$("#dialog2").dialog();

		$('#btnAceptarElimino').click(function(event) {
			$("#dialog2").dialog('close');

			var idcomanda = id.replace("C", '');
			$('#comanda' + idcomanda).hide('slow', function() {
				this.remove();
			});

		});
	},
	terminarPedido : function(element) {
		var id = $(element).attr('id');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=terminarComanda',
			type : 'POST',
			dataType : 'json',
			data : {
				'id' : id
			},
		}).done(function(data) {
			if (data) {
				var idcomanda = id.replace("T", '');
				$('#comanda' + idcomanda).hide('slow', function() {
					this.remove();
				});

				var $objeto = [];
				$objeto['id'] = pedidos.tipo;

				pedidos.comandas($objeto);
			}
		});
	},
	imprimir : function(organizacion, data, area, moduloPrint) {
		var isMobile = {
			mobilecheck : function() {
			return (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i.test(navigator.userAgent||navigator.vendor||window.opera)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test((navigator.userAgent||navigator.vendor||window.opera).substr(0,4)));
			}
		};
		var contProducto = 0;
			var persona = 0;
			var tipo = '';
			var jsonPedido = [];
			jsonPedido.push({'logo' : '', 'codigo' : '', 'qr' : '', 'tipo' : 1, 'type' : ''});

			$.each(data, function(index, val) {

				if(val.tipo == 1){
					tipo = 'Para Llevar';
				}else if(val.tipo == 2){
					tipo = 'A domicilio';
				}
				jsonPedido = formatearTicket(jsonPedido, '', 0,0);
				jsonPedido = formatearTicket(jsonPedido, '', 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Comanda: ' + val["comanda"], 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Hora -> ' + val["inicioPedido"], 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Mesa -> ' + val["nombre_mesa"], 0,0);
				
				if(val["tipo"] == 1 || val["tipo"] == 2){
					jsonPedido = formatearTicket(jsonPedido, 'Tipo: ' + tipo, 0,0);
				}

				if(val["domicilio"]){
					jsonPedido = formatearTicket(jsonPedido, 'Domicilio -> ' + val["domicilio"], 0,0);
				}
				
				if(val["tel"]){
					jsonPedido = formatearTicket(jsonPedido, 'Tel -> ' + val["tel"], 0,0);
				}
				jsonPedido = formatearTicket(jsonPedido, '', 0,0);
				jsonPedido = formatearTicket(jsonPedido, '', 0,0);

				$.each(val["persona"], function(index, value) {
					persona = (index);
					jsonPedido = formatearTicket(jsonPedido, 'Orden ' + persona, 0,0);
					jsonPedido = formatearTicket(jsonPedido, '', 0,0);
					
					var $tiempo_platillo = 0;
					
					$.each(value.productos, function(index, producto) {
						contProducto = (index + 1);
						
					// Muestra en que tiempo se debe servir el platilo
						if(producto["tiempo_platillo"] != $tiempo_platillo){
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
							jsonPedido = formatearTicket(jsonPedido, '============> Tiempo '+ producto["tiempo_platillo"], 0,0);
							
							
							$tiempo_platillo = producto["tiempo_platillo"];
						}
						jsonPedido = formatearTicket(jsonPedido, producto["cantidad"] + 'x ' + producto["descripcion"], 0,0);
						jsonPedido = formatearTicket(jsonPedido, '', 0,0);
						// Opcionales
						if (!isEmptyF(producto["opcionales"])) {
							jsonPedido = formatearTicket(jsonPedido, producto["opcionalesDesc"], 0,0);
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
							
						}

					// Extra
						
						if (!isEmptyF(producto["adicionalesDesc"])) {
							jsonPedido = formatearTicket(jsonPedido, producto["adicionalesDesc"], 0,0);
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
						}
					
					// Kit
						if (!isEmptyF(producto["desc_kit"])) {
							jsonPedido = formatearTicket(jsonPedido, producto["desc_kit"], 0,0);
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
							
						}

					// Sin
						if (!isEmptyF(producto["sin"]) || !isEmptyF(producto["sin_desc"])) {
							jsonPedido = formatearTicket(jsonPedido, producto["sin_desc"], 0,0);
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
							
						}

					// Nota sin
						if (!isEmptyF(producto["nota_sin"])) {
							jsonPedido = formatearTicket(jsonPedido, producto["nota_sin"], 0,0);
							jsonPedido = formatearTicket(jsonPedido, '', 0,0);
							
						}
					});
					jsonPedido = formatearTicket(jsonPedido, '', 0,0);
					jsonPedido = formatearTicket(jsonPedido, '', 0,0);
				});
			});
		if(isMobile.mobilecheck()){
			var jsV = JSON.stringify(jsonPedido);
			jsV = jsV.replace(/#/g, '');
			console.log(jsV);
			var navegador = (navigator.userAgent.indexOf('Firefox') != -1) ? 1 : ((navigator.userAgent.indexOf("Chrome") != -1) ? 2 : 0);
			var pestana = window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';S.navegador='+ navegador +';end'); 
			pestana.close();
		} else {

			if(moduloPrint == 1){

				var segundo = 0;
	            var impresionTexto = ""; 

	            $.each(jsonPedido, function(index, element){
	                if(segundo == 0){
	                    segundo = 1;
	                }else{
	                    impresionTexto = impresionTexto + element.texto + "\n";
	                }
	            });

	            $.ajax({
	                url : 'ajax.php?c=impresion&f=insertar',
	                type: 'POST',
	                dataType: 'json',
	                data: { area : area, ticket : impresionTexto, codigo : ""},
	            })
	            .done(function(resp) {

	            })
	            .fail(function() {
	                console.log("error");
	            })
	            .always(function() {
	                console.log("complete");
	            });

			}else{

				var altura = 290;
				var mm = 15.118110236;
				var contProducto = 0;
				var persona = 0;
				var tipo = '';

				var html = '<div style="text-align:left;font-size:14px;">';
				
				$.each(data, function(index, val) {

					if(val.tipo == 1){
						tipo = 'Para Llevar';
					}else if(val.tipo == 2){
						tipo = 'A domicilio';
					}

					html += organizacion + '</br></br>';
					html += 'Comanda # ' + val["comanda"] + '</br>';
					html += 'Hora -> ' + val["inicioPedido"] + '</br>';
					html += 'Mesa -> ' + val["nombre_mesa"] + '</br>';

					if(val["tipo"] == 1 || val["tipo"] == 2){
						html += 'Tipo: ' + tipo + '</br>';
					}
					
					if(val["domicilio"]){
						html += 'Domicilio -> ' + val["domicilio"] + '</br>';
					}
					
					if(val["tel"]){
						html += 'Tel -> ' + val["tel"] + '</br>';
					}
					
					html += '</br></br></br>';

					$.each(val["persona"], function(index, value) {
						persona = (index);
						html += 'Orden: ' + persona + '</br>';
						html += '</br>';
						
						var $tiempo_platillo = 0;
						
						$.each(value.productos, function(index, producto) {
						// Muestra en que tiempo se debe servir el platilo
							if(producto["tiempo_platillo"] != $tiempo_platillo){
								html += '</br><strong>============> Tiempo '+ producto["tiempo_platillo"] + '</strong>';
								
								$tiempo_platillo = producto["tiempo_platillo"];
							}
							
							contProducto = (index + 1);

							/*
							if(producto["notap"] != ''){
								notap = '['+producto["notap"]+']';
							}else{
								notap = '';
							}
							*/

							html += '</br><strong>'+ producto["cantidad"] + '</strong>'+'x ' +producto["descripcion"]  + '</br>   ' +producto["notap"]+ '</br>';

						// Opcionales
							if (producto["opcionales"] != '' || producto["opcionalesDesc"]) {
								var caracterContar = ',';
								var numeroApariciones = (producto["opcionalesDesc"].length - producto["opcionalesDesc"].replace(caracterContar, "").length) / caracterContar.length;

								html += '( <label style="color:red">' + producto["opcionalesDesc"] + '</label> )</br>';
								altura += (mm * numeroApariciones);
							}

						// Extra
							if (producto["adicionales"] != '' || producto["adicionalesDesc"]) {
								var caracterContar = ',';
								var numeroApariciones = (producto["adicionalesDesc"].length - producto["adicionalesDesc"].replace(caracterContar, "").length) / caracterContar.length;

								html += '( <label style="color:red">' + producto["adicionalesDesc"] + '</label> )</br>';
								altura += (mm * numeroApariciones);
							}
						
						// Kit
							if (producto["desc_kit"] != '') {
								var caracterContar = ',';
								var numeroApariciones = (producto["desc_kit"].length - producto["desc_kit"].replace(caracterContar, "").length) / caracterContar.length;

								html += '( <label style="color:red">' + producto["desc_kit"] + '</label> )</br>';
								altura += (mm * numeroApariciones);
							}

						// Sin
							if (producto["sin"] || producto["sin_desc"]) {
								var caracterContar = ',';
								var numeroApariciones = (producto["sin_desc"].length - producto["sin_desc"].replace(caracterContar, "").length) / caracterContar.length;

								html += '( <label style="color:red">' + producto["sin_desc"] + '</label> )</br>';
								altura += (mm * numeroApariciones);
							}

						// Nota sin
							if (producto["nota_sin"]) {
								var caracterContar = ',';
								var numeroApariciones = (producto["nota_sin"].length - producto["nota_sin"].replace(caracterContar, "").length) / caracterContar.length;
								html += '( <label style="color:red">' + producto["nota_sin"] + '</label> )</br>';
								altura += (mm * numeroApariciones);
							}

							altura += mm;
							Math.round(altura);
						});
						html += '</br></br>';
					});
				});

				html += '</div>';

				var ventana = window.open('', '_blank', 'width=207.874015748,height=' + altura + ',leftmargin=0');

				//abrimos una ventana vacía nueva
				ventana.document.write(html);

				//imprimimos el HTML del objeto en la nueva ventana
				ventana.document.close();

				//cerramos el documento
				ventana.print();

				//imprimimos la ventana
				ventana.close();
				
			}

		}
	},
	autocompleteProductos : function() {
		$('#txtPropina').autocomplete({
			minLength : 2,
			source : function(request, response) {
				pedidos.request = $.ajax({
					url : 'ajax.php?c=pedidosActivos&f=serachPropina',
					type : 'GET',
					dataType : 'json',
					data : {
						'term' : request.term
					},
					beforeSend : function(xhr) {
						if (pedidos.request != null) {
							pedidos.request.abort();
							pedidos.request = null;
						}
					},
					success : function(data) {
						response($.map(data, function(item) {
							return {
								label : item.label,
								value : item.value
							};
						}));
					}
				});
			},
			select : function(event, ui) {
				console.log(ui.item.value);
			},
		});
	},
	asignaridPropina : function() {
		var idPropina = $('#txtPropina').val();

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=addidPropina',
			type : 'POST',
			dataType : 'json',
			data : {
				'id' : idPropina
			},
		}).done(function(data) {
			if (data) {
				alert('Se asigno el producto.');
			}
		}).fail(function() {
			console.log("error");
		});

	},

///////////////// ******** ---- 		mostrar_propina			------ ************ //////////////////
//////// Guarda el estatus del checkbox para mostrar o no la propina
	// Como parametros recibe:
		// mostrar: valor del Checkbox

	mostrar_propina : function($objeto) {
		console.log('------> objeto mostrar propina');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=pedidosActivos&f=mostrar_propina',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('------> done mostrar propina');
			console.log(resp);

			// Error: Manda un mensaje con el error
			if (resp) {
				var $mensaje = 'Configuracion guardada';
				$('#text_propina').notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			}
		}).fail(function(resp) {
			console.log('------> fail mostrar propina');
			console.log(resp);

			// Error: Manda un mensaje con el error
			var $mensaje = 'Error al configurar la propina';
			$('#text_propina').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 		FIN mostrar_propina		------ ************ //////////////////

///////////////// ******** ---- 		mostrar_consumo			------ ************ //////////////////
//////// Guarda el estatus del checkbox para mostrar o no el consumo en la facturacion
	// Como parametros recibe:
		// mostrar: valor del Checkbox

	mostrar_consumo : function($objeto) {
		console.log('------> objeto mostrar propina');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=pedidosActivos&f=mostrar_consumo',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('------> done mostrar_consumo');
			console.log(resp);

		// Error: Manda un mensaje con el error
			if (resp) {
				var $mensaje = 'Configuracion guardada';
				$('#text_consumo').notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success'
				});
			}
		}).fail(function(resp) {
			console.log('------> fail mostrar_consumo');
			console.log(resp);

		// Error: Manda un mensaje con el error
			var $mensaje = 'Error al configurar el consumo';
			$('#text_consumo').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error'
			});
		});
	},

///////////////// ******** ---- 		FIN mostrar_propina		------ ************ //////////////////
	terminartodo : function($comanda){
		$("#ped-"+$comanda+" .btn-loader").each(function (index) 
        { 
            $(this).trigger('click');
        });
	},
	reactivartodo : function($comanda){
		console.log("#react_"+$comanda);
		$("#ter-"+$comanda+" .btn-loader").each(function (index) 
        { 
            $(this).trigger('click');
        });
	},
	reactivartodo_can : function($comanda){
		console.log("#react_"+$comanda);
		$("#elim-"+$comanda+" .btn-loader").each(function (index) 
        { 
            $(this).trigger('click');
        });
	},
///////////////// ******** ---- 		listar_ajustes		------ ************ //////////////////	
//////// Optiene los ajustes. Y cambia los inputs segun estos
	// Como parametros recibe:

	listar_ajustes : function($objeto) {
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=pedidosActivos&f=listar_ajustes',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-----> done listar_ajustes');
			console.log(resp);
		
		// Llena los campos value
			$.each(resp, function(index, value) {
				if(value != 1 || index=='time_rojo' || index=='time_amarillo' || index=='mostrar_opciones_menu'){
					//console.log(index+": "+value);
					$("#" + index).val(value);
					if(index=='mostrar_opciones_menu'){
						$.each(value.split(","), function(i,e){
						    $("#mostrar_opciones_menu option[value='" + e + "']").prop("selected", true);
						});
					}
				}
		
			});
			
					//Marca el checkbox de consumo si se debe mostrar la propina.
			if(resp['consumo'] == 1) {

				$("#check_consumo").prop("checked", true);
			
			}

		// Marca el checkbox si se debe de mostrar la propina
	
			if (resp['propina'] == 1) {
				$("#check_propina").prop("checked", true);
			}


		// Marca el checkbox si esta la propina activada
			if (resp['switch_propina'] != 1) {
				$("#tab_propina").removeClass("in");
				$("#txt_propina").html("Propina desactivada");
				$("#accordion_propina").removeClass("panel-success").addClass("panel-danger");
				$("#switch_propina").attr("propina", 1);
			}

		// Marca el checkbox si esta la informacion del ticket activada
			if (resp['switch_info_ticket'] != 1) {
				$("#tab_info_ticket").removeClass("in");
				$("#txt_info_ticket").html("Información ticket desactivada");
				$("#accordion_info_ticket").removeClass("panel-success").addClass("panel-danger");
				$("#switch_info_ticket").attr("infoticket", 1);
			}
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('-----> fail listar_ajustes');
			console.log(resp);

		// Mensaje de error
			var $mensaje = 'Error al obtener los ajustes';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 		FIN listar_ajustes		------ ************ //////////////////

///////////////// ******** ----				reactivar 			------ ************ //////////////////
//////// Termina el pedido
	// Como parametros puede recibir:
		// adicionales -> cadena con los id de los productos extras
		// adicionalesDesc -> descripcion de los productos extras
		// cantidad -> cantidad del pedido
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// descripcion -> nombre del producto
		// idproducto -> ID del producto
		// sin -> cadena con los id de los productos sin
		// adicionales_desc -> descripcion de los productos extras
		// nota_sin -> nota de los productos sin
		// opcionales -> cadena con los id de los productos opcionales
		// opcionalesDesc -> descripcion de los productos extras
		// persona -> numero de persona
		// producto -> ID del pedido

	reactivar : function($objeto) {
		console.log('----> Objeto reactivar');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#loader_' + $objeto['producto']);
		$btn.button('loading');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=reactivar',
			type : 'POST',
			dataType : 'json',
			data : $objeto,
		}).done(function(resp) {
			console.log('----> Done terminar');
			console.log(resp);

		 	pedidos.comandas({'id' : pedidos.tipo});
		 // Consulta si la persona tiene mas pedidos

			pedidos.listar_terminados({
				div : 'div_terminados'
			});
		}).fail(function(resp) {
			console.log('----> fail reactivar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Mensaje de error
			var $mensaje = 'Error al reactivar el pedido';
			$btn.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});

			return 0;
		});

	},

///////////////// ******** ----				FIN reactivar			------ ************ //////////////////

///////////////// ******** ----				reactivar_can 			------ ************ //////////////////
//////// Termina el pedido
	// Como parametros puede recibir:
		// adicionales -> cadena con los id de los productos extras
		// adicionalesDesc -> descripcion de los productos extras
		// cantidad -> cantidad del pedido
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// descripcion -> nombre del producto
		// idproducto -> ID del producto
		// sin -> cadena con los id de los productos sin
		// adicionales_desc -> descripcion de los productos extras
		// nota_sin -> nota de los productos sin
		// opcionales -> cadena con los id de los productos opcionales
		// opcionalesDesc -> descripcion de los productos extras
		// persona -> numero de persona
		// producto -> ID del pedido

	reactivar_can : function($objeto) {
		console.log('----> Objeto reactivar_can');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#loader_' + $objeto['producto']);
		$btn.button('loading');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=reactivar_can',
			type : 'POST',
			dataType : 'json',
			data : $objeto,
		}).done(function(resp) {
			console.log('----> Done reactivar_can');
			console.log(resp);

		 	pedidos.comandas({'id' : pedidos.tipo});
		 // Consulta si la persona tiene mas pedidos

			pedidos.listar_eliminados({
				div : 'div_eliminados'
			});
		}).fail(function(resp) {
			console.log('----> fail reactivar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Mensaje de error
			var $mensaje = 'Error al reactivar el pedido';
			$btn.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});

			return 0;
		});

	},

///////////////// ******** ----				FIN reactivar			------ ************ //////////////////

///////////////// ******** ----				terminar 			------ ************ //////////////////
//////// Termina el pedido
	// Como parametros puede recibir:
		// adicionales -> cadena con los id de los productos extras
		// adicionalesDesc -> descripcion de los productos extras
		// cantidad -> cantidad del pedido
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// descripcion -> nombre del producto
		// idproducto -> ID del producto
		// sin -> cadena con los id de los productos sin
		// adicionales_desc -> descripcion de los productos extras
		// nota_sin -> nota de los productos sin
		// opcionales -> cadena con los id de los productos opcionales
		// opcionalesDesc -> descripcion de los productos extras
		// persona -> numero de persona
		// producto -> ID del pedido

	terminar : function($objeto) {
		console.log('----> Objeto terminar');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#loader_' + $objeto['producto']);
		$btn.button('loading');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=terminar',
			type : 'POST',
			dataType : 'json',
			data : $objeto,
		}).done(function(resp) {
			console.log('----> Done terminar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
		// Elimina el TR del listado(cuando estan en listado los pedidos pendientes)
			$('#tr_listado_pendientes_' + $objeto['producto']).remove();
			
		// Consulta si la persona tiene mas pedidos
			var $pedido = $('#pedido_' + $objeto['producto']);
			var $hermano = $pedido.siblings('.row');
			$hermano = $hermano.html();

			console.log('----> $hermano');
			console.log($hermano);

		// Si no tiene pedidos elimina la persona
			if (!$hermano) {
				var $persona = $pedido.parent().parent().parent();
				console.log('----> persona');
				console.log($persona);

				$hermano = $persona.siblings('.panel-default');
				$hermano = $hermano.html();

				$persona.remove();

				console.log('----> $hermano');
				console.log($hermano);

			// Si no hay mas personas remueve la comanda
				if (!$hermano) {
					console.log('----> entra');
					$('#comanda_' + $objeto['comanda']).remove();
				}
			} else {
				$pedido.remove();
			}

			pedidos.listar_terminados({
				div : 'div_terminados'
			});
		}).fail(function(resp) {
			console.log('----> fail terminar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Mensaje de error
			var $mensaje = 'Error al terminar el pedido';
			$btn.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});

			return 0;
		});

	},

///////////////// ******** ----				FIN terminar			------ ************ //////////////////

///////////////// ******** ----				listar_terminados		------ ************ //////////////////
//////// Obtiene la vista de los pedidos terminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista

	listar_terminados : function($objeto) {
		$objeto.vista_listado = pedidos.vista_listado;
		console.log('-----> objeto listar_terminados');
		console.log($objeto);

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=listar_terminados',
			type : 'POST',
			dataType : 'html',
			data : $objeto,
		}).done(function(resp) {
			console.log('-----> done listar_terminados');
			console.log(resp);

			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable si es un listado
			if(pedidos.vista_listado == 1){
				pedidos.convertir_dataTable({id:'tabla_listado_terminados'});
			}
		}).fail(function(resp) {
			console.log('-----> fail listar_terminados');
			console.log(resp);

		// Mensaje de error
			var $mensaje = 'Error al listar los pedidos';
			$('#' + $objeto['div']).notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ----			FIN listar_terminados			------ ************ //////////////////

///////////////// ******** ----					eliminar				------ ************ //////////////////
//////// Elimina el pedido
	// Como parametros puede recibir:
		// Array pedido
			// adicionales -> cadena con los id de los productos extras
			// adicionalesDesc -> descripcion de los productos extras
			// cantidad -> cantidad del pedido
			// comanda -> ID de la comanda
			// departamento -> ID del departamento
			// descripcion -> nombre del producto
			// idproducto -> ID del producto
			// sin -> cadena con los id de los productos sin
			// adicionales_desc -> descripcion de los productos extras
			// nota_sin -> nota de los productos sin
			// opcionales -> cadena con los id de los productos opcionales
			// opcionalesDesc -> descripcion de los productos extras
			// persona -> numero de persona
			// producto -> ID del pedido
			// Fin array pedido
			// merma -> 1 -> Mandar a merma, 2 -> no
			// comentario -> String con el comentario

	eliminar : function($objeto) {
		console.log('----> Objeto eliminar');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#'+$objeto.btn);
		$btn.button('loading');

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=eliminar',
			type : 'POST',
			dataType : 'json',
			data : $objeto,
		}).done(function(resp) {
			console.log('----> Done eliminar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Elimina el TR del listado(cuando estan en listado los pedidos pendientes)
			$('#tr_listado_pendientes_' + $objeto.pedido.producto).remove();
			
		// Consulta si la persona tiene mas pedidos
			var $pedido = $('#pedido_' + $objeto.pedido.producto);
			var $hermano = $pedido.siblings('.row');
			$hermano = $hermano.html();

			console.log('----> $hermano');
			console.log($hermano);

		// Si no tiene pedidos elimina la persona
			if (!$hermano) {
				var $persona = $pedido.parent().parent().parent();
				console.log('----> persona');
				console.log($persona);

				$hermano = $persona.siblings('.panel-default');
				$hermano = $hermano.html();

				$persona.remove();

				console.log('----> $hermano');
				console.log($hermano);

			// Si no hay personas remueve la comanda
				if (!$hermano) {
					console.log('----> entra');
					$('#comanda_' + $objeto.pedido.comanda).remove();
				}
			} else {
				$pedido.remove();
			}

		// Limpia el comentario
			$('#comentario_eliminar').val('');
		// Reinicia el select de la merma
			$('#merma').val(2);
			$(".selectpicker").selectpicker("refresh");
		// Cierra la modal de eliminar
			$('#modal_eliminar_pedido').click();
			
			pedidos.listar_eliminados({
				div : 'div_eliminados'
			});
		}).fail(function(resp) {
			console.log('----> fail eliminar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Mensaje de error
			var $mensaje = 'Error al terminar el pedido';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});

			return 0;
		});
	},

///////////////// ******** ----					FIN eliminar			------ ************ //////////////////

///////////////// ******** ----					listar_eliminados 		------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista

	listar_eliminados : function($objeto) {
		$objeto['vista_listado'] = pedidos.vista_listado;
		
		console.log('-----> objeto listar_eliminados');
		console.log($objeto);

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=listar_eliminados',
			type : 'POST',
			dataType : 'html',
			data : $objeto,
		}).done(function(resp) {
			console.log('-----> done listar_eliminados');
			console.log(resp);

			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable si es un listado
			if(pedidos.vista_listado == 1){
				pedidos.convertir_dataTable({id:'tabla_listado_eliminados'});
			}
		}).fail(function(resp) {
			console.log('-----> fail listar_eliminados');
			console.log(resp);

		// Mensaje de error
			var $mensaje = 'Error al listar los pedidos';
			$('#' + $objeto['div']).notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 			FIN listar_eliminados		------ ************ //////////////////

///////////////// ******** ---- 			actualizar_configuracion		------ ************ //////////////////
//////// Actualiza la configuracion de Foodware
	// Como parametros recibe:
		// tipo -> tipo de operacion 1: Terminar Pedidos Después de Pago, 2: Mantener Pedidos Después de Pago
		// pedir_pass -> 1 -> debe pedir el password, 2 -> no

	actualizar_configuracion : function($objeto) {
		console.log('------> objeto mostrar propina');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=pedidosActivos&f=actualizar_configuracion',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('------> done actualizar_configuracion');
			console.log(resp);
			
		// Propina activa
			if ($objeto['switch_propina'] == 1) {
				$("#txt_propina").html("Propina activada");
				$("#accordion_propina").removeClass("panel-danger").addClass("panel-success");
				$("#switch_propina").attr("propina", 2);
			}
			
		// Propina desactivada
			if ($objeto['switch_propina'] == 2) {
				$("#txt_propina").html("Propina desactivada");
				$("#accordion_propina").removeClass("panel-success").addClass("panel-danger");
				$("#switch_propina").attr("propina", 1);
			}

		// Informacion ticket activa
			if ($objeto['switch_info_ticket'] == 1) {
				$("#txt_info_ticket").html("Información ticket activada");
				$("#accordion_info_ticket").removeClass("panel-danger").addClass("panel-success");
				$("#switch_info_ticket").attr("infoticket", 2);
			}
			
		// Informacion ticket
			if ($objeto['switch_info_ticket'] == 2) {
				$("#txt_info_ticket").html("Información ticket desactivada");
				$("#accordion_info_ticket").removeClass("panel-success").addClass("panel-danger");
				$("#switch_info_ticket").attr("infoticket", 1);
			}
			
			var $mensaje = 'Configuracion guardada';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});
		}).fail(function(resp) {
			console.log('------> fail actualizar_configuracion');
			console.log(resp);

		// Error: Manda un mensaje con el error
			var $mensaje = 'Error al configurar';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 			FIN actualizar_configuracion		------ ************ //////////////////

///////////////// ******** ---- 				popup_comanda				------ ************ //////////////////
//////// Crea un popup sobre la comanda
	// Como parametros recibe:
		// tipo -> tipo de operacion 1: Terminar Pedidos Después de Pago, 2: Mantener Pedidos Después de Pago

	popup_comanda : function($objeto) {
		console.log('==================> objeto popup_comanda');
		console.log($objeto);

		$.each($objeto, function(index) {
			var $contenido = "	<button type='button' class='btn btn-primary btn-lg'>";
			$contenido += "		<i class='fa fa-check'></i> Nuevos pedidos";
			$contenido += "	</button>";

			$('#comanda_' + index).tooltipster({
				contentAsHTML : true,
				interactive : true,
				animation : 'fall',
				autoClose : false,
				theme : 'tooltipster-Shadow',
				zIndex : 1,
				content : $contenido
			}).mouseenter();
		});
	},

///////////////// ******** ---- 				FIN popup_comanda			------ ************ //////////////////

///////////////// ******** ---- 				cambiar_vista				------ ************ //////////////////
//////// CAmbia la manera en la que se muestran los pedidos(0-> vista en comanda, 1-> vista en listado)
	// Como parametros recibe:

	cambiar_vista : function($objeto) {
		pedidos.vista_listado = (pedidos.vista_listado == 0) ? 1 : 0;
		var $objeto = [];
		$objeto['id'] = pedidos.tipo;

		console.log('=============> vista_listado');
		console.log($objeto);

	// Lista los pedidos pendientes
		pedidos.comandas($objeto);

	// Lista los pedidos terminados
		pedidos.listar_terminados({
			div : 'div_terminados'
		});

	// Lista los pedidos eliminados
		pedidos.listar_eliminados({
			div : 'div_eliminados'
		});
	},
///////////////// ******** ---- 				FIN cambiar_vista			------ ************ //////////////////

///////////////// ******** ---- 	convertir_dataTable		------ ************ //////////////////
	//////// Conviertela tabla en dataTable
		// Como parametros recibe:
			// id -> ID de la tabla a convertir

	convertir_dataTable : function($objeto) {
		console.log('objeto convertir dataTable');
		console.log($objeto);
		
		var $valida = $.fn.dataTable.isDataTable('#' + $objeto['id']);
		
	// Validacion para evitar error al crear el dataTable
		if (!$valida) {
			$('#' + $objeto['id']).DataTable({
				language : {
					search : "<i class=\"fa fa-search\"></i>",
					lengthMenu : "_MENU_ por pagina",
					zeroRecords : "No hay datos.",
					infoEmpty : "No hay datos que mostrar.",
					info : " ",
					infoFiltered : " -> <strong> _TOTAL_ </strong> resultados encontrados",
					paginate : {
						first : "Primero",
						previous : "<<",
						next : ">>",
						last : "Último"
					}
				},
				order: [[0, 'asc']]
			});
		}
	},

///////////////// ******** ---- 	FIN convertir_dataTable			------ ************ //////////////////

///////////////// ******** ----		listar_comandas_activas 		------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista

	listar_comandas_activas : function($objeto) {
		console.log('-----> objeto listar_comandas_activas');
		console.log($objeto);

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=listar_comandas_activas',
			type : 'POST',
			dataType : 'html',
			data : $objeto,
		}).done(function(resp) {
			console.log('-----> done listar_comandas_activas');
			console.log(resp);

			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('-----> fail listar_comandas_activas');
			console.log(resp);

		// Mensaje de error
			var $mensaje = 'Error al buscar las comandas';
			$('#' + $objeto['div']).notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 			FIN listar_eliminados		------ ************ //////////////////

///////////////// ******** ----				actualizar_pedidos	 		------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda

	actualizar_pedidos : function($objeto) {
		console.log('-----> objeto actualizar_pedidos');
		console.log($objeto);

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=actualizar_pedidos',
			type : 'POST',
			dataType : 'json',
			data : $objeto,
		}).done(function(resp) {
			console.log('-----> done actualizar_pedidos');
			console.log(resp);
			
			var $mensaje = 'Pedidos terminados';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});
		
		// Elimina la fila
			$('#tr_comandas_activas_' + $objeto['id_comanda']).remove();
		}).fail(function(resp) {
			console.log('-----> fail actualizar_pedidos');
			console.log(resp);

		// Mensaje de error
			var $mensaje = 'Error al terminar los pedidos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 			FIN listar_eliminados		------ ************ //////////////////

}; 