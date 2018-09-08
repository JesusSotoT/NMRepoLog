<!-- CSS -->
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css"/>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="css/sucursal.css">
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<!-- JS -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
<script src="js/sucursal.js"></script>

<body onload='quitafooter()'>
<script>
  function reloadArbolFrame(){
    $('iframe').each(function() {
      this.contentWindow.location.reload(true);
    });
  }
</script>
 
  <!-- Main Wrapper / Content -->
  <div class="container well" style="height:100% !important;">
    <!-- Title -->
    <div class="row" style="margin-bottom: .5em !important;">
      <div class="col-xs-12 title-container">
        <h3 class="title" style="margin-top: .5em !important;">Almacenes</h3>
      </div>
    </div>  <!-- // Title -->
    <!-- Tabs -->
    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a data-toggle="tab" role="tab" href="#sucursal">Sucursales</a></li>
      <li role="presentation"><a data-toggle="tab" onclick="reloadArbolFrame()" role="tab" href="#arbol">Arbol de Almacenes</a></li>
    </ul> <!-- Tabs -->
    <!-- Content -->
    <div class="tab-content" style="height:77.75% !important;">
      <div id="sucursal" class="tab-pane fade in active" style="height:70em !important;">
        <iframe src="index.php?c=sucursal&f=verSucursales" style="height:100% !important;"></iframe>
      </div>
      <div id="arbol" class="tab-pane fade in" style="height:52em !important;">
        <iframe src="index.php?c=almacenes&f=index" id="arbolFrame" style="height:100%!important;"></iframe>
      </div>
    </div> <!-- // Content -->
  </div> <!-- // Main Wrapper -->
</body>