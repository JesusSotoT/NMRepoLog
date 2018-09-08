<style>
    .panel-heading{
        height: 50px;
    }
    .blueText{
        color: #48D1CC;
    }
    .subText{
        color: #808080;
    }
    .divdorder{
        border: red;
    }
    .subText2{
      font-family: monospace;
      color: #48D1CC;
    }
    .panel-heading {background-color: blue!important}
    .btnmin{
        min-width: 77px !important;
    }
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Implementacion Inicial</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <script src="js/implementacion.js"></script>

    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/jquery.min.js"></script>
    
    <body> 
        <div class="container">
            <div class="col-md-12">
                 <div class="col-md-3 pull-right">
                    <img width="280" height="80" src="images/dias.png">
                </div>
                <div class="col-md-9 divdorder">
                    <h2 class="blueText">Hola <?php echo $nombre_instacia ?></h2>
                    <h3 class="subText"><b>Bienvenido a <img src="" alt=""> El mejor socio para impulsar tu negocio</b></h3><br><br><br>
                </div>

                <div class="col-md-12 subText"style="border-width: 2px; border-style: solid; border-color: 808080; "></div>
               
            </div>             
            <div class="container" id="divempresas" >
                <div class="col-md-12 text-center subText2"><h3>Elije el perfil de tu Empresa</h3></div>
                <div class="col-md-12 text-center subText"><h3>Elejir un perfil nos ayudara a comprender mejor tu negocioy poder aconsejrte para que puedas aprovechar mas Appministra </h3></div>
                <div class="col-md-12 text-center"> 
                    <div class="col-md-6 text-center">
                        <img style="cursor: pointer" width="150" height="150" src="images/eservicios.png" onclick="imgservicios();">
                    </div>
                    <div class="col-md-6 text-center">
                        <img style="cursor: pointer" width="150" height="150" src="images/ecomercio.png" onclick="imgcomercio();">
                    </div>
                    
                    <!--
                    <img style="cursor: pointer" width="150" height="150" src="images/emanu.png" onclick="imgmanu();">    
                    -->
                    
                </div>
            </div> 
           
            <div class"container" id="divServicios"> 
                <div class="pull-right">
                    <button type="button" class="btn btn-info" onclick="back();"> <- Regresar</button>
                </div>
                <div>
                    <h3> Haz elegido el Perfil de: <label class="blueText">Servicios</label></h3>
                    <h5 class="subText">A continuación te mostraremos una serrie de actividades sugeridas, que te ayudaran a conocer Appministra y configurar tu empresa</h5>
                </div>
                
                
                <div class="panel-group" id="accordion">
                    <div class="panel panel-primary">
                        <div class="panel-heading panel-primary">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#serv1">
                                     <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div id="sp1pro" class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                                        </div>
                                    </div>
                                    <div>Paso 1:</div>
                                </a>
                            </h4>
                        </div>
                        <div id="serv1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-xs-11">
                                    <label >Logo de tu empresa <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" width="20px" title="El logo de tu empresa se utiliza para generar tus facturas, remisiones y otros documentos"> </label><br>
                                </div>
                                <div class="col-xs-1">
                                    <button id="sp1logo" type="button" class="btn btn-primary btnmin" onclick="logoEmpresa();" >Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Facturacion Electronica <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" title="Por definir"> </label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="sp1factura" type="button" class="btn btn-primary btnmin" onclick="facturacionEmpresa();">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Sucursales <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" title="Por definir"> </label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="sp1sucursal" type="button" class="btn btn-primary btnmin" onclick="sucursalEmpresar();">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Monedas <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" title="Por definir"> </label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="sp1moneda" type="button" class="btn btn-primary btnmin">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Bancos y Cajas <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" title="Por definir"> </label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="sp1banco" type="button" class="btn btn-primary btnmin">Realizar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#serv2">
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                <div>Paso 2:</div>
                                </a>
                            </h4>
                        </div>
                        <div id="serv2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >FALTA DEFINIR</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#serv3">
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                <div>Paso 3:</div><br>
                                </a>
                            </h4>
                        </div>
                        <div id="serv3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >FALTA DEFINIR</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#serv4">
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                <div>Paso 4:</div>
                                </a>
                            </h4>
                        </div>
                        <div id="serv4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >FALTA DEFINIR</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class"container" id="divComercio">
                <div class="pull-right">
                    <button type="button" class="btn btn-info" onclick="back();"><- Regresar</button>
                </div>
                <div>
                    <h3> Haz elegido el Perfil de: <label class="blueText">Comercio</label></h3>
                    <h5 class="subText">A continuación te mostraremos una serrie de actividades sugeridas, que te ayudaran a conocer Appministra y configurar tu empresa</h5>
                </div>
                
                <div class="panel-group" id="accordion">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#com1">
                                     <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                    <div>Paso 1: </div>
                                </a>
                            </h4>
                        </div>
                        <div id="com1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >FALTA DEFINIR</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#com2">
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                <div>Paso 2:</div> 
                                </a>
                            </h4>
                        </div>
                        <div id="com2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >Lista de Precio</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="cp1listaP" type="button" class="btn btn-primary btnmin" onclick="preciosEmpresa();">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Registra un Producto</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="cp1prod" type="button" class="btn btn-primary btnmin">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Importa tu catalogo de productos por medio de Excel</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="cp1prodE" type="button" class="btn btn-primary btnmin">Realizar</button>
                                </div>

                                <div class="col-md-11">
                                    <label >Importar tus listas de precio</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button id="cp1listapImpor" type="button" class="btn btn-primary btnmin'">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#com3">
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                <div>Paso 3:</div> 
                                </a>
                            </h4>
                        </div>
                        <div id="com3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-11">
                                    <label >FALTA DEFINIR</label><br>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary">Realizar</button>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>

<script>
    $(document).ready(function() {
        $("#divServicios").hide();
        $("#divComercio").hide();
        $("#divempresas").show();
        cargaInicial();
    });
    function imgservicios(){
        $("#divServicios").show();
        $("#divempresas").hide();
    }
    function imgcomercio(){
        $("#divComercio").show();
        $("#divServicios").hide();
        $("#divempresas").hide();
    }
    function back(){
        $("#divServicios").hide();
        $("#divComercio").hide();
        $("#divempresas").show();
    }


</script>


