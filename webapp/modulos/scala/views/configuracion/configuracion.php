<style>
    .table100{
    width : 100% !important
}
</style>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Configuracion</title>
        <link rel="stylesheet" href="">
    </head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/configuracion.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!--<script src="../../libraries/dataTable/js/datatables.min.js"></script>-->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            reload_app();
            $("#menuAc").select2();
        });
    </script>
    <body>  
        <div id="divconfig" class="container well">
            <div class="row">
                <div class="col-sm-4">
                     <button class="btn btn-primary" onclick="newapp();"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar guia de implementacion</label>
                </div>      
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12" style="overflow:auto;">
                    <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                        <thead>
                            <tr>
                                <th>Aplicacion</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>        
            </div>
        </div>

        <div id="divpasos" class="container well" style="display:none">
            <div class="container-fluid well">
                <div class="row">
                    <div class="col-sm-1">
                        <button class="btn btn-default" onclick="back();"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" onclick="save_pasos();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>                
                    </div>
                    <div class="col-sm-2">
                        <input id="in_idapp" type="hidden"><label id="lbapp"></label>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <div style="heigth:400px;overflow:auto;">
                            <div id="tabsCliente">  
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#paso1">Paso 1</a></li>
                                    <li><a data-toggle="tab" href="#paso2">Paso 2</a></li>
                                    <li><a data-toggle="tab" href="#paso3">Paso 3</a></li>
                                    <li><a data-toggle="tab" href="#paso4">Paso 4</a></li>
                                    <li><a data-toggle="tab" href="#paso5">Paso 5</a></li>              
                                </ul>
                            </div>
                            <div class="tab-content" style="min-height:550px; border:1px solid black;">
                                
                                <div id="paso1" class="tab-pane fade in active">
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label class="control-label">Nombre paso</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p1nom" class="form-control" type="text" value=" Configuracion">
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="control-label">Link</label>                
                                        </div>
                                        <div class="col-sm-7">                
                                            <input id="p1link" class="form-control" type="text" value="">
                                            <input id="idpaso1" type="hidden">
                                            <input id="idpaso1R" type="hidden">
                                        </div>
                                    </div><br><br>          
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Descripcción</label>
                                        <div class="col-md-7">
                                        <textarea class="form-control" id="p1desc" cols="50" rows="3">rfrfr</textarea>
                                        </div>
                                    </div><br><br><br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" onclick="newsp(1);"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar sub paso</label>
                                        </div>
                                    </div> <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table100"  id="p1table">
                                                <thead>                                  
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Menú</th>
                                                        <th>Descripción</th>
                                                        <th>Link</th>
                                                        <th>Tipo</th>
                                                        <th>Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>          
                                    <!--fin-->
                                </div><!-- Fin del Tab Paso1 -->

                                <div id="paso2" class="tab-pane fade">
                                    <!--ini-->
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label class="control-label">Nombre paso</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p2nom" class="form-control" type="text" value="">
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="control-label">Link</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p2link" class="form-control" type="text" value="">
                                            <input id="idpaso2" type="hidden">
                                            <input id="idpaso2R" type="hidden">
                                        </div>
                                    </div><br><br>          
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Descripcción</label>
                                        <div class="col-md-7">
                                        <textarea class="form-control" id="p2desc" cols="50" rows="3"></textarea>
                                        </div>
                                    </div><br><br><br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" onclick="newsp(2);"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar sub paso</label>
                                        </div>
                                    </div> <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table100" id="p2table">
                                                <thead>                                  
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Menú</th>
                                                        <th>Descripción</th>
                                                        <th>Link</th>
                                                        <th>Tipo</th>
                                                        <th>Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>          
                                    <!--fin-->
                                </div><!-- Fin del Tab Paso2 -->

                                <div id="paso3" class="tab-pane fade">
                                    <!--ini-->
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label class="control-label">Nombre paso</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p3nom" class="form-control" type="text" value="">
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="control-label">Link</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p3link" class="form-control" type="text" value="">
                                            <input id="idpaso3" type="hidden">
                                            <input id="idpaso3R" type="hidden">
                                        </div>
                                    </div><br><br>          
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Descripcción</label>
                                        <div class="col-md-7">
                                        <textarea class="form-control" id="p3desc" cols="50" rows="3"></textarea>
                                        </div>
                                    </div><br><br><br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" onclick="newsp(3);"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar sub paso</label>
                                        </div>
                                    </div> <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table100" id="p3table">
                                                <thead>                                  
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Menú</th>
                                                        <th>Descripción</th>
                                                        <th>Link</th>
                                                        <th>Tipo</th>
                                                        <th>Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>          
                                    <!--fin-->
                                </div><!-- Fin de tab Paso3 -->

                                <div id="paso4" class="tab-pane fade">
                                    <!--ini-->
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label class="control-label">Nombre paso</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p4nom" class="form-control" type="text" value="">
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="control-label">Link</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p4link" class="form-control" type="text" value="">
                                            <input id="idpaso4" type="hidden">
                                            <input id="idpaso4R" type="hidden">
                                        </div>
                                    </div><br><br>          
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Descripcción</label>
                                        <div class="col-md-7">
                                        <textarea class="form-control" id="p4desc" cols="50" rows="3"></textarea>
                                        </div>
                                    </div><br><br><br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" onclick="newsp(4);"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar sub paso</label>
                                        </div>
                                    </div> <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table100" id="p4table">
                                                <thead>                                  
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Menú</th>
                                                        <th>Descripción</th>
                                                        <th>Link</th>
                                                        <th>Tipo</th>
                                                        <th>Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>          
                                    <!--fin-->
                                </div><!-- Fin del tab Paso4 -->

                                <div id="paso5" class="tab-pane fade">
                                    <!--ini-->
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <label class="control-label">Nombre paso</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p5nom" class="form-control" type="text" value="">
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="control-label">Link</label>                
                                        </div>
                                        <div class="col-sm-3">                
                                            <input id="p5link" class="form-control" type="text" value="">
                                            <input id="idpaso5" type="hidden">
                                            <input id="idpaso5R" type="hidden">
                                        </div>
                                    </div><br><br>          
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Descripcción</label>
                                        <div class="col-md-7">
                                        <textarea class="form-control" id="p5desc" cols="50" rows="3"></textarea>
                                        </div>
                                    </div><br><br><br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" onclick="newsp(5);"><i class="fa fa-plus" aria-hidden="true"></i></button><label> Agregar sub paso</label>
                                        </div>
                                    </div> <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table100" id="p5table">
                                                <thead>                                  
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Menú</th>
                                                        <th>Descripción</th>
                                                        <th>Link</th>
                                                        <th>Tipo</th>
                                                        <th>Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>          
                                    <!--fin-->
                                </div><!-- Fin del tab Paso5 -->

                            </div>  <!-- Fin del div de los tabs -->
                        </div><!-- fin de contenedor overflow -->
                    </div> <!-- Fin del Panel Body -->
                </div>
            </div> 
        </div>

    </body>
<!-- ADD-GUIA APP -->
    <div class="modal fade" id="modal_add_guia" data-keyboard="false" style="display:none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Generales</h3>
                </div>

                <div class="modal-body form">
                    <form action="#" id="form_app" class="form-horizontal">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Nombre</label>
                                <div class="col-md-9">
                                    <input id="app_nombre" class="form-control" type="text">
                                </div>
                            </div>
                
                            <div class="form-group">
                                <label class="control-label col-md-3">Solución</label>
                                <div class="col-md-9">
                                    <select id="app_solucion" class="form-control">                                        
                                        <option value="1001">Appministra - POS Emprendedor</option>
                                        <option value="1002">Foodware - Emprendedor</option>
                                        <option value="1004">Acontia - Emprendedor</option>
                                        <option value="1011">Xtructur - Negocio</option>
                                        <option value="1014">Appministra - POS Negocio</option>
                                        <option value="1015">Appministra - POS Empresarial</option>
                                        <option value="1016">Appministra - Comercial</option>                                                                                                                                                                            
                                        <option value="1019">Foodware - Negocio</option>
                                        <option value="1020">Foodware - Empresarial</option> 
                                        <option value="1021">Acontia - Negocio</option>
                                        <option value="1022">Acontia - Empresarial</option>                                       
                                        <option value="1023">Xtructur - Negocio Plus</option>
                                        <option value="2024">Xtructur - Empresarial</option>
                                        <option value="2025">Xtructur - Corporativo</option>
                                    </select>                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Descripcción</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="app_desc" cols="60" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="save_app()" class="btn btn-primary">Guardar</button>
                </div>            
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!-- ADD-GUIA APP FIN-->

<!-- ADD-SUB PASO ACTIVIDAD-->
    <div class="modal fade" id="modal_add_actividad" data-keyboard="false" style="display:none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Sub paso</h3>
                </div>

                <div class="modal-body form">
                    <form action="#" id="form_guia" class="form-horizontal">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-2">Nombre</label>
                                <div class="col-md-6">
                                    <input id="nombreAc" class="form-control subpaso" type="text">
                                    <input id="idpasoA" type="hidden">
                                    <input id="idpasoR" type="hidden">
                                    <input id="id_act" type="hidden">
                                </div>
                                <div class="col-md-3">
                                    <input id="opcionalAC" type="checkbox"> Opcional
                                </div>
                            </div>
                
                            <div class="form-group">
                                <label class="control-label col-md-2">Menú</label>
                                <div class="col-md-9">
                                    <select id="menuAc" class="form-control" style="width: 100%;"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Descripcción</label>
                                <div class="col-md-9">
                                    <textarea class="form-control subpaso" id="descAc" cols="60" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2 subpaso">Link</label>
                                <div class="col-md-9">
                                    <input id="linkAc" class="form-control subpaso" type="text">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id ="btnsaveact" onclick="save_actividad()" class="btn btn-primary">Guardar</button>                    
                    <button type="button" id ="btneditact" onclick="edit_actividad()" class="btn btn-primary">Editar</button>
                </div>            
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!-- ADD-SUB PASO ACTIVIDAD FIN-->
</html>



