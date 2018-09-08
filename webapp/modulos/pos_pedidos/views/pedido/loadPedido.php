
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="js/pedido.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="../../libraries/typeahead/typeahead.js"></script>
    <!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {

      /*  $.ajax({
            url: 'ajax.php?c=cotizacion&f=getClient',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
                $.each(data, function(index, value) {
                    var optionCliente = $(document.createElement('option')).attr({'value': value.id}).html(value.nombre).appendTo($('#selectcliente'));
                });
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
                $.ajax({
                    url: 'ajax.php?c=cotizacion&f=getProduct',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(data) {
                    
                    $.each(data, function(index, value) {
                        var optionProduct = $(document.createElement('option')).attr({'value': value.idProducto}).html(value.nombre).appendTo($('#selectProduct'));
                     });
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                }); */

    $("#selectcliente").select2({
         width : "100px"
    });
    $("#selectProductP").select2({
         width : "150px"
    });   

        
    });

    
</script>

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

</head>

<body>

<div class="container" style="width:100%" id="contenido">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Pedidos a Clientes
                <input type="hidden" id="idPedidoHide" value="<?php echo $idPedido; ?>">
            </h3>
            <div class="row">
              <div class="col-md-1">
                </div>
              <div class="col-md-1">
                  <input type="button" value="Regresar" onclick="backbuttonP();" class="btn btn-warning btnMenu">
              </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">Pedido</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Cliente:</label>
                                    <select name="" id="selectcliente" >
                                        <option value="0">--Selecciona un Cliente--</option>
                                            <?php 
                                           
                                                foreach ($filtros['cliente'] as $key => $value) {
                                                  //echo 'cleinte='.$value['idCliente'].'<br>';
                                                    if($filtros['pedidoInfo'][0]['idCliente']==$value['id']){
                                                        echo '<option value='.$value['id'].' selected >'.$value['nombre'].'</option>';
                                                    }else{
                                                        echo '<option value='.$value['id'].'>'.$value['nombre'].'</option>';
                                                    }
                                                }  
                                            ?>
                                    </select>
                                </div>
                             <!--   <div class="col-md-3">
                                    <label>Productos:</label>
                                    <select name="" id="selectProductP">
                                        <option value="0">--Selecciona un Producto--</option>
                                        <?php 
                                         foreach ($filtros['productos'] as $key => $value) {
                                                echo '<option value='.$value['idProducto'].'>'.$value['nombre'].'</option>';
                                            }  
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Cantidad:</label>
                                    <input type="text" id="cantidad" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="button" value="Agregar" onclick="agregaP();" class="btn btn-primary btnMenu">
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <section id="tableContainer">
                        <h5>Productos pedidos</h5>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                                <div class="table-responsive">
                                    <table class="table" id="cotTable">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Producto</th>
                                                <th>Unidad</th>
                                                <th>Precio</th>
                                                <th>Importe</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $tablePr='';
                                                foreach ($_SESSION['pedido'] as $key => $value) {
                                                    if($key!='charges'){
                                                        $tablePr .='<tr class="cotTable xxx" id="prodFila_'.$value['idProducto'].'">';
                                                        $tablePr .='<td>'.$value['cantidad'].'</td>';
                                                        $tablePr .='<td>'.$value['nombre'].'</td>';
                                                        $tablePr .='<td>'.$value['unidad'].'</td>';
                                                        $tablePr .='<td>'.$value['precio'].'</td>';
                                                        $tablePr .='<td>'.$value['importe'].'</td>';
                                                        //$tablePr .='<td><span class="glyphicon glyphicon-minus-sign" onclick="deleteProP('.$value['idProducto'].');"></span></td>';
                                                        $tablePr .='<td></td>';
                                                        $tablePr .='</tr>';
                                                    }
                                                }
                                                echo $tablePr;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4" id="x">
                            </div>
                            <div class="col-md-4 col-sm-4" id="divTaxes">
                                <?php 
                                foreach ($_SESSION['pedido']['charges']['taxes'] as $key => $value) {
                                    if($value!='0' || $value!=0){
                                        echo '<div class="tax"><label>'.$key.':$</label>'.$value.'</div>';
                                    }
                                }
                                echo '<div class="tax"><label>Subtotal:$</label>'.$_SESSION['pedido']['charges']['sbtot'].'</div>';
                                ?>
                            </div>
                            <div class="col-md-4 col-sm-4" id="divTotal">
                                <div class="total">Total:$<label id="totalLab"><?php echo $_SESSION['pedido']['charges']['Tot']; ?></label></div>
                            </div>
                        </div>
                        <div class="row">
                        <!--    <div class="col-md-6 col-sm-6">
                                <label>Observaciones:</label>
                                <textarea class="form-control" rows="3" id="observ"><?php echo $filtros['pedidoInfo'][0]['observaciones'];  ?></textarea>
                            </div> -->
                            <div class="col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2">
                             <!--   <div id="sendBotton">
                                <input type="button" value="Guardar" onclick="sendP();" class="btn btn-primary btnMenu" style="margin-top: 4.5em;">
                                </div> -->
                            </div>
                            <div class="col-md-2 col-sm-2">
                            <!--    <input type="button" value="Regresar" onclick="backbuttonP();" class="btn btn-warning btnMenu" style="margin-top: 4.5em;"> -->
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