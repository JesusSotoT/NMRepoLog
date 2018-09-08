<style>
    .table100{
    width : 100% !important    
}
.req{
    color:#FF0000; 
    font-weight:bold;
}
</style>
<?php 
//echo json_encode($regimen);
 ?>
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
   <!-- <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>-->
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <script src="views/facturacion/simpleUpload.js"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>

    <body>  
        <br>
        <div class="container well">
            <div class="row" style="padding-bottom: 17px;">
                <div class="col-xs-12 col-md-12">
                    <h3>Configuracion General</h3>
                </div>
            </div> 
            <div class="row">                    
                <ul class="nav nav-tabs">
                    <!--<li class="active"><a data-toggle="tab" href="#paso1">Bienvenido</a></li>-->
                    <li class="active" ><a data-toggle="tab" href="#paso2" >Mi Organización</a></li>
                    <li><a data-toggle="tab" href="#paso3" onclick="relaodPU();">Perfiles de Usuario</a></li>
                    <li><a data-toggle="tab" href="#paso4" onclick="relaodAU();">Administración de Usuarios</a></li>
                </ul>                            
                <div class="tab-content" style="min-height:650px;">

                    <div id="paso1" class="tab-pane fade">
                        <div id="divBien"></div>
                    </div><!-- Fin del Tab Paso1 -->

                    <div id="paso2" class="tab-pane fade in active">                                    
                        <div class="panel panel-default">
                            <div class="panel-heading">                                            
                                    <h3 class="panel-title">Capture los datos de su organización</h3>
                            </div>

                            <div class="panel-body">                         
                            
                                <div class="form-group">

                                    <div class="col-sm-4">
                                        <label class="control-label">ID</label>  
                                        <input id="idOr" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['idorganizacion']; ?>" readonly>              
                                    </div>    
                                    <div class="col-sm-4">
                                        <label class="control-label">RFC<font class="req">*</font></label> 
                                        <input id="rfc" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['RFC']; ?>">                
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Regimen:</label>                                                     
                                        <select id="regimen" class="form-control">
                                        <option value="0">SELECCIONA EL REGIMEN FISCAL</option>
                                            <?php 
                                                foreach ($regimen as $k => $v) {
                                                    if($miOrganizacion[0]['idregfiscal'] == $v['idregfiscal']){
                                                        echo '<option value="'.$v['idregfiscal'].'" selected>'.$v['descripcion'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['idregfiscal'].'">'.$v['descripcion'].'</option>'; 
                                                    }
                                                    
                                                }
                                             ?>                                                
                                        </select> 
                                    </div> 

                                    <div class="col-sm-6">
                                        <label class="control-label">Razon Social:<font class="req">*</font></label>                                                                                     
                                        <input id="razon" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['nombreorganizacion']; ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">Domicilio:</label>                                         
                                        <input id="domicilio" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['domicilio']; ?>">
                                    </div>
                                                                                                               
                                    <div class="col-sm-2">
                                        <label class="control-label">Pais:</label> <br>
                                        <select id="pais" class="form-control">
                                        <option value="0">Selecciona un Pais</option>
                                            <?php 
                                                foreach ($pais as $k => $v) {
                                                    if(1 == $v['idpais']){
                                                        echo '<option value="'.$v['idpais'].'" selected>'.$v['pais'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['idpais'].'">'.$v['pais'].'</option>'; 
                                                    }
                                                    
                                                }
                                             ?>                                                
                                        </select>                
                                    </div>
                                    <div class="col-sm-1" style="padding-top: 28px; padding-left: 0px;">                                            
                                        <button type="button" data-toggle="modal" onclick="modalpais();" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus cursor" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <div class="col-sm-2">
                                        <label class="control-label">Estado:</label> 
                                        <select id="estado" class="form-control">
                                        <option value="0">Selecciona un Estado</option>
                                            <?php 
                                                foreach ($estados as $k => $v) {
                                                    if($miOrganizacion[0]['idestado'] == $v['idestado']){
                                                        echo '<option value="'.$v['idestado'].'" selected>'.$v['estado'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['idestado'].'">'.$v['estado'].'</option>'; 
                                                    }
                                                    
                                                }
                                             ?>                                                
                                        </select>                 
                                    </div>
                                    <div class="col-sm-1" style="padding-top: 28px; padding-left: 0px;">
                                        <button type="button" data-toggle="modal" data-target="#nuevoEstado" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus cursor" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <div class="col-sm-2">
                                        <label class="control-label">Municipio:</label> 
                                        <select id="municipios" class="form-control">
                                        <option value="0">Selecciona un Municipio</option>
                                            <?php 
                                                foreach ($municipios as $k => $v) {
                                                    if($miOrganizacion[0]['idmunicipio'] == $v['idmunicipio']){
                                                        echo '<option value="'.$v['idmunicipio'].'" selected>'.$v['municipio'].'</option>';
                                                    }
                                                    
                                                    else{
                                                        echo '<option value="'.$v['id'].'">'.$v['municipio'].'</option>'; 
                                                    }
                                                    
                                                    
                                                }
                                             ?>                                                
                                        </select>                
                                    </div>
                                    <div class="col-sm-1" style="padding-top: 28px; padding-left: 0px;">                                            
                                        <button type="button" data-toggle="modal" data-target="#nuevoMunicipio" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus cursor" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <div class="col-sm-3">
                                        <label class="control-label">Colonia:</label> 
                                        <textarea id="colonia" class="form-control" cols="30" rows="1"><?php echo $miOrganizacion[0]['colonia']; ?></textarea>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label">Codigo Postal:</label> 
                                        <input id="cp" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['cp']; ?>"><br> <br>
                                    </div> 
                                    <div class="col-sm-4">
                                        <label class="control-label">Pagina Web:</label> 
                                        <input id="web" class="form-control" type="text" value="<?php echo $miOrganizacion[0]['paginaweb']; ?>">            
                                    </div>
                                                                            
                                    <div class="col-sm-4">                        
                                        <label class="control-label">Logo Empresa (180x180 pixeles.):<?php echo $miOrganizacion[0]['logoempresa']; ?> </label> <br>                                                                                                      
                                        <a class="btn btn-default btn-xs" target="blank" href="../../netwarelog/descarga_archivo_fisico.php?d=1&f=<?php echo $miOrganizacion[0]['logoempresa'];?> &ne=pvt_configura_facturacion" title="Descargar archivo">
                                            <i class="fa fa-arrow-circle-down"></i>
                                        </a>                                            
                                        <a class="btn btn-default btn-xs" target="blank" onclick="verlogoempresa('<?php echo $miOrganizacion[0]['logoempresa'];?>');" title="Ver archivo">
                                            <i class="fa fa-file-o"></i>
                                        </a>                                        
                                        <input type="file" id="miarchi" size="100" name="Filedata" style="display: block;">
                                        <input type="hidden" id="logoempresa" value="<?php echo $miOrganizacion[0]['logoempresa']; ?>">
                                    </div>                                                                                
                                </div> 
                            
                                
                            </div>
                            <div style="text-align: center;"><button class="btn btn-default" onclick="saveMiOrg();">Guardar <i class="fa fa-check" aria-hidden="true"></i></button></div>                                   
                        </div>
                    </div><!-- Fin del Tab Paso2 -->

                    <div id="paso3" class="tab-pane fade">  
                        <div class="panel panel-default">                                        
                            <div class="panel-heading">
                                <h3 class="panel-title">Crea los perfiles necesarios para tu negocio</h3>
                            </div> 
                            <div class="panel-body" id="divtablePU" style="padding-left: 300px; padding-right: 300px;" ></div>
                            <div class="panel-body" id="divtablePU2"></div>
                        </div>                                                                                                        
                        
                    </div><!-- Fin del Tab Paso3 -->

                    <div id="paso4" class="tab-pane fade">
                        <div class="panel panel-default">                                    
                            <div class="panel-heading">
                                <h3 class="panel-title">Capture la imformación de los usuarios</h3>
                            </div>                                     
                            <div class="panel-body" id="divtableAU" style="padding-left: 50px; padding-right: 50px;" ></div>
                            <div class="panel-body" id="newUser2" > </div>
                        </div>
                        
                    </div><!-- Fin del Tab Paso4 -->

                </div>  <!-- Fin del div de los tabs -->
            </div><!-- fin de contenedor overflow -->
        </div>
 
        <div id="modalApps" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Administrador de Aplicaciones Favoritas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="nuevaAventura" class="modal-body">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="saveAF();">Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="modalexito" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="claE" class="alert alert-success">
                        <h5 id="headE" class="modal-title">¡Exito!</h5>                        
                    </div>
                    <div class="modal-body">                
                        <p id="msgE">Tus datos se guardaron correctamente</p>
                    </div>
                    <div class="modal-footer">                        
                        <button id="btnE" type="button" class="btn btn-default" data-dismiss="modal" onclick="reloadOrg()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalexito2" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="claE" class="alert alert-warning">
                        <h5 id="headE" class="modal-title">¡Exito!</h5>                        
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">                
                        <p id="msgE">Su perfil se ha guardado correctamente</p>
                    </div>
                    <div class="modal-footer">                        
                        <button id="btnE" type="button" class="btn btn-default" data-dismiss="modal" onclick="reloadPU2()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalexito3" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="claE" class="alert alert-warning">
                        <h5 id="headE" class="modal-title">¡Exito!</h5>                        
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">                
                        <p id="msgE">Tu usuario se ha guardado correctamente</p>
                    </div>
                    <div class="modal-footer">                        
                        <button id="btnE" type="button" class="btn btn-default" data-dismiss="modal" onclick="reloadPU3()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalBP" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="alert alert-warning">
                        <h5 class="modal-title">Se perderán los cambios</h5>                        
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">                
                        <p>¿Seguro que deseas regresar?</p>
                    </div>
                    <div class="modal-footer">                        
                        <button id="btnE" type="button" class="btn btn-danger" onclick="cancelPU();">Regresar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalBAU" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="alert alert-warning">
                        <h5 class="modal-title">Se perderán los cambios</h5>                        
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">                
                        <p>¿Seguro que deseas regresar?</p>
                    </div>
                    <div class="modal-footer">                        
                        <button id="btnE" type="button" class="btn btn-danger" onclick="cancelAU();">Regresar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalUsers" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="alert alert-warning">
                        <h5 class="modal-title">Usuarios registrados</h5>                        
                    </div>
                    <div id="userbody" class="modal-body" style="padding-top: 0px;">                
                        
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal"">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- M O D A L   P A R A   A G R E G A R   U N   N U E V O   P A Í S -->
                                    <div class="modal fade" id="nuevoPais" role="dialog" aria-labelledby="nuevoPais" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" >Agregar nuevo País</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" id="inputNuevoPais" class="form-control" placeholder="Nombre de país">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoPais">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<!-- F I N   D E L   M O D A L -->

<!-- M O D A L   P A R A   A G R E G A R   U N   N U E V O   E S T A D O -->
                                    <div class="modal fade" id="nuevoEstado" role="dialog" aria-labelledby="nuevoEstado" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" >Agregar nuevo Estado</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <select id="selectPais2" class="form-control">
                                                        <option value="0">Selecciona un Pais</option>
                                                        <?php 
                                                            foreach ($pais as $k => $v) {
                                                                if(1 == $v['idpais']){
                                                                    echo '<option value="'.$v['idpais'].'" selected>'.$v['pais'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$v['idpais'].'">'.$v['pais'].'</option>'; 
                                                                }
                                                                
                                                            }
                                                         ?>  
                                                    </select>
                                                    <input type="text" id="inputNuevoEstado" class="form-control" placeholder="Nombre de estado">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoEstado">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<!-- F I N   D E L   M O D A L -->

<!-- M O D A L   P A R A   A G R E G A R   U N   N U E V O   M U N I C I P I O -->
                                    <div class="modal fade" id="nuevoMunicipio" role="dialog" aria-labelledby="nuevoMunicipio" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" >Agregar nuevo Municipio</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <select id="selectPais3" class="form-control" >
                                                        <option value="0">Selecciona un Pais</option>
                                                        <?php 
                                                            foreach ($pais as $k => $v) {
                                                                if(1 == $v['idpais']){
                                                                    echo '<option value="'.$v['idpais'].'" selected>'.$v['pais'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$v['idpais'].'">'.$v['pais'].'</option>'; 
                                                                }
                                                                
                                                            }
                                                         ?> 
                                                    </select>
                                                    <select id="selectEstado3" class="form-control" >
                                                        <option value="0">Selecciona un Estado</option>
                                                        <?php 
                                                            foreach ($estados as $k => $v) {
                                                                if($miOrganizacion[0]['idestado'] == $v['idestado']){
                                                                    echo '<option value="'.$v['idestado'].'" selected>'.$v['estado'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$v['idestado'].'">'.$v['estado'].'</option>'; 
                                                                }
                                                                
                                                            }
                                                         ?>  
                                                    </select>
                                                    <input type="text" id="inputNuevoMunicipio" class="form-control" placeholder="Nombre de municipio">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoMunicipio">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<!-- F I N   D E L   M O D A L -->
<!-- M O D A L   P A R A   A G R E G A R   U N   N U E V O   P A Í S -->
                                    <div class="modal fade" id="modalPuestos" role="dialog" aria-labelledby="nuevoPais" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" >Agregar nuevo Puesto</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" id="inptpuesto" class="form-control" placeholder="Nombre de Puesto">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnPuesto">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<!-- F I N   D E L   M O D A L -->

        <div class="modal fade" id="modalLoad" role="dialog" style="z-index:1051;" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Espere un momento...</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-default">
                            <div align="center"><label id="lblMensajeEstado"></label></div>
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

<script>
    $(document).ready(function() {
        //$("#modalexito").modal('show');
        //$("#regimen, #municipios, #estado, #pais").select2({width: '170px'});
        $("#selectPais2, #selectPais3, #selectEstado3").select2({width: '100%'});
        $("#regimen, #municipios, #estado, #pais").select2();
        $("#puesto, #perfil, #suc, #cliente").select2({
            dropdownAutoWidth : true,
            width: '270px'
        });
        relaodPU();
        relaodAU();
        //reloadB();        
    });

    function reloadB(){
        $('#divBien').html('');
        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=configuraciong&f=reloadB',
                type: 'post',
                dataType: 'html',
        })
        .done(function(data) {
            $('#divBien').append(data);
        });
    }

    function moreApps(){
        $('#nuevaAventura').html('');
        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=configuraciong&f=moreApps',
                type: 'post',
                dataType: 'html',
        })
        .done(function(data) {
            $('#nuevaAventura').append(data);
        });
        $("#modalApps").modal('show');
    }

    function validarfc(rfc){
            var rfc = rfc.replace(/\s*[\r\n][\r\n \t]*/g, "");
            var valid = /^([A-Z,Ñ,&]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/;

            if(rfc.length <12){
                return 0;
            }
            var validRfc=new RegExp(valid);
            var matchArray=rfc.match(validRfc);
            if (matchArray==null){
                return 0;
            }else{
                return 1;
            }   
    }

    function validarEmail(valor) {
      if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
       //alert("La dirección de email " + valor + " es correcta!.");
      } else {       
       return 1;
      }
    }

    function saveMiOrg(){

        var file = $('#miarchi').val();

        if(file == '' || file == null){
            $('#logoempresa').val('<?php echo $miOrganizacion[0]['logoempresa']; ?>');
            //alert('vacio '+'<?php echo $miOrganizacion[0]['logoempresa']; ?>');
        }else{
            $('#logoempresa').val(file);
            //alert('new'+file);
        }

        var logoempresa = $('#logoempresa').val();            
        var idOr = $('#idOr').val();
        var razon = $('#razon').val();

        var rfc = $('#rfc').val();
        var regimen = $('#regimen').val();
        var web = $('#web').val();
        var domicilio = $('#domicilio').val();
        var colonia = $('#colonia').val();
        var pais = $('#pais').val();
        var estado = $('#estado').val();
        var municipio = $('#municipios').val();
        var cp = $('#cp').val();

        if(idOr == '' || razon == '' || rfc == ''){
            alert('¡Faltan Campos Obligatorios!');
        }

        var valRFC = validarfc(rfc);

        if(valRFC == 1){

        }else{  
            alert('RFC Invalido!');
            return false;
        }

        //return false;
        $("#modalLoad").modal('show');
        var validaFile = 1;
        $('input[type=file]').simpleUpload('views/configuraciong/subirarchivo.php', {
                            start: function(file){
                                //upload started
                                console.log("upload started");
                            },

                            progress: function(progress){
                                //received progress
                                console.log("upload progress: " + Math.round(progress) + "%");
                            },

                            success: function(data){
                                //upload successful
                                console.log(data);
                                var objresp = $.parseJSON(data);
                                console.log(objresp);
                                var suc = objresp['success'];
                                var msg = objresp['message'];
                                if(suc == false){
                                    alert(msg);
                                    validaFile = 0;
                                    $("#modalLoad").modal('hide');
                                }                              
                            },

                            error: function(error){
                                alert('Error al subir la imagen');
                                return false;
                                console.log("upload error: " + error.name + ": " + error.message);
                                alert("upload error: " + error.name + ": " + error.message);
                                validaFile = 0;
                                $("#modalLoad").modal('hide');
                            }
                        });

        setTimeout(function(){ saveorg(); }, 2000);
        
        function saveorg(){
            if(validaFile == 1){
                
                $.ajax({
                        url: 'ajax.php?c=configuraciong&f=saveMiOrg',
                        type: 'post',
                        dataType: 'json',
                        data:{idOr:idOr,
                            razon:razon,
                            logoempresa:logoempresa,
                            rfc:rfc,
                            regimen:regimen,
                            web:web,
                            domicilio:domicilio,
                            colonia:colonia,
                            pais:pais,
                            estado:estado,
                            municipio:municipio,
                            cp:cp
                        }
                })
                .done(function(data) {
                    $("#modalexito").modal('show');
                    $("#modalLoad").modal('hide');
                })     
            }            
        }
                
        
    }
    function relaodPU(){
        $('#divtablePU').html('');
        $('#divtablePU2').html('');
        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=configuraciong&f=relaodPU',
                type: 'post',
                dataType: 'html',
        })
        .done(function(data) {
            $('#divtablePU').html('');
            $('#divtablePU2').html('');
            $('#divtablePU').append(data);
            $('#tablePU').DataTable( {
                                iDisplayLength: 5,
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'print',
                                        title: $('h1').text(),
                                        customize: function ( win ) {
                                            $(win.document.body)
                                            .css( 'font-size', '10pt' )
                                            .prepend(
                                            '<h3>Perfiles de Usuarios</h3>'
                                            );                                                     
                                        }
                                    },
                                    'excel',
                                ],
                                destroy: true,
                                searching: true,
                                language: {
                                    buttons: {
                                        print: 'Exportar'
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
                                 }
                            });

            $("#tablePU_wrapper").find(".dt-buttons").append('<a class="dt-button buttons-print" style="padding-top:0px;padding-bottom:0px;padding-left:0px;padding-right:0px;"><button onclick="newPU();" class="btn btn-primary btn-sm">Nuevo <i aria-hidden="true" class="fa fa-plus"></i></button></a>');


        });

    }
    function newPU(){
        $("#divtablePU").html(''); 
        $("#divtablePU2").html('');        

        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                    url: 'ajax.php?c=configuraciong&f=relaodPU2',
                    type: 'post',
                    dataType: 'html',
            })
            .done(function(data) {
                $('#divtablePU2').append(data);
                $("#divtablePU2").show();
            });
    }
    function editPU(idPU,nombre){
        $("#divtablePU").html(''); 
        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                    url: 'ajax.php?c=configuraciong&f=reloadEditPU',
                    type: 'post',
                    dataType: 'html',
                    data:{idPU:idPU,nombre:nombre}
            })
            .done(function(data) {
                $('#divtablePU2').append(data);
                $("#divtablePU2").show();
            });
    }
    function editedPU(idperfil){

        var perfil = $("#perfil").val();
        var strmenus = '';
        
        $('input[type=checkbox]').each(function () {
            if(this.checked){            
               strmenus += this.value+',';
            }                         
        });
        console.log(idperfil+' '+perfil+' '+strmenus);

        $.ajax({ 
            data : {idperfil:idperfil, perfil:perfil,strmenus:strmenus},
            url: 'ajax.php?c=configuraciong&f=editedPU',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) { 
            $("#divtablePU2").html('');
            //relaodPU();
            $("#divtablePU").show();

            $("#headE").text('¡Exito!');
            $("#msgE").text('Su perfil se guardaron correctamente');
            $("#btnE").text('Cerrar');   
            $("#claE").removeClass('alert-success');          
            $("#claE").addClass('alert-warning');
            $("#modalexito2").modal('show');
                                  
            console.log(data);
        })
    }

    function relaodAU(){

        $('#divtableAU').html('');
        $('#newUser2').html('');
        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=configuraciong&f=relaodAU',
                type: 'post',
                dataType: 'html',
        })
        .done(function(data) {
            $('#divtableAU').html('');
            $('#newUser2').html('');
            $('#divtableAU').append(data);
            $("#tableAU").DataTable( {
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'print',
                                        title: $('h1').text(),
                                        customize: function ( win ) {
                                            $(win.document.body)
                                            .css( 'font-size', '10pt' )
                                            .prepend(
                                            '<h3>Administración de Usuarios</h3>'
                                            );                                                     
                                        }
                                    },
                                    'excel',
                                ],
                                destroy: true,
                                searching: true,
                                language: {
                                    buttons: {
                                        print: 'Exportar'
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
                                 }
                            });        
            $("#tableAU_wrapper").find(".dt-buttons").append('<a class="dt-button buttons-print" style="padding-top:0px;padding-bottom:0px;padding-left:0px;padding-right:0px;"><button onclick="newUser2();" class="btn btn-primary btn-sm">Nuevo <i aria-hidden="true" class="fa fa-plus"></i></button></a>');
            $("#tableAU").addClass('pull-left');
        });
    }

    function verlogoempresa(file){
        window.open('../../netwarelog/archivos/1/organizaciones/'+file+'', '_blank');
    }

    function deletePU(idperfil){

        var r = confirm("¿Desea eliminar el perfil?");
        if (r == true) {
            $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                    url: 'ajax.php?c=configuraciong&f=deletePU',
                    type: 'post',
                    dataType: 'html',
                    data:{idperfil:idperfil}
            })
            .done(function(data) {
                alert('¡Operacion Exitosa!');            
                //location.reload();
                $("#divtablePU").html('');
                relaodPU();
                $("#divtablePU").show();
            });
        } else {
    
        } 
        
    }

    function newUser2(idadmin){
        $("#divtableAU").html('');
        $("#newUser2").html('');
        $.ajax({ 
            data:{idadmin:idadmin},
            url: 'ajax.php?c=configuraciong&f=newUser2',
            type: 'post',
        })
        .done(function(data) {  
            $("#newUser2").append(data);
            $("#perfil, #puesto, #suc, #cliente").select2();             
        })
    }

    $( "#nombreUser" ).focusout(function() {
        var nombreUser = $("#nombreUser").val();
        $.ajax({
                url: 'ajax.php?c=configuraciong&f=user',
                type: 'post',
                data:{nombreUser:nombreUser,
                async : false
                }
        })
        .done(function(data) {
            var auxE = $("#auxE").val();
            if(data == 1){
                if(auxE == 1){
                    console.log('Edit');
                }else{
                     alert('Nombre de usuario no permitido!');                
                    $("#nombreUser").focus();                
                    return false; 
                }
               
            }
                    
        }) 
    })

    function saveAU(){
        var fotoPerfil = '';
        var idadmin = $("#idadmin").val();
        var nombre = $("#nombre").val();
        var apellidos = $("#apellidos").val();
        var nombreUser = $("#nombreUser").val();
        var pass = $("#pass").val();
        var pass2 = $("#pass2").val();
        var email = $("#email").val();
        var suc = $("#suc").val();
        var perfil = $("#perfil").val();
        var puesto = $("#puesto").val();
        var cliente = $("#cliente").val();        
        var auxE = $("#auxE").val();
        var idempleado = $("#idempleado").val();
        var fotoPerfil1 = $("#fotoPerfil").val();
        var fotoPerfilAUX = $("#fotoPerfil2").val();
        var auxpass = $("#auxpass").val();

        if(auxpass == 1){
            var expresionR=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?!.*\s).{4,500}$/;
            var resultado=expresionR.test(pass);
            if(resultado != true){
            alert('La contraseña debe de contener al menos una mayúscula y un número');
            return false;
            }
        }
        
        
        
        if(fotoPerfil1 == ''){
            fotoPerfil = fotoPerfilAUX;
        }else{
            fotoPerfil = fotoPerfil1;
        }

        //alert(fotoPerfil);
        //return false;

        if(nombre == '' || apellidos == '' || nombreUser == '' || pass == '' || pass2 == ''){
            alert('Faltan Campos Obligatorios!');
            return false;
        }

        if(pass != pass2){
            alert('Las Contraseñas no coninciden!');
            return false;
        }

        /*
        var validaE = validarEmail(email);
        if(validaE == 1){
            alert("La dirección de email es incorrecta!");
            return false;
        }
        */

        
        //alert(auxpass+' '+pass);
        //return false;        
       $('input[type=file]').simpleUpload('views/configuraciong/subirarchivo2.php', {
                            start: function(file){
                                //upload started
                                console.log("upload started");
                            },

                            progress: function(progress){
                                //received progress
                                console.log("upload progress: " + Math.round(progress) + "%");
                            },

                            success: function(data){
                                //upload successful
                                console.log(data);
                                var objresp = $.parseJSON(data);
                                console.log(objresp);
                                
                            },

                            error: function(error){
                                alert('Error al subir el archivo');
                                console.log("upload error: " + error.name + ": " + error.message);
                            }
                        });

       $.ajax({
                url: 'ajax.php?c=configuraciong&f=saveAU',
                type: 'post',
                dataType: 'json',
                data:{nombre:nombre,
                    apellidos:apellidos,
                    nombreUser:nombreUser,
                    pass:pass,
                    email:email,
                    suc:suc,
                    perfil:perfil,
                    puesto:puesto,
                    fotoPerfil:fotoPerfil,
                    auxE:auxE,
                    idempleado:idempleado,
                    idadmin:idadmin,
                    cliente:cliente,
                    auxpass:auxpass

                }
        })
        .done(function(data) {
            
            //relaodAU();
            //$("#modalAU").modal('hide');  
            $("#modalexito3").modal('show');          
        }) 
        
    }
    function editedAU(){


               $('input[type=file]').simpleUpload('views/configuraciong/subirarchivo2.php', {
                            start: function(file){
                                //upload started
                                console.log("upload started");
                            },

                            progress: function(progress){
                                //received progress
                                console.log("upload progress: " + Math.round(progress) + "%");
                            },

                            success: function(data){
                                //upload successful
                                console.log(data);
                                var objresp = $.parseJSON(data);
                                console.log(objresp);
                                
                            },

                            error: function(error){
                                alert('Error al subir el archivo');
                                console.log("upload error: " + error.name + ": " + error.message);
                            }
                        });

    }
    function editAU(id){     
        $("#btnsaveAU").text('Guardar');
        $("#auxE").val(1);
        $("#idadmin, #nombre, #apellidos, #nombreUser, #pass, #pass2, #email, #fotoPerfil, #fotoPerfil2").val('');
        $.ajax({
                url: 'ajax.php?c=configuraciong&f=editAU',
                type: 'post',
                data:{id:id},
                dataType: 'json'
        })
        .done(function(data) {

            //$("#modalAU").modal('show');
            console.log(data);  
            $.each(data, function(index, val) {
                //alert(val.idperfil);
                $("#idadmin").val(val.idadmin);
                $("#nombre").val(val.nombre);
                $("#apellidos").val(val.apellidos);
                $("#nombreUser").val(val.nombreusuario);
                $("#pass").val(val.clave);
                $("#pass2").val(val.confirmaclave);
                $("#email").val(val.correoelectronico);
                $("#puesto").val(val.idpuesto);
                $("#perfil").val(val.idperfil);
                $("#suc").val(val.idSuc);
                $("#idempleado").val(val.idempleado);
                $("#fotoPerfil2").val(val.foto);
                $("#lbFoto").text('Fotografia Perfil: '+val.foto+'');

                $("#btndescAU").prop({
                    href: '../../netwarelog/descarga_archivo_fisico.php?d=1&f='+val.foto,
                })

            });
        }) 
    }

    function saveAF(){        
        var apps = "";
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                apps+=this.value+',';
            }            
        });  

        $.ajax({
                url: 'ajax.php?c=configuraciong&f=saveAF',
                type: 'post',
                dataType: 'json',
                data:{apps:apps}
        })
        .done(function(data) {
            alert('¡Registro Exitoso!'); 
            reloadB();
            $("#modalApps").modal('hide');                       
        })       
    }
    function backP(){
        $("#modalBP").modal('show');
    }
    function cancelPU(){
        $("#modalBP").modal('hide');
        $("#divtablePU2").html('');
        relaodPU();
        $("#divtablePU").show();
    }
    function savePU(){
        var perfil = $("#perfil").val();
        var strmenus = '';

        if(perfil == ''){
            alert('¡Ingrese un nombre!');
            return false;
        }
        
        $('input[type=checkbox]').each(function () {
            if(this.checked){            
               strmenus += this.value+',';
            }                         
        });
        $.ajax({ 
            data : {perfil:perfil,strmenus:strmenus},
            url: 'ajax.php?c=configuraciong&f=savePU',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) { 
            $("#divtablePU2").html('');
            //relaodPU();
            $("#divtablePU").show();
            $("#modalexito2").modal('show');                       
            console.log(data);
        })
    }

    $('#estado').change(function()
            {   

                $('#municipios').html('');
                $('#municipios').append('<option selected="selected" value="0">Selecciona un Municipio</option>');
                $('#municipios').select2("val", '0');
                var idestado = $("#estado").val();
                //alert(idestado);
                $.ajax({ 
                        data : {idestado:idestado},
                        url: 'ajax.php?c=configuraciong&f=municipios',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {                        
                        $.each(data, function(index, val) {
                              $('#municipios').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                              $("#municipios").trigger('change.select2');
                        });
                    })
            });
            
    function reloadOrg(){
        $("#modalexito").modal('hide');
        location.reload();
    }
    function reloadPU2(){
        $("#modalexito2").modal('hide');
        relaodPU();
    }
    function reloadPU3(){
        $("#modalexito3").modal('hide');
        relaodAU();
    }
    function usuarios(perfil){
        $("#userbody").html('');
        $.ajax({ 
            data : {perfil:perfil},
            url: 'ajax.php?c=configuraciong&f=usuarios',
            type: 'post',
        })
        .done(function(data) {  
            $("#userbody").append(data);               
            $("#modalUsers").modal('show');
        })
        
    }
    function deleteAU(idadmin,idempleado){

        var r = confirm("¿Desea eliminar el usuario?");
        if (r == true) {
            $.ajax({ 
                    url: 'ajax.php?c=configuraciong&f=deleteAU',
                    type: 'post',
                    dataType: 'json',
                    data:{idadmin:idadmin,idempleado:idempleado}
            })
            .done(function(data) {
                alert('¡Operacion Exitosa!');            
                //location.reload();
                $("#divtableAU").html('');
                relaodAU();
                $("#divtableAU").show();
            });
        } else {
    
        } 
        

    }
    function modalpais(){
        $('#inputNuevoPais').val('');
        $("#nuevoPais").modal('show');
    }
    $('#btnNuevoPais').on('click', () => {
        var nombre = $('#inputNuevoPais').val();
        if(nombre != ''){
            $.ajax({ 
                data : {nombre:nombre},
                url: 'ajax.php?c=configuraciong&f=nuevoPais',
                type: 'post',
            })
            .done(function(data) {  
                alert(data+' '+nombre);
                $("#pais").append('<option value='+data+' selected>'+nombre+'</option>');
                $("#pais").trigger('change.select2');

                $("#selectPais2").append('<option value='+data+' selected>'+nombre+'</option>');
                $("#selectPais2").trigger('change.select2');

                $("#selectPais3").append('<option value='+data+' selected>'+nombre+'</option>');
                $("#selectPais3").trigger('change.select2');

            })
        }else{
            alert("No puedes dejar el campos vacios");
        }
    });

    $('#btnNuevoEstado').on('click', () => {
        var idpais = $("#selectPais2").val();
        var estado = $('#inputNuevoEstado').val(); 
        if(idpais == 0){ alert('Seleccione un País'); return false;}

        if(estado != ''){
            $.ajax({ 
                data : {idpais:idpais,estado:estado},
                url: 'ajax.php?c=configuraciong&f=nuevoEstado',
                type: 'post',
            })
            .done(function(data) {  
                
                $("#estado").append('<option value='+data+' selected>'+estado+'</option>');
                $("#estado").trigger('change.select2');

                $("#selectEstado3").append('<option value='+data+' selected>'+estado+'</option>');
                $("#selectEstado3").trigger('change.select2');

            })
        }else{
            alert("No puedes dejar el campos vacios");
        }
    });

    $("#selectPais3").change(function(event) {
        var idpais = $("#selectPais3").val();
        $("#selectEstado3").html('');
        $.ajax({ 
                data : {idpais:idpais},
                url: 'ajax.php?c=configuraciong&f=reloadEstado',
                type: 'post',
                dataType: 'json'
            })
            .done(function(data) { 
                $.each(data, function(index, val) {
                      $('#selectEstado3').append('<option value="'+val.idestado+'">'+val.estado+'</option>');  
                });                
                $("#selectEstado3").trigger('change.select2');
            })        
    });

    $('#btnNuevoMunicipio').on('click', () => {
        
        var idestado = $("#selectEstado3").val();
        var municipio = $('#inputNuevoMunicipio').val(); 
        if(idestado == 0){ alert('Seleccione un Estado'); return false;}

        if(municipio != ''){
            $.ajax({ 
                data : {idestado:idestado,municipio:municipio},
                url: 'ajax.php?c=configuraciong&f=nuevoMunicipio',
                type: 'post',
            })
            .done(function(data) {  
                
                $("#municipios").append('<option value='+data+' selected>'+municipio+'</option>');
                $("#municipios").trigger('change.select2');

            })
        }else{
            alert("No puedes dejar el campos vacios");
        }
    });

    $("#pais").change(function(event) {
        var idpais = $("#pais").val();
        $("#estado").html('');
        $('#estado').append('<option value="0" selected>Seleccione un Estado</option>');
        $.ajax({ 
                data : {idpais:idpais},
                url: 'ajax.php?c=configuraciong&f=reloadEstado',
                type: 'post',
                dataType: 'json'
            })
            .done(function(data) { 
                $.each(data, function(index, val) {
                      $('#estado').append('<option value="'+val.idestado+'">'+val.estado+'</option>');  
                });                
                $("#estado").trigger('change.select2');
            })        
    });

    function puestos(){
        $("#modalPuestos").modal('show');
    }

    $('#btnPuesto').on('click', () => {
        
        
        var puesto = $('#inptpuesto').val(); 
        

        if(puesto != ''){
            $.ajax({ 
                data : {puesto:puesto},
                url: 'ajax.php?c=configuraciong&f=nuevoPuesto',
                type: 'post',
            })
            .done(function(data) {  
                
                $("#puesto").append('<option value='+data+' selected>'+puesto+'</option>');
                $("#puesto").trigger('change.select2');

            })
        }else{
            alert("No puedes dejar el campos vacios");
        }
    });

    function editpass(){       
        var r = confirm("¿Desea editar la contraseña?");
        if (r == true) {
            $("#pass").attr('onfocus', '');
            $("#pass2, #pass").removeAttr("readonly").val('');
            $("#auxpass").val(1);         
            $("#pass2").focus();
            $("#pass2").trigger('click');
            $("#pass").focus();
            $("#pass").click();
            $("#pass").trigger('click');

        }else{
            return false;
        }
    }
</script>




