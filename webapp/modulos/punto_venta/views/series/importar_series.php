<!--LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" /-->
<?php include('../../../../netwarelog/design/css.php');?>
<LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="../../../punto_venta/js/importar_productos.js"></script>

<link href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<style>

  .tit_tabla_buscar td
  {
    font-size:medium;
  }

  #logo_empresa /*Logo en pdf*/
  {
    display:none;
  }

  @media print
  {
    #imprimir,#filtros,#excel, #botones
    {
      display:none;
    }
    #logo_empresa
    {
      display:block;
    }
    .table-responsive{
      overflow-x: unset;
    }
    #imp_cont{
      width: 100% !important;
    }
  }
  .btnMenu{
      border-radius: 0; 
      width: 100%;
      margin-bottom: 0.3em;
      margin-top: 0.3em;
  }
  .row
  {
      margin-top: 0.5em !important;
  }
  h5, h4, h3{
      background-color: #eee;
      padding: 0.4em;
  }
  .modal-title{
    background-color: unset !important;
    padding: unset !important;
  }
  .nmwatitles, [id="title"] {
      padding: 8px 0 3px !important;
    background-color: unset !important;
  }
  .select2-container{
      width: 100% !important;
  }
  .select2-container .select2-choice{
      background-image: unset !important;
    height: 31px !important;
  }
  .twitter-typeahead{
    width: 100% !important;
  }
  .tablaResponsiva{
      max-width: 100vw !important; 
      display: inline-block;
  }
  .table tr, .table td{
    border: none !important;
  }
</style>
<!-- ///////////////////////////// -->   

<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-1">
        </div>
        <div class="col-md-6 col-sm-10">
            <h3 class="nmwatitles text-center">Importar Series (Excel)</h3>
            <div class="row">
                <div class="col-md-12">
                    <img src='../../img/xls_icon.gif'> <a href='plantilla_serie.xlsx'>Descarga la plantilla para las Series</a>
                    <label style='color: #FF0000; font-size:12px;'>(No elimine ninguna columna del formato. Los campos marcados con asterisco son obligatorios)</label>
                </div>
            </div>
            <hr>
            <?php
                $url = '../../funcionesBD/importar_series.php';
            ?>
            <form id="myForm" action=<?php echo $url; ?> method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <input type='hidden' value='subirArchivo' name='funcion'>
                        <input type="file" size="100" name="myfile">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8">
                        <input type="submit" value="Previsualizar" id="btnarchivo" class="btn btn-primary btnMenu">
                    </div>
                </div>
            </form>
            <hr>
            <div class="row" id='upload_div' style='display: table;' title='Subir'>
                <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                    <div id='tabla_div'>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-bottom:3em;">
                    <b>Nota:</b><br>
                    ·La lista de productos no debe rebasar los 900 elementos por carga.<br>
                    ·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
                    ·En los campos de Producto solo deben insertarse números y ningun otro caracter<br>
                    ·El lote no debe contener espacios ni caracteres especiales, solo números y letras<br>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

?>