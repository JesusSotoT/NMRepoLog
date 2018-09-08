var pedidos = Array();
var empleado = 0;
var comandas = {

// Inicializamos variables
	interval : '',
	tiempoRefresh : 10,
	intervalMinus : 0,
	detener : 0,
	idioma : 0,
	total_comanda: 0,
// Inicializamos el array de datos la comandera
	datos_mesa_comanda : {
		id_mesa: 0,
		id_comanda: 0,
		tipo: 0
	},
	totales_comandas : {},
	porcentajes_comandas: {},
	precio_comandas: {},
// Inicializamos el array de las mesas seleccionadas
	mesas_seleccionadas : {},

	areas : [],
	

	init : function() {
		comandas.buscaProductos();
	},

	timer : function(tiempo) {
		comandas.intervalMinus = comandas.tiempoRefresh - tiempo;

		$('.error').text("Se buscaran nuevos comandas en: " + comandas.intervalMinus + ' segundos.');
		comandas.interval = clearInterval(comandas.interval);

		if (tiempo < comandas.tiempoRefresh) {
			comandas.interval = setTimeout("comandas.timer(" + (tiempo + 1) + ")", 1000);
		} else {
			comandas.buscaProductos();
		}
	},

	tooltips : function(comanda, ids, lugar) {
		// tooltip para pedidos terminados
		$contenido = "	<div align='center'>";
		$contenido += "		Hay pedidos terminados<br/>";
		$contenido += "		en " + lugar + "<br/><br/>";

		$contenido += "		<button onclick='comandas.entregado(" + comanda + ",\"" + ids + "\")' type='button' class='btn btn-success'>";
		$contenido += "			<i class='fa fa-check'></i> Entregados";
		$contenido += "		</button>";
		$contenido += "	</div>";
		
		$('[id_comanda=' + comanda + ']').tooltipster({
			contentAsHTML : true,
			interactive : true,
			animation : 'fall',
			autoClose : false,
			theme : 'tooltipster-Shadow',
			zIndex : 1,
			content : $contenido,
		}).mouseenter();
		// tooltip para pedidos terminados fin
	},

	buscaProductos : function($objeto) {
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

		// Detiene las cosultas

		if(comandas.detener == 1){
			return 0;
		}
		
		comandas.interval = clearInterval(comandas.interval);

		
		//SE OMITE POR VELOCIDAD
		// Consulta si hay productos pendientes
		$.ajax({
			url : 'ajax.php?c=comandas&f=checkProductos',
			type : 'POST',
			dataType : 'json'
		}).done(function(data) {
			console.log('=========> Done buscaProductos');
			console.log(data);

			// 
			if (data["status"]) {
				if (navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate) {
					navigator.vibrate([500, 300, 100]);
				}
				$.each(data["comandas"], function(index, val) {
					var productos = (data["comandas"][index]["productos"]).substr(1);

					comandas.tooltips(index, productos, data["comandas"][index]["lugar"]);
				});
			}

			comandas.timer(0);
		}).fail(function() {
			comandas.timer(0);
			console.log("error");
		});
		//SE OMITE POR VELOCIDAD 
		

		var $fecha = new Date();
		var m = $fecha.getMonth() + 1;
		var $mes = (m < 10) ? '0' + m : m;
		var d = $fecha.getDate();
		var $dia = (d < 10) ? '0' + d : d;
		
		var $fecha_listar_espera = $fecha.getFullYear() + '-' + $mes + '-' + $dia;
		$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T';

		$objeto = {f_ini: $fecha+'00:01', f_fin: $fecha+'23:59'};
		console.log("lelo");
		console.log($objeto);
		// Consulta el estado de las mesas
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=checkMesas',
			type : 'POST',
			dataType : 'json'
		}).done(function(data) {
			console.log('=========> Done checkMesas');
			//console.log(data);
			
			if (data) {
				$.each(data, function(index, val) {		

							
					if(data[index]["id_comanda"] != 0){ // son mesas activas con comanda
						if (data[index]["juntas"] == null) { // mesas juntas
							if(data[index]["tipo_mesa"] == 1)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/ocupada_cuadrada_2p.png");
							else if(data[index]["tipo_mesa"] == 2)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/ocupada_cuadrada.png");
							else if(data[index]["tipo_mesa"] == 3)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/rectangulo_2p_ocupada.png");
							else if(data[index]["tipo_mesa"] == 4)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/ocupada_redonda_4p.png");
							else if(data[index]["tipo_mesa"] == 5)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/ocupada_redonda_2p.png");
							else if(data[index]["tipo_mesa"] == 6)
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/sillon_ocupado.png");
							else if(data[index]["tipo_mesa"] == 9)
								$('#silla_' + data[index]["mesa"]).css("background-color", "#6b4583");
						} else {
							$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/ocupada_juntadas.png");
						}
						$('#mesa_' + data[index]["mesa"]).attr('id_comanda', data[index]["id_comanda"]);
					} else {
						if(data[index]["id_res"] != null){ // sin reservacion
							if (data[index]["juntas"] == null) {
								if(data[index]["tipo_mesa"] == 1)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_cuadrada_2p.png");
								else if(data[index]["tipo_mesa"] == 2)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_cuadrada.png");
								else if(data[index]["tipo_mesa"] == 3)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_2ps_rectangular.png");
								else if(data[index]["tipo_mesa"] == 4)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_redonda_4ps.png");
								else if(data[index]["tipo_mesa"] == 5)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_redonda_2ps.png");
								else if(data[index]["tipo_mesa"] == 6)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/sillon_reservado.png");
								else if(data[index]["tipo_mesa"] == 9)
									$('#silla_' + data[index]["mesa"]).css("background-color", "#c69f34");
							} else {
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/reservada_juntadas.png");
							}
							
						} else if(data[index]["mesa_status"] == 4){ // bloqueda 4
							$("#mesa_"+data[index]["mesa"]).attr("mesa_status", '4');
							if (data[index]["juntas"] == null) {
								if(data[index]["tipo_mesa"] == 1)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/bloqueada_2ps.png");
								else if(data[index]["tipo_mesa"] == 2)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/mesa_bloqueada.png");
								else if(data[index]["tipo_mesa"] == 3)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/rectangular2_bloqueada.png");
								else if(data[index]["tipo_mesa"] == 4)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/bloqueada_redonda.png");
								else if(data[index]["tipo_mesa"] == 5)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/bloqueada2_redonda.png");
								else if(data[index]["tipo_mesa"] == 6)
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/sillon_bloqueado.png");
								else if(data[index]["tipo_mesa"] == 9)
									$('#silla_' + data[index]["mesa"]).css("background-color", "#848484");
							} else {
								$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/bloqueada_juntadas.png");
							}
						} else {
							$("#mesa_"+data[index]["mesa"]).attr("mesa_status", '1'); // libre

								if (data[index]["juntas"] == null) {
									if(data[index]["tipo_mesa"] == 1)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
									else if(data[index]["tipo_mesa"] == 2)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_cuadrada_4p.png");
									else if(data[index]["tipo_mesa"] == 3)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_rectangular_2ps.png");
									else if(data[index]["tipo_mesa"] == 4)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_redonda_4ps.png");
									else if(data[index]["tipo_mesa"] == 5)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_redonda_2ps.png");
									else if(data[index]["tipo_mesa"] == 6)
										$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/sillones.png");
									else if(data[index]["tipo_mesa"] == 9)
										$('#silla_' + data[index]["mesa"]).css("background-color", "#423228");
								} else {
									$('#img_' + data[index]["mesa"]).attr("src", "images/mapademesas/libre_juntadas.png");
								}

								///// ELIMINA MESA TEMPORAR CREADA DESDE COMANDERA AL CERRAR COMANDA
									if(data[index]["tipoMesa"] == 3 && data[index]["statusMesa"] == 2){
										$("#"+data[index]["mesa"]).remove();
									}										
								///// ELIMINA MESA TEMPORAR CREADA DESDE COMANDERA AL CERRAR COMANDA FIN


						}
					}
					if(data[index]['notificacion'] == 1){
						comandas.tooltip_mesa(data[index]);
					}
									
				});
			}

			comandas.timer(0);
		}).fail(function() {
			comandas.timer(0);
			console.log("error");
		});
	},

	entregado : function(comanda, ids) {
		console.log('objet entregado');
		console.log('comanda: '+comanda+' ids: '+ids);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			url : 'ajax.php?c=comandas&f=entregado',
			type : 'POST',
			dataType : 'json',
			data : {
				'comanda' : comanda,
				'ids' : ids
			},
		}).done(function(data) {
			console.log('done entregado');
			console.log(data);
			
			if (data) {
				comandas.timer(0);
				$("[id_comanda=" + comanda + "]").tooltipster("destroy");
				$("[id_comanda=" + comanda + "]").attr("title", '');
			}
		}).fail(function(data) {
			console.log('fail entregado');
			console.log(data);
			
			if(comandas.idioma == 1)
				var $mensaje = 'Error al entregar';
			else
				var $mensaje = 'Failed to deliver';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

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
			comandas.idioma = data;
		}).fail(function(data) {
			console.log('fail get_idioma');
			console.log(data);
			comandas.idioma = 1;
		});

   },
///////////////// ******** ---- 		FIN get_idioma		------ ************ //////////////////
   
///////////////// ******** ---- 		calendarios		------ ************ //////////////////
	//////// Cambia los input por calendarios.
		// Como parametros puede recibir:
			// Array con los id de los input

	calendarios : function($objeto) {
		console.log($objeto);

		$("#f_ini").datepicker({
			maxDate : 0,
			dateFormat : 'yyyy-mm-dd',
			numberOfMonths : 1,
			onSelect : function(selected) {
				$("#f_fin").datepicker("option", "minDate", selected);
			}
		});

		$("#f_fin").datepicker({
			dateFormat : 'yyyy-mm-dd',
			maxDate : 365,
			numberOfMonths : 1,
			onSelect : function(selected) {
				$("#f_ini").datepicker("option", "maxDate", selected);
			}
		});
	},

///////////////// ******** ---- 		FIN calendarios		------ ************ //////////////////

///////////////// ******** ---- 		promedio_comensal		------ ************ //////////////////
//////// Consulta el promedio por comensal y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// sucursal -> ID de la sucursal
		// empleado -> ID del empleado
		// comensales -> Numero de comensales
		// div -> div donde se cargara el contenido html

	promedio_comensal : function($objeto) {
		console.log('-------> Objeto promedio_comensal');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();

	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}

		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}

	// Loader en el boton OK
		var $btn = $('#btn_promedio');
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=promedio_comensal',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('-------> reponse promedio_comensal');
			console.log($objeto);
		
		// Quita el loader
			$btn.button('reset');
			
		// Carga los promedios en la div
			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable				
			comandas.convertir_dataTable({id:'tabla_promedios'});
		}).fail(function(resp) {
			console.log('------> Fail promedio_comensal');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los datos';
			else
				var $mensaje = 'Failed to get data';
			$.notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
			
		});
	},

///////////////// ******** ---- 		FIN promedio_comensal		------ ************ //////////////////

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
		if(comandas.idioma == 0)
			comandas.get_idioma();
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
		
		d = new Date();
		var dias = new Array('0','1','2','3','4','5','6')

		datetext = d.toTimeString();
		datetext =  datetext.split(' ')[0];
		$objeto['hour'] =  datetext.split(':')[0]+':'+datetext.split(':')[1];
		$objeto['day'] = dias[d.getDay()];
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=buscar_productos',
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
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los productos';
			else
				var $mensaje = 'Failed to get products';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
			});
		});
	},

///////////////// ******** ---- 		FIN buscar_productos		------ ************ //////////////////


///////////////// ******** ---- 			listar_comandas			------ ************ //////////////////
	//////// Consulta las comandas y lo agrega a la div
		// Como parametros recibe:
			// f_ini -> fecha y hora de inicio
			// F_fin -> fecha y hora final
			// status -> status de la comanda(abierta, cerrada, eliminada)
			// div -> div donde se cargara el contenido html

	listar_comandas : function($objeto) {
		console.log('------------> Objeto listar_comandas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#btn_buscar_comandas').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#btn_buscar_comandas').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
	
	// Loader en el boton OK
		var $btn = $('#btn_buscar_comandas');
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_comandas',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_comandas');
				console.log(resp);

				$btn.button('reset');

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener las comandas';
					else
						var $mensaje = 'Error: \n Failed to get the commands';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Agrega las comandas a la div
				$('#' + $objeto['div']).html(resp);
				
			// Crea el datatable
				comandas.convertir_dataTable({id:'tabla_comandas'});
			}
		});
	},

///////////////// ******** ---- 		FIN listar_comandas		------ ************ //////////////////

///////////////// ******** ---- 		listar_mesas		------ ************ //////////////////
	//////// Consulta las mesas y lo agrega a la div
		// Como parametros recibe:
			// empleado -> ID del empleado
			// asignar -> varoable para quitar las mesas de servicio a domicilio y para llevar
			// div -> div donde se cargara el contenido html

	listar_mesas : function($objeto) { 
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_mesas',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				$('#' + $objeto['div']).html(resp);

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener las comandas';
					else
						var $mensaje = 'Error: \n Failed to get the commands';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_mesas		------ ************ //////////////////

///////////////// ******** ---- 		asignar		------ ************ //////////////////
	//////// Agrega la mesa a los permisos del empleado
		// Como parametros recibe:
			// id -> ID del empleado
			// id_mesa -> ID de la mesa

	asignar : function($objeto) {
		$objeto['id'] = empleado;
		if(comandas.idioma == 0)
			comandas.get_idioma();
		console.log('-------------> $objeto asignar');
		console.log($objeto);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=asignar',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log('-------------> response asignar');
				console.log(resp);

			// Error: Manda un mensaje con el error
				if (resp['status'] == 0) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al asignar la mesa';
					else
						var $mensaje = 'Error: \n Failed to assign table';
					$('#btn_' + $objeto['id_mesa']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Se agrego una mesa al mesero
				if (resp['status'] == 1) {
					comandas.listar_asignacion({
						id : empleado
					});
				}
			}
		});
	},

///////////////// ******** ---- 		FIN asignar		------ ************ //////////////////

///////////////// ******** ---- 		listar_asignacion		------ ************ //////////////////
	//////// Obtien los permisos del empleado y palome los checks correspodientes
		// Como parametros recibe:
			// id -> ID del empleado

	listar_asignacion : function($objeto) {
		console.log('------------> $objeto listar_asignacion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		empleado = $objeto['id'];
		$('#id_empleado').val(empleado);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_asignacion',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log('------------> response listar_asignacion');
				console.log(resp);

			// Error: Manda un mensaje con el error
				if (resp['status'] == 0) {
					console.log('vacio');
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener las asignaciones';
					else
						var $mensaje = 'Error: \n Failed to get assignments';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Empleado CON permisos
				if (resp['status'] == 1) {
				// Desmarca las mesas
					$.each(resp['mesas'], function(index, val) {
						$('#btn_' + val['mesa']).removeClass("btn-success");
					});

				// Marca solo las mesas asignadas al empleado
					$.each(resp['permisos'], function(index, val) {
						$('#btn_' + val).addClass('btn-success');
					});

					return 0;
				}

			// Empleado SIN permisos
				if (resp['status'] == 2) {
				// Desmarca las mesas
					$.each(resp['mesas'], function(index, val) {
						$('#btn_' + val['mesa']).removeClass("btn-success");
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 		listar_agregados		------ ************ //////////////////
//////// Obtien los pedidos de la persona seleccionada
	// Como parametros recibe:
		// persona -> Numero de persona
		// Div -> div en donde se cargara el contenido
		// Clase -> la clase del color que se deben de pintar los productos

	listar_agregados : function($objeto) {
		console.log('------------> $objeto listar_agregados');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$('#persona').val($objeto['persona']);
		$('#clase').val($objeto['clase']);

	// Colorea la div del color de la persona
		$('#div_personas_personalizado').removeAttr('class');
		$('#div_personas_personalizado').addClass('col-xs-7 bg-' + $objeto['clase']);

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_agregados',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_agregados');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);
			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener los pedidos';
					else
						var $mensaje = 'Error: \n Failed to get orders';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_agregados		------ ************ //////////////////

///////////////// ******** ---- 		agregar_pedido		------ ************ //////////////////
	//////// Agrega un pedido a la persona seleccionada
		// Como parametros recibe:
			// persona -> Numero de persona
			// pedido -> array con los datos del pedido
			// Div -> din donde se cargara el contenido
			// Clase -> Clase que llevaran los botones

	agregar_pedido : function($objeto) {
		console.log('------------> $objeto agregar_pedido');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if ($objeto['persona'] == 0) {
			if(comandas.idioma == 1)
				var $mensaje = 'Selecciona una persona';
			else
				var $mensaje = 'Select a person';
			$('#' + $objeto['boton']).notify($mensaje, {
				position : "left middle",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_pedido',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response agregar_pedidos');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

				$('#pedido_' + $objeto['id']).remove();

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al agregar el pedido';
					else
						var $mensaje = 'Error: \n Error adding order';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			//Lista las sub_comandas
				$objet = Array();
				$objet['div'] = 'div_sub_comandas';
				comandas.listar_sub_comandas($objet);
			}
		});
	},

///////////////// ******** ---- 		FIN agregar_pedido		------ ************ //////////////////

///////////////// ******** ---- 		quitar_pedido		------ ************ //////////////////
	//////// Elimina un pedido a la persona seleccionada
		// Como parametros recibe:
			// persona -> Numero de persona
			// pedido -> array con los datos del pedido
			// Div -> din donde se cargara el contenido

	quitar_pedido : function($objeto) {
		console.log('------------> $objeto quitar_pedido');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=quitar_pedido',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response quitar_pedido');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

				$('#agregado_' + $objeto['id']).remove();

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al eliminar el pedido';
					else
						var $mensaje = 'Error: \n Failed to delete order';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			//Lista las sub_comandas
				$objet = Array();
				$objet['div'] = 'div_sub_comandas';
				comandas.listar_sub_comandas($objet);
			}
		});
	},

///////////////// ******** ---- 		FIN quitar_pedido		------ ************ //////////////////

///////////////// ******** ---- 		listar_pedidos		------ ************ //////////////////
	//////// Obtien los pedidos de la comanda
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido

	listar_pedidos : function($objeto) {
		console.log('------------> $objeto listar_pedidos');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_pedidos',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_pedidos');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener los pedidos';
					else
						var $mensaje = 'Error: \n Failed to get orders';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_pedidos		------ ************ //////////////////

///////////////// ******** ---- 		listar_sub_comandas		------ ************ //////////////////
	//////// Obtien las sub comandas y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido
			// status -> el estatus por el que filtrara la comanda
			// id-> ID de la comanda

	listar_sub_comandas : function($objeto) {
		console.log('------------> $objeto listar_sub_comandas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_sub_comandas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_sub_comandas');
			console.log(resp);
			
			$('#' + $objeto['div']).html(resp);
		
		// Error: Manda un mensaje con el error
			if (!resp) {
				if(comandas.idioma == 1)
					var $mensaje = 'Error al obtener los pedidos';
				else	
					var $mensaje = 'Failed to get orders';
				$('#' + $objeto['div']).notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail listar_sub_comandas');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los pedidos';
			else
				var $mensaje = 'Failed to get orders';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_sub_comandas		------ ************ //////////////////

///////////////// ******** ---- 		listar_sub_comandas_2		------ ************ //////////////////
	//////// Obtien las sub comandas y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido
			// status -> el estatus por el que filtrara la comanda
			// id-> ID de la comanda

	listar_sub_comandas_2 : function($objeto) {
		console.log('------------> $objeto listar_sub_comandas_2');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_sub_comandas_2',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_sub_comandas_2');
			console.log(resp);
			
			$('#' + $objeto['div']).html(resp);
		
		// Error: Manda un mensaje con el error
			if (!resp) {
				if(comandas.idioma == 1)
					var $mensaje = 'Error al obtener los pedidos';
				else	
					var $mensaje = 'Failed to get orders';
				$('#' + $objeto['div']).notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail listar_sub_comandas_2');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los pedidos';
			else
				var $mensaje = 'Failed to get orders';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_sub_comandas		------ ************ //////////////////

///////////////// ******** ---- 		listar_personas		------ ************ //////////////////
	//////// Obtienlas personas de la comanda y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido

	listar_personas : function($objeto) {
		console.log('------------> $objeto listar_personas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_personas',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_personas');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener los pedidos';
					else
						var $mensaje = 'Error: \n Failed to get orders';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_personas		------ ************ //////////////////

///////////////// ******** ---- 		listar_personas_2		------ ************ //////////////////
	//////// Obtienlas personas de la comanda y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido

	listar_personas_2 : function($objeto) {
		console.log('------------> $objeto listar_personas_2');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_personas_2',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_personas_2');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al obtener los pedidos';
					else
						var $mensaje = 'Error: \n Failed to get orders';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN listar_personas_2		------ ************ //////////////////

///////////////// ******** ---- 		agregar_persona		------ ************ //////////////////
	//////// Agrega una persona a la comanda y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido

	agregar_persona : function($objeto) {		
		console.log('------------> $objeto agregar_persona');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_persona',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response agregar_persona');
				console.log(resp);

				$('#' + $objeto['div']).html(resp);

			// Actualiza el input de borrar
				nuevo = 0;
				nuevo = parseInt($objeto['persona']) + parseInt($('#borrar_persona_personalizado').val());
				$('#borrar_persona_personalizado').val(nuevo);

			// Manda llamar a la funcion que lista los agregados de las personas
				$objeto['div'] = 'div_agregados_personalizado';
				$objeto['persona'] = 0;
				comandas.listar_agregados($objeto);
				comandas.listar_personas_2({div:'div_personas_personalizado_2'});

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al agregar la persona';
					else
						var $mensaje = 'Error: \n Error adding person';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN agregar_persona		------ ************ //////////////////

///////////////// ******** ---- 		quitar_persona		------ ************ //////////////////
	//////// Elimina una persona y sus productos de la comanda y las carga en una div
		// Como parametros recibe:
			// Div -> div en donde se cargara el contenido

	quitar_persona : function($objeto) {
		console.log('------------> $objeto quitar_persona');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		var personas = $('#borrar_persona_personalizado').val();

	// ** Validaciones
		if (personas < 1) {
			// Cierra la ventana modal
			$('#cerrar_modal_personalizar').click();

			return 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=quitar_persona',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				delete comandas.totales_comandas[$objeto['persona']];
				delete comandas.precio_comandas[$objeto['persona']];
				delete comandas.porcentajes_comandas[$objeto['persona']];
				console.log('------------> response quitar_persona');
				console.log(resp);

				$objet = Array();

				$('#' + $objeto['div']).html(resp);
			// Manda llamar a la funcion que lista las personas
				$objet['div'] = 'div_pedidos';
				comandas.listar_pedidos($objet);

			// Manda llamar a la funcion que lista los agregados de las personas
				$objeto['div'] = 'div_agregados_personalizado';
				$objeto['persona'] = 0;
				comandas.listar_agregados($objeto);
				comandas.listar_personas_2({div:'div_personas_personalizado_2'});

			// Actualiza el input de borrar
				nuevo = parseInt(personas) - 1;
				$('#borrar_persona_personalizado').val(nuevo);

			//Lista las sub_comandas
				$objet = Array();
				$objet['div'] = 'div_sub_comandas';
				comandas.listar_sub_comandas($objet);


			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al eliminar la persona';
					else
						var $mensaje = 'Error: \n Error deleting person';
					$('#' + $objeto['div']).notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN quitar_persona		------ ************ //////////////////

///////////////// ******** ---- 		guardar_comanda_parcial		------ ************ //////////////////
//////// Crear una comanda parcial, la guarda e imprime un Ticket
	// Como parametros recibe:
		// Persona -> numero de persona
		// idpadre -> ID de la comanda padre
		// mesa -> ID de la mesa

	guardar_comanda_parcial : function($objeto) {
		var tipodiv = $objeto.tipodiv;
		var id_comanda = $objeto.idpadre;

		if(tipodiv == 1){// dividir por porcentaje o cantidad

			console.log('----> totales');
			console.log(comandas.totales_comandas);
			console.log('----> porcentajes');
			console.log(comandas.porcentajes_comandas);

			var persona = new Object();
			
			 $.each(comandas.totales_comandas, function(index, val) {

		 		persona[index] = new Object();	
		 		persona[index].persona=index;
		 		persona[index].total=val;
		 		persona[index].idcomanda=id_comanda;	
			
			 	$.each(comandas.porcentajes_comandas, function(index1, val1) {

			 		if(index == index1){
			 			persona[index].porce=val1;
			 			persona[index].cantidad=val1/100;
			 		}

	         	});
	         });

			var pedidos = new Object();
			$.ajax({
					data : {id_comanda:id_comanda},
					url : 'ajax.php?c=comandas&f=pedidosDiv',
					type : 'POST',
					dataType : 'json',
					async:false,
			}).done(function(resp) {
				pedidos = resp;
			});

			var ped = '';
			$.each(persona, function(index, val) {
				ped = '';
				if(index > 0){
					$.each(pedidos, function(index1, val1) {
						ped += val1.id+',';					
					});
					persona[index]['pedidos']=ped;
				}
				persona[index]['pedidos'] = persona[index]['pedidos'].slice(0,-1);
			});

			var toti = 0;
			var toti2 = 0;
			$.each(comandas.totales_comandas, function(index, val) {
				toti += parseFloat(val);
				toti2 += parseFloat(val);
			});

			toti = comandas.total_comanda - toti;
			
			if(toti != 0){
				if(toti < 0){
					if(comandas.idioma == 1)
						var $mensaje = 'Excede el total';
					else
						var $mensaje = 'Missing orders to add';
					$('#btn_personalizado_ok').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});
					return 0;
				}else{
					if(comandas.idioma == 1)
						var $mensaje = 'Falta completar total';
					else
						var $mensaje = 'Missing orders to add';
					$('#btn_personalizado_ok').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});
					//return 0;
				}
			}else{ 

				// ch@ hasta aqui
				var total = new Object();
				$objeto.porcentaje = comandas.totales_comandas;
				$objeto.id_comanda = $objeto.idpadre;
				$objeto.persona = persona;
				$objeto.total = toti2;

				console.log('===============obj final=');
				console.log($objeto);
				//return false;

				
				var $btn = $('#btn_personalizado_ok');
				$btn.button('loading');

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=guardar_comanda_parcial2',
					type : 'POST',
					dataType : 'html',
				}).done(function(resp) {
					console.log('-------------> resp ajax');
					console.log(resp);

					$btn.button('reset');

					var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
						$(ventana).ready(function() { 
						//cargamos el HTML del objeto en la nueva ventana
							ventana.document.write(resp);
							// ventana.resizeTo(207.87, ventana.document.body.firstElementChild.clientHeight);
							ventana.document.close();
							
							setTimeout(closew,1000);
							function closew(){
								ventana.print();  //imprimimos la ventana
								ventana.close();
								
							// Valida si hay o no pedidos pendientes en la comanda, si hay cierra la  comanda
								comandas.comanda_padre({
									idComanda : $objeto['idpadre'],
									status_padre : 2,
									id_mesa: $objeto['mesa'],
									tipo_mesa: $objeto['tipo_mesa']
								});
							}
						});
				});
			}
		}else{ // dividir por productos
			console.log('------------> $objeto guardar_comanda_parcial');
			console.log($objeto);
			if(comandas.idioma == 0)
				comandas.get_idioma();
			// Loader en el boton OK
			var $btn = $('#btn_personalizado_ok');
			$btn.button('loading');

			$.ajax({
				data : $objeto,
				url : 'ajax.php?c=comandas&f=guardar_comanda_parcial',
				type : 'GET',
				dataType : 'html',
			}).done(function(resp) {
				console.log('------------> done guardar_comanda_parcial');
				console.log(resp);			
			
				// Quita el loader del boton
				$btn.button('reset');
				

				// Error: Al guardar la comanda, Manda un mensaje con el error
					if (!resp) {
						if(comandas.idioma == 1)
							var $mensaje = 'Error: \n Error al guardar la comanda';
						else
							var $mensaje = 'Error: \n Failed to save command';
						$('#' + $objeto['div']).notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});

						return 0;
					}

				// Error: La persona no tiene pedidos, Manda un mensaje con el error
					if (resp == 2) {
						if(comandas.idioma == 1)
							var $mensaje = 'Faltan pedidos por agregar';
						else
							var $mensaje = 'Missing orders to add';
						$('#btn_personalizado_ok').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});

						return 0;
					}

				// Error: Al actualizar la comanda, Manda un mensaje con el error
					if (resp == 3) {
						if(comandas.idioma == 1)
							var $mensaje = 'Error al actualizar la comanda';
						else
							var $mensaje = 'Error updating command';
						$('#btn_personalizado_ok').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});

						return 0;
					}

				// Error: Al actualizar los pedidos, Manda un mensaje con el error
					if (resp == 4) {
						if(comandas.idioma == 1)
							var $mensaje = 'Error al actualizar los pedidos';
						else
							var $mensaje = 'Error updating orders';
						$('#btn_personalizado_ok').notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'warn',
						});

						return 0;
					}

				//abrimos una ventana vacía nueva
					var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
					$(ventana).ready(function() { 
					//cargamos el HTML del objeto en la nueva ventana
						ventana.document.write(resp);
						// ventana.resizeTo(207.87, ventana.document.body.firstElementChild.clientHeight);
						ventana.document.close();
						
						setTimeout(closew,1000);
						function closew(){
							ventana.print();  //imprimimos la ventana
							ventana.close();
							
						// Valida si hay o no pedidos pendientes en la comanda, si hay cierra la  comanda
							comandas.comanda_padre({
								idComanda : $objeto['idpadre'],
								status_padre : 2,
								id_mesa: $objeto['mesa'],
								tipo_mesa: $objeto['tipo_mesa']
							});
						}
					});
				
			}).fail(function(resp) {
				console.log('---------> Fail guardar_parcial');
				console.log(resp);

				// Quita el loader
				$btn.button('reset');
				if(comandas.idioma == 1)
					$mensaje = 'Error al cerrar las comandas';
				else 
					$mensaje = 'Failed to close commands';
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

///////////////// ******** ---- 		FIN guardar_comanda_parcial		------ ************ //////////////////

///////////////// ******** ---- 		comanda_padre		------ ************ //////////////////
//////// Consulta si la comanda padre tiene pedidos sin pagar
	// Si tiene pedidos, refresca los datos
	// si no, cierra la comanda
	// Como parametros recibe:
		// idcomanda -> ID de la comanda padre
		// status -> estatus de los pedidos
		// id_mesa ID de la mesa

	comanda_padre : function($objeto) {
		console.log('------------> $objeto comanda_padre');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=comanda_padre',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log('------------> response comanda_padre');
				console.log(resp);

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al consultar la comanda padre';
					else
						var $mensaje = 'Error: \n Failed to query the parent command';
					$('#btn_personalizado_ok').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Error: Al actualizar la comanda padre, Manda un mensaje con el error
				if (resp['status'] == 3) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error: \n Error al consultar la comanda padre';
					else
						var $mensaje = 'Error: \n Failed to query the parent command';
					$('#btn_personalizado_ok').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Tiene pedidos
				if (resp['status'] == 1) {
					$objet = Array();

				// Actualiza el input de borrar
					var personas = $('#borrar_persona_personalizado').val();
					nuevo = parseInt(personas) - 1;
					$('#borrar_persona_personalizado').val(nuevo);

				// Cambia 0 para que tenga que seleccionar una persona
					$('#persona').val(0);

				// Manda llamar a la funcion que lista las personas
					$objet['div'] = 'div_personas_personalizado';
					comandas.listar_personas($objet);

				// Manda llamar a la funcion que lista los agregados de las personas
					$objeto['div'] = 'div_agregados_personalizado';
					$objeto['persona'] = 0;
					comandas.listar_agregados($objeto);

					return 0;
				}

			// No tiene pedidos :D, direcciona al mapa de mesas
				if (resp['status'] == 2) {
					if ($objeto['tipo_operacion'] == 3) {
						console.log('======> Entra tipo operacion 3');
						
					// Oculta la ventana modal
						$("#div_personalizar").click();
					
					// Crea una neuva comanda
						comandera.mandar_mesa_comandera({
							id_mesa : $objeto['id_mesa'],
							tipo : 0,
							id_comanda : '',
							tipo_operacion: comandera.ajustes['tipo_operacion']
						});
					} else {
					// Quita la comanda de la mesa
						$("#mesa_" + $objeto['id_mesa']).attr('id_comanda', '');
						console.log("objeto");
						console.log($objeto);
						if($objeto['tipo_mesa'] == 1)
							$('#img_' + $objeto['id_mesa']).attr("src", "images/mapademesas/libre_cuadrada_2p.png");
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
						$("#div_tiempo_" + $objeto['id_mesa']).html('');
						$("#div_total_" + $objeto['id_mesa']).html('');
				
					// Oculta las ventanas modal
						$("#div_personalizar").click();
						setTimeout(function() {
							$("#modal_comandera").click();
						}, 500);
					}
				}
			}
		});
	},

///////////////// ******** ---- 		FIN comanda_padre		------ ************ //////////////////

///////////////// ******** ---- 		guardar_asignacion		------ ************ //////////////////
	//////// Guarda los permisos de los empleados
		// Como parametros recibe:
			// empleado -> ID del empleado
			// Vista -> 1: Vista empleados, 2: Vista asignacion

	guardar_asignacion : function($objeto) {
		console.log('------------> objeto guardar_asignacion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton Guardar
		var $btn = $('#btn_guardar');
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_asignacion',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log('-------------> response guardar_asignacion');
				console.log(resp);

			// Quita el loader
				$btn.button('reset');

			// Elimina el pass del campo
				$('#pass_empleado').val('');

			// Error: Manda un mensaje con el error
				if (resp['status'] == 0) {
					if(comandas.idioma == 1)
						var $mensaje = 'Error al guardar las asignaciones';
					else
						var $mensaje = 'Failed to save assignments';
					$('#btn_guardar').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Se agregaron las mesas al mesero
				if (resp['status'] == 1) {
				// Cierra la ventana de las mesas
					$('#btn_cerrar_mesas').click();

				// Consulta los empleados y los agrega a la div
					comandas.listar_empleados({
						div : 'div_empleados'
					});

				// Bloquea las mesas asignadas para que no las pueda seleccionar el usuario
					if ($objeto['vista'] == 1) {
						comandas.bloquear_mesas($objeto);
					}
				}
			}
		});
	},

//////////////// ******** ---- 		FIN guardar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 	iniciar_sesion		------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar
		// empleado -> ID del empleado

	iniciar_sesion : function($objeto) {
		console.log('--------> objeto Iniciar sesion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
	// Valida si se debe de pedir el pass o no
		if($objeto['pedir_pass'] != 2){
			if (!$objeto['pass']) {
				if(comandas.idioma == 1)
					var $mensaje = 'Introduce el pass';
				else
					var $mensaje = 'Enter password';
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
			success : function(resp) {
				console.log('--------> RESPONSE Iniciar sesion');
				console.log(resp);
			
			// Limpia el campo de pass
				$('#pass_empleado').val('');

			// Error :(
				if (resp['status'] == 0) {
					if(comandas.idioma == 1)
						var $mensaje = 'Contraseña incorrecta';
					else
						var $mensaje = 'Incorrect password';
					$('#pass_empleado').notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

			// Cierra la ventana modal y filtra por los permisos del empleado
				if (resp['status'] == 1) {
				// Cierra la ventana de pass
					$('#btn_cerrar_pass').click();
					
				// Abre la ventana de mesas
					$("#modal_mesas").modal();

				// Lista las asignaciones del empleado
					comandas.listar_asignacion({
						id : $objeto['empleado']
					});
					
					return 0;
				}

			// Cierra la ventana modal y trae todas las mesas
				if (resp['status'] == 2) {
				// Cierra la ventana de pass
					$('#btn_cerrar_pass').click();

				// Abre la ventana de mesas
					$("#modal_mesas").modal();

				// Lista las asignaciones del empleado
					comandas.listar_asignacion({
						id : $objeto['empleado']
					});
					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN	iniciar_sesion		------ ************ //////////////////

///////////////// ******** ---- 		listar_empleados		------ ************ //////////////////
	//////// Obtiene los empleados con sus permisos y asiganaciones y los carga en una div
		// Como parametros recibe:

	listar_empleados : function($objeto) {
		console.log('------------> $objeto listar_empleados');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_empleados',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> response listar_empleados');
			console.log(resp);
			
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_empleados');
			console.log(resp);
		
		// Quita el loader
			$('#' + $objeto['div']).html('Error al buscar los empleados');
			if(comandas.idioma == 1)
				$mensaje = 'Error al buscar los empleados';
			else
				$mensaje = 'Error fetching employees';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_empleados		------ ************ //////////////////

///////////////// ******** ---- 		autorizar_asignacion		------ ************ //////////////////
	//////// Autoriza la asignacion de mesas
	// Como parametros puede recibir:
		//pass -> contraseña del admin

	autorizar_asignacion : function($objeto) {
		console.log('--------> objeto autorizar_asignacion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['pass']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Introduce el pass';
			else
				var $mensaje = 'Enter password';
			$('#pass').notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}

	// Loader en el boton Autorizar
		var $btn = $('#btn_autorizar');
		$btn.button('loading');

	// Consulta el pass si es el correcto asigna las mesas
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log(resp);

			// Quita el loader
				$btn.button('reset');

				if (resp != $objeto['pass']) {
					if(comandas.idioma == 1)
						var $mensaje = 'Contraseña incorrecta';
					else
						var $mensaje = 'Incorrect password';
					$('#pass').notify($mensaje, {
						position : "left",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

			// Manda guardar
				if (resp == $objeto['pass']) {
				// Loading en el boton
					$btn.button('loading');
					
					$.ajax({
						data : $objeto,
						url : 'ajax.php?c=comandas&f=autorizar_asignacion',
						type : 'GET',
						dataType : 'json',
						success : function(resp) {
							console.log('--------> RESPONSE autorizar_asignacion');
							console.log(resp);

						// Quita el loader
							$btn.button('reset');

						// Error :(
							if (resp['status'] == 0) {
								if(comandas.idioma == 1)
									var $mensaje = 'Error al guardar las asignaciones';
								else
									var $mensaje = 'Failed to save assignments';
								$('#pass').notify($mensaje, {
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

								if(comandas.idioma == 1)
									var $mensaje = 'Mesas asignadas';
								else
									var $mensaje = 'Assigned tables';
								$('#pass').notify($mensaje, {
									position : "left",
									autoHide : true,
									autoHideDelay : 5000,
									className : 'success',
								});

							// Consulta los empleados y los agrega a la div
								comandas.listar_empleados({
									div : 'div_empleados'
								});

								return 0;
							}
						}//sucess ajax autorizar
					});
					//ajax autorizar
				}//if todo bien :D
			}//sucess ajax pass
		});
		//ajax pass
	}, //funcion

///////////////// ******** ---- 	FIN	autorizar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 		FIN asignar		------ ************ //////////////////

///////////////// ******** ---- 		bloquear_mesas		------ ************ //////////////////
	//////// Bloquea las mesas asignadas para que no las pueda seleccionar el usuario
		// Como parametros recibe:

	bloquear_mesas : function($objeto) {
		console.log('------------> $objeto bloquear_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=bloquear_mesas',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log('------------> response bloquear_mesas');
				console.log(resp);

			// Error: Manda un mensaje con el error
				if (resp['status'] == 0) {
					console.log('vacio');
					if(comandas.idioma == 1)
						var $mensaje = 'Error al bloquear las mesas';
					else
						var $mensaje = 'Error blocking tables';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});

					return 0;
				}

			// Empleado CON permisos
				if (resp['status'] == 1) {
					// Desmarca las mesas
					$.each(resp['result']['rows'], function(index, val) {
						$('#btn_' + val['id_mesa']).prop('disabled', true);
					});

					return 0;
				}
			}
		});
	},

///////////////// ******** ---- 		FIN bloquear_mesas		------ ************ //////////////////

///////////////// ******** ---- 	reiniciar_asignacion		------ ************ //////////////////
	//////// Reinicia las asignaciones de los meseros
		// Como parametros puede recibir:
			//	pass -> contraseña del admin

	reiniciar_asignacion : function($objeto) {
		console.log('--------> objeto reiniciar_asignacion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['pass']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Introduce el pass';
			else
				var $mensaje = 'Enter password';
			$('#pass').notify($mensaje, {
				position : "left",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}

	// Loader en el boton Autorizar
		var $btn = $('#btn_reiniciar');
		$btn.button('loading');

	// Consulta el pass si es el correcto asigna las mesas
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=configuracion&f=pass',
			type : 'GET',
			dataType : 'json',
			success : function(resp) {
				console.log(resp);

			// Quita el loader
				$btn.button('reset');
				
				if (resp != $objeto['pass']) {
					if(comandas.idioma == 1)
						var $mensaje = 'Contraseña incorrecta';
					else
						var $mensaje = 'Incorrect password';
					$('#pass').notify($mensaje, {
						position : "left",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});

					return 0;
				}

				if (resp == $objeto['pass']) {
					if(comandas.idioma == 1)
						var text = "¿Estas seguro de reiniciar las asignaciones?";
					else
						var text = "Are you sure to restart the assignments?";
					if (confirm(text)) {
						$.ajax({
							data : $objeto,
							url : 'ajax.php?c=comandas&f=reiniciar_asignacion',
							type : 'GET',
							dataType : 'json',
							success : function(resp) {
								console.log('--------> RESPONSE reiniciar_asignacion');
								console.log(resp);

							// Quita el loader
								$btn.button('reset');

							// Error :(
								if (resp['status'] == 0) {
									if(comandas.idioma == 1)
										var $mensaje = 'Error al reiniciar las asignaciones';
									else
										var $mensaje = 'Failed to restart mappings';
									$('#pass').notify($mensaje, {
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

									if(comandas.idioma == 1)
										var $mensaje = 'Asignaciones reiniciadas';
									else
										var $mensaje = 'Assignments restarted';
									$('#pass').notify($mensaje, {
										position : "left",
										autoHide : true,
										autoHideDelay : 5000,
										className : 'success',
									});

								// Consulta los empleados y los agrega a la div
									comandas.listar_empleados({
										div : 'div_empleados'
									});

									return 0;
								}
							}//sucess ajax autorizar
						});
						//ajax autorizar
					} else {//if Confirmar
						// Regresa el boton a su estado normal
						$btn.button('reset');
					}
				}//if pass correcto :D
			}//sucess ajax pass
		});
		//ajax pass
	}, //funcion

///////////////// ******** ---- 	FIN	autorizar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 		listar_actividades		------ ************ //////////////////
	//////// Obtien las actividades de los empleados y las carga en la div
		// Como parametros recibe:
			// empleado -> id del empleado
			// f_ini -> fecha y hora inicial
			// f_fin -> Fecha y hora final
			// actividad -> Actividad seleccionada

	listar_actividades : function($objeto) {
		console.log('------------> $objeto listar_actividades');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
		
	// Loader en el boton buscar
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_actividades',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> response listar_actividades');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
		// Error: Manda un mensaje con el error
			if (!resp) {
				if(comandas.idioma == 1)
					alert('Error: \n Error al obtener las actividades');
				else
					alert('Error: \n Failed to get the activities');
	
				return 0;
			}

		// Carga el contenido a la div
			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable
			comandas.convertir_dataTable({id:'tabla_actividades'});
		
		}).fail(function(resp) {
			console.log('---------> Fail actualizar');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				$mensaje = 'Error al buscar reservaciones';
			else
				$mensaje = 'Error searching reservations';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_actividades		------ ************ //////////////////

///////////////// ******** ---- 		listar_comensalesXmesa		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// sucursal -> ID de la sucursal

	listar_comensalesXmesa : function($objeto) {
		console.log('------------> $objeto listar_comensalesXmesa');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
			 	
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
	    $btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_comensalesXmesa',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_comensalesXmesa');
				console.log(resp);

	    		$btn.button('reset');
	    		
			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						alert('Error: \n Error al obtener las actividades');
					else
						alert('Error: \n Failed to get the activities');
					
					return 0;
				}
				
				$('#' + $objeto['div']).html(resp);
				
			// Crea el datatable
				comandas.convertir_dataTable({id:'tabla_comensalesXmesa'});
			}
		});
	},

///////////////// ******** ---- 		FIN listar_comensalesXmesa		------ ************ //////////////////

///////////////// ******** ---- 		listar_consumo		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// sucursal -> ID de la sucursal

	listar_consumo : function($objeto) {
		console.log('------------> $objeto listar_consumo');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
			 	
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
	    $btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_consumo',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_consumo');
				console.log(resp);

	    		$btn.button('reset');
	    		
			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						alert('Error: \n Error al obtener las actividades');
					else
						alert('Error: \n Failed to get the activities');
					
					return 0;
				}
				
				$('#' + $objeto['div']).html(resp);
				
			// Crea el datatable
				comandas.convertir_dataTable({id:'tabla_consumo'});
			}
		});
	},

///////////////// ******** ---- 		FIN listar_consumo		------ ************ //////////////////

///////////////// ******** ---- 		listar_zonas		------ ************ //////////////////
	//////// Obtien los registros de los comensales y los carga en la div
		// Como parametros recibe:
			// empleado -> id del empleado
			// f_ini -> fecha y hora inicial
			// f_fin -> Fecha y hora final
			// mesa -> ID de la mesa
			// comandas -> numero total de comandas
			// zona -> zona

	listar_zonas : function($objeto) {
		console.log('------------> $objeto listar_zonas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton OK
		var $btn = $('#btn_zona_buscar');
		$btn.button('loading');

	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

		// Quita el loader del boton
			$btn.button('reset');

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';

			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

		// Quita el loader del boton
			$btn.button('reset');

			return 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_zonas',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_zonas');
				console.log(resp);

			// Quita el loader del boton
				$btn.button('reset');

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						alert('Error: \n Error al obtener las actividades');
					else
						alert('Error: \n Failed to get the activities');

					return 0;
				}

			// Carga la vista al div
				$('#' + $objeto['div']).html(resp);
				
			// Crea el datatable
				comandas.convertir_dataTable({id:'tabla_zonas'});
			}
		});
	},

///////////////// ******** ---- 		FIN listar_zonas		------ ************ //////////////////

///////////////// ******** ---- 		listar_ocupacion		------ ************ //////////////////
	//////// Obtien los registros de las ocupaciones y los carga en la div
		// Como parametros recibe:
			// empleado -> id del empleado
			// f_ini -> fecha y hora inicial
			// f_fin -> Fecha y hora final
			// mesa -> ID de la mesa
			// comandas -> numero total de comandas
			// zona -> zona

	listar_ocupacion : function($objeto) {
		console.log('------------> $objeto listar_ocupacion');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton OK
		var $btn = $('#btn_ocupacion_buscar');
		$btn.button('loading');

	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

		// Quita el loader del boton
			$btn.button('reset');

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

		// Quita el loader del boton
			$btn.button('reset');

			return 0;
		}

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_ocupacion',
			type : 'GET',
			dataType : 'html',
			success : function(resp) {
				console.log('------------> response listar_ocupacion');
				console.log(resp);

			// Quita el loader del boton
				$btn.button('reset');

			// Error: Manda un mensaje con el error
				if (!resp) {
					if(comandas.idioma == 1)
						alert('Error: \n Error al obtener las actividades');
					else
						alert('Error: \n Failed to get the activities');
					
					return 0;
				}
				
				$('#' + $objeto['div']).html(resp);
				
			// Crea el datatable
				comandas.convertir_dataTable({id:'tabla_ocupaciones'});
			}
		});
	},

///////////////// ******** ---- 		FIN listar_ocupacion		------ ************ //////////////////

///////////////// ******** ---- 			graficar				------ ************ //////////////////
	//////// Genera una grafica y la carga en la div
		// Como parametros recibe:
			// div -> Div donde se cargara la grafica
			// datos -> Array con los datos a graficar
			// tipo -> tipo de grafica(dona, barras, linea, linea y area)

	graficar : function($objeto) {
		console.log('------------> $objeto graficar');
		console.log($objeto);
	// Grafica de dona
		if ($objeto['dona']) {
			Morris.Donut({
				element : $objeto['div'] + '_dona',
				data : $objeto['dona'],
  				formatter:function (y, data) {
  				// Formatea el texte del centro de la dona si existe
  					if($objeto['caracter']){
						return $objeto['caracter'] + y;
				// Sin formato
  					}else{
  						return y;
  					}
				}
			});
		}
		
	// Grafica de barras
		if ($objeto['barras']) {
			Morris.Bar({
				element : $objeto['div'] + '_barras',
				data : $objeto['barras'],
				xkey : $objeto['x'],
				ykeys : [$objeto['y']],
				labels : [$objeto['label']]
			});
		}

	// Grafica lineal
		if ($objeto['lineal']) {
			Morris.Line({
				element : $objeto['div'] + '_lineal',
				data : $objeto['lineal'],
				xkey : $objeto['x'],
				ykeys : [$objeto['y']],
				labels : [$objeto['label']],
				xLabels: $objeto['xlabel']
			});
		}

	// Grafica area
		if ($objeto['area']) {
			Morris.Area({
				element : $objeto['div'] + '_lineal',
				data : $objeto['area'],
				xkey : $objeto['x'],
				ykeys : [$objeto['y']],
				labels : [$objeto['label']]
			});
		}
	},

///////////////// ******** ---- 		FIN graficar		------ ************ //////////////////

///////////////// ******** ---- 	convertir_dataTable		------ ************ //////////////////
//////// Conviertela tabla en dataTable
	// Como parametros recibe:
		// id -> ID de la tabla a convertir

	convertir_dataTable : function($objeto) {
		console.log('objeto convertir dataTable');
		console.log($objeto);
		
		var $orden = ($objeto['orden']) ? $objeto['orden'] : 'asc';
		if(comandas.idioma == 1){
			var  page = 'por pagina';
			var exist = 'No hay datos.';
			var empty = 'No hay datos que mostrar.';
			var enc = 'resultados encontrados';
			var first = 'Primero';
			var last = 'Ultimo';
		} else {
			var page = 'per page';
			var exist = 'There is no data.';
			var empty = 'No data to display.';
			var enc = 'results found';
			var first = 'First';
			var last = 'Latest';
		}
	// Validacion para evitar error al crear el dataTable
		if (!$.fn.dataTable.isDataTable('#' + $objeto['id'])) {
			$('#' + $objeto['id']).DataTable({
				dom : 'Bfrtip',
				buttons : ['excel'],
				language : {
					buttons : {
						pageLength : "%d "+page
					},					
					search : "Buscar",
					lengthMenu : "",
					zeroRecords : exist,
					infoEmpty : empty,
					info : " ",
					infoFiltered : " -> <strong> _TOTAL_ </strong> "+enc,
					paginate : {
						first : 'Primero',
						previous : "Anterior",
						next : "Siguiente",
						last : 'Ultimo'
					}
				},
				order: [[0, $orden]]
			});
		}
	},

///////////////// ******** ---- 	FIN convertir_dataTable		------ ************ //////////////////
 
///////////////// ******** ----			codigo_barras			------ ************ //////////////////
//////// Crea una imagen con un codigo de barras
	// Como parametros recibe:
		// id -> ID de la imagen
		// codigo -> codigo que se debe de mostrar

	codigo_barras : function($objeto) {
		console.log('objeto codigo_barras');
		console.log($objeto);

		JsBarcode("#"+$objeto['id'], $objeto['codigo']);
	},


///////////////// ******** ----			FIN codigo_barras		-----************ //////////////////

///////////////// ******** ---- 		listar_comandas_hijas		------ ************ //////////////////
//////// Obtien las sub comandas y las carga en una div
	// Como parametros recibe:
		// div -> div en donde se cargara el contenido
		// id_padre -> ID de la comanda padre

	listar_comandas_hijas : function($objeto) {
		console.log('------------> $objeto listar_sub_comandas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton OK
		var $btn = $('#btn_'+$objeto['id']);
		$btn.button('loading');
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_comandas_hijas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_comandas_hijas');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			$('#' + $objeto['div']).html(resp);
			$('#div_sub_comandas_2').html(resp);
			
		// Crea el datatable
			comandas.convertir_dataTable({id:'tabla_sub_comandas'});
				
		// Error: Manda un mensaje con el error
			if (!resp) {
				if(comandas.idioma == 1)
					var $mensaje = 'Error al obtener las sub comandas';
				else
					var $mensaje = 'Error getting sub commands';
				$('#' + $objeto['div']).notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail listar_comandas_hijas');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener las sub comandas';
			else
				var $mensaje = 'Error getting sub commands';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_comandas_hijas		------ ************ //////////////////

///////////////// ******** ---- 			imprime_sub_comanda			------ ************ //////////////////
//////// Imprime el ticket de la sub comanda
	// Como parametros recibe:
		// id -> ID de la sub comanda
		// pedidos -> Cadena con los ID's de los pedidos
		// vista_estatus_comanda -> Bnadera(1 -> indica que solo se debe de imprimir el ticket y no afectar la DB)

	imprime_sub_comanda : function($objeto) {
		console.log('------------> $objeto imprime_sub_comanda');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();

		//if($objeto.tipo == 2) para comanda dividida por porcentje o cantidad

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_comanda_parcial',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done imprime_sub_comanda');
			console.log(resp);
			
		// Imprimimos el TIcket en una nueva ventana
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
			$(ventana).ready(function() {
			//cargamos el HTML del objeto en la nueva ventana
				ventana.document.write(resp);
				ventana.document.close();
				
				setTimeout(closew,100);
				function closew(){
					ventana.print();  //imprimimos la ventana
				}
			});
				
		}).fail(function(resp) {
			console.log('---------> Fail imprime_sub_comanda');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al imprimir el ticket';
			else
				var $mensaje = 'Error printing ticket';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN imprime_sub_comanda			------ ************ //////////////////

///////////////// ******** ---- 					modal_login				------ ************ //////////////////
//////// Abre la modal de login, llena los campos y hace un focus
	// Como parametros puede recibir:
		// id-> ID del usuario
		// nombre-> nombre del usuario

	modal_login : function($objeto) {
		console.log('--------> objeto modal_login');
		console.log($objeto);
	
	// llena los campos
		setTimeout(function() {
			$('#empleado').val($objeto['empleado']);
			$('#id_empleado').val($objeto['id']);
			$('#pass_empleado').focus();
		}, 500);
	},
	
///////////////// ******** ---- 				FIN modal_login				------ ************ //////////////////

///////////////// ******** ---- 				listar_utilidades			------ ************ //////////////////
//////// Obtien los registros de las utilidades y los carga en la div
	// Como parametros puede recibir:
		// btn -> boton del loading
		// div -> Div donde se cargara el contenido
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// grafica -> bandera que indica el filtrado de la grafica(1-> dia, 2-> semanda, 3->mes, 4-> año)		
		// producto -> ID del producto o *-> todos los productos
		
	listar_utilidades : function($objeto) {
		console.log('------------> $objeto listar_utilidades');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#f_ini').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#f_fin').notify($mensaje, {
				position : "bottom center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
			 	
	// Loader en el boton buscar
		var $btn = $('#'+$objeto['btn']);
	    $btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_utilidades',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_utilidades');
			console.log(resp);

	    	$btn.button('reset');
	    		
			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable
			comandas.convertir_dataTable({id:'tabla_utilidades'});
		}).fail(function(resp) {
			console.log('---------> Fail listar_utilidades');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener las utilidades';
			else 
				var $mensaje = 'Failed to get the utilities';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_utilidades				------ ************ //////////////////

///////////////// ******** ---- 				listar_productos_detalle			------ ************ //////////////////		
	listar_productos_detalle : function($objeto) {
		console.log('------------> $objeto listar_productos_detalle');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
			 	
	// Loader en el boton buscar
		var $btn = $('#'+$objeto['btn']);
	    $btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_productos_detalle',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_productos_detalle');
			console.log(resp);

	    	$btn.button('reset');
	    		
			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable
			comandas.convertir_dataTable({id:'tabla_utilidades'});
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_detalle');
			console.log(resp);

			// Quita el loader
			$btn.button('reset');

			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener las utilidades';
			else
				var $mensaje = 'Failed to get the utilities';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 		FIN listar_productos_detalle				------ ************ //////////////////

///////////////// ******** ---- 			eliminar_cliente				------ ************ //////////////////
//////// Elimina un cliente nuevo en la BD
	// Como parametros recibe:
		// id -> ID del cliente
		// btn -> Boton
		// tr -> TR de la tabla

	eliminar_cliente : function($objeto) {
		console.log('------------> $objeto eliminar_cliente');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=eliminar_cliente',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('------------> response eliminar_cliente');
			console.log(resp);
		
		// Todo bien
			if (resp['status'] == 1) {
				$('#' + $objeto['tr']).removeClass().addClass("danger");
				$btn.hide();

				if(comandas.idioma == 1)
					var $mensaje = 'Eliminado con exito';
				else
					var $mensaje = 'Successfully deleted';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail eliminar_cliente');
			console.log(resp);
			
			if(comandas.idioma == 1)
				$mensaje = 'Error al eliminar el cliente';
			else
				$mensaje = 'Failed to delete client';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN eliminar_cliente				------ ************ //////////////////

///////////////// ******** ---- 				agregar_cliente					------ ************ //////////////////
//////// Agrega un cliente nuevo en la BD
	// Como parametros recibe:
		// nombre -> Nombre del cliente
		// direccion -> Direccion
		// tel -> Telefono

	agregar_cliente : function($objeto) {
		console.log('------------> $objeto agregar_cliente');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_cliente',
			type : 'GET',
			dataType : 'json',
			async: false
		}).done(function(resp) {
			console.log('------------> response agregar_cliente');
			console.log(resp);
		
		// Todo bien
			if(resp){
				console.log('--------> var cliente_nuevo');
				console.log(resp);
				var cliente_nuevo = resp;
				if(cliente_nuevo == 0){
					$btn.button('reset');
					if(comandas.idioma == 1)
						$mensaje = 'Error al guardar cliente';
					else
						$mensaje = 'Failed to save client';
					$.notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
							arrowSize : 15
						}
					);
					return 0;
				} else {
					var tabla = $('#tabla_servicio_domicilio').DataTable();
					tabla.destroy();
					$objeto['id'] = cliente_nuevo;
					if($objeto['nombre'] == undefined)
						$objeto['nombre'] = '';
					if($objeto['direccion'] == undefined)
						$objeto['direccion'] = '';
					if($objeto['cel'] == undefined)
						$objeto['cel'] = '';
					$objeto['para_llevar'] = '';
					$objeto['servicio_domicilio'] = '1';
					var json = JSON.stringify($objeto);
					json = json.replace(/"/g, "'");
					console.log('<tr id="tr_servicio_domicilio_'+cliente_nuevo+'"><td id="nom_'+cliente_nuevo+'">'+$objeto['nombre']+'</td><td id="dir_'+cliente_nuevo+'">'+$objeto['direccion']+'</td><td id="tel_'+cliente_nuevo+'" align="center">'+$objeto['cel']+'</td><td id="zon_'+cliente_nuevo+'">'+$("#zona_reparto_domicilio option:selected").text()+'</td><td id="via_'+cliente_nuevo+'">'+$("#via_contacto_domicilio option:selected").text()+'</td><td align="center"> <button  id="btn_edit_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json+')" data-toggle="modal"  data-target="#modal_editar_cliente_domicilio" class="btn btn-primary"> <i class="fa fa-pencil-square-o"></i> </button> </td><td align="center"> <button id="btn_servicio_domicilio_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="servicio_domicilio({ nombre:'+"'"+$objeto['nombre']+"'"+', btn:'+"'"+'btn_servicio_domicilio_'+cliente_nuevo+"'"+', domicilio:'+"'"+$objeto['direccion']+"'"+', via_contacto:'+"'"+$objeto['via_contacto']+"'"+', zona_reparto:'+"'"+$objeto['zona_reparto']+"'"+', tel:'+"'"+$objeto['cel']+"'"+', tipo_operacion: 1})" class="btn btn-success"><i class="fa fa-check"></i></button></td></tr>');
					var via_c = $("#editar_via_contacto_domicilio option[value='"+$objeto['via_contacto']+"']").text();

					$("#tabla_servicio_domicilio").prepend('<tr id="tr_servicio_domicilio_'+cliente_nuevo+'"><td id="nom_'+cliente_nuevo+'">'+$objeto['nombre']+'</td><td id="dir_'+cliente_nuevo+'">'+$objeto['direccion']+'</td><td id="tel_'+cliente_nuevo+'" align="center">'+$objeto['cel']+'</td><td id="zon_'+cliente_nuevo+'">'+$("#zona_reparto_domicilio option:selected").text()+'</td><td id="via_'+cliente_nuevo+'">'+via_c+'</td><td align="center"> <button  id="btn_edit_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json+')" data-toggle="modal"  data-target="#modal_editar_cliente_domicilio" class="btn btn-primary"> <i class="fa fa-pencil-square-o"></i> </button> </td><td align="center"> <button id="btn_servicio_domicilio_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="servicio_domicilio({ nombre:'+"'"+$objeto['nombre']+"'"+', btn:'+"'"+'btn_servicio_domicilio_'+cliente_nuevo+"'"+', domicilio:'+"'"+$objeto['direccion']+"'"+', via_contacto:'+"'"+$objeto['via_contacto']+"'"+', zona_reparto:'+"'"+$objeto['zona_reparto']+"'"+', tel:'+"'"+$objeto['cel']+"'"+', tipo_operacion: 1})" class="btn btn-success"><i class="fa fa-check"></i></button></td></tr>');
					comandas.convertir_dataTable({id:'tabla_servicio_domicilio'});
					$("#cliente_servicio_domicilio").val('');
					$("#direccion_servicio_domicilio").val('');
					$("#exterior_servicio_domicilio").val('');
					$("#interior_servicio_domicilio").val('');
					$("#cp_servicio_domicilio").val('');
					$("#colonia_servicio_domicilio").val('');
					$("#cel_servicio_domicilio").val('');
					$("#tel_servicio_domicilio").val('');
					$("#email_servicio_domicilio").val('');
					$("#via_contacto_domicilio").val('');
					$("#zona_reparto_domicilio").val('');

					tabla = $('#tabla_para_llevar').DataTable();
					tabla.destroy();
					$objeto['para_llevar'] = '1';
					$objeto['servicio_domicilio'] = '';
					var json2 = JSON.stringify($objeto);
					json2 = json2.replace(/"/g, "'");
					$("#tabla_para_llevar").prepend('<tr id="tr_para_llevar_'+cliente_nuevo+'"> <td>'+$objeto['nombre']+'</td><td align="center">'+$objeto['cel']+'</td>  <td id="via_'+cliente_nuevo+'">'+via_c+'</td> <td align="center"> <button  id="btn_edit_'+cliente_nuevo+'"  data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json2+')" data-toggle="modal"  data-target="#modal_editar_para_llevar" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></button></td><td align="center"><button id="btn_para_llevar_'+cliente_nuevo+'"  data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="para_llevar({ btn: '+"'"+'btn_para_llevar_'+cliente_nuevo+', nombre: '+"'"+$objeto['nombre']+"'"+', via_contacto: '+"'"+$objeto['via_contacto']+"'"+', cel: '+"'"+$objeto['cel']+"'"+', tipo_operacion: 1 })" class="btn btn-success"><i class="fa fa-check"></i></button></td></tr>');
					comandas.convertir_dataTable({id:'tabla_para_llevar'});
					$("#cliente_para_llevar").val('');
					$("#tel_para_llevar").val('');
					$("#via_contacto").val('');

					$('.selectpicker').selectpicker('refresh');
				}
			} else {
				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail agregar_cliente');
			console.log(resp);
			if(comandas.idioma == 1)
				$mensaje = 'Error al agregar el cliente';
			else
				$mensaje = 'Failed to add client';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_cliente					------ ************ //////////////////

///////////////// ******** ---- 				editar_cliente					------ ************ //////////////////
//////// Agrega un cliente nuevo en la BD
	// Como parametros recibe:
		// nombre -> Nombre del cliente
		// direccion -> Direccion
		// tel -> Telefono

	editar_cliente : function($objeto) {
		console.log('------------> $objeto editar_cliente');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=editar_cliente',
			type : 'GET',
			dataType : 'json',
			async: false
		}).done(function(resp) {
			console.log('------------> response editar_cliente');
			console.log(resp);
		
		// Todo bien
			if(resp){
				console.log('--------> var cliente_nuevo');
				console.log(resp);
				var cliente_nuevo = resp;
				if(cliente_nuevo == 0){
					$btn.button('reset');
					if(comandas.idioma == 1)
						$mensaje = 'Error al editar cliente';
					else
						$mensaje = 'Error editing client';
					$.notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
							arrowSize : 15
						}
					);
					return 0;
				} else {
					console.log('editar::::');
					console.log($objeto);
					var tabla = $('#tabla_servicio_domicilio').DataTable();
					tabla.destroy();
					if($objeto['nombre'] == undefined)
						$objeto['nombre'] = '';
					if($objeto['direccion'] == undefined)
						$objeto['direccion'] = '';
					if($objeto['cel'] == undefined)
						$objeto['cel'] = '';
					$objeto['id'] = cliente_nuevo;
					$objeto['para_llevar'] = '';
					$objeto['servicio_domicilio'] = '1';
					var json = JSON.stringify($objeto);
					json = json.replace(/"/g, "'");
					var via_c = $("#editar_via_contacto_domicilio option[value='"+$objeto['via_contacto']+"']").text();

					//console.log('<tr id="tr_servicio_domicilio_'+cliente_nuevo+'"><td id="nom_'+cliente_nuevo+'">'+$objeto['nombre']+'</td><td id="dir_'+cliente_nuevo+'">'+$objeto['direccion']+'</td><td id="tel_'+cliente_nuevo+'" align="center">'+$objeto['cel']+'</td><td id="zon_'+cliente_nuevo+'">'+$("#editar_zona_reparto_domicilio option:selected").text()+'</td><td id="via_'+cliente_nuevo+'">'+$("#editar_via_contacto_domicilio option:selected").text()+'</td><td align="center"> <button  id="btn_edit_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json+')" data-toggle="modal"  data-target="#modal_editar_cliente_domicilio" class="btn btn-primary"> <i class="fa fa-pencil-square-o"></i> </button> </td><td align="center"> <button id="btn_servicio_domicilio_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="servicio_domicilio({ nombre:'+"'"+$objeto['nombre']+"'"+', btn:'+"'"+'btn_servicio_domicilio_'+cliente_nuevo+"'"+', domicilio:'+"'"+$objeto['direccion']+"'"+', via_contacto:'+"'"+$objeto['via_contacto']+"'"+', zona_reparto:'+"'"+$objeto['zona_reparto']+"'"+', tel:'+"'"+$objeto['cel']+"'"+', tipo_operacion: 1})" class="btn btn-success"><i class="fa fa-check"></i></button></td></tr>');
					//$("#tabla_servicio_domicilio").prepend('<tr id="tr_servicio_domicilio_'+cliente_nuevo+'"><td id="nom_'+cliente_nuevo+'">'+$objeto['nombre']+'</td><td id="dir_'+cliente_nuevo+'">'+$objeto['direccion']+'</td><td id="tel_'+cliente_nuevo+'" align="center">'+$objeto['cel']+'</td><td id="zon_'+cliente_nuevo+'">'+$("#zona_reparto_domicilio option:selected").text()+'</td><td id="via_'+cliente_nuevo+'">'+$("#via_contacto_domicilio option:selected").text()+'</td><td align="center"> <button  id="btn_edit_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json+')" data-toggle="modal"  data-target="#modal_editar_cliente_domicilio" class="btn btn-primary"> <i class="fa fa-pencil-square-o"></i> </button> </td><td align="center"> <button id="btn_servicio_domicilio_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="servicio_domicilio({ nombre:'+"'"+$objeto['nombre']+"'"+', btn:'+"'"+'btn_servicio_domicilio_'+cliente_nuevo+"'"+', domicilio:'+"'"+$objeto['direccion']+"'"+', via_contacto:'+"'"+$objeto['via_contacto']+"'"+', zona_reparto:'+"'"+$objeto['zona_reparto']+"'"+', tel:'+"'"+$objeto['cel']+"'"+', tipo_operacion: 1})" class="btn btn-success"><i class="fa fa-check"></i></button></td></tr>');
					$("#tr_servicio_domicilio_"+cliente_nuevo).html('<td id="nom_'+cliente_nuevo+'">'+$objeto['nombre']+'</td><td id="dir_'+cliente_nuevo+'">'+$objeto['direccion']+'</td><td id="tel_'+cliente_nuevo+'" align="center">'+$objeto['cel']+'</td><td id="zon_'+cliente_nuevo+'">'+$("#editar_zona_reparto_domicilio option:selected").text()+'</td><td id="via_'+cliente_nuevo+'">'+via_c+'</td><td align="center"> <button  id="btn_edit_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json+')" data-toggle="modal"  data-target="#modal_editar_cliente_domicilio" class="btn btn-primary"> <i class="fa fa-pencil-square-o"></i> </button> </td><td align="center"> <button id="btn_servicio_domicilio_'+cliente_nuevo+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="servicio_domicilio({ nombre:'+"'"+$objeto['nombre']+"'"+', btn:'+"'"+'btn_servicio_domicilio_'+cliente_nuevo+"'"+', domicilio:'+"'"+$objeto['direccion']+"'"+', via_contacto:'+"'"+$objeto['via_contacto']+"'"+', zona_reparto:'+"'"+$objeto['zona_reparto']+"'"+', tel:'+"'"+$objeto['cel']+"'"+', tipo_operacion: 1})" class="btn btn-success"><i class="fa fa-check"></i></button></td>');
					comandas.convertir_dataTable({id:'tabla_servicio_domicilio'});
					
					tabla = $('#tabla_para_llevar').DataTable();
					tabla.destroy();
					$objeto['para_llevar'] = '1';
					$objeto['servicio_domicilio'] = '';
					var json2 = JSON.stringify($objeto);
					json2 = json2.replace(/"/g, "'");
					$("#tr_para_llevar_"+cliente_nuevo).html('<td>'+$objeto['nombre']+'</td><td align="center">'+$objeto['cel']+'</td>  <td id="via_'+cliente_nuevo+'">'+via_c+'</td> <td align="center"> <button  id="btn_edit_'+cliente_nuevo+'"  data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="comandera.llenar_campos('+json2+')" data-toggle="modal"  data-target="#modal_editar_para_llevar" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></button></td><td align="center"><button id="btn_para_llevar_'+cliente_nuevo+'"  data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>"  onclick="para_llevar({ btn: '+"'"+'btn_para_llevar_'+cliente_nuevo+', nombre: '+"'"+$objeto['nombre']+"'"+', via_contacto: '+"'"+$objeto['via_contacto']+"'"+', cel: '+"'"+$objeto['cel']+"'"+', tipo_operacion: 1 })" class="btn btn-success"><i class="fa fa-check"></i></button></td>');
					comandas.convertir_dataTable({id:'tabla_para_llevar'});

				}
			} else {
				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail editar_cliente');
			console.log(resp);
			if(comandas.idioma == 1)
				$mensaje = 'Error al editar el cliente';
			else
				$mensaje = 'Failed to edit client';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_cliente					------ ************ //////////////////

///////////////// ******** ---- 			convertir_calendario				------ ************ //////////////////
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

///////////////// ******** ---- 			litar_productos_mercado				------ ************ //////////////////
//////// Convierte el input en calendario
	// Como parametros recibe:
		// id -> ID del input

	litar_productos_mercado : function($objeto) {
		console.log('objeto litar_productos_mercado');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=litar_productos_mercado',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> response litar_productos_mercado');
			console.log(resp);
		
		// Todo bien
			if (resp['status'] == 1) {
				$('#' + $objeto['tr']).removeClass().addClass("danger");
				$btn.hide();

				if(comandas.idioma == 1)
					var $mensaje = 'Eliminado con exito';
				else
					var $mensaje = 'Successfully deleted';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
				});

				return 0;
			}
		}).fail(function(resp) {
			console.log('---------> Fail litar_productos_mercado');
			console.log(resp);
			if(comandas.idioma == 1)
				$mensaje = 'Error al obtener los datos';
			else
				$mensaje = 'Failed to get data';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN litar_productos_mercado			------ ************ //////////////////

///////////////// ******** ---- 					listar_kits					------ ************ //////////////////
//////// Carga la vista de los kits
	// Como parametros recibe:
		// div -> Div donde se cargaron los kits
		// tipo -> 6 -> kit
			
	listar_kits : function($objeto) {
		console.log('------------> $objeto listar_kits');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton buscar
		var $btn = $('#'+$objeto['btn']);
	    $btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_kits',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_kits');
			console.log(resp);

	    	$btn.button('reset');
	    		
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_kits');
			console.log(resp);

			// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los kits';
			else
				var $mensaje = 'Error getting kits';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN listar_kits					------ ************ //////////////////

///////////////// ******** ---- 			listar_productos_kit				------ ************ //////////////////
//////// Carga la vista de los productos del kit
	// Como parametros recibe:
		// div -> Div donde se cargaron los kits
		// id_kit -> ID del kit
			
	listar_productos_kit : function($objeto) {
		console.log('------------> $objeto listar_productos_kit');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_productos_kit',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done listar_productos_kit');
			console.log(resp);
	    		
			$('#' + $objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('---------> Fail listar_productos_kit');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los productos del kit';
			else
				var $mensaje = 'Error getting kit products';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN listar_productos_kit			------ ************ //////////////////

///////////////// ******** ---- 			detalles_producto				------ ************ //////////////////
//////// Carga la vista de los productos del kit
	// Como parametros recibe:
		// div -> Div donde se cargaron los kits
		// id_kit -> ID del kit
		// idProduct -> ID del producto 
	
	detalles_producto : function($objeto) {
		console.log('------------> Objeto detalles_producto');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=getItemsProduct',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('------------> done detalles_producto');
			console.log(resp);
			
			var idproduct = $objeto['idProduct'];
			var idcomanda = $objeto['id_comanda'];
			var person = $objeto['persona'];
			
		// Valida que el producto tenga materiales, si tiene arma la vista de los
		// opcionales, extra y los normales, si no carga el producto a la persona
			if (resp["total"] > 0) {
				$("#"+$objeto['div']).html('');

				var opcional = '';

				var $cabecera_sin = '';
				var $cabecera_extra = '';
				var $cabecera_opcional = '';

				var $sin = '';
				var $extra = '';
				var $opcional = '';

				var $sin_nota = '';
				var $extra_nota = '';
				var $opcional_nota = '';

			// Contenedor de productos opcionales y extra
				$div = '	<div>';
				$div += '		<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff;display:inline-block">';
				$div += '			<div align="center" onclick="comandas.editar_producto_kit({id_producto: '+idproduct+', persona: '+person+', id_comanda: '+idcomanda+', div: \''+$objeto['div']+'\'})">';
				$div += '				<button type="button" class="btn btn-success">';
				$div += '					<i class="fa fa-check"></i> Ok';
				$div += '				</button>';
				$div += '			</div>';
				$div += '		</a>';
				$div += '		<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff;display:inline-block">';
				$div += '			<div align="center" onclick="comandas.limpiar_div({div: \''+$objeto['div']+'\'})">';
				$div += '				<button type="button" class="btn btn-danger">';
				$div += '					<i class="fa fa-ban"></i> Cancelar';
				$div += '				</button>';
				$div += '			</div>';
				$div += '		</a>';
				$div += '		</div><br />';
				$div += '		<div class="row" id="div">';
				
				$.each(resp["rows"], function(index, value) {
					var $opcionales = value['opcionales'];
					console.log('---> opcionales: ' + $opcionales);


				// Producto Sin
					if ($opcionales.indexOf('1') != -1) {
					// Vacia la nota para qeu no aparesca Undefine
						if (!value.nota_sin) {
							value.nota_sin = '';
						}

						$cabecera_sin = '	<div class="col-md-4">';
						$cabecera_sin += '		<div class="panel panel-warning">';
						$cabecera_sin += '			<div class="panel-heading">';
						$cabecera_sin += '				Sin:';
						$cabecera_sin += '			</div>';
						$cabecera_sin += '			<div class="panel-body" id="sin_kit">';
						
						$cabecera_sin += '			</div>';
						$cabecera_sin += '		</div>';
						$cabecera_sin += '	</div>';

						$sin += '	<div class="row">';
						$sin += '		<div class="col-md-12" align="left">';
						$sin += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
						$sin += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
						$sin += '					<table>';
						$sin += '						<tr>';
						$sin += '							<td><input type="checkbox" class="itemCheck" value="' + value['idProducto'] + '" opcional="1"/></td>';
						$sin += '							<td><div style="font-size:11px;font-family:verdana">&nbsp' + value['nombre'] + '</div></td>';
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
						$sin_nota += '				<textarea id="nota_sin_kit" class="form-control" style="cursor: se-resize">' + value.nota_sin + '</textarea>';
						$sin_nota += '			</div>';
						$sin_nota += '		</div>';
						$sin_nota += '	</div>';
					}

				// Producto extra
					if ($opcionales.indexOf('2') != -1) {
					// Vacia la nota para qeu no aparesca Undefine
						if (!value.nota_extra) {
							value.nota_extra = '';
						}

						$cabecera_extra = '	<div class="col-md-4">';
						$cabecera_extra += '		<div class="panel panel-info">';
						$cabecera_extra += '			<div class="panel-heading">';
						$cabecera_extra += '				Extra:';
						$cabecera_extra += '			</div>';
						$cabecera_extra += '			<div class="panel-body" id="extra_kit">';
						$cabecera_extra += '			</div>';
						$cabecera_extra += '		</div>';
						$cabecera_extra += '	</div>';

						$extra += '	<div class="row">';
						$extra += '		<div class="col-md-12" align="left">';
						$extra += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
						$extra += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
						$extra += '					<table>';
						$extra += '						<tr>';
						$extra += '							<td><input type="checkbox" class="itemCheck" value="' + value['idProducto'] + '" opcional="2"/></td>';
						$extra += '							<td><div style="font-size:11px;font-family:verdana">&nbsp' + value['nombre'] + '</div></td>';
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
						$extra_nota += '				<textarea id="nota_extra_kit" class="form-control" style="cursor: se-resize">' + value.nota_extra + '</textarea>';
						$extra_nota += '			</div>';
						$extra_nota += '		</div>';
						$extra_nota += '	</div>';
					}

				// Opcionales
					if ($opcionales.indexOf('3') != -1) {
					// Vacia la nota para que no aparesca Undefine
						if (!value.nota_opcional) {
							value.nota_opcional = '';
						}

						$cabecera_opcional = '	<div class="col-md-4">';
						$cabecera_opcional += '		<div class="panel panel-success">';
						$cabecera_opcional += '			<div class="panel-heading">';
						$cabecera_opcional += '				Opcional:';
						$cabecera_opcional += '			</div>';
						$cabecera_opcional += '			<div class="panel-body" id="opcional_kit">';
						$cabecera_opcional += '			</div>';
						$cabecera_opcional += '		</div>';
						$cabecera_opcional += '	</div>';

						$opcional += '	<div class="row">';
						$opcional += '		<div class="col-md-12" align="left">';
						$opcional += '			<div style="background:#D8D8D8;" class="itemProductCheck">';
						$opcional += '				<a href="javascript:void(0)" style="color:#000000;text-decoration:none">';
						$opcional += '					<table>';
						$opcional += '						<tr>';
						$opcional += '							<td><input type="checkbox" class="itemCheck" value="' + value['idProducto'] + '" opcional="3"/></td>';
						$opcional += '							<td><div style="font-size:11px;font-family:verdana">&nbsp' + value['nombre'] + '</div></td>';
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
						$opcional_nota += '				<textarea id="nota_opcional_kit" class="form-control" style="cursor: se-resize">' + value.nota_opcional + '</textarea>';
						$opcional_nota += '			</div>';
						$opcional_nota += '		</div>';
						$opcional_nota += '	</div>';
					}
				});
				
				$div += '		</div>';
				$div += '	</div>';

			// ** Contenedores
			// Crea el contenedor de los productos
				$("#"+$objeto['div']).append($div);

			// Agrega las cabeceras a la Div
				$("#"+$objeto['div']).append($cabecera_sin);
				$("#"+$objeto['div']).append($cabecera_extra);
				$("#"+$objeto['div']).append($cabecera_opcional);

			// Agrega los productos "Sin"
				$("#sin_kit").append($sin);
			// Agrega los productos "Extra"
				$("#extra_kit").append($extra);
			// Agrega los productos "opcionales"
				$("#opcional_kit").append($opcional);

			// ** notas
			// Agrega la nota "Sin"
				$("#sin_kit").append($sin_nota);
			// Agrega la nota "Extra"
				$("#extra_kit").append($extra_nota);
			// Agrega la nota "Normal"
				$("#opcional_kit").append($opcional_nota);

			// Cambia el fondo del producto al checar o quitar el check
				$('.itemProductCheck').off('click');
				$(".itemProductCheck").click(function() {
					if ($(this).css('background-color') != 'rgb(216, 216, 216)') {
						$(this).css('background-color', '#D8D8D8');
						$(this).find('input').prop('checked', false);
					} else {
						$(this).css('background-color', '#81F781');
						$(this).find('input').prop('checked', true);
					}
				});
		// Carga el producto a la persona si no tiene materiales
			}
		}).fail(function(resp) {
			console.log('=========> fail detalles_producto');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al obtener los datos';
			else
				var $mensaje = 'Failed to get data';
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

///////////////// ******** ---- 			editar_producto_kit					------ ************ //////////////////
//////// Edita la informacion del producto del kit
	// Como parametros recibe:
		
// Agrega el producto al comensal
	editar_producto_kit : function($objeto) {
		console.log('================= objeto editar_producto_kit');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		var opcionales = new Array();
		var extras = new Array();
		var sin = new Array();

		var $nota_opcional = $('#nota_opcional_kit').val();
		var $nota_extra = $('#nota_extra_kit').val();
		var $nota_sin = $('#nota_sin_kit').val();

		var idperson = $(this).attr('idperson');
		var idcomanda = $(this).attr('idcomanda');

	// Cera los arreglos de opcionales y extra de los check seleccionados
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
		
		var $o = opcionales.join(',');
		var $e = extras.join(',');
		var $s = sin.join(',');

	// Loader
		var $loader = '	<div align="center">';
		$loader += '		<i class="fa fa-refresh fa-5x fa-spin"></i>';
		$loader += '	</div>';
		$("#" + $objeto['div']).html($loader);
		
		$objeto['opcionales'] = opcionales.join(',');
		$objeto['extras'] = extras.join(',');
		$objeto['sin'] = sin.join(',');
		$objeto['nota_opcional'] = $nota_opcional;
		$objeto['nota_extra'] = $nota_extra;
		$objeto['nota_sin'] = $nota_sin;
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=editar_producto_kit',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done editar_producto_kit');
			console.log(resp);
			
			$("#" + $objeto['div']).html('');
		}).fail(function(resp) {
			console.log('================= Fail editar_producto_kit');
			console.log(resp);
			if(comandas.idioma == 1)
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

///////////////// ******** ---- 			FIN editar_producto_kit				------ ************ //////////////////
	
///////////////// ******** ---- 				limpiar_div						------ ************ //////////////////
//////// Limpia el contenido de una div
	// Como parametros recibe:
		// div -> ID de la div que se tiene que limpiar
	
	limpiar_div: function($objeto) {
		$("#" + $objeto['div']).html('');
	},
	
///////////////// ******** ---- 				FIN limpiar_div					------ ************ //////////////////

///////////////// ******** ---- 				guardar_kit						------ ************ //////////////////
//////// Guarda el pedido del kit y los pedidos de sus productos
	// Como parametros recibe:

	guardar_kit : function($objeto) {
		console.log('===============> objeto guardar_kit');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=guardar_kit',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done guardar_kit');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
		
		// Cierra la ventana modal
			$("#cerrar_kit").click();
			
			loadUserProducts(0,$objeto['persona'], resp['id_comanda'], 'getItemsPerson',0,0);
		}).fail(function(resp) {
			console.log('================= Fail guardar_kit');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
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

///////////////// ******** ---- 			FIN guardar_kit						------ ************ //////////////////

///////////////// ******** ---- 				cambiar_status					------ ************ //////////////////
//////// Cambia el estatus de la comanda en la BD
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// id_comanda -> ID de la comanda

	cambiar_status : function($objeto) {
		console.log('===============> objeto cambiar_status');
		console.log($objeto);
		//return false;
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['pass']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Introduce la contraseña';
			else
				var $mensaje = 'Enter password';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cambiar_status',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done cambiar_status');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			
			if(resp['status'] == 2){
				if(comandas.idioma == 1)
					var $mensaje = 'Contraseña incorrecta';
				else
					var $mensaje = 'Incorrect password';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});
				
				return 0;
			}
			
			if(comandas.idioma == 1)
				var $mensaje = 'Comanda abierta';
			else
				var $mensaje = 'Open command';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
			
		// Todo bien :D, oculta la modal
			$('#modal_autorizar').modal('hide');
		}).fail(function(resp) {
			console.log('================= Fail cambiar_status');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al abrir la comanda';
			else
				var $mensaje = 'Error opening command';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN cambiar_status				------ ************ //////////////////

///////////////// ******** ---- 				agregar_via_contacto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto
		// btn -> Buton del loader

	agregar_via_contacto : function($objeto) {
		console.log('===============> objeto agregar_via_contacto');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['nombre']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Introduce un nombre';
			else
				var $mensaje = 'Enter a name';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_via_contacto',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done agregar_via_contacto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Via de contacto guardada';
			else
				var $mensaje = 'Contact path saved';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
			
		// Todo bien :D, oculta la modal
			$('#modal_via_contacto').modal('hide');
		
		// Actualiza el select
			$("#via_contacto").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#via_contacto").val(resp['result']);
			$('#via_contacto').selectpicker('refresh');
			
			$("#via_contacto_domicilio").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#via_contacto_domicilio").val(resp['result']);
			$('#via_contacto_domicilio').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('================= Fail agregar_via_contacto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al guardar la via de contacto';
			else
				var $mensaje = 'Failed to save contact path';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_via_contacto			------ ************ //////////////////

///////////////// ******** ---- 				agregar_zona_reparto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto
		// btn -> Buton del loader

	agregar_zona_reparto : function($objeto) {

		console.log('===============> objeto agregar_zona_reparto');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['nombre']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Introduce un nombre';
			else
				var $mensaje = 'Enter a name';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		
	// Loader en el boton OK
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=agregar_zona_reparto',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> done agregar_zona_reparto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Zonda de reparto guardada';
			else
				var $mensaje = 'Deal cast saved';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
				arrowSize : 15
			});
			
		// Todo bien :D, oculta la modal
			$('#modal_zona_reparto').modal('hide');
		
		// Actualiza el select
		/*
			$("#via_contacto").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#via_contacto").val(resp['result']);
			$('#via_contacto').selectpicker('refresh');
		*/
			
			$("#zona_reparto_domicilio").append('<option value="'+resp['result']+'">'+$objeto['nombre']+'</option>');
			$("#zona_reparto_domicilio").val(resp['result']);
			$('#zona_reparto_domicilio').selectpicker('refresh');
		}).fail(function(resp) {
			console.log('================= Fail agregar_zona_reparto');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al guardar la via de contacto';
			else
				var $mensaje = 'Failed to save contact path';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN agregar_zona_reparto			------ ************ //////////////////



///////////////// ******** ---- 				tooltip_mesa					------ ************ //////////////////
//////// Crea un tooltip de la mesa
	// Como parametros puede recibir:
	
	tooltip_mesa : function($objeto) {
		console.log('=========> objeto tooltip_mesa');
		console.log($objeto);
		
		$contenido = "	<div align='center'>";
		$contenido += "		Solicitud de servicio<br/><br/>";
		$contenido += "		<button onclick='comandas.actualizar_mesa({id_mesa: " +$objeto['mesa'] +", notificacion: \" 0\"})' type='button' class='btn btn-success'>";
		$contenido += "			<i class='fa fa-check'></i> Enterado";
		$contenido += "		</button>";
		$contenido += "	</div>";
		
		$('#mesa_'+$objeto['mesa']).tooltipster({
			contentAsHTML : true,
			interactive : true,
			animation : 'fall',
			autoClose : false,
			theme : 'tooltipster-Shadow',
			zIndex : 1,
			content : $contenido,
		}).mouseenter();
	},
	
///////////////// ******** ---- 				FIN tooltip_mesa				------ ************ //////////////////

///////////////// ******** ---- 				actualizar_mesa					------ ************ //////////////////
//////// Actualiza la mesa con nuevos datos
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion
	
	actualizar_mesa : function($objeto) {
		console.log('=========> objeto actualizar_mesa');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=actualizar_mesa',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done actualizar_mesa');
			console.log(resp);
		
		// Elimina el tooltipster
			$('#mesa_'+$objeto['id_mesa']).tooltipster("destroy");
			$('#mesa_'+$objeto['id_mesa']).attr("title", '');
		}).fail(function(resp) {
			console.log('=========> fail actualizar_mesa');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al actualizar la mesa';
			else
				var $mensaje = 'Error updating table';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_mesa					------ ************ //////////////////

///////////////// ******** ---- 			cargar_productos					------ ************ //////////////////
//////// Consulta los productos y los agrega a la div
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion
	
	cargar_productos : function($objeto) {
		console.log('=========> objeto cargar_productos');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=cargar_productos',
			type : 'POST',
			dataType : 'hrml'
		}).done(function(resp) {
			console.log('=========> done cargar_productos');
			console.log(resp);
		
		// Carga el contenido a la vista
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> fail cargar_productos');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al cargar los productos';
			else
				var $mensaje = 'Failed to load products';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 			FIN cargar_productos				------ ************ //////////////////

///////////////// ******** ---- 			actualizar_comanda					------ ************ //////////////////
//////// Actualiza la mesa con nuevos datos
	// Como parametros recibe:
		// id -> ID de la comanda
		// total -> total de la comanda
	
	actualizar_comanda : function($objeto) {
		console.log('=========> objeto actualizar_comanda');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=actualizar_comanda',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done actualizar_comanda');
			console.log(resp);
			
		}).fail(function(resp) {
			console.log('=========> fail actualizar_comanda');
			console.log(resp);
			/*
			if(comandas.idioma == 1)
				var $mensaje = 'Error al actualizar la mesa';
			else
				var $mensaje = 'Error updating table';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
			*/
		});
	},
	
///////////////// ******** ---- 			FIN actualizar_comanda				------ ************ //////////////////

///////////////// ******** ---- 			mandar_comanda_caja					------ ************ //////////////////
//////// Manda la comanda a caja
	// Como parametros recibe:
		// codigo -> Codigo de la comanda
	
	mandar_comanda_caja : function($objeto) {
		console.log('=========> objeto mandar_comanda_caja');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		var codigo = $objeto['codigo'];

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
				if(comandas.idioma == 1)
					var $mensaje = 'Necesitas abrir la caja';
				else
					var $mensaje = 'You need to open the box';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'warn',
					arrowSize : 15
				});

			}
			
		}
		console.log("strign: "+stringcaja);
		var outElement = $("#tb2156-u", window.parent.document).parent();
		var caja = outElement.find(stringcaja);
		var pestana = $("body", window.parent.document).find(stringcaja2);
		var openCaja = $("body", window.parent.document).find(stringcaja3);
		var pathname = window.location.pathname;
		var url = document.location.host + pathname;
		
		if(caja.length > 0){
		// Selecciona la pestaña de caja
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
		}
	},

	
///////////////// ******** ---- 			FIN mandar_comanda_caja				------ ************ //////////////////

///////////////// ******** ---- 				vista_comandera					------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_comandera : function($objeto) {
		console.log('=========> objeto vista_comandera');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
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
		}).fail(function(resp) {
			console.log('=========> Fail vista_comandera');
			console.log(resp);

			$('#'+$objeto['div']).html('Error al cargar la comandera');
			if(comandas.idioma == 1)
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

///////////////// ******** ---- 			mandar_mesa_comandera				------ ************ //////////////////
//////// Consulta los datos de la mesa y los devuelve en un array
	// Como parametros recibe:
		// id -> ID de la mesa
		// tipo -> Tipo de mesa
		// id_comanda -> ID de la comanda
	
	mandar_mesa_comandera : function($objeto) {
		
		console.log('=========> objeto mandar_mesa_comandera');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=mandar_mesa_comandera',
			type : 'GET',
			dataType : 'json',
		}).done(function(resp) {
			console.log('=========> Done mandar_mesa_comandera');
			console.log(resp);
			
			comandas.datos_mesa_comanda = resp;
			
			$("#comanda_text").html(resp['id_comanda']);
			$("#mesa_text").html(resp['info_mesa']['nombre_mesa']);
		}).fail(function(resp) {
			console.log('=========> Fail mandar_mesa_comandera');
			console.log(resp);
			if(comandas.idioma == 1)
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



///////////////// ******** ---- 				vista_eliminar_mesas			------ ************ //////////////////
//////// Carga la vista de las mesas a eliminar
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_eliminar_mesas : function($objeto) {
		console.log('=========> objeto vista_eliminar_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		comandas.mesas_seleccionadas = {};
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_eliminar_mesas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_eliminar_mesas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail vista_eliminar_mesas');
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
	
///////////////// ******** ---- 			FIN vista_eliminar_mesas			------ ************ //////////////////

///////////////// ******** ---- 				seleccionar_mesa				------ ************ //////////////////
//////// Agrega una mesa al array de las mesas seleccionadas
	// Como parametros recibe:
		// id_mesa -> ID de la mesa
	
	
	seleccionar_mesa : function($objeto) {
		console.log('=========> objeto seleccionar_mesa');
		console.log($objeto);

		if(comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']]){
			delete comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']];
			$('#btn_eliminar_mesa_' + $objeto['id_mesa']).removeClass('btn-danger');
			$('#btn_juntar_mesa_' + $objeto['id_mesa']).removeClass('btn-info');
		}else{
			comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']] = $objeto;
			$('#btn_eliminar_mesa_' + $objeto['id_mesa']).addClass('btn-danger');
			$('#btn_juntar_mesa_' + $objeto['id_mesa']).addClass('btn-info');
		}
		
		console.log('=========> mesas_seleccionadas');
		console.log(comandas.mesas_seleccionadas);
	},
	
///////////////// ******** ---- 			FIN mandar_mesa_comandera			------ ************ //////////////////

///////////////// ******** ---- 				seleccionar_mesa				------ ************ //////////////////
//////// Agrega una mesa al array de las mesas seleccionadas
	// Como parametros recibe:
		// id_mesa -> ID de la mesa
	
	
	seleccionar_silla : function($objeto) {
		console.log('=========> objeto seleccionar_mesa');
		console.log($objeto);
		
		if(comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']]){
			delete comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']];
			$('#btn_eliminar_mesa_' + $objeto['id_mesa']).removeClass('btn-danger');
			$('#btn_juntar_mesa_' + $objeto['id_mesa']).removeClass('btn-info');
		}else{
			comandas.mesas_seleccionadas['mesa_'+$objeto['id_mesa']] = $objeto;
			$('#btn_eliminar_mesa_' + $objeto['id_mesa']).addClass('btn-danger');
			$('#btn_juntar_mesa_' + $objeto['id_mesa']).addClass('btn-info');
		}
		
		console.log('=========> mesas_seleccionadas');
		console.log(comandas.mesas_seleccionadas);

	},
	
///////////////// ******** ---- 			FIN mandar_mesa_comandera			------ ************ //////////////////

	
///////////////// ******** ---- 				eliminar_mesas					------ ************ //////////////////
//////// Elimina las mesas seleccionadas
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// mesas_seleccionadas -> IDs de las mesas seleccionadas
	
	eliminar_mesas : function($objeto) {
		console.log('=========> objeto eliminar_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// Loading
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=eliminar_mesas',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done eliminar_mesas');
			console.log(resp);
			
		// Pass incorrecto
			if (resp['status'] == 2) {
			// Quita el loader
				$btn.button('reset');
			
				if(comandas.idioma == 1)
					var $mensaje = 'Contraseña incorrecta';
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
			
		// Limpia el array de las mesas
			comandas.mesas_seleccionadas = {};
			
			//var pathname = window.location.pathname;
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'index.php?c=comandas&f=menuMesas');
			window.location.reload();
		}).fail(function(resp) {
			console.log('=========> fail eliminar_mesas');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al eliminar las mesas';
			else
				var $mensaje = 'Deleting tables failed';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN eliminar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				vista_juntar_mesas				------ ************ //////////////////
//////// Carga la vista de las mesas
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_juntar_mesas : function($objeto) {
		console.log('=========> objeto vista_juntar_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		comandas.mesas_seleccionadas = {};
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_juntar_mesas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_juntar_mesas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail vista_juntar_mesas');
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
	
///////////////// ******** ---- 			FIN vista_juntar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				vista_juntar_sillas				------ ************ //////////////////
//////// Carga la vista de las mesas
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	vista_juntar_sillas : function($objeto) {
		console.log('=========> objeto vista_juntar_sillas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		comandas.mesas_seleccionadas = {};
		$('#' + $objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=vista_juntar_sillas',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> Done vista_juntar_sillas');
			console.log(resp);
		
		// Carga la vista a la div
			$('#'+$objeto['div']).html(resp);
		}).fail(function(resp) {
			console.log('=========> Fail vista_juntar_sillas');
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
	
///////////////// ******** ---- 			FIN vista_juntar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				juntar_mesas					------ ************ //////////////////
//////// Junta las mesas seleccionadas
	// Como parametros recibe:
		// mesas_seleccionadas -> IDs de las mesas seleccionadas
	
	juntar_mesas : function($objeto) {
		console.log('=========> objeto juntar_mesas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
		
		/*$.each($objeto.mesas, function(index, val) {
			console.log("index"+ index);
			$objeto.mesas[index] = val["id_mesa"];
		});*/
	// Loading
		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=juntar_mesas',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done juntar_mesas');
			console.log(resp["result"]["id"]);
		
			console.log('=========> comandas.mesas_seleccionadas ');
			console.log(comandas.mesas_seleccionadas);
			var ids_mesas = '';
			var nombre_mesa = '';
		// Limpia el array de las mesas
			var aux = '';
			$.each($objeto.mesas, function(index, val) {
					if(aux != ''){
						$("#"+val["id_mesa"]).hide();
						nombre_mesa = nombre_mesa+','+val['nombre'];
						if(val['id_mesas'] != ''){
							ids_mesas = ids_mesas+','+val['id_mesas'];
						}else{
							ids_mesas = ids_mesas+','+val['id_mesa'];
						}
						
						
					}else{
						if(val['id_mesas'] != ''){
							ids_mesas = val['id_mesas'];
						}else{
							ids_mesas = val['id_mesa'];
						}
						nombre_mesa = val['nombre'];
						
						aux = index;
					}
				});
			if(resp["result"]["id"])
				$("#img_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("src", "images/mapademesas/ocupada_juntadas.png");
			else
				$("#img_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("src", "images/mapademesas/libre_juntadas.png");

			$("#div_nombre_mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).text(nombre_mesa);
			if(resp["result"]["id"])
				$("#mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("id_comanda", "'"+resp["result"]["id"]+"'");
			$("#mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("onclick", "comandera.mandar_mesa_comandera({ids_mesas: '"+ids_mesas+"',id_mesa: "+comandas.mesas_seleccionadas[aux]['id_mesa']+",tipo: 0,tipo_mesa: 1,nombre_mesa_2: '"+nombre_mesa+"',id_comanda: $(this).attr('id_comanda'),separar: 1,tipo_operacion: comandera.ajustes['tipo_operacion']})");
			comandas.mesas_seleccionadas = [];
			
			// Oculta la ventana modal
				$("#modal_comandera").click();
				$("#modal_juntar_mesas").click();
				// Quita el loader
			$btn.button('reset');
			console.log("nombre_mesa");
			console.log(nombre_mesa);
			//var pathname = window.location.pathname;
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'http://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
		}).fail(function(resp) {
			console.log('=========> fail juntar_mesas');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al juntar las mesas';
			else
				var $mensaje = 'Failed to put tables together';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN juntar_mesas				------ ************ //////////////////


///////////////// ******** ---- 				juntar_mesas					------ ************ //////////////////
//////// Junta las mesas seleccionadas
	// Como parametros recibe:
		// mesas_seleccionadas -> IDs de las mesas seleccionadas
	
	juntar_sillas : function($objeto) {
		console.log('=========> objeto juntar_sillas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();

		var $btn = $('#'+$objeto['btn']);
		$btn.button('loading');
		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=juntar_sillas',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done juntar_sillas');
			console.log(resp["result"]["id"]);
		
			console.log('=========> comandas.mesas_seleccionadas ');
			console.log(comandas.mesas_seleccionadas);
			var ids_mesas = '';
			var nombre_mesa = '';
		// Limpia el array de las mesas
			var aux = '';
			$.each($objeto.mesas, function(index, val) {
					if(aux != ''){
						$("#"+val["id_mesa"]).hide();
						nombre_mesa = nombre_mesa+','+val['nombre'];
						if(val['id_mesas'] != ''){
							ids_mesas = ids_mesas+','+val['id_mesas'];
						}else{
							ids_mesas = ids_mesas+','+val['id_mesa'];
						}
						
						
					}else{
						if(val['id_mesas'] != ''){
							ids_mesas = val['id_mesas'];
						}else{
							ids_mesas = val['id_mesa'];
						}
						nombre_mesa = val['nombre'];
						
						aux = index;
					}
				});
			if(resp["result"]["id"])
				$("#img_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("src", "images/mapademesas/ocupada_juntadas.png");
			else
				$("#img_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("src", "images/mapademesas/libre_juntadas.png");

			$("#div_nombre_mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).text(nombre_mesa);
			if(resp["result"]["id"])
				$("#mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("id_comanda", "'"+resp["result"]["id"]+"'");
			$("#mesa_"+comandas.mesas_seleccionadas[aux]['id_mesa']).attr("onclick", "comandera.mandar_mesa_comandera({ids_mesas: '"+ids_mesas+"',id_mesa: "+comandas.mesas_seleccionadas[aux]['id_mesa']+",tipo: 0,tipo_mesa: 1,nombre_mesa_2: '"+nombre_mesa+"',id_comanda: $(this).attr('id_comanda'),separar: 1,tipo_operacion: comandera.ajustes['tipo_operacion']})");
			comandas.mesas_seleccionadas = [];
			
			// Oculta la ventana modal
				$("#modal_comandera").click();
				$("#modal_juntar_sillas").click();
				// Quita el loader
			$btn.button('reset');
			console.log("nombre_mesa");
			console.log(nombre_mesa);
			
			//var pathname = window.location.pathname;
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'https://' + document.location.host + pathname + '?c=comandas&f=menuMesas');
			//$("#tb2156-u .frurl", window.parent.document).attr('src', 'index.php?c=comandas&f=menuMesas');
			window.location.reload();
			
		}).fail(function(resp) {
			console.log('=========> fail juntar_mesas');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al juntar las mesas';
			else
				var $mensaje = 'Failed to put tables together';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN juntar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				listar_propinas					------ ************ //////////////////
//////// Consulta las propinas y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// div -> div donde se cargara el contenido html
		// empleado -> ID del empleado
		// mesa -> ID de la emsa
		// sucursal -> ID de la sucursal
		// metodo_pago -> Metodo de pago

	listar_propinas : function($objeto) {
		console.log('------------> Objeto listar_propinas');
		console.log($objeto);
		if(comandas.idioma == 0)
			comandas.get_idioma();
	// ** Validaciones
		if (!$objeto['f_ini']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha de inicio invalida: \n Ejem. 13/01/2015 13:21';
			else
				var $mensaje = 'Invalid start date: \n Example. 13/01/2015 13:21';
			$('#btn_buscar_propinas').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
		if (!$objeto['f_fin']) {
			if(comandas.idioma == 1)
				var $mensaje = 'Fecha final invalida: \n Ejem. 13/01/2015 23:59';
			else
				var $mensaje = 'Invalid end date: \n Example. 13/01/2015 23:59';
			$('#btn_buscar_propinas').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'warn',
			});

			return 0;
		}
	
	// Loader en el boton OK
		var $btn = $('#btn_buscar_propinas');
		$btn.button('loading');

		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=listar_propinas',
			type : 'POST',
			dataType : 'html',
		}).done(function(resp) {
			console.log('=========> done listar_propinas');
			console.log(resp);
		
		// Quita el loader
			$btn.button('reset');
		
		// Carga los promedios en la div
			$('#' + $objeto['div']).html(resp);
			
		// Crea el datatable				
			comandas.convertir_dataTable({id:'tabla_propinas'});
		}).fail(function(resp) {
			console.log('=========> fail listar_propinas');
			console.log(resp);
			
		// Quita el loader
			$btn.button('reset');
			if(comandas.idioma == 1)
				var $mensaje = 'Error al buscar las propinas';
			else
				var $mensaje = 'Error al buscar las propinas';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 				FIN listar_propinas				------ ************ //////////////////

///////////////// ******** ---- 				editar_empleado					------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda
	
	editar_empleado : function($objeto) {
		console.log('=========> objeto editar_empleado');
		console.log($objeto);
		
		if(comandas.idioma == 0)
			comandas.get_idioma();
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=editar_empleado',
			type : 'POST',
			dataType : 'json'
		}).done(function(resp) {
			console.log('=========> done editar_empleado');
			console.log(resp);
			
			if(comandas.idioma == 1)
				var $mensaje = 'Datos guardados';
			else
				var $mensaje = 'Save data';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'success',
			});
		}).fail(function(resp) {
			console.log('=========> fail editar_empleado');
			console.log(resp);
			
			if(comandas.idioma == 1)
				var $mensaje = 'Error al guardar los datos';
			else
				var $mensaje = 'Save data failed';
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},
	
///////////////// ******** ---- 				FIN eliminar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				imprimir_propina				------ ************ //////////////////
//////// Imprime el ticket de la propina
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// mesero -> Nombre del mesero
		// total_propina -> Total de la propina
		
	imprimir_propina : function($objeto) {
		console.log('------------> $objeto imprimir_propina');
		console.log($objeto);
		
		if(comandas.idioma == 0)
			comandas.get_idioma();
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=imprimir_propina',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done imprimir_propina');
			console.log(resp);
			
		// Imprimimos el TIcket en una nueva ventana
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
			$(ventana).ready(function() {
			//cargamos el HTML del objeto en la nueva ventana
				ventana.document.write(resp);
				ventana.document.close();
				
				setTimeout(closew,100);
				function closew(){
					ventana.print();  //imprimimos la ventana
				}
			});
		}).fail(function(resp) {
			console.log('---------> Fail imprimir_propina');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al imprimir la propina';
			else
				var $mensaje = 'Error printing ticket';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

///////////////// ******** ---- 			FIN imprime_sub_comanda			------ ************ //////////////////

///////////////// ******** ---- 				porcentaje_pre				------ ************ //////////////////
//////// Imprime el ticket de la propina
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// mesero -> Nombre del mesero
		// total_propina -> Total de la propina
		
	porcentaje_pre : function($objeto) {
		console.log('------------> $objeto porcentaje_pre');
		console.log($objeto); // solo trae a la persona
		
		console.log($("#porcentaje_"+$objeto).val());
		if($("#porcentaje_"+$objeto).val() > 100){
			$("#porcentaje_"+$objeto).val(100);
		}

		var to = parseFloat((comandas.total_comanda * $("#porcentaje_"+$objeto).val())/100);
		var to2 = $("#peso_"+$objeto).val();
		if (!to2) {
			to2 = 0;
		};
		var total = parseFloat(to)+parseFloat(to2);

		var to3 = parseFloat((100*$("#peso_"+$objeto).val()) / (comandas.total_comanda));
		var to4 = $("#porcentaje_"+$objeto).val();
		if (!to4) {
			to4 = 0;
		};
		var per = parseFloat(to3)+parseFloat(to4);

		$("#pago_"+$objeto).html("$ "+total.toFixed(2));
		$("#per_"+$objeto).html("% "+per.toFixed(2));

		comandas.totales_comandas[$objeto] = total.toFixed(2);
		comandas.porcentajes_comandas[$objeto] = per.toFixed(2);
		comandas.precio_comandas[$objeto] = parseFloat(to2).toFixed(2);
		console.log(comandas.totales_comandas);
		console.log(comandas.porcentajes_comandas);
		console.log(comandas.precio_comandas);
		var toti = 0;
		$.each(comandas.totales_comandas, function(index, val) {
			toti += parseFloat(val);
		});
		toti = comandas.total_comanda - toti;
		$("#sub_to").html("$ "+toti.toFixed(2));

		
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=porcentaje_pre',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done imprimir_propina');
			console.log(resp);
		});

	},

///////////////// ******** ---- 			FIN porcentaje_pre			------ ************ //////////////////

///////////////// ******** ---- 				llenar_campos_dividir				------ ************ //////////////////
//////// Imprime el ticket de la propina
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// mesero -> Nombre del mesero
		// total_propina -> Total de la propina
		
	llenar_campos_dividir : function($objeto) {
		console.log("llenar_campos_dividir");
		console.log(comandas.totales_comandas);
		$.each(comandas.totales_comandas, function(index, val) {
			$("#peso_"+index).val(comandas.precio_comandas[index]);
			$("#porcentaje_"+index).val(comandas.porcentajes_comandas[index]);
			comandas.porcentaje_pre(index);
		});

		/*if(comandas.idioma == 0)
			comandas.get_idioma();
			
		$.ajax({
			data : $objeto,
			url : 'ajax.php?c=comandas&f=imprimir_propina',
			type : 'GET',
			dataType : 'html',
		}).done(function(resp) {
			console.log('------------> done imprimir_propina');
			console.log(resp);
			
		// Imprimimos el TIcket en una nueva ventana
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
			$(ventana).ready(function() {
			//cargamos el HTML del objeto en la nueva ventana
				ventana.document.write(resp);
				ventana.document.close();
				
				setTimeout(closew,100);
				function closew(){
					ventana.print();  //imprimimos la ventana
				}
			});
		}).fail(function(resp) {
			console.log('---------> Fail imprimir_propina');
			console.log(resp);
			if(comandas.idioma == 1)
				var $mensaje = 'Error al imprimir la propina';
			else
				var $mensaje = 'Error printing ticket';
				
			$.notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});*/
	},

///////////////// ******** ---- 			FIN llenar_campos_dividir			------ ************ //////////////////

}; // Fin de la clase