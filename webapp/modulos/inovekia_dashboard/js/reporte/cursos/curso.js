$(document).ready(function() {

  $("#organismo").change(function(){
    obtenerInformacion($("#organismo").val(), 0, "", 0);
  });

  $("#consultor").change(function(){
    obtenerInformacion($("#organismo").val(), $("#consultor").val(), "", 0);
  });

  $("#folio").change(function(){
    obtenerInformacion($("#organismo").val(), $("#consultor").val(), $("#folio").val(), 0);
  });

  $("#empresario").change(function(){
    obtenerInformacion($("#organismo").val(), $("#consultor").val(), $("#folio").val(), $("#empresario").val());
  });

  obtenerInformacion(0, 0, "", 0);

});

function obtenerInformacion(organismo, consultor, folio, empresario){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=reporte&f=cursosCurso",
      dataType: "json",
      data: { id_organismo: organismo, id_consultor: consultor, folio: folio, id_empresario: empresario },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){

          if(organismo == 0){
            $("#organismo").html(respuesta.f_organismo);
            $("#consultor").html("<option value='0'>Selecciona un consultor</option>");
            $("#folio").html("<option value=''>Selecciona un folio</option>");
            $("#empresario").html("<option value=''>Selecciona un empresario</option>");
          } else {
            if(consultor == 0){
              $("#consultor").html(respuesta.f_consultor);
              if(folio == "") $("#folio").html(respuesta.f_folio);
              if(empresario == 0) $("#empresario").html(respuesta.f_empresario);
            }
          }
          $(".agregado").remove();
          
          $.each(respuesta.cursos, function(nombre, porcentajes){
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