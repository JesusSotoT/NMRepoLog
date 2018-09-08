var ruta_global = "https://www.netwarmonitor.com/clientes/inovekia/inovekia_consultor/public/scorm/";

var catalogo = "lms";
var formulario = "frm";
var columnas_centradas = [ 1 ];
var _curso;

$(document).ready(function() {

  tabla_modulo = $('#data_table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      },
      "columnDefs": [
        { className: "dt-body-center", "targets": columnas_centradas }
      ]
  });

  $("#empresario").change(function(){
    if(_curso == 1){
      $("#scorm").attr("src", ruta_global + "atencion_clientes/res/index.phtml?empresario=" + $("#empresario").val() + "&cliente=" + cliente);
    } else if(_curso == 2) {
      $("#scorm").attr("src", ruta_global + "operaciones_contables/res/index.phtml?empresario=" + $("#empresario").val() + "&cliente=" + cliente);
    } else if(_curso == 3) {
      $("#scorm").attr("src", ruta_global + "inventarios/res/index.phtml?empresario=" + $("#empresario").val() + "&cliente=" + cliente);
    } else if(_curso == 4) {
      $("#scorm").attr("src", ruta_global + "hardware_software/res/index.phtml?empresario=" + $("#empresario").val() + "&cliente=" + cliente);
    } else if(_curso == 5) {
      $("#scorm").attr("src", ruta_global + "capacidad_endeudamiento/res/index.phtml?empresario=" + $("#empresario").val() + "&cliente=" + cliente);
    }
  });

});

function mostrarLms(empleado, curso)
{
  _curso = curso;
	$('#modal').off().on('shown.bs.modal', function () {
    obtenerEmpresarios(empleado);
	}).on('hide.bs.modal', function () {
    $("#scorm").attr("src", "");
  }).modal({backdrop: 'static', keyboard: false, show: true});
}

function obtenerEmpresarios(empleado)
{
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=empresario&f=empresario",
      dataType: "json",
      data: { consultor: empleado, cliente: cliente },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){
          var html = "<option value='0'>Selecciona un empresario</option>";
          for (var i = 0; i < respuesta.registros.length; i++) {
            html += "<option value='"+ respuesta.registros[i]['id'] +"'>"+ respuesta.registros[i]['razon'] +"</option>";
          }
          $("#empresario").html(html);
          if(cliente != "inovekia"){
            if(_curso == 1){
              $("#scorm").attr("src", ruta_global + "atencion_clientes/res/index.phtml?empresario=-1" + "&cliente=" + cliente);
            } else if(_curso == 2) {
              $("#scorm").attr("src", ruta_global + "operaciones_contables/res/index.phtml?empresario=-1" + "&cliente=" + cliente);
            } else if(_curso == 3) {
              $("#scorm").attr("src", ruta_global + "inventarios/res/index.phtml?empresario=-1" + "&cliente=" + cliente);
            } else if(_curso == 4) {
              $("#scorm").attr("src", ruta_global + "hardware_software/res/index.phtml?empresario=-1" + "&cliente=" + cliente);
            } else if(_curso == 5) {
              $("#scorm").attr("src", ruta_global + "capacidad_endeudamiento/res/index.phtml?empresario=-1" + "&cliente=" + cliente);
            }
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
