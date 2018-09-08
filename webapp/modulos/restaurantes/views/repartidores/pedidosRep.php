<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Modulo de Repartidores</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="js/repartidores/repartidores.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

<!-- bootstrap-select -->
	<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
		
    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
    <!--  Notify  -->
        <script src="js/notify.js"></script>
        
	<!-- Mapa -->
	    <link rel="stylesheet" href="css/configuracion/mapa.css">
		<script src="js/configuracion/mapa.js"></script>
    <body> 
        <?php 
            //print_r($configuracion);
         ?> 
    <!-- Empleados -->
        <div class="row" style="margin:0">
            <div class="col-md-4 col-xs-12">
                <h3>Asignar Pedidos</h3>
            </div>
            <div class="col-md-8 col-xs-12">
                <button data-toggle="modal" data-target="#modal_asign_turn"class="btn btn-info" style="float:right; margin-top: 20px;">Asignar turnos</button>
                <button onclick="comandas.search_turn();" class="btn btn-info" style="float:right; margin-top: 20px; margin-right: 10px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                <input id="turnos" style="width: 90px; float:right; margin-top: 20px;" type="number" class="form-control">
                <span style="float:right; margin-top: 20px; font-weight: bold; font-size: 18px; margin-right: 10px;">Turno: </span>
            </div>
        </div>
        
        <div class="row">

            <div class="col-md-12"><?php
                $clases[0] = 'default';
                $clases[1] = 'success';
                $clases[2] = 'warning';
                $clases[3] = 'primary';
                $clases[4] = 'danger';
                $clases[5] = 'info';
                        
                $posi = 0;
                
                foreach ($_SESSION['permisos']['empleados'] as $key => $value) { ?>
                    <?php  /// pide contraseña basandose en la  configuracion 
                        if($configuracion['pedir_pass'] == 1){                                    
                    ?>
                    <div class="pull-left" style="padding:5px">
                        <button 
                            type="button" 
                            class="btn btn-<?php echo $clases[$posi] ?> btn-lg repartidores" 
                            onclick="comandas.modal_login({empleado:'<?php echo $value['usuario'] ?>', id:'<?php echo $value['id'] ?>'})" 
                            style="width: 110px;"
                            id="repar-<?php echo $value['id']?>"
                            turno= "<?php echo $value['turno']?>"                         
                            data-toggle="modal" 
                            data-target="#modal_pass">
                            <i class="fa fa-user"></i> <br>
                            <i style="font-size: 15px" ><?php echo substr($value['usuario'], 0, 8); ?></i>
                        </button>
                     <?php          
                        }
                         /// no pide contraseña basandose en la configuracion 
                        if($configuracion['pedir_pass'] == 2){                                    
                    ?>
                    <div class="pull-left" style="padding:5px">
                        <button 
                            type="button" 
                            class="btn btn-<?php echo $clases[$posi] ?> btn-lg repartidores" 
                            onclick="comandas.listar_asignacion({empleado:'<?php echo $value['usuario'] ?>', id:'<?php echo $value['id'] ?>'})" 
                            style="width: 110px;"                             
                            data-toggle="modal" 
                            id="repar-<?php echo $value['id']?>"
                            turno= "<?php echo $value['turno']?>" 
                            data-target="#modal_mesas">
                            <i class="fa fa-user"></i> <br>
                            <i style="font-size: 15px" ><?php echo substr($value['usuario'], 0, 8); ?></i>
                        </button>

                     <?php          
                        }
                    ?>

                    </div><?php
                
                    $posi++;
                    $posi = ($posi > 5) ? 0 : $posi;
                    
                } ?>
            </div>
        </div>
    <!-- FIN Empleados -->
		<div class="row">
			<div class="col-md-6">
	           <div class="input-group input-group-lg">
					<select onchange="mapa.listar_areas_mapa({id_area: $('#area').val()}); setTimeout(function() {
    			$('div[title=\'Dibujar forma\']').hide();
                $('div[title=\'Trazar una forma\']').hide();
			}, 1000);" class="selectpicker" data-width="20%" id="area"><?php
						foreach ($areas as $key => $value) { ?>
							<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
						} ?>
					</select>
				</div>
			</div>
		</div>

		<div class="panel-group" id="accordion_mapa" role="tablist" aria-multiselectable="true">
		    <div class="panel panel-default">
		        <div 
		        	class="panel-heading" 
		        	role="tab"
		        	data-toggle="collapse" 
		        	data-parent="#accordion_mapa" 
		        	href="#tab_mapa" 
		        	aria-controls="collapse_mapa"
		        	style="cursor: pointer">
		            <h4 class="panel-title"><strong><i class="fa fa-map-o"></i> Mapa</strong></h4>
		        </div>
		        <div id="tab_mapa" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_tab_mapa">
		            <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <input id="buscador" type="text" class="form-control" />
                                <input id="lat_buscador" type="hidden" />
                                <input id="lng_buscador" type="hidden" />
                            </div>
                            <div class="col-md-2">
                                <button id="dirigente" class="btn btn-primary btn-block">Buscar</button>
                            </div>
                            <div class="col-md-1">
                                <button id="localizador" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-primary btn-block">Localizar</button>
                            </div>
                        </div>
                        <div class="row">
				            <div class="col-md-12">
				                <div id="google-map"></div>
				            </div>
                        </div>
		            </div>
		        </div>
		    </div>
	        <script>
				setTimeout(function() {
	        		mapa.listar_areas_mapa({id_area: $('#area').val()});
				}, 1000);
	        </script>
		</div>
        <div class="container panel col-xs-12 col-md-12" >
            <div class="row">
                <div class="col-xs-12 col-md-12">
                   <h3>Pedidos Repartidores</h3>
                </div>
            </div>
            <div class="container panel">
                <div class="container" style="overflow:auto">
                    <div class="col-sm-12">
                        <table id="table_pedidos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr turno="-1">
                            <th td width="30">Pedido</th>
                            <th td width="30">Repartidor</th>
                            <th td width="30">Tiempo de Entrega</th>
                            <th td width="30">Confirmacion de Entrega</th>
                          </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            
                        </tfoot>
                      </table>
                    </div>
                </div>
            </div>
        </div>


    <!-- Modal pass-->
        <div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="titulo_pass" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="btn_cerrar_pass" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="titulo_pass">Ingresar</h4>
                    </div>
                    <div class="modal-body">
                        <input readonly="1" id="id_empleado" type="text" class="form-control" style="visibility:hidden" />
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="fa fa-user"></i> </span>
                                    <input readonly="1" id="empleado" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
                                    <input autocomplete="off" name="empleado" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})" id="pass_empleado" type="password" class="form-control" autofocus="autofocus">
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- Botones -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"  onclick="comandas.iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})">
                            <i class="fa fa-sign-in"></i> Entrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <!-- FIN Modal pass-->
    
    <!-- Modal Mesas -->
        <div class="modal fade" style="height:95%" id="modal_mesas" tabindex="-1" role="dialog" aria-labelledby="titulo_mesas" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="btn_cerrar_mesas" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="titulo_mesas">Seleccionar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="overflow: scroll;height:55%">
                            <div class="col-xs-12" id="contenedor">
                                <!-- En esta div se cargar las mesas -->
                                    
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <button id="btn_guardar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>"  onclick="comandas.guardar_asignacion({empleado: $('#id_empleado').val(),vista:1})" class="btn btn-success btn-lg" type="button">
                                    <i class="fa fa-check"></i> Guardar
                                </button>
                                <button id="cerrar_modal" type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
                                    <i class="fa fa-ban"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- FIN Modal Mesas -->
     <!-- Modal Asignar turno -->
        <div class="modal fade" style="height:95%;" id="modal_asign_turn" tabindex="-1" role="dialog" aria-labelledby="titulo_asign_turn" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="btn_cerrar_asign_turn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="titulo_asign_turn">Asignar turnos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="overflow: scroll;height:55%; margin: 0;">
                            <table id="table_asign_turn" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center" width="30" style="text-align: center;">Repartidor</th>
                                        <th align="center" width="30" style="text-align: center;">Turno</th>
                                        <th align="center" width="30" style="text-align: center;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['permisos']['empleados'] as $key => $value) { ?>
                                        <tr>
                                            <td align="center" width="30"><?php echo $value['usuario']; ?></td>
                                            <td align="center" width="30"><input id="turn-<?php echo $value['id']?>" style="width: 90px" value="<?php echo $value['turno'] ?>" type="number" class="form-control"></td>
                                            <td align="center" width="30"><button class="btn btn-success" onclick='comandas.save_turn(<?php echo json_encode($value); ?>);'><i class="fa fa-floppy-o" aria-hidden="true"></i></buttom></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- FIN Asginar turno -->
   
    	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpwgA5xlOvsDDyR5gFhx5662NQmDfM0jw&libraries=drawing,places&callback=setMap" async defer></script>
    	<script>
	    	setTimeout(function() {
    			$("div[title='Dibujar forma']").hide();
                $("div[title='Trazar una forma']").hide();
			}, 2000);
    	</script>
    </body>
    <button onclick="listarpedidosRep()"></button>
</html>
<script>
    //comandas.listar_mesas({div:'contenedor', asignar:1});

    $(document).ready(function() {
        autoload();
        $('#table_asign_turn').DataTable({                                                            
            order: [[ 0, "desc" ]],
            destroy: true,
            language: { 
                buttons: {
                    print: 'Imprimir'
                },                                                                   
            search: "Buscar:",
            lengthMenu:"Mostrar _MENU_ elementos",
            zeroRecords: "No hay datos.",
            infoEmpty: "No hay datos que mostrar.",
            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
            paginate: {
                        first:      "Primero",
                        previous:   "Anterior",
                        next:       "Siguiente",
                        last:       "Último"
                    }
            },
        });   
    });
</script>
</script>