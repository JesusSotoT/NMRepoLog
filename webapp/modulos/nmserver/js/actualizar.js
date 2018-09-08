var catalogo = "actualizar";
var formulario = "frm";
var columnas_centradas = [ 3 ];

$(document).ready(function() {

  $("#btn_cargar").click(function(){
    subirActualizacion();
  });

});

function subirActualizacion() {
  if(validarFormulario(formulario)){
    var datosfrm = new FormData(document.getElementById(formulario));
    $.ajax({
      type: "POST",
        url: "../nmserver/ajax.php?c="+ catalogo +"&f=actualizar",
        dataType: "json",
        data: datosfrm,
        processData: false,
        contentType: false,
        success: function(respuesta){
          if(respuesta.status !== undefined && respuesta.status == true){
            mensajeIcono("success", "", "Informacion guardada correctamente", function(){
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