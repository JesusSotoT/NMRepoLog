
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Pedidos</title>
    <!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


    <link rel="stylesheet" href="../../libraries/datepicker/css/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="../../libraries/jquery.min.js"></script>    

    <!--<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>-->
    <script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>

    <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/pedido/pedido.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script type="text/javascript" src="js/caja/punto_venta.js" ></script>
    <script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
    <!-- data -table -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script>
        $(document).ready(function() {
          window.table =     $('#tabliGriP').DataTable({
                                      language: {
                                      search: "Buscar:",
                                      lengthMenu:"",
                                      zeroRecords: "No hay datos.",
                                      infoEmpty: "No hay datos que mostrar.",
                                      info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                      paginate: {
                                          first:      "Primero",
                                          previous:   "Anterior",
                                          next:       "Siguiente",
                                          last:       "Último"
                                      },
                                   },
                                    aaSorting : [[0,'desc' ]]
                  }); 
          //gridPedidos();
          filtrosPedidos();

                $("#cotiClienteP, #estatus").select2({
                    width : "150px"
                });
                $("#cotiEmpleadoP").select2({
                     width : "100px"
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
  .abajo{
    position:absolute;
    bottom:-55px;;
}
</style>

</head>

<body>

<div class="container" style="width:100%" id="contenido">
  <div class="row">
    <div class="col-md-12">
      <h3 class="nmwatitles text-center">
        Pedidos de Clientes
      </h3>
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10" id="imp_cont">
          <section>
            <div class="row">
              <div class="col-md-3">
                <label>Cliente:</label>
                <select id="cotiClienteP">
                  <option value="0">--Selecciona un Cliente</option>
                </select>
              </div>
              <div class="col-md-3">
                <label>Empleado:</label>
                <select id="cotiEmpleadoP">
                  <option value="0">--Selecciona un Empleado--</option>
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
            <!--
              <div class="col-md-1">
              <label>Estatus:</label>
              </div>
              <div class="col-md-2">
                <select id="estatus">
                  <option value="-1">Todos</option>
                  <option value="0">Cancelado</option>
                  <option value="1">Activo</option>
                  <option value="2">Proceso</option>
                  <option value="3">Terminado</option>
                  <option value="4">En venta</option>
                  <option value="5">Vendido</option>

                </select>
              </div>  
              -->            
              <div class="col-md-3">
                <input type="hidden" id="lim">
                <input type="button" value="Buscar" onclick="buscaP();" class="btn btn-primary btnMenu">
              </div>              
              <div class="col-md-3">
                <input type="button" onclick="createnewP();" value="Levantar Pedido" class="btn btn-success btnMenu">
              </div>
              <div class="col-md-3">
                <input type="button" value="Mostrar mas" onclick="mostrarmas();" class="btn btn-primary btnMenu">
              </div>
            </div>
          </section>
          <section>
            <div class="row" id="gridPedi">
              <div class="col-md-12 col-sm-12 col-md-12 tablaResponsiva">
                <div class="table-responsive">
                  <table class="table display" id="tabliGriP">
                       <thead>
                           <tr>
                               <th class="nmcatalogbusquedatit">ID</th>
                               <th class="nmcatalogbusquedatit">Cliente</th>
                               <th class="nmcatalogbusquedatit">Cotizacion</th>
                               <th class="nmcatalogbusquedatit">Total</th>
                               <th class="nmcatalogbusquedatit">Empleado</th>
                               <th class="nmcatalogbusquedatit">Fecha</th>
                               <th class="nmcatalogbusquedatit">PDF</th>
                               <th class="nmcatalogbusquedatit">Estados</th>
                           </tr>
                       </thead>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado">Procesando...</label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>



</body> 
</html>