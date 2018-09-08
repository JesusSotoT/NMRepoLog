<style>
.divCenter{
  width: 50% !important;
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
    <h1>Vales Caja - Prestamos Diversos</h1>
    <body>
    <button class="btn btn-success" onclick="add_diverso()"><i class="glyphicon glyphicon-plus"></i> Nuevo Prestamo</button>
      
        <div id = "divadd"class="container divCenter text-center panel panel-default">
        <form class="form-horizontal">
          <h3>Vale de caja</h3>
            <div class="form-body">
              <div class="form-group">
                <label class="control-label col-md-3">Fecha Captura</label>
                <div class="col-md-9">
                  <input id="fechaCaptura" class="form-control col-md-3" type="text">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Operador</label>
                <div class="col-md-9">
                  <select id="idoperador" class="form-control"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Concepto</label>
                <div class="col-md-9">
                  <select id="idconcepto" class="form-control selectpicker" data-live-search="true"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Forma de Pago</label>
                <div class="col-md-9">
                  <select id="idformapago" class="form-control"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Cuenta</label>
                <div class="col-md-9">
                  <select id="idcuenta" class="form-control"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3">Observaciones</label>
                <div class="col-md-9">
                 <textarea id="observaciones" class="form-control" rows="2"></textarea>
                </div>
              </div>
              <div class="form-group"> 
                  <label class="control-label col-md-3">Cantidad</label>
                <div class="col-md-9">
                 <input id="cantidad" onkeypress="return isNumber(event)" class="form-control" type="text">
                </div>
            </div>
         </form> 
        </div> 
        <div class="text-right">
              <button class="btn btn-danger" onclick="close()" >Cerrar</button>
              <button class="btn btn-default" onclick="save_diverso()">Guardar</button>     
        </div>
    </body>
  </div>

  <script>


  $(function() {
      $("#divadd").hide();
  });

    function add_diverso(){
      $('#idoperador').html('');
      $('#idcuenta').html('');
      $('#idformapago').html('');
      $('#idconcepto').html('');
      $('#fechaCaptura').val('');
      $("#fechaCaptura").datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $("#fechaCaptura").datepicker( "setDate" , hoy() );


      /// OPERADORES
      $.ajax({
        url: 'ajax.php?c=valescaja&f=listaOperadores',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data){   
        $.each(data, function(index, val) {
          $('#idoperador').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>');
        });
      })
      /// CUENTAS
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
      /// FORMASPAGO
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
      /// CONCEPTO
      $.ajax({
        url: 'ajax.php?c=valescaja&f=listaConceptos',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        $.each(data, function(index, val) {
          $('#idconcepto').append('<option value="'+val.idconceptoprestamo+'">'+val.concepto+'</option>');  
        });
      })
        $("#divadd").show();
      }
      function close(){
        alert(2);
      }
      function save_diverso(){
        fechaCaptura = $("#fechaCaptura").val();
        idoperador = $("#idoperador").val();
        idconcepto = $("#idconcepto").val();
        idformapago = $("#idformapago").val();
        idcuenta = $("#idcuenta").val();
        observaciones = $("#observaciones").val();
        cantidad = $("#cantidad").val();

        $.ajax({
                data:{fechaCaptura:fechaCaptura,idoperador:idoperador,idconcepto:idconcepto,idformapago:idformapago,idcuenta:idcuenta,observaciones:observaciones,cantidad:cantidad},
                url: 'ajax.php?c=valescaja&f=saveDiverso',
                type: 'POST',
                dataType: 'json',
        })

      }
  </script>

 