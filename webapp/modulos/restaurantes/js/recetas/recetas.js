/**
 * @author Fer De La Cruz
 */
var $total=0;
var $total_preparados=0;
var $costo=0;
var recetas = {
///////////////// ******** ---- 			vista_nueva				------ ************ //////////////////
//////// Consulta los productos, las recetas y las agrega a un div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_nueva : function($objeto) {
		console.log('----> Objeto nueva');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=vista_nueva',
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
			if ($objeto['tipo'] == 2) {
				$('#' + $objeto['objeto2']['div']).html($objeto['objeto2']['resp']);
			
				$('.selectpicker').selectpicker('refresh');
			};
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

///////////////// ******** ---- 		FIN vista_nueva				------ ************ //////////////////

///////////////// ******** ---- 		convertir_dataTable			------ ************ //////////////////
//////// Conviertela tabla en dataTable
	// Como parametros recibe:
		// id -> ID de la tabla a convertir
	
	convertir_dataTable : function($objeto) {
		console.log('objeto convertir dataTable');
		console.log($objeto);
	
	// 2 -> El usuario no puede ordenar la tabla
		var ordenar = ($objeto['ordenar'] == 2) ? false : true;
	// Ordena la tabla(default desc 1ra columna)
		var $orden = ($objeto['orden']) ? $objeto['orden'] : 'desc';
		
		var botones = [
			// { extend: 'pageLength', className: 'btn btn-default' },
			{
				extend : 'excel',
				className : 'btn btn-success',
				exportOptions : {
					columns : [0, 1, 2, 3, 4]
				}
			}, {
				extend : 'print',
				className : 'btn btn-info',
				exportOptions : {
					columns : [0, 1, 2, 3, 4]
				}
			}
		];

	// Oculta los botones
		if($objeto.botones == 2){
			botones = [];
		}
		
	// Validacion para evitar error al crear el dataTable
		if (!$.fn.dataTable.isDataTable('#' + $objeto['id'])) {
			$('#' + $objeto['id']).DataTable({
				ordering: ordenar,
			    buttons: botones,
				dom : 'Bfrtip',
				language : {
					buttons : {
						print : "<i class='fa fa-print'></i> Imprimir",
						excel : "<i class='fa fa-file-excel-o'></i> Exportar",
					},			
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
				order: [[0, $orden]]
			});
		}
	},
	
///////////////// ******** ---- 			FIN convertir_dataTable				------ ************ //////////////////
///////////////// ******** ---- 		agregar_insumo			------ ************ //////////////////
//////// Agrega un insumo al array de los insumos agregados
	// Como parametros recibe:
		// id -> ID del insumo
		// div -> ID de la div donde se cargara la vista
		// id_unidad -> ID de la unidad
		// unidad_compra -> ID de la unidad de compra
		// nombre -> nombre del insumo
		// unidad -> nombre de la unidad
		// check -> valor del check(true o false)
		
	agregar_insumo : function($objeto) {
		console.log('objeto agregar insumo');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		var type = 'json';
		if ($objeto['length'] == $objeto['index']) {
			type = 'html'
		}
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=agregar_insumo',
			type : 'POST',
			dataType : type,
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar insumo '+$objeto['id']);
			console.log(resp);
			
			if ($objeto['length'] == $objeto['index']) {
			// Carga la vista a la div
				$('#' + $objeto['div']).html(resp);
				
				$('.selectpicker').selectpicker('refresh');
				
				
				
			}
			console.log('----> check');
			console.log($objeto['check']);
			var tabla = $('#tabla_insumos').dataTable();
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
			console.log('----> Fail agregar insumo');
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
	
///////////////// ******** ---- 		FIN agregar_insumo			------ ************ //////////////////

///////////////// ******** ---- 		agregar_insumo_preparado	------ ************ //////////////////
//////// Agrega un insumo preparado al array de los insumos agregados
	// Como parametros recibe:
		// id -> ID del insumo
		// div -> ID de la div donde se cargara la vista
		// id_unidad -> ID de la unidad
		// unidad_compra -> ID de la unidad de compra
		// nombre -> nombre del insumo
		// unidad -> nombre de la unidad
		// check -> valor del check(true o false)
		// preparado -> 1 si es insumo preparado
	
	agregar_insumo_preparado : function($objeto) {
		console.log('objeto agregar insumo preparado');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		var type = 'json';
		if ($objeto['length'] == $objeto['index']) {
			type = 'html'
		}
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=agregar_insumo',
			type : 'POST',
			dataType : type,
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar insumo preparado '+$objeto['id']);
			console.log(resp);
			if ($objeto['length'] == $objeto['index']) {
			// Carga la vista a la div
				$('#' + $objeto['div']).html(resp);
				
				$('.selectpicker').selectpicker('refresh');
				
				console.log('----> check');
				console.log($objeto['check']);
				
				if($objeto['check']===false){
					$('#check_preparado_'+$objeto['id']).prop("checked", true);
					$('#tr_preparado_'+$objeto['id']).addClass('success');
				}else{
					$('#check_preparado_'+$objeto['id']).prop("checked", false);
					$('#tr_preparado_'+$objeto['id']).removeClass('success');
				}
			}
		}).fail(function(resp) {
			console.log('----> Fail agregar insumo');
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
	
///////////////// ******** ---- 		FIN agregar_insumo			------ ************ //////////////////

///////////////// ******** ---- 		agregar_servicio	------ ************ //////////////////
//////// Agrega un insumo preparado al array de los insumos agregados
	// Como parametros recibe:
		// id -> ID del insumo
		// div -> ID de la div donde se cargara la vista
		// id_unidad -> ID de la unidad
		// unidad_compra -> ID de la unidad de compra
		// nombre -> nombre del insumo
		// unidad -> nombre de la unidad
		// check -> valor del check(true o false)
		// preparado -> 1 si es insumo preparado
	
	agregar_servicio : function($objeto) {
		console.log('objeto agregar servicio');
		console.log($objeto);
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		var type = 'json';
		if ($objeto['length'] == $objeto['index']) {
			type = 'html'
		}
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=agregar_insumo',
			type : 'POST',
			dataType : type,
			async:false
		}).done(function(resp) {
			console.log('----> Done agregar servico '+$objeto['id']);
			console.log(resp);
			if ($objeto['length'] == $objeto['index']) {
			// Carga la vista a la div
				$('#' + $objeto['div']).html(resp);
				
				$('.selectpicker').selectpicker('refresh');
				
				console.log('----> check');
				console.log($objeto['check']);
				
				if($objeto['check']===false){
					$('#check_servicios_'+$objeto['id']).prop("checked", true);
					$('#tr_servicio_'+$objeto['id']).addClass('success');
				}else{
					$('#check_servicios_'+$objeto['id']).prop("checked", false);
					$('#tr_servicio_'+$objeto['id']).removeClass('success');
				}
			}
		}).fail(function(resp) {
			console.log('----> Fail agregar insumo');
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
	
///////////////// ******** ---- 		FIN agregar_insumo			------ ************ //////////////////


///////////////// ******** ---- 		calcular_precio				------ ************ //////////////////
//////// Calcula el sub total del insumo, el total de la receta y carga los valores
	// Como parametros recibe:
		// id -> ID del insumo
		// cantidad -> cantidad del insumo
		// preparado -> 1 si es insumo preparado
		
	calcular_precio : function($objeto) {
		console.log('objeto calcular_precio');
		console.log($objeto);

	// Loader
		if($objeto['preparado'] == 1){
			$('#loader_preparado_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}else{
			$('#loader_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}
		$objeto['id_pro'] = $("#select_costo_proveedor_"+$objeto['id']).val(); 
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=calcular_precio',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done calcular precio');
			console.log(resp);
			
		// Quita el loader
			if($objeto['preparado'] == 1){
				$('#loader_preparado_'+$objeto['id']).html('<i class="fa fa-slack"></i>');
			}else{
				$('#loader_'+$objeto['id']).html('<i class="fa fa-slack"></i>');
			}
			
			$total=0;
			$total_preparados=0;
			$costo=0;
			
			if(resp["insumos"]){
			// Agrega los subtotales a los insumos
				$.each(resp["insumos"], function(index, val) {
				// Calculamos el sub-total de cada insumo, el total y los cargamos
					if(val['sub_total']){
						if(val.costear != 2){
							$total += parseFloat(val['sub_total']);
							$costo += parseFloat(val['sub_total']);
							
							$('#sub_total_'+val['id']).html('$ '+val['sub_total']);
						}else{
							$('#sub_total_'+val['id']).html('$ 0');
						}
					}else{
						$('#sub_total_'+val['id']).html('$ 0');
					}
				});
			
			// Actualiza el total
				$('#total').html($total);
			}
			
			if(resp["insumos_preparados"]){
			// Agrega los subtotales a los insumos
				$.each(resp["insumos_preparados"], function(index, val) {
				// Calculamos el sub-total de cada insumo, el total y los cargamos
					if(val['sub_total']){
						if(val.costear != 2){
							$total_preparados += parseFloat(val['sub_total']);
							$costo += parseFloat(val['sub_total']);
							
							$('#sub_total_preparado_'+val['id']).html('$ '+val['sub_total']);
						}else{
							$('#sub_total_preparado_'+val['id']).html('$ 0');
						}
					}else{
						$('#sub_total_preparado_'+val['id']).html('$ 0');
					}
				});
			
			// Actualiza el total
				$('#total_preparados').html($total_preparados);
			}
		// Actualiza el precio de venta
			$total = $('#total').html();
			$total = (!$total) ? 0 : parseFloat($total) ;
			
			$total_preparados = $('#total_preparados').html();
			$total_preparados = (!$total_preparados) ? 0 : parseFloat($total_preparados) ;
		
			$('#precio_venta').val($total + $total_preparados);
			
		// calcula la ganancia
			recetas.calcular_ganancia({porcentaje:$('#margen_ganancia').val()});
		}).fail(function(resp) {
			console.log('----> Fail calcular precio');
			console.log(resp);
			
			$mensaje = 'Error, no se pueden hacer cambios';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN calcular_precio			------ ************ //////////////////

///////////////// ******** ---- 			costear					------ ************ //////////////////
//////// Valida si se debe de costear o no ese insumo
	// Como parametros recibe:
		// id -> ID del insumo
		// check -> Valor del check
		
	costear : function($objeto) {
		console.log('objeto costear');
		console.log($objeto);

	// Loader
		if($objeto['preparado'] == 1){
			$('#loader_preparado_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}else{
			$('#loader_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=costear',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done costear');
			console.log(resp);
			
		// Quita el loader
			if($objeto['preparado'] == 1){
				$('#loader_preparado_'+$objeto['id']).html('<i class="fa fa-slack"></i>');
			}else{
				$('#loader_'+$objeto['id']).html('<i class="fa fa-slack"></i>');
			}
			
			$total = 0;
			$total_preparados = 0;
			$costo = 0;
			
			if(resp["insumos"]){
			// Agrega los subtotales a los insumos
				$.each(resp["insumos"], function(index, val) {
				// Calculamos el sub-total de cada insumo, el total y los cargamos
					if(val['sub_total']){
						if(val.costear != 2){
							$total += parseFloat(val['sub_total']);
							$costo += parseFloat(val['sub_total']);
							
							$('#sub_total_'+val['id']).html('$ '+val['sub_total']);
						}else{
							$('#sub_total_'+val['id']).html('$ 0');
						}
					}else{
						$('#sub_total_'+val['id']).html('$ 0');
					}
				});
			
			// Actualiza el total
				$('#total').html($total);
			}
			
			if(resp["insumos_preparados"]){
			// Agrega los subtotales a los insumos
				$.each(resp["insumos_preparados"], function(index, val) {
				// Calculamos el sub-total de cada insumo, el total y los cargamos
					if(val['sub_total']){
						if(val.costear != 2){
							$total_preparados += parseFloat(val['sub_total']);
							$costo += parseFloat(val['sub_total']);
							
							$('#sub_total_preparado_'+val['id']).html('$ '+val['sub_total']);
						}else{
							$('#sub_total_preparado_'+val['id']).html('$ 0');
						}
					}else{
						$('#sub_total_preparado_'+val['id']).html('$ 0');
					}
				});
			
			// Actualiza el total
				$('#total_preparados').html($total_preparados);
			}
		// Actualiza el precio de venta
			$total = $('#total').html();
			$total = (!$total) ? 0 : parseFloat($total) ;
			
			$total_preparados = $('#total_preparados').html();
			$total_preparados = (!$total_preparados) ? 0 : parseFloat($total_preparados) ;
		
			$('#precio_venta').val($total + $total_preparados);
			
		// calcula la ganancia
			recetas.calcular_ganancia({porcentaje:$('#margen_ganancia').val()});
		}).fail(function(resp) {
			console.log('----> Fail calcular precio');
			console.log(resp);
			
			$mensaje = 'Error, no se pueden hacer cambios';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN costear					------ ************ //////////////////

///////////////// ******** ---- 		calcular_ganancia			------ ************ //////////////////
//////// Calcula la ganancia segun el precio de venta
	// Como parametros recibe:
		// porcentaje -> porcentaje que se quiere ganar
	
	calcular_ganancia : function($objeto) {
		console.log('objeto calcular_ganancia');
		console.log($objeto);
	
	// Calculamos el nuevo precio
		$total = $('#total').html();
		$total = (!$total) ? 0 : parseFloat($total) ;
		
		$total_preparados = $('#total_preparados').html();
		$total_preparados = (!$total_preparados) ? 0 : parseFloat($total_preparados) ;
		
		$precio = ($total + $total_preparados);
		
		var $porcentaje=(parseFloat($precio)*$objeto['porcentaje'])/100;
		var $nuevo_precio=parseFloat($porcentaje)+parseFloat($precio);
	
	// Cambiamos el valor del precio de venta
		$('#precio_venta').val($nuevo_precio);
	},
	
///////////////// ******** ---- 		FIN calcular_ganancia			------ ************ //////////////////

///////////////// ******** ---- 		guardar							------ ************ //////////////////
//////// Guarda la receta o insumo preparado
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
		
	guardar : function($objeto) {

		$objeto['costo']=$costo;
		console.log('objeto guardar');
		console.log($objeto);

	
	// ** Validaciones
		if (!$objeto['nombre']) {
			var $mensaje = 'Escribe un nombre';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
		if (!$objeto['codigo']) {
			var $mensaje = 'Escribe un un codigo';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
		if (!$objeto['precio_venta']) {
			var $mensaje = 'Precio no valido';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=guardar',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done guardar');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Todo bien :D
			if(resp['status']==1){
				var $mensaje = 'Guardado';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			
			// Carga de nuevo la vista
				recetas.vista_nueva({div:'div_recetas', btn:'btn_nueva', panel:'success'});
				
				return 0;
			}
			
		// Sin insumos :p
			if(resp['status']==2){
				var $mensaje = 'Agrega unos cuantos insumos';
				$('#notificaciones').notify($mensaje, {
					position : "top right",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				
				return 0;
			}
			
		// El producto ya existe
			if(resp['status']==3){
				var $mensaje = 'Cambia el nombre y/o codigo';
				$('#notificaciones').notify($mensaje, {
					position : "top right",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				
				return 0;
			}
		}).fail(function(resp) {
			console.log('----> Fail guardar');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			$mensaje = 'Error al guardar';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN guardar			------ ************ //////////////////

///////////////// ******** ---- 			validar_codigo			------ ************ //////////////////
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
	
///////////////// ******** ---- 		FIN validar_codigo			------ ************ //////////////////

///////////////// ******** ---- 		guardar_opcionales			------ ************ //////////////////
//////// Guarda los opcionales del insumo
	// Como parametros recibe:
		// id -> ID del insumo
		// opcionales -> cadena con los IDS de los opcionales
		// preparado -> 1 si el insumo es preparado
	
	guardar_opcionales : function($objeto) {
		console.log('objeto guardar_opcionales');
		console.log($objeto);
		
	// Loader
		if($objeto['preparado']==1){
			$('#loader_select_preparado_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}else{
			$('#loader_select_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
		}
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=guardar_opcionales',
			type : 'POST',
			dataType : 'json',
			async:false
		}).done(function(resp) {
			console.log('----> Done guardar_opcionales');
			console.log(resp);
			
		// Quita el loader
			if($objeto['preparado']==1){
				$('#loader_select_preparado_'+$objeto['id']).html('<i class="fa fa-list-ul"></i>');
			}else{
				$('#loader_select_'+$objeto['id']).html('<i class="fa fa-list-ul"></i>');
			}
		}).fail(function() {
		// Quita el loader
			if($objeto['preparado']==1){
				$('#loader_select_preparado_'+$objeto['id']).html('<i class="fa fa-list-ul"></i>');
			}else{
				$('#loader_select_'+$objeto['id']).html('<i class="fa fa-list-ul"></i>');
			}
			
			$mensaje = 'Error, no se pueden hacer cambios';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN guardar_opcionales		------ ************ //////////////////

///////////////// ******** ---- 			vista_copiar			------ ************ //////////////////
//////// Consulta las recetas, los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_copiar : function($objeto) {
		console.log('----> Objeto vista_copiar');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=vista_copiar',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_copiar');
			console.log(resp);
			
		// Carga la vista de de nueva receta
			recetas.vista_nueva({div:'div_recetas', btn:'btn_nueva', panel:'warning'});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista_copiar');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			$("#"+$objeto['div']).html('Error al cargar los datos');
		
		// Mensaje error
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

///////////////// ******** ---- 		FIN vista_copiar			------ ************ //////////////////

///////////////// ******** ---- 				copiar				------ ************ //////////////////
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

	copiar : function($objeto) {
		console.log('----> Objeto copiar');
		console.log($objeto);
		
	// Cierra la ventana modal
		$('#btn_cerrar_copiar').click();
					
	// Agrega los insumos y los subtotales de los insumos
		$.each($objeto["insumos"], function(index, val) {
		// Formateamos el array
			val['id']=val['idProducto'];
			val['id_unidad']=val['idunidad'];
			val['unidad_compra']=val['idunidadCompra'];
			val['check']=false;
			val['div']=$objeto['div'];
			val['select']=val['opcionales'].split(",");
			val['index'] = index;
			val['length'] = $objeto["insumos"].length - 1;
		// Agrega el insumo al array de insumos agregados
			recetas.agregar_insumo(val);
		
		// Calcula el precio y el subtotal del insumo
			//recetas.calcular_precio(val);
		});
		
	// Agrega los insumos y los subtotales de los insumos preparados
		$.each($objeto["insumos_preparados"], function(i, v) {
		// Formateamos el array
			v['id']=v['idProducto'];
			v['id_unidad']=v['idunidad'];
			v['unidad_compra']=v['idunidadCompra'];
			v['check']=false;
			v['div']=$objeto['div'];
			v['select']=v['opcionales'].split(",");
			v['preparado']=1;
			v['index'] = i;
			v['length'] = $objeto["insumos_preparados"].length - 1;
		// Agrega el insumo al array de insumos agregados
			recetas.agregar_insumo_preparado(v);
		
		// Calcula el precio y el subtotal del insumo
			//recetas.calcular_precio(v);
		});
	
	// LLena los campos
		$("#nombre").val($objeto['nombre']);
		$("#margen_ganancia").val($objeto['ganancia']);
		$("#codigo").val($objeto['codigo']);
		$("#precio_venta").val($objeto['precio']);
		$("#preparacion").val($objeto['preparacion']);
		$("#precio_venta").val($objeto['precio']);
		$("#unidad_compra").val($objeto['idunidadCompra']);
		$("#unidad_venta").val($objeto['idunidad']);
	
	// Cambia el selec si es insumo preparado
		if($objeto['tipo_producto'] == 4){
			$("#tipo").val(2);
		}
		
		$('.selectpicker').selectpicker('refresh');
	},

///////////////// ******** ---- 			FIN copiar				------ ************ //////////////////

///////////////// ******** ---- 			vista_editar			------ ************ //////////////////
//////// Consulta las recetas, los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_editar : function($objeto) {
		console.log('----> Objeto vista_copiar');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=vista_editar',
			type : 'POST',
			async: false,
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_editar');
			console.log(resp);
			
		// Carga la vista de de nueva receta
			recetas.vista_nueva({tipo: 2, div:'div_recetas', btn:'btn_nueva', panel:'primary', objeto2: {div:$objeto['div'], resp: resp}});
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			//$('#' + $objeto['div']).html(resp);
			
			//$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista_editar');
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

///////////////// ******** ---- 		FIN vista_editar			------ ************ //////////////////

///////////////// ******** ---- 				editar				------ ************ //////////////////
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

	editar : function($objeto) {
		console.log('----> Objeto editar');
		console.log($objeto);
		console.log($objeto['insumos'][0]['idProducto']);
		//return 0;
	// Loader
		$("#div_editar").html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
	// Cierra la ventana modal
		$('#btn_cerrar_editar').click();
		if ($objeto['insumos'][0]['idProducto']!=undefined) {
	// Agrega los insumos y los subtotales de los insumos
		$.each($objeto["insumos"], function(index, val) {

		// Formateamos el array
			val['id'] = val['idProducto'];
			val['id_unidad'] = val['idunidad'];
			val['unidad_compra'] = val['idunidadCompra'];
			val['check'] = false;
			val['div'] = $objeto['div'];
			val['select'] = val['opcionales'].split(",");
			val['index'] = index;
			val['length'] = $objeto["insumos"].length - 1;
		
		// Agrega el insumo al array de insumos agregados
			recetas.agregar_insumo(val);
		
		// Calcula el precio y el subtotal del insumo
			//recetas.calcular_precio(val);
		});
		}
	// Agrega los insumos y los subtotales de los insumos preparados
		$.each($objeto["insumos_preparados"], function(i, v) {
		// Formateamos el array
			v['id']=v['idProducto'];
			v['id_unidad']=v['idunidad'];
			v['unidad_compra']=v['idunidadCompra'];
			v['check']=false;
			v['div']=$objeto['div'];
			v['select']=v['opcionales'].split(",");
			v['preparado']=1;
			v['index'] = i;
			v['length'] = $objeto["insumos_preparados"].length - 1;
		// Agrega el insumo al array de insumos agregados
			recetas.agregar_insumo_preparado(v);
		
		// Calcula el precio y el subtotal del insumo
			//recetas.calcular_precio(v);
		});
	
	// LLena los campos
		$("#nombre").val($objeto['nombre']);
		$("#margen_ganancia").val($objeto['ganancia']);
		$("#codigo").val($objeto['codigo']);
		$("#precio_venta").val($objeto['precio']);
		$("#preparacion").val($objeto['preparacion']);
		$("#unidad_compra").val($objeto['idunidadCompra']);
		$("#unidad_venta").val($objeto['idunidad']);
	
	// Actualiza el precio despues de 1 segundo para dar tiempo a la funcion que agrega los insumos
		setTimeout ("$(\"#precio_venta\").val("+$objeto['precio']+");", 1000);

	// Cambia el selec si es insumo preparado
		if($objeto['tipo_producto'] == 4){
			$("#tipo").val(2);
		}
		
		$('.selectpicker').selectpicker('refresh');
		
	// Muestra el boton de actualiza y le agrega el ID de la receta o insumo
		$('#btn_actualizar').show();
		$('#btn_actualizar').attr('id_receta', $objeto['idProducto']);
		$('#btn_precio_venta').attr('id_receta', $objeto['idProducto']);
		
	// Oculta el boton de guardar
		$("#btn_guardar_receta").hide();
	},

///////////////// ******** ---- 			FIN editar				------ ************ //////////////////

///////////////// ******** ---- 				actualizar			------ ************ //////////////////
//////// Actualizar la receta o insumo preparado
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
		
	actualizar : function($objeto) {
		$objeto['costo']=$costo;
		console.log('objeto actualizar');
		console.log($objeto);
	
	// ** Validaciones
		if (!$objeto['nombre']) {
			var $mensaje = 'Escribe un nombre';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
		if (!$objeto['codigo']) {
			var $mensaje = 'Escribe un un codigo';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
		if (!$objeto['precio_venta']) {
			var $mensaje = 'Precio no valido';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=actualizar',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done actualizar');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Todo bien :D
			if(resp['status']==1){
				var $mensaje = 'Modificado con exito';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});
			
			// Carga la vista para editar las recetas
				$('#btn_editar').click();
				
				return 0;
			}
			
		// Sin insumos :p
			if(resp['status']==2){
				var $mensaje = 'Agrega unos cuantos insumos';
				$('#notificaciones').notify($mensaje, {
					position : "top right",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
				});
				
				return 0;
			}
		}).fail(function(resp) {
			console.log('----> Fail actualizar');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			$mensaje = 'Error al modificar';
			$('#notificaciones').notify($mensaje, {
				position : "top right",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 		FIN actualizar					------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar				------ ************ //////////////////
//////// Consulta las recetas, los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta

	vista_eliminar : function($objeto) {
		console.log('----> Objeto vista_eliminar');
		console.log($objeto);

	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
	// Loader
		$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=vista_eliminar',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done vista_eliminar');
			console.log(resp);
	
		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
			
			$('.selectpicker').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('----> Fail vista_eliminar');
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

///////////////// ******** ---- 		FIN vista_eliminar			------ ************ //////////////////

///////////////// ******** ---- 				eliminar			------ ************ //////////////////
//////// Elimina una receta o insumo preparado
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado

	eliminar : function($objeto) {
		console.log('----> Objeto eliminar');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		if(confirm("¿Estas seguro que quieres eliminar?")){
			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=recetas&f=eliminar',
				type : 'POST',
				dataType : 'json'
			}).done(function(resp) {
				console.log('----> Done eliminar');
				console.log(resp);
		
			// Quita el loader
				$btn.button('reset');
				
			// Todo bien :D
				if(resp['status']==1){
				// Indica que se elimino la receta y oculta el boton de eliminar
					$('#tr_eliminar_' + $objeto['id']).removeClass().addClass("danger");
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
				
			// Error
				if(resp['status']==2){
					var $mensaje = 'Error al eliminar';
					$('#' + $objeto['btn']).notify($mensaje, {
						position : "top right",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});
					
					return 0;
				}
				
			}).fail(function(resp) {
				console.log('----> Fail eliminar');
				console.log(resp);
				
			// Quita el loader
				$btn.button('reset');
			
			// Mensaje error
				$mensaje = 'Error, no se puede eliminar';
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

///////////////// ******** ---- 			FIN eliminar				------ ************ //////////////////

///////////////// ******** ---- 			restaurar_precio			------ ************ //////////////////
//////// Busca el precio actual del producto y lo agrega al campo precio_venta
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		// btn -> boton del loader

	restaurar_precio : function($objeto) {
		console.log('----> Objeto restaurar_precio');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=restaurar_precio',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done restaurar_precio');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
			
		// Todo bien :D, actualiza el precio
			if(resp['status'] == 1){
				$('#precio_venta').val(resp['result']);
				
				return 0;
			}
		}).fail(function(resp) {
			console.log('----> Fail restaurar_precio');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Mensaje error
			$mensaje = 'Error, no se puede obtener el precio';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN restaurar_precio			------ ************ //////////////////

///////////////// ******** ---- 			preparar_insumo				------ ************ //////////////////
//////// Descuenta del inventario los insumos y prepara un insumo preparado
	// Como parametros recibe:
		// btn -> Boton del loader
		// id_producto -> ID del insumo preparado
		// cantidad -> Cantidad que se debe preparar del insumo

	preparar_insumo : function($objeto) {
		console.log('----> Objeto preparar_insumo');
		console.log($objeto);
	
	// ** Validaciones
		if (!$objeto['cantidad']) {
			var $mensaje = 'Escribe la cantidad';
			$('#' + $objeto['btn']).notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=preparar_insumo',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done preparar_insumo');
			console.log(resp);
			
		// Todo bien :D
			if(resp['status'] == 1){
			// Mensaje error
				$mensaje = 'Preparando insumo...';
				$('#' + $objeto['btn']).notify($mensaje, {
					position : "left",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
			
			// Asigna el ID de la preparacion y muestra el boton de terminado
				$("#btn_terminar_" + $objeto['id_producto']).attr("id_preparacion", resp['id_preparacion']);
				$("#btn_terminar_" + $objeto['id_producto']).show();
			
			// Bloquea la cantidad
				$("#cantidad_" + $objeto['id_producto']).prop( "disabled", true );
			}
		}).fail(function(resp) {
			console.log('----> Fail preparar_insumo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Mensaje error
			$mensaje = 'Error al preparar el insumo';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN preparar_insumo				------ ************ //////////////////

///////////////// ******** ---- 				terminar_insumo				------ ************ //////////////////
//////// Actualiza el inventario y el insumo preparado
	// Como parametros recibe:
		// btn -> Boton del loader
		// id -> ID del insumo preparado
		// id_preparacion -> ID de la preparacion
		// cantidad -> Cantidad que se debe preparar del insumo

	terminar_insumo : function($objeto) {
		console.log('----> Objeto terminar_insumo');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=terminar_insumo',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('----> Done terminar_insumo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
		// Todo bien :D
			if(resp['status'] == 1){
			// Mensaje error
				$mensaje = 'Insumo preparado';
				$("#cantidad_" + $objeto['id']).notify($mensaje, {
					position : "top",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});
				
				var $btn_preparar = $('#btn_preparar_' + $objeto['id']);
				$btn_preparar.button('reset');
			
				$("#cantidad_" + $objeto['id']).prop("disabled", false);
				$("#cantidad_" + $objeto['id']).val('');
				$("#btn_terminar_" + $objeto['id']).hide();
			}
		}).fail(function(resp) {
			console.log('----> Fail terminar_insumo');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Mensaje error
			$mensaje = 'Error al terminar';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN terminar_insumo				------ ************ //////////////////

///////////////// ******** ---- 			verReceta			------ ************ //////////////////
//////// Convierte el input en calendario
	// Como parametros recibe:
		// id -> ID del input

	verReceta : function($objeto) {
		console.log('objeto verReceta');
		console.log($objeto);
		if($objeto['insumos'] || $objeto['insumos_preparados']){
			$("#modal_ver_receta").modal("show");
		} else {
			alert('Esta receta no contiene insumos.');
			return 0;
		}
		
		if ($objeto['imagen']) {
			$("#img_ver_receta").show();
			$("#img_ver_receta").attr("src", '../pos/'+$objeto['imagen']);
		} else {
			$("#img_ver_receta").hide();
		}
		if($objeto['insumos']){
			$("#tabla_insumos").show();
			$("#title_insumos").show();
			$("#body_ver_receta").html('');
			$.each($objeto['insumos'], function(index, val) {
				var html = '<tr style="text-align:center">';
						html += '<td>'+parseInt(val['cantidad'])+'</td>';
						html += '<td>'+val['nombre']+'</td>';
						html += '<td>'+val['unidad']+'</td>';
						html += '<td>$ '+parseFloat(val['costo'])+'</td>';
						if(val['costear'] == 1){
							var cost_pre = val['cantidad'] * val['costo'];
						} else {
							var cost_pre = 0;
						}
						html += '<td>$ '+cost_pre+'</td>';
					html += '</tr>';
				$("#body_ver_receta").append(html);
			});
		} else {
			$("#tabla_insumos").hide();
			$("#title_insumos").hide();
		}
		if($objeto['insumos_preparados']){
			$("#tabla_insumos_preparados").show();
			$("#title_insumos_preparados").show();
			$("#body_ver_receta_2").html('');
			$.each($objeto['insumos_preparados'], function(index, val) {
				var html = '<tr style="text-align:center">';
						html += '<td>'+parseInt(val['cantidad'])+'</td>';
						html += '<td>'+val['nombre']+'</td>';
						html += '<td>'+val['unidad']+'</td>';
						html += '<td>$ '+parseFloat(val['costo'])+'</td>';
						if(val['costear'] == 1){
							var cost_pre = val['cantidad'] * val['costo'];
						} else {
							var cost_pre = 0;
						}
						html += '<td>$ '+cost_pre+'</td>';
					html += '</tr>';
				$("#body_ver_receta_2").append(html);
			});
		} else {
			$("#tabla_insumos_preparados").hide();
			$("#title_insumos_preparados").hide();
		}
		//$("#modal_ver_receta").click();
	},

///////////////// ******** ---- 			FIN verReceta			------ ************ //////////////////

///////////////// ******** ---- 			convertir_calendario			------ ************ //////////////////
//////// Convierte el input en calendario
	// Como parametros recibe:
		// id -> ID del input

	convertir_calendario : function($objeto) {
		console.log('objeto convertir_calendario');
		console.log($objeto);
		
		$.fn.datepicker.dates['es'] = 
		{
            days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
            daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", 
            	"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        };
        
		$("#"+$objeto['id']).datepicker({
			isRTL: false,
		 	format: 'yyyy-mm-dd',
		 	language: 'es',
	        autoclose :true
	    });
	},

///////////////// ******** ---- 			FIN convertir_calendario			------ ************ //////////////////

///////////////// ******** ---- 		listar_movimientos_inventario			------ ************ //////////////////
//////// Consluta las entradas y las salidas de los productos
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// sucursal -> ID de la sucursal
		// almacen -> ID del almacen
		// grafica -> 1 -> Dia, 2 -> Semana, 3 -> Mes, 4 -> Año
		// insumos -> string con los ID's de los insumos
		// tipo -> 3 -> Insumo, 4 -> insumo preparado
		// btn -> Boton del loader
		// div -> Div donde se carga el contenido

	listar_movimientos_inventario : function($objeto) {
		console.log('----> Objeto listar_movimientos_inventario');
		console.log($objeto);
		
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=recetas&f=listar_movimientos_inventario',
			type : 'POST',
			dataType : 'html'
		}).done(function(resp) {
			console.log('----> Done listar_movimientos_inventario');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

		// Carga la vista a la div
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('----> Fail listar_movimientos_inventario');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
		
		// Mensaje error
			$mensaje = 'Error al cosultar los insumos';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_movimientos_inventario		------ ************ //////////////////

}; 