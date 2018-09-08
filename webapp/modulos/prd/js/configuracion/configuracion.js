var configuracion = {
// ** Variable
	datos_menu : '',
	html_menu : '',
	
///////////////// ******** ---- 		guardar_pass		------ ************ //////////////////
	//////// Cambia la contraseña de seguridad
	// Como parametros recibe:
	// pass1 -> contraseña
	// pass2 -> debe coincidir con pass1

	guardar_pass : function($objeto) {
	// ** Validaciones
		if ($objeto['pass1'] != $objeto['pass2']) {
			var $mensaje = 'Las contraseñas no coinciden';
			$('#pass2').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warning',
			});
			return 0;
		}
		if (!$objeto['pass1'] || !$objeto['pass2']) {
			var $mensaje = 'Debes ingresar una contraseña';
			$('#pass2').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warning',
			});
			return 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=guardar_pass',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log(resp);
				
				if(resp){
					var $mensaje = 'Contraseña guardada';
					$('#form_seguridad').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'success',
					});
				
				// Limpia el form
					$('#form_seguridad').each (function(){
						this.reset();
					});
				
				// Cambia la contraseña					
					$('#pass').val($objeto['pass1']);
				}
				
			// Error: Manda un mensaje con el error
				if (!resp) {
					var $mensaje = 'Error: \n error al guardar la contraseña';
					$('#pass2').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				}
			}
		});
	},
	
///////////////// ******** ---- 		FIN guardar_pass		------ ************ //////////////////

///////////////// ******** ---- 		guardar_platillo		------ ************ //////////////////
//////// Guarda los dias y el horario en el que debe aparecer el platillo
	// Como parametros recibe:
		// id -> ID del producto

	guardar_platillo : function($objeto) {
		$objeto['dias']='';
		console.log('-----> objeto guardar_platillo');
		console.log($objeto);
		
	// ** Valida la hora de inicio
		if ($objeto['inicio']=='') {
			var $mensaje = 'Debes ingresar una hora de inicio';
			$('#btn_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});
			
			return 0;
		}
		
	// ** Valida la hora de fin
		if ($objeto['fin']=='') {
			var $mensaje = 'Debes ingresar una hora final';
			$('#btn_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});
			
			return 0;
		}
		
		var $diferencia=parseInt($objeto['fin'])-parseInt($objeto['inicio']);
		
	// ** Valida la que las horas esten bien
		if ($diferencia < 1) {
			var $mensaje = 'Horario no valido Ejem: 09:00 AM a 02:30 PM';
			$('#btn_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});
			
			return 0;
		}
		
	// Domingo
		if($('#do_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+='0';
		}
	// Lunes
		if($('#lu_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',1';
		}
	// Martes
		if($('#ma_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',2';
		}
	// Miercoles
		if($('#mi_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',3';
		}
	// Jueves
		if($('#ju_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',4';
		}
	// Viernes
		if($('#vi_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',5';
		}
	// Sabado
		if($('#sa_'+$objeto['id']).prop('checked') ) {
			$objeto['dias']+=',6';
		}
		
	// ** Valida que se seleccione un dia al menos
		if ($objeto['dias']=='') {
			var $mensaje = 'Debes seleccionar al menos un dia';
			$('#btn_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});
			
			return 0;
		}
		
		console.log('------>Dias');
		console.log($objeto['dias']);
		
	// Loader en el boton OK
		var $btn = $('#btn_'+$objeto['id']);
	    $btn.button('loading');
			    
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=guardar_platillo',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-------> Done guardar_platillo');
			console.log(resp);
			
			$btn.button('reset');

		// Todo bien :D
			if(resp['status']==1){
				$('#btn_'+$objeto['id']).removeClass().addClass("btn btn-success");
				
				var $mensaje = 'Horario establecido con exito';
				$('#btn_'+$objeto['id']).notify($mensaje, {
					position : "left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
				
				return 0;
			}
			
		// Error: Manda un mensaje con el error
			if (!resp) {
				var $mensaje = 'Error: \n error al guardar los datos';
				$('#btn_'+$objeto['id']).notify($mensaje, {
					position : "left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
			}
		}).fail(function(resp) {
			console.log('-------> Fail guardar_platillo');
			console.log(resp);
			
			$btn.button('reset');
		
			var $mensaje = 'Error: al guardar los datos';
			$('#btn_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});//Fin Ajax
	},
	
///////////////// ******** ---- 		FIN guardar_platillo		------ ************ //////////////////

///////////////// ******** ---- 		convertir_dataTable			------ ************ //////////////////
	//////// Conviertela tabla en dataTable
		// Como parametros recibe:
			// id -> ID de la tabla a convertir

	convertir_dataTable : function($objeto) {
		console.log('objeto convertir dataTable');
		console.log($objeto);

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
			order: [[0, ""]]
		});
	},

///////////////// ******** ---- 	FIN convertir_dataTable				------ ************ //////////////////

///////////////// ******** ---- 			cambio						------ ************ //////////////////
	//////// Cambia el color del boton a gris
		// Como parametros recibe:
			// id -> ID del boton

	cambio : function($objeto) {
		console.log('objeto cambio');
		console.log($objeto);
		
		$('#btn_'+$objeto['id']).removeClass().addClass("btn btn-default");
	},

///////////////// ******** ---- 			FIN cambio			------ ************ //////////////////

///////////////// ******** ---- 		eliminar_platillo		------ ************ //////////////////
//////// Elimina los dias y el horario de un platillo
	// Como parametros recibe:
		// id -> ID del producto

	eliminar_platillo : function($objeto) {
		console.log('-----> objeto eliminar_platillo');
		console.log($objeto);
		
		if(!confirm('Se borrara el horario del producto. ¿Deseas continuar?')){
			
			return 0;
		}
		
	// Loader en el boton OK
		var $btn = $('#btn_eliminar_'+$objeto['id']);
	    $btn.button('loading');
			    
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=eliminar_platillo',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('-------> Done eliminar_platillo');
			console.log(resp);
			
			$btn.button('reset');

		// Todo bien :D
			if(resp['status']==1){
				var $mensaje = 'Horario borrado con exito';
				$('#btn_eliminar_'+$objeto['id']).notify($mensaje, {
					position : "left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			
			// ** Limpia los campos
				$('#inicio_'+$objeto['id']).val('');
				$('#fin_'+$objeto['id']).val('');
				
				$('#do_'+$objeto['id']).prop('checked', false);
				$('#lu_'+$objeto['id']).prop('checked', false);
				$('#ma_'+$objeto['id']).prop('checked', false);
				$('#mi_'+$objeto['id']).prop('checked', false);
				$('#ju_'+$objeto['id']).prop('checked', false);
				$('#vi_'+$objeto['id']).prop('checked', false);
				$('#sa_'+$objeto['id']).prop('checked', false);
				
				return 0;
			}
			
		// Error: Manda un mensaje con el error
			if (!resp) {
				var $mensaje = 'Error al cancelar los horarios';
				$('#btn_eliminar_'+$objeto['id']).notify($mensaje, {
					position : "left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
			}
		}).fail(function(resp) {
			console.log('-------> Fail eliminar_platillo');
			console.log(resp);
			
			$btn.button('reset');
		
			var $mensaje = 'Error al eliminar los datos';
			$('#btn_eliminar_'+$objeto['id']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});//Fin Ajax
	},
	
///////////////// ******** ---- 		FIN eliminar_platillo		------ ************ //////////////////

///////////////// ******** ---- 		vista_nueva					------ ************ //////////////////
//////// Consulta los productos y los agrega a un div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_nueva : function($objeto) {
		console.log('----> Objeto nueva');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_nueva',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done nueva');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista nueva');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_nueva				------ ************ //////////////////

///////////////// ******** ---- 			agregar_producto			------ ************ //////////////////
//////// Agrega un producto al array de los productos agregados
	// Como parametros recibe:
		// id -> ID del producto
		// div -> ID de la div donde se cargara la vista
		// id_unidad -> ID de la unidad
		// unidad_compra -> ID de la unidad de compra
		// nombre -> nombre del producto
		// unidad -> nombre de la unidad
		// check -> valor del check(true o false)
		
	agregar_producto : function($objeto) {
		console.log('objeto agregar producto');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=agregar_producto',
			type : 'POST',
			dataType : 'html',
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar producto '+$objeto['id']);
			console.log(resp);
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
			
			console.log('----> check');
			console.log($objeto['check']);
			
			var tabla = $('#tabla_productos').dataTable();
    		var tabla = tabla.fnGetNodes();
			
			$(tabla).each(function (index){
				id = $(this,tabla).attr('id');
				
				if(id == 'tr_'+$objeto['id']){
					checkbox = $(this,tabla).find('input');
					
					if($objeto['check'] === false){
						checkbox.prop("checked", true);
						$(this,tabla).addClass('success');
					}else{
						checkbox.prop("checked", false);
						$(this,tabla).addClass('success');
						$(this,tabla).removeClass('success');
					}
				}
    		});
		}).fail(function(resp) {
			console.log('----> Fail agregar producto');
			console.log(resp);
			
			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN agregar_producto			------ ************ //////////////////

///////////////// ******** ---- 			cambiar_tipo					------ ************ //////////////////
//////// Oculta una div y muestra otra dependiendo del tipo
	// Como parametros recibe:
		// tipo -> tipo de div que se tiene que mostrar 1 -> div descuentos, 2 -> div cantidad

	cambiar_tipo : function($objeto) {
		console.log('----> Objeto cambiar_tipo');
		console.log($objeto);
		
		if($objeto['tipo'] == 1){
		// Oculta la div de cantidades y muestra la de descuento
			$('#div_por_descuento').show();
			$('#div_por_cantidad').hide();
		
		// Limpia las cantidades
			$('#cantidad').val('');
			$('#cantidad_descuento').val('');
		}else{
		// Oculta la div de descuento y muestra la de cantidades
			$('#div_por_cantidad').show();
			$('#div_por_descuento').hide();
		
		// Limpia el descuento
			$('#descuento').val('');
		}
	},

///////////////// ******** ---- 				FIN cambiar_tipo			------ ************ //////////////////

///////////////// ******** ---- 				guardar_promocion			------ ************ //////////////////
//////// Guarda la informacion en la DB
	// Como parametros recibe:
		// form -> formulario con los datos a guardar

	guardar_promocion : function($objeto) {
		console.log('---------> $objeto guardar');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#btn_guardar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
	
	// Valida que se ingrese un descuento o unas cantidades
		if ($datos['descuento'] == '' && $datos['cantidad'] == '' && $datos['cantidad_descuento'] == '') {
			var $mensaje = 'Agrega un descuento';
			$("#btn_guardar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		if ($datos['dias'] == '') {
			var $mensaje = 'Debes seleccionar al menos un dia';
			$("#btn_guardar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
	
	// Valida la hora de inicio y fin
		if ($datos['inicio'] == '' || $datos['fin'] == '') {
			var $mensaje = 'Agrega una hora de inicio y fin';
			$("#btn_guardar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
	/* FIN Validaciones
	=============================================================== */
		
		console.log('---------> datos guardar_promocion');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#btn_guardar_promocion');
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=guardar_promocion',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done guardar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_nueva').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_guardar_promocion").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre de la promocion';
				$("#btn_guardar_promocion").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail guardar_promocion');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN guardar_promocion				------ ************ //////////////////

///////////////// ******** ---- 				actualizar_promocion			------ ************ //////////////////
//////// Arma el array con la informacion de la promocion y manda los datos
	// Como parametros recibe:
		// form -> formulario con los datos a guardar

	actualizar_promocion : function($objeto) {
		console.log('---------> $objeto actualizar_promocion');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#actualizar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
	
	// Valida que se ingrese un descuento o unas cantidades
		if ($datos['descuento'] == '' && $datos['cantidad'] == '' && $datos['cantidad_descuento'] == '') {
			var $mensaje = 'Agrega un descuento';
			$("#btn_actualizar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		if ($datos['dias'] == '') {
			var $mensaje = 'Debes seleccionar al menos un dia';
			$("#btn_actualizar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
	
	// Valida la hora de inicio y fin
		if ($datos['inicio'] == '' || $datos['fin'] == '') {
			var $mensaje = 'Agrega una hora de inicio y fin';
			$("#btn_guardar_promocion").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
	/* FIN Validaciones
	=============================================================== */
		$datos['id_promocion'] = $objeto['id_promocion'];
		
		console.log('---------> datos actualizar_promocion');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=actualizar_promocion',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done actualizar_promocion');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_editar').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_actualizar_promocion").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre de la promocion';
				$("#btn_actualizar_promocion").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail actualizar_promocion');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_promocion				------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_promocion					------ ************ //////////////////
//////// Consulta las promociones, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_editar_promocion : function($objeto) {
		console.log('----> Objeto vista_editar_promocion');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_promocion',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_editar_promocion');
			console.log(resp);
			
		// Carga la vista de de nueva promocion
			configuracion.vista_nueva({div:'div_promociones', btn:'btn_nueva', panel:'primary'});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_editar_promocion');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#"+$objeto['div']).html('Error al cargar los datos');
		
		// Mensaje error
			$mensaje = 'Error, no se pueden cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_editar_promocion					------ ************ //////////////////

///////////////// ******** ---- 				editar_promocion						------ ************ //////////////////
//////// Carga la vista y la llena con los datos de la receta o insumo preparado
	// Como parametros recibe:
		// idProducto -> ID del insumo
		// nombre -> nombre del insumo
		// idUnidad -> ID de la unidad de venta
		// idunidadCompra -> ID de la unidad de compra
		// costo -> costo del insumo
		// tipo_producto -> 7 receta, 8 insumo preparado
		// unidad -> texto que se muestra de la unidad(unidad, kilo, litro, gramo, etc.)
		// insumos_preparados -> array con los insumos preparados que componen la receta
		// insumos -> array con los insumos que componen la receta
		// preparacion -> preparacion de la receta

	editar_promocion : function($objeto) {
		console.log('----> Objeto editar');
		console.log($objeto);
		
	// Loader
		$("#div_editar_promocion").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Cierra la ventana modal
		$('#btn_cerrar_editar').click();
		
	// Agrega los insumos y los subtotales de los insumos
		$.each($objeto["productos"], function(index, val) {
		// Formateamos el array
			val['id'] = val['idProducto'];
			val['div'] = 'div_productos_agregados';
			val['check'] = false;
		
		// Agrega el insumo al array de insumos agregados
			configuracion.agregar_producto(val);
		});
		
	// LLena los campos
		$("#nombre").val($objeto['nombre']);
		$("#tipo").val($objeto['tipo_promocion']);
		configuracion.cambiar_tipo({tipo: $objeto['tipo_promocion'] });
		$('.selectpicker').selectpicker('refresh');
		$("#descuento").val($objeto['descuento']);
		$("#cantidad").val($objeto['cantidad']);
		$("#cantidad_descuento").val($objeto['cantidad_descuento']);
		$("#descuento").val($objeto['descuento']);
		$("#inicio").val($objeto['inicio']);
		$("#fin").val($objeto['fin']);
	
		if ($objeto['dias'].indexOf('0') != -1) {
			$('#do').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('1') != -1) {
			$('#lu').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('2') != -1) {
			$('#ma').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('3') != -1) {
			$('#mi').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('4') != -1) {
			$('#ju').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('5') != -1) {
			$('#vi').prop('checked', true);
		}
		if ($objeto['dias'].indexOf('6') != -1) {
			$('#sa').prop('checked', true);
		}

	// Muestra el boton de actualiza y le agrega el ID de la receta o insumo
		$('#btn_actualizar_promocion').show();
		$('#btn_actualizar_promocion').attr("id_promocion", $objeto['id_promocion']);
		
	// Oculta el boton de guardar
		$("#btn_guardar_promocion").hide();
	},

///////////////// ******** ---- 			FIN editar_promocion						------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_promocion					------ ************ //////////////////
//////// Consulta las promociones, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_eliminar_promocion : function($objeto) {
		console.log('----> Objeto vista_eliminar_promocion');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_eliminar_promocion',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_eliminar_promocion');
			console.log(resp);
			
		// Carga la vista de de nueva receta
			configuracion.vista_nueva({div:'div_promociones', btn:'btn_nueva'});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_eliminar_promocion');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#"+$objeto['div']).html('Error al cargar los datos');
		
		// Mensaje error
			$mensaje = 'Error, no se pueden cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_eliminar_promocion				------ ************ //////////////////

///////////////// ******** ---- 				eliminar_promocion						------ ************ //////////////////
//////// Elimina la promocion y sus productos
	// Como parametros recibe:
		// id_promocion -> ID de la promocion

	eliminar_promocion : function($objeto) {
		console.log('----> Objeto eliminar_promocion');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		if(confirm("¿Estas seguro que quieres eliminar la promocion?")){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=configuracion&f=eliminar_promocion',
				type : 'POST',
				dataType : 'json'
			}).done(function(resp) {
				console.log('----> Done eliminar_promocion');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
			// Todo bien :D
				if(resp['status']==1){
				// Indica que se elimino la promocion y oculta el boton de eliminar_promocion
					$('#tr_eliminar_promocion_' + $objeto['id_promocion']).removeClass().addClass("danger");
					$btn.hide();
					
					var $mensaje = 'Eliminado con exito';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'success',
					});
				
					return 0;
				}
			}).fail(function(resp) {
				console.log('----> Fail eliminar_promocion');
				console.log(resp);
				
			// Quita el loader
				$btn.button('reset');
			
			// Mensaje error
				$mensaje = 'Error, no se puede eliminar la promocion';
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

///////////////// ******** ---- 				FIN eliminar_promocion					------ ************ //////////////////

///////////////// ******** ---- 				vista_nuevo_menu						------ ************ //////////////////
//////// Carga la vista para crear un nuevo menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_nuevo_menu : function($objeto) {
		console.log('----> Objeto vista_nuevo_menu');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_nuevo_menu',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_nuevo_menu');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista vista_nuevo_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_nuevo_menu					------ ************ //////////////////

///////////////// ******** ---- 			convertir_arbol							------ ************ //////////////////
	//////// Convierte una div en un arbol de checkbox
		// Como parametros recibe:
			// id -> ID de la div a convertir
			// datos -> Datos del arbol

	convertir_arbol : function($objeto) {
		console.log('====> objeto convertir_arbol');
		console.log($objeto);

		$('#' + $objeto['id']).on('changed.jstree', function (e, data) {
			var i, j, r = [];

			for ( i = 0, j = data.selected.length; i < j; i++) {
				var nodo = data.instance.get_node(data.selected[i]);
				var parent = data.instance.get_node(nodo['parent']);
				nodo['parent_text'] = parent['text'];
				r.push(nodo);
			}
			
		    configuracion.datos_menu = r;
		}).jstree({
			'core' : {
				'data' : $objeto['datos'],
			    "check_callback" : true
			}, 
			"checkbox" : {
				"keep_selected_style" : false
		    },
		    "plugins" : ["checkbox", "dnd"]
		});
	},

///////////////// ******** ---- 			FIN convertir_arbol						------ ************ //////////////////

///////////////// ******** ---- 				agregar_menu						------ ************ //////////////////
//////// Agrega un nuevo menu digital
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// imprimir -> 1 -> imprimir menu
		// estilo -> 1 -> alternativo, 2 -> clasico, 3 -> organico vintage, 4 -> tradicional
		// btn -> boton del loader

	agregar_menu : function($objeto) {
		$objeto['productos'] = configuracion.datos_menu;
		console.log('----> Objeto agregar_menu');
		console.log($objeto);
		
	// ** Valida que se  seleccionen productos
		if(!$objeto['productos']){
			$mensaje = 'Necesitas seleccionar productos';
			$('#' + $objeto['btn']).notify($mensaje, {
				position : "left center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			
			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		var div_html = $("#div_botones").html();
		
		$("#div_botones").html('<div align="center"><i class="fa fa-refresh fa-4x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=agregar_menu',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done agregar_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#div_botones").html(div_html);
		
		// Todo bien :D
			if(resp['status'] == 1){
				$mensaje = 'Menu guardado';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
			
			// Valida si se debe de imprimir el menu o no
				if($objeto['imprimir'] == 1){
					$("#"+$objeto['div_imprimir']).printArea();
				}
				
				return 0;
			}
			
		// Sin productos
			if(resp['status'] == 2){
				$mensaje = 'Necesitas seleccionar productos';
				$('#' + $objeto['btn']).notify($mensaje, {
					position : "left center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});
				
				return 0;
			}
		}).fail(function(resp) {
			console.log('----> Fail agregar_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#div_botones").html(div_html);

			$mensaje = 'Error al guardar el menu';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_menu					------ ************ //////////////////

///////////////// ******** ---- 			imprimir_menu						------ ************ //////////////////
//////// Carga la vista  segun el estilo seleccionado con los datos del menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> 1 -> alternativo, 2 -> clasico, 3 -> organico vintage, 4 -> tradicional

	imprimir_menu : function($objeto) {
		$objeto['productos'] = configuracion.datos_menu;
		console.log('----> Objeto vista_nuevo_menu');
		console.log($objeto);
		
	// ** Valida que se  seleccionen productos
		if(!$objeto['productos']){
			$mensaje = 'Necesitas seleccionar productos';
			$('#' + $objeto['btn']).notify($mensaje, {
				position : "left center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			
			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=imprimir_menu',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done imprimir_menu');
			console.log(resp);
			
			configuracion.html_menu = resp;
			
		// Quita el loader
			$btn.button('reset');
			
			$('#modal_imprimir_menu').modal('show');
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista imprimir_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN imprimir_menu						------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_menu						------ ************ //////////////////
//////// Carga la vista para editar un menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_editar_menu : function($objeto) {
		console.log('----> Objeto vista_editar_menu');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$("#" + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_menu',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_editar_menu');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			configuracion.vista_nuevo_menu({div:'div_menu', btn:'btn_nueva', panel:'primary'});
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista vista_editar_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#" + $objeto['div']).html('Error, no se puede cargar los datos');
			
			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_editar_menu					------ ************ //////////////////

///////////////// ******** ---- 				editar_menu							------ ************ //////////////////
//////// Carga la vista y la llena con los datos del menul
	// Como parametros recibe:

	editar_menu : function($objeto) {
		console.log('----> Objeto editar_menu');
		console.log($objeto);
		
	// Loader
		$("#div_editar").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Retrasa la funcion para que alcance a cargar la vista
		setTimeout(function() {
		// Cierra la ventana modal
			$('#btn_cerrar_editar').click();
			
		// LLena los campos
			$("#nombre").val($objeto['nombre']);
			$("#nombre_restaurante").val($objeto['nombre_restaurante']);
			$("#estilo").val($objeto['estilo']);
			$('#imagen_menu').prop('src', 'images/Menu_Digital/Menu_'+$objeto['estilo']+'.jpg');
			
			$('.selectpicker').selectpicker('refresh');
			
		// Muestra el boton de actualiza y le agrega el ID del menu
			$('#btn_actualizar').show();
			$('#btn_actualizar').attr("id_menu", $objeto['id_menu']);
			$('#btn_imprimir_actualizar').attr("id_menu", $objeto['id_menu']);
			$('#btn_imprimir_actualizar').show();
			
		// Oculta los botones de guardar
			$("#btn_imprimir_guardar").hide();
			$("#btn_guardar_menu").hide();
		
			$.each($objeto["productos"], function(index, val) {
				$("#arbol").jstree("select_node", "#"+val['id_producto']);
			});
		}, 2000);
	},

///////////////// ******** ---- 			FIN editar_promocion						------ ************ //////////////////

///////////////// ******** ---- 				actualizar_menu							------ ************ //////////////////
//////// Actualiza los datos del menu
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// imprimir -> 1 -> imprimir menu
		// estilo -> 1 -> alternativo, 2 -> clasico, 3 -> organico vintage, 4 -> tradicional
		// btn -> boton del loader

	actualizar_menu : function($objeto) {
		$objeto['productos'] = configuracion.datos_menu;
		console.log('----> Objeto actualizar_menu');
		console.log($objeto);
		
	// ** Valida que se  seleccionen productos
		if(!$objeto['productos']){
			$mensaje = 'Necesitas seleccionar productos';
			$('#' + $objeto['btn']).notify($mensaje, {
				position : "left center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
				arrowSize : 15
			});
			
			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		var div_html = $("#div_botones").html();
		
		$("#div_botones").html('<div align="center"><i class="fa fa-refresh fa-4x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=actualizar_menu',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done actualizar_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#div_botones").html(div_html);
		
		// Todo bien :D
			if(resp['status'] == 1){
				$mensaje = 'Menu guardado';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
			
			// Valida si se debe de imprimir el menu o no
				if($objeto['imprimir'] == 1){
					$("#"+$objeto['div_imprimir']).printArea();
				}
				
				return 0;
			}
			
		// Sin productos
			if(resp['status'] == 2){
				$mensaje = 'Necesitas seleccionar productos';
				$('#' + $objeto['btn']).notify($mensaje, {
					position : "left center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});
				
				return 0;
			}
		}).fail(function(resp) {
			console.log('----> Fail actualizar_menu');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#div_botones").html(div_html);

			$mensaje = 'Error al guardar el menu';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN actualizar_menu					------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_menu					------ ************ //////////////////
//////// Carga la vista para eliminar un menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_eliminar_menu : function($objeto) {
		console.log('----> Objeto vista_eliminar_menu');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$("#" + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_eliminar_menu',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_eliminar_menu');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			configuracion.vista_nuevo_menu({div:'div_menu', btn:'btn_nueva', panel:'success'});
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista vista_eliminar_menu:)u');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#" + $objeto['div']).html('Error, no se puede cargar los datos');
			
			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_eliminar_menu					------ ************ //////////////////

///////////////// ******** ---- 				eliminar_menu						------ ************ //////////////////
//////// Elimina el menu y sus productos
	// Como parametros recibe:
		// id_menu -> ID del menu
		// btn -> ID del boton

	eliminar_menu : function($objeto) {
		console.log('----> Objeto eliminar_menu');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		if(confirm("¿Estas seguro que quieres eliminar el menu?")){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=configuracion&f=eliminar_menu',
				type : 'POST',
				dataType : 'json'
			}).done(function(resp) {
				console.log('----> Done eliminar_menu');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
			// Todo bien :D
				if(resp['status'] == 1){
				// Indica que se elimino el menu y oculta el boton
					$('#tr_eliminar_menu_' + $objeto['id_menu']).removeClass().addClass("danger");
					$btn.hide();
					
					var $mensaje = 'Eliminado con exito';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'success',
					});
				
					return 0;
				}
			}).fail(function(resp) {
				console.log('----> Fail eliminar_menu');
				console.log(resp);
				
			// Quita el loader
				$btn.button('reset');
			
			// Mensaje error
				$mensaje = 'Error, no se puede eliminar el menu';
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

///////////////// ******** ---- 			FIN eliminar_menu					------ ************ //////////////////

///////////////// ******** ---- 			vista_nuevo_kit						------ ************ //////////////////
//////// Carga la vista para un nuevo kit
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_nuevo_kit : function($objeto) {
		console.log('----> Objeto vista_nuevo_kit');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_nuevo_kit',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_nuevo_kit');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_nuevo_kit');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_nuevo_kit				------ ************ //////////////////

///////////////// ******** ---- 			actualizar_cantidad				------ ************ //////////////////
//////// Cambia la cantidad del producto
	// Como parametros recibe:
		// id -> ID del producto
		// cantidad -> Nueva cantidad

	actualizar_cantidad : function($objeto) {
		console.log('----> objeto actualizar_cantidad');
		console.log($objeto);
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=actualizar_cantidad',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done actualizar_cantidad');
			console.log(resp);
		
			$costo = 0;
			
		// Calcula el costo del kit
			$.each(resp["productos_agregados"], function(index, val) {
				if(val['sub_total']){
					$costo += parseFloat(val['sub_total']);
				}
			});
			
		// Actualiza el costo total
			$('#costo_total').html($costo);		
		}).fail(function(resp) {
			console.log('----> Fail actualizar_cantidad');
			console.log(resp);

			$mensaje = 'Error al cambiar la cantidad';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_cantidad			------ ************ //////////////////

///////////////// ******** ---- 				guardar_kit					------ ************ //////////////////
//////// Guarda la informacion en la DB
	// Como parametros recibe:
		// form -> formulario con los datos a guardar

	guardar_kit : function($objeto) {
		console.log('---------> $objeto guardar');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#btn_guardar_kit").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		// if ($datos['dias'] == '') {
			// var $mensaje = 'Debes seleccionar al menos un dia';
			// $("#btn_guardar_kit").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
	
	// Valida la hora de inicio y fin
		// if ($datos['inicio'] == '' || $datos['fin'] == '') {
			// var $mensaje = 'Agrega una hora de inicio y fin';
			// $("#btn_guardar_kit").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
		
	/* FIN Validaciones
	=============================================================== */
	
		$datos['costo'] = $objeto['costo'];
		
		console.log('---------> datos guardar_kit');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#btn_guardar_kit');
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=guardar_kit',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done guardar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_nueva').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_guardar_kit").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre del kit';
				$("#btn_guardar_kit").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail guardar_kit');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN guardar_kit				------ ************ //////////////////

///////////////// ******** ---- 			validar_codigo				------ ************ //////////////////
//////// Valida el codigo introducido y lo cambia de ser incorrecto
	// Como parametros recibe:
		// id -> ID del campo a validar
		
	validar_codigo : function($objeto) {
		console.log('-----> Obejto validar_codigo');
		console.log($objeto);
	
	// Formatea el codigo
		var $codigo=$('#'+$objeto['id']).val();
		$codigo = $codigo.replace(/\s/g, '');
	
	// Actualiza el codigo
		$('#'+$objeto['id']).val($codigo);
	},
	
///////////////// ******** ---- 			FIN validar_codigo				------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_kit				------ ************ //////////////////
//////// Carga la vista para editar los kits
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_editar_kit : function($objeto) {
		console.log('----> Objeto vista_editar_kit');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_kit',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_editar_kit');
			console.log(resp);
			
		// Carga la vista de de nueva promocion
			configuracion.vista_nuevo_kit({div:'div_kits', btn:'btn_nueva', panel:'primary'});
			
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_editar_kit');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

		// Loader
			$("#"+$objeto['div']).html('Error, no se puede cargar los datos');
		
			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_editar_kit			------ ************ //////////////////

///////////////// ******** ---- 				editar_kit					------ ************ //////////////////
//////// Carga la vista y la llena con los datos del kit
	// Como parametros recibe:
		// id_kit -> ID del kit
		// nombre -> Nombre del insumo
		// costo -> Costo del kit
		// precio -> Precio del kit
		// productos -> array con los productos del kit

	editar_kit : function($objeto) {
		console.log('----> Objeto editar_kit');
		console.log($objeto);
		
	// Loader
		$("#div_editar").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Retrasa la funcion para que alcance a cargar la vista
		setTimeout(function() {
		// Agrega los insumos y los subtotales de los insumos
			$.each($objeto["productos"], function(index, val) {
			// Formateamos el array
				val['id'] = val['idProducto'];
				val['check'] = false;
				val['div'] = $objeto['div'];
				val['vista'] = 'listar_productos_agregados_kit';
			
			// Agrega el producto al array de los  productos agregados
				configuracion.agregar_producto(val);
			});
		
		// LLena los campos
			$("#nombre").val($objeto['nombre']);
			$("#codigo").val($objeto['codigo']);
			$("#inicio").val($objeto['inicio']);
			$("#fin").val($objeto['fin']);
			$("#costo_total").html($objeto['costo']);
		
		// Dias
			if ($objeto['dias'].indexOf('0') != -1) {
				$('#do').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('1') != -1) {
				$('#lu').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('2') != -1) {
				$('#ma').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('3') != -1) {
				$('#mi').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('4') != -1) {
				$('#ju').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('5') != -1) {
				$('#vi').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('6') != -1) {
				$('#sa').prop('checked', true);
			}
		
		// Actualiza el precio despues de 1 segundo para dar tiempo a la funcion que agrega los productos
			setTimeout ("$(\"#precio\").val("+$objeto['precio']+");", 500);
			
		// Muestra el boton de actualiza y le agrega el ID del kit
			$('#btn_actualizar_kit').show();
			$('#btn_actualizar_kit').attr('id_kit', $objeto['id_kit']);
			
		// Oculta el boton de guardar
			$("#btn_guardar_kit").hide();
			
		// Cierra la ventana modal
			$('#btn_cerrar_editar').click();
		}, 2000);
	},

///////////////// ******** ---- 			FIN editar_kit					------ ************ //////////////////

///////////////// ******** ---- 			actualizar_kit					------ ************ //////////////////
//////// Actualiza la informacion en la DB del kit
	// Como parametros recibe:
		// form -> formulario con los datos a guardar
		// costo -> costo del kit
		// id_kit -> ID del kit

	actualizar_kit : function($objeto) {
		console.log('---------> $objeto actualizar');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#btn_actualizar_kit").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		// if ($datos['dias'] == '') {
			// var $mensaje = 'Debes seleccionar al menos un dia';
			// $("#btn_actualizar_kit").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
	
	// Valida la hora de inicio y fin
		// if ($datos['inicio'] == '' || $datos['fin'] == '') {
			// var $mensaje = 'Agrega una hora de inicio y fin';
			// $("#btn_actualizar_kit").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
		
	/* FIN Validaciones
	=============================================================== */
	
		$datos['costo'] = $objeto['costo'];
		$datos['id_kit'] = $objeto['id_kit'];
		
		console.log('---------> datos actualizar_kit');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#btn_actualizar_kit');
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=actualizar_kit',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done actualizar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_editar').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_actualizar_kit").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre del kit';
				$("#btn_actualizar_kit").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail actualizar_kit');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al actualizar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_kit				------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_kit				------ ************ //////////////////
//////// Consulta los kits, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta
		// vista -> Vista que se debe cargar

	vista_eliminar_kit : function($objeto) {
		console.log('----> Objeto vista_eliminar_kit');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_kit',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_eliminar_kit');
			console.log(resp);
			
		// Carga la vista de de nueva receta
			configuracion.vista_nuevo_kit({div:'div_kits', btn:'btn_nueva'});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_eliminar_kit');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#"+$objeto['div']).html('Error al cargar los datos');
		
		// Mensaje error
			$mensaje = 'Error, no se pueden cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN vista_eliminar_kit			----- ************ //////////////////

///////////////// ******** ---- 					eliminar_kit				------ ************ //////////////////
//////// Elimina el kit y sus productos
	// Como parametros recibe:
		// id_kit -> ID del kit

	eliminar_kit : function($objeto) {
		console.log('----> Objeto eliminar_kit');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		if(confirm("¿Estas seguro que quieres eliminar el kit?")){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=configuracion&f=eliminar_kit',
				type : 'POST',
				dataType : 'json'
			}).done(function(resp) {
				console.log('----> Done eliminar_kit');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
			// Todo bien :D
				if(resp['status'] == 1){
				// Indica que se elimino el kit y oculta el boton de eliminar_kit
					$('#tr_eliminar_' + $objeto['id_kit']).removeClass().addClass("danger");
					$btn.hide();
					
					var $mensaje = 'Eliminado con exito';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'success',
					});
				
					return 0;
				}
			}).fail(function(resp) {
				console.log('----> Fail eliminar_kit');
				console.log(resp);
				
			// Quita el loader
				$btn.button('reset');
			
			// Mensaje error
				$mensaje = 'Error, no se puede eliminar el kit';
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

///////////////// ******** ---- 				FIN eliminar_combo				------ ************ //////////////////

///////////////// ******** ---- 				vista_nuevo_combo				------ ************ //////////////////
//////// Carga la vista para un nuevo combo
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_nuevo_combo : function($objeto) {
		console.log('----> Objeto vista_nuevo_combo');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_nuevo_combo',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_nuevo_combo');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_nuevo_combo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_nuevo_combo				------ ************ //////////////////

///////////////// ******** ---- 			agregar_producto_combo				------ ************ //////////////////
//////// Agrega un producto al array de los productos agregados del combo
	// Como parametros recibe:
		// id -> ID del producto
		// div -> ID de la div donde se cargara la vista
		// id_unidad -> ID de la unidad
		// unidad_compra -> ID de la unidad de compra
		// nombre -> nombre del producto
		// unidad -> nombre de la unidad
		// check -> valor del check(true o false)
		
	agregar_producto_combo : function($objeto) {
		console.log('objeto agregar producto_combo');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=agregar_producto_combo',
			type : 'POST',
			dataType : 'html',
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar producto '+$objeto['id']);
			console.log(resp);
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			console.log('----> check');
			console.log($objeto['check']);
			
			var tabla = $('#tabla_productos').dataTable();
    		var tabla = tabla.fnGetNodes();
			
			$(tabla).each(function (index){
				id = $(this,tabla).attr('id');
				
				if(id == 'tr_'+$objeto['id']){
					checkbox = $(this,tabla).find('input');
					
					if($objeto['check'] === false){
						checkbox.prop("checked", true);
						$(this,tabla).addClass('success');
					}else{
						checkbox.prop("checked", false);
						$(this,tabla).addClass('success');
						$(this,tabla).removeClass('success');
					}
				}
    		});
		}).fail(function(resp) {
			console.log('----> Fail agregar producto_combo');
			console.log(resp);
			
			$mensaje = 'Error al agregar el producto';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN agregar_producto_combo				------ ************ //////////////////

///////////////// ******** ---- 				agregar_grupo					------ ************ //////////////////
//////// Crea un nuevo grupo y lo selecciona
	// Como parametros recibe:
		// grupo -> ID del grupo a agregar
		// div -> Din donde se cargara en contenido
		
	agregar_grupo : function($objeto) {
		console.log('objeto agregar producto_combo');
		console.log($objeto);
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=agregar_grupo',
			type : 'POST',
			dataType : 'html',
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar_grupo ');
			console.log(resp);
			
		// Deselecciona los productos de la tabla si se esta eliminando
			if($objeto['grupo']){
				var tabla = $('#tabla_productos').dataTable();
	    		var tabla = tabla.fnGetNodes();
				
				$.each($objeto['productos'], function(index, value) {
					$(tabla).each(function (index){
						id = $(this,tabla).attr('id');
						
						if(id == 'tr_'+value['id']){
							checkbox = $(this,tabla).find('input');
							
							checkbox.prop("checked", false);
							$(this,tabla).removeClass('success');
						}
		    		});
				});
			}
			
		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail agregar_grupo');
			console.log(resp);
			
			$mensaje = 'Error al agregar el grupo';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_grupo					------ ************ //////////////////

///////////////// ******** ---- 			seleccionar_grupo					------ ************ //////////////////
//////// Selecciona el grupo, cambia el color del panel y lo expande
	// Como parametros recibe:
		// grupo -> ID del grupo
	
	seleccionar_grupo : function($objeto) {
		console.log('objeto seleccionar_grupo');
		console.log($objeto);
		
	// Selecciona el grupo
		$('#grupo').val($objeto['grupo']);
		
	// Cambia todos los paneles seleccionados a no seleccionados, los contrae y muestra sus botones
		$('.panel-info').removeClass("panel-info").addClass("panel-default");
		$('.btn-info').show();
		$('.contraer').removeClass("in");
		
	// Cambia el color del panel, oculta su boton y expande el panel
		$('#panel_'+$objeto['grupo']).removeClass("panel-default").addClass("panel-info");
		$('#btn_seleccionar_grupo_'+$objeto['grupo']).hide();
		$('#heading_'+$objeto['grupo']).click();
	},

///////////////// ******** ---- 			FIN seleccionar_grupo				------ ************ //////////////////

///////////////// ******** ---- 				guardar_combo					------ ************ //////////////////
//////// Guarda la informacion en la DB
	// Como parametros recibe:
		// form -> formulario con los datos a guardar

	guardar_combo : function($objeto) {
		console.log('---------> $objeto guardar');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#btn_guardar_combo").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		// if ($datos['dias'] == '') {
			// var $mensaje = 'Debes seleccionar al menos un dia';
			// $("#btn_guardar_combo").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
	
	// Valida la hora de inicio y fin
		// if ($datos['inicio'] == '' || $datos['fin'] == '') {
			// var $mensaje = 'Agrega una hora de inicio y fin';
			// $("#btn_guardar_combo").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
		
	/* FIN Validaciones
	=============================================================== */
	
		$datos['costo'] = $objeto['costo'];
		
		console.log('---------> datos guardar_combo');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#btn_guardar_combo');
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=guardar_combo',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done guardar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_nuevo').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_guardar_combo").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre del combo';
				$("#btn_guardar_combo").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail guardar_combo');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al guardar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN guardar_combo				------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_combo				------ ************ //////////////////
//////// Carga la vista para editar los combos
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	vista_editar_combo : function($objeto) {
		console.log('----> Objeto vista_editar_combo');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_combo',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_editar_combo');
			console.log(resp);
			
		// Carga la vista de de nueva promocion
			configuracion.vista_nuevo_combo({div:'div_combos', btn:'btn_nuevo', panel:'primary'});
			
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_editar_combo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');

		// Loader
			$("#"+$objeto['div']).html('Error, no se puede cargar los datos');
		
			$mensaje = 'Error, no se puede cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN vista_editar_combo			------ ************ //////////////////

///////////////// ******** ---- 				editar_combo					------ ************ //////////////////
//////// Carga la vista y la llena con los datos del combo
	// Como parametros recibe:
		// id_combo -> ID del combo
		// nombre -> Nombre del insumo
		// costo -> Costo del combo
		// precio -> Precio del combo
		// productos -> array con los productos del combo

	editar_combo : function($objeto) {
		console.log('----> Objeto editar_combo');
		console.log($objeto);
		
	// Loader
		$("#div_editar").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Retrasa la funcion para que alcance a cargar la vista
		setTimeout(function() {
		// Agrega los insumos y los subtotales de los insumos
			$.each($objeto["productos"], function(index, val) {
			// Formateamos el array
				val['id'] = val['idProducto'];
				val['check'] = false;
				val['div'] = $objeto['div'];
				val['vista'] = 'listar_productos_agregados_combo';
			
			// Agrega el producto al array de los  productos agregados
				configuracion.agregar_producto_combo(val);
			});
		
		// LLena los campos
			$("#nombre").val($objeto['nombre']);
			$("#codigo").val($objeto['codigo']);
			$("#inicio").val($objeto['inicio']);
			$("#fin").val($objeto['fin']);
			$("#costo_total").html($objeto['costo']);
		
		// Dias
			if ($objeto['dias'].indexOf('0') != -1) {
				$('#do').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('1') != -1) {
				$('#lu').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('2') != -1) {
				$('#ma').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('3') != -1) {
				$('#mi').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('4') != -1) {
				$('#ju').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('5') != -1) {
				$('#vi').prop('checked', true);
			}
			if ($objeto['dias'].indexOf('6') != -1) {
				$('#sa').prop('checked', true);
			}
		
		// Actualiza el precio despues de 1 segundo para dar tiempo a la funcion que agrega los productos
			setTimeout ("$(\"#precio\").val("+$objeto['precio']+");", 500);
			
		// Muestra el boton de actualiza y le agrega el ID del combo
			$('#btn_actualizar_combo').show();
			$('#btn_actualizar_combo').attr('id_combo', $objeto['id_combo']);
			
		// Oculta el boton de guardar
			$("#btn_guardar_combo").hide();
			
		// Cierra la ventana modal
			$('#btn_cerrar_editar').click();
		}, 2000);
	},

///////////////// ******** ---- 			FIN editar_combo					------ ************ //////////////////

///////////////// ******** ---- 			actualizar_combo					------ ************ //////////////////
//////// Actualiza la informacion en la DB del combo
	// Como parametros recibe:
		// form -> formulario con los datos a guardar
		// costo -> costo del combo
		// id_combo -> ID del combo

	actualizar_combo : function($objeto) {
		console.log('---------> $objeto actualizar');
		console.log($objeto);
		
		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Debes llenar los siguientes campos: \n';
		
	/* Validaciones
	=============================================================== */
	
	// obtiene los inputs y los recorre
		var $inputs = $('#' + $objeto.form+ ' :input');
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0 && id) {
				error = 1;

				$requeridos.push(id);
			}
			if(id){
				$datos[this.id] = $(this).val();
			}
		});
		
	// Forma el mensaje con los campos requeridos
		if ($requeridos.length > 0) {
			$.each($requeridos, function(index, value) {
				$mensaje += '-->' + this + ' \n';
			});
		}
		
	// Si hay algun error, manda un mensaje
		if (error == 1) {
			$("#btn_actualizar_combo").notify($mensaje, {
				position : "top left",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'warn',
			});
			
			return 0;
		}
		
		$datos['dias'] = '';
	// Domingo
		if ($('#do').prop('checked')) {
			$datos['dias'] += '0';
		}
	// Lunes
		if ($('#lu').prop('checked')) {
			$datos['dias'] += ',1';
		}
	// Martes
		if ($('#ma').prop('checked')) {
			$datos['dias'] += ',2';
		}
	// Miercoles
		if ($('#mi').prop('checked')) {
			$datos['dias'] += ',3';
		}
	// Jueves
		if ($('#ju').prop('checked')) {
			$datos['dias'] += ',4';
		}
	// Viernes
		if ($('#vi').prop('checked')) {
			$datos['dias'] += ',5';
		}
	// Sabado
		if ($('#sa').prop('checked')) {
			$datos['dias'] += ',6';
		}

	// ** Valida que se seleccione un dia al menos
		// if ($datos['dias'] == '') {
			// var $mensaje = 'Debes seleccionar al menos un dia';
			// $("#btn_actualizar_combo").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
	
	// Valida la hora de inicio y fin
		// if ($datos['inicio'] == '' || $datos['fin'] == '') {
			// var $mensaje = 'Agrega una hora de inicio y fin';
			// $("#btn_actualizar_combo").notify($mensaje, {
				// position : "top left",
				// autoHide : true,
				// autoHideDelay : 4000,
				// className : 'warn',
			// });
// 			
			// return 0;
		// }
		
	/* FIN Validaciones
	=============================================================== */
	
		$datos['costo'] = $objeto['costo'];
		$datos['id_combo'] = $objeto['id_combo'];
		
		console.log('---------> datos actualizar_combo');
		console.log($datos);
		
	// Loader en el boton OK
		var $btn = $('#btn_actualizar_combo');
		$btn.button('loading');

		$.ajax({
			data : $datos,
			url : 'ajax.php?c=configuracion&f=actualizar_combo',
			type : 'post',
			dataType : 'json',
		}).done(function(resp) {
			console.log('----> Done actualizar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Todo bien :D
			if (resp['status'] == 1) {
				var $mensaje = 'Datos guardados';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

			// Limpia los campos
				$('#btn_editar').click();

				return 0;
			}

		// Sin productos :p
			if (resp['status'] == 2) {
				var $mensaje = 'Necesitas agregar productos';
				$("#btn_actualizar_combo").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}

		// El producto ya existe
			if (resp['status'] == 3) {
				var $mensaje = 'Cambia el nombre del combo';
				$("#btn_actualizar_combo").notify($mensaje, {
					position : "top left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('----------> fail actualizar_combo');
			console.log(resp);

			$btn.button('reset');

			var $mensaje = 'Error al actualizar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 4000,
				className : 'error',
			});
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_combo				------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_combo				------ ************ //////////////////
//////// Consulta los combos, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta
		// vista -> Vista que se debe cargar

	vista_eliminar_combo : function($objeto) {
		console.log('----> Objeto vista_eliminar_combo');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=vista_editar_combo',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_eliminar_combo');
			console.log(resp);
			
		// Carga la vista de los combos
			configuracion.vista_nuevo_combo({div:'div_combos', btn:'btn_nuevo'});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail vista_eliminar_combo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#"+$objeto['div']).html('Error al cargar los datos');
		
		// Mensaje error
			$mensaje = 'Error, no se pueden cargar los datos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN vista_eliminar_combo			----- ************ //////////////////

///////////////// ******** ---- 					eliminar_combo				------ ************ //////////////////
//////// Elimina el combo y sus productos
	// Como parametros recibe:
		// id_combo -> ID del combo

	eliminar_combo : function($objeto) {
		console.log('----> Objeto eliminar_combo');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		if(confirm("¿Estas seguro que quieres eliminar el combo?")){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=configuracion&f=eliminar_combo',
				type : 'POST',
				dataType : 'json'
			}).done(function(resp) {
				console.log('----> Done eliminar_combo');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
			// Todo bien :D
				if(resp['status'] == 1){
				// Indica que se elimino el combo y oculta el boton de eliminar_combo
					$('#tr_eliminar_' + $objeto['id_combo']).removeClass().addClass("danger");
					$btn.hide();
					
					var $mensaje = 'Eliminado con exito';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'success',
					});
				
					return 0;
				}
			}).fail(function(resp) {
				console.log('----> Fail eliminar_combo');
				console.log(resp);
				
			// Quita el loader
				$btn.button('reset');
			
			// Mensaje error
				$mensaje = 'Error, no se puede eliminar el combo';
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

///////////////// ******** ---- 				FIN eliminar_combo				------ ************ //////////////////

}; 