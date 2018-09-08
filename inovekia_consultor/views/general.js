var path_servidor = window.location.pathname.substr(0, window.location.pathname.lastIndexOf('/'));

var token = android.obtenerToken();
var dispositivo = android.obtenerDispositivo();
var empresario = android.obtenerEmpresario();

function mensajeIcono(tipo, titulo, mensaje, callback)
{
	swal({
			title: titulo,
		  	text: mensaje,
		  	type: tipo,
		  	showCancelButton: false,
		  	confirmButtonColor: (tipo == "success") ? "#8ED4F5" : "#DD6B55",
		  	confirmButtonText: "OK",
		  	closeOnConfirm: true,
		  	html: true
		},
		callback()
	);
}

function validarFormulario(formulario)
{
	var campo;
	$.each($("#" + formulario)[0].elements, function(index, elemento){
		if(($("#" + elemento.id).val().trim() == "" || $("#" + elemento.id).val().trim() == null) && $("#" + elemento.id).hasClass("requerido")){
			campo = elemento.id;
			return false;
		}
	});
	if(campo != null){
		mensajeIcono("error", "Un momento...", "Todos los campos con * son requeridos", function(){});
		return false;
	}
	return true;
}