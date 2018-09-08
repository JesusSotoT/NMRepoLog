
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Punto de venta</title>
    <!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/typeahead.css" />
    <link rel="stylesheet" href="css/caja/caja.css" />

    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/caja/caja.js" ></script>
    <script type="text/javascript" src="js/typeahead.js" ></script>
    <script type="text/javascript" src="js/caja/punto_venta.js" ></script>
    <script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>

    <script>
    $(document).ready(function() {
        /*$('#tdmenu', window.parent.document).hide('slow', function() {
            $("#tdocultar", window.parent.document).trigger("click");
        });*/
    // Variable to store your files
    var files;

    // Add events
    $('input[type=file]').on('change', prepareUpload);

    $('#casioForm').on('submit', uploadFiles);

    $( document ).keydown(function(e) {
        keyCode = e.which;
        if (keyCode == 13 && (!$('#search-producto').is( ":focus" ) && $('.typeahead').is( ":focus" ) && !$('#iniciocaja').is(":focus")))
        {
            alert("El campo de busqueda no tenia el foco intentelo nuevamente.");
            $('#search-producto').focus();
        }
    });
        caja.init();
    });
    
    function bascula(str){
      $("#cantidad-producto").val(str);
  }
  function calculapropina(){
        var  percent = $('#propporcent').val();
        var total = $('#totalComanda').val();

        montoProp = total * percent;
        $('#txtPropina').val(montoProp.toFixed(2));
  }
      // Grab the files and set them to our variable
    function prepareUpload(event) {
      
      files = event.target.files;
      console.log(files);
    }
  function uploadFiles(event){
    

    event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // START A LOADING SPINNER HERE

    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    console.log(files);
    $.ajax({
        url: 'ajax.php?c=caja&f=readFile&files',
        type: 'POST',
        //data: {files:files,data:data},
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            console.log(data);
            if(typeof data.error === 'undefined')
            {
                // Success so call function to process the form
                submitForm(event, data);
            }
            else
            {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
            }
            caja.init();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
    function submitForm(event, data)
    {
        
        // Create a jQuery object from the form
        $form = $(event.target);
        
        // Serialize the form data
        var formData = $form.serialize();
        
        // You should sterilise the file names
        $.each(data.files, function(key, value)
        {
            formData = formData + '&filenames[]=' + value;
        });

        $.ajax({
            url: 'ajax.php?c=caja&f=readFile',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {   
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
                caja.init();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
            },
            complete: function()
            {
                // STOP LOADING SPINNER
            }
        });
    }
    function muestraCasio(){
            if ($('#casioDiv').hasClass('xy')){
                $('#casioDiv').hide("swing");
                $('#casioDiv').removeClass('xy');
            }else{
                $('#casioDiv').show("swing");
                $('#casioDiv').addClass('xy');
             }
     
    }
  </script>

  <style type="text/css">
    .typeahead.form-control {
        width: 100% !important;
    }
    .btnMenu{
        border-radius: 0; 
        width: 100%;
        margin-bottom: 1em;
        margin-top: 1em;
    }
    .row
    {
        margin-top: 1em !important;
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
    #s_cliente {
        width: 100% !important;
    }
    .twitter-typeahead{
        width: 100% !important;
    }
    .tt-dropdown-menu{
        width: 25% !important;
    }
    .imgDelete{
        margin-left: unset !important;
    }
    @media (max-width: 780px){
        .rTouch{
            height: 200px !important; 
            overflow: auto !important; 
            -webkit-overflow-scrolling: touch !important;
        }
    }
    .tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
  </style>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="nmwatitles text-center" style="padding: unset; padding-bottom: 10px !important;">
                    Caja
                </h3>
            </div>
        </div>
        <section id="divSuspendidas" style="display: none;">
            <form class="form-horizontal" role="form">
                <div clas="row">
                    <div class="col-md-12">
                        <label>Ventas suspendidas:</label>
                        <select id="s_cliente" class="form-control">
                            <option value="0" selected>Selecciona</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="divAccionesSuspender">
                    <div class="col-md-4">
                        <input class="btn btn-primary btnMenu" type="button" onclick="caja.cargarSuspendida();" value="Cargar">
                    </div>
                    <div class="col-md-4">
                        <input id="sselimina" class="btn btn-danger btnMenu" type="button" onclick="caja.eliminarSuspendida();" value="Eliminar">
                    </div>
                    <div class="col-md-4">
                        <input id="ssnueva" class="btn btn-success btnMenu" type="button" onclick="caja.cancelarCaja();" value="Realizar nueva venta">
                    </div>
                </div>
            </form>
        </section>
        <?php
        if($enabled==""){
        ?>
        <section id="lectorBascula">
            <div class="row">
                <div class="col-md-12">
                    <label>Bascula:</label>
                </div>
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="325" height="75">
                    <param name="movie" value="swf/ba249c4353cfb827054d106666737da00.swf" />
                    <param name="quality" value="high" />
                    <param name="AllowScriptAccess" value="always"/>
                    <param name="FlashVars" value="v=<?php echo $vars; ?>" />
                    <embed src="swf/ba249c4353cfb827054d106666737da00.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="325" height="75" FlashVars="v=<?php echo $vars; ?>"></embed>
                </object>
            </div>
        </section>
        <?php
        }
        ?>
        <section>
            <div class="row" style="display:none;">
                <div class="col-md-4">
                    <button class="btn btn-success btnMenu" onclick="muestraCasio();">Casio</button>
                </div>
            </div>
            <form action="ajax.php?c=caja&f=readFile" method="post" enctype="multipart/form-data" id="casioForm">
                <div class="row" id="casioDiv" style="display:none;">
                    <div class="col-md-6">
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-default btnMenu">Carga archivo trn</button>
                    </div>
                </div>
            </form>
        </section>
        <section>
            <form>
                <input type="hidden" value="" id="codigo">
                <input type="hidden" value="" id="propina">
                <input type="hidden" id="hidencliente-caja" value="">
                <!-- Aqui se Carga el id del pedido en dado caso de vener de pedidos -->
                <input type="hidden" id="idPedido">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cantidad:</label>
                            <input type="text" class="typeahead form-control " onkeypress="return caja.isNumberKey(event)" id="cantidad-producto" data-provide="typeahead" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Articulo:</label>
                            <input type="text" class="typeahead form-control " id="search-producto" placeholder="Ingrese c&oacute;digo o descripci&oacute;n" data-provide="typeahead" onkeypress="caja.busquedaXcodigo(event)">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cliente:</label>
                            <input type="text" class="typeahead form-control " id="cliente-caja" placeholder="Publico en General" data-provide="typeahead">
                        </div>
                    </div>
                    <input id="hidensearch-producto" type="hidden">
                    <input id="codigo" type="hidden" value="">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Documento:</label>
                            <select class="form-control" onchange="caja.tipoDocumento(this.value);" id="documento">
                                <option value="1">Ticket</option>
                                <option value="2">Factura</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="selectrfc" style="display: none;">
                            <label>RFC:</label>
                            <select class="form-control" id="rfc">
                                <option value="0">XAXX010101000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label></label>
                        <div class="form-group" id="selectConsumo" style="display: none;">
                            <select class="form-control" id="consumo" >
                                <option value="0">Normal</option>
                                <option value="1">Consumo</option>
                            </select>
                        </div>
                    </div>
                    <input id="hidensearch-producto" type="hidden">
                    <input id="codigo" type="hidden" value="">
                </div>
            </form>
        </section>
        <section id="contenedorGeneral">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                    <div class="table-responsive" >          
                        <table class="table table-bordered" id="contenedorGeneralTabla">
                            <thead>
                                <tr>
                                    <th>Acci&oacute;n</th>
                                    <th>C&oacute;digo</th>
                                    <th>Descripci&oacute;n</th>
                                    <th>Cantidad</th>
                                    <th>Precio U.</th>
                                    <th>Impuestos</th>
                                    <th>Descuento</th>
                                    <th>Sub.Total</th>
                                </tr>
                            </thead>
                            <tbody id="contenedorGeneralTablaCuerpo">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <section class="impuestosCaja" style="display:none; padding: 1em !important;">
        </section>
    </div>

    <div id="caja-dialog"></div>
    <div id="caja-dialog-confirmacion"></div>
    <div id='susp-hids'></div>

    <div id='modalPropina' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">&iquest;Deseas dar Propina&quest;</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Se recomienda el siguiente % de propina a partir del total de su compra:</label>
                            <select  id="propporcent" onchange="calculapropina();" class="form-control">
                                <option value="0.1">1</option>
                                <option value="0.2">2</option>
                                <option value="0.3">3</option>
                                <option value="0.4">4</option>
                                <option value="0.5">5</option>
                                <option value="0.6">6</option>
                                <option value="0.7">7</option>
                                <option value="0.8">8</option>
                                <option value="0.9">9</option>
                                <option value="0.10" selected>10</option>
                                <option value="0.11">11</option>
                                <option value="0.12">12</option>
                                <option value="0.13">13</option>
                                <option value="0.14">14</option>
                                <option value="0.15">15</option>
                                <option value="0.16">16</option>
                                <option value="0.17">17</option>
                                <option value="0.18">18</option>
                                <option value="0.19">19</option>
                                <option value="0.20">20</option>
                                <option value="0.21">21</option>
                                <option value="0.22">22</option>
                                <option value="0.23">23</option>
                                <option value="0.24">24</option>
                                <option value="0.25">25</option>
                                <option value="0.26">26</option>
                                <option value="0.27">27</option>
                                <option value="0.28">28</option>
                                <option value="0.29">29</option>
                                <option value="0.30">30</option>
                            </select> 
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="txtPropina" value="">
                            <input type="hidden" id="totalComanda" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success btnMenu" onclick="javascript:caja.propinaAceptar();">Aceptar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger btnMenu" onclick="javascript:caja.propinaCancelar();">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='modalAdenda' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adenda</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea name="adenda" class="col-md-12" rows="20" id="addenda"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    
    <div id='modalAjuste' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajuste</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Ajusta al total que quieras la Factura:</label>
                            <input type="text" id="ajusteTotal" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success btnMenu" onclick="javascript:caja.ajusteAceptar();">Aceptar</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btnMenu" onclick="javascript:caja.ajusteCancelar();">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='modalCambioCantidad' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Cambio de cantidad</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-top: 0 !important;">
                        <div class="col-md-12">
                            <label class="text-center col-md-12" id="lblDescripcionProducto"></label>
                        </div>
                    </div>
                    <div class="row" style="display:none;" id="divComentariosProducto">
                        <div class="col-md-12">
                            <textarea id="txtareacomentariosProducto" rows="5" style="width:100%; resize:none"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Precio: $</label>
                                    <div id="listaprecios"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Cantidad:</label>
                                    <input type="text " class="form-control" style="margin-left: 0 !important; width: 100% !important;" id="txtCantidadProducto" onkeypress="return caja.isNumberKey(event)" value="">
                                </div>
                            </div>
                            <div class="row" id="divExistenciasProducto" style="display:none;">
                                <div class="col-md-12">
                                    <label>En existencia :</label>
                                    <label id="lblexistenciaProducto" ></label>
                                </div>
                            </div>
                            <label>Descuento:</label>
                            <div class="row" id="divDescuento">
                                <div class="col-md-6" style="margin-bottom: 1em !important;">
                                    <select id="cboDescuentoProducto" style="width: 100% !important;" class="form-control">
                                        <option value="$">$</option>
                                        <option value="%">%</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" style="margin-left: 0 !important; width: 100% !important;" class="form-control" onkeypress="return caja.isNumberKey(event)" id="txtDescuentoProducto" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img src="" id="imagenProducto" alt="" class="col-xs-12">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success btnMenu" onclick="javascript:caja.modificaCantidadAceptar();">Aceptar</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btnMenu" onclick="javascript:caja.modificaCantidadCancelar();">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id='modalPago' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Ventana de pago</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total a pagar:</label>
                            <label id="lblTotalxPagar"></label>
                        </div>
                        <div class="col-md-6">
                            <label>Pagado hasta el momento:</label>
                            <label id="lblAbonoPago">0.00</label>
                        </div>
                    </div>
               <!--     <div class="row">
                        <div class="col-sm-4">
                            <label>Descuento %:</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" id="descuentoGeneral" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-info">Apliacar Descuento</button>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-4">
                            <label>Metodo de pago:</label>
                            <select id="cboMetodoPago" class="form-control" style="margin-right: unset; width: 100%;">
                            <?php 

                                foreach ($formasDePago as $key => $value) {
                                    echo '<option value="'.$value['idFormapago'].'">'.utf8_decode($value['nombre']).'</option>';
                                }

                            ?>
                               <!-- <option value="1">Efectivo</option>
                                <option value="2">Cheque</option>
                                <option value="3">Tarjeta de regalo</option>
                                <option value="4">Tarjeta de cr&eacute;dito</option>
                                <option value="5">Tarjeta de debito</option>
                                <option value="6">Cr&eacute;dito</option>
                                <option value="7">Transferencia</option>
                                <option value="8">Spei</option>
                                <option value="9">-No Identificado-</option> -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Monto a abonar:</label>
                            <input type="text" class="form-control" onkeypress="return caja.isNumberKey(event)" id="txtCantidadPago" style="width: 100% !important;" placeholder="Cantidad" value="">
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary btnMenu" id="btnAgregarPago">Agregar Pago</button>
                        </div>
                    </div>
                    <div class="row" id="divReferenciaPago" style="display:none;">
                        <div class="col-md-12">
                            <label id="lblReferencia">Referencia transferencia:</label>
                            <input type="text" id="txtReferencia" class="form-control pull-left" value="">
                        </div>
                    </div>
                    <section id="divDesglosePago">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">          
                                    <table class="table table-bordered" id="divDesglosePagoTabla">
                                        <thead>
                                            <tr>
                                                <th>Metodo</th>
                                                <th>Cantidad</th>
                                                <th>Acci&oacute;n</th>
                                            </tr>
                                        </thead>
                                        <tbody id="divDesglosePagoTablaCuerpo">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Aun por pagar:</label>
                                <label id="lblPorPagar">0.00</label>
                            </div>
                            <div class="col-md-6">
                                <label>Cambio:</label>
                                <label id="lblCambio">0.00</label>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-3">
                            <button onclick="javascript:caja.pagar();" type="button" class="btn btn-success btnMenu">Pagar</button>
                        </div>
                        <div class="col-md-3">
                            <button onclick="javascript:$('#modalPago').modal('hide'); caja.suspender();" type="button" class="btn btn-warning btnMenu">Suspender</button>
                        </div>
                        <div class="col-md-3">
                            <button onclick="javascript:$('#modalPago').modal('hide');" type="button" class="btn btn-danger btnMenu">Salir</button>
                        </div>
                        <div class="col-md-3">
                            <button onclick="javascript:caja.cancelarCaja(true); $('#modalPago').modal('hide');" type="button" class="btn btn-danger btnMenu">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='modalEstadoMensaje' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-center control-label col-xs-12" id="lblMensajeEstado"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div id='modalComprobante' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Comprobante</h4>
                </div>
                <div class="modal-body">
                    <div class="row rTouch">
                        <div class="col-md-12">
                            <iframe id="frameComprobante" src="" frameborder="0" style="float:left;height:100%;width:100%;"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <!--<button class="btn btn-danger btnMenu" onclick="javascript:window.location.reload();">Salir</button> -->
                            <button class="btn btn-danger btnMenu" onclick="limpiaFrame();">Salir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='inicio_caja' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Inicializar caja</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="divContSucursal">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Saldo actual en caja:</label>
                            <label id="saldocaja"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Ingrese con cuanto inicia caja:</label>
                            <input type="text" class="form-control" onkeypress="return caja.isNumberKey(event)" id="iniciocaja" maxlength="8" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success btnMenu" onclick="javascript:caja.cajaIniciar();">Iniciar</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btnMenu" onclick="javascript:caja.cajaCancelar();">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='modal_Observaciones' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Observaciones</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="col-md-12 text-center" > Comentarios en </label>
                            <label class="col-md-12 text-center" id="lblComentarioE"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="txtareaObservaciones" style="width:100%; resize:none" rows="15"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>