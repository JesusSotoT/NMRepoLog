var $nombre_mesa = '';

function imprime(objet) {
	var idcomanda = objet['id_comanda'];
}

///////////////// ******** ---- 	cerrar_comanda		------ ************ //////////////////
//////// Imprime la comanda en una nueva ventana
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda
		// id_mesa -> ID de la mesa
		// reimprime -> bandera que indica que es reimpresion de comanda
		// tipo -> mesa normal
		// pbdandera -> indica si es normal o (servicio a domicilio, para llevar)
		
function cerrar_comanda($objeto) {
	var $servicio = $objeto['tipo'];
	var $nombre = '';
	var $direccion = '';
	var $id_reservacion = '';

// Valida que el numero de comensales sea 1 o mas
	var $num_comensales = $('#num_comensales').val();
	if ($num_comensales < 1) {
		$num_comensales = 1;
	}

	var idComanda = $objeto['id_comanda'];
	var bandera = $objeto['pbdandera'];;
	var idmesa = $objeto['id_mesa'];
	var tipo = $objeto['tipo'];
	var tel = $objeto['tel'];

// Armamos el array para el Ajax
	var $objeto = {};
	$objeto['idComanda'] = idComanda;
	$objeto['bandera'] = bandera;
	$objeto['idmesa'] = idmesa;
	$objeto['tipo'] = tipo;
	$objeto['tel'] = tel;
	$objeto['id_reservacion'] = $id_reservacion;
	console.log('--------- > objeto cerrar Comanda');
	console.log($objeto);

	$(".GtableCloseComanda").css('visibility', 'hidden');

	$.ajax({
		data : $objeto,
		url : 'ajax.php?c=comandas&f=closeComanda',
		type : 'GET',
		dataType : 'json',
	}).done(function(callback) {
		console.log('--------- > Done Cerrar comanda');
		console.log(callback);

		var txt_propina = '';
		var persona = 0;
		var totalPersona = 0;
		var $promedio_comensal = 0;
		var totalComanda = 0;
		var idComanda = $objeto['id_comanda'];
		var bandera = 0;
		var logo = callback['logo'];
		var sub_total = 0;
		var impuestos = 0;

	// La comanda se cierra pagando todo junto
		if (callback['tipo'] == 0) {
			var html = '<script src="../../libraries/JsBarcode.all.min.js"><\/script><script src="js/comandas/comandas.js"><\/script>        <div style="text-align:left;font-size:14px">';
			
			html += '	<div>';
			html += '		<input type="image" src="../../netwarelog/archivos/1/organizaciones/' + logo + '" style="width:180px"/>';
			html += '	</div>';
			
			html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
			html += '	Mesa: ' + callback['rows'][0]['nombre_mesa'];
			html += '</div>';
		
			var bcontent = "";
			var codigo = "";

			$.each(callback['rows'], function(index, value) {
			// Servicio a Domicilio o para llevar
				if ($servicio == 2) {
					html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
					html += '	Nombre: ' + $nombre;
					html += '</div>';
					html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
					html += '	Direccion: ' + $direccion;
					html += '</div>';
				}

				console.log('Mesa: ' + $nombre_mesa);

				if (persona != value['npersona']) {
					html = html.replace(">Orden No:" + persona, ">Orden No: " + persona);
					html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">Orden No:' + value['npersona'] + '</div>';
					persona = value['npersona'];
					totalPersona = 0;
				}

				if (!bandera) {
					bandera = 1;

				// Para llevar
					if (value['tipo'] == "1") {
						bcontent = '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						bcontent += '		Nombre: ' + value['nombreu'];
						bcontent += '	</div>';
						
						bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						bcontent += '		Domicilio: ' + value['domicilio'];
						bcontent += '	</div>';

						if (callback['tel']) {
							bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
							bcontent += '		Tel: ' + callback['tel'];
							bcontent += '	</div>';
						}
					}

				// Servicio a domicilio
					if (value['tipo'] == "2") {
						bcontent = '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						bcontent += '		Nombre: ' + value['nombreu'];
						bcontent += '	</div>';
						bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						bcontent += '		Domicilio: ' + value['domicilio'];
						bcontent += '	</div>';

						if (callback['tel']) {
							bcontent += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
							bcontent += '		Tel: ' + callback['tel'];
							bcontent += '	</div>';
						}
					}

					codigo = value['codigo'];
				}

				html += '<div style="margin-left:15px">';
				html += '	<table style="font-size:11px;font-family:Arial;border-collapse:collpase">';
				html += '		<tr>';
				html += '			<td>' + value['cantidad'] + '</td>';
				html += '			<td>' + value['nombre'] + '</td>';
				html += '			<td>' + parseFloat(value['precioventa'] * value['cantidad']).toFixed(2) + '</td>';
				html += '		</tr>';
				html += '	</table>';
				html += '</div>';

			// Si existen materiales con costo extra los agrega a la cuenta
				if (value['costo_extra']) {
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
						html += '		<td>' + cc['nombre'] + ': </td>';
						html += '		<td>$ ' + parseFloat(cc['costo'] * value['cantidad']) + '</td>';
						html += '	</tr>';

						$costo_extra += parseFloat(cc['costo'] * value['cantidad']);

						console.log('---	-	-	-	-	-	-	$costo_extra: ' + $costo_extra);
					});

					html += '	</table>';
					html += '</div>';

					console.log('--- totalPersona: ' + totalPersona + '--- totalComanda' + totalComanda + '--- impuestos' + impuestos);

					totalPersona += parseFloat($costo_extra);
					totalComanda += parseFloat($costo_extra);

					console.log('--- totalPersona: ' + totalPersona + '--- totalComanda' + totalComanda + '--- impuestos' + impuestos);

				}

				totalPersona += parseFloat(value['precioventa'] * parseFloat(value['cantidad']));
				totalComanda += parseFloat(value['precioventa'] * parseFloat(value['cantidad']));
				impuestos += parseFloat(value['impuestos'] * parseFloat(value['cantidad']));

				$promedio_comensal += totalPersona;
			});

			$promedio_comensal = ($promedio_comensal / $num_comensales);

			html = html.replace(">Orden No:" + persona, ">Orden No: " + persona);
			html = html.replace(">Comanda No:" + idComanda, ">Comanda No: " + idComanda + " / Mesa:" + $nombre_mesa);
			var propina = totalComanda * 0.10;
			html += bcontent;

			if (callback['mostrar'] == 1) {
				txt_propina = 'Propina sugerida: ' + propina.toFixed(2);
				html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				html += '	' + txt_propina;
				html += '	</div>';
			}

			totalComanda = totalComanda.toFixed(2);
			sub_total = (totalComanda - impuestos).toFixed(2);
			impuestos = impuestos.toFixed(2);

		// Sub total
			// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			// html += '		Sub total: <strong>$' + sub_total + '</strong>';
			// html += '	</div>';
// 
		// // Impuestos
			// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			// html += '		Impuestos: <strong>$' + impuestos + '</strong>';
			// html += '	</div>';

			html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
			html += '		Total: <strong>$' + totalComanda + '</strong>';
			html += '	</div>';
			html += '	<div style="margin-top:10px;">';
			html += '		<img id="' + codigo + '" style="width:190px;margin-left:-3px;"/>';
			html += '	</div>';
			html += '</div>';

		// Codigo de barras
			html += '<script>comandas.codigo_barras({id:\'' + codigo + '\', codigo:\'' + codigo + '\'});<\/script>';
			console.log(html);

			bandera = 0;
			bcontent = "";

		//abrimos una ventana vacía nueva
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');

			$(ventana).ready(function() {
				ventana.document.write(html);
				ventana.document.close();
			
			//imprimimos la ventana
				setTimeout(closew, 1000);
				function closew() {
					ventana.print();
				}
			});
		}
	// FIN La comanda se cierra pagando todo junto

	// La comanda se cierra pagando individual
		if (callback['tipo'] == 1) {
			var html = '<script src="../../libraries/JsBarcode.all.min.js"><\/script><script src="js/comandas/comandas.js"><\/script>';

			$.each(callback['rows'], function(index, value) {
				totalPersona = 0;
				codigo = value['codigo'];
				impuestos = 0;

				html += '	<div>';
				html += '		<input type="image" src="../../netwarelog/archivos/1/organizaciones/' + logo + '" style="width:180px"/>';
				html += '	</div>';
				html += '	<div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">';
				html += '		Orden:' + index + ' / Mesa: ' + $nombre_mesa;
				html += '	</div>';

			// Servicio a Domicilio o para llevar
				if ($servicio == 2) {
					html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
					html += '	Nombre: ' + $nombre;
					html += '</div>';
					html += '<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">';
					html += '	Direccion: ' + $direccion;
					html += '</div>';
				}

				console.log('Mesa: ' + $nombre_mesa);
				if (!bandera) {
					bandera = 1;

				// Para llevar
					if (value['tipo'] == "1") {
						html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						html += '		Nombre: ' + value['nombre_usuario'];
						html += '	</div>';
						html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						html += '		Domicilio: ' + value['domicilio'];
						html += '	</div>';
				
						if (callback['tel']) {
							html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
							html += '		Tel: ' + callback['tel'];
							html += '	</div>';
						}
					}
				
				// Servicio a domicilio
					if (value['tipo'] == "2") {
						html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						html += '		Nombre: ' + value['nombre_usuario'];
						html += '	</div>';
						html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
						html += '		Domicilio: ' + value['domicilio'];
						html += '	</div>';
				
						if (callback['tel']) {
							html += '	<div style="border-top:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
							html += '		Tel: ' + callback['tel'];
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
					html += '			<td>' + v['cantidad'] + '</td>';
					html += '			<td>' + v['nombre'] + '</td>';
					html += '			<td>' + parseFloat(v['precioventa'] * v['cantidad']).toFixed(2) + '</td>';
					html += '		</tr>';
					html += '	</table>';
					html += '</div>';

				// Si existen materiales con costo extra los agrega a la cuenta
					if (v['costo_extra']) {
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
							html += '		<td>' + cc['nombre'] + ': </td>';
							html += '		<td>$ ' + parseFloat(cc['costo'] * v['cantidad']) + '</td>';
							html += '	</tr>';

							$costo_extra += parseFloat(cc['costo'] * v['cantidad']);
							totalPersona += parseFloat($costo_extra);

							console.log('---	-	-	-	-	-	-	$costo_extra: ' + $costo_extra);
						});

						html += '	</table>';
						html += '</div>';

						console.log('--- totalPersona: ' + totalPersona + '---- impuestos' + impuestos);
					}
				});

				$promedio_comensal += totalPersona;
				sub_total = (totalPersona - impuestos).toFixed(2);
				propina = parseFloat(totalPersona * 0.10).toFixed(2);
				impuestos = impuestos.toFixed(2);

			// Sub total
				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				// html += '		Sub total: <strong>$' + sub_total + '</strong>';
				// html += '	</div>';
// 
			// // Impuestos
				// html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				// html += '		Impuestos: <strong>$' + impuestos + '</strong>';
				// html += '	</div>';

				html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
				html += '		Total: <strong>$' + totalPersona + '</strong>';
				html += '	</div>';

				if (callback['mostrar'] == 1) {
					txt_propina = 'Propina sugerida: ' + propina;
					html += '	<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">';
					html += '	' + txt_propina;
					html += '	</div>';
				}

				html += '	<div style="margin-top:10px;">';
				html += '		<img id="' + codigo + '" style="width:190px;margin-left:-3px;"/>';
				html += '	</div>';
				html += '</div>';

			// Codigo de barras
				html += '<script>comandas.codigo_barras({id:\'' + codigo + '\', codigo:\'' + codigo + '\'});<\/script>';
			});//FIN Each Personas

			console.log(html);

		//abrimos una ventana vacía nueva
			var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');

		//imprimimos la ventana
			$(ventana).ready(function() {
				ventana.document.write(html);
				ventana.document.close();

				setTimeout(closew, 1000);
				function closew() {
					ventana.print();
				}
			});
		}
	// FIN La comanda se cierra pagando individual
	}).fail(function(resp) {
		console.log('---------> Fail Closecomanda');
		console.log(resp);

		$mensaje = 'Error al imprimir la comanda';
		$.notify($mensaje, {
			position : "top center",
			autoHide : true,
			autoHideDelay : 5000,
			className : 'error',
			arrowSize : 15
		});
	});
	// Fin ajax
}

///////////////// ******** ---- 	FIN cerrar_comanda		------ ************ //////////////////

///////////////// ******** ---- 	formatearTicket		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	vararray -> array ticket
//	varstring -> string a convertir
//	contador -> contador
function formatearTicket(vararray, varstring, bold, underlined){
			varstring = String(varstring).trim();
			var varstring_copia = varstring.split(" ");
			var string = '';
			var ant_string = '';
			if(varstring.length > 32){
				for (var x = 0; x < varstring_copia.length; x++) {
					ant_string = string.trim();
					string += ' '+varstring_copia[x];
					if(string.length > 32){
						vararray.push({'texto' : ant_string , 'bold' : bold, 'underlined' : underlined});
						string = '';
						x--;
					} else if(x == (varstring_copia.length - 1)){
						vararray.push({'texto' : string.trim(), 'bold' : bold, 'underlined' : underlined} );
					}
					
				}
			} else {
				vararray.push({'texto' : varstring, 'bold' : bold, 'underlined' : underlined});
			}
			return vararray;
}
///////////////// ******** ---- 	FIN formatearTicket		------ ************ //////////////////

function isEmptyF(obj){
	    var isEmpty = false;
	 
	    if (typeof obj == 'undefined' || obj === null || obj === ''){
	      isEmpty = true;
	    }      
	       
	    if (typeof obj == 'number' && isNaN(obj)){
	      isEmpty = true;
	    }
	       
	    if (obj instanceof Date && isNaN(Number(obj))){
	      isEmpty = true;
	    }
	       
	    return isEmpty;
}

///////////////// ******** ---- 	imprimePedido		------ ************ //////////////////
//////// Imprime los pedidos de la comanda en una nueva ventana
// Como parametros puede recibir:
//	id_comanda -> ID de la comanda

function imprimePedido(objet) {
	var idcomanda = objet['id_comanda'];
	var id = 1;

	console.log('---> objeto imprimePedido');
	console.log(objet);

	/*
	 Esta funcion es para mandar llamar las comandas de la zona que selecciones, y se vuelve a consultar cada 10 segundos
	 */

	$.ajax({
		url : '../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=reimprime',
		type : 'POST',
		dataType : 'json',
		data : {
			'tipo' : idcomanda,
			'pedidos' : objet['pedidos']
		},
		async: false
	}).done(function(data) {
		console.log('------> Done imprimePedido');
		console.log(data);

		// Sin datos
		if (data['status'] == 2) {
			alert('No se encontraron pedidos');

			return 0;
		}


		var organizacion = data.organizacion;
		var altura = 290;
		var mm = 15.118110236;
		//5 mm
		var contProducto = 0;
		var persona = 0;
		//mm = 3.779527559 px;
		var tipo = '';
		var jsonPedido = [];
		jsonPedido.push({'logo' : '', 'codigo' : '', 'qr' : '', 'tipo' : 1, 'type' : ''});

		if(data.dispositivo == 1){
			$.each(data.comanda, function(index, val) {
				//jsonPedido[0] = organizacion;
				jsonPedido = formatearTicket(jsonPedido, organizacion, 0,0);
				if(val.tipo == 1){
					tipo = 'Para Llevar';
				}else if(val.tipo == 2){
					tipo = 'A domicilio';
				}
				jsonPedido = formatearTicket(jsonPedido, '', 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Comanda: ' + val["comanda"], 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Inicio del Pedido: ' + val["inicioPedido"], 0,0);
				jsonPedido = formatearTicket(jsonPedido, 'Mesa: ' + val["nombre_mesa"], 0,0);
				
				
				
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
				//altura  = altura * (mm*(persona+1));
			});
			var jsV = JSON.stringify(jsonPedido);
			jsV = jsV.replace(/#/g, '');
			console.log(jsV);
			var navegador = (navigator.userAgent.indexOf('Firefox') != -1) ? 1 : ((navigator.userAgent.indexOf("Chrome") != -1) ? 2 : 0);
			var pestana = window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';S.navegador='+ navegador +';end'); 
			pestana.close();
			//window.open('intent://intentar/#Intent;scheme=http;package=com.netwarmonitor.utilidades;S.extra1=' + jsV + ';end'); 
			
		} else {
			var html = '<div style="text-align:left;font-size:14px;">';
			$.each(data.comanda, function(index, val) {

				if(val.tipo == 1){
					tipo = 'Para Llevar';
				}else if(val.tipo == 2){
					tipo = 'A domicilio';
				}
				html += organizacion + '</br></br>';
				html += 'Comanda: ' + val["comanda"] + '</br>';
				html += 'Inicio del Pedido: ' + val["inicioPedido"] + '</br>';
				html += 'Mesa: ' + val["nombre_mesa"] + '</br>';
				
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
					html += 'Orden ' + persona + '</br>';
					html += '</br>';

					var $tiempo_platillo = 0;
					
					$.each(value.productos, function(index, producto) {
						contProducto = (index + 1);
						
					// Muestra en que tiempo se debe servir el platilo
						if(producto["tiempo_platillo"] != $tiempo_platillo){
							html += '</br><strong>============> Tiempo '+ producto["tiempo_platillo"] + '</strong>';
							
							$tiempo_platillo = producto["tiempo_platillo"];
						}

						html += '</br><strong>'+ producto["cantidad"] + '</strong>'+ 'x ' + producto["descripcion"] +'</br>';

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
							//producto["adicionalesDesc"] = (producto["adicionalesDesc"]).replace(',',',</br>');
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
				//altura  = altura * (mm*(persona+1));
			});
			html += '</div>';
			var ventana = window.open('', '_blank', 'width=207.874015748,height=' + altura + ',leftmargin=0');
			//abrimos una ventana vacía nueva
			ventana.document.write(html);
			//imprimimos el HTML del objeto en la nueva ventana
			//ventana.document.close();  //cerramos el documento
			ventana.print();
			//imprimimos la ventana *
		}
	}).fail(function(resp) {
		console.log('------> fail imprimePedido');
		console.log(resp);

		alert('Error al imprimir los pedidos');

	});
}

///////////////// ******** ---- 	imprimePedido		------ ************ //////////////////