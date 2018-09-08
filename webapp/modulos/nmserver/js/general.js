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
		var pasar = false;
		if($("#" + elemento.id).hasClass("archivo")){
			if(parseInt($("#id").val().trim()) > 0){
				pasar = true;
			}
		}
		if(($("#" + elemento.id).val().trim() == "" || $("#" + elemento.id).val().trim() == null) && $("#" + elemento.id).hasClass("requerido")){
			if(!pasar){
				campo = elemento.id;
				return false;
			}
		}
	});
	if(campo != null){
		mensajeIcono("error", "Un momento...", "El campo <b>" + campo + "</b> es requerido", function(){});
		return false;
	}
	return true;
}