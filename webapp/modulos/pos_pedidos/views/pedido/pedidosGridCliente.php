
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="js/pedido2.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="../../libraries/typeahead/typeahead.js"></script>
    <!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css"></script>
  
  <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>


    <script>
        $(document).ready(function() {

         // gridPedidos();
          //filtrosPedidos();
 $('#tabliGriP').DataTable({
                        dom: 'Bfrtip',
                        buttons: [ 'excel' ],
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
 
   

           $('#desde').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
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
</style>

</head>

<body>

<div class="container" style="width:100%" id="contenido">
  <div class="row">
    <div class="col-md-12">
      <h3 class="nmwatitles text-center">
        Pedidos
      </h3>
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10" id="imp_cont">
          <section>
            <div class="row">
            <div class="col-md-6">
            <input type="hidden" id="cotiClienteP" value="<?php echo $cliente['idCliente']; ?>">
              <label><?php

              echo $cliente['nombre']; ?></label>
            </div>
           <!--   <div class="col-md-3">
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
              </div> -->
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
              <div class="col-md-3">
                <input type="button" value="Buscar" onclick="buscaP();" class="btn btn-primary btnMenu">
              </div>
              <div class="col-md-3">
                <input type="button" onclick="createnewP();" value="Levantar Pedido" class="btn btn-success btnMenu">
              </div> 
              <div class="col-md-3">
              </div>
              <div class="col-md-3">
              </div>
            </div>
          </section>
          <section>
            <div class="row" id="gridPedi">
              <div class="col-md-12 col-sm-12 col-md-12 tablaResponsiva">
                <div class="table-responsive">
                <?php 
                  
                ?>
                  <table class="table display" id="tabliGriP">
                       <thead>
                           <tr>
                               <th class="nmcatalogbusquedatit">ID</th>
                               <th class="nmcatalogbusquedatit">Total</th>
                               <th class="nmcatalogbusquedatit">Fecha</th>
                               <th class="nmcatalogbusquedatit">PDF</th>
                               <th class="nmcatalogbusquedatit">Estados</th>
                           </tr>
                       </thead>
                       <tbody>
                         <?php 
                          foreach ($pedidos as $key => $value) {
                     
                     switch($value['status']) {
                        case '0':
                            $estado = '<a class="btn btn-danger">Cancelado</a>';
                            break;
                        case '1':
                            $estado = '<a class="btn btn-default">Activo</a>';
                            break;
                        case '2':
                            $estado = '<a class="btn btn-warning">Proceso</a>';
                            break;
                        case '3':
                            $estado = '<a  class="btn btn-primary">Terminado</a><a class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            break;
                        case '4':
                            $estado = '<a  class="btn btn-info">En Venta</a><a class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            break;  
                        case '5':
                            $estado = '<a class="btn btn-success">Vendido</a>';
                            break;

                    }



                              echo '<tr>';
                              echo '<td><a href="index.php?c=pedido&f=pedidoView2&pe='.$value['id'].'">'.$value['id'].'</td>';
                              echo '<td><a href="index.php?c=pedido&f=pedidoView2&pe='.$value['id'].'">$'.number_format($value['total'],2).'</td>';
                              echo '<td><a href="index.php?c=pedido&f=pedidoView2&pe='.$value['id'].'">'.$value['fecha'].'</td>';
                              echo '<td><a onclick="FunPdf('.$value['id'].');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
                              echo '<td>'.$estado.'</td>';
                              echo '</tr>';
                          }
                            
                         ?> 
                       </tbody>
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