<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Configuración de caja</title>

        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
         <link rel="stylesheet" href="../../libraries/bootstrap-switch/bootstrap-switch.css">

         <!-- DataTables  -->
        <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

        <script src="../../libraries/jquery.min.js"></script>
        <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../../libraries/bootstrap-switch/bootstrap-switch.js"></script>
        <script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
         <link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
         <script src="../../libraries/select2/dist/js/select2.min.js"></script>
         <!-- DataTables  -->
        <script src="../../libraries/dataTable/js/datatables.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
        <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
        <script src="../../libraries/export_print/buttons.html5.min.js"></script>
        <script src="../../libraries/notify.js"></script>
        <script src="../../libraries/export_print/jszip.min.js"></script>

        <script>
            $(document).ready( function() {

                $.ajax({
                    type: "GET",
                    url: "ajax.php?c=config_caja&f=consulta",
                    timeout: 2000,
                    dataType : 'json',

                    success: function(data) {
                        data = data[0];
                        console.log(data);
                        //alert(data[0].tipo_descuento);
                        ( data.tipo_descuento == null || data.tipo_descuento == "")
                            ? $('#tipoDescuento').val("3")
                            : $('#tipoDescuento').val(data.tipo_descuento);
                        ( data.limit_global_c == null || data.limit_global_c == "")
                            ? $('#limit-global .cantidad').val("0")
                            : $('#limit-global .cantidad').val(data.limit_global_c);
                        ( data.limit_global_p == null || data.limit_global_p == "")
                            ? $('#limit-global .porcentaje').val("0")
                            : $('#limit-global .porcentaje').val(data.limit_global_p);
                        ( data.limit_sin_pass_c == null || data.limit_sin_pass_c == "")
                            ? $('#limit-unit .cantidad').val("0")
                            : $('#limit-unit .cantidad').val(data.limit_sin_pass_c);
                        ( data.limit_sin_pass_p == null || data.limit_sin_pass_p == "")
                            ? $('#limit-unit .porcentaje').val("0")
                            : $('#limit-unit .porcentaje').val(data.limit_sin_pass_p);
                        ( data.password == null || data.password == "")
                            ? $('#confirm-password').val("")
                            : $('#password').val(data.password);
                        ( data.password == null || data.password == "")
                            ? $('#confirm-password').val("")
                            : $('#confirm-password').val(data.password);
                        ( data.max_caja == null || data.max_caja == "")
                            ? $('#caja-max').val("0")
                            : $('#caja-max').val(data.max_caja) ;
                        ( data.max_retiro == null || data.max_retiro == "")
                            ? $('#retiro-max').val("0")
                            : $('#retiro-max').val(data.max_retiro) ;
                        ( data.leyenda_ticket == null || data.leyenda_ticket  == "")
                            ? $('#ticket').val("")
                            : $('#ticket').val(data.leyenda_ticket) ;
                        ( data.precio_unit_ticket == null || data.precio_unit_ticket  == "")
                            ? $('#verPrecioUnitario').val("")
                            : $('#verPrecioUnitario').val(data.precio_unit_ticket == "1" ? "1" : "0") ;
                        ( data.puntos == null || data.puntos  == "")
                            ? $('#verTarjetas').val("")
                            : $('#verTarjetas').val(data.puntos == "1" ? "1" : "0") ;
                        ( data.modifica_precios == null || data.modifica_precios  == "")
                            ? $('#activaPrecio').val("")
                            : $('#activaPrecio').val(data.modifica_precios == "1" ? "1" : "0") ;
                        ( data.moduloPrint == null || data.moduloPrint  == "")
                            ? $('#moduloPrint').val("")
                            : $('#moduloPrint').val(data.moduloPrint == "1" ? "1" : "0") ;

                        $('#activarDevCan').val(data.activar_dev_can == "1" ? "1" : "0") ;
                        $('#activarRetiroDevCan').val(data.activar_retiro_dev_can == "1" ? "1" : "0") ;


                        if(data.cotizacio_desc == "1"){
                            $('#cotizacion-descuento').attr('checked', true);
                        }
                        if(data.ov_desc == "1"){
                            $('#orden-venta-descuento').attr('checked', true);
                        }

                        if(data.tipo_descuento == "1"){
                            $('#limit-global').css('visibility', 'visible');
                            $('#limit-unit').css('visibility', 'hidden');
                        }
                        else if(data.tipo_descuento == "2") {
                            $('#limit-global').css('visibility', 'hidden');
                            $('#limit-unit').css('visibility', 'visible');
                        }
                        else if(data.tipo_descuento == "3") {
                            $('#limit-global').css('visibility', 'visible');
                            $('#limit-unit').css('visibility', 'visible');
                        }

                        $("#prontipagos_usuario").val(data.usuarioProntipago);
                        $("#prontipagos_contrasena").val(data.contrasenaProntipago);
                        //if(data.usuarioProntipago != '' && data.contrasenaProntipago != '') $("#btn_activar_prontipagos").trigger("click");
                        //

                            if(data.printAuto == "1") {
                    $("#corte_1").prop('checked' , true);

                         } else if (data.printAuto == "0")  {
                    $("#corte_0").prop('checked', true);
                        }

                    },
                    error: function() {
                        alert('Error, porfavor intenta mas tarde.');
                    }
                });
            });
            ///////////////// ******** ----     convertir_dataTable     ------ ************ //////////////////
            //////// Conviertela tabla en dataTable
                // Como parametros recibe:
                    // id -> ID de la tabla a convertir
                var contar = 0;
                function convertir_dataTable($objeto) {
                    console.log('objeto convertir dataTable');
                    console.log($objeto);

                    var $orden = ($objeto['orden']) ? $objeto['orden'] : 'desc';
                    var  page = 'por pagina';
                    var exist = 'No hay datos.';
                    var empty = 'No hay datos que mostrar.';
                    var enc = 'resultados encontrados';
                    var first = 'Primero';
                    var last = 'Ultimo';

                // Validacion para evitar error al crear el dataTable
                    if (!$.fn.dataTable.isDataTable('#' + $objeto['id'])) {
                        $('#' + $objeto['id']).DataTable({
                            fnDrawCallback: function( oSettings ) {
                            contar++;
                            $("[name='my-checkbox']").bootstrapSwitch();

                              $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
                                console.log(this.id); // DOM element
                                console.log(state); // true | false
                                $.ajax({
                                    data : {id: this.id, status: state},
                                    url : 'ajax.php?c=caja&f=change_status',
                                    type : 'POST',
                                    dataType : 'json',
                                }).done(function(resp) {
                                    console.log('=========> Done change_status');
                                    console.log(resp);

                                    if (resp['status'] == 2) {
                                        if (!state) {
                                            $mensaje = 'Error al activar el metodo de pago';
                                        } else {
                                            $mensaje = 'Error al inactivar el metodo de pago';
                                        }
                                        $.notify($mensaje, {
                                            position : "top center",
                                            autoHide : true,
                                            autoHideDelay : 5000,
                                            className : 'error',
                                            arrowSize : 15
                                        });
                                    } else {
                                        if (!state) {
                                            $mensaje = 'Metodo de pago activo';
                                        } else {
                                            $mensaje = 'Metodo de pago inactivo';
                                        }
                                        $.notify($mensaje, {
                                            position : "top center",
                                            autoHide : true,
                                            autoHideDelay : 5000,
                                            className : 'success',
                                            arrowSize : 15
                                        });
                                    }

                                }).fail(function(resp) {
                                    console.log('---------> Fail change_status');
                                    console.log(resp);
                                // Manda un mensaje de error
                                    if (!state) {
                                        $mensaje = 'Error al activar el metodo de pago';
                                    } else {
                                        $mensaje = 'Error al inactivar el metodo de pago';
                                    }
                                    $.notify($mensaje, {
                                        position : "top center",
                                        autoHide : true,
                                        autoHideDelay : 5000,
                                        className : 'error',
                                        arrowSize : 15
                                    });
                                });

                            });

                            },
                            dom : 'Bfrtip',
                            buttons : ['excel'],
                            language : {
                                buttons : {
                                    pageLength : "%d "+page
                                },
                                search : "Buscar",
                                lengthMenu : "",
                                zeroRecords : exist,
                                infoEmpty : empty,
                                info : " ",
                                infoFiltered : " -> <strong> _TOTAL_ </strong> "+enc,
                                paginate : {
                                    first : 'Primero',
                                    previous : "Anterior",
                                    next : "Siguiente",
                                    last : 'Ultimo'
                                }
                            },
                            order: [[0, $orden]]
                        });
                    }
                }

            ///////////////// ******** ----     FIN convertir_dataTable     ------ ************ //////////////////

        </script>

    </head>
    <body>
        <br>
        <div class="container well">
            <div class="row">

                <div class="col-sm-1">
                    <div id="btnSave">
                      <button type="submit" class="btn btn-primary" id="save"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Configuración de caja</h3>
                </div>


                <div class="panel-body">
                    <!-- div de los Tabs -->
                    <div id="tabsProduct">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#basicos">Configuración</a></li>
                            <li><a data-toggle="tab" href="#prontipagos">Prontipagos</a></li>
                            <li><a data-toggle="tab" href="#metodos_pago">Formas de pago</a></li>
                            <li><a data-toggle="tab" href="#tipo_documento">Tipo de documento</a></li>
                            <li><a data-toggle="tab" href="#moduloImprimir">Modulo de Impresión</a></li>
                        </ul>
                    </div>

                    <!-- Div contendro de los Contenidos -->
                    <div class="tab-content" style="height:400px;">
                        <div id="basicos" class="tab-pane fade in active">
                            <div class="form-horizontal col-sm-12">
                                <div class="form-group">


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="tipoDescuento">Tipo de descuento</label>
                                            <select id="tipoDescuento" class="form-control" >
                                                <option value="1" > Global </option>
                                                <option value="2" > Por producto (PP) </option>
                                                <option value="3" selected > Ambos </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div id="dinamico" class="row">

                                        <div id="limit-global" >
                                            <div class="col-sm-12" >
                                                <label >Límite de descuento global sin contraseña</label>
                                            </div>
                                            <div class="col-sm-3" style="display: none;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="number" class="cantidad form-control text-right" min="0" value="0.00">
                                                </div>
                                            </div>
                                            <div class="col-sm-1" style="display: none;"></div>
                                             <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input type="number" class="porcentaje form-control text-right" min="0" max="100"  value="0.00" >
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="limit-unit" >
                                            <div class="col-sm-12">
                                                <label >Límite de descuento por producto sin contraseña</label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input type="number" class="cantidad form-control text-right" min="0" value="0.00">
                                                </div>
                                            </div>
                                            <div class="col-sm-1" ></div>
                                             <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input type="number" class="porcentaje form-control text-right" min="0" max="100"  value="0.00" >
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="password">Contraseña</label>
                                            <input type="password" id="password" class="form-control" placeholder="* * * * *">
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="confirm-password">Confirmar contraseña</label>
                                            <input type="password" id="confirm-password" class="form-control" placeholder="* * * * *">
                                        </div>
                                    </div>



                                    <div class="row" style="display: none;">
                                        <div class="col-sm-3">
                                            <label for="caja-max" class="control_label">Caja Max.</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" id="caja-max" class="form-control text-right" min="0" value="0.00">
                                            </div>
                                        </div>
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label for="retiro-max" class="control-label">Max. retiro</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" id="retiro-max" class="form-control text-right" min="0" value="0.00">
                                            </div>


                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="ticket">Leyenda del ticket</label>
                                            <textarea id="ticket" class="form-control" rows="5" placeholder="Ej. Gracias por su compra =)"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" >

                                        <div class="col-sm-12">
                                            <label for="verPrecioUnitario">Ver precio Unitario en ticket</label>
                                            <select id="verPrecioUnitario" class="form-control" >
                                                <option value="0" > No </option>
                                                <option value="1" > Si </option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row" >

                                        <div class="col-sm-12">
                                            <label>Usar Tarjetas de puntos/regalo</label>
                                            <select id="verTarjetas" class="form-control" >
                                                <option value="0" > No </option>
                                                <option value="1" > Si </option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row" >

                                        <div class="col-sm-6">
                                            <label>Activar contraseña para cancelación y devolución</label>
                                            <select id="activarDevCan" class="form-control" >
                                                <option value="0" > No </option>
                                                <option value="1" > Si </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Activar los retiros de efectivo por cancelación y devolución</label>
                                            <select id="activarRetiroDevCan" class="form-control" >
                                                <option value="0" > No </option>
                                                <option value="1" > Si </option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row" >

                                        <div class="col-sm-6">
                                            <label>Activar modificacion de precio</label>
                                            <select id="activaPrecio" class="form-control" >
                                                <option value="0" > No </option>
                                                <option value="1" > Si </option>
                                            </select>
                                        </div>

                                    </div>


                                    <div class="row" style="display: none;">

                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="cotizacion-descuento" > Cotización descuento </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="orden-venta-descuento" > Orden de venta descuento </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div id="prontipagos" class="tab-pane">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="">Usuario:</label>
                                    <input id="prontipagos_usuario" name="prontipagos_usuario" type="text" class="form-control" placeholder="Usuario">
                                </div>
                                <div class="col-sm-4">
                                    <label for="">Contraseña:</label>
                                    <input id="prontipagos_contrasena" name="prontipagos_contrasena" type="password" class="form-control" placeholder="Contraseña">
                                </div>
                                <div class="col-sm-4">
                                    <label style="width: 100%">&nbsp;</label>
                                    <button id="btn_activar_prontipagos" type="button" class="btn btn-primary">Vincular prontipagos</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Seleccione los productos que desea activar:</label>
                                    <select id="prontipagos_productos" class="form-control selectpicker" multiple></select>
                                </div>
                                <div class="col-sm-4">
                                    <label style="width: 100%">&nbsp;</label>
                                    <button id="btn_activar_productos_prontipagos" type="button" class="btn btn-primary">Activar servicios</button>
                                </div>
                            </div>
                        </div>

                        <div id="metodos_pago" class="tab-pane">
                            <div class="panel-heading"><div class="col-sm-9 col-xs-9"><h3></h3></div><div class="col-sm-3 col-xs-3"><button style="float:right; display:none; margin-top: 20px; margin-bottom: 10px;" id="nuevo" class="btn btn-success" onclick="nuevo()">Nuevo</button></div></div>
                            <div class="panel-body">
                               <div class="col-sm-6 col-xs-6">
                                    <table id="tabla_metodos_pago" style="width: 100%;" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th align="center"><strong>Nombre</strong></th>
                                                <th align="center"><strong></i></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                            foreach ($metodos as $key => $value) { ?>
                                                <tr id="tr_metodo_pago_<?php echo $value['idFormapago'] ?>">
                                                    <td ><?php if($value['tipo'] == 2) { ?><a onclick="edit('<?php echo $value['nombre']?>','<?php echo $value['claveSat']?>', '<?php echo $value['idFormapago'] ?>' )"><?php echo $value['nombre'].' ('.$value['claveSat'].')' ?></a><?php } else { ?><?php echo $value['nombre'].' ('.$value['claveSat'].')' ?><?php } ?></td>
                                                    <td style="text-align: center"><input type="checkbox" <?php if($value['activo'] == 1) {?>checked<?php } ?> id="<?php echo $value['idFormapago'] ?>" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default" name="my-checkbox"></td>
                                                </tr><?php
                                            } ?>
                                        </tbody>
                                    </table>
                                    <script>convertir_dataTable({id:'tabla_metodos_pago'})</script>
                                </div>
                                <div class="col-sm-6 col-xs-6" style=" padding: 13px; background-color: gainsboro; border-radius: 12px;">
                                    <h4>Nuevo tipo de pago: </h4>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nombre: </label>
                                        <div class="col-sm-10">
                                            <input id="nombre" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tipo: </label>
                                        <div class="col-sm-10" id="div_select_tipo_pago">
                                            <select class="selectpicker" data-live-search="true" id="tipo_pago">
                                                <?php

                                                foreach ($metodos as $key => $value) { ?>
                                                    <?php if($value['tipo'] == 1) { ?>
                                                        <option value="<?php echo $value['claveSat'] ?>">
                                                            <?php echo ' ('.$value['claveSat'].') '.$value['nombre'] ?>
                                                        </option>
                                                    <?php } ?>
                                                    <?php
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <br><br>
                                    <button style="float:right;" id="btn_save_t" class="btn btn-success" onclick="guardar_tipo_pago()">Guardar</button>
                                    <button style="float:right; display: none;" id="edit_save_t" class="btn btn-primary" onclick="editar_tipo_pago()">Editar</button>
                                    <button style="float:right; display: none; margin-right: 5px;" id="delete_save_t" class="btn btn-danger" onclick="delete_tipo_pago()">Eliminar</button>
                                </div>
                            </div>
                        </div>

                        <div id="tipo_documento" class="tab-pane">
                            <div class="panel-heading"><div class="col-sm-9 col-xs-9"><h3></h3></div><div class="col-sm-3 col-xs-3"><button style="float:right; display:none; margin-top: 20px; margin-bottom: 10px;" id="nuevo" class="btn btn-success" onclick="nuevo()">Nuevo</button></div></div>
                            <div class="panel-body">
                               <div class="col-sm-6 col-xs-6">
                                    <table id="tabla_metodos_pago" style="width: 100%;" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th align="center"><strong>Tipo de documento</strong></th>
                                                <th align="center"><strong></i></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $configDatos[0]['tipo_documento'];

                                            $tiposdocs=array();
                                            if($documentos[0]['tipo_documento']!='0'){
                                                $explodeDoc=explode(',',$documentos[0]['tipo_documento']);
                                                $tiposdocs[2]=$explodeDoc[0];
                                                $tiposdocs[5]=$explodeDoc[1];
                                                $tiposdocs[4]=$explodeDoc[2];
                                            }else{
                                                $tiposdocs[2]=0;
                                                $tiposdocs[5]=0;
                                                $tiposdocs[4]=0;
                                            }

                                        ?>
                                            <tr>
                                                <td>Factura</td>
                                                <td style="text-align: center">
                                                <input class="chbtpd" <?php if($tiposdocs[2]!=0){ ?> checked <?php } ?> onclick="saveTD();" style="cursor:pointer;" type="checkbox" name="tipa" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Recibo de honorarios</td>
                                                <td style="text-align: center">
                                                <input class="chbtpd" <?php if($tiposdocs[5]!=0){ ?> checked <?php } ?> onclick="saveTD();" style="cursor:pointer;" type="checkbox" name="tipa" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Recibo de pago</td>
                                                <td style="text-align: center">
                                                <input class="chbtpd" <?php if($tiposdocs[4]!=0){ ?> checked <?php } ?> onclick="saveTD();" style="cursor:pointer;" type="checkbox" name="tipa" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div id="moduloImprimir" class="tab-pane">
                            <div class="col-md-12">
                            <br><br>
                                <div class="col-md-1">
                                    <label for="">Activar:</label>
                                </div>
                                <div class="col-md-4">
                                    <select id="moduloPrint" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="panel panel-default">
                <div class="panel-heading"></div>
                  <h3 class="panel-title" style="" >Corte de Caja</h3>


                </div> <div class="tab-content" style="height:100px;">
                        <div id="basicos" class="tab-pane fade in active">
                            <div class="form-horizontal col-sm-12">
                                <div class="form-group">
                                </div>
                                </div>
                                </div>
                                <label style=" font-size: 13px;" class="col-sm-10" >Impresion automatica</label>
                                <form class="col-md-10"  action="">
                                <div class="col-sm-12" ></div>




   <td></td> <div class="col-sm-5"><input type="radio" name="corte"  id="corte_1" value="1" > Si <td>      </td></div>






 <div class="rows"> <input style="" type="radio"  name="corte" id="corte_0" value="0"  > No<br> </div>




</form>
                                </div>

</div>





        </div>

        <div  style="height:90px;" ></div>
        <script src="js/config_caja.js"></script>
        <script type="">
        var htmlSelect = '<select class="selectpicker" data-live-search="true" id="tipo_pago">';
        <?php foreach ($metodos as $key => $value) { ?>
            <?php if($value['tipo'] == 1) { ?>
                htmlSelect += '<option value="<?php echo $value['claveSat'] ?>">';
                    htmlSelect += "<?php echo $value['nombre'].' ('.$value['claveSat'].')' ?></option>";
            <?php } ?>
            <?php
        } ?>
            htmlSelect += '</select>';

        $("#tipo_pago").select2({
            width : "200px"
        });

        function saveTD(){
            chbtpd='';
            $(".chbtpd").each(function() {
                $(this).addClass( "foo" );
                if ($(this).is(':checked')) {
                    chbtpd+='1,';
                }else{
                    chbtpd+='0,';
                }
            });

            $.ajax({
                data : {td:chbtpd},
                url : 'ajax.php?c=caja&f=editar_tipo_documento',
                type : 'POST',
                dataType : 'json',
            }).done(function(resp) {
                console.log('=========> Done editar_tipo_documento');
                console.log(resp);

                if (resp['status'] == 2) {
                      $mensaje = 'Error al editar el tipo de documento.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                        arrowSize : 15
                    });
                } else {
                    $mensaje = 'Tipo de documento editado correctamente.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'success',
                        arrowSize : 15
                    });
                }

            }).fail(function(resp) {
                console.log('---------> Fail editar_tipo_documento');
                console.log(resp);
            // Manda un mensaje de error
                    $mensaje = 'Error al editar el tipo de documento.';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'error',
                    arrowSize : 15
                });
            });
        }

        function nuevo(){
            console.log("nuevo");
            $('#div_select_tipo_pago').html(htmlSelect);
            $("#edit_save_t").hide();
            $("#delete_save_t").hide();
            $("#btn_save_t").show();
            $("#nuevo").hide();
            $("#nombre").val('');
            $("#tipo_pago").val($("#tipo_pago option:first").val());
            $("#tipo_pago").select2({
                width : "200px"
            });
        }

        function edit($nombre, $clave, $id){
            console.log("edit");
            $('#div_select_tipo_pago').html(htmlSelect);
            $("#edit_save_t").show();
            $("#delete_save_t").show();
            $("#edit_save_t").attr('id_tipo', $id);
            $("#btn_save_t").hide();
            $("#nuevo").show();
            $("#nombre").val($nombre);
            $("#tipo_pago").val($clave);
            $("#tipo_pago").select2({
                width : "200px"
            });
        }
        function guardar_tipo_pago(){
            if ($('#nombre').val() == '') {
                $('#nombre').notify("Coloque un nombre.", {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'warning',
                        arrowSize : 15
                    });
                return 0;
            }
            $.ajax({
                data : { nombre: $('#nombre').val(), clave: $('#tipo_pago').val()},
                url : 'ajax.php?c=caja&f=guardar_tipo_pago',
                type : 'POST',
                dataType : 'json',
            }).done(function(resp) {
                console.log('=========> Done guardar_tipo_pago');
                console.log(resp);

                if (resp['status'] == 2) {
                      $mensaje = 'Error al guardar el nuevo tipo de pago.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                        arrowSize : 15
                    });
                } else {
                    var table = $('#tabla_metodos_pago').DataTable();
                    table.destroy();
                    $mensaje = 'Metodo de pago guardado correctamente.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'success',
                        arrowSize : 15
                    });
                    $("#tabla_metodos_pago").append('<tr id="tr_metodo_pago_'+resp['id']+'"><td ><a onclick="edit('+"'"+$('#nombre').val()+"'"+','+"'"+$('#tipo_pago').val()+"'"+', '+"'"+resp['id']+"'"+' )">'+$('#nombre').val()+' ('+$('#tipo_pago').val()+')</a></td><td style="text-align: center"><input type="checkbox" checked id="'+resp['id']+'" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default" name="my-checkbox"></td></tr>');

                    convertir_dataTable({id:'tabla_metodos_pago'});
                    nuevo();
                }

            }).fail(function(resp) {
                console.log('---------> Fail guardar_tipo_pago');
                console.log(resp);
            // Manda un mensaje de error
                    $mensaje = 'Error al guardar el nuevo tipo de pago.';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'error',
                    arrowSize : 15
                });
            });
        }
        function editar_tipo_pago(){
            if ($('#nombre').val() == '') {
                $('#nombre').notify("Coloque un nombre.", {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'warning',
                        arrowSize : 15
                    });
                return 0;
            }
            $.ajax({
                data : {id: $("#edit_save_t").attr('id_tipo'), nombre: $('#nombre').val(), clave: $('#tipo_pago').val()},
                url : 'ajax.php?c=caja&f=editar_tipo_pago',
                type : 'POST',
                dataType : 'json',
            }).done(function(resp) {
                console.log('=========> Done editar_tipo_pago');
                console.log(resp);

                if (resp['status'] == 2) {
                      $mensaje = 'Error al editar el tipo de pago.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                        arrowSize : 15
                    });
                } else {
                    var table = $('#tabla_metodos_pago').DataTable();
                    table.destroy();
                    $mensaje = 'Metodo de pago editado correctamente.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'success',
                        arrowSize : 15
                    });
                    $("#tr_metodo_pago_"+$("#edit_save_t").attr('id_tipo')).html('');
                    $("#tr_metodo_pago_"+$("#edit_save_t").attr('id_tipo')).append('<td ><a onclick="edit('+"'"+$('#nombre').val()+"'"+','+"'"+$('#tipo_pago').val()+"'"+', '+"'"+$("#edit_save_t").attr('id_tipo')+"'"+' )">'+$('#nombre').val()+' ('+$('#tipo_pago').val()+')</a></td><td style="text-align: center"><input type="checkbox" checked id="'+$("#edit_save_t").attr('id_tipo')+'" data-handle-width="60" data-size="mini" data-on-text="" data-off-text="" data-on-color="success" data-off-color="default" name="my-checkbox"></td>');

                    convertir_dataTable({id:'tabla_metodos_pago'});
                    nuevo();
                }

            }).fail(function(resp) {
                console.log('---------> Fail editar_tipo_pago');
                console.log(resp);
            // Manda un mensaje de error
                    $mensaje = 'Error al editar el tipo de pago.';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'error',
                    arrowSize : 15
                });
            });
        }

        function delete_tipo_pago(){
            if (!confirm("¿Seguro que quiere eliminar el metodo de pago?")) {
                return 0;
            }
            $.ajax({
                data : {id: $("#edit_save_t").attr('id_tipo')},
                url : 'ajax.php?c=caja&f=delete_tipo_pago',
                type : 'POST',
                dataType : 'json',
            }).done(function(resp) {
                console.log('=========> Done delete_tipo_pago');
                console.log(resp);

                if (resp['status'] == 2) {
                      $mensaje = 'Error al eliminar el tipo de pago.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'error',
                        arrowSize : 15
                    });
                } else {
                    var table = $('#tabla_metodos_pago').DataTable();
                    table.destroy();
                    $mensaje = 'Metodo de pago eliminado correctamente.';
                    $.notify($mensaje, {
                        position : "top center",
                        autoHide : true,
                        autoHideDelay : 5000,
                        className : 'success',
                        arrowSize : 15
                    });
                    $("#tr_metodo_pago_"+$("#edit_save_t").attr('id_tipo')).remove();

                    convertir_dataTable({id:'tabla_metodos_pago'});
                    nuevo();
                }

            }).fail(function(resp) {
                console.log('---------> Fail editar_tipo_pago');
                console.log(resp);
            // Manda un mensaje de error
                    $mensaje = 'Error al editar el tipo de pago.';
                $.notify($mensaje, {
                    position : "top center",
                    autoHide : true,
                    autoHideDelay : 5000,
                    className : 'error',
                    arrowSize : 15
                });
            });
        }


    </script>
    </body>
</html>
