
<style>
  .tabla-convenios th {
padding: 10px;
font-size: 13px;
background-color: #0073ff;
color: #000000;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
</style>
<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
</head>

   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
  <!-- ch@isystem- Librerias raiz transport -->
  <!-- <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
  <script src="js/datatables.min.js" type="text/javascript"></script>

  <div class="container">
    <h1>Convenios</h1>
     <hr style=" 
   
    border-width: 3px;
    border-color: #0073ff;
    width: 100%;
           ""   >
  <br >
    <button class="btn btn-success" onclick="add_convenio()"><i class="glyphicon glyphicon-plus"></i> Nuevo Convenio</button>

        <table id="table_listado" class="table  table-bordered tabla-convenios" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Clave</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Municipio</th>
            <th>Capcidad</th>
            <th>Temperatura</th>
            <th>Descripcion</th>
            <th>Desc. Corta</th>
            <th>Precio Cliente</th>
            <th>Accion</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>


  <!-- Modal para Agregar Convenio-->
  <div class="modal fade" id="modal_form_conv" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv">Agregar Convenio</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="id0" name="id"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Cliente</label>
              <div class="col-md-9">
                <select id="cli" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Estado</label>
              <div class="col-md-9">
                <select id="est" class="form-control"></select>
              </div>
            </div>
            <div id = "ciudades" class="form-group">
              <label class="control-label col-md-3">Ciudad</label>
              <div class="col-md-9">
                <select id="ciu" class="form-control"></select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Capacidad</label>
              <div class="col-md-9">
                <select id="cap" class="form-control"></select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Temperatura</label>
              <div class="col-md-9">
                <select id="temp" class="form-control">
                    <option value="Seco">Seco</option>
                    <option value="Frio">Frio</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Descripcion</label>
              <div class="col-md-9">
                <input id="desc" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Desc. Corta</label>
              <div class="col-md-9">
                <select id="desccorta" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Cliente</label>
              <div class="col-md-9">
                <input id="precioclie" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Proveedor</label>
              <div class="col-md-9">
                <input id="preciopro" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Retencion</label>
              <div class="col-md-9">
                <input id="retencion" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision Fija</label>
              <div class="col-md-9">
                <input id="comisionfija" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision %</label>
              <div class="col-md-9">
                <input id="comisionporc" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Coordinacion</label>
              <div class="col-md-9">
                <select  id="coor" class="form-control">
                    <option value="-1">SI</option>
                    <option value="0">NO</option>
                </select>
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save_convenio()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


  <!-- Modal para Modificar Convenio-->
  <div class="modal fade" id="modal_form_conv_edit" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv_edit">Agregar Convenio</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio_edit" class="form-horizontal">
          <input type="hidden"  id="idconvenioE"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Cliente</label>
              <div class="col-md-9">
                <select id="cliE" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Estado</label>
              <div class="col-md-9">
                <select id="estE" class="form-control"></select>
              </div>
            </div>
            <div id="ciudadesE" class="form-group">
              <label class="control-label col-md-3">Ciudad</label>
              <div class="col-md-9">
                <select id="ciuE" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Capacidad</label>
              <div class="col-md-9">
                <select id="capE" class="form-control"></select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Temperatura</label>
              <div class="col-md-9">
                <select id="tempE" class="form-control">
                    <option value="Seco">Seco</option>
                    <option value="Frio">Frio</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Descripcion</label>
              <div class="col-md-9">
                <input id="descE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Desc. Corta</label>
              <div class="col-md-9">
                <select id="desccortaE" class="form-control"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Cliente</label>
              <div class="col-md-9">
                <input id="precioclieE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Precio Proveedor</label>
              <div class="col-md-9">
                <input id="precioproE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Retencion</label>
              <div class="col-md-9">
                <input id="retncionE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision Fija</label>
              <div class="col-md-9">
                <input id="comisionfijaE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Comision %</label>
              <div class="col-md-9">
                <input id="comisionporcE" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Coordinacion</label>
              <div class="col-md-9">
                <select  id="coordinacionE" class="form-control">
                    <option value="-1">SI</option>
                    <option value="0">NO</option>
                </select>
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="send_edit_canvenio()" class="btn btn-primary">Modificar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script type="text/javascript">

  $(function() {

                reload_table();
/*
                  $.ajax({
                  url: 'ajax.php?c=ordenservicio&f=listaConvenios',
                  type: 'POST',
                  dataType: 'json',
                  })
                    .done(function(data) {
                          //console.log(data); //devuelve los obj...
                        var table = $('#table_listado').DataTable();
                            //$('.filas').empty();
                            table.clear().draw();
                            var x ='';
                            $.each(data, function(index, val) {
                                x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                                '<td>'+val.idconvenio+'</td>'+
                                                '<td>'+val.nombre+'</td>'+
                                                '<td>'+val.estado+'</td>'+
                                                '<td>'+val.municipio+'</td>'+
                                                '<td>'+val.capacidad+'</td>'+
                                                '<td>'+val.temperatura+'</td>'+
                                                '<td>'+val.descripcion+'</td>'+
                                                '<td>'+val.concepto+'</td>'+
                                                '<td>'+val.precio_cliente+'</td>'+
                                                '<td><a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_convenio('+val.idconvenio+')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>'+
                                                '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_convenio('+val.idconvenio+')"><i class="glyphicon glyphicon-pencil"></i> Eliminar</a> </td>'
                                                +'</tr>';
                                    table.row.add($(x)).draw();
                            });
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
*/
  });

  function reload_table()
    {
                  $.ajax({
                  url: 'ajax.php?c=ordenservicio&f=listaConvenios',
                  type: 'POST',
                  dataType: 'json',
                  })
                    .done(function(data) {
                          //console.log(data); //devuelve los obj...
                        var table = $('#table_listado').DataTable();
                            //$('.filas').empty();
                            table.clear().draw();
                            var x ='';
                            $.each(data, function(index, val) {
                                x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                                '<td>'+val.idconvenio+'</td>'+
                                                '<td>'+val.nombre+'</td>'+
                                                '<td>'+val.estado+'</td>'+
                                                '<td>'+val.municipio+'</td>'+
                                                '<td>'+val.capacidad+'</td>'+
                                                '<td>'+val.temperatura+'</td>'+
                                                '<td>'+val.descripcion+'</td>'+
                                                '<td>'+val.concepto+'</td>'+
                                                '<td>'+val.precio_cliente+'</td>'+
                                                '<td><a class="btn btn-sm btn-primary" href="javascript:void()"title=" Editar"onclick="edit_convenio('+val.idconvenio+')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>'+
                                                '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_convenio('+val.idconvenio+')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a> </td>'
                                                +'</tr>';
                                    table.row.add($(x)).draw();
                            });
                    })
    }
function delete_convenio(id){

if(confirm('Seguro que desea eliminar el convenio?'))
      {
            $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idconvenioD:id},
            url: 'ajax.php?c=ordenservicio&f=deleteConvenio',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Fallo la elimindacion");
                }
            })
      }
}
  function add_convenio()
    {
          //$('#form_convenio')[0].reset(); // reset form on modals
          $('#modal_form_conv').modal('show'); // show bootstrap modal
          $('.modal-title_conv').text('Nuevo Convenio'); // Set Title to Bootstrap modal title
          $('#cap').empty(); // se limpian opciones anteriomente cargadas
          $('#est').empty();
          $('#desccorta').empty();
          $('#cli').html('<option selected="selected" value="0">Selecciona un Cliente</option>');
          $('#est').html('<option selected="selected" value="0">Selecciona un Estado</option>');
          $('#cap').html('<option selected="selected" value="0">Selecciona la Capacidad</option>');
          $('#ciudades').hide();
          //$('#cap').select2();
          //$('#est').select2();

          ///LISTAR CLIENTES
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaClientes',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#cli').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                });
            })
          /// LISTAR CAPACIDADES
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaCapacidad',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#cap').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                });
            })
          /// LISTAR ESTADOS
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaEstados',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              var idest = 0;
                $.each(data, function(index, val) {
                  $('#est').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                });
                $('#est').change(function()
                {
                  $('#ciudades').show(700);
                  $('#ciu').html('<option selected="selected" value="0">Selecciona una Ciudad</option>');
                    idest = $('#est').val();
                    if(idest > 0){
                      //alert(idest);
                        $.ajax({ /// LISTAR CIUDADES
                        data : {idest:idest},
                        url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                        type: 'POST',
                        dataType: 'json',
                      })
                      .done(function(data) {
                          $.each(data, function(index, val) {
                            $('#ciu').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                          });
                      })
                    }else{
                      alert("Seleccione un Estado");
                    }
                });
            })
          /// LISTAR DESC CORTA
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaDesccorta',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#desccorta').append('<option value="'+val.iddesccorta+'">'+val.concepto+'</option>');
                });
            })
    }
    function save_convenio(){
      cli = $("#cli").val();
      est = $("#est").val();
      ciu = $("#ciu").val();
      cap = $("#cap").val();
      temp = $("#temp").val();
      desc = $("#desc").val();
      desccorta = $("#desccorta").val();
      precioclie = $("#precioclie").val();
      preciopro = $("#preciopro").val();
      retencion = $("#retencion").val();
      comisionfija = $("#comisionfija").val();
      comisionporc = $("#comisionporc").val();
      coor = $("#coor").val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idsolicitud:0,cli:cli,est:est,ciu:ciu,cap:cap,temp:temp,desc:desc,desccorta:desccorta,precioclie:precioclie,
                    preciopro:preciopro,retencion:retencion,comisionfija:comisionfija,comisionporc:comisionporc,coor:coor},
            url: 'ajax.php?c=ordenservicio&f=saveConvenio',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                $('#modal_form_conv').modal('hide');
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })

    }

    function edit_convenio(id)
    {
      //alert(id);
      $('#form_convenio_edit')[0].reset(); // reset form on modals
      $('#modal_form_conv_edit').modal('show'); // show bootstrap modal
      $('.modal-title_conv_edit').text('Edtar Convenio'); // Set Title to Bootstrap modal title
      $('#cliE').empty(); // se limpian opciones anteriomente cargadas
      $('#capE').empty();
      $('#estE').empty();
      $('#ciuE').empty();
      $('#desccortaE').empty();
      $('#idconvenioE').val(id);
      var idcliente = 0;
      //LISTA DATOS DE CONVENIOS
      $.ajax({
        data : {id:id},
        url: 'ajax.php?c=ordenservicio&f=listaConveniosEdit',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        $.each(data, function(index, val) {
          /////// ids para select ///////
          idcliente = val.idcliente;
          idest = val.idestado;
          idmunicipio =  val.idmunicipio;
          idcapacidad = val.idcapacidadunidad;
          iddesccorta = val.iddesccorta;
          ///////////////////////////////
          $("#tempE").val(val.temperatura);
          $("#descE").val(val.descripcion);
          $("#precioclieE").val(val.precio_cliente);
          $("#precioproE").val(val.precio_proveedor);
          $("#retncionE").val(val.retencion);
          $("#comisionfijaE").val(val.comision);
          $("#comisionporcE").val(val.comision_porcentual);
          $("#coordinacionE").val(val.coordinacion);
          //////////////AJAX////////////////////////
                      ///LISTAR CLIENTES//////
                      $.ajax({
                        url: 'ajax.php?c=ordenservicio&f=listaClientes',
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                            $.each(data, function(index, val) {
                              if(idcliente == val.id){
                                 $('#cliE').append('<option selected="selected" value="'+val.id+'">'+val.nombre+'</option>');
                              }else{
                                $('#cliE').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                              }
                            });
                       })

                      /// LISTAR ESTADOS////////
                      $.ajax({
                        url: 'ajax.php?c=ordenservicio&f=listaEstados',
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                          var idestE = 0;
                            $.each(data, function(index, val) {
                              if(idest == val.idestado){
                                $('#estE').append('<option selected="selected" value="'+val.idestado+'">'+val.estado+'</option>');
                              }
                              else{
                                $('#estE').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                              }
                            });
                                idest = val.idestado
                                $.ajax({ /// LISTAR CIUDADES //// al cargar model
                                    data : {idest:idest},
                                    url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                                    type: 'POST',
                                    dataType: 'json',
                                  })
                                  .done(function(data) {
                                      $.each(data, function(index, val) {
                                        if(idmunicipio == val.idmunicipio){
                                          $('#ciuE').append('<option selected="selected" value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                                        }else{
                                          $('#ciuE').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                                        }
                                      });
                                  })
                             //// CHANGE /// COMBOBOX
                            $('#estE').change(function()
                            {
                                $('#ciuE').empty();
                                idest = $('#estE').val(); /// SE TOMA EL NUEVO ESTADO
                                $.ajax({ /// LISTAR CIUDADES //// al camnbiar de estado
                                    data : {idest:idest},
                                    url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                                    type: 'POST',
                                    dataType: 'json',
                                  })
                                  .done(function(data) {
                                      $.each(data, function(index, val) {
                                          $('#ciuE').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                                      });
                                  })
                            });
                        })
                      ///LISTAR CAPACIDADE//////
                      $.ajax({
                        url: 'ajax.php?c=ordenservicio&f=listaCapacidad',
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                            $.each(data, function(index, val) {
                              if(idcapacidad == val.idcapacidadunidad){
                                $('#capE').append('<option selected="selected" value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                              }else{
                                $('#capE').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                              }
                            });
                        })
                      ///LISTAR DESC CORTA//////
                      $.ajax({
                        url: 'ajax.php?c=ordenservicio&f=listaDesccorta',
                        type: 'POST',
                        dataType: 'json',
                      })
                        .done(function(data) {
                            $.each(data, function(index, val) {
                              if(iddesccorta == val.iddesccorta){
                                $('#desccortaE').append('<option selected="selected" value="'+val.iddesccorta+'">'+val.concepto+'</option>');
                              }else{
                                $('#desccortaE').append('<option value="'+val.iddesccorta+'">'+val.concepto+'</option>');
                              }
                            });
                        })
        });
      })
    }// FIN FUNCTION

function send_edit_canvenio(){

      idconvenioE = $("#idconvenioE").val();
      cli = $("#cliE").val();
      est = $("#estE").val();
      ciu = $("#ciuE").val();
      cap = $("#capE").val();
      temp = $("#tempE").val();
      desc = $("#descE").val();
      desccorta = $("#desccortaE").val();
      precioclie = $("#precioclieE").val();
      preciopro = $("#precioproE").val();
      retencion = $("#retencionE").val();
      comisionfija = $("#comisionfijaE").val();
      comisionporc = $("#comisionporcE").val();
      coor = $("#coordinacionE").val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idconvenioE:idconvenioE,cli:cli,est:est,ciu:ciu,cap:cap,temp:temp,desc:desc,desccorta:desccorta,precioclie:precioclie,
                    preciopro:preciopro,retencion:retencion,comisionfija:comisionfija,comisionporc:comisionporc,coor:coor},
            url: 'ajax.php?c=ordenservicio&f=editConvenio',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                $('#modal_form_conv_edit').modal('hide');
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })
}
</script>
</body>
