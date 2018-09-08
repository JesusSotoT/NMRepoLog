<style>
    .shadow {
    background-color: #d3d3d3;
  }
  .fila-base{ display: none; } /* fila base oculta */
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
    <h1>Vales Caja - Prestamos Operadores</h1>
    <body>

      <table id="table_listado" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Operador</th>
            <th>Prestamo</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      
    </body>
  </div>

    <!-- ADD-PRESTAMO -->
 <div class="modal fade" id="modal_form_prestamo" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title">Prestamos Personales</h3>
        </div>

        <div class="modal-body form">
        <form action="#" id="form_add_anticipo" class="form-horizontal">
          <input id="contador" class="form-control" type="hidden" value="0">
          <div class="form-body">
            
            <div class="form-group">
              <label class="control-label col-md-2">Vale NO.</label>
              <div class="col-md-2">
                <input id="novale" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Prestamo NO.</label>
              <div class="col-md-2">
                <input id="noprestamo" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Fecha</label>
              <div class="col-md-3">
                <input id="fecha" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Operador</label>
              <div class="col-md-9">
                <input id="idEmpleado" class="form-control" type="hidden">
               <input id="operador" class="form-control" type="text">
              </div>
            </div>

          <div class="form-group"> 
              <label class="control-label col-md-2">Forma Pago</label>
              <div class="col-md-4">
               <select id="idformapago" class="form-control"></select>
              </div>
              <label class="control-label col-md-2">Importe</label>
              <div class="col-md-4">
               <input id="importe" onkeypress="return isNumber(event)" class="form-control" type="text" value="0">
              </div>
          </div>

          <div class="form-group" > 
              <label class="control-label col-md-2">Cuenta</label>
              <div class="col-md-10">
                <select id="idcuenta" class="form-control"></select>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-3">Observaciones</label>
              <div class="col-md-9">
               <textarea id="observaciones" class="form-control" rows="2"></textarea>
              </div>
          </div>

          <div class="form-group col-md-12 shadow">
            <div class="form-group col-md-6 ">
                <label class="control-label col-md-12">Me comprometo a pagar en:</label>
                <label class="control-label col-md-4">Pagos:</label>
                <div class="col-md-8">
                 <input id="num_pagos" onkeypress="return isNumber(event)" class="form-control" type="text">
                </div>
                <label class="control-label col-md-4">De:</label>
                <div class="col-md-8">
                 <input id="pagos_de" onkeypress="return isNumber(event)" class="form-control" type="text">
                </div>
                <button type="button" onclick="addpago()">Agregar</button>
                <button type="button" onclick="recorrer()">Recorrer</button>
            </div>
            <div class="form-group col-md-6 ">
                <table id="tabla" class=" col-md-12">
                  <thead>
                    <tr>
                      <th>Pagos</th>
                      <th>De</th>
                      <th>Importe</th>
                      <th>...</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="fila-base">
                      <td id ="pagos0"></td>
                      <td id ="de0"></td>
                      <td id ="importe0"></td>
                      <td id ="del0"> <button id="btneliminar" onclick="deleteFila()" type="button">Eliminar</button></td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Suma</th>
                      <th></th>
                      <th id ="suma">0</th>
                      <th></th>
                    </tr>
                  </tfoot>  
                </table>

            </div>
          </div>



        </form>
        </div>

        <div class="modal-footer">
          <button type="button" onclick="savePrestamo()" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- ADD-PRESTAMO FIN-->


  <script>
  reloadtable();
  function reloadtable(){
    $.ajax({
      url: 'ajax.php?c=valescaja&f=listaOperadores',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data){
      console.log(data);
      var table = $('#table_listado').DataTable();
      table.clear().draw();
      var x ='';

      $.each(data, function(index, val) {
         x ='<tr>'+
                '<td>'+val.idEmpleado+'</td>'+
                '<td>'+val.operador+'</td>'+
                '<td><a class="btn btn-sm btn-default" title="Autorizar" onclick="addPrestamo('+val.idEmpleado+', \''+val.operador+'\')"><i class="glyphicon glyphicon-ok"></i></a></td>'
                +'</tr>';
                
                table.row.add($(x)).draw(); 
      });
    })
  }
  function addPrestamo(idEmpleado,operador){

      $(".filas").remove();
      $("#num_pagos").val('');
      $("#pagos_de").val('');
      $(".filadel").html('');
      $(".filadel").remove();
      $("#suma").text('0');
      $("#importe").val(0);
      $("#observaciones").val('');

    //conut = $("#contador").val();
    $("#contador").val(0);
    //alert(conut);

    $("#idEmpleado").val(idEmpleado);
    $("#modal_form_prestamo").modal('show');
    $("#novale").prop('readonly',true);
    $("#noprestamo").prop('readonly',true);
    $("#fecha").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $("#fecha").datepicker( "setDate" , hoy() );
    $("#operador").val(operador).prop('readonly',true);

    $.ajax({
      url: 'ajax.php?c=valescaja&f=listaCuentas',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      $.each(data, function(index, val) {

            $('#idcuenta').append('<option value="'+val.idbancaria+'">'+val.cuenta+'</option>');  
        
      });
    })
    $.ajax({
      url: 'ajax.php?c=valescaja&f=listaFormaspago',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      $.each(data, function(index, val) {
        $('#idformapago').append('<option value="'+val.idFormapago+'">'+val.nombre+'</option>');  
      });
    })
  }
  function addpago(){

    suma1 = $("#suma").text();
    suma1 = suma1 *1;
    conut = $("#contador").val();
    conut = conut * 1;
    conut1 = conut + 1;
    $("#contador").val(conut1);
      //alert(conut1);
    num_pagos = $("#num_pagos").val();
    pagos_de = $("#pagos_de").val();
    importe = num_pagos * pagos_de;
    suma2 = suma1 + importe;
    if(suma2 > $("#importe").val()){
      alert("Sobrepasa el importe");
    }else{
      if (num_pagos == '' || pagos_de == ''){
        alert("lleno los campos");
      }else{
        $('#tabla tbody tr:last').after('<tr class="filas" id = "fila'+conut1+'"><td id="pagos0" >'+num_pagos+'</td><td>'+pagos_de+'</td><td>'+importe+'</td><td id="count'+conut1+'"><button id="btndel'+conut1+'" onclick="deleteFila('+conut1+')" type="button">Eliminar</button></td></tr>');
        $("#pagos0").text(num_pagos);
        $("#de0").text(pagos_de);
        $("#importe0").text(importe);

        var i = 0;
        suma = 0;
        $("#tabla tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                    case 2: campo3 = $(this).text();
                            break;
                }
          
            })
            if(i > 1){
              campo3 = campo3*1;
              suma +=campo3;
            }  
        })
        $("#num_pagos").val('');
        $("#pagos_de").val('');
        $("#suma").text(suma);
      }// fin ELSE
    }// fin ELSE
  }
  function deleteFila(id){

    $("#fila"+id).remove();
    /// se elimina y se hace el proceso para la suma
        var i = 0;
        suma = 0;
        $("#tabla tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                    case 2: campo3 = $(this).text();
                            break;
                }
          
            })
            if(i > 1){
              campo3 = campo3*1;
              suma +=campo3;
            }  
            
        })
        $("#num_pagos").val('');
        $("#pagos_de").val('');
        $("#suma").text(suma);
    
  }
  function savePrestamo(){

    fecha = $("#fecha").val();
    idEmpleado = $("#idEmpleado").val();
    idFormapago = $("#idformapago").val();
    importe = $("#importe").val();
    idcuenta = $("#idcuenta").val();
    observaciones = $("#observaciones").val();
    suma = $("#suma").text();
    suma = suma*1;

    if((suma == importe) && importe > 1){
      
    /// AJAX PARA GUARDAR EL VALE Y PRESTAMO
    $.ajax({
            data : {fecha:fecha,idEmpleado:idEmpleado,idFormapago:idFormapago,importe:importe,idcuenta:idcuenta,observaciones:observaciones},
            url: 'ajax.php?c=valescaja&f=savePrestamoOperadores',
            type: 'POST',
            dataType: 'json',
          })
    .done(function(data){
          var idprestamo = data;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// PROCEDIMIENTO PARA FORMAR JSON DE PAGOS
    var i = 0;
    json = "";
    $("#tabla tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                }
                
                $(this).css("background-color", "#ECF8E0");
            })
            if(i > 1){
                //alert(campo1 + ' - ' + campo2 + ' - ' + campo3);
                json +='{"pagos":"'+campo1+'","pagos_de":"'+campo2+'"}'+',';
            }
            
        })
      /// AJAX PARA GUARDAR LOS PAGOS
      $.ajax({
        data : {json:json,idprestamo:idprestamo},
        url: 'ajax.php?c=valescaja&f=pagosjson',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data){
        $("#modal_form_prestamo").modal('hide');
        console.log(data);
      })    

////////////////////////////////////////////////////////////////////////////////////////////////////////////
    })

    }else{
      alert("Debe ser igual el importe que el la suma");
    }
    
  }
  function recorrer(){
    var i = 0;
    json = "";
    $("#tabla tbody tr").each(function (index) 
        {
            var campo1, campo2, campo3;
            
            i++;
            $(this).children("td").each(function (index2) 
            {
                switch (index2) 
                {
                    case 0: campo1 = $(this).text();
                            break;
                    case 1: campo2 = $(this).text();
                            break;
                }
                
                $(this).css("background-color", "#ECF8E0");
            })
            alert(i);
            if(i > 1){
                //alert(campo1 + ' - ' + campo2 + ' - ' + campo3);
                json +='{"pagos":"'+campo1+'","pagos_de":"'+campo2+'"}'+',';
            }
            
        })

          $.ajax({
            data : {json:json},
            url: 'ajax.php?c=valescaja&f=pagosjson',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data){
            console.log(data);
          })
  }

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
  </script>