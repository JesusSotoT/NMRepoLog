
$(document).ready(function() {

  $("#reportar").click(function(){

    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=reporteTrimestral",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/views/plantilla/reporteTrimestral/reportePDF.zip");
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#reporte_micro_mercado").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=reporteMicroMercado",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){

        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);

      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#analisis_micro_mercado").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=analisisMicroMercado",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#foto_micro_empresario").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=fotosMicroEmpresario",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');

      }
    });
  });

  $("#analisis_financiero").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=analisisFinanciero",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#plan_accion").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=planAccion",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#ife").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=ife",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#carta_autoempleo").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=autoEmpleo",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#recibo_luz").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=reciboLuz",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#lista_raya_hombre").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=listaRayaHombre",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#ife_empleado_hombre").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=ifeEmpleadoHombre",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#lista_raya_mujer").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=listaRayaMujer",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#ife_empleado_mujer").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=ifeEmpleadoMujer",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#rfc_actualizada").click(function(){
    if($("#folio").val() == 0) { alert("Selecciona un folio"); return; }
    if($("#desde").val() == '') { alert("Selecciona una fecha desde"); return; }
    if($("#hasta").val() == '') { alert("Selecciona una fecha hasta"); return; }
    if($("#consultor").val() == 0) { alert("Selecciona un consultor"); return; }
    $('#modal_carga').modal('show');
    
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=rfcActualizada",
      data: {
        folio: $("#folio").val(),
        fecha_inicial: $("#desde").val(),
        fecha_final: $("#hasta").val(),
        consultor: $("#consultor").val()
      },
      success: function(respuesta){
        $('#modal_carga').modal('hide');
        window.open("../inovekia_dashboard/" + respuesta);
      },
      error: function(error){
        $('#modal_carga').modal('hide');
      }
    });
  });

  $("#desde, #hasta").datetimepicker({
    locale: 'es',
    useCurrent: false
  }).on('dp.change', function(event){
    $(this).val(event.date.format('YYYY-MM-DD'));
  });

  $("#folio").change(function(){
    $.ajax({
      type: "POST",
      url: "../inovekia_dashboard/ajax.php?c=trimestral&f=obtenerConsultorPorFolio",
      dataType: "json",
      data: {
        folio: $("#folio").val()
      },
      success: function(respuesta){
        var html = "<option value='0'>Selecciona un consultor</option>";
        $.each(respuesta.consultores, function(index, consultor){
          html += "<option value='"+ consultor.email +"'>"+ consultor.nombre + "</option>";
        });
        $("#consultor").html(html);
      },
      error: function(error){

      }
    });
  });

  ajax



});