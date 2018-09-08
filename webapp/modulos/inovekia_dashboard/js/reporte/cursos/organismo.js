$(document).ready(function() {

  $("#organismo").change(function(){
    obtenerInformacion($("#organismo").val());
  });

  obtenerInformacion(0);
});

function obtenerInformacion(organismo){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=reporte&f=cursosOrganismo",
      dataType: "json",
      data: { id_organismo: organismo },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){

          if(organismo == 0) $("#organismo").html(respuesta.f_organismo);
          $(".agregado").remove();

          $.each(respuesta.organismos, function(nombre, porcentajes){
            var base = $("#base").clone().removeAttr("id").removeClass("hidden").addClass("agregado");
            base.find("#base-nombre").removeAttr("id").html(nombre);
            base.find("#base-total").removeAttr("id").html(porcentajes.total);
            if(parseInt(porcentajes.total) > 0) {
              var no = base.find("#base-no").removeAttr("id").html(parseFloat(porcentajes.no).toFixed(2) + "%");
              definirColor(no, porcentajes.no);
              var visto = base.find("#base-visto").removeAttr("id").html(parseFloat(porcentajes.iniciado).toFixed(2) + "%");
              definirColor(visto, porcentajes.iniciado);
              var completo = base.find("#base-completo").removeAttr("id").html(parseFloat(porcentajes.completo).toFixed(2) + "%");
              definirColor(completo, porcentajes.completo);
            } else {
              var no = base.find("#base-no").removeAttr("id").html(porcentajes.no);
              var visto = base.find("#base-visto").removeAttr("id").html(porcentajes.iniciado);
              var completo = base.find("#base-completo").removeAttr("id").html(porcentajes.completo);
            }
            base.appendTo("#listado");
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

function definirColor(elemento, valor){
  if(valor <= 50){
    elemento.addClass("bg-danger").removeClass("bg-gray");
  }else if(valor >= 51 && valor <= 79){
    elemento.addClass("bg-warning").removeClass("bg-gray");
  }else if(valor >= 80 && valor <= 99){
    elemento.addClass("bg-primary").removeClass("bg-gray");
  }else{
    elemento.addClass("bg-success").removeClass("bg-gray");
  }
}