var comandera = {
// Inicializamos variables
	datos_mesa_comanda : {
		id_mesa: 0,
		id_comanda: 0,
		tipo: 0,
		tipo_operacion: 1,
		nombre:'',
		mesaTipo: 0,
		idempleado: 0
	},
	mapa_mesas: {
		mesero: {
			id: 0,
			permisos: '',
			mesas: []
		}
	},
	htmlPromo : '',
	ids_mesas : [],
	mesas_juntas: {},
	ajustes: {},
	combos : [],
	promociones : [],
	pedidos_seleccionados : {},
	datos_combo : {},
	datos_promocion : {},
	productos: '',
	idioma : 0,
	pedido_merma: '',
	departamentos : '',
	opcionales : [],
	extra : [],
	sin : [],

	

///////////////// ******** ---- 				vista_comandera					------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_comandera : function($objeto) {
		console.log('=========> objeto vista_comandera');
		console.log($objeto);
		
		if(comandera.idioma == 0)
			comandera.get_idioma();	
			
	// Loading
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_comandera',
			type : 'POST',
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
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al cargar la comandera';
			else
				var $mensaje = 'Error loading command';
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

///////////////// ******** ---- 				vista_mudar_mesa			------ ************ //////////////////
//////// Carga la vista de las mesas a eliminar
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_mudar_mesa : function($objeto) {
		console.log('=========> objeto vista_eliminar_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_mudar_mesa',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_mudar_mesa');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail vista_mudar_mesa');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar las mesas');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al cargar las mesas';
			else
				var $mensaje = 'Error loading tables';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN vista_mudar_mesa			------ ************ //////////////////

///////////////// ******** ---- 			mandar_mesa_comandera				------ ************ //////////////////
//////// Consulta los datos de la mesa y los devuelve en un array
	// Como parametros recibe:
		// id_mesa -> ID de la mesa
		// tipo -> Tipo de mesa
		// id_comanda -> ID de la comanda
		// tipo_operacion -> Tipo de operacion del restaurante
	
	mandar_mesa_comandera : function($objeto) {		
		$('#div_productos').hide();
		//$('.btn').prop( "disabled", true );
		//$('.act').prop( "disabled", false );		
		console.log('=========> objeto mandar_mesa_comandera');
		console.log($objeto);
	//alert($objeto['id_comanda']);
		if($("#mesa_"+$objeto['id_mesa']).attr('mesa_status') == 4){
			console.log("leloooooo");
			if(comandas.idioma == 1)
				var $mensaje = 'Mesa bloqueada imposible abrirla';
			else
				var $mensaje = 'Locked table impossible to open';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		$("#modal_comandera").modal();
		if(comandera.idioma == 0)
			comandera.get_idioma();	
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=mandar_mesa_comandera',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done mandar_mesa_comandera');
			console.log(resp);
		
		// Valia que exista una comanda abierta, si no crea una
			if(!resp['info_mesa']){
				if(comandera.idioma == 1)
					var $mensaje = 'La comanda ya no existe';
				else
					var $mensaje = 'The order no longer exists';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 10000,
					className : 'warn',
					arrowSize : 15
				});
			
			// Quita la comanda de la mesa
				$("#mesa_" + $objeto['id_mesa']).attr('id_comanda', '');
				if(resp['separar'] != 1){
					if($objeto['tipo_mesa'] == 1)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/images/mapademesas/libre_cuadrada_2p.png");
					else if($objeto['tipo_mesa'] == 2)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
					else if($objeto['tipo_mesa'] == 3)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
					else if($objeto['tipo_mesa'] == 4)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
					else if($objeto['tipo_mesa'] == 5)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
					else if($objeto['tipo_mesa'] == 6)
						$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/sillones.png");
					else if($objeto['tipo_mesa'] == 9)
						$('#silla_' + $objeto['id_mesa']).css("background-color", "#423228");
				} else {
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_juntadas.png");
				}
				$("#div_tiempo_" + $objeto['id_mesa']).html('');
				$("#div_total_" + $objeto['id_mesa']).html('');
				$('#tiempo').val(1);
		
			// Oculta la ventana modal
				$("#modal_comandera").click();

				return 0;
			}
			
		// LLena los campos
			$("#comanda_text").html(resp['id_comanda']);
			$("#mesa_text").html(resp['info_mesa']['nombre_mesa']);
			$("#borrar_persona").val(resp['num_personas']);
			$("#mesa_" + $objeto['id_mesa']).attr('id_comanda', resp['id_comanda']);
			if(resp['separar'] != 1){
				if($objeto['tipo_mesa'] == 1)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/ocupada_cuadrada_2p.png");
				else if($objeto['tipo_mesa'] == 2)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/ocupada_cuadrada.png");
				else if($objeto['tipo_mesa'] == 3)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/rectangulo_2p_ocupada.png");
				else if($objeto['tipo_mesa'] == 4)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/ocupada_redonda_4p.png");
				else if($objeto['tipo_mesa'] == 5)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/ocupada_redonda_2p.png");
				else if($objeto['tipo_mesa'] == 6)
					$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/sillon_ocupado.png");
				else if($objeto['tipo_mesa'] == 9)
					$('#silla_' + $objeto['id_mesa']).css("background-color", "#6b4583");
			} else {
				$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/ocupada_juntadas.png");
			}
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
			comandera.productos = ''; 
				comandera.area_inicio();
				
		}).fail(function(resp) {
			console.log('=========> Fail mandar_mesa_comandera');
			console.log(resp);
			if(comandera.idioma == 1)
				var $mensaje = 'Error al cargar la comandera';
			else
				var $mensaje = 'Error loading command';
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
	seleccionar_opc : function($objeto) {
		console.log('=========> objeto seleccionar_opc');
		console.log($objeto);
		if($objeto['grupo'] == 1){
			if(comandera.sin.includes($objeto['id_producto'])){
				console.log("existe");
				comandera.sin.splice(comandera.sin.indexOf($objeto['id_producto']), 1);
				$('#btn_sin_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_sin_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				comandera.sin.push($objeto['id_producto']);
				$('#btn_sin_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_sin_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> sin');
			console.log(comandera.sin);
		}
		if($objeto['grupo'] == 2){
			if(comandera.extra.includes($objeto['id_producto'])){
				console.log("existe");
				comandera.extra.splice(comandera.extra.indexOf($objeto['id_producto']), 1);
				$('#btn_extra_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_extra_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				comandera.extra.push($objeto['id_producto']);
				$('#btn_extra_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_extra_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> extra');
			console.log(comandera.extra);
		}
		if($objeto['grupo'] == 3){
			if(comandera.opcionales.includes($objeto['id_producto'])){
				console.log("existe");
				comandera.opcionales.splice(comandera.opcionales.indexOf($objeto['id_producto']), 1);
				$('#btn_opcional_' + $objeto['id_producto']).removeClass('btn-info');
				$('#btn_opcional_' + $objeto['id_producto']).addClass('btn-default');
			}else{
				comandera.opcionales.push($objeto['id_producto']);
				$('#btn_opcional_' + $objeto['id_producto']).removeClass('btn-default');
				$('#btn_opcional_' + $objeto['id_producto']).addClass('btn-info');
			}
			console.log('=========> opcionales');
			console.log(comandera.opcionales);
		}
		
	},
	reiniciar_opcionales : function($objeto) {
		console.log('=========> objeto reiniciar_opcionales');
		console.log($objeto);
		
		if($objeto['grupo'] == 1) {
			comandera.sin = [];
			$('.btn_sin').removeClass('btn-info');
			$('.btn_sin').addClass('btn-default');
			$('#nota_sin').val('');
		}
		if($objeto['grupo'] == 2) {
			comandera.extra = [];
			$('.btn_extra').removeClass('btn-info');
			$('.btn_extra').addClass('btn-default');
			$('#nota_extra').val('');
		}
		if($objeto['grupo'] == 3) {
			comandera.opcionales = [];
			$('.btn_opcional').removeClass('btn-info');
			$('.btn_opcional').addClass('btn-default');
			$('#nota_opcional').val('');
		}
	},
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Limpia la div de los pedidos
		$("#div_listar_pedidos_persona").html('<div align="center"><h3><span class="label label-default">Agrega una persona</span></h3></div>');
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_personas',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_personas');
			//console.log(resp);
		
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

			if(comandera.idioma == 1)
				var $mensaje = 'Error al cargar las personas';
			else
				var $mensaje = 'Error loading people';
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
		/*
		* Se comento el metodo de ocultado
		* del elemento que contiene los productos
		* ya que hacia un retardo en las peticiones.
		*/

		//$("#div_productos").hide();
		//$('.btn').prop( "disabled", true );
		//$('.act').prop( "disabled", false );	
		console.log('=========> objeto listar_pedidos_persona');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();

		/*
			* Se elimina el spin de carga de productos
			* lo cual agiliza la carga de productos pedidos
			* dentro de la vista de comandera.
		*/

		//$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		
		if (!$objeto['persona']) {
			if (comandera.idioma == 1)
				var $mensaje = 'La comanda ya no existe';
			else
				var $mensaje = 'The order no longer exists';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 10000,
				className : 'warn',
				arrowSize : 15
			});
		
		// Activa las cosultas del status de las mesas
			comandas.detener = 0;

		// Limpia los datos de la comanda
			datos_mesa_comanda = {};

			return 0;

		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_pedidos_persona',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done listar_pedidos_persona');
			console.log(resp);

			// Oculta el modal de descuentos
			$("#modalDescParcial").modal('hide');
		
		// Guarda la persona seleccionada
			comandera.datos_mesa_comanda['persona_seleccionada'] = $objeto['persona'];
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		
		// Cambia el la persona para cerrar la comanda por persona
			$('#text_cerrar_persona').html($objeto['persona']);
			$('#borrar_persona').val($objeto['persona']);

			//$('.btn').prop( "disabled", false );

			$("#div_productos").show();
		}).fail(function(resp) {
			console.log('=========> Fail listar_pedidos_persona');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar los pedidos de la persona');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al cargar los pedidos de la persona';
			else
				var $mensaje = "Error loading person's orders";
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
			//$('.btn').prop( "disabled", false );
			$("#div_productos").show();
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
		// combo 1 -> Es un producto de un combo
	
	detalles_producto : function($objeto) {
		console.log('=========> objeto detalles_producto');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();	

	// Guarda el HTML de los productos en una variable si no se ha guardado
		if(comandera.productos == ''){
			comandera.productos = $("#div_productos").html();
		}
		comandera.opcionales = [];
		comandera.sin = [];
		comandera.extra = [];
		comandera.htmlPromo = $("#div_promocion").html();
		if($objeto.combo == 1){			
			if(comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				if(comandera.idioma == 1)
					var $mensaje = 'No puedes seleccionar mas productos';
				else
					var $mensaje = 'No items to select';
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
			if(comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				if(comandera.idioma == 1)
					var $mensaje = 'No puedes seleccionar mas productos 2';
				else
					var $mensaje = 'No items to select';
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
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=detalles_producto',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done detalles_producto');
			//console.log(resp);
			
		// Si es combo cambia el numero de productos seleccionados
			if($objeto.combo == 1){
				
				// Ejecuta los scripts del combo
				//ch@ Se agrego un if para que no repita los script que vienen del controlador 
				if($objeto.materiales != 1){
					$("#div_ejecutar_scripts").html(resp);
					// Cambia el numero de seleccionados
					$('#cantidad_grupo_'+$objeto.grupo).html(comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados);
				}

				
				// Carga la vista si son materiales

				if($objeto.materiales == 1){
				// Carga la vista a la div
					$('#' + $objeto['div']).html(resp);
					// Cambia el numero de seleccionados
					$('#cantidad_grupo_'+$objeto.grupo).html(comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados); // se coloco dentro el if por el if de arriba
				}
				
			} else if($objeto.promocion == 1 && $objeto.tipo_promocion == 3 || $objeto.promocion == 1 && $objeto.tipo_promocion == 5) {
				console.log(comandera.datos_promocion.grupos[$objeto.grupo]);
				// Ejecuta los scripts del promociones
				$("#div_ejecutar_scripts").html(resp);
				// Cambia el numero de seleccionados
				$('#cantidad_grupo_'+$objeto.grupo).html(comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados);
			
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

			if(comandera.idioma == 1)
				var $mensaje = 'Error al agregar el producto';
			else
				var $mensaje = 'Error adding product';
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
		
		var opcionales = comandera.opcionales;
		var extras = comandera.extra;
		var sin = comandera.sin;
		
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
			if(!comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados){
				num = comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados = 1;
			}else{
				comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados ++;
				num = comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados;
			}
			$('#cantidad_grupo_'+$objeto.grupo).html(num);
		
		// Agrega el pedido a los pedidos seleccioandos del combo
			comandera.seleccionar_pedido($objeto);
		
		// Si no se han seleccionado todos los productos carga el html
			if(comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
				$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>Grupo completo</b>');
			}else{
			// Busca el grupo y carga el HTML de ese grupo
				$.each(comandera.combos, function(key, val) {
					if ($objeto.grupo == val.grupo) {
						$("#" + $objeto.div).html(val.html);
					}
				});
			}
	// Guarda el pedido de la persona normalmente
		} else if($objeto.promocion == 1){
		// Actualiza el numero de pedidos seleccionados
			if($objeto['tipo_promocion'] == 1 || $objeto['tipo_promocion'] == 2 || $objeto['tipo_promocion'] == 4) {
				if(!comandera.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados){
					comandera.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados = 0;
				}
				
				comandera.datos_promocion.grupos['productos'][$objeto['id_producto']].num_seleccionados ++;
				comandera.seleccionar_pedido($objeto);
				console.log(comandera.datos_promocion);
				$("#div_promocion").html(comandera.htmlPromo);
			} else if($objeto['tipo_promocion'] == 3 || $objeto['tipo_promocion'] == 5) {
				// Actualiza el numero de pedidos seleccionados
				var num = 0;
				if(!comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados){
					num = comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados = 1;
				}else{
					comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados ++;
					num = comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados;
				}
				$('#cantidad_grupo_'+$objeto.grupo).html(num);
			
			// Agrega el pedido a los pedidos seleccioandos del combo
				comandera.seleccionar_pedido($objeto);
			
			// Si no se han seleccionado todos los productos carga el html
				if(comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados >= $objeto.cantidad_grupo){
					if($objeto['tipo_promocion'] == 3){
						$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>Promocion completo</b>');
					} else {
						$("#"+$objeto.div).html('<i class="fa fa-cutlery"></i> <b>'+$objeto.grupo+' completo</b>');
					}
				}else{
				// Busca el grupo y carga el HTML de ese grupo
					$.each(comandera.promociones, function(key, val) {
						if ($objeto.grupo == val.grupo) {
							$("#" + $objeto.div).html(val.html);
						}
					});
				}
			}


	// Guarda el pedido de la persona normalmente
		}else{
			comandera.guardar_pedido($objeto);
		}
	},
	kk : function($objeto){
		//$objeto['idmesa'] = 110;
		//alert(comandera['datos_mesa_comanda']['nombre']);
		//alert($objeto['nombre']);
		$('#div_departamentos').remove();
		$('#div_mover_scroll').remove();
		$.ajax({
			url: 'ajax.php?c=comandas&f=mesasDeLaSession',
			type: 'POST',
			dataType: 'html',
			//data: {param1: 'value1'},
		})
		.done(function(respMesas) {
			
			console.log(respMesas);
			//alert(respMesas);
			
			comandera.area_inicio();
			$('#div_mesas2').html('');
			$('#div_mesas2').html(respMesas);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
						setTimeout(function() {
							$('#modalComprobante').modal('hide');
							$('#div_comandera').empty();
							$('.modal-backdrop').css('display','none');
							comandera.vista_comandera({
								div: 'div_comandera',
								vista: '1'
							});
							comandera.pedir({
									id_comanda: $objeto['idComanda'],
									cerrar_comanda: 1
								});
							comandera.mandar_mesa_comandera({
								id_mesa: $objeto['idmesa'],
								tipo: 0,
								id_comanda: '',
								tipo_operacion: 3,
								nombre_mesa_2 : comandera['datos_mesa_comanda']['nombre']
							});

					}, 200);
	},
	mensaje: function(mensaje) {

        $('#lblMensajeEstado').text(mensaje);
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
    },    
    eliminaMensaje: function() {

        $('#modalMensajes').modal('hide');
    },
	pagarTPersona: function($objeto){
		//alert($objeto['id_comanda']);
	},
	changeMetodoPago : function(){
	    if($('#cboMetodoPago').val() == 1)
	        $('#btnDenominacionesPago').show();
	    else
	        $('#btnDenominacionesPago').hide();
	},
	enviarTicket: function(){
    var emailTicket = $('#emailTicket').val();
    var idVenta = $('#idVentaTicket').val();
    var enviarR = $('#inputRecibo').val();


    // Expresion regular para validar el correo
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    // Se utiliza la funcion test() nativa de JavaScript
    if (regex.test(emailTicket.trim())) {
        comandera.mensaje('Enviando...');

        if (enviarR == 4) {
            comandera.eliminaMensaje();
            comandera.enviarRecibo();
        } else {
            $.ajax({
                url: '../pos/ajax.php?c=caja&f=enviarTicket',
                type: 'POST',
                dataType: 'json',
                data: {idVenta : idVenta,
                        correo : emailTicket},
            })

            .done(function(result) {
                console.log(result);
                if(result.estatus==true){
                    comandera.eliminaMensaje();
                    alert('Se envio al correo Electronico');
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        }

    } else {
        alert('La direccón de correo no es valida');
        return false;
    }
},
	////////////////Esta funcion es mia de mi, la cual se detona al presionar el boton verde  de pagar/////////////////
	pagarT : function($objeto){

		//alert($objeto['idmesa']+' '+comandera['datos_mesa_comanda']['nombre']);
		//return false;

		var next = 1;
		$objeto['id_comanda'] = String($objeto['id_comanda']);
		
		$.ajax({ // ajax para verfificar status de comanda si esta pagada termina el proceso y refresca
			url: 'ajax.php?c=comandas&f=statusComanda',			
			type: 'POST',
			dataType: 'json',
			data: {id: $objeto['id_comanda']},
			async: false
		})
		.done(function(data) { // data es el status de la comanda
			if(data == 1){ // pagada
				alert('La comanda ya fue pagada');
				next = 0;
				// Falta Refescar comandera
				comandera.mandar_mesa_comandera({
					id_mesa: $objeto['idmesa'],
					tipo: 0,
					id_comanda: '',
					tipo_operacion: 3,
					//nombre_mesa_2 : comandera['datos_mesa_comanda']['nombre']
				});
			}
		})

		if(next == 1){

			console.log($objeto);
		
			$objeto['id_comanda'] = String($objeto['id_comanda']);
			//alert($objeto['id_comanda']);
			var size = 5 - $objeto['id_comanda'].length;
			var string = "";
			var i=0;
			for (i = 0; i < size; i++){
				string += "0";
			}

			string += $objeto['id_comanda'];

			//alert(string);
			$.ajax({
				url: '../pos/ajax.php?c=caja&f=agregaProducto',
				type: 'POST',
				dataType: 'json',
				data: {id: 'COM'+string,
						borrarSesion: 1
				},
			})
			.done(function(data) {
				console.log(data);

				if(data.estatus==1000){
                    alert("El producto no cuenta con existencias.");                   
                    return false;
                }

				 $('#modalPagar').modal({
	                show:true,
	            });
				comandera.modalPagar(data);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		}		

	},
	    modalPagar: function (data){
            /*if($('#totalDeProductosInput').val() < .001){
                alert('Tienes que vender al menos un producto.');
                return false;
            } */
            console.log(data);


            comandera.checaPagos();
            //caja.checaPagos();
   
            
            //console.log('------> Info venta');
            //console.log(caja.info_venta);
            
           /* if(caja.info_venta['ajustes']['switch_propina'] == 1){
	            var $porcentaje = (caja.info_venta['ajustes']['calculo_automatico'] / 100);
	            $porcentaje = $porcentaje.toFixed(2);
	            
	            if(caja.info_venta['ajustes']['aplicar_a'] == 1){
	           		var $monto = caja.data["cargos"]["total"].toFixed(2);
	            }else{
	            	var $monto = caja.data["cargos"]["subtotal"].toFixed(2);
	            }
	            
	            caja.info_venta['venta']['monto_total'] = $monto;
	            
	            $monto = $monto * $porcentaje;
	            $monto = $monto.toFixed(2);
	           	
	            $("#porcentaje_propina").val(caja.info_venta['ajustes']['calculo_automatico']);
	            $("#monto_propina").val($monto);
            } */
            
            
            $('#txtCantidadPago').val(data["cargos"]["total"].toFixed(2));
            $('#lblTotalxPagar').text(data["cargos"]["total"].toFixed(2));
            $('#btnAgregarPago').unbind('click').bind('click', function() {

                var tipostr = $('#cboMetodoPago option:selected').text();
                var tipo = $('#cboMetodoPago').val();
                var pago = ($('#txtCantidadPago').val()).replace(",",'');
                if(pago < 0){
                    alert('El pago debe ser mayor a cero.');
                    return false;
                }
                var txtReferencia = $('#txtReferencia').val();

                comandera.metodoPago(tipo, tipostr, pago, txtReferencia,data);

            });
            $('#cboMetodoPago').unbind('change').bind('change', function() {
                comandera.muestraReferenciaPago($(this).val());
            });
            $('#modalPagar').modal({
                show:true,
            });
    },
        muestraReferenciaPago: function(valor){
    
    var elemento = $('#divReferenciaPago');
    var elTexto = $('#lblReferencia');
    $('#txtReferencia').val('');

    elemento.css({'display': 'block'});

    switch (parseInt(valor))
    {
        case 2 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').hide();
        elTexto.text('Numero de cheque:');
        break;
        case 3:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').hide();
        elTexto.text('Numero de tarjeta:');

        break;
        case 4:
        case 5:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        $('#tarjetasRadios').show();
        elTexto.text('Numero de tarjeta:');
        break;
        case 6:
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Comentario:');
         $('#tarjetasRadios').hide();
        break;
        case 7 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Referencia transferencia:');
         $('#tarjetasRadios').hide();
        break;
        case 8 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Referencia spei:');
         $('#tarjetasRadios').hide();
        break;
        case 25 :
        //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
        elTexto.text('Tarjeta de Vales:');
         $('#tarjetasRadios').hide();
        break;
        case 26 :
            //$('#txtCantidadPago').val(caja.data["cargos"]["total"].toFixed(2));
            caja.hazUnTruco();
        break;

        default :
        elemento.css({'display': 'none'});
        break;
    }
},
    metodoPago: function(tipo, tipostr, cantidad, txtReferencia,data3) {

    if ($('#cboMetodoPago').val() == '')
    {
        return;
    }

    if ($("#txtCantidadPago").val() == "") {
        alert("Ingresa la cantidad para agregar el pago");
        $("#txtCantidadPago").focus();
        return false;
    }

    if ($("#cboMetodoPago").val() == 2 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el nÃºmero de cheque para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 7 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar la txtReferencia de la transferencia para registrar el pago");
        return false;
    }


    if ($("#cboMetodoPago").val() == 8 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar la txtReferencia SPEI para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 3 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el nÃºmero de la tarjeta de regalo para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 4 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el nÃºmero de baucher para registrar el pago");
        return false;
    }

    if ($("#cboMetodoPago").val() == 5 && $("#txtReferencia").val() == "")
    {
        alert("Debes ingresar el nÃºmero de baucher para registrar el pago");
        return false;
    }


    if ($("#cboMetodoPago").val() == 6 && $("#hidencliente-caja").val() == "")
    {
        alert("Debes seleccionar el cliente para poder registrar un pago a credito");
        return false;
    }

        //Tejeta de regalo
        if ($("#cboMetodoPago").val() == 3)
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../pos/ajax.php?c=caja&f=checatarjetaregalo',
                data: {
                    numero: txtReferencia,
                    monto: cantidad
                },
                success: function(response) {
                    if (response.status)
                    {
                        comandera.agregarPago(tipo, tipostr, cantidad, txtReferencia,data3);
                    } else
                    {
                        alert(response.msg);
                    }
                }});//end ajax
        } else if ($("#cboMetodoPago").val() == 6)//pago a credito
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../pos/ajax.php?c=caja&f=checalimitecredito',
                data: {
                    cliente: $("#hidencliente-caja").val(),
                    monto: cantidad
                },
                success: function(resp) {
                    if (resp.status)
                    {
                        comandera.agregarPago(tipo, tipostr, cantidad, txtReferencia,data3);
                    } else
                    {
                        alert(resp.msg);
                    }

                }});//end ajax
        } else
        {
            comandera.agregarPago(tipo, tipostr, cantidad, txtReferencia,data3);
        }


    },
    agregarPago: function(tipo, tipostr, cantidad, txtReferencia,data2)
    {

        /*var cambio = $("#pagar-cambio").html().replace("Cambio:$", "");
         var cambio = cambio.replace("$", "");
         if (cambio > 0)
         {
         alert("Con los pagos efectuados se puede completar la venta");
         return false;
     }*/

        /*if ($("#cboMetodoPago").val() > 3 && $("#cantidadpago").val() > $('#cantidad-recibida').val().replace('$', '').replace(',', ''))
         {
         alert('El pago no debe ser superior al total');
         return false;
         //alert($("#cboMetodoPago").val()+" / "+$("#cantidadpago").val()+" / "+$('#cantidad-recibida').val().replace('$','')); return false;

     }*/
 

     if ($('#Pago' + tipo).length)
     {
        cantidad = parseFloat(cantidad.replace(",", ""));
            //cantidad += parseFloat($('#cantidad' + tipo).html());
        }
        //alert($('input:radio[name=tarRadio]:checked').val());


        $.ajax({
            type: 'POST',
            url: '../pos/ajax.php?c=caja&f=agregaPago',
            dataType: 'json',
            data: {
                tipo: tipo,
                tipostr: tipostr,
                cantidad: cantidad,
                txtReferencia: txtReferencia,
                tarjeta : $('input:radio[name=tarRadio]:checked').val(),
            },
            success: function(data) {
			console.log("======> Pagos");
			console.log(data);
			
                if (data.status)
                {
                    if ($('.nopagos').length)
                    {
                        $('.nopagos').parent().empty();
                    }

                    if ($('#Pago' + data.tipo).length)
                    {
                        $('#cantidad' + data.tipo).html(data.cantidad);
                    } else
                    {
                        var abonosPagos = $('#divDesglosePagoTablaCuerpo');

                        var registroCaja = $(document.createElement('tr')).attr({'id': 'Pago' + data.tipo}).appendTo(abonosPagos);
                        var regTipo = $(document.createElement('td')).html(data.tipostr).appendTo(registroCaja);
                        var regCantidad = $(document.createElement('td')).attr({'id': 'cantidad' + data.tipo}).html(data.cantidad.toFixed(2)).appendTo(registroCaja);
                        var regAccion = $(document.createElement('td')).css({'text-align' : 'center'}).appendTo(registroCaja);
                        var accion = $(document.createElement('span')).addClass('glyphicon glyphicon-remove').appendTo(regAccion);

                        accion.bind('click', function() {
                            comandera.eliminarPago(data.tipo);
                        });
                    }

                    $('#lblAbonoPago').text("$ " + data.abonado);
                    $('#lblPorPagar').text("$ " + data.porPagar);
                    $('#lblCambio').text("$ " + data.cambio);

                    $('#txtCantidadPago').val(data.porPagar);
                    $('#txtReferencia').val('');
                }
            }});
},
	checaPagos: function() {

    $.ajax({
        type: 'POST',
        url: '../pos/ajax.php?c=caja&f=checarPagos',
        dataType: 'json',
        success: function(data) {
            if (data.status)
            {
                $('#abonosPagos').empty();

                var abonosPagos = $('#abonosPagos');

                $.each(data.pagos, function(index, value) {
                    var registroCaja = $(document.createElement('div')).attr({'id': 'Pago' + index}).addClass('form-control registroCaja').appendTo(abonosPagos);
                    var regTipo = $(document.createElement('div')).addClass('col-xs-5').html(value.tipostr).appendTo(registroCaja);
                    var regCantidad = $(document.createElement('div')).attr({'id': 'cantidad' + index}).addClass('col-xs-5').html(value.cantidad).appendTo(registroCaja);
                    var regAccion = $(document.createElement('div')).addClass('col-xs-2').appendTo(registroCaja);
                    var accion = $(document.createElement('img')).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(regAccion);

                    accion.bind('click', function() {
                        //caja.eliminarPago(index);
                    });
                });

                $('#lblAbonoPago').text(data.abonado);
                $('#lblPorPagar').text(data.porPagar);
                $('#lblCambio').text(data.cambio);
                $('#txtCantidadPago').val(data.porPagar);
            } else if (data.statusInicio == false)
            {
                $('#modalPago').dialog("close");
                //caja.inicioCaja(data);
            }
        }});

    },

    eliminarPago: function(pago) {
    $.ajax({
        type: 'POST',
        url: '../pos/ajax.php?c=caja&f=eliminarPago',        
        dataType: 'json',
        data: {
            pago: pago
        },
        success: function(data) {

            $('#Pago' + pago).hide('slow', function() {
                $(this).remove();

                if ($('#abonosPagos').html() == '')
                {
                    $('#abonosPagos').html('<div class="form-control registroCaja nopagos"><div class="col-xs-12 text-center">No hay pagos</div></div>');
                }
            });

            if (data.status)
            {
                $('#lblAbonoPago').text(data.abonado);
                $('#lblPorPagar').text(data.porPagar);
                $('#lblCambio').text(data.cambio);
            } else
            {
                $('#lblAbonoPago').text('0.00');
                $('#lblPorPagar').text('0.00');
                $('#lblCambio').text('0.00');
            }
        }});

	},

    pagar: function() {
    	$('#pagarPagar').prop('disabled', true);
    var codigo = $('#codigo').val();
    var propina = $('#propina').val();
    var pedido = $('#idPedido').val();

    if ($('.nopagos').length)
    {
        alert('Debes saldar la deuda.');
        $('#pagarPagar').prop('disabled', false);
        $('#txtCantidadPago').focus();
        return;
    }

    if ($('#lblPorPagar').text() != '0.00' && $('#lblPorPagar').text() != '$ 0.00')
    {
        alert('No has cubierto el total de la deuda.');
        $('#pagarPagar').prop('disabled', false);
        return;
    }



    if (codigo != '')
    {
        $.ajax({
            url: 'ajax.php?c=productocomanda&f=borrarProductoTemporal',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {'codigo': codigo},
            error: function(data) {
                alert('No se pudo borrar la comanda-p');
                return;
            }
        });
    }


    $.ajax({
        url: '../pos/ajax.php?c=caja&f=guardarVenta',
        type: 'POST',
        dataType: 'json',
        async: true,
        data: {
            idFact: $("#rfc").val(),
            //propinas: caja.info_venta['propinas'],
            documento: '1',
            cliente: '',
            vendedor: $("#hidenvendedor-caja").val(),
            suspendida: $("#s_cliente").val(),
            propina: propina,
            comentario: '',
            moneda : '1',
                //pagoautomatico: 1,
                //impuestos: $totalimpuestos,
                //sucursal: $("#caja-sucursal").val(),
                //almacen: $("#caja-almacen").val(),
                //cambio: 0,
                //monto: $total,
                //cliente: $("#hidencliente-caja").val(),
                //empleado: $("#idvendedor").val()
            },
            beforeSend: function() {
                //caja.mensaje("Guardando Venta");
            },
            success: function(resp) {
            	console.log('----> success venta');
            	console.log(resp);
            	//alert(pedido);
                //alert(resp.idVenta);

                //ch
               /* $.ajax({
                    url: '../pos/ajax.php?c=caja&f=datosventa2',
                    type: 'POST',
                    dataType: 'json',
                    data: {idVenta : resp.idVenta},
                })
                .done(function(data) {
                    $.each(data, function(index, val) { 
                        var emailCliente  = val.emailCliente; 
                        $("#emailTicket").val(emailCliente);
                    });                      
                }); */
                


                if (resp.status)
                {   $('#modalPagar').modal('hide');
           			$('#pagarPagar').prop('disabled', false);
                    /*if(pedido!=''){ 

                        //Cambia el estatus del pedido
                        $.ajax({
                            url: 'ajax.php?c=caja&f=estatusPedido',
                            type: 'POST',
                            dataType: 'json',
                            data: {idVenta : resp.idVenta,
                                   idPedido : pedido },
                        })
                        .done(function(resx) {
                            console.log(resx);

                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            console.log("complete");
                        });
                        
                    } */
                    var documentTipo = 1;
                 if(documentTipo == 2)
                 {
                    $('#lblComentarioE').html('la Factura.');
                }else if(documentTipo == 5){
                    $('#lblComentarioE').html('el Recibo de Honorarios.');
                }else{
                    $('#lblComentarioE').html('el Recibo de Ingresos.');
                }
                comandera.observacionesFactura(resp);
            } else
            {
                //caja.eliminaMensaje();
                alert(resp.msg);
            }
        },
        error: function(data) {
			console.log('----> error venta');
			console.log(data);
            	
            //caja.eliminaMensaje();
            alert(data.msg);
        }
    });
},
observacionesFactura: function(resp) {
    obsResp = resp;
    var documentTipo = 1;
    if (documentTipo == 1)
    {
        comandera.comprobante(resp, false);
        comandera.mensaje("Generando Ticket");
    } else if (documentTipo == 4) {
        caja.comprobante(resp, false);
        comandera.mensaje("Generando Recibo de pago");
    } else {
        comandera.eliminaMensaje();
        $('#modal_Observaciones').modal({
                                        backdrop: 'static',
                                        keyboard: false, 
                                    });
    }
},
comprobante: function(resp, mensaje) {
 console.log(resp);
 console.log(mensaje);
 


    $.ajax({
        type: 'POST',
        url: '../pos/ajax.php?c=caja&f=facturar',
        dataType: 'json',
        data: {
            idFact: '0',
            idVenta: resp.idVenta,
            doc: '1',
            mensaje: mensaje,
            consumo:$('#consumo').val(),
        },
        beforeSend: function() {



        },
        success: function(resp) {
            //alert('entro al success');
            //return false;
            comandera.eliminaMensaje();
            if (resp.success == '500') {
                alert(resp.mensaje);
                window.location.reload();
                return false;
            }
            if (resp.success == '-1') {
                alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
                window.location.reload();
                return false;
            }
            if (resp.success == '3') {
                alert('Venta realizada con exito.');
                
                comandera.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);

                    //window.location.reload();
                    return false;
                }
                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                ================================================ */
                if (resp.success == 0 || resp.success == 5) {
                    if (resp.success == 0) {
                        alert('Ha ocurrido un error durante el proceso de facturaciÃ³n. Error ' + resp.error + ' - ' + resp.mensaje);
                    }
                    //alert('esto es una prueba');
 
                        comandera.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);
                        // caja.modalComprobante("ajax.php?c=caja&f=ticket&idVenta=" + resp.idVenta, true);
                        $('#inputRecibo').val('1');
                    

                    console.log(resp);
                    $.ajax({
                        type: 'POST',
                        url:'../pos/ajax.php?c=caja&f=pendienteFacturacion',
                        data:{
                            azurian:resp.azurian,
                            idFact:'0',
                            monto:(resp.monto),
                            cliente:$("#hidencliente-caja").val(),
                            trackId:resp.trackId,
                            idVenta:resp.idVenta,
                            doc: '1'

                        },
                        beforeSend: function() {
                            comandera.eliminaMensaje();
                            comandera.mensaje("Guardando Factura 2");
                        },
                        success: function(resp){  
                            comandera.eliminaMensaje();
                        }

                    });

                }

                if (resp.success == 1)
                {
                    azu = JSON.parse(resp.azurian);
                    uid = resp.datos.UUID;
                    correo = resp.correo;

                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php?c=caja&f=guardarFacturacion',
                        dataType: 'json',
                        data: {
                            UUID: uid,
                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                            selloCFD: resp.datos.selloCFD,
                            selloSAT: resp.datos.selloSAT,
                            FechaTimbrado: resp.datos.FechaTimbrado,
                            idComprobante: resp.datos.idComprobante,
                            idFact: resp.datos.idFact,
                            idVenta: resp.datos.idVenta,
                            noCertificado: resp.datos.noCertificado,
                            tipoComp: resp.datos.tipoComp,
                            trackId: resp.datos.trackId,
                            monto: (resp.monto),
                            cliente: $("#hidencliente-caja").val(),
                            idRefact: 0,
                            azurian: resp.azurian,
                            doc: $('#documento').val()
                        },
                        beforeSend: function() {
                            if($('#documento').val() == 2)
                            {   
                                $('#labelTF').text("Factura");
                                $('#emailTicketHide').hide();  
                                caja.mensaje("Guardando Factura");
                            }else if($('#documento').val() == 3)
                            {
                                caja.mensaje("Guardando Recibo de Ingresos");
                            }
                        },
                        success: function(resp) {
                           
                            caja.eliminaMensaje();
                            //window.open('../../modulos/facturas/'+uid+'.pdf');
                            $.ajax({
                                async: false,
                                type: 'POST',
                                url: 'ajax.php?c=caja&f=envioFactura',
                                dataType: 'json',
                                data: {
                                    uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: $('#documento').val()
                                },
                                beforeSend: function() {
                                    //caja.mensaje("Enviando Factura");
                                },
                                success: function(resp) {
                                    $('#modalFacturacion').modal('hide');
                                    $('#modalCodigoVenta').modal('hide');

                                    caja.eliminaMensaje();
                                    if(resp.cupon==false){
                                        //caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
                                    }else{
                                        //caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
                                    }
                                    caja.eliminaMensaje();
                                    //window.open('../../modulos/facturas/' + uid + '.pdf');
                                    //window.location.reload();
                                },
                                error: function() {
                                    caja.eliminaMensaje();
                                }
                            });

$("#loaderventa").hide();
$('#caja-dialog').modal('hide');
$("#boton-pagar").removeAttr("disabled");
alert('Has registrado la venta con exito');
                            //window.location.reload();
                        },
                        error: function() {
                            caja.eliminaMensaje();
                        }
                    });
}
			// Redirecciona al mapa de mesas si es comida rapida
                /*if(caja.info_venta.ajustes['tipo_operacion'] == 3){
					setTimeout(function() {
						var pestana = $("body", window.parent.document).find("#tb2156-1");
						var mapa = $("body", window.parent.document).find("#mnu_2156");
						mapa.trigger('click');
						pestana.trigger('click');
						window.location.reload();
					}, 500);
                } */
            }
        });
},
modalComprobante: function(src, ticket, idVenta) {
    
    /*if($('#documento').val() == "2" ){
        $('#labelTF').text("Factura");
        caja.eliminaMensaje();
        $('#emailTicketHide').hide();
 
    } */
        comandera.eliminaMensaje();
        $('#idVentaTicket').val(idVenta);
        var sizeWidth = 0;
        var sizeheight = 0;

        if (ticket)
        {
            sizeWidth = 325;
            sizeheight = 450;
        } else {

            sizeWidth = $('#tb1238-u', window.parent.document).width() - 100;
            sizeheight = $('#tb1238-u', window.parent.document).height() - 50;
            
        }

        $('#modalPago').modal('hide');

        $('#frameComprobante').attr({'src': src});

        $('#modalComprobante').modal({backdrop: 'static'});
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
		var $div_productos = $('#' + $objeto['div']).html();
	
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');

		/*
			* Se coloco la carga de productos del 
			* elemento #dic_productos fuera del ajax
			* debido que el uso dentro de este causaba
			* un retardo aproximado de 500-800 milisegundos.
		*/

		// Carga los productos
		$("#div_productos").html(comandera.productos);
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_pedido',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_pedido');
			console.log(resp);
		
		// Selecciona el pedido(nos sirve al momento de querer agregar complementos)
			comandera.datos_mesa_comanda['pedido_seleccionado'] = resp;
			
		// Quita el loader
			$btn.button('reset');
			
		// Carga los pedidos de la persona
			comandera.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
			
		}).fail(function(resp) {
			console.log('=========> Fail guardar_pedido');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar el pedido';
			else
				var $mensaje = 'Failed to save order';
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
		var $btn = $('#sum_ped_'+$objeto['idorder']);
		$btn.button('loading');
		var $cantidad = parseInt($('#cantidad_' + $objeto['idorder']).html());
		var $num_pedidos = parseInt($('#num_pedidos' + $objeto['idorder']).val());
		$objeto['cantidad'] = $cantidad;
		$objeto['num_pedidos'] = $num_pedidos;
		if(comandera.idioma == 0)
			comandera.get_idioma();
		console.log('---	-	-	-	-	$objeto sumar_pedido');
		console.log($objeto);
	
		for ( i = 0; i < $num_pedidos; i++) {
			console.log('---	-	-	-	-	entrar for');
			console.log($objeto);
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=comandas&f=sumar_pedido',
				type : 'POST',
				dataType : 'json',
			}).done(function(resp) {
				console.log('---	-	-	-	-	done sumar_pedido');
				console.log(resp);
				$btn.button('reset');
			// Error
				if (resp['status'] == 2) {
					if(comandera.idioma == 1)
						var $mensaje = 'Error al cargar aumentar la cantidad';
					else
						var $mensaje = 'Failed to load increase amount';
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
				$btn.button('reset');
			// Manda un mensaje de error
				if(comandera.idioma == 1)
					$mensaje = 'Error al aumentar la cantidad';
				else
					$mensaje = 'Failed to increase amount';
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
		// merma -> 1 Si sedebe de mandar a merma
		// comentario -> Comentario de la merma
		
	restar_pedido : function($objeto) {
		var $btn2 = $('#btn_restar_'+$objeto['id']);
		$btn2.button('loading');
		console.log('=========> objeto restar_pedido');
		console.log($objeto);
		var $btn = $('#btn_merma');
		$btn.button('loading');
			
		if(comandera.idioma == 0)
			comandera.get_idioma();
	
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=restar_pedido',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done restar_pedido');
			console.log(resp);
			
		// Limpia la variable
			comandera.pedido_merma = '';
		// Limpia el comentario
			$('#comentario_merma').val('');
		// Reinicia el select de la merma
			$('#merma').val(2);
			$(".selectpicker").selectpicker("refresh");
		// Cierra la modal de la merma
			$('#modal_merma').click();
			$('#btn_cerrar_merma').click();
			$btn.button('reset');
			$btn2.button('reset');
			comandera.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('---------> Fail restar_pedido');
			console.log(resp);
			$btn2.button('reset');
		// Manda un mensaje de error
			if(comandera.idioma == 1)	
				$mensaje = 'Error al restar la cantidad';
			else
				$mensaje = 'Error subtracting amount';
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=eliminar_pedido',
			type : 'POST',
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
			if(comandera.idioma == 1)
				$mensaje = 'Error al eliminar el pedido';
			else
				$mensaje = 'Failed to delete order';
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


///////////////// ******** ----  			DESCUENTO PRO				------ ************ //////////////////

	desc_pedido : function($objeto) { 

		$("#desCantidad").val('');
		if($objeto.monto_desc > 0){
			alert('Ya se Realizo un Desceunto');
			return 0;
		}
		console.log($objeto);
		$("#modalNota").modal('hide');		
		$("#modalDescParcial").modal('show');
		$("#encabezadoPrecio").text($objeto.precio);
		$("#encabezadoNombre").text($objeto.nombre);

		


		$("#btndesc").attr('onclick','comandera.aplicaDesc('+$objeto.idcomanda+','+$objeto.idorder+','+$objeto.idperson+','+$objeto.precio+')');


	},

	aplicaDesc : function(idcomanda,idorder,idperson,precio) { 

		var tipo_desc = $("#tipoDescu").val();
		var monto_desc = $("#desCantidad").val();

		if(monto_desc > 99 || monto_desc < 1){
			alert('Descuento no Valido! Ingrese otra cantidad!');
			return 0;
		}

		$.ajax({
			data:{idorder:idorder,tipo_desc:tipo_desc,monto_desc:monto_desc},			
			url : 'ajax.php?c=comandas&f=aplidaDesc',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done agregar_persona_comandera');
			console.log(resp);

			$objeto = {	div:'div_listar_pedidos_persona', persona: idperson, id_comanda: idcomanda };
			comandera.listar_pedidos_persona($objeto);

		});

/*
		
		console.log($objeto);
		$("#modalNota").modal('hide');		
		$("#modalDescParcial").modal('show');
		$("#encabezadoPrecio").text($objeto.precio);
		$("#encabezadoNombre").text($objeto.nombre);
		*/



	},

///////////////// ******** ----  			DESCUENTO PRO FIN			------ ************ //////////////////


///////////////// ******** ----  		agregar_persona_comandera		------ ************ //////////////////
//////// Agrega una persona y carga la vista de las personas
	// Como parametro puede recibir:
		// num_personas -> Numero de personas
		// id_comanda -> ID de la comanda

	agregar_persona_comandera : function($objeto) {
		$("#div_productos").hide();
		//$('.btn').prop( "disabled", true );
		//$('.act').prop( "disabled", false );	
		console.log('=========> objeto agregar_persona_comandera');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_persona_comandera',
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
			comandera.vista_personas($datos);
		
			//$('.btn').prop( "disabled", false );
			//$("#div_productos").show();
		// Selecciona la nueva persona despues de medio segundo
			setTimeout(function() {
				$("#persona_" + resp['result']).click();
			}, 500);
		}).fail(function(resp) {
			console.log('---------> Fail agregar_persona_comandera');
			console.log(resp);
	
		// Manda un mensaje de error
			if(comandera.idioma == 1)
				$mensaje = 'Error al agregar la persona';
			else
				$mensaje = 'Error adding person';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
			//$('.btn').prop( "disabled", false );
			//$("#div_productos").show();
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
		if(comandera.idioma == 0)
			comandera.get_idioma();	

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=pedir',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> Done pedir');
			console.log(resp);
			
	    	// Inicializamos variables del combo
	    	comandera.pedidos_seleccionados = {};
	    	comandera.datos_combo = {};
	    	comandera.combos = [];
	    	
			console.log('=========> ajsutes');
			console.log(comandera.ajustes);
			
			// Redirecciona solo si no es Fast food
			if (comandera.ajustes['tipo_operacion'] != 3) {
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
			if(comandera.idioma == 1)
				$mensaje = 'Error al mandar el pedido';
			else
				$mensaje = 'Ordering failed';
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
///////////////// ******** ---- 	formatearTicket		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	vararray -> array ticket
//	varstring -> string a convertir
//  bold -> 0: nomarl, 1: negrita
//  underlined -> 0: no, 1: si
//  tipo -> 1: left, 2: center, 3: right
 formatearTicket : function(vararray, varstring, bold, underlined, tipo){
			varstring = String(varstring).trim();
			var varstring_copia = varstring.split(" ");
			var string = '';
			var ant_string = '';
			if(varstring.length > 32){
				for (var x = 0; x < varstring_copia.length; x++) {
					ant_string = string.trim();
					string += ' '+varstring_copia[x];
					if(string.length > 32){
						//centrado
						var string_spaces = '';
						if(tipo == 2){
							var spaces = Math.floor((32 - ant_string.length) / 2);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						} else if(tipo == 3){
							var spaces = 32 - varstring.length;
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						}
						vararray.push({'texto' : string_spaces+ant_string, 'bold' : bold, 'underlined' : underlined});
						string = '';
						x--;
					} else if(x == (varstring_copia.length - 1)){
						string = string.trim();
						var string_spaces = '';
						if(tipo == 2){
							var spaces = Math.floor((32 - string.length) / 2);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						} else if(tipo == 3){
							var spaces = 32 - varstring.length;
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
						}
						vararray.push({'texto' : string_spaces+string, 'bold' : bold, 'underlined' : underlined});
					}
					
				}
			} else {
				//centrado
				var string_spaces = '';
				if(tipo == 2){
					var spaces = Math.floor((32 - varstring.length) / 2);
					for (var i = 0; i < spaces; i++) {
						string_spaces+= ' ';
					}
				} else if(tipo == 3){
					var spaces = 32 - varstring.length;
					for (var i = 0; i < spaces; i++) {
						string_spaces+= ' ';
					}
				}
				vararray.push({'texto' : string_spaces+varstring, 'bold' : bold, 'underlined' : underlined});
			}
			return vararray;
},
///////////////// ******** ---- 	FIN formatearTicket		------ ************ //////////////////

///////////////// ******** ---- 	formatearTicketProducts		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	vararray -> array ticket
//  cant -> cantidad producto
//  producto -> descripcion producto
//  total -> total
//	product -> string a convertir
//  bold -> 0: nomarl, 1: negrita
//  underlined -> 0: no, 1: si
//  tipo -> 1: left, 2: center, 3: right, 4: products
 formatearTicketProducts : function(vararray, cant, varproduct, total, bold, underlined){
			cant = String(cant).trim();
			varproduct = String(varproduct).trim();
			total = String(total).trim();
			var product_copia = varproduct.split(" ");
			var product = '';
			var ant_product = '';
			var ya = 0;
			if(varproduct.length > 18){
				for (var x = 0; x < product_copia.length; x++) {
					ant_product = product.trim();
					product += ' '+product_copia[x];
					if(product.length > 18){
						//centrado
						var string_spaces = '';
						var spaces = 0;
						spaces = (18 - ant_product.length);
						for (var i = 0; i < spaces; i++) {
							string_spaces+= ' ';
						}
						ant_product += string_spaces;
						if(ya == 0){
							string_spaces = '';
							spaces = (7 - cant.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							cant += string_spaces;
							string_spaces = '';
							spaces = (7 - total.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							total = string_spaces+total;
							vararray.push({'texto' : cant+ant_product+total, 'bold' : bold, 'underlined' : underlined});
							ya = 1;
						}
						else{
							vararray.push({'texto' : '       '+ant_product, 'bold' : bold, 'underlined' : underlined});
						}
						product = '';
						x--;
					} else if(x == (product_copia.length - 1)){
						product = product.trim();
						var string_spaces = '';
						var spaces = 0;
						spaces = (18 - product.length);
						for (var i = 0; i < spaces; i++) {
							string_spaces+= ' ';
						}
						product += string_spaces;
						if(ya == 0){
							string_spaces = '';
							spaces = (7 - cant.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							cant += string_spaces;
							string_spaces = '';
							spaces = (7 - total.length);
							for (var i = 0; i < spaces; i++) {
								string_spaces+= ' ';
							}
							total = string_spaces+total;
							vararray.push({'texto' : cant+product+total, 'bold' : bold, 'underlined' : underlined});
							ya = 1;
						}
						vararray.push({'texto' : '       '+product, 'bold' : bold, 'underlined' : underlined});
					}
					
				}
			} else {
				//centrado
				var string_spaces = '';
				var spaces = 7 - cant.length;
				for (var i = 0; i < spaces; i++) {
					string_spaces+= ' ';
				}
				cant += string_spaces;
				string_spaces = '';
				spaces = 18 - varproduct.length;
				for (var i = 0; i < spaces; i++) {
					string_spaces+= ' ';
				}
				varproduct += string_spaces;
				string_spaces = '';
				spaces = 7 - total.length;
				for (var i = 0; i < spaces; i++) {
					string_spaces+= ' ';
				}
				total = string_spaces+total;
				console.log("total:"+total);
				vararray.push({'texto' : cant+varproduct+total, 'bold' : bold, 'underlined' : underlined});
			}
			return vararray;
},
///////////////// ******** ---- 	FIN formatearTicketProducts		------ ************ //////////////////
	imprimeMobile : function(resp){
		var ticket = comandera.imprimeMobile2(resp);


	        console.log("ticket: ");
	        console.log(ticket);
	        setTimeout(function(){
	        	//window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + ticket + ';end'); 
				var navegador = (navigator.userAgent.indexOf('Firefox') != -1) ? 1 : ((navigator.userAgent.indexOf("Chrome") != -1) ? 2 : 0);
			var pestana = window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + ticket + ';S.navegador='+ navegador +';end'); 
			pestana.close();
		//window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + ticket + ';end');
	     },  5000);  	
	},

	imprimeMobile2 : function(resp){
		console.log("imprimeMobile");
		
			var ya_mesa = 0;
	        var arrayTicket = [];
	        var persona = -1;
	        var total_persona = 0;
	        var total_comanda = 0;
	        var costo_extra = 0;
	        var impuestos = 0;
	        var promedio_comensal = 0;
	        arrayTicket.push({'logo' : '', 'codigo' : '', 'qr' : '', 'tipo' : 2, 'type' : ''});
	        if(!isEmptyF(resp['comanda']['logo'])){
	            arrayTicket[0]['logo']= resp['comanda']['logo'];
	            arrayTicket[0]['type']= resp['comanda']['type'];
	        } 
	        $.each(resp['comanda']['rows'], function(key, value) {
	            if(resp['que_mostrar']["switch_info_ticket"] == 1 && ya_mesa==0) {
	                ya_mesa = 1;
	                if (resp['que_mostrar']["mostrar_info_empresa"] == 1) {
	                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['nombreorganizacion'], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'RFC: '+resp['organizacion'][0]['RFC'], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['datos_sucursal'][0]['direccion']+" "+resp['datos_sucursal'][0]['municipio']+","+resp['datos_sucursal'][0]['estado'], 0, 0, 2);
	                    if(resp['organizacion'][0]['paginaweb']!='-'){
	                        arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['paginaweb'], 0, 0, 2);
	                    }
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Sucursal: '+resp['datos_sucursal'][0]["nombre"]["tel_contacto"], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Apertura: '+resp['objeto']['f_ini'], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Cierre: '+resp['fecha_fin'], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesero: '+resp['objeto']['mesero'], 0, 0, 2);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Personas: '+resp['objeto']['personas'], 0, 0, 2);

	                }
	                if (value['tipo'] != 2 && value['tipo'] != 1 && $.isNumeric(value['nombreu'])) {
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: #'+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
	                } else {
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: '+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
	                }
	                arrayTicket = comandera.formatearTicket(arrayTicket, value['codigo'], 0, 0, 2);
	                arrayTicket[0]['codigo'] = value['codigo']; 
	                if(value['tipo'] == 1 || value['tipo'] == 2){ 
	                    if(resp['que_mostrar']["mostrar_nombre"] == 1) {
	                        arrayTicket = comandera.formatearTicket(arrayTicket, 'Cliente: '+value['nombreu'], 0, 0, 2);
	                     }
	                     if(resp['que_mostrar']["mostrar_domicilio"] == 1) { 
	                        if(value['domicilio']){
	                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Domicilio: '+value['domicilio'], 0, 0, 2);
	                        }
	                     }
	                     if(resp['que_mostrar']["mostrar_tel"] == 1) { 
	                        if(resp['comanda']['tel']){
	                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Tel: '+resp['comanda']['tel'], 0, 0, 2);
	                        }
	                     }
	                }
	            } 
	            if(persona != value['npersona']){
	                if(total_persona > 0) {
	                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona, 1, 0, 3);
	                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
	                }
	                arrayTicket = comandera.formatearTicket(arrayTicket, 'Orden No: '+value['npersona'], 0, 0, 2);
	                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
	                arrayTicket = comandera.formatearTicketProducts(arrayTicket, 'Cant.', 'Producto', 'Total', 1, 0);
	                total_persona = 0;
	                persona = value['npersona'];
	                //codigo = $value['codigo'];
	            }
	            arrayTicket = comandera.formatearTicketProducts(arrayTicket, value['cantidad'], value['nombre'], '$'+(value['precioventa'] * value['cantidad']).toFixed(2), 1, 0);

	            if(value['costo_extra']){
	                costo_extra = 0;

	                $.each(value['costo_extra'], function(k, v) {
	                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Extra: '+v['nombre'], '$'+(v['costo'] * value['cantidad']).toFixed(2), 0, 0);
	                    costo_extra += (v['costo'] * value['cantidad']).toFixed(2);
	                });

	            // Calcula totales
	                total_persona += costo_extra;
	                total_comanda += costo_extra;
	            } //Fin costo extra

	            if(value['costo_complementos']){
	                costo_extra = 0;

	                $.each(value['costo_complementos'], function(k, v) {
	                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Complemento: '+v['nombre'], '$'+(v['costo'] * value['cantidad']).toFixed(2), 0, 0);
	                    costo_extra += (v['costo'] * value['cantidad']).toFixed(2);
	                });

	                // Calcula totales
	                    total_persona += costo_extra;
	                    total_comanda += costo_extra;
	            } //Fin costo complementoss

	            total_persona += (value['precioventa'] * value['cantidad']);
	            total_comanda += (value['precioventa'] * value['cantidad']);
	            impuestos += (value['impuestos'] * value['cantidad']);
	            promedio_comensal += total_persona;

	            if(total_persona > 0 && key == (resp['comanda']["rows"].length-1)) {
	                arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona, 1, 0, 3);
	                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
	            }
	        });
	        promedio_comensal = (promedio_comensal / resp['objeto']['num_comensales']); 
	        var propina = 0;
	        if(resp['comanda']['mostrar'] == 1){ 
	            propina = (total_comanda * 0.10);
	            propina = parseFloat(propina);
	            propina = propina.toFixed(2);
	            arrayTicket = comandera.formatearTicket(arrayTicket, 'Propina sugerida: $'+propina, 1, 0, 3);

	        } 
	        if(resp['que_mostrar']["mostrar_iva"] == 1){
	            arrayTicket = comandera.formatearTicket(arrayTicket, 'IVA incluido.', 0, 0, 1);

	        }

	        console.log("====Ã‡==========> --1-1-1-1-1-1-11");
	        console.log(total_comanda);

	        total_comanda = parseFloat(total_comanda);
	        total_comanda = total_comanda.toFixed(2);

	        arrayTicket = comandera.formatearTicket(arrayTicket, 'Total: $'+total_comanda, 1, 0, 3);

	        arrayTicket = comandera.formatearTicket(arrayTicket, arrayTicket[0]['codigo'], 0, 0, 2);
	        arrayTicket = comandera.formatearTicket(arrayTicket, 'Documento sin ninguna validez oficial', 0, 0, 2);
	        arrayTicket = comandera.formatearTicket(arrayTicket, 'by Foodware.', 0, 0, 3);

	       
	      
	        console.log(JSON.stringify(arrayTicket));
	        return JSON.stringify(arrayTicket);
		
		
        
	},

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

		// ch@
		var aux = 1;
		$.ajax({
                url: 'ajax.php?c=comandas&f=statusPedidos',			
				type: 'POST',
				dataType: 'json',
				data: {id: $objeto.idComanda},
				async: false            
        })
        .done(function(data) {        	
        	if(data == 0){
        		aux = 0;
        		$mensaje = 'No cuenta con pedidos activos';

        		$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
							arrowSize : 15
						});

				return 0;

        	}
        }) 
        if(aux == 0){ return false; }
        // ch@

		var isMobile = {
			mobilecheck : function() {
				//return true;
				return (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i.test(navigator.userAgent||navigator.vendor||window.opera)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test((navigator.userAgent||navigator.vendor||window.opera).substr(0,4)));
			}
		};
		comandera.ids_mesas = $objeto;
		var sep = comandera.datos_mesa_comanda['separar'];
		if(comandera.idioma == 0)
			comandera.get_idioma();
			
		// Detiene las cosultas del status de las mesas
		comandas.detener = 1;
		
		// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
	
		// Oculta la div de pagar
		$(".GtableCloseComanda").css('visibility', 'hidden');
		
		// Regresa un Json si se debe de mandar a caja, si no carga el HTML
		var $tipo = 'html';
		if($objeto['bandera'] == 2 || $objeto['bandera'] == 3 || isMobile.mobilecheck()){
			$tipo = 'json';
		}

		
		// Hace el pedido antes de cerrar la comanda(sirve en mandar a caja para el inventario)
		console.log("Tipo opera: "+$objeto['tipo_operacion']);
		//if($objeto['pedir'] == 1 || $objeto['tipo_operacion'] == 3){
		if($objeto['pedir'] == 1){
			comandera.pedir({
				id_comanda: $objeto['idComanda'],
				cerrar_comanda: 1
			});
		}
		
		// Valida que este abierta la caja si se quiere pagar en caja
		if ($objeto['bandera'] == 2) {
			var outElement = $("#tb2156-u", window.parent.document).parent();
			var caja = outElement.find("#tb2051-u");
			
			if (caja.length > 0) {
				// Todo bien :D
				var stringcaja = "#tb2051-u";
				var stringcaja2 = "#tb2051-1";
				var stringcaja3 = "#mnu_2051";
			}else{
				var caja2 = outElement.find("#tb2357-u");
				if (caja2.length > 0) {
				// Todo bien :D
					var stringcaja = "#tb2357-u";
					var stringcaja2 = "#tb2357-1";
					var stringcaja3 = "#mnu_2357";
				}else{
					if(comandera.idioma == 1)
						alert("No se Puede Cerrar Comanda, Favor de Abrir La Caja");
					else
						alert("Can not Close Command, Please Open Box");
						
					return;

				}
				
			}
		}
		
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

		if(moduloPrint == 1){
			$tipo = 'json';
			$objeto["banderita"] = 1;	
		}else{
			$objeto["banderita"] = 0;
		}

		if($objeto["bandera"] == 1){
			moduloPrint = 0;
			$tipo = 'html';
			$objeto["banderita"] = 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cerrar_comanda',
			type : 'POST',
			dataType : $tipo,
			async: false
		}).done(function(resp) {
			console.log('=========> Done cerrar_comanda 2');
			console.log(resp);
		
			
			// Limpia el div de los productos
			comandera.productos = ''; 
			comandera.area_inicio();
			
			// Elimina la mesa si es servicio a domicilio
			if($objeto['tipo'] == 2 || $objeto['tipo'] == 1){
				if($objeto['ver'] != 1){ 
					console.log('=========> Elimina la mesa');
					$("#"+$objeto['idmesa']).remove();
				}
			}
			// Elimina la mesa si es servicio a domicilio fin


			// ELIMINA LA MESA CUANDO ES TEMPORAR DESDE LA COMANDERA	
			if(comandera.datos_mesa_comanda['mesaTipo'] == 3){
				$.ajax({
					data : {id_mesa:comandera.datos_mesa_comanda['id_mesa'],idempleado:comandera.datos_mesa_comanda['idempleado']},
					url : 'ajax.php?c=comandas&f=eliminaMesa',
					type : 'POST',
					async: false
				}).done(function(resp) {
					console.log('=========> Elimina la mesa');
					$("#"+$objeto['idmesa']).remove();
				});				
			}
			// ELIMINA LA MESA CUANDO ES TEMPORAR DESDE LA COMANDERA
				

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
					var caja = outElement.find(stringcaja);
					var pestana = $("body", window.parent.document).find(stringcaja2);
					var openCaja = $("body", window.parent.document).find(stringcaja3);
					var pathname = window.location.pathname;
					var url = document.location.host + pathname;

					if (caja.length > 0) {
					// Valida que exista un codigo
						if (!codigo) {
							if(comandera.idioma == 1)
								$mensaje = 'Error al obtener el codigo de la comanda';
							else
								$mensaje = 'Error getting command code';
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
					console.log("si begbgtnrghftrbnrt");
					console.log($objeto);
						$.ajax({
							data : $objeto,
							url : 'ajax.php?c=comandas&f=actualizar_comanda',
							type : 'POST',
							dataType : 'json',
						}).done(function(resp) {							
							console.log('---------> Success actualizar_comanda');
							console.log(resp);

							// Valida si la comanda se cierra por persona o normal
							if ($objeto['cerrar_persona'] != 1) {
								// Si es reimprimir no dirige al mapa de mesas
								if ($objeto['reimprime'] != 1) {
									console.log('======> salta reimprime');
									console.log(comandera.ajustes['tipo_operacion']);
									
								// Recarga la pagina en lugar de redirigir al mapa de mesas
									if (comandera.ajustes['tipo_operacion'] == 3) {
										console.log('======> Entra tipo operacion 3');

									// Carga una nueva comanda en la mesa
										comandera.mandar_mesa_comandera({
											id_mesa: $objeto['idmesa'],
											tipo: 0,
											id_comanda: '',
											tipo_operacion: comandera.ajustes['tipo_operacion']
										});
										
										setTimeout(function() {
										// Abre la pestaÃ±a de caja
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
									// Abre la pestaÃ±a de caja
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
										
										if(comandera.datos_mesa_comanda['separar'] == 1 || comandera.datos_mesa_comanda['tipo'] != 0){
											comandera.datos_mesa_comanda['separar'] = '';
											console.log("lala1");
											comandera.repintar_mesas(comandera.ids_mesas);
											$("#modal_comandera").click();
										}else{
										// Quita la comanda de la mesa
											$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
											if(comandera['datos_mesa_comanda']['tipo_mesa'] == 1)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 2)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 3)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 4)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 5)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 6)
												$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/sillones.png");
											else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 9)
												$('#silla_' + $objeto['idmesa']).css("background-color", "#423228");
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
									id_comanda: '',
									tipo_operacion: comandera.ajustes['tipo_operacion']
								});

							}
						}).fail(function(resp) {
							console.log('---------> Fail actualizar_comanda');
							console.log(resp);
							if(comandera.idioma == 1)
								$mensaje = 'Error al actualizar la comanda 2';
							else
								$mensaje = 'Error updating command';
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
						if(comandera.idioma == 1)
							alert("No se Puede Cerrar Comanda, Favor de Abrir La Caja");
						else
							alert("Can not Close Command, Please Open Box");
					}
				}
				return 0;
			} // FIN La comanda se cierra pagando directo en caja
			
			// La comanda se manda a caja
			if ($objeto['bandera'] == 3) {
				if (resp['rows'][0]['respuesta'] == "ok") {
				// Recarga la comandera con una nueva comanda
					if (comandera.ajustes['tipo_operacion'] == 3) {
						console.log('======> Entra tipo operacion 3');
						comandera.mandar_mesa_comandera({
							id_mesa : $objeto['idmesa'],
							tipo : 0,
							id_comanda : '',
							tipo_operacion: comandera.ajustes['tipo_operacion']
						});
					} else {
					// Separa las mesas juntas
						if (comandera.datos_mesa_comanda['separar'] == 1) {
							comandera.datos_mesa_comanda['separar'] = '';
							console.log("lala2");
							comandera.repintar_mesas(comandera.ids_mesas);
							$("#modal_comandera").click();
					// Quita la comanda de la mesa
						} else {
							$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
							if(comandera['datos_mesa_comanda']['tipo_mesa'] == 1)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 2)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 3)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 4)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 5)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 6)
								$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/sillones.png");
							else if(comandera['datos_mesa_comanda']['tipo_mesa'] == 9)
								$('#silla_' + $objeto['idmesa']).css("background-color", "#423228");
							$("#div_tiempo_" + $objeto['idmesa']).html('');
							$("#div_total_" + $objeto['idmesa']).html('');

						// Oculta la ventana modal
							$("#modal_comandera").click();
						}
					}
				} else {
					if(comandera.idioma == 1)
						alert("Error al cerrar la comanda");
					else
						alert("Error closing command");
				}				
				return 0;
			}
			
			if(isMobile.mobilecheck()){
				var ya_mesa = 0;
		        var arrayTicket = [];
		        var persona = -1;
		        var total_persona = 0;
		        var total_comanda = 0;
		        var costo_extra = 0;
		        var impuestos = 0;
		        var promedio_comensal = 0;
		        arrayTicket.push({'logo' : '', 'codigo' : '', 'qr' : '', 'tipo' : 2, 'type' : ''});
		        if(!isEmptyF(resp['comanda']['logo'])){
		           	arrayTicket[0]['logo']= resp['comanda']['logo'];
		            arrayTicket[0]['type']= resp['comanda']['type'];
		        } 
		        $.each(resp['comanda']['rows'], function(key, value) {
		            if(resp['que_mostrar']["switch_info_ticket"] == 1 && ya_mesa==0) {
		                ya_mesa = 1;
		                if (resp['que_mostrar']["mostrar_info_empresa"] == 1) {
		                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['nombreorganizacion'], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'RFC: '+resp['organizacion'][0]['RFC'], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['datos_sucursal'][0]['direccion']+" "+resp['datos_sucursal'][0]['municipio']+","+resp['datos_sucursal'][0]['estado'], 0, 0, 2);
		                    if(resp['organizacion'][0]['paginaweb']!='-'){
		                        arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['paginaweb'], 0, 0, 2);
		                    }
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Sucursal: '+resp['datos_sucursal'][0]["nombre"], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Apertura: '+resp['objeto']['f_ini'], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Cierre: '+resp['fecha_fin'], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesero: '+resp['objeto']['mesero'], 0, 0, 2);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Personas: '+resp['objeto']['personas'], 0, 0, 2);

		                }
		                if (value['tipo'] != 2 && value['tipo'] != 1 && $.isNumeric(value['nombreu'])) {
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: '+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
		                } else {
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: '+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
		                }
		                arrayTicket = comandera.formatearTicket(arrayTicket, value['codigo'], 0, 0, 2);
		                arrayTicket[0]['codigo'] = value['codigo']; 
		                if(value['tipo'] == 1 || value['tipo'] == 2){ 
		                    if(resp['que_mostrar']["mostrar_nombre"] == 1) {
		                        arrayTicket = comandera.formatearTicket(arrayTicket, 'Cliente: '+value['nombreu'], 0, 0, 2);
		                     }
		                     if(resp['que_mostrar']["mostrar_domicilio"] == 1) { 
		                        if(value['domicilio']){
		                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Domicilio: '+value['domicilio'], 0, 0, 2);
		                        }
		                     }
		                     if(resp['que_mostrar']["mostrar_tel"] == 1) { 
		                        if(resp['comanda']['tel']){
		                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Tel: '+resp['comanda']['tel'], 0, 0, 2);
		                        }
		                     }
		                }
		            } 
		            if(persona != value['npersona']){
		                if(total_persona > 0) {
		                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona, 1, 0, 3);
		                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
		                }
		                arrayTicket = comandera.formatearTicket(arrayTicket, 'Orden No: '+value['npersona'], 0, 0, 2);
		                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
		                arrayTicket = comandera.formatearTicketProducts(arrayTicket, 'Cant.', 'Producto', 'Total', 1, 0);
		                total_persona = 0;
		                persona = value['npersona'];
		                //codigo = $value['codigo'];
		            }
		            arrayTicket = comandera.formatearTicketProducts(arrayTicket, value['cantidad'], value['nombre'], '$'+(value['precioventa'] * value['cantidad']).toFixed(2), 1, 0);

		            if(value['costo_extra']){
		                costo_extra = 0;

		                $.each(value['costo_extra'], function(k, v) {
		                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Extra: '+v['nombre'], '$'+(v['costo'] * value['cantidad']).toFixed(2), 0, 0);
		                    costo_extra = parseFloat(costo_extra) + parseFloat((parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2));
		                });

		            // Calcula totales
		                total_persona += costo_extra;
		                total_comanda += costo_extra;
		            } //Fin costo extra

		            if(value['costo_complementos']){
		                costo_extra = 0;

		                $.each(value['costo_complementos'], function(k, v) {
		                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Complemento: '+v['nombre'], '$'+(parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2), 0, 0);
		                    costo_extra = parseFloat(costo_extra) + parseFloat((parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2));

		                });
		                // Calcula totales
		                    total_persona += costo_extra;
		                    total_comanda += costo_extra;
		            } //Fin costo complementoss

		            total_persona += (parseFloat(value['precioventa']) * parseFloat(value['cantidad']));
		            total_comanda += (parseFloat(value['precioventa']) * parseFloat(value['cantidad']));
		            impuestos += (parseFloat(value['impuestos']) * parseFloat(value['cantidad']));
		            promedio_comensal += total_persona;

		            if(total_persona > 0 && key == (resp['comanda']["rows"].length-1)) {
		                arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona.toFixed(2), 1, 0, 3);
		                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
		            }
		        });
		        promedio_comensal = (promedio_comensal / resp['objeto']['num_comensales']); 
		        var propina = 0;
		        if(resp['comanda']['mostrar'] == 1){ 
		            propina = (total_comanda * 0.10);
		            propina = parseFloat(propina);
		            propina = propina.toFixed(2);
		            arrayTicket = comandera.formatearTicket(arrayTicket, 'Propina sugerida: $'+propina, 1, 0, 3);

		        } 
		        if(resp['que_mostrar']["mostrar_iva"] == 1){
		            arrayTicket = comandera.formatearTicket(arrayTicket, 'IVA incluido.', 0, 0, 1);

		        }

		        total_comanda = parseFloat(total_comanda);
		        total_comanda = total_comanda.toFixed(2);

		        arrayTicket = comandera.formatearTicket(arrayTicket, 'Total: $'+total_comanda, 1, 0, 3);

		        arrayTicket = comandera.formatearTicket(arrayTicket, arrayTicket[0]['codigo'], 0, 0, 2);
		        arrayTicket = comandera.formatearTicket(arrayTicket, 'Documento sin ninguna validez oficial', 0, 0, 2);
		        arrayTicket = comandera.formatearTicket(arrayTicket, 'by Foodware.', 0, 0, 3);

		       comandas.actualizar_comanda({
					id: $objeto['idComanda'], 
					total: total_comanda
				});
		      	var jsV = JSON.stringify(arrayTicket);
				jsV = jsV.replace(/#/g, '');
				console.log(jsV);
				var navegador1 = (navigator.userAgent.indexOf('Firefox') != -1) ? 1 : ((navigator.userAgent.indexOf("Chrome") != -1) ? 2 : 0);
				var pestana1 = window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';S.navegador='+ navegador1 +';end'); 
				pestana1.close();
		        //window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';end'); 
				
				if ($objeto['cerrar_persona'] != 1) {
					// Si es reimprimir no dirige al mapa de mesas
					if ($objeto['reimprime'] != 1) {
						console.log('======> entra reimprime');
						console.log(comandera.ajustes['tipo_operacion']);
					
						// Recarga la comandera con una nueva comanda
						if (comandera.ajustes['tipo_operacion'] == 3) {
							console.log('======> Entra tipo operacion 3');
							comandera.mandar_mesa_comandera({
								id_mesa: $objeto['idmesa'],
								tipo: 0,
								id_comanda: '',
								tipo_operacion: comandera.ajustes['tipo_operacion']
							});
							
						// Activa las cosultas del status de las mesas
							comandas.detener = 0;
						} else {
						// Separa las mesas juntas
						console.log($objeto);
							if (sep == 1) {
								comandera.datos_mesa_comanda['separar'] = '';
								console.log("lala3");
								comandera.repintar_mesas($objeto);
								$("#modal_comandera").click();
						// Quita la comanda de la mesa
							} else {
							// Activa las cosultas del status de las mesas
								comandas.detener = 0;
								
							// Limpia los datos de la comanda
								datos_mesa_comanda = {};
								$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
								if($objeto['tipo_mesa'] == 1)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
								else if($objeto['tipo_mesa'] == 2)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
								else if($objeto['tipo_mesa'] == 3)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
								else if($objeto['tipo_mesa'] == 4)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
								else if($objeto['tipo_mesa'] == 5)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
								else if($objeto['tipo_mesa'] == 6)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/sillones.png");
								else if($objeto['tipo_mesa'] == 9)
									$('#silla_' + $objeto['idmesa']).css("background-color", "#423228");
								$("#div_tiempo_" + $objeto['idmesa']).html('');
								$("#div_total_" + $objeto['idmesa']).html('');
								
							// Oculta la ventana modal
								$("#modal_comandera").click();
								
							}
						}
					}
				} else {
					console.log('======> Recarga la comandera con una nueva comanda');
					
				// Activa las cosultas del status de las mesas
					comandas.detener = 0;
					
					comandera.mandar_mesa_comandera({
						id_mesa: $objeto['idmesa'],
						tipo: 0,
						id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
						tipo_operacion: comandera.ajustes['tipo_operacion']
					});
				}
			} else {
				//web LM
				if(moduloPrint == 1){
					var ya_mesa = 0;
			        var arrayTicket = [];
			        var persona = -1;
			        var total_persona = 0;
			        var total_comanda = 0;
			        var costo_extra = 0;
			        var impuestos = 0;
			        var promedio_comensal = 0;
			        arrayTicket.push({'logo' : '', 'codigo' : '', 'qr' : '', 'tipo' : 2, 'type' : ''});
			        if(!isEmptyF(resp['comanda']['logo'])){
			           	arrayTicket[0]['logo']= resp['comanda']['logo'];
			            arrayTicket[0]['type']= resp['comanda']['type'];
			        } 
			        $.each(resp['comanda']['rows'], function(key, value) {
			            if(resp['que_mostrar']["switch_info_ticket"] == 1 && ya_mesa==0) {
			                ya_mesa = 1;
			                if (resp['que_mostrar']["mostrar_info_empresa"] == 1) {
			                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['nombreorganizacion'], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'RFC: '+resp['organizacion'][0]['RFC'], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, resp['datos_sucursal'][0]['direccion']+" "+resp['datos_sucursal'][0]['municipio']+","+resp['datos_sucursal'][0]['estado'], 0, 0, 2);
			                    if(resp['organizacion'][0]['paginaweb']!='-'){
			                        arrayTicket = comandera.formatearTicket(arrayTicket, resp['organizacion'][0]['paginaweb'], 0, 0, 2);
			                    }
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Sucursal: '+resp['datos_sucursal'][0]["nombre"], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Apertura: '+resp['objeto']['f_ini'], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Cierre: '+resp['fecha_fin'], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesero: '+resp['objeto']['mesero'], 0, 0, 2);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Personas: '+resp['objeto']['personas'], 0, 0, 2);

			                }
			                if (value['tipo'] != 2 && value['tipo'] != 1 && $.isNumeric(value['nombreu'])) {
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: '+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
			                } else {
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Mesa: '+resp['comanda']['rows'][0]['nombre_mesa'], 0, 0, 2);
			                }
			                arrayTicket = comandera.formatearTicket(arrayTicket, value['codigo'], 0, 0, 2);
			                arrayTicket[0]['codigo'] = value['codigo']; 
			                if(value['tipo'] == 1 || value['tipo'] == 2){ 
			                    if(resp['que_mostrar']["mostrar_nombre"] == 1) {
			                        arrayTicket = comandera.formatearTicket(arrayTicket, 'Cliente: '+value['nombreu'], 0, 0, 2);
			                     }
			                     if(resp['que_mostrar']["mostrar_domicilio"] == 1) { 
			                        if(value['domicilio']){
			                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Domicilio: '+value['domicilio'], 0, 0, 2);
			                        }
			                     }
			                     if(resp['que_mostrar']["mostrar_tel"] == 1) { 
			                        if(resp['comanda']['tel']){
			                            arrayTicket = comandera.formatearTicket(arrayTicket, 'Tel: '+resp['comanda']['tel'], 0, 0, 2);
			                        }
			                     }
			                }
			            } 
			            if(persona != value['npersona']){
			                if(total_persona > 0) {
			                    arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona, 1, 0, 3);
			                    arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
			                }
			                arrayTicket = comandera.formatearTicket(arrayTicket, 'Orden No: '+value['npersona'], 0, 0, 2);
			                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
			                arrayTicket = comandera.formatearTicketProducts(arrayTicket, 'Cant.', 'Producto', 'Total', 1, 0);
			                total_persona = 0;
			                persona = value['npersona'];
			                //codigo = $value['codigo'];
			            }
			            arrayTicket = comandera.formatearTicketProducts(arrayTicket, value['cantidad'], value['nombre'], '$'+(value['precioventa'] * value['cantidad']).toFixed(2), 1, 0);

			            if(value['costo_extra']){
			                costo_extra = 0;

			                $.each(value['costo_extra'], function(k, v) {
			                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Extra: '+v['nombre'], '$'+(v['costo'] * value['cantidad']).toFixed(2), 0, 0);
			                    costo_extra = parseFloat(costo_extra) + parseFloat((parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2));
			                });

			            // Calcula totales
			                total_persona += costo_extra;
			                total_comanda += costo_extra;
			            } //Fin costo extra

			            if(value['costo_complementos']){
			                costo_extra = 0;

			                $.each(value['costo_complementos'], function(k, v) {
			                    arrayTicket = comandera.formatearTicketProducts(arrayTicket, '', '=> Complemento: '+v['nombre'], '$'+(parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2), 0, 0);
			                    costo_extra = parseFloat(costo_extra) + parseFloat((parseFloat(v['costo']) * parseFloat(value['cantidad'])).toFixed(2));

			                });
			                // Calcula totales
			                    total_persona += costo_extra;
			                    total_comanda += costo_extra;
			            } //Fin costo complementoss

			            total_persona += (parseFloat(value['precioventa']) * parseFloat(value['cantidad']));
			            total_comanda += (parseFloat(value['precioventa']) * parseFloat(value['cantidad']));
			            impuestos += (parseFloat(value['impuestos']) * parseFloat(value['cantidad']));
			            promedio_comensal += total_persona;

			            if(total_persona > 0 && key == (resp['comanda']["rows"].length-1)) {
			                arrayTicket = comandera.formatearTicket(arrayTicket, 'Total de la orden No. '+persona+': '+'$'+total_persona.toFixed(2), 1, 0, 3);
			                arrayTicket = comandera.formatearTicket(arrayTicket, '________________________________', 1, 0, 1);
			            }
			        });
			        promedio_comensal = (promedio_comensal / resp['objeto']['num_comensales']); 
			        var propina = 0;
			        if(resp['comanda']['mostrar'] == 1){ 
			            propina = (total_comanda * 0.10);
			            propina = parseFloat(propina);
			            propina = propina.toFixed(2);
			            arrayTicket = comandera.formatearTicket(arrayTicket, 'Propina sugerida: $'+propina, 1, 0, 3);

			        } 
			        if(resp['que_mostrar']["mostrar_iva"] == 1){
			            arrayTicket = comandera.formatearTicket(arrayTicket, 'IVA incluido.', 0, 0, 1);

			        }

			        total_comanda = parseFloat(total_comanda);
			        total_comanda = total_comanda.toFixed(2);

			        arrayTicket = comandera.formatearTicket(arrayTicket, 'Total: $'+total_comanda, 1, 0, 3);

			        arrayTicket = comandera.formatearTicket(arrayTicket, arrayTicket[0]['codigo'], 0, 0, 2);
			        arrayTicket = comandera.formatearTicket(arrayTicket, 'Documento sin ninguna validez oficial', 0, 0, 2);
			        arrayTicket = comandera.formatearTicket(arrayTicket, 'by Foodware.', 0, 0, 3);

			        var segundo = 0;
        			var impresionTexto = "";

            		$.each(arrayTicket, function(index, element){
		                if(segundo == 0){
		                    segundo = 1;
		                }else{
		                    impresionTexto = impresionTexto + element.texto + "\n";
		                }
		            });

		            console.log('ajax.php?c=impresion&f=insertar');

		            $.ajax({
		                url: 'ajax.php?c=impresion&f=insertar' ,
		                type: 'POST',
		                data: {area : "Caja", ticket : impresionTexto, codigo : arrayTicket[0].codigo},
		            })
		            .done(function(resp) {

		            })
		            .fail(function(err) {
		            	
		                console.log(err);
		            });		
				}else{
					// Ejecuta los scripts de la comanda
					$("#div_ejecutar_scripts").html(resp);
					
					//abrimos una ventana vacÃ­a nueva
					var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=8px,rightmargin=8px');

					$(ventana).ready(function() {
						// Cargamos la vista ala nueva ventana
						ventana.document.write(resp);
						// Cerramos el documento
						ventana.document.close();

						// Imprimimos la ventana y la cierra despues de un segundo
						//alert("imprimiendo ticket");
						setTimeout(closew, 3000);
						function closew() {
							ventana.print();
							//alert("imprimiendo ticket");
							ventana.close();
						} //Fin funcion closew
					});
				}
				// Valida si la comanda se cierra por persona o normal
				if ($objeto['cerrar_persona'] != 1) {
					// Si es reimprimir no dirige al mapa de mesas
					if ($objeto['reimprime'] != 1) {
						console.log('======> entra reimprime');
						console.log(comandera.ajustes['tipo_operacion']);
					
						// Recarga la comandera con una nueva comanda
						if (comandera.ajustes['tipo_operacion'] == 3) {
							console.log('======> Entra tipo operacion 3');
							comandera.mandar_mesa_comandera({
								id_mesa: $objeto['idmesa'],
								tipo: 0,
								id_comanda: '',
								tipo_operacion: comandera.ajustes['tipo_operacion']
							});
							// Activa las cosultas del status de las mesas
							comandas.detener = 0;
						} else {
							// Separa las mesas juntas
							console.log($objeto);
							if (sep == 1) {
								comandera.datos_mesa_comanda['separar'] = '';
								console.log("lala3");
								comandera.repintar_mesas($objeto);
								$("#modal_comandera").click();
							// Quita la comanda de la mesa
							} else {
							// Activa las cosultas del status de las mesas
								comandas.detener = 0;
								
							// Limpia los datos de la comanda
								datos_mesa_comanda = {};
								$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
								if($objeto['tipo_mesa'] == 1)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
								else if($objeto['tipo_mesa'] == 2)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
								else if($objeto['tipo_mesa'] == 3)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
								else if($objeto['tipo_mesa'] == 4)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
								else if($objeto['tipo_mesa'] == 5)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
								else if($objeto['tipo_mesa'] == 6)
									$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/sillones.png");
								else if($objeto['tipo_mesa'] == 9)
									$('#silla_' + $objeto['idmesa']).css("background-color", "#423228");
								$("#div_tiempo_" + $objeto['idmesa']).html('');
								$("#div_total_" + $objeto['idmesa']).html('');
								
							// Oculta la ventana modal
								$("#modal_comandera").click();

							}
						}
					}
				} else {
					console.log('======> Recarga la comandera con una nueva comanda');
					
					// Activa las cosultas del status de las mesas
					comandas.detener = 0;
					
					comandera.mandar_mesa_comandera({
						id_mesa: $objeto['idmesa'],
						tipo: 0,
						id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
						tipo_operacion: comandera.ajustes['tipo_operacion']
					});
				}
			}	
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

		setTimeout(function() {
			//// SI ES COMANDA CON SILLAS JUNTAS REFESCA EL MAPA DE MESAS CH 
			if($objeto['sillas'] == 1){
				//var pathname = window.location.pathname;
				//$("#tb2156-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
				//$("#tb2156-u .frurl", window.parent.document).attr('src', 'index.php?c=comandas&f=menuMesas');
				window.location.reload();
			}
			//// SI ES COMANDA CON SILLAS JUNTAS REFESCA EL MAPA DE MESAS CH FIN
		}, 700);
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_comensales',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_comensales');
			console.log(resp);
		
		// Guarda el numero de comensales en la variable de comanda
			comandera['datos_mesa_comanda']['info_mesa']['comensales'] = $objeto['comensales'];
			
			if(comandera.idioma == 1)
				var $mensaje = 'Comensales guardados';
			else
				var $mensaje = 'Saved diners';
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

			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar el numero de comensales';
			else
				var $mensaje = 'Failed to save the number of guests';
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
		comandas.totales_comandas = {};
		comandas.porcentajes_comandas= {};
		comandas.precio_comandas = {};
    	if(comandera.idioma == 0)
			comandera.get_idioma();
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

///////////////// ******** ---- 		FIN cerrar_personalizado		------ ************ //////////////////

///////////////// ******** ---- 		cerrar_comanda_persona			------ ************ //////////////////
//////// Genera una comanda de la persona y la cierra
	// Como parametros recibe:
		// persona -> Numero de persona	
		// id_comanda -> ID de la comanda

	cerrar_comanda_persona : function($objeto) {
		console.log('===============> objeto cerrar_comanda_persona');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cerrar_comanda_persona',
			type : 'POST',
			dataType : 'json',
			async: false
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
				tipo_operacion : comandera.ajustes['tipo_operacion'],
				cerrar_persona: 1,
				mesero: comandera.datos_mesa_comanda['mesero'],
				persona: $objeto['persona'],
				f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp'],
				id_comanda_padre: resp['id_comanda_padre']
			});
		}).fail(function(resp) {
			console.log('================= Fail cerrar_comanda_persona');
			console.log(resp);
			
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_familias',
			type : 'POST',
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al buscar las familias';
			else
				var $mensaje = 'Error fetching families';
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
		if(comandera.idioma == 0)
			comandera.get_idioma();	
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_lineas',
			type : 'POST',
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al buscar las lineas';
			else
				var $mensaje = 'Error fetching the lines';
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
		if(comandera.departamentos){
			$('#div_departamentos').html(comandera.departamentos);
		}
	},
	
///////////////// ******** ---- 			FIN listar_lineas					------ ************ //////////////////

///////////////// ******** ---- 				borrar_persona					------ ************ //////////////////
//////// Elimina la persona de la comanda y sus pedidos
	// Como parametros recibe:
		// id_comanda -> ID de la comanda
		// persona -> ID de la persona
		// btn -> Boton del loader
		// pass -> ContraseÃ±a de seguridad
			
	borrar_persona : function($objeto) {
		$("#div_productos").hide();
		//$('.btn').prop( "disabled", true );
		//$('.act').prop( "disabled", false );	
		console.log('=========> objeto borrar_persona');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=borrar_persona',
			type : 'POST',
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
					id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
					tipo_operacion: comandera.ajustes['tipo_operacion']
				});
				
			// ContraseÃ±a incorrecta		
				if(comandera.idioma == 1)	
					var $mensaje = 'Persona eliminada';
				else
					var $mensaje = 'Person deleted';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
			
				return 0;
			}
		
		// ContraseÃ±a incorrecta		
			if(comandera.idioma == 1)		
				var $mensaje = 'ContraseÃ±a incorrecta';
			else
				var $mensaje = 'Incorrect password';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
		//$('.btn').prop( "disabled", false );
		//$("#div_productos").show();
		}).fail(function(resp) {
			console.log('=========> Fail borrar_persona');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al borrar la persona';
			else
				var $mensaje = 'Error deleting the person';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
			//$('.btn').prop( "disabled", false );
			//$("#div_productos").show();
		});
	},
	
///////////////// ******** ---- 			FIN borrar_persona					------ ************ //////////////////

///////////////// ******** ---- 			autoriza_asignacion					------ ************ //////////////////
//////// Obtiene la contraseÃ±a de seguridad y autoriza la asignacion de la mesa
	// Como parametros puede recibir:
		//	pass -> contraseÃ±a a bsucar
	
	autoriza_asignacion : function($objeto) {
		console.log('--------> Objet autoriza_asignacion');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done autoriza_asignacion');
			console.log(resp);
		
		// Pass incorrecto
			if (resp != $objeto['pass']) {
				if(comandera.idioma == 1)
					var $mensaje = 'ContraseÃ±a incorrecta';
				else
					var $mensaje = 'Incorrect password';
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al autorizar';
			else
				var $mensaje = 'Authorization failed';
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=asignar_mesa',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done asignar_mesa');
			console.log(resp);
			if(comandera.idioma == 1)
				var $mensaje = 'Asignacion guardada';
			else
				var $mensaje = 'Saved assignment';
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al asignar la mesa';
			else
				var $mensaje = 'Failed to assign table';
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Envia un mensaje que la comanda se esta mudando
		if(comandera.idioma == 1)
			var $mensaje = 'Mudando comanda ...';
		else
			var $mensaje = 'Changing commands ...';
		$('#mesa_mudar_' + $objeto['mesa']).notify($mensaje, {
			position : "top center",
			autoHide : true,
			autoHideDelay : 5000,
			className : 'success',
		});

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=mudar_comanda',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done mudar_comanda');
			console.log(resp);
			console.log('=========> $objeto');
			console.log($objeto);

		// Quita la comanda de la mesa
			$("#mesa_" + $objeto['mesa_origen']).attr('id_comanda', '');
			if($objeto['origen_tipo_mesa'] == 1)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
			else if($objeto['origen_tipo_mesa'] == 2)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
			else if($objeto['origen_tipo_mesa'] == 3)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
			else if($objeto['origen_tipo_mesa'] == 4)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
			else if($objeto['origen_tipo_mesa'] == 5)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
			else if($objeto['origen_tipo_mesa'] == 6)
				$('#img_' + $objeto['mesa_origen']).attr("src", "images/mapademesas/sillones.png");
			else if($objeto['origen_tipo_mesa'] == 9)
				$('#silla_' + $objeto['mesa_origen']).css("background-color", "#423228");
			//$('#mesa_' + $objeto['mesa_origen']).css('background-color', '#FFFFFF');
			$("#div_tiempo_" + $objeto['mesa_origen']).html('');
			$("#div_total_" + $objeto['mesa_origen']).html('');
			
		// Guarda la comanda en la nueva mesa
			$("#mesa_" + $objeto['mesa']).attr('id_comanda', resp);
			//$('#mesa_' + $objeto['mesa']).css('background-color', '#FF6961');
			if($objeto['tipo_mesa'] == 1)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/ocupada_cuadrada_2p.png");
			else if($objeto['tipo_mesa'] == 2)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/ocupada_cuadrada.png");
			else if($objeto['tipo_mesa'] == 3)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/rectangulo_2p_ocupada.png");
			else if($objeto['tipo_mesa'] == 4)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/ocupada_redonda_4p.png");
			else if($objeto['tipo_mesa'] == 5)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/ocupada_redonda_2p.png");
			else if($objeto['tipo_mesa'] == 6)
				$('#img_' + $objeto['mesa']).attr("src", "images/mapademesas/sillon_ocupado.png");
			else if($objeto['tipo_mesa'] == 9)
				$('#silla_' + $objeto['mesa']).css("background-color", "#6b4583");

			// Oculta la ventana modal
				$("#modal_comandera").click();
				$("#div_mudar").click();
			
		// Recarga la pagina
			//var pathname = window.location.pathname;
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
		}).fail(function(resp) {
			console.log('=========> Fail mudar_comanda');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			if(comandera.idioma == 1)
				var $mensaje = 'Error al mudar la comanda';
			else
				var $mensaje = 'Error changing command';
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
//////// Obtiene la contraseÃ±a de seguridad y elimina la mesa si es correcta
	// Como parametros puede recibir:
		//	pass -> contraseÃ±a a bsucar	
		
	eliminar_comanda : function($objeto) {
		console.log('=========> objeto eliminar_comanda');
		console.log($objeto);
		comandera.ids_mesas = $objeto;
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done autorizar eliminar_comanda');
			console.log(resp);
			if (resp != $objeto['pass']) {
				if(comandera.idioma == 1)
					var $mensaje = 'ContraseÃ±a incorrecta';
				else
					var $mensaje = 'Incorrect password';
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
				type : 'POST',
			}).done(function(resp) {
				console.log('=========> Done eliminar_comanda');
				console.log(resp);
				if(comandera.idioma == 1)
					var $mensaje = 'Comanda eliminada';
				else
					var $mensaje = 'Command deleted';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			
				if($objeto['tipo'] == 2 || $objeto['tipo'] == 1){
					console.log('=========> Elimina la mesa');
					$("#"+$objeto['idmesa']).remove();
				}
		    // Inicializamos variables del combo
		    	comandera.pedidos_seleccionados = {};
		    	comandera.datos_combo = {};
		    	comandera.combos = [];
	    	
			// Cierra la ventana modal
				$('#modal_eliminar_comanda').click();
				
			// Recarga la comandera con una nueva comanda
				if (comandera.ajustes['tipo_operacion'] == 3) {
					console.log('======> Entra tipo operacion 3');
					
					comandera.mandar_mesa_comandera({
						id_mesa : $objeto['idmesa'],
						tipo : 0,
						id_comanda : '',
						tipo_operacion: comandera.ajustes['tipo_operacion']
					});
				
				} else {
					if (comandera.datos_mesa_comanda['separar'] == 1) {
						console.log("lala4");
						comandera.repintar_mesas(comandera.ids_mesas);
						$("#modal_comandera").click();
					} else {
					// Quita la comanda de la mesa
						$("#mesa_" + $objeto['idmesa']).attr('id_comanda', '');
						if($objeto['tipo_mesa'] == 1)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
						else if($objeto['tipo_mesa'] == 2)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
						else if($objeto['tipo_mesa'] == 3)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
						else if($objeto['tipo_mesa'] == 4)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_4ps.png");
						else if($objeto['tipo_mesa'] == 5)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/libre_redonda_2ps.png");
						else if($objeto['tipo_mesa'] == 6)
							$('#img_' + $objeto['idmesa']).attr("src", "images/mapademesas/sillones.png");
						else if($objeto['tipo_mesa'] == 9 || $objeto['tipo_mesa'] == 7){
							$('#silla_' + $objeto['idmesa']).css("background-color", "#423228");
						}
						$("#div_tiempo_" + $objeto['idmesa']).html('');
						$("#div_total_" + $objeto['idmesa']).html('');

					// Oculta la ventana modal
						$("#modal_comandera").click();
					}
				}	

				$.ajax({
					url: 'ajax.php?c=comandas&f=mesasDeLaSession',
					type: 'POST',
					dataType: 'html',
					//data: {param1: 'value1'},
				})
				.done(function(respMesas) {
					
					console.log(respMesas);
					//alert(respMesas);
					$('#div_mesas2').html('');
					$('#div_mesas2').html(respMesas);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});


			}).fail(function(resp) {
				console.log('=========> Fail mudar_comanda');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
				if(comandera.idioma == 1)
					var $mensaje = 'Error al eliminar la comanda';
				else
					var $mensaje = 'Failed to delete command';
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

			if(comandera.idioma == 1)
				var $mensaje = 'Error al autorizar la eliminacion';
			else
				var $mensaje = 'Error authorizing removal';
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

///////////////// ******** ---- 		get_idioma		------ ************ //////////////////
	//////// Obtiene el idioma guardado en configuracion
		// Como parametros puede recibir:
   repintar_mesas : function($objeto){
   		console.log('=========> objeto repintar_mesas');
		console.log($objeto);
   		$.ajax({
   			data : $objeto,
			url : 'ajax.php?c=comandas&f=repintar_mesas',
			type : 'POST',
			dataType : 'json'
		}).done(function(data) {
			console.log('done repintar_mesas');
			console.log(data);
			$.each(data, function(key, val) {
				$("#"+val['mesa']).remove();
				var html = '<div class="grid-stack-item mesa" id="'+val['mesa']+'" data-gs-x="'+val['x']+'" data-gs-y="'+val['y']+'" data-gs-no-resize="1" data-gs-width="'+val['width']+'" data-gs-height="'+val['height']+'"><div style="width:100%; height:100%;text-align: center;" class="grid-stack-item-content"><div ><img id="img_'+val['mesa']+'" style="max-width: 100%; ';
				if(val['id_tipo_mesa'] == 6) { 
				html = html + 'width: 101px; height: 97px;';
				} else {
				html = html + 'width: auto; height:100%; ';
				}
				if (val['mesero'] == null || val['mesero'] == 'null') {
					var mese = '';
				} else {
					var mese = val['mesero'];
				}
				html = html + '" src="'+val['imagen']+'"><div id="div_total_'+val['mesa']+'" class="price" style="display:none; font-size: 12px; color: white; position: absolute; left 0; top: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px" > <!-- En esta div se carga el total de la comanda --> </div> <div id="div_tiempo_'+val['mesa']+'" class="time" style="display: none; font-size: 12px; color: white; position: absolute; right: 0; bottom: 0; background-color: rgba(0,0,0,0.6); padding: 3px; border-radius: 15px"> <!-- En esta div se carga el tiempo que lleva abierta la comanda en el dia --> </div><div onclick="comandera.mandar_mesa_comandera({ id_mesa: '+val['mesa']+', tipo: 0, tipo_mesa: '+val['id_tipo_mesa']+', nombre_mesa_2: '+"'"+val['width']+"'"+', id_comanda: $(this).attr("id_comanda"), tipo_operacion: comandera.ajustes["tipo_operacion"]})" id="mesa_'+val['mesa']+'" id_comanda="0" data-toggle="modal" data-target="#modal_comandera" style="color: white;width: 55%; cursor:pointer; position: absolute; font-size: 11px;transform: translate(-50%, -50%); left: 50%;top: 50%;"><div id="mesero_'+val['mesa']+'" style="font-size:12px">'+mese+'</div><div id="div_nombre_mesa_'+val['mesa']+'" style="font-size: 18px;" >'+val['nombre_mesa']+'</div></div></div></div></div>';
				$("#contenedor-"+val["idDep"]).append(html);
				gridAct = $('#contenedor-'+val["idDep"]).data('gridstack');
				// Crea la mesa y la agrega a la cuadricula
				gridAct.addWidget = function(el, x, y, width, height, auto_position, min_width, max_width, min_height, max_height, id) {
					el = $(el);
					if ( typeof id != 'undefined')
						el.attr('id', id);
					if ( typeof x != 'undefined')
						el.attr('data-gs-x', x);
					if ( typeof y != 'undefined')
						el.attr('data-gs-y', y);
					if ( typeof width != 'undefined')
						el.attr('data-gs-width', width);
					if ( typeof height != 'undefined')
						el.attr('data-gs-height', height);
					if ( typeof min_width != 'undefined')
						el.attr('data-gs-min-width', min_width);
					if ( typeof max_width != 'undefined')
						el.attr('data-gs-max-width', max_width);
					if ( typeof min_height != 'undefined')
						el.attr('data-gs-min-height', min_height);
					if ( typeof max_height != 'undefined')
						el.attr('data-gs-max-height', max_height);
					if ( typeof auto_position != 'undefined')
						el.attr('data-gs-auto-position', auto_position ? 'yes' : null);
					this.container.append(el);
					this._prepare_element(el);
					this._update_container_height();

					return el;
				};
				gridAct.addWidget($('#'+val["mesa"]), val['x'], val['y'], val['width'], val['height'], false, null, null, null, null, val["mesa"]);
			});
		}).fail(function(data) {
			console.log('fail repintar_mesas');
			console.log(data);
		});

   },
///////////////// ******** ---- 		FIN get_idioma		------ ************ //////////////////

///////////////// ******** ---- 		get_idioma		------ ************ //////////////////
	//////// Obtiene el idioma guardado en configuracion
		// Como parametros puede recibir:
   get_idioma : function(){
   		$.ajax({
			url : 'ajax.php?c=comandas&f=get_idioma',
			type : 'POST',
			dataType : 'json',
			data : {
			},
		}).done(function(data) {
			console.log('done get_idioma');
			console.log(data);
			comandera.idioma = data;
		}).fail(function(data) {
			console.log('fail get_idioma');
			console.log(data);
			comandera.idioma = 1;
		});

   },
///////////////// ******** ---- 		FIN get_idioma		------ ************ //////////////////

///////////////// ******** ---- 				guardar_promedio_comensal			------ ************ //////////////////
//////// Registra el promedio por comensal de la comanda
	// Como parametros puede recibir:
		// 	promedio -> promedio por comensal de la comanda a registrar
		//	comanda -> id de la comanda	
	
	guardar_promedio_comensal : function($objeto) {
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_promedio_comensal',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-----> Response promedio comensal');
			console.log(resp);

		}).fail(function(resp) {
			console.log('=========> Fail guardar_promedio_comensal');
			console.log(resp);

		/*	if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar el promedio de por comensal';
			else
				var $mensaje = 'Failed to save average of per diner';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			}); */
		});
	},

///////////////// ******** ---- 			FIN guardar_promedio_comensal			------ ************ //////////////////

///////////////// ******** ---- 					validar_cuenta					------ ************ //////////////////
//////// Valida que la cuenta tenga pedidos
	// Como parametros puede recibir:
		// 	id_comanda -> ID de la comanda
	
	validar_cuenta : function($objeto) {
		
		var next = 1;
		$objeto['id_comanda'] = String($objeto['id_comanda']);
		$.ajax({ // ajax para verfificar status de comanda si esta pagada termina el proceso y refresca
			url: 'ajax.php?c=comandas&f=statusComanda',			
			type: 'POST',
			dataType: 'json',
			data: {id: $objeto['id_comanda']},
			async: false
		})
		.done(function(data) { // data es el status de la comanda
			if(data == 1){ // pagada
				alert('La comanda ya fue pagada');
				next = 0;
				// Falta Refescar comandera
			}
		})

		if(next==1){
			
			console.log('=========> $objeto validar_cuenta');
			console.log($objeto);
			if(comandera.idioma == 0)
				comandera.get_idioma();
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=comandas&f=validar_cuenta',
				type : 'POST',
				dataType : 'json',
			}).done(function(resp) {
				console.log('-----> Response validar_cuenta');
				console.log(resp);
			
			// Todo bien :D
				if(resp['status'] == 1){
					$('.GtableCloseComanda').css('visibility', 'visible');
					
					return 0;
				}
				if(comandera.idioma == 1)
					var $mensaje = 'Necesitas agregar pedidos';
				else
					var $mensaje = 'You need to add orders';
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

				if(comandera.idioma == 1)
					var $mensaje = 'Error al validar la cuenta';
				else
					var $mensaje = 'Error validating account';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
					arrowSize : 15
				});
			});
		}
		
	},

///////////////// ******** ---- 			FIN guardar_promedio_comensal			------ ************ //////////////////

///////////////// ******** ---- 				autorizar_pedido					------ ************ //////////////////
//////// Obtiene la contraseÃ±a de seguridad y autoriza la modificacion del pedido
	// Como parametros puede recibir:
		//	pass -> contraseÃ±a a bsucar
		// pedido -> ID del pedido
		// json -> 1 -> devuelve un json
		
	autorizar_pedido: function($objeto) {
		console.log('--------> Objet autorizar_pedido');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=validar_pass',
			type : 'POST',
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
			if(comandera.idioma == 1)
				var $mensaje = 'ContraseÃ±a incorrecta';
			else
				var $mensaje = 'Incorrect password';
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al autorizar';
			else
				var $mensaje = 'Authorization failed';
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=actualizar_tiempo_pedidos',
			type : 'POST',
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
			if(comandera.idioma == 1)
				var $mensaje = 'Tiempo guardado';
			else
				var $mensaje = 'Saved time';
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar los tiempos';
			else
				var $mensaje = 'Failed to save times';
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
			$('#editar_direccion_servicio_domicilio').val($objeto['direccion']);
			$('#editar_exterior_servicio_domicilio').val($objeto['exterior']);
			$('#editar_interior_servicio_domicilio').val($objeto['interior']);
			$('#editar_cp_servicio_domicilio').val($objeto['cp']);
			$('#editar_colonia_servicio_domicilio').val($objeto['colonia']);
			$('#editar_cel_servicio_domicilio').val($objeto['cel']);
			$('#editar_tel_servicio_domicilio').val($objeto['tel']);
			$('#editar_email_servicio_domicilio').val($objeto['email']);
			$('#editar_via_contacto_domicilio').val($objeto['via_contacto']);
			$('#editar_zona_reparto_domicilio').val($objeto['zona_reparto']);
			$('#id_cliente_domicilio').val($objeto['id']);
			$('.selectpicker').selectpicker('refresh');
		}
		
	// Para llevar
		if($objeto['para_llevar'] == 1){
			$('#editar_cliente_para_llevar').val($objeto['nombre']);
			$('#editar_tel_para_llevar').val($objeto['cel']);
			$('#editar_via_contacto_para_llevar').val($objeto['via_contacto']);
			$('#id_cliente_para_llevar').val($objeto['id']);
			$('.selectpicker').selectpicker('refresh');
		}
		
	// Para llevar
		if($objeto['gestion_correo'] == 1){
			$('#editar_cliente').val($objeto['nombre']);
			$('#editar_email').val($objeto['email']);
			$('#editar_tel').val($objeto['tel']);
			$('#id_cliente').val($objeto['id']);
		}
		$('.selectpicker').selectpicker('refresh');
	},
	
///////////////// ******** ---- 				FIN llenar_campos					------ ************ //////////////////
	
///////////////// ******** ---- 					listar_combos						------ ************ //////////////////
//////// Carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// tipo -> 7 -> combo
		// persona -> ID de la personas seleccionada
		
	listar_combos : function($objeto) {
		console.log('------------> $objeto listar_combos');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
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
			url : 'ajax.php?c=comandas&f=listar_combos',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_combos');
			//console.log(resp);
	    		
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_combos');
			console.log(resp);
		
		// Quita el loader
			$('#' + $objeto['div']).html('Error al obtener los combos');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al obtener los combos';
			else
				var $mensaje = 'Error getting combos';
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

///////////////// ******** ---- 					listar_combos						------ ************ //////////////////
//////// Carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// tipo -> 7 -> combo
		// persona -> ID de la personas seleccionada
		
	listar_promociones : function($objeto) {
		console.log('------------> $objeto listar_promociones');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
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
			url : 'ajax.php?c=comandas&f=listar_promociones',
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
			if(comandera.idioma == 1)
				var $mensaje = 'Error al obtener las promciones';
			else
				var $mensaje = 'Error getting promotions';
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

///////////////// ******** ---- 			listar_productos_combo					------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		// combo -> Array con los datos del combo
			
	listar_productos_combo : function($objeto) {
		console.log('------------> $objeto listar_productos_combo');
		console.log($objeto);
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader
		$('#' + $objeto.div).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_productos_combo',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_productos_combo');
			//console.log(resp);
	    
	    // Inicializamos variables del combo
	    	comandera.pedidos_seleccionados = {};
	    	comandera.datos_combo = $objeto.combo;
	    	comandera.combos = [];
	    	
			$('#' + $objeto.div).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_combo');
			console.log(resp);
			if(comandera.idioma == 1)
				var $mensaje = 'Error al obtener los productos del combo';
			else
				var $mensaje = 'Error getting combo products';
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

///////////////// ******** ---- 			listar_productos_promociones					------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		// combo -> Array con los datos del combo
			
	listar_productos_promociones : function($objeto) {
		console.log('------------> $objeto listar_productos_promociones');
		console.log($objeto);

		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader
		$('#' + $objeto.div).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
				
		var $objeto2 = JSON.stringify($objeto);	
	
	/*
		$objeto2['promocion']['grupos'] = '';	
		$objeto2['promocion']['grupos']['productos'] = '{}';
		$objeto2['promocion']['grupos']['comprar'] = '{}';
		$objeto2['promocion']['grupos']['recibir'] = '{}';
	*/

		$.ajax({
			data : {obj:$objeto2},
			url : 'ajax.php?c=comandas&f=listar_productos_promociones',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {

			console.log('------------> done listar_productos_promociones');
			console.log(resp);

	    
	    // Inicializamos variables del combo
	    	comandera.pedidos_seleccionados = {};
	    	comandera.datos_promocion = $objeto.promocion;
	    	comandera.promociones = [];	    	
	    		    	
			$('#' + $objeto.div).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_promociones');
			console.log(resp);
			if(comandera.idioma == 1)
				var $mensaje = 'Error al obtener los productos de la promocion';
			else
				var $mensaje = 'Error getting promotion products';
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
		console.log(comandera['combos']);
		
	// Busca el grupo y carga el HTML de ese grupo
		$.each(comandera['combos'], function(key, val) {
			if ($objeto['grupo'] == val['grupo']) {
				$("#" + $objeto['div']).html(val['html']);
				$("#cantidad_grupo_" + $objeto.grupo).html(0);
			
			// Elimina el grupo del array de los productos seleccionados
				delete comandera.pedidos_seleccionados[$objeto.grupo];
				comandera.datos_combo.grupos[$objeto.grupo].num_seleccionados = 0;
				
				console.log('=========> pedidos_seleccionados');
				console.log(comandera.pedidos_seleccionados);
			}
		});
	},
	
///////////////// ******** ---- 				FIN reiniciar_grupo				------ ************ //////////////////

///////////////// ******** ---- 				reiniciar_grupo_promociones					------ ************ //////////////////
//////// Carga el HTML original del grupo
	// Como parametros recibe:
		// grupo -> ID del grupo
		// div -> Div donde se carga el contenido
		
	reiniciar_grupo_promociones : function($objeto) {
		console.log('=========> objeto reiniciar_grupo_promociones');
		console.log($objeto);
		
		console.log('=========> Promociones');
		console.log(comandera['promociones']);
		
	// Busca el grupo y carga el HTML de ese grupo
		$.each(comandera['promociones'], function(key, val) {
			if ($objeto['grupo'] == val['grupo']) {
				$("#" + $objeto['div']).html(val['html']);
				$("#cantidad_grupo_" + $objeto.grupo).html(0);
			
			// Elimina el grupo del array de los productos seleccionados
				delete comandera.pedidos_seleccionados[$objeto.grupo];
				comandera.datos_promocion.grupos[$objeto.grupo].num_seleccionados = 0;
				
				console.log('=========> pedidos_seleccionados');
				console.log(comandera.pedidos_seleccionados);
			}
		});
	},
	
///////////////// ******** ---- 				FIN reiniciar_grupo_promociones				------ ************ //////////////////

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
		var datos = comandera.pedidos_seleccionados;
		console.log("lengthw: "+Object.keys(comandera.pedidos_seleccionados).length);
	// Si no existe el grupo en el array lo crea
		if(!datos[$objeto.grupo])
			datos[$objeto.grupo] = {};
		if(!datos[$objeto.grupo][$objeto.id_producto])
			datos[$objeto.grupo][$objeto.id_producto] = {};
	// Agrega el producto al grupo del array
		datos[$objeto.grupo][$objeto.id_producto][Object.keys(datos[$objeto.grupo][$objeto.id_producto]).length] = $objeto;
		
		
		
	// Guarda el array modificado
		comandera.pedidos_seleccionados = datos;
		
		//console.log('=========> pedidos_seleccionados');
		//console.log(comandera.pedidos_seleccionados[$objeto.grupo]);
		var total = 0;
		$.each(comandera.pedidos_seleccionados[$objeto.grupo], function(index, val) {
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
		if(comandera.idioma == 0)
			comandera.get_idioma();
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_combo',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done guardar_combo');
			//console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
		// Error
			if (resp['status'] == 2) {
				if(comandera.idioma == 1)
					var $mensaje = 'Selecciona todos los productos';
				else
					var $mensaje = 'Select all products';
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
			comandera.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['datos_combo']['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail guardar_combo');
			console.log(resp);
			
			// Quita el loader
			$btn.button('reset');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar los datos';
			else
				var $mensaje = 'Failed to save data';
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

///////////////// ******** ---- 				guardar_promocion						------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:

	guardar_promocion : function($objeto) {
		console.log('===============> objeto guardar_promocion');
		console.log($objeto);	
		if(comandera.idioma == 0)
			comandera.get_idioma();

	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		

		/// OBJ TO STRNG
		var $objeto2 = JSON.stringify($objeto);
		///

		$.ajax({
			data : {obj:$objeto2},
			url : 'ajax.php?c=comandas&f=guardar_promocion',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done guardar_promocion');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
		// Error
			if (resp['status'] == 2) {
				if(comandera.idioma == 1)
					var $mensaje = 'Selecciona todos los productos';
				else
					var $mensaje = 'Select all products';
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
			comandera.listar_pedidos_persona({
				persona: $objeto['persona'], 
				id_comanda: $objeto['datos_promocion']['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail guardar_promocion');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar los datos';
			else
				var $mensaje = 'Failed to save data';
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

///////////////// ******** ---- 				listar_complementos					------ ************ //////////////////
//////// Carga la vista de los complementos
	// Como parametros recibe:
		// div -> Div donde se debe de cargar el contenido
		// pedido -> Pedido seleccionado
		
	listar_complementos : function($objeto) {
		console.log('===============> objeto listar_complementos');
		console.log($objeto);
		
		if(comandera.idioma == 0)
			comandera.get_idioma();
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_complementos',
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
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al mostrar los complementos';
			else
				var $mensaje = 'Failed to load data';
				
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
		
		if(comandera.idioma == 0)
			comandera.get_idioma();
	
	// Valida que se seleccione un producto
		if(!$objeto.pedido){
			if(comandera.idioma == 1)
				var $mensaje = 'Debes agregar un producto';
			else
				var $mensaje = 'Need add item';
				
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
			url : 'ajax.php?c=comandas&f=agregar_complemento',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done agregar_complemento');
			console.log(resp);
			
		// Carga los pedidos de la persona
			comandera.listar_pedidos_persona({
				persona: comandera.datos_mesa_comanda['persona_seleccionada'], 
				id_comanda: comandera.datos_mesa_comanda['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail agregar_complemento');
			console.log(resp);
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar el complemento';
			else
				var $mensaje = 'Failed to save data';
				
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

///////////////// ******** ---- 			eliminar_complemento						------ ************ //////////////////
//////// Elimina el complemento del pedido
	// Como parametros recibe:
		// id_pedido -> ID del pedido
		// id_complemento -> ID del complemento
		
	eliminar_complemento : function($objeto) {
		console.log('===============> objeto eliminar_complemento');
		console.log($objeto);
		
		if(comandera.idioma == 0)
			comandera.get_idioma();
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=eliminar_complemento',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done eliminar_complemento');
			console.log(resp);
			
		// Carga los pedidos de la persona
			comandera.listar_pedidos_persona({
				persona: comandera.datos_mesa_comanda['persona_seleccionada'], 
				id_comanda: comandera.datos_mesa_comanda['id_comanda'],
				div: 'div_listar_pedidos_persona'
			});
		}).fail(function(resp) {
			console.log('================= Fail eliminar_complemento');
			console.log(resp);
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al eliminar el complemento';
			else
				var $mensaje = 'Failed to delete item';
				
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

///////////////// ******** ---- 				imprimir_pedidos						------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
	// Como parametros puede recibir:
		//	id_comanda -> ID de la comanda

	imprimir_pedidos : function(objet) {
		var idcomanda = objet['id_comanda'];
		var id = 1;

		console.log('---> objeto imprimePedido');
		console.log(objet);
		
		$.ajax({
			url : 'ajax.php?c=pedidosActivos&f=reimprime',
			type : 'POST',
			dataType : 'json',
			data : {
				'tipo' : idcomanda,
				'pedidos' : objet['pedidos']
			},
		}).done(function(data) {
			console.log('------> Done imprimePedido');
			console.log(data);

		// Sin datos
			if (data['status'] == 2) {
				if(comandera.idioma == 1)
					alert('No se encontraron pedidos');
				else
					alert("No Orders Found");

				return 0;
			}

			var organizacion = data.organizacion;
			var altura = 290;
			var mm = 15.118110236; //5 mm
			var contProducto = 0;
			var persona = 0;
			var tipo = '';

			var html = '<div style="text-align:left;font-size:14px;">';
			$.each(data.comanda, function(index, val) {

				if (val.tipo == 1) {
					tipo = 'Para Llevar';
				} else if (val.tipo == 2) {
					tipo = 'A domicilio';
				}
				
				html += organizacion + '</br></br>';
				html += 'Comanda: ' + val["comanda"] + '</br>';
				html += 'Inicio del Pedido: ' + val["inicioPedido"] + '</br>';
				html += 'Mesa: ' + val["nombre_mesa"] + '</br>';

				if (val["tipo"] == 1 || val["tipo"] == 2) {
					html += 'Tipo: ' + tipo + '</br>';
				}

				if (val["domicilio"]) {
					html += 'Domicilio -> ' + val["domicilio"] + '</br>';
				}

				if (val["tel"]) {
					html += 'Tel -> ' + val["tel"] + '</br>';
				}

				html += '</br></br></br>';

				$.each(val["persona"], function(index, value) {
					persona = (index);
					html += 'Orden ' + persona + '</br>';
					html += '</br>';

					var $tiempo_platillo = 0;

					$.each(value.productos, function(index, producto) {
						contProducto = (index + 1);

					// Muestra en que tiempo se debe servir el platilo
						if (producto["tiempo_platillo"] != $tiempo_platillo) {
							html += '</br><strong>============> Tiempo ' + producto["tiempo_platillo"] + '</strong>';

							$tiempo_platillo = producto["tiempo_platillo"];
						}

						html += '</br><strong>' + producto["cantidad"] + '</strong>' + 'x ' + producto["descripcion"] + '</br>';

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
						}
						if (producto["adicionalesDesc"] != '') {
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
		
		// Imprimimos los pedidos en una nueva ventana
			var ventana = window.open('', '_blank', 'width=207.874015748,height=' + altura + ',leftmargin=0');
			ventana.document.write(html);
			ventana.print();
			ventana.close();
		}).fail(function(resp) {
			console.log('------> fail imprimePedido');
			console.log(resp);

			if(comandera.idioma == 1)
				var $mensaje = 'Error al imprimir pedidos';
			else
				var $mensaje = 'Failed to print items';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN imprimir_pedidos					------ ************ //////////////////

///////////////// ******** ---- 					guardar_merma						------ ************ //////////////////
//////// Guarda la merma del producto
	// Como parametros recibe:
		// pedido -> Arra y con los datos del pedido
		// comentario -> String con el comentario de la merma
		// btn -> Boton del loader
		
	guardar_merma : function($objeto) {
		console.log('===============> objeto eliminar_complemento');
		console.log($objeto);
		
	// Loader
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		if(comandera.idioma == 0)
			comandera.get_idioma();
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_merma',
			type : 'POST',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done guardar_merma');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
			if(comandera.idioma == 1)
				var $mensaje = 'Merma guardada';
			else
				var $mensaje = 'Save item';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
		}).fail(function(resp) {
			console.log('================= Fail guardar_merma');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			if(comandera.idioma == 1)
				var $mensaje = 'Error al guardar la merma';
			else
				var $mensaje = 'Failed to save item';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 					FIN guardar_merma					------ ************ //////////////////

///////////////// ******** ---- Seleciiona producto, combo o promocion para poder agregar complemento ------ ************ //////////////////

    addcomple : function(idpedido) {
        // Selecciona el pedido(nos sirve al momento de querer agregar complementos)
			comandera.datos_mesa_comanda['pedido_seleccionado'] = idpedido;
    },

///////////////// ******** ---- Seleciiona producto, combo o promocion para poder agregar complemento fin ------ ************ //////////////////    


}; // Fin de la clase