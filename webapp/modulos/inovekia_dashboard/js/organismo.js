var catalogo = "organismo";
var formulario = "frm";
var columnas_centradas = [ 1 ];

var tabla_modulo_consultores;

$(document).ready(function() {

  	tabla_modulo_consultores = $('#data_table_consultores').DataTable({
      	language: {
        	url: 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      	},
      	"columnDefs": [
		    { className: "dt-body-center", "targets": [ 1 ] }
		]
  	});
});

function mostrarConsultores(identificador)
{
	$('#modal').off().on('shown.bs.modal', function () {
    popularTablaConParametros("consultor", { 'organismo': identificador }, tabla_modulo_consultores);
	}).modal({backdrop: 'static', keyboard: false, show: true});
}

function seleccionarConsultor(organismo, consultor){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=consultor&f=seleccionar",
      dataType: "json",
      data: { id_organismo: organismo, id_consultor: consultor },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){
          mensajeIcono("success", "", "Informacion guardada correctamente", function(){
            popularTablaConParametros("consultor", { 'organismo': organismo }, tabla_modulo_consultores);
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

function eliminarConsultor(organismo, consultor){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=consultor&f=eliminar",
      dataType: "json",
      data: { id_organismo: organismo, id_consultor: consultor },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){
          mensajeIcono("success", "", "Informacion guardada correctamente", function(){
			popularTablaConParametros("consultor", { 'organismo': organismo }, tabla_modulo_consultores);          
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