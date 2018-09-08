var tabla_modulo;

$(document).ready(function() {

	tabla_modulo = $('#data_table').DataTable({
      	language: {
        	url: 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      	},
      	"columnDefs": [
		    { className: "dt-body-center", "targets": columnas_centradas }
		]
  	});

	$("#btn_guardar").click(function(){
		guardarRegistro(catalogo, formulario);
	});

	$("#agregar_registro").click(function(){
		limpiarFormulario(formulario);
		$('#modal').off().modal({backdrop: 'static'});
	});

	popularTabla(catalogo);

});

function popularTabla(catalogo, parametros, tabla, funcion = "grid")
{
	tabla_modulo.clear().draw();
	$.ajax({
		type: "POST",
	  	url: "../nmserver/ajax.php?c="+ catalogo +"&f=" + funcion,
	  	dataType: "json",
	  	data: { },
	  	success: function(respuesta){
	  		if(respuesta.status !== undefined && respuesta.status == true){
	  			for (var i = 0; i < respuesta.registros.length; i++) {
	  				tabla_modulo.row.add(respuesta.registros[i]).draw();
	  			}
	  		}else{
	  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
	  		}
	  	},
	  	error: function(error){
	  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
	  	}
	});
}

function popularTablaConParametros(catalogo, parametros, tabla, funcion = "grid")
{
	tabla.clear().draw();
	$.ajax({
		type: "POST",
	  	url: "../nmserver/ajax.php?c="+ catalogo +"&f=" + funcion,
	  	dataType: "json",
	  	data: parametros,
	  	success: function(respuesta){
	  		if(respuesta.status !== undefined && respuesta.status == true){
	  			for (var i = 0; i < respuesta.registros.length; i++) {
	  				tabla.row.add(respuesta.registros[i]).draw();
	  			}
	  		}else{
	  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
	  		}
	  	},
	  	error: function(error){
	  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
	  	}
	});
}

function editarRegistro(catalogo, identificador)
{
	$('#modal').off().on('shown.bs.modal', function () {
		$.ajax({
			type: "POST",
		  	url: path_servidor + "/ajax.php?c="+ catalogo +"&f=registro",
		  	dataType: "json",
		  	data: { id: identificador },
		  	success: function(respuesta){
		  		if(respuesta.status !== undefined && respuesta.status == true){
		  			$.each(respuesta.item, function(key, value){
		  				$("form [id=" + key + "]").val(value);
		  			});
		  		}else{
		  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
		  		}
		  	},
		  	error: function(error){
		  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
		  	}
		});
	}).modal({backdrop: 'static', keyboard: false, show: true});
}

function eliminarRegistro(catalogo, identificador)
{
	$.ajax({
		type: "POST",
	  	url: path_servidor + "/ajax.php?c="+ catalogo +"&f=estatus",
	  	dataType: "json",
	  	data: { id: identificador },
	  	success: function(respuesta){
	  		if(respuesta.status !== undefined && respuesta.status == true){
	  			mensajeIcono("success", "", "Informacion guardada correctamente", function(){
	  				popularTabla(catalogo);
	  			});
	  		}else{
	  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
	  		}
	  	},
	  	error: function(error){
	  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
	  	}
	});
}

function eliminarRegistroConParametros(catalogo, identificador, parametros, tabla)
{
	$.ajax({
		type: "POST",
	  	url: path_servidor + "/ajax.php?c="+ catalogo +"&f=estatus",
	  	dataType: "json",
	  	data: { id: identificador },
	  	success: function(respuesta){
	  		if(respuesta.status !== undefined && respuesta.status == true){
	  			mensajeIcono("success", "", "Informacion guardada correctamente", function(){
	  				if(typeof tabla !== "object") tabla = window[tabla];
	  				popularTablaConParametros(catalogo, parametros, tabla);
	  			});
	  		}else{
	  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
	  		}
	  	},
	  	error: function(error){
	  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
	  	}
	});
}

function guardarRegistro(catalogo, formulario)
{
	if(validarFormulario(formulario)){
		var datosfrm = new FormData(document.getElementById(formulario));
		$.ajax({
			type: "POST",
		  	url: path_servidor + "/ajax.php?c="+ catalogo +"&f=almacenar",
		  	dataType: "json",
		  	data: datosfrm,
		  	processData: false,
		  	contentType: false,
		  	success: function(respuesta){
		  		if(respuesta.status !== undefined && respuesta.status == true){
		  			mensajeIcono("success", "", "Informacion guardada correctamente", function(){
						$("#modal").modal('hide');
						limpiarFormulario(formulario);
	  					popularTabla(catalogo);
					});
		  		}else{
		  			mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
		  		}
		  	},
		  	error: function(error){
		  		mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
		  	}
		});
	}
}

function limpiarFormulario(formulario)
{
	$("#" + formulario)[0].reset();
	$("#id").val(0);
}
