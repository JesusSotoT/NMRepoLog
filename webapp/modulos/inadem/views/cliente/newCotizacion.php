
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Cotizaciones</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/cotizacion/cotizacion.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
    <script src="js/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<script>
    $(document).ready(function() {
        $.ajax({
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
                });

    $("#selectcliente").select2({
         width : "100px"
    });
    $("#selectProduct").select2({
         width : "150px"
    });   

        
    });

    
</script>


</head>

<body>
  
<div id="contenido" class="col-xs-12 container-fluid">
   <div class="nmwatitles">Cotizaciones a Clientes</div>

        <div class="panel panel-default">
          <div class="panel-heading">Cotizacion</div>
          <div class="panel-body">
            <div class="col-xs-9">
                <label>Cliente:</label>
                <select name="" id="selectcliente" >
                    <option value="0">--Selecciona un Cliente--</option>
                </select>
            </div><br>
            <div class="col-xs-12" style="padding-top:5%;">
                <div style="float:left;" class="col-xs-6">
                    <div style="float:left;">
                        <label>Productos:</label>
                    </div>
                    <div style="float:left;" class="col-xs-8">
                        <select name="" id="selectProduct" class="col-xs-8">
                            <option value="0">--Selecciona un Producto--</option>
                        </select>
                    </div>
                </div>
                <div style="float:left;" class="col-xs-6">
                    <label>Cantidad:</label>
                    <input type="text" id="cantidad" class="nminputtext">
                    <input type="button" value="Agregar" onclick="agrega();" class="nminputbutton_color2">
                </div>
            </div>
          </div>
            <div id="tableContainer" style="display:none;">
                <div class="col-xs-12">
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
                </table>
                <div class="col-xs-12">
                    <div class="col-xs-4" id="x"></div>
                    <div class="col-xs-4" id="divTaxes"></div>
                    <div class="col-xs-4" id="divTotal">
                        <div class="total">Total:$<label id="totalLab"></label></div>
                    </div>
                </div>
                <div>
                <div class="col-xs-12">
                    <div class="col-xs-6"><label>Observaciones:</label><textarea class="form-control" rows="3" id="observ"></textarea></div>
                    <div class="col-xs-6"><div style="padding-top:10%;"><input type="button" value="Cotizar y Enviar" onclick="send();" class="nminputbutton"></div></div>
                </div>
            </div>
        </div>
    </div>


<input type="button" value="Regresar" onclick="backbutton();" class="nminputbutton_color2">


</body> 
</html>