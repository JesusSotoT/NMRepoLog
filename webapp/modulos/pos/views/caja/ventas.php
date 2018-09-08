<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ventas</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/ventas.js"></script>
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

   <script>
   $(document).ready(function() {
function pad (n, length) {
    var  n = n.toString();
    while(n.length < length)
         n = "0" + n;
    return n;
}
        //$('#tableSales').DataTable()
var desde = new Date();
desde.setDate( desde.getDate() - 20 )
var month = pad(desde.getUTCMonth() + 1 , 2); //months from 1-12
var day = pad(desde.getUTCDate(), 2);
var year = pad(desde.getUTCFullYear(), 2);
desde = year + "-" + month + "-" + day;
$('#desde').val(desde);

var hasta = new Date();
var month = pad(hasta.getUTCMonth() + 1, 2); //months from 1-12
var day = pad(hasta.getUTCDate(), 2);
var year = pad(hasta.getUTCFullYear(), 2);
hasta = year + "-" + month + "-" + day;
$('#hasta').val(hasta);



        graficar('','','','','');
        $('#tableSales').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar",
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

        <?php if(!empty($objeto['id'])) {?>
                console.log("vent-<?php echo $objeto['id']?>");
                ventaDetalle(<?php echo $objeto['id']?>);
            
        <?php } ?>
        $('#cliente').select2();

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
           <h3>Ventas</h3>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Clientes</label>
                        <select  class="form-control" id="cliente">
                            <option value="0">-Seleccion un Cliente-</option>
                            <?php 
                           // print_r($ventasIndex['clientes']);
                                foreach ($ventasIndex['clientes'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Empleado</label>
                        <select id="empleado" class="form-control">
                            <option value="0">-Seleccion un Empleado-</option>
                            <?php 
                                foreach ($ventasIndex['usuarios'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['idempleado'].'">'.$value2['usuario'].'</option>';
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
    
                </div>
                <div class="row">
                <div class="col-sm-3">
                        <label>Sucursal</label>
                        <select id="idSucursal" class="form-control">
                        <option value="0">- Todas -</option>
                        <?php
                            foreach ($sucursales as $key => $value) {
                                echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                            }

                        ?>
                        </select>
                </div> 
                <div class="col-md-3">
                	<label>Via de contacto</label>
                	<select id="via_contacto" class="form-control">
                		<option value="">- Todas -</option><?php
                            foreach ($vias_contacto as $key => $value) {
                                echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                            } ?>
                        </select>
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
                                    <div id="contProducts" style="height:400px;overflow:auto;" class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12" align="center">
                                                <label>Venta Global por Periodo</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12" id="gLine" style="height:150px;"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6" align="center"><label>10 Productos mas vendidos</label></div>
                                            <div class="col-sm-6" align="center"><label>10 Productos menos vendidos</label></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6" id="gDonut" style="height:200px;">
                                                
                                            </div> 
                                            <div class="col-sm-6" id="gDonutMenos" style="height:200px;"></div>
                                        </div>
                                  
                                      <!--  <div class="col-sm-6" id="gDonut" style="height:100%;"></div>
                                        <div class="col-sm-6" id="gLine" style="height:100%;"></div> -->
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
                    <?php 
                                        foreach ($ventasGrid['ventas'] as $key => $value) {
                                            if($value['estatus']=='Activa'){
                                                $total +=number_format($value['monto'],2,'.','');
                                            } 
                                        }

                    ?>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-3">
                        <h4># de Transacciones</h4><label id="transacciones"><?php echo $ventasGrid['numVentas']; ?></label>
                    </div>
                    <div class="col-sm-3">
                        <h4>Ticket Promedio</h4><label id="ticketPromedio"><?php echo '$'.number_format(($total/$ventasGrid['numVentas']),2)?></label>
                    </div>
                    <div class="col-sm-2">
                        <h4>Venta Global</h4><label id="montoTotalLabel"><?php echo '$'.number_format($total,2); ?></label>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="overflow:auto;">
                            <div style="width:100% ">
                            <table class="table table-bordered table-hover" id="tableSales">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Tipo Doc.</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Empleado</th>
                                        <th>Sucursal</th>
                                        <th>Estatus</th>
                                        <th>Impuestos</th>
                                        <th>Monto</th>
                                        <th>FP</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($ventasGrid['ventas'] as $key => $value) {
                                            //print_r($value)
                                            if($value['estatus']=='Activa'){
                                                $estatus = '<span class="label label-success">Activa</span>';
                                            }else{
                                                $estatus = '<span class="label label-danger">Cancelada</span>';
                                            }

                                            if($value['documento']==1){
                                                if($value['cadenaOriginal']!=''){
                                                    $cad = base64_decode($value['cadenaOriginal']);
                                                    $cad = json_decode($cad);
                                                    $cad = $this->CajaModel->object_to_array($cad);
                                                    //print_r($cad);
                                                    $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['folio'].')</a>';
                                                    $xdoc = 'Ticket Facturado'.$xLink.'';
                                                }else{
                                                    $xdoc = 'Ticket';
                                                }
                                                $res = $this->CajaModel->ventasFact($value['folio']);
                                                if($res!=''){
                                                    $cad = base64_decode($value['cadenaOriginal']);
                                                    $cad = json_decode($cad);
                                                    $cad = $this->CajaModel->object_to_array($cad);  
                                                    if (isset($cad['Basicos']['version'])) {
                                                        $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['folio'].')</a>';
                                                        $xdoc = 'Ticket Facturado'.$xLink.'';
                                                    }else{
                                                        $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['Folio'].')</a>';
                                                        $xdoc = 'Ticket Facturado'.$xLink.'';
                                                    } 
                                                  
                                                }
                                                                                      
                                               /* if (isset($cad['Basicos']['version'])) {
                                                    $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['folio'].')</a>';
                                                    $xdoc = 'Ticket Facturado'.$xLink.'';
                                                }else{
                                                    $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['Folio'].')</a>';
                                                    $xdoc = 'Ticket Facturado'.$xLink.'';
                                                } */
                                            }else if($value['documento']==2){
                                                if($value['cadenaOriginal']!=''){
                                                    $cad = base64_decode($value['cadenaOriginal']);
                                                    $cad = json_decode($cad);
                                                    $cad = $this->CajaModel->object_to_array($cad);
                                                    //print_r($cad);
                                                    $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['folio'].')</a>';
                                                }else{
                                                    $xLink = '(Pendiente)';
                                                }   
                                                $xdoc = 'Factura '.$xLink;
                                            }else if($value['documento']==4){
                                                $xdoc = 'Recibo de pago';
                                            }else if($value['documento']==5){
                                                if($value['cadenaOriginal']!=''){
                                                    $cad = base64_decode($value['cadenaOriginal']);
                                                    $cad = json_decode($cad);
                                                    $cad = $this->CajaModel->object_to_array($cad);
                                                    //print_r($cad);
                                                    $xLink = '<a href="../../modulos/facturas/'.$cad['datosTimbrado']['UUID'].'.pdf" target="_blank">('.$cad['Basicos']['folio'].')</a>';
                                                }else{
                                                    $xLink = '(Pendiente)';
                                                }  
                                                $xdoc = 'Recibo de Honorarios'.$xLink;
                                            }

                                            if($value['devoluciones'] != 0)
                                                $estatus .= '<br> <span class="label label-warning" > Con devoluciones </span>';
                                            echo '<tr class="rows">';
                                            echo '<td>'.$value['folio'].'</td>';
                                            echo '<td>'.$xdoc.'</td>';
                                            echo '<td>'.$value['fecha'].'</td>';
                                            echo '<td>'.$value['cliente'].'</td>';
                                            echo '<td>'.$value['empleado'].'</td>';
                                            echo '<td>'.$value['sucursal'].'</td>';
                                            echo '<td>'.$estatus.'</td>';
                                            echo '<td>$'.number_format($value['iva'],2).'</td>';
                                            echo '<td>$'.number_format($value['monto'],2).'</td>';
                                            echo '<td>'.trim($value['formas_pago'],',').'</td>';
                                            echo '<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('.$value['folio'].');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                            echo '</tr>';
                                            $total +=$value['monto']; 
                                        }




                                    ?>
                                </tbody>
                     
                            </table>
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
                    <h4 class="modal-title" >Venta <span id="idFacPanel"></span></h4>
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
                                            <th>Importe</th>
                                            <th>Devolver</th>
                                            <th>Movimientos</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaVenta">
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
                        <div class="col-sm-3">
                            <textarea id="idComentarioDevolucion" class="form-control" rows="2" placeholder="Escribe un comentario"></textarea>
                        </div>
                        <div class="col-sm-2">
                            <select id="idAlmacenDevolucion" class="form-control">

                                <?php foreach ($selectAlmacenes as $key => $value) { ?>
                                <option value="<?= $value['id'] ?>"> <?= $value['nombre'] ?> </option>
                                <?php } ?>
                                <option value="0"> Merma </option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button id="devButton" class="btn btn-default" onclick="javascript:devolverVenta();"><i class="fa fa-undo" aria-hidden="true"></i> Devolver</button> 
                        </div>
                    
                        <div class="col-md-6 ">
                         <!--   <button class="btn btn-default" onclick="javascript:caja.devolver();"><i class="fa fa-undo" aria-hidden="true"></i> Devolver</button> -->
                            <button id="cancelButton" class="btn btn-danger" onclick="javascript:cancelaVenta();"> <i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button> 
                            <button class="btn btn-primary" onclick="javascript:imprime();"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button> 
                            <button class="btn btn-warning" onclick="javascript:$('#modalVentasDetalle').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
                          <!--  <button class="btn btn-info" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <!-- Modal de detalle de devoluciones  -->
    <div id='modalDetalleMovimientoDevolucion' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="width:90%">
            <div class="modal-content">
                <div class="modal-header modal-header-default">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="idMovimientoDevolucionProducto">  </h4>
                </div>
                <div class="modal-body">
                    <div style="height:400px;overflow:auto;">
                        <div class="row">
                            <div class="col-sm-12">
                                    <input id="idVentaHidden" type="hidden">
                                <table class="table table-bordered" id="tableSale">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>ID Almacen</th>
                                            <th>Comentario</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaMovimientosDevolucion">
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
                            <button class="btn btn-warning" onclick="javascript:$('#modalDetalleMovimientoDevolucion').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
                          <!--  <button class="btn btn-info" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
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


  
<!-- Modal series y lotes -->
<div class="modal fade" id="modalSeriesDevolucion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Devolución de productos con series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" producto="">
            <thead>
                <tr>
                    <th></th>
                    <th>Series</th>
                    
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="aceptarDevolucionSeries">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLotesDevolucion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Devolución de productos con lotes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" producto="">
            <thead>
                <tr>
                    <th></th>
                    <th>Cantidad</th>
                    <th>Lote</th>
                    
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="aceptarDevolucionLotes">Aceptar</button>
      </div>
    </div>
  </div>
</div>
    
</body>
</html>