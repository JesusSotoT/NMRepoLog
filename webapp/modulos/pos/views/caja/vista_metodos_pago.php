<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Caja</title>

<!-- ** /////////////////////////- -                 CSS                --///////////////////// **-->

    <!-- Iconos font-awesome -->
        <link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
    <!-- jqueryui -->
        <link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
    <!-- bootstrap min CSS -->
        <link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap-theme.min.css">
         <link rel="stylesheet" href="../../libraries/bootstrap-switch/bootstrap-switch.css">
    <!-- Select con buscador  -->
        <link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- jquery Mobile -->
        <!-- <link rel="stylesheet" href="../../libraries/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"> -->
    <!-- DataTables  -->
        <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
        
    <!-- ** Sistema -->
        
<!-- ** //////////////////////////- -               FIN CSS                 --///////////////////// **-->

<!-- ** //////////////////////////- -               JS                      --///////////////////// **-->

    <!-- JQuery -->
        <script src="../../libraries/jquery.min.js"></script>
    <!-- JQuery-Ui -->
        <script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <!-- bootstrap -->
        <script src="../../libraries/bootstrap-3.3.7/js/bootstrap.min.js"></script> 
        <script src="../../libraries/bootstrap-switch/bootstrap-switch.js"></script>
    <!-- Select con buscador  -->
        <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <!-- Notify  -->
        <script src="../../libraries/notify.js"></script>
    <!-- DataTables  -->
        <script src="../../libraries/dataTable/js/datatables.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
        <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
        <script src="../../libraries/export_print/buttons.html5.min.js"></script>
        <script src="../../libraries/export_print/jszip.min.js"></script>

<script>
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
    <div id="" class="container well">
        <div class="container-fluid well">
            <div class="panel panel-default">
                <div class="panel-heading"><div class="col-sm-9 col-xs-9"><h3>Métodos de pago</h3></div><div class="col-sm-3 col-xs-3"><button style="float:right; display:none; margin-top: 20px; margin-bottom: 10px;" id="nuevo" class="btn btn-success" onclick="nuevo()">Nuevo</button></div></div>
                <div class="panel-body">
                   <div class="col-sm-6 col-xs-6">
                        <table id="tabla_metodos_pago" class="table table-striped table-bordered" cellspacing="0">
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
                            <div class="col-sm-10">
                                <select class="selectpicker" data-live-search="true" id="tipo_pago">
                                    <?php
                                    
                                    foreach ($metodos as $key => $value) { ?>
                                        <?php if($value['tipo'] == 1) { ?>
                                            <option value="<?php echo $value['claveSat'] ?>">
                                                <?php echo $value['nombre'].' ('.$value['claveSat'].')' ?>
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
                </div> <!-- Fin del Panel Body -->
            </div>
        </div> 
    </div>
</body>
<script type="">
    $("#tipo_pago").select2({
        width : "200px"
    });
    function nuevo(){
        console.log("nuevo");
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
</html>    