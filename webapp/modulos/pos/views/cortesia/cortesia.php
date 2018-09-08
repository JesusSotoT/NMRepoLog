<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Ordenes de Compra</title>
        <link rel="stylesheet" href="">

        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
        <script src="../../libraries/jquery.min.js"></script>
        <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
        <!--Select 2 -->
        <script src="../../libraries/select2/dist/js/select2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

        <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
        <!-- Modificaciones RC -->
        <script src="../../libraries/dataTable/js/datatables.min.js"></script>
        <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
        <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
        <script src="../../libraries/export_print/buttons.html5.min.js"></script>
        <script src="../../libraries/export_print/jszip.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

         <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>
        
        <script src="js/cortesia.js"></script>
    </head>
<body>  
    <br>
<div class="container well col-sm-12">
   
    <h3>Cortesías</h3>
    <div class="row">
        <div class='col-sm-12 col-md-2'>
            <button id="btn-nuevaC" class="btn btn-primary" data-toggle="modal" data-tajet="modalEditar"><i class="fa fa-plus" aria-hidden="true" ></i> Nueva cortesía </button>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
                     <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estatus</th>
                        <th>Modificar</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-principal">
                        
                    </tbody>
                </table>
        </div>
    </div>
</div>

        <div id="modalDesactiva" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content panel-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-header panel-heading">
                        <h4 id="modal-label"> Desactivar! </h4>
                    </div>
                    <div class="modal-body">
                        <p> ¿Deseas desactivar esta cortesía? </p>
                        <input type="hidden" id="eliminaProd">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" > Desactivar </button> 
                    </div>
                </div>
            </div> 
        </div>


        <div id="modalActiva" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content panel-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-header panel-heading">
                        <h4 id="modal-label"> ¡Activar! </h4>
                    </div>
                    <div class="modal-body">
                        <p> ¿Deseas activar esta cortesía? </p>
                        <input type="hidden" id="activaProd">
                    </div>
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-danger"> Activar </button> 
                    </div>
                </div>
            </div> 
        </div>

        <div id="modalEditar" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content panel-default">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-header panel-heading">
                        <h4 id="modal-label"> ¡Editar cortesía! </h4>
                    </div>
                    <div class="modal-body">

                            <div class="row">

                                <div class="col-sm-1">
                                    <div id="btnSave">
                                        <button type="submit" class="btn btn-primary" id="save"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                                    </div>
                                </div>
                            </div>

                                    <!-- Div contendro de los Contenidos -->
                                    <div class="tab-content" style="height:400px;">
                                        <div id="basicos" class="tab-pane fade in active">
                                            <div class="form-horizontal col-sm-12">
                                                <div class="form-group">


                                                    <div class="row" style="display: none;"> 
                                                        <div class="col-sm-3">
                                                            <label for="idd"> ID </label>
                                                            <input type="text" id="idd" class="form-control" disabled>
                                                            </input>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <label for="nombre"> Nombre </label>
                                                            <input type="text" id="nombre" class="form-control" >
                                                            </input>
                                                        </div>


                                                        <div class="col-sm-3">
                                                            <label>Desde</label>
                                                            <div id="datetimepicker1" class="input-group date">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                                <input id="desde" class="form-control" type="text" placeholder="Fecha de inicio">
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Hasta</label>
                                                            <div id="datetimepicker2" class="input-group date">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>   
                                                                <input id="hasta" class="form-control" type="text" placeholder="Fecha final"> 
                                                            </div>


                                                            <div class="row"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label for="producto"> Producto </label>
                                                            <select name="producto" id="producto" class="form-control" >
                                                            </select>   
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="producto">  </label>
                                                            <button id="btn-agregar" class="btn btn-primary form-control" data-tajet="modalEditar" onclick="agregaProducto();">Agregar</button>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="height: 50px;">
                                                    </div>

                                                    <table id="tabla-productos" class="table">
                                                        <thead>
                                                        <tr>
                                                            <th> ID </th>
                                                            <th> Producto </th>
                                                            <th ><i class="fa fa-times" aria-hidden="true"></i> Eliminar </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="height: 10px !important; overflow: scroll;" ></tbody>

                                                    </table>  
                                                        
                                                    

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                    </div>
                    
                    <div class="modal-footer">
                        
                    </div>

                </div>
            </div> 
        </div>

</body>
</html>