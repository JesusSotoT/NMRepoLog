var catalogo = "consultor";
var formulario = "frm";
var columnas_centradas = [ 2, 3 ];
var mapa;
var marcador;
var fecha;
var _empresario;
var _consultor;
var _consultor_inadem;

var tabla_modulo_empresarios;
var tabla_modulo_seguimientos;
var tabla_modulo_inadem;

$(document).ready(function() {

  	tabla_modulo_empresarios = $('#data_table_empresarios').DataTable({
      	language: {
        	url: 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      	},
      	"columnDefs": [
		    { className: "dt-body-center", "targets": [ 1, 2 ] }
		]
  	});

    tabla_modulo_seguimientos = $('#data_table_seguimientos').DataTable({
        language: {
          url: 'http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
        },
        "columnDefs": [
        { className: "dt-body-center", "targets": [ ] }
    ]
    });

    $('#fecha').datetimepicker({
      inline: true,
      sideBySide: true,
      locale: 'es',
      useCurrent: false
    }).on('dp.change', function(event){
      fecha = event.date.format('YYYY-MM-DD HH:mm:ss');
    });

    $("#btn_guardar_visita").click(function(){
      seleccionarVisita();
    });

    $("#btn_abrir_inadem").click(function(){
      
      if(validarEmail($("#txt_correo").val())){
        $.ajax({
          type: "POST",
            url: "../inovekia_dashboard/ajax.php?c=consultor&f=inadem",
            dataType: "json",
            data: { id_consultor: _consultor_inadem, email: $("#txt_correo").val() },
            success: function(respuesta){
              if(respuesta.status !== undefined && respuesta.status == true){
                var encabezados = "";
                $.each(respuesta.encabezados, function(index, encabezado){
                  encabezados += "<th>" + encabezado + "</th>";
                });
                $("#modal-inadem-encabezados").html(encabezados);
                tabla_modulo_inadem = $('#data_table_inadem').DataTable({
                    language: {
                      url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
                    },
                    "columnDefs": [
                    { className: "dt-body-center", "targets": [ ] }
                ]
                });
                for (var i = 0; i < respuesta.xml.length; i++) {
                  tabla_modulo_inadem.row.add(respuesta.xml[i]).draw();
                }
              }else{
                mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
              }
            },
            error: function(error){
              mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
            }
        });
      }else{
        alert("correo invalido");
      }

    });

});

function iniciaMapa()
{
  mapa = new google.maps.Map(document.getElementById('mapa'), {
        center: {lat: 23.7628153, lng: -101.8123433},
        zoom: 4
      });
  mapa.addListener('click', function(e) {
      if(marcador == null){
        marcador = new google.maps.Marker({
          position: e.latLng,
          map: mapa
        });  
      } else {
        marcador.setPosition(e.latLng);
      }
      mapa.panTo(e.latLng);
  });
}

function mostrarEmpresarios(identificador)
{
	$('#modal').off().on('shown.bs.modal', function () {
    popularTablaConParametros("empresario", { 'consultor': identificador }, tabla_modulo_empresarios);
	}).modal({backdrop: 'static', keyboard: false, show: true});
}

function mostrarInadem(consultor, correo)
{
  $("#txt_correo").val(correo);
  _consultor_inadem = consultor;
  $('#modal-inadem').off().on('shown.bs.modal', function () {
    if(tabla_modulo_inadem != null && tabla_modulo_inadem !== undefined) {
      tabla_modulo_inadem.destroy();
      tabla_modulo_inadem = null;
      $("#modal-inadem-encabezados").html("");
    }
  }).modal({backdrop: 'static', keyboard: false, show: true});
}

function seleccionarEmpresario(consultor, empresario){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=empresario&f=seleccionar",
      dataType: "json",
      data: { id_consultor: consultor, id_empresario: empresario },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){
          mensajeIcono("success", "", "Informacion guardada correctamente", function(){
            popularTablaConParametros("empresario", { 'consultor': consultor }, tabla_modulo_empresarios);
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

function eliminarEmpresario(consultor, empresario){
  $.ajax({
    type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=empresario&f=eliminar",
      dataType: "json",
      data: { id_consultor: consultor, id_empresario: empresario },
      success: function(respuesta){
        if(respuesta.status !== undefined && respuesta.status == true){
          mensajeIcono("success", "", "Informacion guardada correctamente", function(){
            popularTablaConParametros("empresario", { 'consultor': consultor }, tabla_modulo_empresarios);
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

function visita(consultor, empresario)
{
  _empresario = empresario;
  _consultor = consultor;
  $('#modal-visita').off().on('shown.bs.modal', function () {
    iniciaMapa();
  }).modal({backdrop: 'static', keyboard: false, show: true});
}

function seleccionarVisita(consultor, empresario){
  if(fecha !== undefined && marcador !== undefined){
    var tiempo = fecha.split(" ");
    $.ajax({
      type: "POST",
        url: "../inovekia_dashboard/ajax.php?c=empresario&f=visita",
        dataType: "json",
        data: { empresario: _empresario, consultor: _consultor, fecha: tiempo[0], hora: tiempo[1], latitud: marcador.getPosition().lat(), longitud: marcador.getPosition().lng() },
        success: function(respuesta){
          if(respuesta.status !== undefined && respuesta.status == true){
            mensajeIcono("success", "", "Informacion guardada correctamente", function(){});
          }else{
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
          }
        },
        error: function(error){
          mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
        }
    });
  } else {
    mensajeIcono("error", "Un momento...", "Debes seleccionar la fecha y hora de la visita, asi como la ubicación del lugar", function(){});
  }
}

function seguimiento(consultor, empresario)
{
  $('#modal-seguimiento').off().on('shown.bs.modal', function () {
    popularTablaConParametros("empresario", { 'consultor': consultor, 'empresario': empresario }, tabla_modulo_seguimientos, "seguimiento");
  }).modal({backdrop: 'static', keyboard: false, show: true});
}

function validarEmail(email) {
    var experesionRegular = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return experesionRegular.test(email);
}



