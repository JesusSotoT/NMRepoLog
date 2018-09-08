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
		(function(){
			return function(){
				callback();
			};
		}())
	);
}

function mensajeIconoDecision(titulo, mensaje, negacion, afirmacion, callbackOk, callbackCancel = null)
{
	swal({
			title: titulo,
		  	text: mensaje,
		  	type: "warning",
		  	showCancelButton: true,
		  	cancelButtonColor: "#DD6B55",
		  	cancelButtonText: negacion,
		  	confirmButtonColor: "#8ED4F5",
		  	confirmButtonText: afirmacion,
		  	closeOnConfirm: false,
		  	closeOnCancel: (callbackCancel == null)
		},
		function(isConfirm){
			if(isConfirm){
				callbackOk();
			} else {
				if(callbackCancel != null) callbackCancel();
			}
		}
	);
}

function validarFormulario(formulario)
{
	//console.log($("#" + formulario)[0].elements);
	var campo;
	$.each($("#" + formulario)[0].elements, function(index, elemento){
		var pasar = false;
		if($("#" + formulario).find("#" + elemento.id).hasClass("archivo")){
			if(parseInt($("#" + formulario).find("#id").val().trim()) > 0){
				pasar = true;
			}
		}
		if(($("#" + formulario).find("#" + elemento.id).val() == null || $("#" + formulario).find("#" + elemento.id).val().trim() == "") && $("#" + formulario).find("#" + elemento.id).hasClass("requerido")){
			if(!pasar){
				campo = elemento.id;
				return false;
			}
		}
		if($("#" + formulario).find("#" + elemento.id).val().trim() == 0 && $("#" + formulario).find("#" + elemento.id).is("select") && $("#" + formulario).find("#" + elemento.id).hasClass("requerido")){
			if(!pasar){
				campo = elemento.id;
				return false;
			}
		}
	});
	if(campo != null){
		if(campo.indexOf("id_") != -1) campo = campo.replace("id_", "");
		if(campo.indexOf("_") != -1) campo = campo.replace("_", " ");
		if(campo.indexOf("imagen") != -1) campo = "imagen";
		mensajeIcono("error", "Un momento...", "El campo <b>" + campo + "</b> es requerido", function(){});
		return false;
	}
	return true;
}

function limpiarFormulario(formulario)
{
	$("#" + formulario)[0].reset();
	$("#id").val(0);
}