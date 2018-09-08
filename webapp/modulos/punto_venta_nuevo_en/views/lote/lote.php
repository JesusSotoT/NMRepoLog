
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Lotes</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/lote/lote.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <script>
        $(document).ready(function() {
            //pintaGrid();
            cargaSelects();
            
            $("#lote").select2({
             width : "150px"
            });
            $("#producto").select2({
                 width : "150px"
            }); 
            $("#organismo").select2({
                 width : "150px"
            }); 
            $.datepicker.regional['es'] = {
             closeText: 'Cerrar',
             prevText: '<Ant',
             nextText: 'Sig>',
             currentText: 'Hoy',
             monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
             monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
             dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
             dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
             dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
             weekHeader: 'Sm',
             dateFormat: 'dd/mm/yy',
             firstDay: 1,
             isRTL: false,
             showMonthAfterYear: false,
             yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $("#desde").datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd' 
        });
        $("#hasta").datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd' 
        });
        });
    </script>
<style type="text/css">
a:link {text-decoration:none;color:#000000;}
a:visited {text-decoration:none;color:#000000;}
a:active {text-decoration:none;color:#000000;}
a:hover {text-decoration:underline;color:#000000;}
</style>

<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
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
    #imprimir,#filtros,#excel,#email_icon, #botones
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
    h4, h3{
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

</head>

<body>

<div class="container" style="width:100%" id="contenido">
  <div class="row">
    <div class="col-md-12">
      <h3 class="nmwatitles text-center">
        Lotes y Series
      </h3>
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-md-3">
              <label>Lote:</label>
              <select id="lote">
                <option value="0">--Selecciona --</option>
                <?php 
              /*  foreach ($filtros['folio'] as $key => $value) {
                  echo '<option value="'.$value['folio_inadem'].'">'.$value['folio_inadem'].'</option>';
                } */

                ?>
              </select>
            </div>
            <div class="col-md-3">
              <label>Producto:</label>
              <select id="producto">
                <option value="0">--Selecciona --</option>
                <?php 
               /* foreach ($filtros['convocatoria'] as $key => $value) {
                  echo '<option value="'.$value['convocatoria'].'">'.$value['convocatoria'].'</option>';
                } */

                ?>
              </select>
            </div>
            <div class="col-md-3">
              <label>Fecha inicio:</label>
              <input type="text" id="desde" class="form-control">
            </div>
            <div class="col-md-3">
              <label>Fecha fin:</label>
              <input type="text" id="hasta" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-md-offset-9">
              <input type="button" value="Buscar" onclick="busca();" class="btn btn-primary btnMenu">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
              <div class="table-responsive" id="gridCoti">
                <table class="table display" id="Gridinadem">
                   <thead>
                       <tr>
                           <th class="nmcatalogbusquedatit">ID Lote</th>
                           <th class="nmcatalogbusquedatit">Orden de Compra</th>
                           <th class="nmcatalogbusquedatit">Producto</th>
                           <th class="nmcatalogbusquedatit">Cantidad</th>
                           <th class="nmcatalogbusquedatit">Fecha Recibido</th>
                           <th class="nmcatalogbusquedatit">Fecha de Caducidad</th>
                       </tr>
                   </thead>
                   <?php 
                    foreach ($lotes['grid'] as $key => $value) {
                        $rowsGrid .="<tr class='rowsTable'>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['idLote']."</a></td>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['idOrdeCom']."</a></td>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['nombre']."</a></td>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['cantidad']."</a></td>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['fecha_recibido']."</a></td>";
                        $rowsGrid .="<td><a href='ajax.php?c=lote&f=loteForm&pe=".$value['idLote']."'>".$value['fecha_caducidad]']."</a></td>";
                        $rowsGrid .="</tr>";
                    }
                    echo $rowsGrid; 
                   ?>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body> 
</html>