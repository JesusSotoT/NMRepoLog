<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Sistema -->
    <script src="js/reporte.js"></script>
    
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
<!--    <script src="../../libraries/export_print/jquery-1.12.3.js"></script> -->

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
<!-- Notify  -->
	<script src="../../libraries/notify.js"></script>

   <script>
   $(document).ready(function() {
        //$('#tableSales').DataTable()
        //graficar('','','');
        /*$('#tableSales').DataTable({
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
        $('#cliente').select2(); */
        buscar();
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
<body>  
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Análisis de Ventas</h3>
        </div>
    </div> 
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                      <label>Sucursal</label>
                        <select id="idSucursal" class="form-control">
                        <option value="0">-Todas-</option>
                        <?php
                            foreach ($filtros['sucursales'] as $key => $value) {
                                echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                            } 

                        ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Desde</label>
                        <div id="datetimepicker1" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input id="desde" class="form-control" type="text" placeholder="Fecha de Entrega">
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <label>Hasta</label>
                        <div id="datetimepicker2" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>   
                            <input id="hasta" class="form-control" type="text" placeholder="Fecha de Entrega"> 
                        </div>
                        
                        
                        <div class="row"></div>
                    </div>
                    <div class="col-sm-3">
                        <label>Ordenar</label>
                        <select id="orden" class="form-control">
                            <option value="day">Dia</option>
                            <option value="week">Semana</option>
                            <option value="month">Mes</option>
                            <option value="year">Año</option>
                        </select>
                    </div>
    
                </div>
                <div class="row">
                <div class="col-sm-3">
                <!-- Elegir reporte -->  
                </div>
                    <div class="col-sm-5">
                        
                    </div>
                    <div class="col-sm-3">
                        <!-- Elegir reporte -->  
                        <label>Reporte</label>
                        <select id="reporte" class="form-control">
                        <!--    <option value="1">Ventas Totales</option> -->
                            <option value="2">Productos</option>
                            <option value="3">Formas de Pago</option>
                            <option value="4">Empleado</option>
                            <option value="5">Cliente</option>
                            <option value="6">Departamento</option>
                            <option value="7">Familia</option>
                            <option value="8">Linea</option>
                          <!--  <option value="4">Cliente</option> -->
                        </select>
                    </div>
                    <div class="col-sm-1"><br>
                        <button class="btn btn-default" onclick="buscar();">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel-group" id="accordion_graficas" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div hrefer class="panel-heading" id="heading_graficas" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_graficas" href="#tab_graficas" aria-controls="collapse_graficas" aria-expanded="true">
                                <h4 class="panel-title">
                                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                                    <strong>Graficas</strong> 
                                </h4>
                            </div>
                            <div id="tab_graficas" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_graficas" >
                                <div class="panel-body" >
                                    <div id="contProducts" style="height:300px;overflow:auto;" class="col-sm-12">
                                        <div class="col-sm-6" id="gDonut" style="height:100%;"></div>
                                        <div class="col-sm-6" id="gLine" style="height:100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
               <!-- <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-default btn-block" onclick="graficar();">Graficas</button>
                    </div>
                </div>
                <div class="row" style="display:none;" id="graficasDiv">
                    <div class="col-sm-12">
                       <div class="col-sm-6" id="gDonut" ></div>
                        <div class="col-sm-6" id="gLine"  style="height:250px;"></div> 
                    </div>                    
                </div> -->
                <div class="row">
                    <div class="col-sm-5"></div>
                    <div class="col-sm-3">
                    <?php 
                                        /*foreach ($ventasGrid['ventas'] as $key => $value) {
                                            if($value['estatus']=='Activa'){
                                                $total +=number_format($value['monto'],2,'.','');
                                            } 
                                        } */

                    ?>
                       <h4>Total:<h4 id="montoTotalLabel"></h4></h4> 
                    </div>
                    <div class="col-sm-4"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="overflow:auto;">
                            <div style="width:100% " id="tableDivCont">
                        <!--    <table class="table table-bordered table-hover" id="tableSales">
                             
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Empleado</th>
                                        <th>Sucursal</th>
                                        <th>Estatus</th>
                                        <th>Impuestos</th>
                                        <th>Monto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($ventasGrid['ventas'] as $key => $value) {
                                            if($value['estatus']=='Activa'){
                                                $estatus = '<span class="label label-success">Activa</span>';
                                            }else{
                                                $estatus = '<span class="label label-danger">Cancelada</span>';
                                            }
                                            echo '<tr class="rows">';
                                            echo '<td>'.$value['folio'].'</td>';
                                            echo '<td>'.$value['fecha'].'</td>';
                                            echo '<td>'.$value['cliente'].'</td>';
                                            echo '<td>'.$value['empleado'].'</td>';
                                            echo '<td>'.$value['sucursal'].'</td>';
                                            echo '<td>'.$estatus.'</td>';
                                            echo '<td>$'.number_format($value['iva'],2).'</td>';
                                            echo '<td>$'.number_format($value['monto'],2).'</td>';
                                            echo '<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('.$value['folio'].');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                            echo '</tr>';
                                            $total +=$value['monto']; 
                                        }




                                    ?>
                                </tbody>
                     
                            </table> -->
                            </div>
                        </div>    
                    </div>        
            </div>
        </div>    
    </div>
           <!-- Modal modalVentasDetalle -->
<!-- Modal de Ventas -->
    <div id='modalVentasDetalle' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-default">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="idFacPanel"></h4>
                </div>
                <div class="modal-body">
                    <div style="height:400px;overflow:auto;">
                        <div class="row">
                            <div class="col-sm-12">
                                    <input id="idVentaHidden" type="hidden">
                                <table class="table table-bordered" id="tableSale">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad</th>
                                            <th>Precio U.</th>
                                           <!-- <th>Descuento</th> -->
                                            <th>Impuestos</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                             
                            </div>
                        </div>  
                    <div class="row">
                    <div class="col-sm-6">
                        <div id="pay">
                            
                        </div>
                    </div>
                    <div class="col-sm-3" id="impuestosDiv"></div>
                    <div class="col-sm-3">
                        <div id="subtotalDiv" class="totalesDiv"></div>
                         <div id="ddiv" class="totalesDiv"></div>
                        <div id="totalDiv" class="totalesDiv"></div>
                        <!-- inputs donde se guarda el total y subtotal -->
                        <input type="hidden" id="inputSubTotal">
                        <input type="hidden" id="inputTotal">
                    </div>
                    </div>
                    </div>                  
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button class="btn btn-warning" onclick="cancelaVenta();"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button> 
                            <button class="btn btn-primary" onclick="imprime();"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button> 
                            <button class="btn btn-danger" onclick="javascript:$('#modalVentasDetalle').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
                        </div>
                    </div>
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
            <div align="center"><label id="lblMensajeEstado"></label></div>
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