$(document).ready(function() {
  $("#btn_guardar").click(function(){
    var datosfrm = new FormData(document.getElementById("frm"));
    if($("#excel").val() != ""){
      $.ajax({
        type: "POST",
          url: "../inovekia_dashboard/ajax.php?c=plantilla&f=procesar",
          dataType: "json",
          data: datosfrm,
          processData: false,
          contentType: false,
          success: function(respuesta){
            if(respuesta.status !== undefined && respuesta.status == true){
              mensajeIcono("success", "", "Información guardada correctamente", function(){
              });
            }else{
              mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
            }
          },
          error: function(error){
            mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
          }
      });
    } else {
      mensajeIcono("error", "Un momento...", "No ha seleccionado ningun archivo", function(){});
    }
  });

  $("#btn_guardar_usuarios").click(function(){
    var datosfrm = new FormData(document.getElementById("frm"));
    if($("#excel_usuarios").val() != ""){
      $.ajax({
        type: "POST",
          url: "../inovekia_dashboard/ajax.php?c=plantilla&f=agregarUsuario",
          dataType: "json",
          data: datosfrm,
          processData: false,
          contentType: false,
          success: function(respuesta){
            if(respuesta.status !== undefined && respuesta.status == true){
              mensajeIcono("success", "Información guardada", respuesta.mensaje,  function(){
              });
            }else{
              mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
            }
          },
          error: function(error){
            mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
          }
      });
    } else {
      mensajeIcono("error", "Un momento...", "No ha seleccionado ningun archivo", function(){});
    }
  });
});