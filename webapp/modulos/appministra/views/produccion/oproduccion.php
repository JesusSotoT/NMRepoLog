<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
    <style>
        .p0{padding:0;}
        .glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -ms-animation: spin .7s infinite linear;
    -webkit-animation: spinw .7s infinite linear;
    -moz-animation: spinm .7s infinite linear;
}
@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}


    </style>
</head>

<!-- CHRIS - COMMENTS
=============================*/
//Modales precargados
//Modal 1 y modal 2 
-->

<div class="modal fade" id="modalDescParcial" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Descuento Parcial</h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <input type="hidden" id="xProParc">
            <input type="hidden" id="idprodDescP">
            <input type="hidden" id="idcarDescp">
            <input type="hidden" id="idprecio">
                <div class="col-sm-8">
                    <h3 id="encabezadoNombre"></h3>
                </div>
                <div class="col-sm-4">
                    <h3 id="encabezadoPrecio"></h3>
                    <input type="hidden" id="encabezadoPrecioInput">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">   
                        <div class="col-sm-4">
                            <label>Descuento:</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="tipoDescu" class="form-control">
                                <option value="%">%</option>
                                <option value="$">$</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="desCantidad">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-block" onclick="aplicaDesParcial();">Aplicar</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label>Clave</label>
                </div>
                <div class="col-sm-4">
                    <input type="password" class="form-control" id="inpass" value="">
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalDescGlobal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Descuento Global</h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <input type="hidden" id="xProParc">
            <input type="hidden" id="total">
            <input type="hidden" id="subtotal">
                <div class="col-sm-6">
                    <h3 id="h3DescG1"></h3>
                </div>
                <div class="col-sm-6">
                    <h3 id="h3DescG2"></h3>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">   
                        <div class="col-sm-2">
                            <label>Descuento:</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="desCantidadG" placeholder="%">
                        </div>
                        <div class="col-sm-2">
                            <label>Clave</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="password" class="form-control" id="inpassG" value="">
                        </div>
                    </div>
                </div>                
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-block" onclick="aplicaDesGlobal();">Aplicar</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

<div id="modal-car" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Producto con caracteristicas</h4>
            </div>
            <div id="divcar" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="modal-car-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-car-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-agrega" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Precio de producto</h4>
            </div>
            <div id="bodyespecial" class="modal-body">
                <div class="row">
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">$ Precio:</label>
                        <div class="col-sm-6">
                          <input id="precionuevo" type="text" value="0">
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-recep-uno" type="button" class="btn btn-default">Continuar</button> 
            </div>
        </div>
    </div> 
</div>

<div id="modal-conf4" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>¿Seguro que deseas inactivar esta cotizacion?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf4-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf4-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-confdelop" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>¿Seguro que deseas eliminar esta orden de produccion?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-confdelop-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-confdelop-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>

<div id="modal-confusar" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Orden de produccion actualizada!</h4>
            </div>
            <div class="modal-body">
                <p>El producto esta listo para producirse.</p>
            </div>
        </div>
    </div> 
</div>  


<div id="modal-conf3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Orden de produccion guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La orden fue guardada exitosamente.</p>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-confexp" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Pre-requisicione guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La pre-requisicion fue guardada exitosamente.</p>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conf1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>Tienes una orden de produccion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf1-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf1-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>


<div id="modal-conf2" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>Tienes una orden de produccion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf2-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>
 <!--Fin modales precargados-->

<body>
    <br>
    <div class="container well" style="padding:25px;margin-bottom: 150px;">
        <div class="row" style="padding-bottom:20px;">
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Orden de producción</h3></div>
            <input type="hidden" id="ist" value="0">
            <input type="hidden" id="it" value="0">
            <input type="hidden" id="cadimps" value="0">
            <input type="hidden" id="auxDescG" value="0"> 

        </div>

   <input type="hidden"  id="orden" value="" />

        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="nreq();">Nueva orden</button>
            <button id="btnlistorden" class="btn btn-default" type="submit" onclick="listareq();">Listado ordenes</button>
            <button id="btnback" class="btn btn-default pull-right" style="visibility:hidden;" onclick="listareq();"><span class="glyphicon glyphicon-arrow-left"></span> Regresar</button>
        </div>
        <div id="nreq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>
       
        <div id="listareq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>

        <div id="listareq" class="row" style="display:block;margin-top:20px;font-size:12px;display:none">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No. Orden.</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Inicio.</th>
                        <th>Fecha Entrega</th>
                        <th>Sucursal</th>
                        <th>Usuario</th>
                        <th>Estatus</th>
                        <th class="no-sort" style="text-align: center;">Acciones</th>

                    </tr>
                </thead>
            </table>
        </div>


        <div id="div_ciclo" class="row" style="display:none;" oprod="0">
            <div class="panel panel-default">
                <div id="ciclo_ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Ciclos de produccion</span></div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-4" style="height: 100%;">
                    <div class="form-group">
                        <div id="ciclo_Paso1" style="border-radius:0px;" class="panel panel-default">
                        <div style="padding:15px 0 10px 0; font-size:12px;background-color:#f4f4f4;border:1px solid #fff;" class="panel-body">
                            <div id="izqpasos">

                            </div>
                            <!--<div class="col-sm-12 p0">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 1
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p1" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Formulacion de insumos</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 2
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p2" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de insumos utilizados</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 3
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p3" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de peso de producto</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 4
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p4" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de personal</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 5
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p5" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de personal y maquinaria</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 6
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p6" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Generación de Lote</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 7
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p7" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de batch</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 8
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p8" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de laboratorio</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 9
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p9" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Fin de producción</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 10
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p10" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Envasado caja master</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 11
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p11" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Envio material de proceso</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 12
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p12" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Finalización de material a proceso</button>
                                </div>
                                
                            </div>
                            </div>
                            <div class="col-sm-12 p0" style="margin-top: 10px;">
                            <div class="form-group" id="panelprod">
                                <div class="col-sm-12" style="margin-bottom: 5px;">
                                    Paso 13
                                </div>
                                <div class="col-sm-12">
                                    <button id="ciclo_p13" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">Registro de linea de producción </button>
                                </div>
                                
                            </div>
                            </div>-->
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- fin bloque izquierdo -->
                    <!-- bloque derecho -->

                    <div id="block_paso1" class="col-sm-8" style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Formulacion de insumos</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="insumos_block1" class="col-sm-12 p0" style="margin-top: 10px;">

                                </div>

                                <div id="guardar_block1" class="col-sm-12 p0" style="margin-top: 10px;">
                                   
                                </div>
                            </div>
                            </div>
              
                        </div>
                        </div>

                    </div>

                    <div id="block_paso2" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de inusmos utilizados.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="insumos_block2" class="col-sm-12 p0" style="margin-top: 10px;">

                                </div>
                                <div id="guardar_block2" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso3" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de peso de producto.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="insumos_block3" class="col-sm-12 p0" style="margin-top: 10px;">

                                </div>
                                <div id="guardar_block3" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso4" class="col-sm-8" style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de personal.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="empleados_block4" class="col-sm-7 p0" style="margin-top: 10px; margin-right: 20px;">
                                    <select id="select_empleados4" style="width:100%;">
                                        <option value="0">Seleccione</option>
                                        <?php foreach ($empleados as $k => $v) { ?>
                                        <option area="<?php echo $v['nomarea']; ?>" value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?> (<?php echo $v['nomarea']; ?>)</option>
                                        <?php } ?>       
                                    </select>
                                     
                                </div>
                                <div id="empleados_block4" class="col-sm-4 p0" style="margin-top: 10px;">
                                    <button id="addPersonal4" style=" padding: 0px;" onclick="emp4();" class="btn btn-default btn-sm btn-block">Agregar</button>
                                </div>

                                <div id="personal_block4" class="col-sm-12 p0" style="margin-top: 10px;">
                                    <table id="tt4" class="table">
                                        <thead>
                                            <tr>
                                                <th width="80%">Nombre</th>
                                                <th width="20%">Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyempleado4">

                                        </tbody>
                                    </table>
                                </div>
                                <div id="guardar_block4" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso5" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de personal y maquinaria</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="personal_block5" class="col-sm-12 p0" style="margin-top: 10px;">
                                    <table id="tt5" class="table">
                                        <thead>
                                            <tr>
                                                <th width="60%">Nombre</th>
                                                <th width="40%">Maquinaria</th>

                                            </tr>
                                        </thead>
                                        <tbody id="bodyempleado5">
                                            <tr>
                                                <th width="100%">No tiene personal registrado, ir al paso 4</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="guardar_block5" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso6" class="col-sm-8" style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Generacion de lote</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="lote_block6" class="col-sm-12 p0" style="margin-top: 10px;">
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    No lote
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="lote6_nolote" type="text" name="" value="" class="form-control">
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    Fecha fabricacion
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="lote6_fechafab" type="text" name="" value="" class="form-control">
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    Fecha de caducidad
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="lote6_fechacad" type="text" name="" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div id="guardar_block6" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso7" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de Batch.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="insumos_block7" class="col-sm-12 p0" style="margin-top: 10px;">

                                </div>
                                <div id="guardar_block7" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso9" class="col-sm-8" style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Fin orden de produccion</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                            <div id="guardar_block9" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                            </div>
                            </div>
                            </div>
              
                        </div>
                        </div>

                    </div>

                    <div id="block_paso14" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Registro de merma.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="insumos_block14" class="col-sm-12 p0" style="margin-top: 10px;">

                                </div>
                                <div id="guardar_block14" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="block_paso15" class="col-sm-8"  style="display: none;">
                        <div class="panel panel-default">
                        <div id="ciclo_paso_ph"  class="panel-heading">Costos de produccion.</div>
                        <div class="panel-body"  style="font-size:12px;">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <div id="lote_block15" class="col-sm-12 p0" style="margin-top: 10px;">
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    Costo total material
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="costo15_material" type="text" name="" value="" class="form-control">
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    Costos adicionales
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="costo15_adicional" type="text" name="" value="" class="form-control">
                                    </div>
                                    <div class="col-sm-4" style="margin-top: 10px;">
                                    Costo total de producto terminado
                                    </div>
                                    <div class="col-sm-8" style="margin-top: 10px;">
                                        <input id="costo15_terminado" type="text" name="" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div id="guardar_block15" class="col-sm-12 p0" style="margin-top: 10px;">
                               
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    
                    <!-- fin bloque derecho -->
                </div>
            </div>
        </div>



        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div id="ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Orden de produccion</span></div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Usuario:</label>
                        <div class="col-sm-10" style="color:#096;">
                            <label id="userlog"><?php echo $username; ?></label>
                            <input type='hidden' id="iduserlog" value='<?php echo $iduser; ?>'>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Orden</label>
                        <div id="txt_nreq" class="col-sm-2" style="color:#ff0000;">
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha registro</label>
                        <div id="fechahoy" class="col-sm-2">
                            <input style="height:30px;width:100%" id="date_hoy" type="text" class="form-control">
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha entrega</label>
                        <div class="col-sm-2 text-left">
                            <input style="height:30px;width:100%" id="date_entrega" type="text" class="form-control">
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Prioridad</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_prioridad"  style="width:100%;">
                                <option value="0">Seleccione</option>
                              
                                    <option value="1">Alta</option>
                                    <option value="2">Baja</option>
                            
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">Sucursal</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_sucursal"  style="width:100%;">
                            <?php if($sucursales==0){ ?>
                            <option value="0">Seleccione</option>
                                <option value="0">No hay sucursales</option>
                            <?php }else{ ?>
                                <option value="0">Seleccione</option>
                              <?php foreach ($sucursales as $k => $v) { ?>
                                    <option value="<?php echo $v['idSuc']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php }} ?>
                            </select>


                        </div>
                        <label class="col-sm-2 control-label text-left">Solicitante</label>
                          <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_solicitante" style="width:100%;">
                                <option value="0">Seleccione</option>
                                <?php foreach ($empleados as $k => $v) { ?>
                                    <option area="<?php echo $v['nomarea']; ?>" value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?> (<?php echo $v['nomarea']; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-sm-2">
                        <input type="text" id="moneda_tc"  placeholder="Tipo de cambio" style="display:none;height:28px;">
                        </div>

                    </div>
                    </div>

                    <div id="addprodoexplo" class="col-sm-12" style="padding-top:15px;">
                        <div class="panel panel-default" style="border-radius:0px;">
                        <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;background-color:#f4f4f4;border:1px solid #fff;">
                            <div class="col-sm-12 p0">
                            <div id="panelprod" class="form-group">
                                <label class="col-sm-2 control-label text-left">Producto</label>
                                <div class="col-sm-8" style="color:#ff0000;">
                                    <select id="c_productos"  style="width:100%;">
                                        <option value="0">Seleccione</option>
                                        <?php foreach ($productos as $k => $v) { ?>
                                            <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2 text-left" >
                                    
                                    <button id="btn_addProd"  class="btn btn-default btn-sm btn-block"><span class="glyphicon glyphicon-plus"></span> Agregar producto</button>
                                </div>
                                
                            </div>
     
                            </div>
                        </div>
                        </div>

                    </div>

                    <div id="addprodoexplo2" class="col-sm-12" style="padding-top:30px;">
                        <div class="panel panel-default" style="border-radius:0px;">
                        <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;background-color:#f4f4f4;border:1px solid #fff;">
                            <div class="col-sm-12 p0">
                            <div id="panelprod" class="form-group">
                                <label id='tit' class="col-sm-2 control-label text-left">Requisicion</label>
                                
                                
                            </div>
     
                            </div>
                        </div>
                        </div>

                    </div>
                    <div id="panel_tabla" class="col-sm-12" style="padding: 15px 37px 15px 31px; display:none;">
                        <table width="100%" id="tablaprods" class="table table-hover">
                        <thead>
                          <tr>
                           <!--<th width="5%" align="left">Seg.</th>-->
                           <th width="10%" align="left">Codigo</th>
                           <th width="30%" align="left">Descripcion</th>
                           <th width="10%" align="left">Unidad</th>
                           <!--<th width="10%" align="left">$Unitario</th>-->
                           <th width="10%" align="left">Cantidad</th>
                           <!--<th width="10" align="left">Subtotal</th>-->
                           <th class="no-sort" width="15%" align="right">&nbsp;</th>                           
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right">&nbsp;</th>
                                <th colspan="2"></th>                                
                            </tr>
                        </tfoot>
                        <tbody id="filasprods">
                        </tbody>
                      </table>
                    </div>

                    <div id="panel_tabla2" class="col-sm-12" style="padding: 15px 37px 15px 31px; display:none;">
                        <table width="100%" id="tablaprods2" class="table table-hover">
                        <thead>
                          <tr>
                           <th width="10%" align="left">Codigo</th>
                           <th width="30%" align="left">Descripcion</th>
                           <th width="10%" align="left">Unidad</th>
                           <th width="10%" align="left">Proveedor</th>
                           <th width="10%" align="left">$Unitario</th>
                           <th width="10%" align="left">Cantidad</th>
                           <th width="10%" align="left">Existencias</th>
                           <th width="10" align="left" class="text-right">Subtotal</th>
                           <th class="no-sort" width="15%" align="right">&nbsp;</th>                           
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right">&nbsp;</th>
                                <th colspan="2"></th>                                
                            </tr>
                        </tfoot>
                        <tbody id="filasprods2">
                        </tbody>
                      </table>
                      <div class="col-sm-12" style="margin: -30px 0 20px 0;">
                          <div class="col-sm-10 text-right"><b>Total</b></div>
                          <div id="tttr" totlimpio="0.00" class="col-sm-2 text-right">0.00</div>
                      </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Observaciones</label>
                        <div class="col-sm-10" style="color:#ff0000;">
                            <textarea class="form-control" rows="3" id="comment"></textarea>
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <input id="cadenaCoti" type="hidden" value="">
                            <!--
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salirdddd</button> 
                        -->
                            <!--<button id="btn_imprimir" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-print"></span> Imprimir</button>-->

                            <button id="btn_savequit_usar" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Utilizar inusmos existentes</button>
    
                            <button id="btn_savequit" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Generar Orden de produccion</button>

                        </div>
                    </div>
                    </div>
                    
                    <div id="error_1"></div>

                    <div id="data_almacen" style="display:none;">
                        <select id="c_almacen" style="width:100px;">
                            <?php foreach ($empleados as $k => $v) { ?>
                                <option value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                 </div>
                 </div>             

        </div>
      
<!-- CHRIS - COMENTARIOS
============================= 
//Librerias genericas 
-->

<script src="../../libraries/jquery.min.js" type="text/javascript"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>

<!-- CHRIS - COMENTARIOS
============================= 
//Librerias raiz appministra 
-->
<script src="js/numeric.js" type="text/javascript"></script>
<script src="js/moneda.js" type="text/javascript"></script>
<script src="js/datatables.min.js" type="text/javascript"></script>
<script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>




</body>

<script>


var table = '';

// var desc config
var ctipodesc = clspp = clspc = clgp = clgc = pass = '';
var siDescG = 0;
var auxsiDescG = 0;
var auxnew = 1;

$( document ).ready(function() {

});
// var desc config fin


function abrirNueva(option){
    window.parent.agregatab("../../modulos/appministra/index.php?c=produccion&f=prerequisito","Pre-Requisiciones","",2392);
}

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    $(function() {


        listareq();
        $('#precionuevo').numeric();
        $('#date_entrega').datepicker({
                format: "yyyy-mm-dd",
                language: "es"
        });

        $('#date_hoy').datepicker({
                format: "yyyy-mm-dd",
                language: "es",
                startDate:new Date()

        }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#date_entrega').val("");
        minDate.setDate(minDate.getDate() + 1);

        $('#date_entrega').datepicker('setStartDate', minDate);
    });
              
        var table = $('#tablaprods').DataTable();

       var today = new Date();
              today.setDate(today.getDate() + 1);

var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){
    dd='0'+dd;
} 
if(mm<10){
    mm='0'+mm;
} 
var today = yyyy+'/'+mm+'/'+dd;
         $('#date_entrega').val(today);
                var today = new Date();

var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){
    dd='0'+dd;
} 
if(mm<10){
    mm='0'+mm;
} 
var today = yyyy+'/'+mm+'/'+dd;


         $('#date_hoy').val(today);

        //Solucion al scroll-y
        $('#tablaprods_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
        
        $('#tablaprods_info').css('margin-to','-10px');

        $('#c_productos').select2();


        $('#c_proveedores').select2({ width: '300px' });
        $('#c_solicitante').select2();
        $('#c_prioridad').select2();
        $('#c_tipogasto').select2();
        $('#c_area').select2();
        $('#c_almacen').select2();
        
        $('#c_sucursal').select2();
        $('footer div').remove();

        $("#c_solicitante").change(function() {

        });
        



        $( "#c_productos" ).change(function() {
            $('#btn_addProd').trigger('click');
        });

        
        $("#btn_imprimir").click(function() {
            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_imprimir','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();
            cliente=$('#c_cliente').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");

            almacen=$('#c_almacen').val();


            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();


          

            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            

            if(moneda==1){
                moneda_tc=0;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            

            

            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }


            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(cliente==0 && deten==0){ 
                alert('Tienes que seleccionar un cliente'); 
                deten=1;
            }else if(fechahoy=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha'); 
                deten=1;
            }else if(fechaentrega=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de entrega'); 
                deten=1;
            }else if(tipogasto==0 && deten==0){ 
                alert('Tienes que seleccionar un tipo de gasto'); 
                deten=1; 
            }else if(moneda==0 && deten==0){ 
                alert('Tienes que seleccionar una moneda'); 
                deten=1; 
            }else if(moneda_tc=='' && deten==0 && moneda>1){ 
                alert('Tienes que ingresar un tipo de cambio'); 
                deten=1; 
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }else if(almacen==0 && deten==0){ 
                alert('Tienes que seleccionar un almacen'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Generar Orden de produccion');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');

                precio = $(this).find('select').val();

                tftf = precio.split('>');

                precio = tftf[0];
                idlista= tftf[1];

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+precio+'>#'+idlista+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
  

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_imprimir','<span class="glyphicon glyphicon-print"></span> Imprimir');
                return false;
            }else{
                imps=$('#imps').html();
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_imprimir",
                    type: 'POST',
                    data:{
                        cliente:cliente,
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        proveedor:proveedor,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        almacen:almacen,
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        imps:imps
                    },
                    success: function(r){
                        window.open('../../modulos/appministra/ticketImp.php','');
                         enabled_btn('#btn_imprimir','<span class="glyphicon glyphicon-print"></span> Imprimir');
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

$("#btn_savequit_usar").click(function() {
    disabled_btn('#btn_savequit_usar','Procesando...');
    id_op = $('#txt_nreq').text();
    iduserlog = $('#iduserlog').val(); //si

    $.ajax({
        url:"ajax.php?c=produccion&f=a_guardarUsar",
        type: 'POST',
        data:{id_op:id_op,iduserlog:iduserlog},
        success: function(r){
            if(r>0){
                table = $('#tablaprods').DataTable();
                table.clear().draw();
                $('#nreq').css('display','none');
                resetearReq();
                
                    $('#modal-confusar').modal('show');
                    enabled_btn('#btn_savequit_usar','Utilizar insumos existentes');
                    listareq();
                
            }else{
                alert('Error de conexion');
                 enabled_btn('#btn_savequit_usar','Utilizar insumos existentes');
                
            }
        }

    });

});

        $("#btn_savequit").click(function() {
         

            //disabled_btn('#btn_savequit','Procesando...');
            
            iduserlog = $('#iduserlog').val(); //si
            option = $('#ph span').attr('opt'); //si
            id_op = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();
            orden =  $('#orden').val();


            // alert('iduserlog:'+iduserlog);
            // alert('option:'+option);
            // alert('id_req:'+id_op);
            // alert('idrequi:'+idrequi);
            // return false;

            fecha_registro=$('#date_hoy').val();
            fecha_entrega=$('#date_entrega').val();
               sol=$('#c_solicitante').val();
            prioridad=$('#c_prioridad').val();
            sucursal=$('#c_sucursal').val();
            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />"); 
            sol=$('#c_solicitante').val();
            
            
            deten=0;
            if(prioridad==0){ 
                alert('Tienes que seleccionar una prioridad'); 
                deten=1;
            }else if(sucursal==0 && deten==0){ 
                alert('Tienes que seleccionar una sucursal'); 
                deten=1;
            }else if(fecha_registro=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de registro'); 
                deten=1;
            }else if(fecha_entrega=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de entrega'); 
                deten=1;
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Generar Orden de produccion');
                return false;
            }


            detenertodo=0;

            if(option==3){
                totalinsumos = $('#tttr').attr('totlimpio');
                totalinsumos=(totalinsumos*1);
                if(totalinsumos==0){
                    alert('No se puede continuer, el total debe ser mayor a 0');
                    return false;
                }
                idsProductos = $('#filasprods2 tr').map(function() {
                    eshead=$(this).attr('eshead');
                    if(eshead==2){
                        detenertodo++;
                    }
                    if(eshead==1){
                        
                    }else{

                        trid = this.id;
                        id = trid.split('tr_');
                        masids =id[1].split('_');
                        idpadre=masids[0];
                        idProd=masids[1];


                        cant = $(this).find('#valCantidad').text();
                        cant=cant*1;

                          uni = $(this).find('.numeros').val();
                           uni=uni*1;

                        lala = $(this).find('#cmbProv_'+idpadre+'_'+idProd).val();

                        if (typeof lala !== "undefined") {
                            jj=lala.split('-');
                            idProv=jj[0];
                            
                        }else{
                            idProv=0;
                        }

                        //precio = $(this).find('select').val();

                        //desc
                        // oldp = $(this).attr('oldp');
                        // tipodesc = $(this).attr('tipodesc');
                        // montod = $(this).attr('montod');                            
                        //

                        //tftf = precio.split('>');

                        // precio = tftf[0];
                        // idlista= tftf[1];

                        // alert('idProv: '+idProv);
                        // alert('idpadre: '+idpadre);
                        // alert('idProducto: '+idProd);
                        // alert('cantidad: '+cant);

                        if (typeof idpadre !== "undefined" && typeof idProd !== "undefined") {
                            id= idProv+'>'+idpadre+'>'+idProd+'>'+cant+'>'+uni;
                        }
                        
                        return id;
                    }
                }).get().join('--c--');
            }else{
                idsProductos = $('#filasprods tr').map(function() {
                    cant = $(this).find('.numeros').val();
                    //ch = $(this).attr('ch');

                    //precio = $(this).find('select').val();

                    //desc
                    // oldp = $(this).attr('oldp');
                    // tipodesc = $(this).attr('tipodesc');
                    // montod = $(this).attr('montod');                            
                    //

                    //tftf = precio.split('>');

                    // precio = tftf[0];
                    // idlista= tftf[1];

                    trid = this.id;
                    id = trid.split('tr_');

                    if (typeof id[1] !== "undefined") {
                        id= id[1]+'>'+cant;
                    }
                    
                    return id;
                }).get().join('--c--');
            }  

            if(detenertodo>0){
                alert('No puede continuar ya que hay productos que no cuentan con insumos registrados');
                return false;
            }


            ttt=$('#tttr').attr('totlimpio');

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Generar Orden de produccion');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=produccion&f=a_guardarOrdenP",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        fecha_registro:fecha_registro,
                        fecha_entrega:fecha_entrega,
                        prioridad:prioridad,
                        sucursal:sucursal,
                        option:option,
                        obs:obs,
                        iduserlog:iduserlog,
                        id_op:id_op,
                        ttt:ttt,
                        orden:orden,
                          sol:sol

                    },
                    success: function(r){ // retonrna id de cotizacion 
                        console.log(r);                        
                        if(r>0 || r=='p'){
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            if(r=='p'){
                                $('#modal-confexp').modal('show');
                                listareq();
                            }else{
                                $('#modal-conf3').modal('show');
                                listareq();
                            }
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_savequit','Generar Orden de produccion');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

        $('.numeros').on('input', function() {
            alert('cambios');
        });

        $("#div_productos").click(function() {
            var isDisabled = $('#c_productos').prop('disabled');
            if(isDisabled==1){
                $('#select2-c_proveedores-container').css('border-color','#ff0000');
                alert('Seleccione un proveedor');
            }
        });  

        $('#btn_addProdx').on( 'click', function () {
            
        } );



        $("#btn_addProd").click(function() { 
            /*
            idCliente = $('#c_cliente').val();
            if(idCliente==0){
                alert('Para agregar productos tienes que seleccionar un cliente');
                return false;
            }
            */
            idProducto = $('#c_productos').val();
            
            

            if(idProducto>0){
                d=$('#filasprods tr').find('td').not(".dataTables_empty").length;
                disabled_btn('#btn_addProd','Procesando...');
                if($("#tr_"+idProducto).length) {
                    valorig = $("#tr_"+idProducto+" input").val();
                    $("#tr_"+idProducto+" input").val((valorig*1)+1);
                    //refreshCants(idProducto,0,0);
                    enabled_btn('#btn_addProd','Agregar producto');
                    return false;
                } 

if(d>0){
                    alert('Solo puedes agregar un articulo por orden de produccion');
                    enabled_btn('#btn_addProd','Agregar producto');
                    return false;
                }

                $.ajax({
                url:"ajax.php?c=produccion&f=a_addProductoProduccion",
                type: 'POST',
                dataType:'JSON',
                data:{idProducto:idProducto},
                success: function(r){
                    console.log(r);
                    if(r.success==1){
                            /*
                            $('#c_cliente').prop('disabled',true);
                            $('#c_proveedores').prop('disabled',true);
                            $('#c_almacen').prop('disabled',true);
                            $('#c_moneda').prop('disabled',true);
                            data_almacen=$('#data_almacen').html();
                            txt_proveedor=$('#c_proveedores option:selected').text();
                            txt_almacen=$('#c_almacen option:selected').text();
                            */
                            
                                //btndescP = '';  /// ELIMINAR
/*
                            if(ctipodesc == 2 || ctipodesc == 3){
                                    var btndescP = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+r.datos[0].id+",0,\""+r.datos[0].descripcion_corta+"\");'>Descuento</button>";                                    
                                }else{
                                    var btndescP =''; /// ELIMINAR
                                }
                                */
                            var btndescP ='';

                            if(r.datos[0].minimo===null){
                                r.datos[0].minimo=0;
                            }
                            

                            var Rowdata = "<tr ch='0' newp='0' oldp='0' tipoDesc='0' montoD='0' id='tr_"+r.datos[0].id+"'><!--<td>0</td>--><td>"+r.datos[0].codigo+"</td><td style='cursor:pointer;' onclick='modalDescuento("+r.datos[0].id+",0);'>"+r.datos[0].descripcion_corta+"</td><td>"+r.datos[0].clave+"</td><!--<td id='valUnit'>"+r.adds+"</td>--><td><input style='width:60%;' class='numeros' type='text' fact='"+r.datos[0].factor+"' min='"+r.datos[0].minimo+"' value='"+r.datos[0].minimo+"'/></td><!--<td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td>--><td><button onclick='removeProdReq("+r.datos[0].id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"+btndescP+"</td></tr>";

                            table.row.add($(Rowdata)).draw();

                            
                            $('#panel_tabla').css('display','block');
                            $('.numeros').numeric();
                            enabled_btn('#btn_addProd','Agregar producto');
                            //refreshCants(idProducto,0,0);
                            cadcarAux = 0;
                        
                        $('.numeros').change(function() { 
                            if($(this).attr( "min" )=='null'){minimo=0;}
                                else{minimo=$(this).attr( "min" );}

                            if($(this).attr( "fact" )=='null' || $(this).attr( "fact" )==''){f=0;}
                                else{f=$(this).attr( "fact" );}

                               

                            if($(this).val()*1<minimo*1){
                                alert("Cantidad es menor al minimo permitido");
                                $(this).val(minimo);
                            }

                             if(f>0){
                                    if($(this).val() % f == 0){
                                    }else{
                                        alert('La cantidad solo pueden ser multiplos del factor seleccionado para este producto ('+f+')');
              
                                        $(this).val(f);
                                    }
                                }
                                
                        });


                    }else{
                        alert('Error al agregar producto');
                    }

                }
                });
            }else{
                alert('Selecciona un producto valido');
            }
        });

    

    });//*****FIN READY****





        function imprimir2(req,modo){
            $('#btn_imprimir_'+req+'_').prop('disabled',true);
            $.ajax({
                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                type: 'POST',
                data:{
                    idCoti:req,
                    modo:modo,
                    print:1,
                    tipo:'Cotizacion'

                },
                success: function(r){
                    window.open("../../modulos/cotizaciones/cotizacionesPdf/cotizacion_"+req+".pdf");
                    $('#btn_imprimir_'+req+'_').prop('disabled',false);
                }
            });
        }

    function eliminarOP(idop){

        $('#modal-confdelop').modal('show').one('click', '#modal-confdelop-uno', function(){
            $.ajax({
                url:"ajax.php?c=produccion&f=a_eliminaOP",
                type: 'POST',
                dataType:'JSON',
                data:{idop:idop},
                success: function(r){
                    if(r==1){
                        $('#modal-confdelop').modal('hide');
                        listareq();

                        //resetearReq();
                        //$('#txt_nreq').text(r.requisicion);
                        //$('#nreq_load').css('display','none');
                        //$('#nreq').css('display','block');
                    }else{
                        $('#modal-confdelop').modal('hide');
                        alert('No se puede inactivar esta cotizacion');
                    }
                }
            });
        }).one('click', '#modal-confdelop-dos', function(){
            $('#modal-confdelop').modal('hide');

        });

    }

    function eliminaReq(idReq){

        $('#modal-conf4').modal('show').one('click', '#modal-btnconf4-uno', function(){
            $.ajax({
                url:"ajax.php?c=ventas&f=a_eliminaRequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{idReq:idReq},
                success: function(r){
                    if(r==1){
                        $('#modal-conf4').modal('hide');
                        listareq();

                        //resetearReq();
                        //$('#txt_nreq').text(r.requisicion);
                        //$('#nreq_load').css('display','none');
                        //$('#nreq').css('display','block');
                    }else{
                        $('#modal-conf4').modal('hide');
                        alert('No se puede inactivar esta cotizacion');
                    }
                }
            });
        }).one('click', '#modal-btnconf4-dos', function(){
            $('#modal-conf4').modal('hide');

        });

    
    }

    function tdTotals(idProducto){
        if($("#tr_totals").length ) {
            total=0;
            $(".valImporte").each(function( index ) {
                totfila = $(this).attr('implimpio');
                totfila = totfila.replace("/,/g", ""); 
                totfila=totfila*1;
                total+=totfila;
            });

            $('#sumatotales').text(total).currency();
            return false;
        } 

        filasprod="<tr id='tr_totals'>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td align='right'><b>Total:</b></td>\
                    <td id='sumatotales'>0.00</td>\
                    <td>MXN</td>\
                    <td>&nbsp;</td>\
                  </tr>";
        $('#filasprods').append(filasprod);
        tt = $('#valImporte').attr('implimpio'); tt=tt*1;
        $('#sumatotales').text(tt).currency();
        
    } 

    function resetearReq(){
        $('#btn_vercli').css('visibility','hidden');

        $('tbody').empty();
        $('#c_solicitante').find('option[value="0"]').prop('selected', true); 
        $('#c_solicitante').select2();
        $('#c_tipogasto').find('option[value="0"]').prop('selected', true); 
        $('#c_tipogasto').select2();
        $('#c_moneda').find('option[value="0"]').prop('selected', true); 
        $('#c_moneda').select2();
        
        $('#c_almacen').find('option[value="0"]').prop('selected', true); 
        $('#c_almacen').select2();
        //$('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#c_productos').select2();
        //$('#c_productos').prop('disabled',true);
        $('#moneda_tc').css('display','none');
        $('#moneda_tc').val('');
        $('#panel_tabla').css('display','none');
        $('#c_proveedores').prop('disabled',false);
        $('#c_almacen').prop('disabled',false);
        $('#c_solicitante').prop('disabled',false);
        $('#c_tipogasto').prop('disabled',false);
        $('#c_moneda').prop('disabled',false);
        $('#comment').prop('disabled',false);
        $('#date_entrega').prop('disabled',false);
        $('#date_hoy').prop('disabled',false);
        $('#comment').val('');
        //$('#date_entrega').val('');
        //$('#date_hoy').val('');
        $('#c_cliente').find('option[value="0"]').prop('selected', true); 
        $('#c_cliente').select2();
        $('#c_cliente').prop('disabled',false);



        $('#c_prioridad').prop('disabled',false);
        $('#c_sucursal').prop('disabled',false);

       // $('#date_entrega').val('');
        //$('#date_hoy').val('');
        //$("#date_entrega").datepicker("setDate", new Date());
        //$("#date_hoy").datepicker("setDate", new Date());
        $('#c_tipogasto').val("6").trigger("change");
        $('#c_moneda').val("1").trigger("change");
        enabled_btn('#btn_savequit','Generar Orden de produccion');
        enabled_btn('#btn_addProd','Agregar producto');


        $('#panel_tabla').css('display','block');
        $('#panel_tabla2').css('display','none');

        $('#addprodoexplo').css('display','block');
        $('#addprodoexplo2').css('display','none');

    }

    function nreq(){
        $('#div_ciclo').css('display','none');
        $('#btn_savequit_usar').css('visibility','hidden');
        resetearReq();

/*
        span = $('#ph').find('span').text();
        alert(span);
        if($('#nreq').is(':visible') && span!='Visualizar orden de produccion') {

            $('#modal-conf1').modal('show');
            $('#modal-btnconf1-uno').on('click',function(){
                table = $('#tablaprods').DataTable();
                table.clear().draw();
                $('#modal-conf1').modal('hide');
                $('#nreq').css('display','none');
                $('#nreq_load').css('display','block');
                $.ajax({
                    url:"ajax.php?c=produccion&f=a_nuevaorden",
                    type: 'POST',
                    dataType:'JSON',
                    data:{ano:1},
                    success: function(r){
                        if(r.success==1){
                            resetearReq();
                            $('#txt_nreq').text(r.op);
                            $('#nreq_load').css('display','none');
                            $('#userlog').text('<?php echo $username; ?>');
                            $('#iduserlog').text('<?php echo $iduser; ?>');
                            $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Orden de produccion</span>');
                            $('#nreq').css('display','block');
                        }else{
                            alert('No se pueden cargar cotizaciones');
                        }
                    }
                });
            });
            $('#modal-btnconf1-dos').on('click',function(){
                $('#modal-conf1').modal('hide');
                return false;
            });
        }else{
            */

            $('#btnlistorden').css('visibility','hidden');
            $('#btnback').css('visibility','visible');


            table = $('#tablaprods').DataTable();
            table.clear().draw();


            $('#listareq').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=produccion&f=a_nuevaorden",
                type: 'POST',
                dataType:'JSON',
                data:{ano:1},
                success: function(r){
                    if(r.success==1){
                        resetearReq();
                        $('#txt_nreq').text(r.op);
                        $('#nreq_load').css('display','none');
                        $('#userlog').text('<?php echo $username; ?>');
                        $('#iduserlog').text('<?php echo $iduser; ?>');
                        $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Orden de produccion</span>');
                        $('#nreq').css('display','block');
                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });
       // }
        
    }

    function vercomcli(op){
        if(op==0){
        
            c=$('#cadenaCoti').val();
        }else{
        
            c=op;
        }
        p='c';
        window.open("../../../coti/index.php?c="+c+'&p='+p);
    }

    function explosionMat(idop,orden){

        $('#div_ciclo').css('display','none');
        //$('#btn_savequit').text('Generar Pre-Requisicion');

         if(orden=='0'){


         $('#btn_savequit').text('Generar Requisicion');
 $('#orden').val('0');
     }
         else{  $('#btn_savequit').text('Generar Orden');

                  $('#tit').text('Orden de Compra');          


            $('#orden').val('1');
     }


        $('#btnlistorden').css('visibility','hidden');
        $('#btnback').css('visibility','visible');

        table = $('#tablaprods2').DataTable();
        table.destroy();

        $('#listareq').css('display','none');
        $('#modal-conf1').modal('hide');
        $('#nreq').css('display','none');
        $('#nreq_load').css('display','block');
        $('#panel_tabla').css('display','none');
        $('#panel_tabla2').css('display','block');
        $('#nreq').css('display','block');


        $('#addprodoexplo').css('display','none');
        $('#addprodoexplo2').css('display','block');


        //$('#ph').html('<span opt="3" class="label label-warning" style="cursor:pointer;">Explosion de materiales - Prerequisiciones</span>');
    if(orden=='0'){
$('#ph').html('<span opt="3" class="label label-warning" style="cursor:pointer;">Explosion de materiales - Requisiciones</span>');}
            else{
$('#ph').html('<span opt="3" class="label label-warning" style="cursor:pointer;">Explosion de materiales - Ordenes</span>');}

        $.ajax({
            url:"ajax.php?c=produccion&f=a_explosionMat",
            type: 'POST',
            dataType:'JSON',                                
            data:{idop:idop},
            success: function(r){

                if(r.success==1){

                    $('#userlog').text(r.requisicion.username);
                        $('#iduserlog').val(r.requisicion.idempleado);
                        $('#txt_nreq').text(r.requisicion.id);
                        $('#nreq_load').css('display','none');
                        $("#c_prioridad").val(r.requisicion.prioridad).trigger("change");
                        $("#c_sucursal").val(r.requisicion.idsuc).trigger("change");
                        $("#date_hoy").val(r.requisicion.fi);
                        $("#date_entrega").val(r.requisicion.fe);
 $("#c_solicitante").val(r.requisicion.idsol).trigger("change");
                        $("#c_prioridad").prop('disabled',true);
                        $("#c_sucursal").prop('disabled',true);
                        $("#date_hoy").prop('disabled',true);
                        $("#date_entrega").prop('disabled',true);
                        $("#comment").prop('disabled',true);
   $("#c_solicitante").prop('disabled',true);
                        //$('#panelprod').css('display','none');
                        $('#panelexplosion').css('display','block');

                        
                        var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");

                        $("#comment").val(comment);

                        // txt_proveedor=$('#c_proveedores option:selected').text();
                        // txt_almacen=$('#c_almacen option:selected').text();

                        //table = $('#tablaprods').DataTable();
                    $.each(r.productos, function( k, v ) {
                        //eliminProd="<button onclick='removeProdReq("+v.id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";

                        eliminProd='';

                        Rowdata1="<tr ch='0' id='tr_"+v.id+"' eshead='1' style='background-color:#eee;'><td colspan='9'><b>Orden de produccion:</b> "+v.nomprod+"</td></tr>";

                        $('#filasprods2').append(Rowdata1);

                        if(v.insumos!=0){
                            usar=0;
                            $.each(v.insumos, function( k2, v2 ) {
                                cant_total=v2.cantidad*v.cantidad;

                                if(v2.existencias<cant_total){
                                    usar++;
                                    ext='<font color="#ff0000">'+v2.existencias+'</font>';
                                }else{
                                    ext='<font color="#096">'+v2.existencias+'</font>';
                                }
                                //subtotal=cant_total*v2.costo;
                                Rowdata="<tr ch='0' id='tr_"+v.id+"_"+v2.idProducto+"' eshead='0'><td>"+v2.codigo+"</td><td>"+v2.nombre+"</td><td>"+v2.unidad_clave+"</td><td id='valUnit'>"+v2.listprovs+"</td><td><input style='width:60%;' class='numeros' type='text' value='0' disabled /></td><td class='valCantidad' id='valCantidad'>"+cant_total+"</td><td class='exxxis'>"+ext+"</td><td class='text-right' id='ttt' implimpio='0'>0.00</td><td>"+eliminProd+"</td></tr>";
                                $('#filasprods2').append(Rowdata);

                            });
                        }else{
                            Rowdata="<tr ch='0' id='tr_"+v.id+"' eshead='2'><td colspan='8'>Este producto no tiene insumos registrados</td></tr>";
                            $('#filasprods2').append(Rowdata);
                        }
                        
                        if(usar==0){
                            $('#btn_savequit_usar').css('visibility','visible');
                        }else{
                            $('#btn_savequit_usar').css('visibility','hidden');
                        }
                        
                        //table.row.add($(Rowdata)).draw();
                        //refreshCants(v.id,v.caracteristica,0);
                        
                        
                        //table.row.add($(Rowdata1)).draw();
                        

                    });
                }

            }
        });
    }

    function savepaso(accion,idop,paso){

        if(accion==1){
            faltan=0;
            idsProductos = $('#insumos_block1 input').map(function() {
                cant = $(this).val();
                cantexist = $(this).attr('existen1');

                if( (cant*1)>(cantexist*1) ){
                    faltan++;
                }
                idinput = $(this).attr('id');
                spli1 = idinput.split('b1_');
                spli2 = spli1[1].split('_');
                idPadre=spli2[0];
                idHijo=spli2[1];

                if (typeof idPadre !== "undefined") {
                    id= idPadre+'>#'+idHijo+'>#'+cant;
                }
                
                return id;
            }).get().join('___');

            if(faltan>0){
                alert('No hay existencias');
                return false;
            }

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro produccion iniciado con exito');
                        ciclo(idop);
                    }

                }
            });
        } 

        if(accion==2){
            idsProductos = $('#insumos_block2 input').map(function() {
                cant = $(this).val();
                idinput = $(this).attr('id');
                spli1 = idinput.split('b2_');
                spli2 = spli1[1].split('_');
                idPadre=spli2[0];
                idHijo=spli2[1];

                if (typeof idPadre !== "undefined") {
                    id= idPadre+'>#'+idHijo+'>#'+cant;
                }
                
                return id;
            }).get().join('___');

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        } 

        if(accion==3){
            idsProductos = $('#insumos_block3 input').map(function() {
                peso = $(this).val();
                idinput = $(this).attr('id');
                spli1 = idinput.split('b3_');
                spli2 = spli1[1].split('_');
                idPadre=spli2[0];
                idHijo=spli2[1];

                if (typeof idPadre !== "undefined") {
                    id= idPadre+'>#'+idHijo+'>#'+peso;
                }
                
                return id;
            }).get().join('___');

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro peso guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        }

        if(accion==14){
            idsProductos = $('#insumos_block14 input').map(function() {
                merma = $(this).val();
                idinput = $(this).attr('id');
                spli1 = idinput.split('b14_');
                spli2 = spli1[1].split('_');
                idPadre=spli2[0];
                idHijo=spli2[1];

                if (typeof idPadre !== "undefined") {
                    id= idPadre+'>#'+idHijo+'>#'+merma;
                }
                
                return id;
            }).get().join('___');

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro merma guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        }

        if(accion==7){
            idsProductos = $('#insumos_block7 input').map(function() {
                peso = $(this).val();
                idinput = $(this).attr('id');
                spli1 = idinput.split('b7_');
                spli2 = spli1[1].split('_');
                idPadre=spli2[0];
                idHijo=spli2[1];

                if (typeof idPadre !== "undefined") {
                    id= idPadre+'>#'+idHijo+'>#'+peso;
                }
                
                return id;
            }).get().join('___');

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro de batch guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        } 

        if(accion==4){
            idsProductos = $('#bodyempleado4 tr').map(function() {

                idinput = $(this).attr('id');
                spli1 = idinput.split('_');
                idEmpleado=spli1[2];

                //maq = $('#bodyempleado5 tr[id="tr_empp_'+idEmpleado+'"]').find('input').val();

                //if (typeof maq === "undefined") {
                    maq='';
                //}

                if (typeof idEmpleado !== "undefined") {
                    id= idEmpleado+'>#'+maq;
                }
                
                return id;
            }).get().join('___');
            if(idsProductos==''){
                alert('Tienes que agregar personal');
                return false;
            }


            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro personal guardado con exito');
                        ciclo(idop);
                        /*$.ajax({
                            url:"ajax.php?c=produccion&f=a_listaPaso5",
                            type: 'POST',
                            dataType: 'json',
                            data:{
                                idop:idop
                            },
                            success: function(r){
                                if(r.tr5==0 || r.tr5==''){
                                    $('#personal_block5').html('No hay personal dado de alta');
                                    $('#bodyempleado5').html('');
                                    $('#guardar_block5').html('');
                                }else{
                                    $('#personal_block5').html('<table id="tt5" class="table">\
                                        <thead>\
                                            <tr>\
                                                <th width="60%">Nombre</th>\
                                                <th width="40%">Maquinaria</th>\
                                            </tr>\
                                        </thead>\
                                        <tbody id="bodyempleado5">\
                                            <tr>\
                                                <th width="100%">No tiene personal registrado, ir al paso 4</th>\
                                            </tr>\
                                        </tbody>\
                                    </table>');
                                    $('#bodyempleado5').html(r.tr5);
                                    $('#guardar_block5').html('<div class="col-sm-3"><button id="save_block5"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(5,'+idop+')">Guardar</button></div>');
                                }

                            }
                        });*/
                    }

                }
            });
        }

        if(accion==5){
            idsProductos = $('#bodyempleado5 tr').map(function() {

                idinput = $(this).find('input').attr('id');
                maquina = $(this).find('input').val();
                spli1 = idinput.split('_');
                idMaq=spli1[1];

                if (typeof idMaq !== "undefined") {
                    id= idMaq+'>#'+maquina;
                }
                
                return id;
            }).get().join('___');

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    idsProductos:idsProductos,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro personal y maquinaria guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        } 

        if(accion==6){
            lote6_nolote = $('#lote6_nolote').val();
            lote6_fechafab = $('#lote6_fechafab').val();
            lote6_fechacad = $('#lote6_fechacad').val();

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    lote6_nolote:lote6_nolote,
                    lote6_fechafab:lote6_fechafab,
                    lote6_fechacad:lote6_fechacad,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro lote guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        }

        if(accion==15){
            costo15_adicional = $('#costo15_adicional').val();
            costo15_terminado = $('#costo15_terminado').val();


            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    costo15_adicional:costo15_adicional,
                    costo15_terminado:costo15_terminado,
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro lote guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        }

        if(accion==9){

            $.ajax({
                url:"ajax.php?c=produccion&f=a_guardarPaso",
                type: 'POST',
                data:{
                    accion:accion,
                    paso:paso,
                    idop:idop
                },
                success: function(r){
                    if(r>0){
                        alert('Registro Fin de produccion guardado con exito');
                        ciclo(idop);
                    }

                }
            });
        } 

    }

    function clipaso(paso,accion,idop){

        $('#block_paso1').css('display','none');
        $('#block_paso2').css('display','none');
        $('#block_paso3').css('display','none');
        $('#block_paso4').css('display','none');
        $('#block_paso5').css('display','none');
        $('#block_paso6').css('display','none');
        $('#block_paso7').css('display','none');
        $('#block_paso8').css('display','none');
        $('#block_paso9').css('display','none');
        $('#block_paso10').css('display','none');
        $('#block_paso11').css('display','none');
        $('#block_paso12').css('display','none');
        $('#block_paso13').css('display','none');
        $('#block_paso14').css('display','none');
        $('#block_paso15').css('display','none');


        $.ajax({
            url:"ajax.php?c=produccion&f=a_clipaso",
            type: 'POST',
            dataType:'JSON',                                
            data:{idop:idop,paso:paso,accion:accion},
            success: function(r){

                if(r.success==1){

                    if(accion==1){
                        cad1='<div class="col-sm-4" style="margin-top: 10px;">\
                            <b>Producto</b>\
                        </div>\
                        <div class="col-sm-4" style="margin-top: 10px;">\
                            <b>Cantidad necesaria</b>\
                        </div>\
                        <div class="col-sm-4" style="margin-top: 10px;">\
                            <b>Existencias</b>\
                        </div>';
                        $.each(r.data, function(k,v) {
                            cad1+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v.nombre+'\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px;">\
                                <input existen1="'+v.existen+'" id="b1_'+v.idop+'_'+v.idProducto+'"  readonly type="text" name="" value="'+v.cantidad+'" class="form-control">\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px; height:40px;">\
                                '+v.existen+'\
                            </div>';

                        });


                        $('#insumos_block1').html(cad1);
                        $('#insumos_block1 input').numeric();
                        $('#guardar_block1').html('<div class="col-sm-3"><button id="save_block1"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Utilizar insumos</button></div>');

                        $('#block_paso1').css('display','block');
                    }

                    if(accion==2){
                        cad2='';
                        $.each(r.data, function(k,v) {
                            cad2+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b2_'+v.idop+'_'+v.idProducto+'" type="text" name="" value="'+v.cantidad2+'" class="form-control">\
                            </div>';

                        });



                        $('#insumos_block2').html(cad2);
                        $('#insumos_block2 input').numeric();
                        $('#guardar_block2').html('<div class="col-sm-3"><button id="save_block2"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');

                        $('#block_paso2').css('display','block');
                    }

                    if(accion==3){
                        cad3='';
                        $.each(r.data, function(k,v) {
                            cad3+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b3_'+v.idop+'_'+v.idProducto+'" type="text" name="" value="'+v.peso+'" class="form-control">\
                            </div>';

                        });



                        $('#insumos_block3').html(cad3);
                        $('#insumos_block3 input').numeric();
                        $('#guardar_block3').html('<div class="col-sm-3"><button id="save_block3"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');

                        $('#block_paso3').css('display','block');
                    }

                    if(accion==14){
                        cad14='';
                        $.each(r.data, function(k,v) {
                            cad14+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b14_'+v.idop+'_'+v.idProducto+'" type="text" name="" value="'+v.merma+'" class="form-control">\
                            </div>';

                        });



                        $('#insumos_block14').html(cad14);
                        $('#insumos_block14 input').numeric();
                        $('#guardar_block14').html('<div class="col-sm-3"><button id="save_block14"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');

                        $('#block_paso14').css('display','block');
                    }

                    if(accion==4){
                        $('#block_paso4').css('display','block');
                        $('#guardar_block4').html('<div class="col-sm-3"><button id="save_block4"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');
                        $('#bodyempleado4').html(r.data);
                    }
                    if(accion==5){

                        $('#block_paso5').css('display','block');
                        if(r.data==''){
                            $('#guardar_block5').html('<div class="col-sm-3"><button id="save_block5"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Siguiente paso</button></div>');
                            $('#personal_block5').html('No hay personal dado de alta');
                        }else{
                            $('#guardar_block5').html('<div class="col-sm-3"><button id="save_block5"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');
                            $('#bodyempleado5').html(r.data);
                            
                        }

                    }

                    if(accion==6){
                        $('#block_paso6').css('display','block');
                        $('#guardar_block6').html('<div class="col-sm-3"><button id="save_block6"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');
                        $("#lote6_fechafab").datepicker({ format: "yyyy-mm-dd",language: "es"});
                        $("#lote6_fechacad").datepicker({ format: "yyyy-mm-dd",language: "es"});
                        $('#lote6_nolote').val(r.lotes[0]);
                        $('#lote6_fechafab').val(r.lotes[1]);
                        $('#lote6_fechacad').val(r.lotes[2]);
                    }

                    if(accion==15){
                        $('#block_paso15').css('display','block');
                        $('#guardar_block15').html('<div class="col-sm-3"><button id="save_block6"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');
                        $('#costo15_adicional').val(r.costos[0]);
                        $('#costo15_terminado').val(r.costos[1]);

                    }

                    if(accion==7){
                        cad7='';
                        $.each(r.data, function(k,v) {
                            cad7+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b7_'+v.idop+'_'+v.idProducto+'" type="text" name="" value="'+v.cbatch+'" class="form-control">\
                            </div>';

                        });

                        $('#insumos_block7').html(cad7);
                        $('#insumos_block7 input').numeric();
                        $('#guardar_block7').html('<div class="col-sm-3"><button id="save_block7"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Guardar</button></div>');

                        $('#block_paso7').css('display','block');
                    }

                    if(accion==9){
                        $('#block_paso9').css('display','block');
                        $('#guardar_block9').html('<div class="col-sm-3"><button id="save_block9"  class="btn btn-primary btn-sm btn-block" onclick="savepaso('+accion+','+idop+','+paso+')">Finalizar produccion</button></div>');
                    }

                }else{
                    alert('Falla de conexion a internet');
                    return false;
                }

            }

        });

    }

    function ciclo(idop){
        $('#block_paso1').css('display','none');
        $('#block_paso2').css('display','none');
        $('#block_paso3').css('display','none');
        $('#block_paso4').css('display','none');
        $('#block_paso5').css('display','none');
        $('#block_paso6').css('display','none');
        $('#block_paso7').css('display','none');
        $('#block_paso8').css('display','none');
        $('#block_paso9').css('display','none');
        $('#block_paso10').css('display','none');
        $('#block_paso11').css('display','none');
        $('#block_paso12').css('display','none');
        $('#block_paso13').css('display','none');
        $('#block_paso14').css('display','none');
        $('#block_paso15').css('display','none');

$('#izqpasos').html('<div class="col-sm-12 p0"  style="margin-top: 2px;">\
                            <div class="form-group" id="panelprod">\
                                <div class="col-sm-12" style="margin-bottom: 5px;">\
                                    Cargando...\
                                </div>\
                            </div>\
                            </div>');
        


        $('#div_ciclo').css('display','block');
        $('#div_ciclo').attr('oprod',idop);

        $('#btnlistorden').css('visibility','hidden');
        $('#btnback').css('visibility','visible');

        table = $('#tablaprods').DataTable();
        table.clear().draw();

        
        $('#listareq').css('display','none');
        $('#modal-conf1').modal('hide');
        $('#nreq').css('display','none');
        $('#nreq_load').css('display','block');

        $('#panel_tabla2').css('display','none');
        $('#panel_tabla').css('display','block');
        $('#addprodoexplo').css('display','block');
        $('#addprodoexplo2').css('display','none');

        $.ajax({
            url:"ajax.php?c=produccion&f=a_explosionMatCiclo",
            type: 'POST',
            dataType:'JSON',                                
            data:{idop:idop},
            success: function(r){
                console.log(r);
                if(r.success==1){
                    o=new Object();
                    d=new Object();
                    cont='';
                    subcont='';
                    x=0;
                    margtop="-2";
                    realizados=0;
                    disabled='disabled';

                    tot = Object.keys(r.data).length;

                    $.each(r.data, function( k, v ) {
                        
                   
        
                        if(v.pasorealizado==1){
                            d[k]='ok';
                            if((k+1)<tot){
                                d[k+1]='act';
                            }
                        }else{
                            if(v.tipo==2){
                                d[k]='act';
                            }
                        }

                        if(k==0 && v.pasorealizado==0){
                            d[k]='act';
                        }

                        

                        if(x>0){
                            margtop=12;
                        }
                        if(v.id_paso in o){
                            subcont='<div class="col-sm-12"  style="margin-top: 5px;">\
                                    <button style="width:100%;" id="k_'+k+'" '+disabled+' onclick="clipaso('+v.id_paso+','+v.id_accion+','+idop+');" id="ciclo_p'+v.id_paso+'_a'+v.id_accion+'" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">'+v.nombre_accion+'</button>\
                                </div>';
                            o[v.id_paso]+=subcont;
                        }else{
                            o[v.id_paso]='';
                            subcont='<div class="col-sm-12" style="margin-top: 5px;">\
                                    <button style="width:100%;" id="k_'+k+'" '+disabled+' onclick="clipaso('+v.id_paso+','+v.id_accion+','+idop+');" id="ciclo_p'+v.id_paso+'_a'+v.id_accion+'" class="btn btn-sm btn-default" style="width:316px;" type="button" style="height:28px;">'+v.nombre_accion+'</button>\
                                </div>';
                            cont+='<div class="col-sm-12 p0"  style="margin-top: '+margtop+'px;">\
                            <div class="form-group" id="panelprod">\
                                <div class="col-sm-12" style="margin-bottom: 5px;">\
                                    '+v.nombre_paso+'\
                                </div>\
                                <div id="subcont_'+v.id_paso+'">\
                                </div>\
                            </div>\
                            </div>';
                            o[v.id_paso]+=subcont;
                        }
                        x++;
                    });
                    $('#izqpasos').html(cont);
                    $.each(o, function( k, v ) {
                        $('#subcont_'+k).html(v);
                    });

                    $.each(d, function( k, v ) {
                        if(v=='ok'){
                            $('#k_'+k).css("background-color", '#e0efdc');
                        }
                        if(v=='act'){
                            $('#k_'+k).prop("disabled", false);
                        }
                    });


                    console.log(d);

                    $('#nreq_load').css('display','none');

                }else{

                    $('#nreq_load').css('display','none');
                    $('#block_paso1').css('display','none');
                    $('#block_paso2').css('display','none');
                    $('#block_paso3').css('display','none');
                    $('#block_paso4').css('display','none');
                    $('#block_paso5').css('display','none');
                    $('#block_paso6').css('display','none');
                    $('#block_paso7').css('display','none');
                    $('#block_paso8').css('display','none');
                    $('#block_paso9').css('display','none');
                    $('#block_paso10').css('display','none');
                    $('#block_paso11').css('display','none');
                    $('#block_paso12').css('display','none');
                    $('#block_paso13').css('display','none');
                    $('#block_paso14').css('display','none');
                    $('#block_paso15').css('display','none');
                    $('#izqpasos').html('<div class="col-sm-12 p0"  style="margin-top: 2px;">\
                            <div class="form-group" id="panelprod">\
                                <div class="col-sm-12" style="margin-bottom: 5px;">\
                                    No hay procesos de produccion para esta orden.\
                                </div>\
                            </div>\
                            </div>');
                    return false;

                    $.each(r.productos, function( k, v ) {
                        cad1='<div class="col-sm-4" style="margin-top: 10px;">\
                                <b>Producto</b>\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px;">\
                                <b>Cantidad necesaria</b>\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px;">\
                                <b>Existencias</b>\
                            </div>';
                        cad2='';
                        cad3='';
                        cad8='';
                        $.each(v.insumos, function( k2, v2 ) {
                            cad1+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v2.nombre+'\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px;">\
                                <input existen1="'+v2.existen+'" id="b1_'+v.id_producto+'_'+v2.idProducto+'"  readonly type="text" name="" value="'+v2.cantidad+'" class="form-control">\
                            </div>\
                            <div class="col-sm-4" style="margin-top: 10px; height:40px;">\
                                '+v2.existen+'\
                            </div>';

                            cad2+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v2.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b2_'+v.id_producto+'_'+v2.idProducto+'" type="text" name="" value="'+v2.cantidad2+'" class="form-control">\
                            </div>';

                            cad3+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v2.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b3_'+v.id_producto+'_'+v2.idProducto+'" type="text" name="" value="'+v2.peso+'" class="form-control">\
                            </div>';

                            cad8+='<div class="col-sm-4" style="margin-top: 10px;">\
                                '+v2.nombre+'\
                            </div>\
                            <div class="col-sm-8" style="margin-top: 10px;">\
                                <input id="b8_'+v.id_producto+'_'+v2.idProducto+'" type="text" name="" value="'+v2.cbatch+'" class="form-control">\
                            </div>';
                        });
                        
                    });

                    $('#insumos_block1').html(cad1);
                    $('#insumos_block1 input').numeric();
                    $('#guardar_block1').html('<div class="col-sm-3"><button id="save_block1"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(1,'+idop+')">Guardar</button></div>');
                    
                    $('#insumos_block2').html(cad2);
                    $('#insumos_block2 input').numeric();
                    $('#guardar_block2').html('<div class="col-sm-3"><button id="save_block2"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(2,'+idop+')">Guardar</button></div>');

                    $('#insumos_block3').html(cad3);
                    $('#insumos_block3 input').numeric();
                    $('#guardar_block3').html('<div class="col-sm-3"><button id="save_block3"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(3,'+idop+')">Guardar</button></div>');

                    $('#insumos_block14').html(cad14);
                    $('#insumos_block14 input').numeric();
                    $('#guardar_block14').html('<div class="col-sm-3"><button id="save_block14"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(14,'+idop+')">Guardar</button></div>');

                    $('#insumos_block8').html(cad8);
                    $('#insumos_block8 input').numeric();
                    $('#guardar_block8').html('<div class="col-sm-3"><button id="save_block8"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(8,'+idop+')">Guardar</button></div>');


                    $('#guardar_block4').html('<div class="col-sm-3"><button id="save_block4"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(4,'+idop+')">Guardar</button></div>');


                    $('#bodyempleado4').html(r.tr);

                    if(r.tr5==''){
                        $('#personal_block5').html('No hay personal dado de alta');
                    }else{
                        $('#bodyempleado5').html(r.tr5);
                        $('#guardar_block5').html('<div class="col-sm-3"><button id="save_block5"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(5,'+idop+')">Guardar</button></div>');
                    }
                    
                    $('#guardar_block6').html('<div class="col-sm-3"><button id="save_block6"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(6,'+idop+')">Guardar</button></div>');
                    $("#lote6_fechafab").datepicker({ format: "yyyy-mm-dd",language: "es"});
                    $("#lote6_fechacad").datepicker({ format: "yyyy-mm-dd",language: "es"});
                    $('#lote6_nolote').val(r.lotes[0]);
                    $('#lote6_fechafab').val(r.lotes[1]);
                    $('#lote6_fechacad').val(r.lotes[2]);


                    $('#guardar_block15').html('<div class="col-sm-3"><button id="save_block15"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(15,'+idop+')">Guardar</button></div>');

                    $('#costo15_adicional').val(r.costos[0]);
                    $('#costo15_terminado').val(r.costos[1]);





                    $('#guardar_block10').html('<div class="col-sm-3"><button id="save_block6"  class="btn btn-primary btn-sm btn-block" onclick="savepaso(10,'+idop+')">Finalizar produccion</button></div>');

$('#nreq_load').css('display','none');
                    // $('#userlog').text(r.requisicion.username);
                    //     $('#iduserlog').val(r.requisicion.idempleado);
                    //     $('#txt_nreq').text(r.requisicion.id);
                    //     $('#nreq_load').css('display','none');
                    //     $("#c_prioridad").val(r.requisicion.prioridad).trigger("change");
                    //     $("#c_sucursal").val(r.requisicion.idsuc).trigger("change");
                    //     $("#date_hoy").val(r.requisicion.fi);
                    //     $("#date_entrega").val(r.requisicion.fe);

                    //     $("#c_prioridad").prop('disabled',true);
                    //     $("#c_sucursal").prop('disabled',true);
                    //     $("#date_hoy").prop('disabled',true);
                    //     $("#date_entrega").prop('disabled',true);
                    //     $("#comment").prop('disabled',true);

                        //$('#panelprod').css('display','none');
                        //$('#panelexplosion').css('display','block');

                        
                        // var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");

                        // $("#comment").val(comment);

                        // txt_proveedor=$('#c_proveedores option:selected').text();
                        // txt_almacen=$('#c_almacen option:selected').text();

                        //table = $('#tablaprods').DataTable();
                    
                }

            }
        });

        // $.ajax({
        //     url:"ajax.php?c=produccion&f=a_ciclo",
        //     type: 'POST',
        //     dataType:'JSON',                                
        //     data:{idOP:idOP},
        //     success: function(r){

        //     }
        // });

    }
    
    function editReq(idReq,mod){ 

        $('#btn_savequit_usar').css('visibility','hidden');

        $('#div_ciclo').css('display','none');

        $('#btnlistorden').css('visibility','hidden');
        $('#btnback').css('visibility','visible');

            table = $('#tablaprods').DataTable();
            table.clear().draw();

            
            $('#listareq').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');

            $('#panel_tabla2').css('display','none');
            $('#panel_tabla').css('display','block');
            $('#addprodoexplo').css('display','block');
            $('#addprodoexplo2').css('display','none');


            
            $.ajax({
                url:"ajax.php?c=produccion&f=a_editarordenp",
                type: 'POST',
                dataType:'JSON',                                
                data:{idReq:idReq,m:1,pr:'req'},
                success: function(r){
                    console.log(r);

                    if(r.success==1){
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Modificar orden de produccion</span>');
                        if(mod==0){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar orden de produccion</span>');
                            // $('#c_cliente').prop('disabled',true);
                            // $('#date_hoy').prop('disabled',true);
                            // $('#c_productos').prop('disabled',true);
                            disabledReq();
                        }

                        $('#userlog').text(r.requisicion.username);
                        $('#iduserlog').val(r.requisicion.idempleado);


       

                        
                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#txt_nreq').text(r.requisicion.id);
                        $('#nreq_load').css('display','none');

                        

                        
                        $("#c_prioridad").val(r.requisicion.prioridad).trigger("change");
                        $("#c_sucursal").val(r.requisicion.idsuc).trigger("change");
                        $("#date_hoy").val(r.requisicion.fi);
                        $("#date_entrega").val(r.requisicion.fe);
 $("#c_solicitante").val(r.requisicion.idsol).trigger("change");
                        // if(r.requisicion.urgente==0){
                        //      $("#opciones_1").attr('checked', 'checked');
                        // }else{
                        //      $("#opciones_2").attr('checked', 'checked');
                        // }

                        // if(r.requisicion.inventariable==0){
                        //     //$("#checkbox").prop( "checked", true );
                        //     $('#checkbox').attr('checked', false);
                        // }else{
                        //     //$("#checkbox").prop( "checked", false );
                        //     $('#checkbox').attr('checked', true);
                        // }
                       

                        // $("#c_tipogasto").val(r.requisicion.id_tipogasto).trigger("change");
                        // $("#c_moneda").val(r.requisicion.id_moneda).trigger("change");
                        // $("#c_almacen").val(r.requisicion.id_almacen).trigger("change");

                        // if(r.requisicion.id_moneda>1){
                        //     $("#moneda_tc").val(r.requisicion.tipo_cambio);
                        // }

                        // $("#c_proveedores").val(r.requisicion.id_proveedor).trigger("change");
                        
                        var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");

                        $("#comment").val(comment);

                        // txt_proveedor=$('#c_proveedores option:selected').text();
                        // txt_almacen=$('#c_almacen option:selected').text();

                        //table = $('#tablaprods').DataTable();

                        console.log(r.productos);
                        btndescPE='';
                        $.each(r.productos, function( k, v ) {

                            if(mod==0){
                                eliminProd='';
                                txtdis='disabled';
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                                btndescPE='';

                            }

                            //alert(v.adds);
                            
                            Rowdata="<tr ch='0' id='tr_"+v.id+"'><!--<td>0</td>--><td>"+v.codigo+"</td><td>"+v.nomprod+"</td><td>"+v.clave+"</td><!--<td id='valUnit'>"+v.adds+"</td>--><td><input style='width:60%;' class='numeros' min='"+v.minimos+"' type='text' value='"+v.cantidad+"'/></td><!--<td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>--><td>"+eliminProd+" "+btndescPE+"</td></tr>";
                            table.row.add($(Rowdata)).draw();
                            //refreshCants(v.id,v.caracteristica,0);
                            

                        });

                        $('#btn_savequit').text('Guardar cambios');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idReq+'">');

                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');

                        $('.numeros').change(function() { 
if($(this).attr( "min" )=='null'){minimo=0;}
    else{minimo=$(this).attr( "min" );}

if($(this).val()*1<minimo*1){alert("Cantidad es menor al minimo permitido");
$(this).val(minimo);
}
});
                        



                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                        /// DESCUENTO GLOBAL
                        var total = r.requisicion.total;
                        //alert(total);
                        //recalcula();
                        /// DESCUENTO GLOBAL FIN
                }
            });


        
    }


function disabledReq(){
        $('#c_solicitante').prop('disabled',true);
        $('#c_tipogasto').prop('disabled',true);
        $('#c_moneda').prop('disabled',true);
        $('#c_proveedores').prop('disabled',true);
        $('#c_almacen').prop('disabled',true);
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        //$('#c_productos').remove();
        $('#moneda_tc').prop('disabled',true);
        $('#comment').prop('disabled', true);
        $('#btn_savequit').prop('disabled', true);
        $('#btn_authquit').prop('disabled', true);
        $('#moneda_tc').prop('disabled', true);
        $('#date_entrega').prop('disabled', true);
        $('#checkbox').prop('disabled', true);
        $('#btn_addProd').prop('disabled', true);
        $('#opciones_2').prop('disabled', true);

    }
    

    function listareq(){
        $('#div_ciclo').css('display','none');
        resetearReq();
        $('#btnlistorden').css('visibility','visible');
        $('#btnback').css('visibility','hidden');

/*
        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar orden de produccion') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#example').DataTable();
                table.destroy();                
                $('#example').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
                    "columnDefs": [
                    { "width": "8%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "11%", "targets": 4 },
                    { "width": "11%", "targets": 5 },
                    { "width": "15%", "targets": 6, "orderable": false, "sClass": "center" }
                  ],
                "aaSorting": [[0,'desc']],



                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() {  $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=produccion&f=a_listaOrdenesP",
                        type: "POST",
                        data: function ( d )    {
                    
                            //d.site = $("#nombredeusuario").val();
                        }
                    }
                });
                $('#example_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                //$('#listareq_load').css('display','none');
                $('#listareq').css('display','block');
                
            });
            $('#modal-btnconf2-dos').on('click',function(){
                $('#modal-conf2').modal('hide');
                return false;
            });
        }else{*/
            $('#modal-conf2').modal('hide');
            $('#nreq').css('display','none');
            $('#listareq_load').css('display','block');
            var table = $('#example').DataTable();
            table.destroy();
            $('#example').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
             
                "columnDefs": [
                    { "width": "8%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "11%", "targets": 4 },
                    { "width": "11%", "targets": 5 },
                    { "width": "15%", "targets": 6, "orderable": false, "sClass": "center" }
                  ],
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=produccion&f=a_listaOrdenesP",
                    type: "POST",
                    data: function ( d )    {
                       
                        //d.site = $("#nombredeusuario").val();
                    }  
                }
            });
            $('#example_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
            $('#listareq').css('display','block');
        //}
        
    }

    function restartOTRO(idProducto,cadcar,aux){ 

    //var aux = 0;       
                
        if(aux == 1){// desc
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('a').remove();
            firstoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:first").val();
            lastoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:last").val();
            //alert(lastoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption).trigger('change');

            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select option[value="'+lastoption+'"]').remove();
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', false);

            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montod', 0);

        }else{// otro precio

            $("a[ch='"+cadcar+"']").remove();
        
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val('OTRO');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('Otro precio');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis').val( $("#prelis option:first").val() ).trigger('change');
        }
        
        
    }

    function restartOTRO2(idProducto,cadcar,aux){
        if(aux == 1){// desc
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('a').remove();
            firstoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:first").val();
            lastoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:last").val();
            //alert(lastoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption).trigger('change');

            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select option[value="'+lastoption+'"]').remove();
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', false);

            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montod', 0);

            if(siDescG > 0){
                aplicaDesGlobal2(siDescG);
                //alert(siDescG);
            }

        }else{// otro precio
            $("a[ch='"+cadcar+"']").remove();
            
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val('OTRO');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('Otro precio');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis').val( $("#prelis option:first").val() ).trigger('change');
        }
    }

    function modalDescuento(idProducto,cadcar){
        /*
        $('#modalDescParcial').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

        $('#encabezadoNombre').text( $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(3)").text() );
        $('#encabezadoPrecio').text('$'+$("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(7)").text() );
        $('#encabezadoPrecioInput').val($("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(7)").text());
        $('#xProParc').val(idProducto);
        */

    }

    function refreshCants(idProducto, idProdPadre){
        p = $('#cmbProv_'+idProdPadre+'_'+idProducto).val();
        provcosto = p.split('-');
        idProv=provcosto[0];
        costo=provcosto[1];



        $('#tr_'+idProdPadre+'_'+idProducto).find('.numeros').val(costo);

        valCantidad = $('#tr_'+idProdPadre+'_'+idProducto).find('#valCantidad').text();

        ttt = valCantidad*costo;

        $('#tr_'+idProdPadre+'_'+idProducto).find('#ttt').attr('implimpio',ttt);

        $('#tr_'+idProdPadre+'_'+idProducto).find('#ttt').text(ttt).currency();

        recal();

        // $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        // $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();

    }

    function recal(){

        var subtotal = 0;
        var total = 0;
      
        $("#filasprods2 tr").each(function (index){
            eshead=$(this).attr('eshead');
            if(eshead==1){
                
            }else{
                totalfila= $(this).find('#ttt').attr('implimpio');
                totalfila=(totalfila*1);
                total+=totalfila;
            }

        }); 


        $('#tttr').attr('totlimpio', total);
         tc = $('#tttr').text(total).currency().text();
        $('#tttr').text('$'+tc+' MXN');
    }
    

    function refreshCants2(idProducto,cadcar,aux){
        //var aux = 0 //-> desc
        //aux = 0; // otro precio AGREGAR COMENT (ELIMINAR)
       
        //ax = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#prelis option:last').is(":selected");
        ax = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:last').is(":selected");


        idprodmodal=idProducto;
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val()*1;
        //valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#prelis').val();
        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:selected').val();


        tftf = valUnit.split('>');
        valUnit=tftf[0];
        if(valUnit!='OTRO' && tftf[1]=='x'){
            //totx = $('#elinew_'+idprodmodal).length;
            totx = $("#elinew_"+idprodmodal+"[ch='"+cadcar+"']").length;
            
            if(totx==0 && aux == 1){
                //$('#tr_'+idprodmodal).find('#valUnit').append('<a id="elinew_'+idprodmodal+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+','+cadcar+');"> x </a>');
                

                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'_'+cadcar+'" ch="'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\','+aux+');"> x </a>');
            }
            
        }

        if(valUnit=='OTRO'){
            $('#modal-agrega').modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
            });

            $('#modal-recep-uno').on('click',function(){
                pn = $('#precionuevo').val();
                if(pn==''){
                    pn=0;
                }
                if(pn==0){
                    alert('El precio no puede ser  igual o menor a 0');
                    return false;
                }
                $('#newprecio').val(pn);

            /*
                $('#tr_'+idprodmodal+' td:nth-child(5)').find('#prelis option:selected').val(pn+'>x');
                $('#tr_'+idprodmodal+' td:nth-child(5)').find('#prelis option:selected').text('$'+pn);
                $('#tr_'+idprodmodal).find('#valUnit').append('<a id="elinew_'+idprodmodal+'_'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
                $('#modal-agrega').modal('hide');
            */
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val(pn+'>x');
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('$'+pn);
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'" ch="'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
                $('#modal-agrega').modal('hide');

                //refreshCants(idprodmodal,0,0);
                refreshCants(idprodmodal,cadcar,aux);
                $('#modal-recep-uno').unbind();
            });


        }else{
            valUnit=valUnit*1;
        }

    /*

        if(ax===false){
            $('#elinew_'+idprodmodal).css('visibility','hidden');
        }else{
            $('#elinew_'+idprodmodal).css('visibility','visible');
        }
*/
        valImporte = valActual*valUnit;

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();
        //table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").focus();

        recalcula();

    }  

    function recalcula(){

        //iniciocaclulaivas
        var subtotal = 0;
        var total = 0;
        var productos = '';
        $("#filasprods tr").each(function (index) 
        {   //console.log($("#tablita input:hidden"));
            
            //contador++;
            idp = $(this).attr('id');
            spliidp = idp.split('_');
            idProducto = spliidp[1];
            cantidad = $(this).find('.numeros').val();
            precio = $(this).find('#prelis').val();
            //precio = $(this).find('#valUnit').text();
            if(cantidad > 0){
               
                subtotal = parseFloat(precio) * parseFloat(cantidad);
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';


            }
        // alert(productos);
            total +=parseFloat(subtotal);
            subtotal = 0;
        });
        //alert(productos);

        var btndescGR = '';
        if(ctipodesc == 1 || ctipodesc == 3){
            btndescGR = '<div><button id ="btndescG" type="" onclick="descGoblal();">Descuento</button></div>';
        }
        $.ajax({
            url: 'ajax.php?c=compras&f=calculaPrecios',
            type: 'POST',
            dataType: 'json',
            data: {productos: productos},
            async:false,
        })
        .done(function(data) {
            console.log(data);
            $('#imps').empty();
            $('.totalesDiv').empty();
            var subtotal = 0;
            subtotal = data.cargos.subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#subtotal').val(subtotal); //ch@
            $('#imps').append('<div class="row">'+btndescGR+                            
                            '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                            '<div class="col-sm-6"><label>$'+subtotal+'</label></div>'+
                            '</div>');
            
            $.each(data.cargos.impuestosPorcentajes, function(index, val) {

                var valF = val.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'); //ch@

                $('#imps').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+index+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+valF+'</label></div>'+
                            '</div>');   
            });

            cadimps='';
            $.each(data.cargos.ppii, function(index, val) {
                cadimps+=val+'|';
            });
            $('#cadimps').val(cadimps); 

            var total = 0;
            total = data.cargos.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#total').val(total); //ch@
            $('#imps').append('<div class="row">'+
                            '<div class="col-sm-6"><label>Total:</label></div>'+
                            '<div class="col-sm-6"><label id="lbtotal">$'+total+'</label></div>'+
                            '</div>');

            $('#ist').val(data.cargos.subtotal);
            $('#it').val(data.cargos.total);
            /// SI DESC GLOBAL 
            if(siDescG > 0 && auxsiDescG == 0){
                aplicaDesGlobal2(siDescG);
            }
            /// SI DESC GLOBAL FIN
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
        $('#totalOrden').val(total);
        $('#totalOrdenLable').text(total);

        

    }              

    function msg_error(error){
        $('#error_1').html('<div class="col-sm-12" style="padding-top:10px; display:block;">\
                    <div class="alert alert-danger">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <strong>Atencion!</strong> Tienes que agregar productos para poder continuar.\
                    </div>\
                    </div>');
        $('#error_1').css('display','block');
    }

    function disabled_btn(btn,text){
        txt_orig= $(btn).val();
        $(btn).prop('disabled', true);
        $(btn).text(text);
    }

    function enabled_btn(btn,text){
        $(btn).prop('disabled', false);
        $(btn).html(text);
    }
    ////////////// ch@ /////////////////////////
    function modalDescProdCot(idProducto,cadcar,nomprod){
        $("#inpass").val('');
        $('#modalDescParcial').modal('show');
        
        $('#idprodDescP').val(idProducto);
        $('#idcarDescp').val(cadcar);
        var precio = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select').val();
        var precioR = precio.slice(0,-2);
        $('#idprecio').val(precioR);
        $('#encabezadoNombre').text(nomprod);

        $('#encabezadoPrecio').text('$'+precioR); 
    }

    function aplicaDesParcial(){
        var limitDesPP = clspp;
        var limitDesCP = clspc; 

        var idProducto = $('#idprodDescP').val();
        var cadcar = $('#idcarDescp').val();
        var cantidadDesc = $("#desCantidad").val()*1;
        var precio = $('#idprecio').val()*1;
        var oldp = precio;

        var inpass = $("#inpass").val();


        if($("#tipoDescu").val() == '%'){
            if(cantidadDesc > 99){
                alert('No puede dar descuento de 100%');
                return false;
            }
            var tipoD = '1'; /// PORCENTAJE 1
            if(cantidadDesc > limitDesPP){
                if(inpass == ''){
                    alert('Ingrese la contraseña');
                    return false;
                }
                if(inpass != pass){
                    alert('La contraseña es incorrecta');
                    return false;
                }
                precio = precio - ((cantidadDesc * precio) / 100);            
            }else{
                precio = precio - ((cantidadDesc * precio) / 100);
            }
        }else{
            var tipoD = '0';  /// CANTIDAD
            if(cantidadDesc > limitDesCP){
                if(inpass == ''){
                    alert('Ingrese la contraseña');
                    return false;
                }
                if(inpass != pass){
                    alert('La contraseña es incorrecta');
                    return false;
                }
                precio = precio - cantidadDesc;
            }else{
                precio = precio - cantidadDesc;
            }
        }


        $("#filasprods").find('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp',oldp); // se guarda el precio al que se le aplico el desc
        $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montoD', cantidadDesc);
        $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', tipoD);

        pn = precio;

        //$("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);

        $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select').append('<option value="'+pn+'>x" selected="selected">$'+pn+'</option>');
        refreshCants(idProducto,cadcar,1);
        $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', 'true');
        $('#modalDescParcial').modal('hide');
        //return false;

        // SI TIENE UN DESC GLOBAL
        if(siDescG > 0){
            aplicaDesGlobal2(siDescG);
            //alert(siDescG);
        }
  
    }
    function aplicaDesGlobal(){
        var total = $('#total').val()*1;
        var desCantidadG = $("#desCantidadG").val()*1;
        var inpassG = $("#inpassG").val();
        if(desCantidadG > clgp){
            if(inpassG == ''){
              alert('Ingrese la Contraseña');  
              return false;
            }
            if(inpassG != pass){
               alert('La contraseña es incorrecta'); 
               return false;
            }
            var descG =  (desCantidadG * total) / 100;
            total = total - descG;
            if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }

            if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }

            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(totalF);
        }else{
            var descG =  (desCantidadG * total) / 100;
            total = total - descG;
            if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }

            if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }


            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(totalF);
        }
        
        
    }

    function aplicaDesGlobal2(desCantidadG){
        var total = $('#total').val()*1;
        //var desCantidadG = $("#desCantidadG").val()*1;
        var descG =  (desCantidadG * total) / 100;
        total = total - descG;

        if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }
        if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }

        $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
        $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
        $("#lbtotal").text('$'+totalF);
        $("#btndescG").prop( "disabled", true );
        $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
        $('#modalDescGlobal').modal('hide');
        $('#it').val(total);
        
    }

    function removeDescG(){
        auxsiDescG = 1;
        var total = $("#total").val();
        $("#divdesc").remove();
        recalcula();
        $("#auxDescG").val(0);
        $("#desCantidadG").val(0);
    }


    
    /// delete
    function descProdReq(idProducto,cadcar,nomprod){ //ch@
        $('#modalDescParcial').modal('show');
        $('#encabezadoNombre').text(nomprod);
        $('#tr_'+idProducto+"[ch='"+cadcar+"']").find('select').val(111).text('111');  
        //alert(nomprod+' '+cadcar);
    }
    /*
        function aplicaDesParcial(){
            var id = $('#xProParc').val();
            var cantidad = $('#desCantidad').val();
            var tipoDes = $('#tipoDescu').val();
            var pre = $('#encabezadoPrecioInput').val();

            if(parseFloat(cantidad) < 0){
                alert('La cantidad debe ser mayor a cero');
                return false;
            }

            if(tipoDes=='%'){
                if(parseFloat(cantidad) > 100 ){
                    alert('El descuento no puede ser mayor al 100%');
                    return false;
                }
            }
            if(tipoDes=='$'){
                if(parseFloat(cantidad) > parseFloat(pre)){
                    alert('El descuento no puede ser mayor al precio del producto');
                    return false;
                }
            } 


            //caja.mensaje('Procesando...');

            $.ajax({
                url: 'ajax.php?c=caja&f=cambiaCantidad',
                type: 'POST',
                dataType: 'json',
                data: {id: id,
                       cantidad : cantidad,
                       tipo : tipoDes,
                    },
            })
            .done(function(data) {
                
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        }
    */



    function descGoblal(){ //ch@
        $("#inpassG").val('');
        var num_cot = $("#txt_nreq").text();
        var total = $("#total").val()*1;
        var subtotal = $("#subtotal").val()*1;
        $('#modalDescGlobal').modal('show');    
        $("#h3DescG1").text('Cotizacion No.'+ num_cot);
        $("#h3DescG2").text('Total: $'+total);
        //alert(total+' '+subtotal);
    }

    ////////////// ch@  fin/////////////////////////
    
    function removeProdReq(idProducto,cadcar){
        table = $('#tablaprods').DataTable();
        rowquit= $('#tr_'+idProducto+"[ch='"+cadcar+"']");
        table.row(rowquit).remove().draw();


        if ( table.data().length !== 0 ) {
            
        }else{
            $('#c_proveedores').prop('disabled',false);
            $('#c_almacen').prop('disabled',false);
            $('#c_cliente').prop('disabled',false);
            $('#c_moneda').prop('disabled',false);
        }

        recalcula();

    }


    function autorizar(id){
       

        
        $.ajax({
            url:"ajax.php?c=produccion&f=a_autorizar",
            type: 'POST',
            data:{id:id},
            success: function(r){
               window.location.reload();
                console.log(r);
            }
        });

    }


    function savequit(){
        disabled_btn('#btn_addProd','Procesando...');

        /*
        $.ajax({
            url:"ajax.php?c=compras&f=a_nuevarequisicion",
            type: 'POST',
            data:{ano:1},
            success: function(r){
                console.log(r);
            }
        });
*/
    }

    function eliemp4(idEmpleado){
        $('#tr_empp_'+idEmpleado).remove();

    }

    function emp4(){
        idEmpleado= $('#select_empleados4').val();
        nombreEmpleado = $("#select_empleados4 option:selected").text();
        areaEmpleado = $("#select_empleados4 option:selected").attr('area');

        
        repetido=0;
        $("#bodyempleado4 tr").each(function( index ) {
            aaa = $(this).attr('id');
            if(aaa=='tr_empp_'+idEmpleado){
                repetido++;
            }
        });

        if(repetido>0){
            alert('Personal repetido');
            return false;
        }


        if(idEmpleado==0){
            alert('Seleccione un empleado');
            return false;
        }




        agrega='<tr id="tr_empp_'+idEmpleado+'">\
        <td>'+nombreEmpleado+'</td>\
        <td><button id="eliemp4" style=" padding: 0px;  height:33px;" onclick="eliemp4('+idEmpleado+');" class="btn btn-danger btn-sm btn-block">Elimina</button></td>\
        </tr>';



        $('#bodyempleado4').append(agrega);

    }

    function paso(paso){

        if(paso==2){
            $('#block_paso2').css('display','block');
        }

    }

    function compras_nuevaRequisicion(){
        /* CHRIS - COMENTARIOS
        =============================*/
        
        //Esta funcion muestra la plantilla de captura de requisiciones
        //La peticion ajax crea un id_tmp de rquisicion
        //La peticion ajax busca el ultimo id de requisicion

        $.ajax({
            url:"ajax.php?c=compras&f=a_nuevarequisicion",
            type: 'POST',
            data:{ano:1},
            success: function(r){
                console.log(r);
            }
        });
    }
</script>