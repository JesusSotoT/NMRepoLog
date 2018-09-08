<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Orden de Compra</title>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/compra.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js"></script>

  <!--  <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script> -->
    <script>
    $(document).ready(function() {
        calculaPrecios(2);

        $('#productosExtras').select2({
            width : '500px',
        });
        $('#fecha_entrega').datepicker({
           format: "yyyy-mm-dd",
           language: "es"
        });
        $("#codigo").focus();
        $("#codigo").keyup(function (e) {
            if (e.keyCode == 13) {
                var cod = $('#codigo').val();
                //cod = 75041748;

                var cantidad = $('#'+cod).val()
                if(cantidad ==''){
                    cantidad = 1;
                }else{
                    cantidad ++;
                }

                $('#'+cod).val(cantidad);

                $('#codigo').val('');
            }
        });
    });
    </script>
</head>
<body>
<div class="container well">
    <div class="row">
        <div class="col-sm-1">
            <button class="btn btn-default" onclick="back();"><i class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button>
        </div>
        <div class="col-sm-1">
            <?php 
                if($ordenBasicos[0]['activo']==4){
                    $xPer = 'disabled';
                    echo '<span class="label label-danger">Recibido</span>';
                }else{
                    $xPer = '';
                    echo '<span class="label label-warning">Editando</span>';   
                }
            ?>

            
        </div>
    </div>
    <div class="panel panel-default">
        <div class='panel-body'>
            <input type="hidden" id="proveedor" value="<?php echo $ordenBasicos[0]['idPrv']; ?>">
         <!--   <input type="hidden" id="almacen" value="1"> -->
            <input type="hidden" id="ordenCompra" value="<?php echo $ordenBasicos[0]['id']; ?>">
            <div class="row">
                <div class="col-sm-11"></div>
               <!-- <div class="col-sm-1">
                    <span class="label label-warning">Modificando</span>
                </div> -->
            </div>
            <div class="row">
                <div class="col-sm-3"><label>Proveedor:</label><p><?php echo $ordenBasicos[0]['razon_social']; ?></p></div>
                <div class="col-sm-3"><label>Orden de Compra:</label><p><?php echo $ordenBasicos[0]['id']; ?></p></div>
                <div class="col-sm-3"><label>Fecha Expedida:</label><p><?php echo $ordenBasicos[0]['fecha']; ?></p></div>
               <div class="col-sm-3">
                <label>Fecha Entrega:</label>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" id="fecha_entrega" placeholder="Fecha de Entrega" value="<?php echo $ordenBasicos[0]['fecha_entrega']; ?>" <?php echo $xPer; ?>/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
               </div>
            </div>
            <div class="row">
                <div class="col-sm-3"><label>Elaboro:</label><p><?php echo $ordenBasicos[0]['usuario']; ?></p></div>
                <div class="col-sm-3">
                    <label>Autorizo:</label>
                    <select id="autorizo" class="form-control" <?php echo $xPer; ?>>
                            <?php 

                            foreach ($usuarios as $key1 => $value1) {
                                if($value1['idempleado'] == $ordenBasicos[0]['autorizo']){
                                    echo '<option value="'.$value1['idempleado'].'" selected>'.$value1['usuario'].'</option>';
                                }else{
                                    echo '<option value="'.$value1['idempleado'].'">'.$value1['usuario'].'</option>';
                                }
                               
                            }

                            ?>                        
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>Almacen</label>
                        <select id="almacen" class="form-control" <?php echo $xPer; ?>>
                            <?php 

                            foreach ($almacenes as $key => $value) {
                                if($value['id'] == $ordenBasicos[0]['id_almacen']){
                                    echo '<option value="'.$value['id'].'" selected>'.$value['nombre'].'</option>';
                                }else{
                                    echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                                }
                               
                            }

                            ?>
                        </select>
       
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3"><label>Factura:</label><p></p></div>
                <div class="col-sm-3"><label>XML:</label><p></p></div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Productos Pedidos</div>
        <div class='panel-body'>
            <div class="row">
                <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                            <input class="form-control" placeholder="Codigo" type="text" id='codigo'>            
                        </div>
                </div>
                <div class="col-sm-1" style="display:none;" id="loadingPro">
                    <i class="fa fa-refresh fa-spin fa-3x"></i>
                </div>
                <div class="col-sm-1">
                    <?php 
                        if($ordenBasicos[0]['activo']==4){
                            echo '';
                        }else{
                            echo '<button type="button" class="btn btn-primary" onclick="adiciona();"><span class="glyphicon glyphicon-plus"></span> Agrega mas Productos</button>';   
                        }
                    ?>
                    
                </div>
            </div>
      
            <div class="row" id="prodExtras" style="display:none;">
                <div class="col-sm-6">
                    <label class="from-label">Producto</label>
                    <div>
                    <select name="" id="productosExtras">
                        <option value="0">-Selecciona-</option>
                        <?php 
                            foreach ($proveProduc as $key1 => $value1) {
                                echo '<option value="'.$value1['id'].'">'.$value1['codigo'].'/'.$value1['nombre'].'</option>';
                            }
                        ?>
                    </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label class="form-label">Cantidad</label>
                    <input type="text" class="form-control" id="cant"> 
                </div>
                <div class="col-sm-2">
                    <label class="form-label">Precio</label>
                    <input type="text" class="form-control" id="price">
                </div>
                <div class="col-sm-2">
                    <br>

                    <button type="button" class="btn btn-default" onclick="agregaMasProd();">Agrega</button>
                </div>
            </div>

            <br>

            <div class="container" style="width: 100%; height: 400px; overflow: auto; background-color:#fbfbf2;">
                <table id="proTable" class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Ordenado</th>
                           
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                        <tbody>
                            <?php
                                if($ordenBasicos[0]['activo']==4){
                                    $disble = "readonly";
                                }else{
                                    $disble = '';
                                }
                                foreach ($proPedidos as $key => $value) {
                                    echo '<tr idProducto="'.$value['id_producto'].'" id="x_'.$value['id_producto'].'">';
                                    echo '<td><span class="glyphicon glyphicon-remove" onclick="elimina('.$value['id_producto'].');"></span></td>';
                                    echo '<td>'.$value['codigo'].'</td>';
                                    echo '<td>'.$value['nombre'].'</td>';
                                    echo '<td><input id="cant_'.$value['id_producto'].'"type="text" value="'.$value['cantidad'].'" onkeyup="calculaPrecios(2)" '.$disble.'></td>';
                                   // echo '<td><input type="text" id="'.$value['codigo'].'"></td>';
                                    echo '<td>$<input type="text" id="cost_'.$value['id_producto'].'" value="'.$value['costo'].'" onkeyup="calculaPrecios(2)" '.$disble.'></td>';
                                    echo '<td><label id="subto_'.$value['id_producto'].'">$'.($value['costo'] * $value['cantidad']).'</label></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                </table>
            </div> 
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-3" id="impuestosDiv"></div>
                <div class="col-sm-3">
                    <div id="subtotalDiv" class="totalesDiv"></div>
                    <div id="totalDiv" class="totalesDiv"></div>
                    <!-- inputs donde se guarda el total y subtotal -->
                    <input type="hidden" id="inputSubTotal">
                    <input type="hidden" id="inputTotal">
                </div>
            </div>   
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                    <div class="col-sm-1">
                    <?php 
                        if($ordenBasicos[0]['activo']!=4){
                            echo '<div id="guardaDiv"><button type="button" class="btn btn-success" onclick="guardar(2);"><span class="glyphicon glyphicon-floppy-disk"></span>Guardar</button></div>
                        <div id="sded" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x"></i></div>';
                            }
                    ?> 
                        
                    </div>
                </div>
            </div>  
        </div>
    </div>  
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Exito</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-success">
            <strong>Exito!</strong> Se guardo correctamente la orde de compra.
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="redireccion();">Continuar</button>
        </div>
      </div>
    </div>
  </div>      
</div>    

   <div class="modal fade" id="modalMensajes" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
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
