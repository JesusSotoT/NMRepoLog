<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mermas</title>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/inventario.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
                $('#producto').select2();
                $('#usuario').select2();
                $('#almacen').select2();
        }); 
    </script>
</head>

<body>
    <div class="container well">
        <div class="row">
            <h3>Mermas</h3>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia2();">Listado Mermas</button>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia1();">Nueva Merma</button>
            </div>
        </div>
            <div class="row" id="contenido2">
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dar de Alta una merma </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Selecciona el producto que deseas dar de baja</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Producto</label>
                                <input type="text"  id="hiddenCarac_input">
                                <select id="producto" class="form-control" onchange="buscaCaracteristicas();">
                                    <option value="0">-Selecciona un Producto-</option>
                                <?php 
                                    foreach ($productos['productos'] as $key => $value) {
                                        echo '<option value="'.$value['id'].'">'.$value['nombre'].' / '.$value['codigo'].'</option>';
                                    }

                                ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Cantidad</label>
                                <input type="text" class="form-control" id="cantidad">
                            </div>
                            <div class="col-sm-3">
                                <label>Usuario</label>
                                <select id="usuario" class="form-control">
                                    <option value="0">-Selecciona un Usuario-</option>
                                <?php 
                                    foreach ($productos['usuarios'] as $key => $value) {
                                        echo '<option value="'.$value['idempleado'].'">'.$value['usuario'].'</option>';
                                    }

                                ?>
                                </select>                            
                            </div>
                            <div class="col-sm-3">
                                <label>Almacen</label>
                                <select id="almacen" class="form-control">
                                    <option value="0">-Selecciona Almacen-</option>
                                <?php 
                                    foreach ($productos['almacenes'] as $key => $value) {
                                        echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                                    }

                                ?>                                    
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Obvservaciones</label>
                                <textarea  id="obse" cols="25" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="col-sm-3">
                                <label>Costo</label>
                                <input type="text" class="form-control" id="costo">
                            </div>
                            <div class="col-sm-3">
                                <br>
                               <!-- <button class="btn btn-default btn-block" onclick="agregaMerma();" style="">Agregar</button> -->
                                <div id="guardaDiv2">
                                    <button type="button" class="btn btn-info btn-block" onclick="agregaMerma();"><span class="glyphicon glyphicon-plus"></span> Agregar</button>
                                </div>
                                <div id="sded2" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x"></i></div>
                            </div>
                        </div>
                        <br>
                        <div id="tableDiv" style="display:none;">
                            <div class="row">
                                <div class="col-sm-12">
                                     <hr></hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-hover" id="tableMermas">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Usuario</th>
                                                <th>Almacen</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10"></div>
                                <div class="col-sm-2">
                                    <!--<button class="btn btn-default" onclick="saveMerma();">Guardar Merma</button> -->

                                    <div id="guardaDiv"><button type="button" class="btn btn-success btn-block" onclick="saveMerma();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Merma</button></div>
                                    <div id="sded" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x"></i></div>

                                </div>
                            </div>
                        </div>    
                       <!-- <div class="row">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2">
                                <button class="btn btn-default btn-block" onclick="agregaMerma();">Agregar</button>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div> 
                <!--- Modal Caracteristicass -->
    <div id="modalCarac" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <h4 id="modal-labelCr"></h4>
                    <input type="hidden" id="carIdProddiv">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <img id="divImagenPro" height="250" width="250">
                        </div>
                        <div class="col-sm-6" id="prodCarcDiv"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="agregaCarac();">Agregar</button> 
                    <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div> 
    </div>
    </div>
</body>
</html>