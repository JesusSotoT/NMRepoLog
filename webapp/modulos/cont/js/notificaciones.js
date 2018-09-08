function agregar_editar_notificacion(val_archivo){
	var url = "ajax.php?c=edu&f=agregar_notificacion", formData = new FormData(), titulo, mensaje, activa, producto;
	id = $('.title').attr('data-id');

	if (id == undefined && id == null) {
		id = 0;
	}

	activa   = $('#activa').val();
	titulo   = $('#titulo').val();
	mensaje  = $('#mensaje').val();
	mensaje  = mensaje.replace(/\n/g, "<br/>");
	producto = $('#producto').val();

	//Validamos el mensaje
	if ((mensaje == '') || (mensaje == undefined)){
		alert("El campo mensaje esta vacío.");
	} 
	//Validamos el titulo
	else if(titulo == '' || (titulo == undefined)){
		alert("El campo titulo esta vacío.");
	} 
	//Validamos el tipo de instancia
	else if($('#tipo_instancia').val() == 0){
		alert("Ingrese un prodcuto");
	}
	else {
		$('#enviar').fadeOut();

		formData.append("id", id);
		formData.append("activa", activa);
		formData.append("titulo", titulo);
		formData.append("mensaje", mensaje);
		formData.append("producto", producto);
		formData.append("archivo", $("#archivo")[0].files[0]);
		$.ajax({
			type: "POST",
			url: url,
			data: formData,
			processData: false,
			contentType: false,
			dataType: "JSON",
			success: function(data) {
				console.log(data);
				//Respuesta del formulario
				if (data.result == 1) {
					alert("La notificación se agrego exitosamente.");
					//Respuesta del archivo
					alert(data.file);
					window.location.replace("index.php?c=edu&f=ver_notificaciones");
				} else if(data.result == 2){
					alert("La notificación se guardo exitosamente.");
					//Respuesta del archivo
					alert(data.file);
					window.location.replace("index.php?c=edu&f=ver_notificaciones");
				} else {
					alert("No se pudo realizar la operación.");
				}
			}
		}).done(function(){
		 	$('#enviar').fadeIn();
		});
	}
}

function validar_extension_pdf(data){
	var myfile = '', ext = '';
	myfile= data.val();
	ext = myfile.split('.').pop();
	if(ext=="pdf"){
		return "pdf";
	} else {
		alert("Ingrese un archivo pdf.");
		data.replaceWith(data.val('').clone(true));
		return "0";
	}
}

function mostrarNoticiasModal(tipo){
	var id_instancia = $('#inst').val()
		, validar_despacho;
	$('#noticias').animate({
		width: "toggle",
		opacity: "toggle"
	}, function(){
		//Validamos que sea el boton de mostrar notificaciones para que haga la consulta.
		//De lo contrario, no consultara por nuevas notificaciones.
		if(tipo == 1){
			$('#lista-loading-noticias').show();
			var display;
			display = $('#noticias').css('display');
			if(display == "block"){
				$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_noticias",
				function(data){
					if (data.error == 0) {
						var info = "";
						$('#lista-loading-noticias').hide();
						$.each(data, function(index, arr){
							if (arr != 0) {
								var archivo;
								if (arr.archivo != 0 && arr.archivo != '' && typeof arr.archivo != typeof undefined) {
									var param = "abrir_pdf('"+arr.archivo+"')";
									archivo = '<a target="_blank" onclick="'+param+';">'+arr.archivo+'</a>';
								} else {
									archivo = "-";
								}
								info += "<li><h3>"+arr.producto+"</h3><h5>"+arr.titulo+"</h5>"+arr.mensaje+"<p>Agregado el: "+arr.fecha+"</p><p>"+archivo+"</p></li>";
							}
						});
						$('#lista-noticias').html(info);
					} else {
						$('#lista-loading-noticias').hide();
						$('#lista-noticias').html(data.error);
					}

					//Validamos que la instancia sea una instancia hija o despacho virtual.
					$.post("../../modulos/cont/ajax.php?c=edu&f=validar_pertenece_despacho",
					{
						id: id_instancia
					}, 
					function(data){
						if (data.id == 0) {
							$('#recordatorios_container').hide();
						} else {
							obtener_recordatorios(id_instancia);
							$('#recordatorios_container').show();
						}
					}, "JSON");	
				}, "JSON");	
			}	
		}
	});
}

function abrir_pdf(archivo){
	$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_pdf",
	{
		archivo: archivo
	}, 
	function(data){
		console.log(data);
		//window.open(data);
		agregatab(data,"PDF","",0);
	}, "JSON");
}

function obtenerNotificaciones(tipo){
	$('#lista-loading2').show();
	$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_noticias",
		{
			tipo: "modificable"
		},
		function(data){
			console.log(data);
			if (data.error == 0) {
				var info = '';
				$('#lista-loading2').hide();
				$.each(data, function(index, arr){
					if (arr != 0) {
						var titulo = "<td title='Editar registro'><a href='index.php?c=edu&f=capturar_notificacion&id="+arr.id+"'>"+arr.titulo+"</a></td>";
						var archivo;
						if (arr.archivo != 0 && arr.archivo != '' && typeof arr.archivo != typeof undefined) {
							archivo = "<a target='_blank' href='notificaciones/"+arr.archivo+"'>"+arr.archivo+"</a>";
						} else {
							archivo = "-";
						}
						info += "<tr><td>"+arr.producto+"</td>"+titulo+"<td>"+linkify(arr.mensaje)+"</td><td>"+arr.fecha+"</td><td>"+arr.estatus+"</td><td>"+archivo+"</td></tr>";
					}
				});
				$('#tabla-noticias2 tbody').html(info);
			} else {
				$('#lista-loading2').hide();
				$('#tabla-noticias2 tfoot').html("<tr><td colspan='5'>"+data.error+"</td></tr>");
			}
		}, "JSON");
}

function obtener_notificacion(id){
	if (typeof id != typeof undefined) {
		$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_notificacion",
		{
			id: id
		},
		function(data){
			var mensaje = '';
			mensaje = data.mensaje;
			regex = /<br\s*[\/]?>/gi;
			mensaje = mensaje.replace(regex, "\n");
			$('#titulo').val(data.titulo);
			$('#mensaje').val(mensaje);
			$('#activa option[value='+data.activa+']').prop('selected', true);
			$('#producto option[value='+data.producto+']').prop('selected', true);
			if (data.archivo !=  0 && data.archivo != '' && typeof data.archivo != typeof undefined) {
				$('#subir_archivo').hide();
				$('#ver_pdf').fadeIn();
				$('#link_pdf').html(data.archivo);
				$('#link_pdf').attr('href', 'notificaciones/'+data.archivo);
			}
		}, "json");
	}
}

function instancia_actual(){
	$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_instancia",
		function(data){
			$('#inst').val(data.instancia);
			$('#tipo').val(data.tipo);
		}, "json");
}

function obtener_recordatorios(id_instancia){
	$('#lista-loading-recordatorios').show();
	$.post("../../modulos/cont/ajax.php?c=edu&f=obtener_eventos",
	{
		id: id_instancia
	},
	function(data){
		$('#recordatorios_container').removeClass('hidden');
		$('#lista-recordatorios').html('');
		if (data != '') {
			//Inicializamos la fecha actual y la fecha para el recordatorio.
			var dia_actual   = moment()
				, recordatorio = dia_actual.clone().add(-3, 'days');

			//Para cada array
			$.each(data, function(index, array){
				//Por cada registro dentro del array
				$.each(array, function(registro, valor){
					//Si el registro equivale a la fecha inicial
					if (registro == "startDate" || registro == 'endDate') {
						valor = moment(valor);
						//Si la fecha es despues o actual
						if((recordatorio <= valor && valor <= dia_actual)) {
							var html 
							$('#lista-recordatorios').append(
								"<li>"
									+"<h4>"+array['title']+"</h4>"+
									"<p> <b>Fecha inicio:</b> "+array['startDate']+"  ||  <b>Fecha fin:</b> "+array['endDate']+"</p>"+
								"</li>"
							);
						} //cierre segundo if
					}  //cierre primer if
				}); //cierre foreach de registros
			});  //cierre foreach de arreglos

		} else {
			$('#lista-recordatorios').html('<p id="not-found">No se encontraron recoradtorios.</p>');
		}
		$('#lista-loading-recordatorios').hide();
	}, "json");
}

function linkify(text){
	if (text) {
		text = text.replace(
			/((https?\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi,
			function(url){
				var full_url = url;
				if (!full_url.match('^https?:\/\/')) {
					full_url = 'http://' + full_url;
				}
				return '<a href="'+ full_url+'" target="_blank">'+url+'</a>';
			}
		);
	}
	return text;
}

function reemplazar_pdf(){
	if (confirm("¿Esta completamente seguro que desea quitar el pdf?\nEsta acción borrara el pdf que tiene almacenado actualmente.")) {
		pdf = $('#link_pdf').attr('href');
		id  = $('.title').attr('data-id');
		pdf_base = $('#link_pdf').html();;
		$.post("ajax.php?c=edu&f=quitar_pdf",
			{
				id: id,
				pdf: pdf
			},
			function(data){
				console.log(data);
				if (data.result) {
					alert("El pdf "+pdf_base+" ha sido borrado exitosamente.");					
				}
			}, "JSON");
		$('#ver_pdf').hide();
		$('#subir_archivo').fadeIn();
	}
}