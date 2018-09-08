<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/facturas.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <!--Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--<script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

        <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>
   <script>
   $(document).ready(function() {

        $('#tableGrid').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
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

        $('#rfc').select2({ width: '100%' });
        $('#sucursal').select2({ width: '100%' });
        $('#empleado').select2({ width: '100%' });
   });
   </script>
<body>  
<div class="container well">
 <!--   <div class="row">
        <div class="col-sm-1">
             <button class="btn btn-primary" onclick="newClient();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Cliente</button>
        </div>
      
    </div> -->
    <div class="row">
     <!--   <div class="col-sm-12">
            <label>Total: <?php //echo $facturas['total']; ?></label>
        </div> -->
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                                <div class="row">
                <!--    <div class="col-sm-3">
                        <label>Cliente</label>
                        <select id="cliente" class="form-control">
                            <option value="0">-Seleccion un Cliente-</option>
                            <?php 
                                foreach ($clientes['clientes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['id'].'">'.$value2['nombre'].'</option>';
                                } 
                            ?>                            

                        </select>
                    </div> -->
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
                        <label>Empleado</label>
                        <select id="empleado" class="form-control">
                            <option value="0">-Selecciona-</option>
                            <?php 
                                foreach ($sucUsus['usu'] as $key => $value) {
                                    echo '<option value="'.$value['idempleado'].'">'.$value['usuario'].'</option>';
                                }

                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Sucursal</label>
                        <select id="sucursal" class="form-control">
                            <option value="0">-Selecciona-</option>
                            <?php 
                                foreach ($sucUsus['suc'] as $key => $value) {
                                    echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                                }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                <div class="col-sm-9"></div>
                    <div class="col-sm-1">
                        <div style="padding-top:36%;">
                            <button class="btn btn-default" onclick="buscarPendientes();">Buscar</button>

                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div style="padding-top:36%;">
                            <button class="btn btn-default" onclick="selAll();">Selecciona Todas</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
    <?php 
     function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
        }

    foreach ($facturas as $key => $value) {
       $azurian=base64_decode($value['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){ 
            $azurian=json_decode($azurian); 
        }
        $azurian = $this->object_to_array($azurian);
        //print_r($azurian);
    }
    ?>
        <div class="col-sm-12" style="overflow:auto;">
            <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th>Sucursal</th>
                        <th>Origen</th>
                    <!--    <th>Trakcid</th> -->
                        <th>Acciones</th>
                        <th><div id="btnaf" style="display:none;"><button class="btn btn-prymary" onclick="allfs();">Facturar Selecccionadas</button></div></th> 
                      <!--  <th>Autorizo</th>
                        <th>Estatus</th>
                        <th>Modificar</th> -->
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status="";
                        $ttt = '';
                        /* function object_to_array($data) {
                            if (is_array($data) || is_object($data)) {
                                $result = array();
                                foreach ($data as $key => $value) {
                                    $result[$key] = $this->object_to_array($value);
                                }
                                return $result;
                            }
                            return $data;
                            } */
                        foreach ($ventas as $key => $value) {
                            if($value['facturado']=='0'){

                                $azurian=base64_decode($value['cadenaOriginal']);
                                //echo $azurian;
                                $azurian = str_replace("\\", "", $azurian);
                                if($azurian!=''){ 
                                    $azurian=json_decode($azurian); 
                                } 
                                //echo $azurian;
                                 $azurian = $this->object_to_array($azurian);

                                if($value['origen'] == 1){ // comercial
                                    $origen = 'Envios';
                                }else{
                                    $origen = 'Caja';
                                }
                                $ttt = number_format($value['monto'],2);
                               /* if($azurian['Basicos']['version']=='3.2'){
                                  $ttt = $azurian['Basicos']['total'];
                                }else{
                                  $ttt = $azurian['Basicos']['Total'];
                                } */
                                
                                echo '<tr>';
                                echo '<td>'.$value['id_sale'].'</td>';
                                echo '<td>'.$value['fecha'].'</td>';
                                echo '<td>$'.$ttt.'</td>';
                                echo '<td>'.$value['cliente'].'</td>';
                                echo '<td>'.$value['empleado'].'</td>';
                                echo '<td>'.$value['sucursal'].'</td>';
                                echo '<td>'.$origen.'</td>';
                                //echo '<td>'.$value['factNum'].'</td>';
                                echo "<td><a class='btn btn-default' onclick='fact(".$value['id_sale'].");'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a></td>";
                                echo '<td><input class="checkPro" type="checkbox" name="prods" value="'.$value['id_sale'].'" id="check_'.$value['id'].'" onclick="aaa();"></td>';
                                echo '</tr>'; 
                            }
                        } 
                        ?>
                    </tbody>
                </table>
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

  <div class="modal fade" id="modalFact" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Facturar</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>RFC:</label>
                            <input type="hidden" id="idPendienteFact">
                        </div>
                        <div class="col-xs-10">
                            <select class="form-control" id="rfc">
                                <option value="0">XAXX010101000</option>
                                <?php 
                                    foreach ($rfcs as $key => $value) {
                                        echo '<option value="'.$value['id'].'">'.$value['rfc'].'/'.$value['razon_social'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div><br>
                    <?php 
                        if($configDatos[0]['seriesFactura']==1){
                            $xyz = '';
                        }else{
                            $xyz = 'style="display:none;"';
                        }
                    ?>
                    <div class="row" <?php echo $xyz; ?>>
                        <div class="col-xs-2">
                            <label>Serie:</label>
                        </div> 
                        <div class="col-xs-10">
                            <select id="seriesCfdi" class="form-control">
                                <?php 
                                    foreach ($seriesCfdi['series'] as $key => $value) {
                                        echo '<option value="'.$value['id'].'">'.$value['serie'].'</option>';
                                    } 

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <textarea id="obser" cols="30" rows="6" class="form-control"></textarea>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-xs-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-xs-6">
                            <button class="btn btn-primary btn-block" onclick="facturale();">Facturar</button>
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