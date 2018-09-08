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
    <script src="js/comisiones.js"></script>
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
        //$('#tableSales').DataTable()
        graficar('','','');
        $('#tableSales').DataTable({
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
           <h3>Comisiones</h3>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    
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
                    <div class="col-sm-3">
                        <label>Empleado</label>
                        <select id="empleado" class="form-control">
                            <option value="0">-Seleccion un Empleado-</option>
                            <?php 
                                foreach ($ventasIndex['usuarios'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['idempleado'].'">'.$value2['nombre'].'</option>';
                                }
                            ?>                            

                        </select>
                    </div>
                </div>
                <div class="row">
                
                
                    <!-- <div class="col-sm-3">
                        <label>Ordenar</label>
                        <select id="orden" class="form-control">
                            <option value="day">Dia</option>
                            <option value="week">Semana</option>
                            <option value="month">Mes</option>
                            <option value="year">Año</option>
                        </select>
                    </div> -->
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
                                    <div id="contProducts" style="height:400px;overflow:none;" class="col-sm-12">
                                        <div class="col-sm-6" style="text-align: center;"><strong>Mejores vendedores</strong></div>
                                        <div class="col-sm-6" style="text-align: center;"><strong>Reporte de comisiones</strong></div>
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
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                    <?php 
                                        foreach ($ventasGrid['comisiones'] as $key => $value) {
                                                $total +=number_format($value['total_comision'],2,'.','');
                                        }

                    ?>
                        <label>Total:<label id="montoTotalLabel"><?php echo '$'.number_format($total,2); ?></label></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="overflow:auto;">
                            <div style="width:100% ">
                            <table class="table table-bordered table-hover" id="tableSales">
                                <thead>
                                    <tr>
                                        <th>Tipo Comisión</th>
                                        <th>Sucursal</th>
                                        <th>Vendedor</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Total Neto</th>
                                        <th>Comisión % </th>
                                        <th>Total Comision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($ventasGrid['comisiones'] as $key => $value) {

                                            if ( $value['tipo_comision'] == "1")
                                                $tipoComision = "Subtotal";
                                            else if ( $value['tipo_comision'] == "2" )
                                                $tipoComision = "Utilidad";
                                            else 
                                                $tipoComision = "Ninguna";
                                            $sucursal = $this->CajaModel->obtenerSucursal($value['sucursal']);
                                            $vendedor = $this->CajaModel->obtenerEmpleado($value['empleado']);
                                            $producto = $this->CajaModel->obtenerProducto($value['producto']);
                                            echo '<tr class="rows">';
                                            echo '<td>'.$tipoComision.'</td>';
                                            echo '<td>'.$sucursal['rows'][0]['nombre'].'</td>';
                                            echo '<td>'.$vendedor['rows'][0]['nombre'].'</td>';
                                            echo '<td>'.$producto['rows'][0]['nombre'].'</td>';
                                            echo '<td>'.$value['cantidad'].'</td>';
                                            echo '<td style="text-align: right;">$ '.number_format($value['total_neto'],2).'</td>';
                                            echo '<td>'.$value['porcentaje_comision'].'</td>';
                                            echo '<td style="text-align: right;">$ '.number_format($value['total_comision'],2).'</td>';
                                            echo '</tr>';
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