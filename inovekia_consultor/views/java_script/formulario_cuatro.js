$(document).ready(function(){
	$("#btn-enviar").click(function(){
		var datos = new FormData(document.getElementById("formulariocuatro"));
		datos.append('llave', token);
		datos.append('identificador', dispositivo);
		datos.append('empresario', empresario);
		$.ajax({
			beforeSend: function(){
			  		var mostrarAlert = android.mostrarAlert();
			},
			type: "POST",
			url: "../../ajax.php?c=formulario_cuatro&f=guardarFormularioCuatro",
			data: datos,
			dataType: "json",
			processData: false,
		  	contentType: false,
			success: function (data){
				if(data.status !== undefined && data.status === true) {
					mensajeIcono("success", "Guardado", "El cuestionario ha sido guardado correctamente", function(){
						$(document).find('input, textarea, button, select, checkbox').attr('disabled','disabled');
					});
				} else {
					mensajeIcono("error", "Un momento...", "No se ha podido procesar la peticion, intentalo nuevamente.<br><br>[" + data.mensaje + "]", function(){
						
					});
				}
			},
			error: function(data){
				mensajeIcono("error", "Un momento...", "No se ha podido procesar la peticion, intentalo nuevamente.", function(){
						
				});
			},
			complete: function(){
    			var quitarAlert = android.quitarAlert();
  			}
		});
	});
});