var comandera = {
// Inicializamos el array de datos la comandera
	datos_mesa_comanda : {
		id_mesa: 0,
		id_comanda: 0,
		tipo: 0,
		tipo_operacion: 1
	},
	
	productos: '',
	departamentos : '',

///////////////// ******** ---- 				vista_comandera					------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_comandera : function($objeto) {
		console.log('=========> objeto vista_comandera');
		console.log($objeto);
		
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_comandera',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_comandera');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
			
			comandera.departamentos = $("#div_departamentos").html();
		}).fail(function(resp) {
			console.log('=========> Fail vista_comandera');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar la comandera');

			var $mensaje = 'Error al cargar la comandera';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN vista_comandera					------ ************ //////////////////

///////////////// ******** ---- 			mandar_mesa_comandera				------ ************ //////////////////
//////// Consulta los datos de la mesa y los devuelve en un array
	// Como parametros recibe:
		// id_mesa -> ID de la mesa
		// tipo -> Tipo de mesa
		// id_comanda -> ID de la comanda
		// tipo_operacion -> Tipo de operacion del restaurante
	
	mandar_mesa_comandera : function($objeto) {
		console.log('=========> objeto mandar_mesa_comandera');
		console.log($objeto);
		
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=mandar_mesa_comandera',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done mandar_mesa_comandera');
			console.log(resp);
		
		// Valia que exista una comanda abierta, si no crea una
			if(!resp['info_mesa']){
				var $mensaje = 'La comanda ya no existe';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 10000,
					className : 'warn',
					arrowSize : 15
				});
			
			// Quita la comanda de la mesa
				$("#mesa_" + $objeto['id_mesa']).attr('id_comanda', '');
				$('#mesa_' + $objeto['id_mesa']).css('background-color', '#FFFFFF');
				$("#div_tiempo_" + $objeto['id_mesa']).html('');
				$("#div_total_" + $objeto['id_mesa']).html('');
				$('#tiempo').val(1);
		
			// Oculta la ventana modal
				$("#modal_comandera").click();
				
				return 0;
			}
			
		// LLena los campos
			$("#comanda_text").html(resp['id_comanda']);
			$("#mesa_text").html(resp['nombre']);
			$("#borrar_persona").val(resp['num_personas']);
			$("#mesa_" + $objeto['id_mesa']).attr('id_comanda', resp['id_comanda']);
			$('#mesa_' + $objeto['id_mesa']).css('background-color', '#FF6961');
			$("#num_comensales_comandera").val(resp['info_mesa']['comensales']);
			$('#tiempo').val(1);
			$("#mesero_" + $objeto['id_mesa']).html(resp['mesero']);
			
		// Oculta la modal de pago si esta abierta
			$(".GtableCloseComanda").css('visibility', 'hidden');
			
		// Guarda los datos de la comanda
			comandera.datos_mesa_comanda = resp;
			
		// Carga la vista de las personas
			var $datos = {};
			$datos['div'] = 'div_personas';
			$datos['num_personas'] = resp['num_personas'];
			$datos['personas'] = resp['personas'];
			$datos['id_comanda'] = resp['id_comanda'];
			comandera.vista_personas($datos);
		}).fail(function(resp) {
			console.log('=========> Fail mandar_mesa_comandera');
			console.log(resp);

			var $mensaje = 'Error al cargar la comandera';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN mandar_mesa_comandera			------ ************ //////////////////
	
///////////////// ******** ---- 				vista_personas					------ ************ //////////////////
//////// Carga la vista de las personas de la comanda
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// num_personas -> Numero de personas
		// personas -> Array con las personas de la comanda
		// id_comanda -> ID de la comanda
	
	vista_personas : function($objeto) {
		console.log('=========> objeto vista_personas');
		console.log($objeto);
		
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Limpia la div de los pedidos
		$("#div_listar_pedidos_persona").html('<div align="center"><h3><span class="label label-default">Agrega una persona</span></h3></div>');
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_personas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_personas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
			
		// Abre la primera orden que encuentre
			var abrir_orden = $("#persona_1").click();
			if(abrir_orden['length'] < 1){
				var orden = 0;
				var limite = 2;
			
			// Busca la orden siguien y le da clic(solo realiza 20 intentos)
				while (orden == 0 && limite < 20) {
					abrir_orden = $("#persona_"+limite).click();
					
				// Para el ciclo si encuentra la persona
					if(abrir_orden['length'] > 0){
						orden = 1;
					}
					
					limite++;
				}
			}
		}).fail(function(resp) {
			console.log('=========> Fail vista_personas');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar las personas');

			var $mensaje = 'Error al cargar las personas';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN vista_personas					------ ************ //////////////////
	
///////////////// ******** ---- 			listar_pedidos_persona				------ ************ //////////////////
//////// Carga la vista de los productos de la persona
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
	
	listar_pedidos_persona : function($objeto) {
		console.log('=========> objeto listar_pedidos_persona');
		console.log($objeto);
		
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_pedidos_persona',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_pedidos_persona');
			console.log(resp);
		
		// Guarda la persona seleccionada
			comandera.datos_mesa_comanda['persona_seleccionada'] = $objeto['persona'];
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		
		// Cambia el la persona para cerrar la comanda por persona
			$('#text_cerrar_persona').html($objeto['persona']);
			$('#borrar_persona').val($objeto['persona']);
			
		// Obtiene el color de fondo de la persona en RGB
			var color = $('#persona_' + $objeto['persona']).css("background-color");
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
			$("#" + $objeto['div']).css("background-color", color);
		}).fail(function(resp) {
			console.log('=========> Fail listar_pedidos_persona');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar los pedidos de la persona');

			var $mensaje = 'Error al cargar los pedidos de la persona';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN listar_pedidos_persona			------ ************ //////////////////

///////////////// ******** ---- 				detalles_producto				------ ************ //////////////////
//////// Consulta los detalles del producto, si tiene carga los opcionales, extras, etc. Si no agrega el producto
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// tipo -> Tipo de producto
		// Materiales -> 1 -> si tiene insumos, 0 -> si no
	
	detalles_producto : function($objeto) {
		console.log('=========> objeto detalles_producto');
		console.log($objeto);
	
	// Guarda el HTML de los productos en una variable si no se ha guardado
		if(comandera.productos == ''){
			comandera.productos = $("#div_productos").html();
		}
	
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=detalles_producto',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done detalles_producto');
			console.log(resp);
		
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail detalles_producto');
			console.log(resp);

			$('#' + $objeto['div']).html('Error al agregar el producto');

			var $mensaje = 'Error al agregar el producto';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN detalles_producto				------ ************ //////////////////

///////////////// ******** ---- 			guardar_detalles_pedido				------ ************ //////////////////
//////// Consulta los detalles del producto, si tiene carga los opcionales, extras, etc. Si no agrega el producto
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// tipo -> Tipo de producto
		// Materiales -> 1 -> si tiene insumos, 0 -> si no
	
	guardar_detalles_pedido : function($objeto) {
		console.log('=========> objeto guardar_detalles_pedido');
		console.log($objeto);
		
		var opcionales = new Array();
		var extras = new Array();
		var sin = new Array();

	// Crea los arreglos de opcionales y extra de los check seleccionados
		$('.itemCheck').each(function() {
		// Valida que este checado
			if ($(this).is(':checked')) {
			// Agrega los productos "sin" al array
				if ($(this).attr('opcional') == 1) {
					sin.push($(this).val());
				}

			// Agrega los productos "extra" al array
				if ($(this).attr('opcional') == 2) {
					extras.push($(this).val());
				}

			// Agrega los productos "opcionales" al array
				if ($(this).attr('opcional') == 3) {
					opcionales.push($(this).val());
				}
			}
		});
		
		$objeto['nota_opcional'] = $('#nota_opcional').val();
		$objeto['nota_extra'] = $('#nota_extra').val();
		$objeto['nota_sin'] = $('#nota_sin').val();
		$objeto['opcionales'] = opcionales.join(',');
		$objeto['extras'] = extras.join(',');
		$objeto['sin'] = sin.join(',');
	
		console.log('=========> objeto antes guardar_pedido');
		console.log($objeto);
		
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Guarda el pedido de la persona
		comandera.guardar_pedido($objeto);
	},

///////////////// ******** ---- 			FIN guardar_detalles_pedido			------ ************ //////////////////

///////////////// ******** ---- 				guardar_pedido					------ ************ //////////////////
//////// Guarda el pedido de la persona y carga sus pedidos
	// Como parametros recibe:
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// opcionales -> Cadena con los IDs de los productos opcionales
		// extras -> Cadena con los IDs de los productos extras
		// sin -> Cadena con los IDs de los productos sin
		// nota_opcional -> string con la nota de los productos opcionales
		// nota_extra -> string con la nota de los productos extras
		// nota_sin -> string con la nota de los productos sin
	
	guardar_pedido : function($objeto) {
		console.log('=========> objeto guardar_pedido');
		console.log($objeto);
		
		var $div_productos = $('#' + $objeto['div']).html();
	
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_pedido',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_pedido');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
		
		// Carga los pedidos de la persona
			comandera.listar_pedidos_persona({
						persona: $objeto['persona'], 
						id_comanda: $objeto['id_comanda'],
						div: 'div_listar_pedidos_persona'
			});
		
		// Carga los productos
			$("#div_productos").html(comandera.productos);
		}).fail(function(resp) {
			console.log('=========> Fail guardar_pedido');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Error al guardar el pedido';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN guardar_pedido					------ ************ //////////////////

///////////////// ******** ----  				sumar_pedido					------ ************ //////////////////
//////// Aumenta la cantidad de la orden y lista los productos de la persona
	// Como parametro puede recibir:
		// idorder -> ID del pedido
		// idcomanda -> ID de la comanda
		// idperson -> numero de  persona
		
			
	sumar_pedido : function($objeto) {
		var $cantidad = parseInt($('#cantidad_' + $objeto['idorder']).html());
		var $num_pedidos = parseInt($('#num_pedidos' + $objeto['idorder']).val());
		$objeto['cantidad'] = $cantidad;
		$objeto['num_pedidos'] = $num_pedidos;
	
		console.log('---	-	-	-	-	$objeto sumar_pedido');
		console.log($objeto);
	
		for ( i = 0; i < $num_pedidos; i++) {
			console.log('---	-	-	-	-	entrar for');
			console.log($objeto);
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=comandas&f=sumar_pedido',
				type : 'GET',
				dataType : 'json',
			}).done(function(resp) {
				console.log('---	-	-	-	-	done sumar_pedido');
				console.log(resp);
	
			// Error
				if (resp['status'] == 2) {
					var $mensaje = 'Error al cargar aumentar la cantidad';
					$(".GtableUserContent").notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
	
					return 0;
				}
	
				if (resp['status'] == 1) {
					$cantidad += 1;
					$('#cantidad_' + $objeto['idorder']).html($cantidad);
					$('#num_pedidos' + $objeto['idorder']).val(1);
				}
			}).fail(function(resp) {
				console.log('---------> Fail sumar_pedido');
				console.log(resp);
	
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
		} //Fin for
	},


///////////////// ******** ---- 			FIN sumar_pedido			------ ************ //////////////////

///////////////// ******** ----  			restar_pedido				------ ************ //////////////////
//////// Resta un pedido de la  persona
	// Como parametro puede recibir:
		// id -> ID del pedido
		// id_comanda -> ID de la comanda
		// persona -> numero de  persona
		
	restar_pedido : function($objeto) {
		console.log('=========> objeto restar_pedido');
		console.log($objeto);
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=restar_pedido',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done restar_pedido');
			console.log(resp);
		
			comandera.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('---------> Fail restar_pedido');
			console.log(resp);
	
		// Manda un mensaje de error
			$mensaje = 'Error al restar la cantidad';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN restar_pedido			------ ************ //////////////////

///////////////// ******** ----  			eliminar_pedido				------ ************ //////////////////
//////// Elimina un pedido de la  persona
	// Como parametro puede recibir:
		// idorder: -> ID del pedido
		// idperson: -> ID de la persona
		// idcomanda: -> ID de la comanda

	eliminar_pedido : function($objeto) {
		console.log('=========> objeto eliminar_pedido');
		console.log($objeto);
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=eliminar_pedido',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done eliminar_pedido');
			console.log(resp);
		
			comandera.listar_pedidos_persona({
				persona: $objeto['idperson'], 
				id_comanda: $objeto['idcomanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('---------> Fail eliminar_pedido');
			console.log(resp);
	
		// Manda un mensaje de error
			$mensaje = 'Error al eliminar el pedido';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN eliminar_pedido			------ ************ //////////////////

///////////////// ******** ----  		agregar_persona_comandera		------ ************ //////////////////
//////// Agrega una persona y carga la vista de las personas
	// Como parametro puede recibir:
		// num_personas -> Numero de personas
		// id_comanda -> ID de la comanda

	agregar_persona_comandera : function($objeto) {
		console.log('=========> objeto agregar_persona_comandera');
		console.log($objeto);
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_persona_comandera',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done agregar_persona_comandera');
			console.log(resp);
			
			var $datos = {};
			$datos['div'] = 'div_personas';
			$datos['num_personas'] = $objeto['num_personas'];
			$datos['personas'] = resp['personas'];
			$datos['id_comanda'] = $objeto['id_comanda'];
			comandera.vista_personas($datos);
		
		// Selecciona la nueva persona despues de medio segundo
			setTimeout(function() {
				$("#persona_" + resp['result']).click();
			}, 500);
		}).fail(function(resp) {
			console.log('---------> Fail agregar_persona_comandera');
			console.log(resp);
	
		// Manda un mensaje de error
			$mensaje = 'Error al agregar la persona';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 	FIN agregar_persona_comandera			------ ************ //////////////////

///////////////// ******** ----  				pedir						------ ************ //////////////////
//////// Manda el pedido de la comanda a las areas correspondientes
	// Como parametro puede recibir:
		// cerrar_comanda -> 1 cierra la modal, 0 -> permanece en la modal 
		// id_comanda -> ID de la comanda

	pedir : function($objeto) {
		console.log('=========> objeto pedir');
		console.log($objeto);
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=pedir',
			type : 'GET',
			dataType : 'json',
			async: false
		}).done(function(resp) {
			console.log('=========> Done pedir');
			console.log(resp);
			
		// Redirecciona solo si no es Fast food
			if (resp['tipo_operacion'] != 3) {
				console.log('-------> tipo_operacion diferente de 3');
				console.log(resp);

			// Valida si viene de cerrrar comanda o no
				if ($objeto['cerrar_comanda'] != 1) {
					console.log('===========> Procesa el pedido normalmente');
					console.log($objeto);
				
				// Oculta la ventana modal
					$("#modal_comandera").click();
				}
			}
		}).fail(function(resp) {
			console.log('---------> Fail pedir');
			console.log(resp);
	
		// Manda un mensaje de error
			$mensaje = 'Error al mandar el pedido';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ----  				FIN pedir					------ ************ //////////////////

///////////////// ******** ---- 				cerrar_comanda				------ ************ //////////////////
//////// Cierra la comanda e imprime el ticket
	// Como parametros recibe:
		// bandera -> 0 -> todo junto, 1 -> individual, 2 -> pagar directo en caja, 3 -> mandar a caja
		// nombre -> Nombre del cliente
		// idComanda -> ID de la comanda
		// idmesa -> ID de la mesa
		// tel -> Telefono
		// Tipo -> Tipo de mesa
		// id_reservacion -> ID de la reservacion
	
	cerrar_comanda : function($objeto) {
		console.log('=========> objeto cerrar_comanda');
		console.log($objeto);
		
		console.log('=========> datos');
		console.log(comandera['datos_mesa_comanda']);
		
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
	
	// Oculta la div de pagar
		$(".GtableCloseComanda").css('visibility', 'hidden');
		
	// Regresa un Json si se debe de mandar a caja, si no carga el HTML
		var $tipo = 'html';
		if($objeto['bandera'] == 2 || $objeto['bandera'] == 3){
			$tipo = 'json';
		}
		
	// Hace el pedido antes de cerrar la comanda(sirve en mandar a caja para el inventario)
		if($objeto['pedir'] == 1){
			comandera.pedir({
				id_comanda: $objeto['idComanda'],
				cerrar_comanda: 1
			});
		}
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cerrar_comanda',
			type : 'GET',
			dataType : $tipo,
		}).done(function(resp) {
			console.log('=========> Done cerrar_comanda');
			console.log(resp);
		
		// Elimina la mesa si es servicio a domicilio
			if($objeto['tipo'] == 2){
				console.log('=========> Elimina la mesa');
				$("#"+$objeto['idmesa']).remove();
			}
			
		// Quita el loader
			$btn.button('reset');
		
		// La comanda se cierra pagando directo en caja
			if ($objeto['bandera'] == 2) {
			// Todo bien regresa el codigo de la comanda
				if (resp['rows'][0]['respuesta'] == "ok") {
				// Inicializamos variables
					var codigo = resp['rows'][0]['comanda'];

					console.log('------> codigo');
					console.log(codigo);

					var outElement = $("#tb2156-u", window.parent.document).parent();
					var caja = outElement.find("#tb2051-u");
					var pestana = $("body", window.parent.document).find("#tb2051-1");
					var openCaja = $("body", window.parent.document).find("#mnu_2051");
					var pathname = window.location.pathname;
					var url = document.location.host + pathname;

					if (caja.length > 0) {
					// Valida que exista un codigo
						if (!codigo) {
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
							if ($objeto['cerrar_persona'] != 1) {
							// Si es reimprimir no dirige al mapa de mesas
								if ($objeto['reimprime'] != 1) {
									console.log('======> salta reimprime');
									console.log(resp['tipo_operacion']);
									
								// Recarga la pagina en lugar de redirigir al mapa de mesas
									if (resp['tipo_operacion'] == 3) {
										console.log('======> Entra tipo operacion 3');

									// Carga una nueva comanda en la mesa
										comandera.mandar_mesa_comandera({
											id_mesa: $objeto['idmesa'],
											tipo: 0,
											id_comanda: ''
										});
										
										setTimeout(function() {
										// Abre la pestaña de caja
											openCaja.trigger('click');
											pestana.trigger('click');

										// Selecciona el campo de busqueda
											var campoBuscar = $(".frurl", caja).contents().find("#search-producto");
										
										// Agrega el codigo de la comanda y busca sus productos
											campoBuscar.trigger("focus");
											campoBuscar.val(codigo);
											campoBuscar.trigger({
												type : "keypress",
												which : 13
											});
											
										// ch@
											var campoCliente = $(".frurl", caja).contents().find("#cliente-caja");
											campoCliente.val(comandera['datos_mesa_comanda']['nombre']);
										}, 500);
									} else {
									// Abre la pestaña de caja
										openCaja.trigger('click');
										pestana.trigger('click');

									// Selecciona el campo de busqueda
										var campoBuscar = $(".frurl", caja).contents().find("#search-producto");
										campoBuscar.trigger("focus");

									// Agrega el codigo de la comanda y busca sus productos
										campoBuscar.val(codigo);
										campoBuscar.trigger({
											type : "keypress",
											which : 13
										});

									// ch@
										var campoCliente = $(".frurl", caja).contents().find("#cliente-caja");
										console.log('======> nombre cliente: ' + comandera['datos_mesa_comanda']['nombre']);
										campoCliente.val(comandera['datos_mesa_comanda']['nombre']);
										
										if(comandera.datos_mesa_comanda['separar'] == 1){
											comandera.datos_mesa_comanda['separar'] = '';
											$('#modal_reiniciar').modal({
												keyboard: false,
												show: true
											});
										}else{
										// Quita la comanda de la mesa
											$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
											$('#mesa_' + $objeto['idmesa']).css('background-color', '#FFFFFF');
											$("#div_tiempo_" + $objeto['idmesa']).html('');
											$("#div_total_" + $objeto['idmesa']).html('');
											
										// Oculta la ventana modal
											$("#modal_comandera").click();
										}
									}
								}
							} else {
								console.log('======> Recarga cerrar_persona');
								
							// Carga una nueva comanda en la mesa
								comandera.mandar_mesa_comandera({
									id_mesa: $objeto['idmesa'],
									tipo: 0,
									id_comanda: ''
								});
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
						});
						// Fin actualizar comanda
					} else {
						alert("No se Puede Cerrar Comanda, Favor de Abrir La Caja");
					}
				}

				return 0;
			} // FIN La comanda se cierra pagando directo en caja
			
		// La comanda se manda a caja
			if ($objeto['bandera'] == 3) {
				if (resp['rows'][0]['respuesta'] == "ok") {
				// Recarga la comandera con una nueva comanda
					if ($objeto['tipo_operacion'] == 3) {
						console.log('======> Entra tipo operacion 3');
						comandera.mandar_mesa_comandera({
							id_mesa : $objeto['idmesa'],
							tipo : 0,
							id_comanda : ''
						});
					} else {
					// Separa las mesas juntas
						if (comandera.datos_mesa_comanda['separar'] == 1) {
							comandera.datos_mesa_comanda['separar'] = '';
							
							$('#modal_reiniciar').modal({
								keyboard : false,
								show : true
							});
					// Quita la comanda de la mesa
						} else {
							$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
							$('#mesa_' + $objeto['idmesa']).css('background-color', '#FFFFFF');
							$("#div_tiempo_" + $objeto['idmesa']).html('');
							$("#div_total_" + $objeto['idmesa']).html('');

						// Oculta la ventana modal
							$("#modal_comandera").click();
						}
					}
				} else {
					alert("Error al cerrar la comanda");
				}
				
				return 0;
			}
		
		// Ejecuta los scripts de la comanda
			$("#div_ejecutar_scripts").html(resp);
			
		//abrimos una ventana vacía nueva
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');

			$(ventana).ready(function() {
			// Cargamos la vista ala nueva ventana
				ventana.document.write(resp);
			// Cerramos el documento
				ventana.document.close();

			// Imprimimos la ventana y la cierra despues de un segundo
				setTimeout(closew, 1000);
				function closew() {
					ventana.print();
					ventana.close();

				// Valida si la comanda se cierra por persona o normal
					if ($objeto['cerrar_persona'] != 1) {
					// Si es reimprimir no dirige al mapa de mesas
						if ($objeto['reimprime'] != 1) {
							console.log('======> entra reimprime');
							console.log($objeto['tipo_operacion']);
						
						// Recarga la comandera con una nueva comanda
							if ($objeto['tipo_operacion'] == 3) {
								console.log('======> Entra tipo operacion 3');
								comandera.mandar_mesa_comandera({
									id_mesa: $objeto['idmesa'],
									tipo: 0,
									id_comanda: ''
								});
							} else {
							// Separa las mesas juntas
								if (comandera.datos_mesa_comanda['separar'] == 1) {
									comandera.datos_mesa_comanda['separar'] = '';
									
									$('#modal_reiniciar').modal({
										keyboard : false,
										show : true
									});
							// Quita la comanda de la mesa
								} else {
									$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
									$('#mesa_' + $objeto['idmesa']).css('background-color', '#FFFFFF');
									$("#div_tiempo_" + $objeto['idmesa']).html('');
									$("#div_total_" + $objeto['idmesa']).html('');
		
								// Oculta la ventana modal
									$("#modal_comandera").click();
								}
							}
						}
					} else {
						console.log('======> Recarga la comandera con una nueva comanda');
						comandera.mandar_mesa_comandera({
							id_mesa: $objeto['idmesa'],
							tipo: 0,
							id_comanda: comandera['datos_mesa_comanda']['id_comanda']
						});
					}
				} //Fin funcion closew
			});
		}).fail(function(resp) {
			console.log('=========> Fail cerrar_comanda');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Error al cerrar la comanda';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN cerrar_comanda				------ ************ //////////////////

///////////////// ******** ---- 			guardar_comensales				------ ************ //////////////////
//////// Guarda el numero de comensales de la comanda
	// Como parametros puede recibir:	
		// comanda -> ID de la comanda
		// comensales -> numero de omensales
		
	guardar_comensales : function($objeto) {
		console.log('=========> $objeto guardar_comensales');
		console.log($objeto);
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_comensales',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_comensales');
			console.log(resp);
		
		// Guarda el numero de comensales en la variable de comanda
			comandera['datos_mesa_comanda']['info_mesa']['comensales'] = $objeto['comensales'];
			
			var $mensaje = 'Comensales guardados';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('=========> Fail guardar_comensales');
			console.log(resp);

			var $mensaje = 'Error al guardar el numero de comensales';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
		
///////////////// ******** ---- 		FIN guardar_comensales				------ ************ //////////////////

///////////////// ******** ---- 			cerrar_personalizado			------ ************ //////////////////
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
		
	cerrar_personalizado : function($objeto) {
		console.log('-----> $objeto cerrar_personalizado');
		console.log($objeto);
    	
	// Loader
		$('#'+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data:$objeto,
		    url:'ajax.php?c=comandas&f=vista_cerrar_personalizado',
		    type: 'GET',
		    dataType:'html',
		}).done(function(resp) {
			console.log('=========> Done cerrar_personalizado');
			console.log(resp);
		    
		   	$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail cerrar_personalizado');
			console.log(resp);

			var $mensaje = 'Error al cerrar la comanda';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN cerrar_personalizado		------ ************ //////////////////

///////////////// ******** ---- 		cerrar_comanda_persona			------ ************ //////////////////
//////// Genera una comanda de la persona y la cierra
	// Como parametros recibe:
		// persona -> Numero de persona	
		// id_comanda -> ID de la comanda

	cerrar_comanda_persona : function($objeto) {
		console.log('===============> objeto cerrar_comanda_persona');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cerrar_comanda_persona',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done cerrar_comanda_persona');
			console.log(resp);
			
			var $comanda = resp['id_comanda'];
			
		// Quita el loader
			$btn.button('reset');
	
		// Cierra la comanda
			comandera.cerrar_comanda({
				bandera : 0,
				nombre : comandera['datos_mesa_comanda']['nombre'],
				idComanda : resp['id_comanda'],
				tel : comandera['datos_mesa_comanda']['tel'],
				idmesa : $objeto['id_mesa'],
				tipo : comandera['datos_mesa_comanda']['tipo'],
				id_reservacion : comandera['datos_mesa_comanda']['id_reservacion'],
				num_comensales : comandera['datos_mesa_comanda']['info_mesa']['comensales'],
				tipo_operacion : comandera['datos_mesa_comanda']['tipo_operacion'],
				cerrar_persona: 1,
				persona: $objeto['persona'],
				id_comanda_padre: resp['id_comanda_padre']
			});
		}).fail(function(resp) {
			console.log('================= Fail cerrar_comanda_persona');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Error al cerrar la comanda';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN cerrar_comnada_persona				------ ************ //////////////////

///////////////// ******** ---- 				listar_familias					------ ************ //////////////////
//////// Consulta la vista de las familias y las carga a la div, consulta los productos y los carga a la div
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// div_productos -> div donde se cargan los productos
		// departamento -> ID del departamento
	
	listar_familias : function($objeto) {
		console.log('=========> objeto listar_familias');
		console.log($objeto);

	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_familias',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_familias');
			console.log(resp);
		
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			comandas.buscar_productos({
				departamento: $objeto['departamento'],
				comanda : comandera['datos_mesa_comanda']['id_comanda'],
				div : $objeto['div_productos']
			});
		}).fail(function(resp) {
			console.log('=========> Fail listar_familias');
			console.log(resp);

			$('#' + $objeto['div']).html('Error al buscar las familias');

			var $mensaje = 'Error al buscar las familias';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN listar_familias					------ ************ //////////////////

///////////////// ******** ---- 				listar_lineas					------ ************ //////////////////
//////// Consulta la vista de las lineas y las carga a la div, consulta los productos y los carga a la div
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// div_productos -> div donde se cargan los productos
		// familia -> ID del departamento
	
	listar_lineas : function($objeto) {
		console.log('=========> objeto listar_lineas');
		console.log($objeto);

	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_lineas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_lineas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			comandas.buscar_productos({
				familia: $objeto['familia'],
				comanda : comandera['datos_mesa_comanda']['id_comanda'],
				div : $objeto['div_productos']
			});
		}).fail(function(resp) {
			console.log('=========> Fail listar_lineas');
			console.log(resp);

			$('#' + $objeto['div']).html('Error al buscar las lineas');

			var $mensaje = 'Error al buscar las lineas';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN listar_lineas					------ ************ //////////////////

///////////////// ******** ---- 				area_inicio						------ ************ //////////////////
//////// Consulta los departamentos y los productos y los agrega a sus divs
	// Como parametros recibe:
	
	area_inicio : function($objeto) {
		console.log('=========> objeto area_inicio');
		console.log($objeto);
	
	// Si no existen los productos los consulta, si existen los agrega a la div
		if (comandera.productos == '') {
			comandas.buscar_productos({
				texto: '',
				comanda : comandera['datos_mesa_comanda']['id_comanda'],
				div : 'div_productos'
			});
		} else{
			$('#div_productos').html(comandera.productos); 
		}
	
	// Agrega los departamentos a la div
		$('#div_departamentos').html(comandera.departamentos);
	},
	
///////////////// ******** ---- 			FIN listar_lineas					------ ************ //////////////////

///////////////// ******** ---- 				borrar_persona					------ ************ //////////////////
//////// Elimina la persona de la comanda y sus pedidos
	// Como parametros recibe:
		// id_comanda -> ID de la comanda
		// persona -> ID de la persona
		// btn -> Boton del loader
		// pass -> Contraseña de seguridad
			
	borrar_persona : function($objeto) {
		console.log('=========> objeto borrar_persona');
		console.log($objeto);

	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=borrar_persona',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done borrar_persona');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
				
		// Todo bien :D
			if(resp['status'] == 1){
			// Cierra la ventana modal
				$('#modal_eliminar_persona').click();
			
			// Limipa el campo de pass
				$('#pass_eliminar_persona').val('');
				
			// Carga los datos de la mesa
				comandera.mandar_mesa_comandera({
					id_mesa: comandera['datos_mesa_comanda']['id_mesa'],
					tipo: comandera['datos_mesa_comanda']['tipo'],
					id_comanda: comandera['datos_mesa_comanda']['id_comanda']
				});
				
			// Contraseña incorrecta			
				var $mensaje = 'Persona eliminada';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
			
				return 0;
			}
		
		// Contraseña incorrecta			
			var $mensaje = 'Contraseña incorrecta';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('=========> Fail borrar_persona');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Error al borrar la persona';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN borrar_persona					------ ************ //////////////////

///////////////// ******** ---- 			autoriza_asignacion					------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y autoriza la asignacion de la mesa
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar
	
	autoriza_asignacion : function($objeto) {
		console.log('--------> Objet autoriza_asignacion');
		console.log($objeto);
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done autoriza_asignacion');
			console.log(resp);
		
		// Pass incorrecto
			if (resp != $objeto['pass']) {
				var $mensaje = 'Contraseña incorrecta';
				$('#pass_asignacion').notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
	
				return 0;
			}
	
			if (resp == $objeto['pass']) {
			// Cierra la ventana de autoricacion
				$('#modal_autorizar').click();
			// Muestra la ventana para seleccionar al empleado
				$('#modal_asignar').modal();
			}
		}).fail(function(resp) {
			console.log('=========> Fail autoriza_asignacion');
			console.log(resp);
	
			var $mensaje = 'Error al autorizar';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
			
///////////////// ******** ---- 			FIN autoriza_asignacion				------ ************ //////////////////

///////////////// ******** ---- 				asignar_mesa					------ ************ //////////////////
//////// Asigna la mesa al mesero
	// Como parametros puede recibir:
		// empleado -> ID del mesero
		// mesa -> ID de la mesa
	
	asignar_mesa : function($objeto) {
		console.log('--------> Objet asignar_mesa');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=asignar_mesa',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done asignar_mesa');
			console.log(resp);
			
			var $mensaje = 'Asignacion guardada';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});

		// Cierra la ventana de asignacion
			$('#modal_asignar').click();		
		
		// Limpia el campo de password
			$('#pass_asignacion').val('');
		}).fail(function(resp) {
			console.log('=========> Fail asignar_mesa');
			console.log(resp);

			// Quita el loader
			$btn.button('reset');

			var $mensaje = 'Error al asignar la mesa';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
			
///////////////// ******** ---- 				FIN asignar_mesa				------ ************ //////////////////

///////////////// ******** ---- 				mudar_comanda					------ ************ //////////////////
//////// Muda la comanda de mesa
	// Como parametros recibe:
		// mesa -> id de la mesa a mudar
		// mesa_origen -> ID de la mesa origen
		// comanda -> ID de la comanda
	
	mudar_comanda : function($objeto) {
	// Envia un mensaje que la comanda se esta mudando
		var $mensaje = 'Mudando comanda ...';
		$('#mesa_mudar_' + $objeto['mesa']).notify($mensaje, {
			position : "top center",
			autoHide : true,
			autoHideDelay : 5000,
			className : 'success',
		});

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=mudar_comanda',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done mudar_comanda');
			console.log(resp);

		// Quita la comanda de la mesa
			$("#mesa_" + $objeto['mesa_origen']).attr('id_comanda', '');
			$('#mesa_' + $objeto['mesa_origen']).css('background-color', '#FFFFFF');
			$("#div_tiempo_" + $objeto['mesa_origen']).html('');
			$("#div_total_" + $objeto['mesa_origen']).html('');
			
		// Guarda la comanda en la nueva mesa
			$("#mesa_" + $objeto['mesa']).attr('id_comanda', $objeto['comanda']);
			$('#mesa_' + $objeto['mesa']).css('background-color', '#FF6961');
			
		// Oculta la comandera y le modal de mudar
			$('#div_mudar').click();
			$("#modal_comandera").click();
		}).fail(function(resp) {
			console.log('=========> Fail mudar_comanda');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			var $mensaje = 'Error al mudar la comanda';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},


///////////////// ******** ---- 				FIN mudar_comanda					------ ************ //////////////////

///////////////// ******** ---- 				eliminar_comanda					------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y elimina la mesa si es correcta
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar	
		
	eliminar_comanda : function($objeto) {
		console.log('=========> objeto eliminar_comanda');
		console.log($objeto);
				
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done autorizar eliminar_comanda');
			console.log(resp);
	
			if (resp != $objeto['pass']) {
				var $mensaje = 'Contraseña incorrecta';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				
				return 0;
			}
			
			$("#pass_eliminar_comanda").val('');
			
			$.ajax({
				data :$objeto,
				url : 'ajax.php?c=comandas&f=deleteComanda',
				type : 'GET',
			}).done(function(resp) {
				console.log('=========> Done eliminar_comanda');
				console.log(resp);
			
				var $mensaje = 'Comanda eliminada';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			
			// Cierra la ventana modal
				$('#modal_eliminar_comanda').click();
				
			// Recarga la comandera con una nueva comanda
				if ($objeto['tipo_operacion'] == 3) {
					console.log('======> Entra tipo operacion 3');
					comandera.mandar_mesa_comandera({
						id_mesa : $objeto['idmesa'],
						tipo : 0,
						id_comanda : ''
					});
				} else {
				// Quita la comanda de la mesa
					$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
					$('#mesa_' + $objeto['idmesa']).css('background-color', '#FFFFFF');
					$("#div_tiempo_" + $objeto['idmesa']).html('');
					$("#div_total_" + $objeto['idmesa']).html('');
	
				// Oculta la ventana modal
					$("#modal_comandera").click();
				}
			}).fail(function(resp) {
				console.log('=========> Fail mudar_comanda');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
		
				var $mensaje = 'Error al eliminar la comanda';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			});
		}).fail(function(resp) {
			console.log('=========> Fail mudar_comanda');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			var $mensaje = 'Error al autorizar la eliminacion';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
			
///////////////// ******** ---- 				FIN eliminar_comanda				------ ************ //////////////////

///////////////// ******** ---- 				guardar_promedio_comensal			------ ************ //////////////////
//////// Registra el promedio por comensal de la comanda
	// Como parametros puede recibir:
		// 	promedio -> promedio por comensal de la comanda a registrar
		//	comanda -> id de la comanda	
	
	guardar_promedio_comensal : function($objeto) {
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_promedio_comensal',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-----> Response promedio comensal');
			console.log(resp);

		}).fail(function(resp) {
			console.log('=========> Fail guardar_promedio_comensal');
			console.log(resp);

			var $mensaje = 'Error al guardar el promedio de por comensal';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN guardar_promedio_comensal			------ ************ //////////////////

///////////////// ******** ---- 					validar_cuenta					------ ************ //////////////////
//////// Valida que la cuenta tenga pedidos
	// Como parametros puede recibir:
		// 	id_comanda -> ID de la comanda
	
	validar_cuenta : function($objeto) {
		console.log('=========> $objeto validar_cuenta');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=validar_cuenta',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-----> Response validar_cuenta');
			console.log(resp);
		
		// Todo bien :D
			if(resp['status'] == 1){
				$('.GtableCloseComanda').css('visibility', 'visible');
				
				return 0;
			}
			
			var $mensaje = 'Necesitas agregar pedidos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('=========> Fail validar_cuenta');
			console.log(resp);

			var $mensaje = 'Error al validar la cuenta';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN guardar_promedio_comensal			------ ************ //////////////////

///////////////// ******** ---- 				autorizar_pedido					------ ************ //////////////////
//////// Obtiene la contraseña de seguridad y autoriza la modificacion del pedido
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar
		// pedido -> ID del pedido
		// json -> 1 -> devuelve un json
		
	autorizar_pedido: function($objeto) {
		console.log('--------> Objet autorizar_pedido');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=validar_pass',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Response autorizar_pedido');
			console.log(resp);

		// Todo bien :D
			if(resp['status'] == 1){
			// Habilita el boton para restar pedidos
				$('#btn_restar_' + $objeto['pedido']).attr("disabled", false);
				
			// Cierra la ventana de autoricacion
				$('#modal_autorizar_pedido').click();
				
			// Limpia el campo de pass
				$('#pass_pedido').val('');
				
				return 0;
			}
			
			var $mensaje = 'Contraseña incorrecta';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('=========> Fail autorizar_pedido');
			console.log(resp);

			var $mensaje = 'Error al autorizar';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

			
///////////////// ******** ---- 				FIN autorizar_pedido				------ ************ //////////////////

///////////////// ******** ---- 				actualizar_tiempo_pedidos			------ ************ //////////////////
//////// Actualiza el tiempo de los pedidos
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda
		// tiempo -> Tiempo del platillo
		// btn -> Boton de loader
		
	actualizar_tiempo_pedidos: function($objeto) {
		console.log('--------> Objet actualizar_tiempo_pedidos');
		console.log($objeto);

	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=actualizar_tiempo_pedidos',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Response actualizar_tiempo_pedidos');
			console.log(resp);
		
		// Aumenta el tiempo de los pedidos
			var tiempo = $('#tiempo').val();
			tiempo = parseInt(tiempo) + 1;
			$('#tiempo').val(tiempo);
			
		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Tiempo guardado';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('=========> Fail actualizar_tiempo_pedidos');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
			var $mensaje = 'Error al guardar los tiempos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

			
///////////////// ******** ---- 			FIN actualizar_tiempo_pedidos			------ ************ //////////////////

///////////////// ******** ---- 				llenar_campos						------ ************ //////////////////
//////// llena los campos del cliente
	// Como parametros recibe:
		
	llenar_campos : function($objeto) {
		console.log('=========> objeto llenar_campos');
		console.log($objeto);
	
	// Servicio a domicilio
		if($objeto['servicio_domicilio'] == 1){
			$('#editar_cliente_servicio_domicilio').val($objeto['nombre']);
			$('#editar_domicilio_servicio_domicilio').val($objeto['direccion']);
			$('#editar_via_contacto_domicilio').val($objeto['id_viacontacto']);
			$('#editar_zona_reparto').val($objeto['id_zonareparto']);
			$('#editar_tel_servicio_domicilio').val($objeto['tel']);
		}
		
	// Servicio a domicilio
		if($objeto['para_llevar'] == 1){
			$('#editar_cliente_para_llevar').val($objeto['nombre']);
			$('#editar_domicilio_para_llevar').val($objeto['direccion']);
			$('#editar_via_contacto_para_llevar').val($objeto['id_viacontacto']);
			$('#editar_zona_reparto_para_llevar').val($objeto['id_zonareparto']);
			$('#editar_tel_para_llevar').val($objeto['tel']);
		}
		
		$('.selectpicker').selectpicker('refresh');
	},
	
///////////////// ******** ---- 				FIN llenar_campos					------ ************ //////////////////
	
}; // Fin de la clase