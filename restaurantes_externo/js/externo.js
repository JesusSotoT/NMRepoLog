var externo = {
	datos_mesa : [],
	datos_mesa_comanda : {
		id_mesa: 0,
		id_comanda: 0,
		tipo: 0,
		tipo_operacion: 1
	},
	productos: '',
	departamentos : '',
	opcionales : [],
	extra : [],
	sin : [],
	htmlPromo : '',
	combos : [],
	promociones : [],
	pedidos_seleccionados : {},
	datos_combo : {},
	datos_promocion : {},
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
		// id_reservacion -> ID de la reservacion
		// personas -> numero de comensales
		// f_ini -> fecha inicio de la comanda
		// mesero -> Nombre del mesero
	
	cerrar_comanda : function($objeto) {	
		console.log('=========> objeto cerrar_comanda 1');
		console.log($objeto);

	
		
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
	
	// Oculta la div de pagar
		$(".GtableCloseComanda").css('visibility', 'hidden');
		//$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$("#div_ticket").html('');
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=cerrar_comanda',
			type : 'POST',
			dataType : 'html',
			async: false
		}).done(function(resp) {
			console.log('=========> Done cerrar_comanda 2');
			console.log(resp);
			
		// Quita el loader

			$btn.button('reset');
		// Ejecuta los scripts de la comanda
			//$("#div_ejecutar_scripts").html(resp);
				
			$("#div_ticket").html(resp);

		}).fail(function(resp) {
			console.log('=========> Fail cerrar_comanda');
			console.log(resp);

		// Activa las cosultas del status de las mesas
			comandas.detener = 0;
			
		// Quita el loader
			$btn.button('reset');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al cerrar la comanda';
			else
				var $mensaje = 'Error closing command';
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

///////////////// ******** ---- 				llamar_mesero				------ ************ //////////////////
//////// Modifica el campo de la mesa para crear una alerta
	// Como parametros puede recibir:
		// id_mesa -> ID de la mesa
		
	llamar_mesero : function($objeto) {
		console.log('--------- > objeto llamar_mesero');
		console.log($objeto);
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=llamar_mesero',
			type : 'GET',
			dataType : 'json'
		}).done(function(resp) {
			console.log('--------- > Done llamar_mesero');
			console.log(resp);
			
			$mensaje = 'El mesero vendra en un momento';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('--------- > Fail llamar_mesero');
			console.log(resp);
	
			$mensaje = 'Error al llamar al mesero';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN llamar_mesero			------ ************ //////////////////

///////////////// ******** ---- 					ver_menu				------ ************ //////////////////
//////// Muestra e imprime el menu
	// Como parametros puede recibir:
		// div -> ID de la div donde se cargara el menu
		
	ver_menu : function($objeto) {
		console.log('--------- > objeto ver_menu');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-4x fa-spin"></i></div>');
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=ver_menu',
			type : 'GET',
			dataType : 'html'
		}).done(function(resp) {
			console.log('--------- > Done ver_menu');
			console.log(resp);
		
		// Carga el contenido
			$("#"+$objeto['div']).html(resp);
		
		// Imprime el contenido
			//$("#"+$objeto['div']).printArea();
		}).fail(function(resp) {
			console.log('--------- > Fail ver_menu');
			console.log(resp);
	
			$mensaje = 'Error al ver el menu';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
			
			$("#"+$objeto['div']).html($mensaje);
		});
	},
	
///////////////// ******** ---- 				FIN llamar_mesero				------ ************ //////////////////

///////////////// ******** ---- 					save_pass				------ ************ //////////////////
//////// Muestra e imprime el menu
	// Como parametros puede recibir:
		// div -> ID de la div donde se cargara el menu
		
	save_pass : function($objeto) {
		console.log('--------- > objeto save_pass');
		console.log($objeto);
		
		if($("#pass_login").val() == ''){
			$("#pass_login").notify("Coloca contrase&ntilde;a.", {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			return 0;
		}
		if($("#pass_login_confirm").val() == ''){
			$("#pass_login_confirm").notify("Confirma contrase&ntilde;a.", {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			return 0;
		}
		if($("#pass_login").val() != $("#pass_login_confirm").val()){
			$("#pass_login").notify("Las contrase&ntilde;as no coinciden.", {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			return 0;
		}
		$.ajax({
			data : {id: $("#btn_save_pass").attr("id_mesa"), pass: $("#pass_login").val(), confirm: $("#pass_login_confirm").val()},
			url : 'ajax.php?c=externo&f=save_pass',
			type : 'GET',
			dataType : 'json'
		}).done(function(resp) {
			console.log('--------- > Done save_pass');
			console.log(resp);
					
			if(resp['status'] == 2){
				$mensaje = 'Error al guardar contrase&ntilde;a.';
				$("#pass_login").notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			} else{
				$.notify("Contrase&ntilde;a guardada", {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
				$("#row_todo").show();
				$("#modal_password").modal('hide');
				externo.mandar_mesa_comandera({
					id_mesa: externo.datos_mesa['id_mesa'],
					tipo: 0,
					tipo_mesa: 1,
					autoclick: 1,
					nombre_mesa_2: externo.datos_mesa['nombre'],
					id_comanda: externo.datos_mesa['id_comanda'],
					tipo_operacion: 1});
			}
		// Imprime el contenido
			//$("#"+$objeto['div']).printArea();
		}).fail(function(resp) {
			console.log('--------- > Fail save_pass');
			console.log(resp);
	
			$mensaje = 'Error al guardar contrase&ntilde;a.';
			$("#pass_login").notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN save_pass				------ ************ //////////////////

///////////////// ******** ---- 					ordenar				------ ************ //////////////////
//////// Muestra e imprime el menu
	// Como parametros puede recibir:
		// div -> ID de la div donde se cargara el menu
		
	ordenar : function($objeto) {
		console.log('--------- > objeto ordenar');
		console.log($objeto);
		
		if($("#pass_ordenar").val() == ''){
			$("#pass_ordenar").notify("Coloca contrase&ntilde;a.", {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			return 0;
		}
		$.ajax({
			data : {id: $("#pass_ordenar").attr("id_mesa"), pass: $("#pass_ordenar").val()},
			url : 'ajax.php?c=externo&f=ordenar',
			type : 'GET',
			dataType : 'json'
		}).done(function(resp) {
			console.log('--------- > Done ordenar');
			console.log(resp);
					
			if(resp['status'] == 2){
				$mensaje = 'Contrase&ntilde;a incorrecta.';
				$("#pass_ordenar").notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			} else {
				$.notify("Contrase&ntilde;a correcta", {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
				$("#modal_login_ordenar").modal('hide');
				externo.mandar_mesa_comandera({
					id_mesa: externo.datos_mesa['id_mesa'],
					tipo: 0,
					tipo_mesa: 1,
					nombre_mesa_2: externo.datos_mesa['nombre'],
					id_comanda: externo.datos_mesa['id_comanda'],
					tipo_operacion: 1});
			}
		// Imprime el contenido
			//$("#"+$objeto['div']).printArea();
		}).fail(function(resp) {
			console.log('--------- > Fail ordenar');
			console.log(resp);
	
			$mensaje = 'Error al iniciar sesion.';
			$("#pass_ordenar").notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN ordenar				------ ************ //////////////////

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
		if(!$objeto['autoclick']){
			$("#modal_comandera").modal();
		}
			$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=mandar_mesa_comandera',
			type : 'POST',
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
		
			// Oculta la ventana modal
				$("#modal_comandera").click();

				return 0;
			}
			
		// LLena los campos
			$("#comanda_text").html(resp['id_comanda']);
			$("#texto").val('');
			$("#borrar_persona").val(resp['num_personas']);
			$("#mesa_text").html($objeto['nombre_mesa_2']);
			$("#num_comensales_comandera").val(resp['info_mesa']['comensales']);
			$('#tiempo').val(1);
			$("#mesero_" + $objeto['id_mesa']).html(resp['mesero']);
			
		// Oculta la modal de pago si esta abierta
			$(".GtableCloseComanda").css('visibility', 'hidden');
			
		// Guarda los datos de la comanda
			externo.datos_mesa_comanda = resp;
			
		// Carga la vista de las personas
			var $datos = {};
			$datos['div'] = 'div_personas';
			$datos['num_personas'] = resp['num_personas'];
			$datos['personas'] = resp['personas'];
			$datos['id_comanda'] = resp['id_comanda'];
			externo.vista_personas($datos);
			externo.productos = ''; 
				externo.area_inicio();
		}).fail(function(resp) {
			console.log('=========> Fail mandar_mesa_comandera');
			console.log(resp);
			$("#modal_comandera").click();
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
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=vista_personas',
			type : 'POST',
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

///////////////// ******** ---- 				area_inicio						------ ************ //////////////////
//////// Consulta los departamentos y los productos y los agrega a sus divs
	// Como parametros recibe:
	
	area_inicio : function($objeto) {
		console.log('=========> objeto area_inicio');
		console.log($objeto);
	
	// Si no existen los productos los consulta, si existen los agrega a la div
		if (externo.productos == '') {
			externo.buscar_productos({
				texto: '',
				comanda : externo['datos_mesa_comanda']['id_comanda'],
				div : 'div_productos'
			});
		} else{
			$('#div_productos').html(externo.productos);
		}
		
	// Agrega los departamentos a la div
		if(externo.departamentos){
			$('#div_departamentos').html(externo.departamentos);
		}
	},
	
///////////////// ******** ---- 			FIN area_inicio					------ ************ //////////////////

///////////////// ******** ---- 		buscar_productos		------ ************ //////////////////
//////// Consulta los productos que coincidan con el texto a buscar y los agrega a la div
	// Como parametros recibe:
		// texto -> palabra u oracion a buscar en los productos
		// div -> div donde se cargaran los resultados
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// familia -> ID de la familia
		// linea -> id de la linea
		// vista -> Vista que se debe de cargar
		// limite -> Limite de productos a cargar

	buscar_productos : function($objeto) {
		console.log('------> Objeto buscar_productos');
		console.log($objeto);
	// Loader en el boton OK
		if($objeto['btn']){
			var $btn = $('#'+$objeto['btn']);
			$btn.button('loading');
		}
		
	// Formatea el campo de limite y establece el nuevo limite
		if($objeto['limite']){
			var $limite = parseInt($objeto['limite']) + 100;
			$("#limite").val($limite);
		}else{
			$("#limite").val(100);
		}
		
		console.log('------> datos antes ajax buscar_productos');
		console.log($objeto);
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=buscar_productos',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------> Done buscar_productos');
			// console.log(resp);
		
		// Quita el loader
			if($objeto['btn']){
				$btn.button('reset');
			}
		
		// Valida si se deben de agregar los productos o cargar toda la vista
			if($objeto['limite']){
				$('#' + $objeto['div']).append(resp);
			}else{
				$('#' + $objeto['div']).html(resp);
				$("#limite").val(100);
			}
		}).fail(function(resp) {
			console.log('------> Fail buscar_productos');
			console.log(resp);
			
		// Quita el loader
			if($objeto['btn']){
				$btn.button('reset');
			}
				var $mensaje = 'Error al obtener los productos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 		FIN buscar_productos		------ ************ //////////////////

///////////////// ******** ---- 				vista_comandera					------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_comandera : function($objeto) {
		console.log('=========> objeto vista_comandera');
		console.log($objeto);
		
			
	// Loading
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=vista_comandera',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_comandera');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
			
			externo.departamentos = $("#div_departamentos").html();
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
		// combo 1 -> Es un producto de un combo
	
	detalles_producto : function($objeto) {
		console.log('=========> objeto detalles_producto');
		console.log($objeto);

	// Guarda el HTML de los productos en una variable si no se ha guardado
		if(externo.productos == ''){
			externo.productos = $("#div_productos").html();
		}
		externo.opcionales = [];
		externo.sin = [];
		externo.extra = [];
		externo.htmlPromo = $("#div_promocion").html();
		if($objeto.combo == 1){
			if(externo.datos_combo.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				
					var $mensaje = 'No puedes seleccionar mas productos';
				
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});
				
				return;
			}
		}
		if($objeto.promocion == 1 && $objeto.tipo_promocion == 3 || $objeto.promocion == 1 && $objeto.tipo_promocion == 5) {
			if(externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				
					var $mensaje = 'No puedes seleccionar mas productos 2';
				
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});
				
				return;
			}
		}
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=detalles_producto_2',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done detalles_producto');
			console.log(resp);
		
		// Si es combo cambia el numero de productos seleccionados
			if($objeto.combo == 1){
			// Ejecuta los scripts del combo
				$("#div_ejecutar_scripts").html(resp);
			// Cambia el numero de seleccionados
				$('#cantidad_grupo_'+$objeto.grupo).html(externo.datos_combo.grupos[$objeto.grupo].num_seleccionados);
			
			// Carga la vista si son materiales
				if($objeto.materiales == 1){
				// Carga la vista a la div
					$('#' + $objeto['div']).html(resp);
				}
			} else if($objeto.promocion == 1 && $objeto.tipo_promocion == 3 || $objeto.promocion == 1 && $objeto.tipo_promocion == 5) {
				console.log(externo.datos_promocion.grupos[$objeto.grupo]);
			// Ejecuta los scripts del promociones
				$("#div_ejecutar_scripts").html(resp);
			// Cambia el numero de seleccionados
				$('#cantidad_grupo_'+$objeto.grupo).html(externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados);
			
			// Carga la vista si son materiales
				if($objeto.materiales == 1){
				// Carga la vista a la div
					$('#' + $objeto['div']).html(resp);
				}
			}else{
			// Carga la vista a la div
				$('#' + $objeto['div']).html(resp);
			}
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
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=guardar_pedido',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_pedido');
			console.log(resp);
		
		// Selecciona el pedido(nos sirve al momento de querer agregar complementos)
			externo.datos_mesa_comanda['pedido_seleccionado'] = resp;
			
		// Quita el loader
			$btn.button('reset');
			
		// Carga los pedidos de la persona
			externo.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		
		// Carga los productos
			$("#div_productos").html(externo.productos);
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
		
		
		if (!$objeto['persona']) {
				var $mensaje = 'La comanda ya no existe';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 10000,
				className : 'warn',
				arrowSize : 15
			});
		

		// Limpia los datos de la comanda
			datos_mesa_comanda = {};

			return 0;

		}
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_pedidos_persona',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_pedidos_persona');
			console.log(resp);
		
		// Guarda la persona seleccionada
			externo.datos_mesa_comanda['persona_seleccionada'] = $objeto['persona'];
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		
		// Cambia el la persona para cerrar la comanda por persona
			$('#text_cerrar_persona').html($objeto['persona']);
			$('#borrar_persona').val($objeto['persona']);
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
			url : 'ajax.php?c=externo&f=agregar_persona_comandera',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done agregar_persona_comandera');
			console.log(resp);
			
			var $datos = {};
			$datos['div'] = 'div_personas';
			$datos['num_personas'] = $objeto['num_personas'];
			$datos['personas'] = resp['personas'];
			$datos['id_comanda'] = $objeto['id_comanda'];
			externo.vista_personas($datos);
		
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

///////////////// ******** ---- 					listar_combos						------ ************ //////////////////
//////// Carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// tipo -> 7 -> combo
		// persona -> ID de la personas seleccionada
		
	listar_combos : function($objeto) {
		console.log('------------> $objeto listar_combos');
		console.log($objeto);
		d = new Date();
		var dias = new Array('0','1','2','3','4','5','6')

		datetext = d.toTimeString();
		datetext =  datetext.split(' ')[0];
		$objeto['hour'] =  datetext.split(':')[0]+':'+datetext.split(':')[1];
		$objeto['day'] = dias[d.getDay()];
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_combos',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_combos');
			console.log(resp);
	    		
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_combos');
			console.log(resp);
		
		// Quita el loader
			$('#' + $objeto['div']).html('Error al obtener los combos');
				var $mensaje = 'Error al obtener los combos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN listar_combos					------ ************ //////////////////

///////////////// ******** ---- 			listar_productos_combo					------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		// combo -> Array con los datos del combo
			
	listar_productos_combo : function($objeto) {
		console.log('------------> $objeto listar_productos_combo');
		console.log($objeto);
	// Loader
		$('#' + $objeto.div).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_productos_combo',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_productos_combo');
			console.log(resp);
	    
	    // Inicializamos variables del combo
	    	externo.pedidos_seleccionados = {};
	    	externo.datos_combo = $objeto.combo;
	    	externo.combos = [];
	    	
			$('#' + $objeto.div).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_combo');
			console.log(resp);
				var $mensaje = 'Error al obtener los productos del combo';
		// Loader
			$('#' + $objeto.div).html($mensaje);
		
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN listar_productos_combo			------ ************ //////////////////

///////////////// ******** ---- 				reiniciar_grupo					------ ************ //////////////////
//////// Carga el HTML original del grupo
	// Como parametros recibe:
		// grupo -> ID del grupo
		// div -> Div donde se carga el contenido
		
	reiniciar_grupo : function($objeto) {
		console.log('=========> objeto reiniciar_grupo');
		console.log($objeto);
		
		console.log('=========> Combos');
		console.log(externo['combos']);
		
	// Busca el grupo y carga el HTML de ese grupo
		$.each(externo['combos'], function(key, val) {
			if ($objeto['grupo'] == val['grupo']) {
				$("#" + $objeto['div']).html(val['html']);
				$("#cantidad_grupo_" + $objeto.grupo).html(0);
			
			// Elimina el grupo del array de los productos seleccionados
				delete externo.pedidos_seleccionados[$objeto.grupo];
				externo.datos_combo.grupos[$objeto.grupo].num_seleccionados = 0;
				
				console.log('=========> pedidos_seleccionados');
				console.log(externo.pedidos_seleccionados);
			}
		});
	},
	
///////////////// ******** ---- 				FIN reiniciar_grupo				------ ************ //////////////////


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
		
		var opcionales = externo.opcionales;
		var extras = externo.extra;
		var sin = externo.sin;
		
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
	
	// Guarda el pedido seleccionado en un array
		if($objeto.combo == 1){
		// Actualiza el numero de pedidos seleccionados
			var num = 0;
			if(!externo.datos_combo.grupos[$objeto.grupo].num_seleccionados){
				num = externo.datos_combo.grupos[$objeto.grupo].num_seleccionados = 1;
			}else{
				externo.datos_combo.grupos[$objeto.grupo].num_seleccionados ++;
				num = externo.datos_combo.grupos[$objeto.grupo].num_seleccionados;
			}
			$('#cantidad_grupo_'+$objeto.grupo).html(num);
		
		// Agrega el pedido a los pedidos seleccioandos del combo
			externo.seleccionar_pedido($objeto);
		
		// Si no se han seleccionado todos los productos carga el html
			if(externo.datos_combo.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>Grupo completo</b>');
			}else{
			// Busca el grupo y carga el HTML de ese grupo
				$.each(externo.combos, function(key, val) {
					if ($objeto.grupo == val.grupo) {
						$("#" + $objeto.div).html(val.html);
					}
				});
			}
	// Guarda el pedido de la persona normalmente
		} else if($objeto.promocion == 1){
		// Actualiza el numero de pedidos seleccionados
			if($objeto['tipo_promocion'] == 1 || $objeto['tipo_promocion'] == 2 || $objeto['tipo_promocion'] == 4) {
				if(!externo.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados){
					externo.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados = 0;
				}
				
				externo.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados ++;
				externo.seleccionar_pedido($objeto);
				console.log(externo.datos_promocion);
				$("#div_promocion").html(externo.htmlPromo);
			} else if($objeto['tipo_promocion'] == 3 || $objeto['tipo_promocion'] == 5) {
				// Actualiza el numero de pedidos seleccionados
				var num = 0;
				if(!externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados){
					num = externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados = 1;
				}else{
					externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados ++;
					num = externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados;
				}
				$('#cantidad_grupo_'+$objeto.grupo).html(num);
			
			// Agrega el pedido a los pedidos seleccioandos del combo
				externo.seleccionar_pedido($objeto);
			
			// Si no se han seleccionado todos los productos carga el html
				if(externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
					if($objeto['tipo_promocion'] == 3){
						$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>Promocion completo</b>');
					} else {
						$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>'+$objeto.grupo+' completo</b>');
					}
				}else{
				// Busca el grupo y carga el HTML de ese grupo
					$.each(externo.promociones, function(key, val) {
						if ($objeto.grupo == val.grupo) {
							$("#" + $objeto.div).html(val.html);
						}
					});
				}
			}


	// Guarda el pedido de la persona normalmente
		}else{
			externo.guardar_pedido($objeto);
		}
	},

///////////////// ******** ---- 			FIN guardar_detalles_pedido			------ ************ //////////////////

///////////////// ******** ---- 				seleccionar_pedido				------ ************ //////////////////
//////// Guarda el pedido en un array
	// Como parametros recibe:
		// combo -> ID del combo
		// departamento -> ID del departamento
		// extras -> extras del producto
		// grupo -> Grupo donde 
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// materiales -> 1 -> tiene insumos, 0 -> no
		// nombre -> Nombre del producto
		// nota_extra -> Nota de los insumos extras
		// nota_opcional -> Nota de los insumos opcionales
		// nota_sin -> Nota de los insumos sin
		// opcionales -> Cadena con los IDs de los productos opcionales
		// persona -> No. de persona
		// sin -> Cadena con los IDs de los productos sin
		// tipo -> Tipo de producto
		// cantidad -> Cantidad del productos o de productos en el caso del combo
		
	seleccionar_pedido : function($objeto) {
		console.log('=========> objeto seleccionar_pedido');
		console.log($objeto);
		
	// Array con los pedidos seleccionados
		var datos = externo.pedidos_seleccionados;
		console.log("lengthw: "+Object.keys(externo.pedidos_seleccionados).length);
	// Si no existe el grupo en el array lo crea
		if(!datos[$objeto.grupo])
			datos[$objeto.grupo] = {};
		if(!datos[$objeto.grupo][$objeto.id_producto])
			datos[$objeto.grupo][$objeto.id_producto] = {};
	// Agrega el producto al grupo del array
		datos[$objeto.grupo][$objeto.id_producto][Object.keys(datos[$objeto.grupo][$objeto.id_producto]).length] = $objeto;
		
		
		
	// Guarda el array modificado
		externo.pedidos_seleccionados = datos;
		
		console.log('=========> pedidos_seleccionados');
		console.log(externo.pedidos_seleccionados[$objeto.grupo]);
		var total = 0;
		$.each(externo.pedidos_seleccionados[$objeto.grupo], function(index, val) {
			$.each(val, function(index2, val2) {
				total ++;
			});
		});
		$("#title-promo").html('Productos seleccionados: '+total);
	},
	
///////////////// ******** ---- 				FIN seleccionar_pedido				------ ************ //////////////////

///////////////// ******** ---- 				guardar_combo						------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:

	guardar_combo : function($objeto) {
		console.log('===============> objeto guardar_combo');
		console.log($objeto);
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=guardar_combo',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done guardar_combo');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
		// Error
			if (resp['status'] == 2) {
					var $mensaje = 'Selecciona todos los productos';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
			
				return 0;
			}
			
		// Cierra la ventana modal
			$("#modal_combo").click();
			
		// Carga los pedidos de la persona
			externo.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['datos_combo']['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail guardar_combo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
				var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN guardar_combo						------ ************ //////////////////

///////////////// ******** ---- 				listar_complementos					------ ************ //////////////////
//////// Carga la vista de los complementos
	// Como parametros recibe:
		// div -> Div donde se debe de cargar el contenido
		// pedido -> Pedido seleccionado
		
	listar_complementos : function($objeto) {
		console.log('===============> objeto listar_complementos');
		console.log($objeto);
		
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_complementos',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_complementos');
			console.log(resp);
			
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('================= Fail listar_complementos');
			console.log(resp);
			
				var $mensaje = 'Error al mostrar los complementos';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN listar_complementos						------ ************ //////////////////

///////////////// ******** ---- 				agregar_complemento						------ ************ //////////////////
//////// Agregar un complemento
	// Como parametros recibe:
		// complemento -> ID del producto 
		// pedido -> ID del pedido
		
	agregar_complemento : function($objeto) {
		console.log('===============> objeto agregar_complemento');
		console.log($objeto);
	
	// Valida que se seleccione un producto
		if(!$objeto.pedido){
				var $mensaje = 'Debes agregar un producto';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			
			return 0;
		}
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=agregar_complemento',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done agregar_complemento');
			console.log(resp);
			
		// Carga los pedidos de la persona
			externo.listar_pedidos_persona({
				persona: externo.datos_mesa_comanda['persona_seleccionada'], 
				id_comanda: externo.datos_mesa_comanda['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail agregar_complemento');
			console.log(resp);
			
				var $mensaje = 'Error al guardar el complemento';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_complemento						------ ************ //////////////////

///////////////// ******** ---- 					listar_combos						------ ************ //////////////////
//////// Carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// tipo -> 7 -> combo
		// persona -> ID de la personas seleccionada
		
	listar_promociones : function($objeto) {
		console.log('------------> $objeto listar_promociones');
		console.log($objeto);
		d = new Date();
		var dias = new Array('0','1','2','3','4','5','6')

		datetext = d.toTimeString();
		datetext =  datetext.split(' ')[0];
		$objeto['hour'] =  datetext.split(':')[0]+':'+datetext.split(':')[1];
		$objeto['day'] = dias[d.getDay()];
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_promociones',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_promociones');
			console.log(resp);
	    
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_promociones');
			console.log(resp);
		
		// Quita el loader
			$('#' + $objeto['div']).html('Error al obtener las promciones');
				var $mensaje = 'Error al obtener las promciones';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN listar_promociones					------ ************ //////////////////

///////////////// ******** ---- 			listar_productos_promociones					------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		// combo -> Array con los datos del combo
			
	listar_productos_promociones : function($objeto) {
		console.log('------------> $objeto listar_productos_promociones');
		console.log($objeto);
	// Loader
		$('#' + $objeto.div).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=listar_productos_promociones',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_productos_promociones');
			console.log(resp);
	    
	    // Inicializamos variables del combo
	    	externo.pedidos_seleccionados = {};
	    	externo.datos_promocion = $objeto.promocion;
	    	externo.promociones = [];

	    	console.log(externo.datos_promocion);
	    	
			$('#' + $objeto.div).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_promociones');
			console.log(resp);
				var $mensaje = 'Error al obtener los productos de la promocion';
		// Loader
			$('#' + $objeto.div).html($mensaje);
		
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN listar_productos_combo			------ ************ //////////////////

///////////////// ******** ---- 				reiniciar_grupo_promociones					------ ************ //////////////////
//////// Carga el HTML original del grupo
	// Como parametros recibe:
		// grupo -> ID del grupo
		// div -> Div donde se carga el contenido
		
	reiniciar_grupo_promociones : function($objeto) {
		console.log('=========> objeto reiniciar_grupo_promociones');
		console.log($objeto);
		
		console.log('=========> Promociones');
		console.log(externo['promociones']);
		
	// Busca el grupo y carga el HTML de ese grupo
		$.each(externo['promociones'], function(key, val) {
			if ($objeto['grupo'] == val['grupo']) {
				$("#" + $objeto['div']).html(val['html']);
				$("#cantidad_grupo_" + $objeto.grupo).html(0);
			
			// Elimina el grupo del array de los productos seleccionados
				delete externo.pedidos_seleccionados[$objeto.grupo];
				externo.datos_promocion.grupos[$objeto.grupo].num_seleccionados = 0;
				
				console.log('=========> pedidos_seleccionados');
				console.log(externo.pedidos_seleccionados);
			}
		});
	},
	
///////////////// ******** ---- 				FIN reiniciar_grupo_promociones				------ ************ //////////////////

///////////////// ******** ---- 				guardar_promocion						------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:

	guardar_promocion : function($objeto) {
		console.log('===============> objeto guardar_promocion');
		console.log($objeto);

	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=guardar_promocion',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done guardar_promocion');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
		// Error
			if (resp['status'] == 2) {
					var $mensaje = 'Selecciona todos los productos';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
			
				return 0;
			}
			
		// Cierra la ventana modal
			$("#modal_promocion").click();
			
		// Carga los pedidos de la persona
			externo.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['datos_promocion']['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail guardar_promocion');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
				var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN guardar_promocion						------ ************ //////////////////
seleccionar_opc : function($objeto) {
		console.log('=========> objeto seleccionar_opc');
		console.log($objeto);
		if($objeto['grupo'] == 1){
			if(externo.sin.includes($objeto['id_producto'])){
				console.log("existe");
				externo.sin.splice(externo.sin.indexOf($objeto['id_producto']), 1);
				$('#btn_sin_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_sin_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				externo.sin.push($objeto['id_producto']);
				$('#btn_sin_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_sin_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> sin');
			console.log(externo.sin);
		}
		if($objeto['grupo'] == 2){
			if(externo.extra.includes($objeto['id_producto'])){
				console.log("existe");
				externo.extra.splice(externo.extra.indexOf($objeto['id_producto']), 1);
				$('#btn_extra_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_extra_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				externo.extra.push($objeto['id_producto']);
				$('#btn_extra_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_extra_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> extra');
			console.log(externo.extra);
		}
		if($objeto['grupo'] == 3){
			if(externo.opcionales.includes($objeto['id_producto'])){
				console.log("existe");
				externo.opcionales.splice(externo.opcionales.indexOf($objeto['id_producto']), 1);
				$('#btn_opcional_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_opcional_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				externo.opcionales.push($objeto['id_producto']);
				$('#btn_opcional_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_opcional_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> opcionales');
			console.log(externo.opcionales);
		}
		
	},
	reiniciar_opcionales : function($objeto) {
		console.log('=========> objeto reiniciar_opcionales');
		console.log($objeto);
		
		if($objeto['grupo'] == 1) {
			externo.sin = [];
			$('.btn_sin').removeClass('btn-info');
			$('.btn_sin').addClass('btn-default');
			$('#nota_sin').val('');
		}
		if($objeto['grupo'] == 2) {
			externo.extra = [];
			$('.btn_extra').removeClass('btn-info');
			$('.btn_extra').addClass('btn-default');
			$('#nota_extra').val('');
		}
		if($objeto['grupo'] == 3) {
			externo.opcionales = [];
			$('.btn_opcional').removeClass('btn-info');
			$('.btn_opcional').addClass('btn-default');
			$('#nota_opcional').val('');
		}
	},

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
			url : 'ajax.php?c=externo&f=listar_familias',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_familias');
			console.log(resp);
		
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			externo.buscar_productos({
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
			url : 'ajax.php?c=externo&f=listar_lineas',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_lineas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			externo.buscar_productos({
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

///////////////// ******** ----  				pedir						------ ************ //////////////////
//////// Manda el pedido de la comanda a las areas correspondientes
	// Como parametro puede recibir:
		// cerrar_comanda -> 1 cierra la modal, 0 -> permanece en la modal 
		// id_comanda -> ID de la comanda

	pedir : function($objeto) {
		console.log('=========> objeto pedir');
		console.log($objeto);
		$objeto['sucursal'] = externo.datos_mesa['idSuc'];
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=pedir',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> Done pedir');
			console.log(resp);
			
	    // Inicializamos variables del combo
	    	externo.pedidos_seleccionados = {};
	    	externo.datos_combo = {};
	    	externo.combos = [];
	    	
	    	externo.listar_pedidos_persona({
				persona: externo.datos_mesa_comanda['persona_seleccionada'], 
				id_comanda: externo.datos_mesa_comanda['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
			
		// Redirecciona solo si no es Fast food
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
			url : 'ajax.php?c=externo&f=eliminar_pedido',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done eliminar_pedido');
			console.log(resp);
		
			externo.listar_pedidos_persona({
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

///////////////// ******** ---- 			eliminar_complemento						------ ************ //////////////////
//////// Elimina el complemento del pedido
	// Como parametros recibe:
		// id_pedido -> ID del pedido
		// id_complemento -> ID del complemento
		
	eliminar_complemento : function($objeto) {
		console.log('===============> objeto eliminar_complemento');
		console.log($objeto);
		
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=externo&f=eliminar_complemento',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done eliminar_complemento');
			console.log(resp);
			
		// Carga los pedidos de la persona
			externo.listar_pedidos_persona({
				persona: externo.datos_mesa_comanda['persona_seleccionada'], 
				id_comanda: externo.datos_mesa_comanda['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail eliminar_complemento');
			console.log(resp);
			
				var $mensaje = 'Error al eliminar el complemento';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_complemento						------ ************ //////////////////
}; // Fin clase
