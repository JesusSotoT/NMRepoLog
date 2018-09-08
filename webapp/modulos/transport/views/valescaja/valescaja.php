<style>
  
.table_listado th {
padding: 10px;
font-size: 13px;
background-color: #ffa90d;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #332a24;
border-bottom-color: #332a24;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}


</style>
<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css"> 
</head>

   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
  <!-- ch@isystem- Librerias raiz transport -->
  <!-- <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/funciones_gen.js" type="text/javascript"></script>

  <div class="container">
    
    <h1>Vales Caja - Autorizar Gastos de Viaje Generados por Trafico</h1>
    <hr style=" margin-right:1px; border-width:4px; border-color: #ffa90d;">
    <body>
      
      <table class="table">
    <tr>
     <th><button class="btn btn-lg btn-success" onclick="new_vale()"><i class="glyphicon glyphicon-plus" ></i> Nuevo Vale</button></th>
    
     <th><button class="btn btn-lg btn-wanrning" onclick="reloadtable(1)"><i class="glyphicon glyphicon-bell" ></i> Por Autorizar</button></th>
  
     <th><button class="btn btn-lg btn-info" onclick="reloadtable(2)"><i class="glyphicon glyphicon-list" ></i> Autorizados</button></th>

     <th><button class="btn btn-lg btn-danger" onclick="reloadtable(3)"><i class="glyphicon glyphicon-save" ></i> Cancelados</button></th>
    </tr>
     </table>



        <table id="table_listado" class="table table-striped table-bordered table_listado" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th style="width: 5%;">Gasto</th>
            <th id = "thcancel" style="width: 5%;">Cancelar</th>
            <th style="width: 5%;">Folio</th>
            <th style="width: 15%;">Fecha</th>
            <th style="width: 30%;">Operador</th>
            <th style="width: 5%;">OS</th>
            <th style="width: 10%;">Forma Pago</th>
            <th style="width: 5%;">Cantidad</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      
    </body>
  </div>

  <!-- ADD-FILTRO -->
 <div class="modal fade" id="modal_filtro" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Gastos de Operador</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_add_anticipo" class="form-horizontal">
          <!--<input id="idfolio1" class="form-control" type="hidden">-->
          <div class="form-body">
            <div class="form-group">
              <div id="divfechas">
                <label class="control-label col-md-2">Fecha del:</label>
                <input id="fecha1" class="control-input col-md-3" type="text">
                <label class="control-label col-md-2">Al:</label>
                <input id="fecha2" class="control-input col-md-3" type="text">
            </div>
                <input type="checkbox" id="checkfechas"> Sin Fechas
            </div>
          

            <div class="form-group">
              <label class="control-label col-md-2">Operador</label>
              <div class="col-md-9">
                <select id="operF" class="form-control" style="width: 400px;"></select>
              </div>
            </div>
            <div id="divos" class="form-group">
              <label class="control-label col-md-2">OS</label>
              <div class="col-md-9">
                <select id="osF" class="form-control" ></select>
              </div>
            </div>
            <button type="button" onclick="listarOS()" class="btn btn-primary">Buscar</button>
          </div>

            <div id="div_table_os" style="height:280px; overflow: scroll;">
              <table id="table_os" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>OS</th>
                    <th>Fecha</th>
                    <th>Operador</th>
                    <th>Unidad</th>
                    <th>Gasto</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- ADD-ANTICIPO FIN-->

  <!-- ADD-ANTICIPO -->
 <div class="modal fade" id="modal_form_anticipo" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Captura de Anticipos para Gastos de Viaje</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_add_anticipo" class="form-horizontal">
          <input id="idfolio" class="form-control" type="hidden">
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Fecha Captura</label>
              <div class="col-md-9">
                <input id="Anfecha" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Operador</label>
              <div class="col-md-9">
                <input id="Anoperdador" class="form-control" type="text">
                <input id="idEmpleado" type="hidden">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Orden de Servicio</label>
              <div class="col-md-9">
                <input id="AnOS" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Forma de Pago</label>
              <div class="col-md-9">
                <select id="Anformapago" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Referencia</label>
              <div class="col-md-9">
               <textarea id="Anreferencia" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="form-group"> 
                <label class="control-label col-md-3">Importe</label>
              <div class="col-md-9">
               <input id="Animporte" class="form-control" type="text">
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-3">Cuenta</label>
              <div class="col-md-9">
                <select id="Ancuenta" class="form-control"></select>
              </div>
            </div>
        </form>
        </div>

        <div class="modal-footer">
          <button id="btnAutorizar" type="button" onclick="updateAnticipo(0,2)" class="btn btn-default">Autorizar</button>
          <button id="btnGuardar" type="button" onclick="saveAnticipo(0,1)" class="btn btn-default">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- ADD-ANTICIPO FIN-->



  <script>
    $('#operF').select2();
$(document).ready(function() {
    //set initial state.
    

    $('#checkfechas').change(function() {
        if($(this).is(":checked")) {
            //var returnVal = 
            if (confirm("Esta seguro de quitar el filtro por fechas?")){
            $("#divfechas").hide();
            $("#fecha1").val('');
            $("#fecha2").val('');
            }else{
              $('#checkfechas').prop('checked',false);
            }
            //$(this).attr("checked", returnVal);

        }else{
          $("#divfechas").show();
          $("#fecha1").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
          $("#fecha2").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
          $("#fecha1").datepicker( "setDate" , hoy() );
          $("#fecha2").datepicker( "setDate" , hoy() );
        }     
    });
});

  reloadtable(1);
  function reloadtable(estatus){

    var estatus = estatus;
    $.ajax({
      data:{estatus:estatus},
      url: 'ajax.php?c=valescaja&f=listaVales',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data){
      //console.log(data);
      var table = $('#table_listado').DataTable();
      table.clear().draw();
      var x ='';

      $.each(data, function(index, val) {
         x ='<tr>'+
                '<td><a class="btn btn-sm btn-success btnautorizar" title="Autorizar" onclick="autorizarGasto('+val.folio+',1)"><i class="glyphicon glyphicon-ok"></i></a></td>'+
                '<td id = "tdcancel"><a class="btn btn-sm btn-danger btncancelar" title="Rechazar" onclick="cancelarAnticipo('+val.folio+',3)"><i class="glyphicon glyphicon-remove"></i></a></td>'+
                '<td>'+val.folio+'</td>'+
                '<td>'+val.fecha+'</td>'+
                '<td>'+val.operador+'</td>'+
                '<td>'+val.os+'</td>'+
                '<td>'+val.forma_pago+'</td>'+
                '<td>'+val.cantidad+'</td>'
                +'</tr>';
                
                table.row.add($(x)).draw(); 
      });
    })
  }

function autorizarGasto(folio,estatus){
  $('#Ancuenta').html('');
  $('#Anformapago').html('');
  $("#btnAutorizar").show();
  $("#btnGuardar").hide();
  //$('#idcuenta').append('<option value="0">Seleccione una Cuenta</option>');
    $.ajax({
      data: {folio:folio},
      url: 'ajax.php?c=valescaja&f=listaVales1',
      type: 'POST',
      dataType: 'json',
    })
      .done(function(data){
        $("#idfolio").val(folio).prop('readonly','readonly');
      $.each(data, function(index, val) {
        $("#Anfecha").val(val.fecha).prop('readonly','readonly');
        $("#Anoperdador").val(val.operador).prop('readonly','readonly');
        $("#AnOS").val(val.os).prop('readonly','readonly');
        $("#Anformapago").val(val.forma_pago).prop('readonly','readonly');
        $("#Anreferencia").val(val.referencia).prop('readonly','readonly');
        $("#Animporte").val(val.cantidad).prop('readonly','readonly');
        idbancaria = val.idbancaria;
        idFormapago = val.idFormapago;

        });

        $.ajax({
          data: {folio:folio,idFormapago:idFormapago},
          url: 'ajax.php?c=valescaja&f=listaFormaspago',
          type: 'POST',
          dataType: 'json',
        })
        .done(function(data) {
          $.each(data, function(index, val) {
            if(idFormapago == val.idFormapago){
                $('#Anformapago').append('<option selected="selected" value="'+val.idFormapago+'">'+val.nombre+'</option>');  
            }else{
                $('#Anformapago').append('<option value="'+val.idFormapago+'">'+val.nombre+'</option>');  
            } 
            });
          })

          $.ajax({
            data: {folio:folio},
            url: 'ajax.php?c=valescaja&f=listaCuentas',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
            $.each(data, function(index, val) {
              if(idbancaria == val.idbancaria){
                                 $('#Ancuenta').append('<option selected="selected" value="'+val.idbancaria+'">'+val.cuenta+'</option>');  
                              }else{
                                $('#Ancuenta').append('<option value="'+val.idbancaria+'">'+val.cuenta+'</option>');  
                              } 
            });
              $("#modal_form_anticipo").modal('show');
          })
    })
}
function saveAnticipo(folio,estatus){

  
  idOS = $("#AnOS").val();
  fechaCaptura = $("#Anfecha").val();
  idcuenta = $("#idcuenta").val();
  idEmpleado = $("#idEmpleado").val();
  idFormapago = $("#Anformapago").val();
  referencia = $("#Anreferencia").val();
  importe = $("#Animporte").val();
  idcuenta = $("#Ancuenta").val();
  
    $.ajax({
      data: {idOS:idOS,fechaCaptura:fechaCaptura,idcuenta:idcuenta,idEmpleado:idEmpleado,idFormapago:idFormapago,referencia:referencia,importe:importe},
      url: 'ajax.php?c=valescaja&f=saveAnticipo',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      reloadtable(1);
      $('#modal_form_anticipo').modal('hide');
      $('#modal_filtro').modal('hide');
      if(data == 1){
        //alert("Registro Exitoso");
      }else{
        alert("Registro Fallido");
      }
    })

}
function updateAnticipo(folio,estatus){

  folio = $("#idfolio").val();
  idcuenta = $("#idcuenta").val();

    $.ajax({
      data: {folio:folio,idcuenta:idcuenta,estatus:estatus},
      url: 'ajax.php?c=valescaja&f=updateAnticipo',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      reloadtable(1);
      $('#modal_form_anticipo').modal('hide');
      if(data == 1){
        //alert("Registro Exitoso");
      }else{
        alert("Registro Fallido");
      }
    })

}
function cancelarAnticipo(folio,estatus){

  if (confirm("Esta seguro que desea cancelar el vale?")){
    
    $.ajax({
      data: {folio:folio,estatus:estatus},
      url: 'ajax.php?c=valescaja&f=cancelAnticipo',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      reloadtable(1);
      $('#modal_form_anticipo').modal('hide');
      if(data == 1){
        //alert("Registro Exitoso");
      }else{
        alert("Registro Fallido");
      }
    })

  }else{
    return false;
  }

}
function cancelarGasto(folio){
  alert(folio);
}

function new_vale(){
  $('#divos').hide();
  $('#osF').html('');
  $('#operF').html('');
  //alert(hoy());
  $('#operF').append('<option value=0>Seleccione un Operador</option>');
  $('#osF').append('<option value=0>Seleccione una Orden de Servicio</option>');
  $('#checkfechas').prop('checked',false);
  $("#divfechas").show();
  $("#fecha1").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
  $("#fecha2").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
  $("#fecha1").datepicker( "setDate" , hoy() );
  $("#fecha2").datepicker( "setDate" , hoy() );

   $.ajax({
      url: 'ajax.php?c=valescaja&f=listaOperadores',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data){   
      $.each(data, function(index, val) {
        $('#operF').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>');
      });
    })
  $("#operF").unbind(); /// limpia todos los eventos o otra solucion es sacar la funcion de la funcion
  $("#operF").change(function () {
    $('#divos').show();
    $('#osF').html('');
    $('#osF').append('<option value=0>Seleccione una Orden de Servicio</option>');
    idEmpleado=$("#operF").val();
    $.ajax({
        data:{filtro:1,id:idEmpleado},
        url: 'ajax.php?c=valescaja&f=listaOS',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data){
      $.each(data, function(index, val) {
        $('#osF').append('<option value="'+val.idordenservicio+'">'+val.idordenservicio+'</option>');
      });
    })

  })

  $("#modal_filtro").modal('show');
}
function addAnticipo(idordenservicio){
  $('#Ancuenta').html('');
  $('#Anformapago').html('');
  $("#btnAutorizar").hide();
  $("#btnGuardar").show();
  //fecha1 = $("#fecha1").val();
  //fecha2 = $("#fecha2").val();
  //operF = $("#operF").val();
  //idordenservicio = $("#osF").val();
  idordenservicio =  idordenservicio;

  $("#Anreferencia").prop('readonly',false);
  $("#Animporte").prop('readonly',false);

  $("#Anfecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
  $("#Anfecha").datepicker( "setDate" , hoy() );
  $("#Animporte").val('');
  $("#Anreferencia").val('');
  

  $.ajax({
      data: {filtro:2,id:idordenservicio},
      url: 'ajax.php?c=valescaja&f=listaOS',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data){
      $.each(data, function(index, val) {
         $("#Anoperdador").val(val.operador).prop('readonly',true);
         $("#AnOS").val(val.idordenservicio).prop('readonly',true);
         $("#idEmpleado").val(val.idEmpleado);
      });
    })
    $.ajax({
      url: 'ajax.php?c=valescaja&f=listaFormaspago',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      $.each(data, function(index, val) {
        $('#Anformapago').append('<option value="'+val.idFormapago+'">'+val.nombre+'</option>');  
      });
          $.ajax({
            url: 'ajax.php?c=valescaja&f=listaCuentas',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
            $.each(data, function(index, val) {
              $('#Ancuenta').append('<option value="'+val.idbancaria+'">'+val.cuenta+'</option>');  
            });
            $("#modal_form_anticipo").modal('show');
          })
    })
 
}
function listarOS(){

  var table = $('#table_os').DataTable({
                                                                                                                "bPaginate": false,
                                                                                                                "bLengthChange": false,
                                                                                                                "bFilter": false,
                                                                                                                "bInfo": false,
                                                                                                                "bAutoWidth": false,
                                                                                                                "bDestroy": true /// permite destruit al volver a recargar
                                                                  });
  table.clear().draw();

  fecha1 = $("#fecha1").val();
  fecha2 = $("#fecha2").val();
  operF = $("#operF").val();
  idordenservicio = $("#osF").val();
  $.ajax({
      data: {fecha1:fecha1,fecha2:fecha2,operF:operF,idordenservicio:idordenservicio},
      url: 'ajax.php?c=valescaja&f=listaOS1', // 2 ES PRUEBA 1 BUENA
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data){
      if (data.length == 0){
        alert("Ningun resultado, modifique los Filtros!");
      }else{
        
        var x ='';

        $.each(data, function(index, val) {
           x ='<tr>'+
                  '<td>'+val.idordenservicio+'</td>'+
                  '<td>'+val.fecha+'</td>'+
                  '<td>'+val.operador+'</td>'+
                  '<td>'+val.idunidad+'</td>'+
                  '<td><a class="btn btn-sm btn-default" title="Gasto" onclick="addAnticipo('+val.idordenservicio+')"><i class="glyphicon glyphicon-ok"></i></a></td>'+
                  +'</tr>';
                  
                  table.row.add($(x)).draw(); 
        });
      }

    })
} 
  </script>