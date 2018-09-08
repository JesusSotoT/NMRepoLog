<?php
ini_set("display_errors",1);
include('../../netwarelog/webconfig.php');

include "../../netwarelog/catalog/conexionbd.php";
$conexion->cerrar();

include "clases.php";
$dev = new clnmdev();
$eventos = $dev->get_eventos();
$asistentes = $dev->get_asistentes();
$empresas_factura = $dev->get_empresas_factura();
$dev->disconnect();

?>
<html lang="es">
<head>
    <meta http-equiv="Expires" content="0">
    <title>Punto de venta</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" />
    <!-- <link rel="stylesheet" href="css/typeahead.css" /> -->
    <link rel="stylesheet" href="css/caja.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  
    <?php include('../../netwarelog/design/css.php');?>
    <!--NETWARLOG CSS -->
    <!-- <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />  -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/registro.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $("select#slcEvento").select2({dropdownAutoWidth : 'true'});
        //$('select.form-control').select2({dropdownAutoWidth: 'true'});
        //$("select.form-control").select2({dropdownAutoWidth : 'true'});
        //$("#slcEvento").select2({dropdownAutoWidth : true});

        $('#selFecha').datepicker({ dateFormat: 'yy/mm/dd' });
    });
  </script>


</head>

<body>


    <div id="contenido" class="col-xs-12 container-fluid"> 

        <div class="col-xs-12 well">
            <label  id="num_caja" class="col-xs-12 text-center">Registro de asistentes</label> 
            <form class="form-horizontal" role="form">
                <div class="form-group divParametros" >
                        <input type="hidden" value="" id="codigo">
                        <input type="hidden" value="" id="propina">
                        <input type="hidden" id="hidencliente-caja" value="">


                        <label for="cliente-caja" class="col-xs-2 control-label">Evento:</label>
                        <div class="col-xs-2">
                            <select class="form-control nminputselect" id="slcEvento">

                                <option value="0">--Elija un evento--</option>
                                <?php
                                foreach ($eventos as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value["idEvento"]?>"><?php echo $value["strNombre_Evento"]?></option>
                                    <?php
                                }
                                ?>
         
                            </select>
                        </div>
                </div>
                <div class="form-group divParametros" >

                        <label for="cliente-caja" class="col-xs-2 control-label">Asistente:</label>
                        <div class="col-xs-2">
                            <select class="form-control nminputselect" id="selAsistente">

                                <option value="0">--Elija un asistente--</option>
                                <?php
                                foreach ($asistentes as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value["id"]?>"><?php echo $value["nombre"]?></option>
                                    <?php
                                }
                                ?>
         
                            </select>
                        </div>

                        
                        <input id="hidensearch-producto" type="hidden">
                        <input id="hiddensearch-asistente" type="hidden">
                        
                        <!--input id="btn_buscar_producto" type="button" onclick="buscarProducto();" value="...."-->
                        <input id="codigo" type="hidden" value="">
                </div>
                <div class="form-group divParametros" >

                        <label for="cliente-caja" class="col-xs-2 control-label">Facturar a:</label>
                        <div class="col-xs-2">
                            <select class="form-control nminputselect" id="selEmpresa_factura">

                                <option value="0">--Elija un empresa--</option>
                                <?php
                                foreach ($empresas_factura as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value["id"]?>"><?php echo $value["nombre"]?></option>
                                    <?php
                                }
                                ?>
         
                            </select>
                        </div>

                        
                        <input id="hidensearch-producto" type="hidden">
                        <input id="hiddensearch-asistente" type="hidden">
                        
                        <!--input id="btn_buscar_producto" type="button" onclick="buscarProducto();" value="...."-->
                        <input id="codigo" type="hidden" value="">
                </div>
                <div class="form-group divParametros" >

                        <label for="cliente-caja" class="col-xs-2 control-label">Fecha:</label>
                        <div class="col-xs-2">
                            <input type="text" id="selFecha" value="--Elija una fecha--"/>
                        </div>

                        
                        <input id="hidensearch-producto" type="hidden">
                        <input id="hiddensearch-asistente" type="hidden">
                        
                        <!--input id="btn_buscar_producto" type="button" onclick="buscarProducto();" value="...."-->
                        <input id="codigo" type="hidden" value="">
                </div>
                <div class="btn btn-success col-xs-2" onclick="registro.modal_mostrar();">Registrar</div>
            </form>           
        </div>
    </div>
    <form class="form-horizontal container-fluid col-xs-12" id="modalPago" role="form" style="display:none;width:500px" >
        <div class="pull-left col-xs-12" id="divReferenciaPago" style="display:none;" >
                <label id="lblReferencia" class="text-left control-label">Referencia transferencia:</label>
                <input type="text" id="txtReferencia" class="form-control pull-left" value="">
        </div>
        <div class="col-xs-12" id="divPagos">
                <div class="col-xs-12 text-center" style="float: none";>
                    <label class="control-label">CONFIRMACION DE REGISTRO </label>
                    
                </div>
                <div class="col-xs-12" style="float: none";>
                    <label class="text-left control-label">Evento : </label>
                    <label class="text-left" id="lblEvento"></label>
                </div>
                <div class="col-xs-12" style="float: none";>
                    <label class="text-left control-label">Asistente : </label>
                    <label class="text-left" id="lblAsistente"></label>
                </div>
                <div class="col-xs-12" style="float: none";>
                    <label class="text-left control-label">Empresa a facturar:</label>
                    <label class="text-left" id="lblEmpresa_factura"></label>
                </div>
                <div class="col-xs-12" style="float: none";>
                    <label class="text-left control-label">Fecha:</label>
                    <label class="text-left" id="lblFecha"></label>
                </div>
        </div>
    </form>
    
    
    
  



</body> 
</html>