"use strict";
var pedidos = {
	contScroll : 0,
	scrollMaximo : 0,
	saltosScroll : 20,
	tipo : 0,
	tiempoRefresh : 5, //Tiempo que tarda el disparar la funcion de buscar pedidos
	interval : '',
	intervalMinus : 0,
	autoPrint : false,
	request : null,
	lugar : '',
	vista_listado : 1,
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
						// pedidos.timer(0);
						$(this).dialog("close");
					}
				}, {
					text : "NO",
					class : 'btn btn-danger col-sm-5 pull-right',
					click : function() {
						pedidos.autoPrint = false;
						pedidos.comandas($objeto);
						// pedidos.timer(0);
						$(this).dialog("close");
					}
				}]
			});
		});
	},
	comandas : function($objeto) {
		console.log('-----> objeto comandas');
		console.log($objeto);
		pedidos.tipo = $objeto['id'];

	// Valida si se deben de imprimir los ticket o no
		if (pedidos.autoPrint) {
			var $ticket = 1;
		}

		console.log('-----> Antes ajax comandas');
		console.log('Tipo: ' + $objeto['id'] + ' 	ticket' + $ticket);

		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=ver',
			type : 'POST',
			dataType : 'html',
			data : {
				'tipo' : $objeto['id'],
				'ticket' : $ticket,
				'vista_listado' : pedidos.vista_listado
			},
		}).done(function(resp) {
			console.log('-----> done comandas');
			console.log(resp);

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
	cancelarProductoDialog : function(element) {
		$("#dialog").dialog();

		$('#btnAceptarDialog').unbind('click').bind('click', function(event) {
			pedidos.cancelarProducto(element);
		});
	},
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
	imprimir : function(organizacion, data) {
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
						html += '</br><strong>============> Tiempo'+ producto["tiempo_platillo"] + '</strong>';
						
						$tiempo_platillo = producto["tiempo_platillo"];
					}
					
					contProducto = (index + 1);

					html += '</br><strong>'+ producto["cantidad"] + '</strong>'+'x ' +producto["descripcion"]  + '</br>';

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

		// marca el checkbox si se debe de mostrar la propina
			if (resp['propina'] == 1) {
				$("#check_propina").prop("checked", true);
			}

		// marca el checkbox si se debe de mostrar el consumo
			if (resp['consumo'] == 1) {
				$("#check_consumo").prop("checked", true);
			}

		// Selecciona el tipo de operacion del restaurante
			if (resp['tipo_operacion'] != 1) {
				$("#tipo_operacion").val(resp['tipo_operacion']);
			}
			
		// Selecciona si se debe pedir pass o no
			if (resp['pedir_pass'] != 1) {
				$("#pedir_pass").val(resp['pedir_pass']);
			}
			
		// Selecciona si se deben de mostrar los dolares o no
			if (resp['mostrar_dolares'] != 1) {
				$("#mostrar_dolares").val(resp['mostrar_dolares']);
			}
			
		// Selecciona si se debe de mostrar la informacion de la comanda o no
			if (resp['mostrar_info_comanda'] != 1) {
				$("#mostrar_info_comanda").val(resp['mostrar_info_comanda']);
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
				var $persona = $pedido.parent().parent();
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
		$objeto['vista_listado'] = pedidos.vista_listado
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

	eliminar : function($objeto) {
		console.log('----> Objeto eliminar');
		console.log($objeto);

		if (confirm("¿Estas seguro de eliminar el pedido?")) {
		// Loader en el boton OK
			var $btn = $('#loader_eliminar_' + $objeto['producto']);
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
				$('#tr_listado_pendientes_' + $objeto['producto']).remove();
				
			// Consulta si la persona tiene mas pedidos
				var $pedido = $('#pedido_' + $objeto['producto']);
				var $hermano = $pedido.siblings('.row');
				$hermano = $hermano.html();

				console.log('----> $hermano');
				console.log($hermano);

			// Si no tiene pedidos elimina la persona
				if (!$hermano) {
					var $persona = $pedido.parent().parent();
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
						$('#comanda_' + $objeto['comanda']).remove();
					}
				} else {
					$pedido.remove();
				}

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
				$btn.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});

				return 0;
			});
		}//if
	},

///////////////// ******** ----					FIN eliminar			------ ************ //////////////////

///////////////// ******** ----					listar_eliminados 		------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista

	listar_eliminados : function($objeto) {
		$objeto['vista_listado'] = pedidos.vista_listado
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

		// Error: Manda un mensaje con el error
			if (resp) {
				var $mensaje = 'Configuracion guardada';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			}
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
	
	// Validacion para evitar error al crear el dataTable
		if (!$.fn.dataTable.isDataTable('#' + $objeto['id'])) {
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

///////////////// ******** ---- 	FIN convertir_dataTable		------ ************ //////////////////
}; 