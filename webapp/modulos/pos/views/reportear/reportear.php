<!DOCTYPE html>
<html lang="">
	<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte de Abonos y Retiros</title>
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/reportear.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">

    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

    <script src="../../libraries/numeric/jquery.numeric.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

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


	<body>

	<div class="container well" >
        <div class="row">
            <div class="col-xs-12 col-md-12">
               <h3>Abonos y Retiros</h3>
            </div>
        </div>
        <div class="row col-md-12" >                     
            <div class="panel panel-default" id="divfiltro">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-6">
                                <label>Desde:</label>
                                <div class="input-group date">                                
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input style="background-color: white;" id="desde" class="form-control" type="text" placeholder="Desde">                             
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <label>Hasta:</label>
                                <div class="input-group date">                                    
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span> 
                                    <input style="background-color: white;" id="hasta" class="form-control" type="text" placeholder="Hasta">   
                                </div> 
                            </div>
                            
                            
                            
                            
                            <?php 

                            if($_SESSION['version'] == 2)
                                echo '<div class="col-sm-4">
                                    <label></label>
                                    <button class="btn btn-danger btn-block" onclick="formRetiro();"><i class="fa fa-minus" aria-hidden="true"></i> Retiro</button>
                                </div>
                                <div class="col-sm-4">
                                    <label></label>
                                    <button class="btn btn-success btn-block" onclick="formAbono();"><i class="fa fa-plus" aria-hidden="true"></i> Abono</button>
                                </div>';

                             ?>


                             
                            <div class="col-sm-4" style="text-align: center;">
                                <label></label>
                                <br>
                                <button type="button" class="btn btn-primary" onclick="generar();">Generar</button>
                            </div>

                        </div>
                        <div class="col-sm-6" >                    
                            <label>Tipo de Movimiento</label><br>
                            <select id="tipoM" class="form-control" style="width: 100%;">                            
                                <option selected="selected" value="1">Retiro</option>
                                <option value="2" >Abono</option>                                           
                            </select>
                            <div id="divtipoA" style="display: none;" style="padding-top: 50px;">
                                <br>
                                <label>Tipo de Abono</label><br>
                                <select id="tipoA" class="form-control" style="width: 100%;">
                                    <option selected="selected" value="1" >General</option>  
                                    <option value="2" >Por cliente</option>                     
                                </select>
                            </div>
                            <div id="divcli" style="display: none;">
                                <br>
                                <label>Cliente</label><br>
                                <select id="cliente" class="form-control" style="width: 100%;" multiple="">
                                    <option value="0" selected="selected">Todos</option>
                                    <?php 
                                        foreach ($clientes as $k => $v) {
                                            echo "<option value=".$v['id'].">".$v['nombre']."</option> ";
                                        }
                                     ?>  
                                </select>
                            </div>
                        </div>
                    </div>
                </div>  
                <div id="divtable" class="panel-body">

                </div>  
            </div>
        </div>        
    </div>




      <!-- Modal del Form -->
  <div class="modal fade" id="modalformRetiro" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header modal-header-primary">
          <h4 class="modal-title"><i class="fa fa-minus" aria-hidden="true"></i> Nuevo Retiro</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <label>Disponible $</label>
                    <input type="text" class="form-control numeros" id="saldo_disponibleR" readonly>
                </div>
                <div class="col-sm-12">
                    <label>Cantidad a Retirar</label>
                    <input type="text" class="form-control numeros" id="cantidadRetiro">
                </div>
                <div class="col-sm-12">
                    <label>Concepto</label>
                    <textarea  cols="30" rows="5" id="concepto" class="form-control"></textarea>
                </div>
            </div>
        </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                             <button class="btn btn-primary btn-block" onclick="retira();"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button> 
                        </div>
                    </div>
                </div>
      </div>
    </div>
  </div>
  <!-- Fin modal retiro -->
  <!-- Modal abono -->
    <!-- Modal del Form -->
  <div class="modal fade" id="modalformAbono" role="dialog">
    <div class="modal-dialog modal-md modal-primary">
      <div class="modal-content">
        <div class="modal-header modal-header-primary">
          <h4 class="modal-title"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Abono</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <label>Cliente</label>
                    <select class="form-control" id="clienteAbono" onchange="buscaCargos();">
                        <option value="0">-Selecciona Cliente-</option>
                        <?php 
                            foreach ($cliente['clientes'] as $key => $value) {
                               echo '<option value="'.$value['id'].'">'.$value['codigo'].'/'.$value['nombre'].'</option>';
                            }
                        ?>
                    </select>
                </div>
              <!-- <div class="col-sm-12">
                    <label>Cargos</label>
                    <select id="cargosAbono"><option value="0">-Selecciona Cargo-</option></select>
                </div> -->
                <div class="col-sm-12">
                    <label>Importe</label>
                    <input type="text" class="form-control numeros" id="cantidadAbono">
                </div>
                <div class="col-sm-12">
                    <label>Concepto</label>
                    <input type="text" id="conceptoAbono" class="form-control">
                </div>
                <div class="col-sm-12">
                    <label>Forma de Pago</label>
                    <select class="form-control" id="formaPagoAbono">
                    <?php   
                        foreach ($formasDePago['formas'] as $key => $value) {
                            echo '<option value="'.$value['idFormapago'].'">('.$value['claveSat'].') '.$value['nombre'].'</option>';
                        }
                    ?>
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Moneda</label>
                    <select class="form-control" id="monedaAbono">
                    <?php   
                        foreach ($moneda as $key => $value) {
                            echo '<option value="'.$value['coin_id'].'">('.$value['codigo'].') '.$value['description'].'</option>';
                        }
                    ?>
                    </select>
                </div>
            </div>
        </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                             <button class="btn btn-primary btn-block" onclick="abona();"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button> 
                        </div>
                    </div>
                </div>


      </div>
    </div>
  </div>
  <!-- fin modal abono -->

  <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
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
        $("#divtable").hide();
        //$('#tipoM, #tipoA, #cliente').select2();
        $('#cliente').select2();
        //$('#tipoM, #tipoA, #cliente').css('height','30px');

        $("#dias").numeric();
        $("#hasta, #desde").datepicker({ 
                    format: "yyyy-mm-dd",
                    "autoclose": true, 
                    language: "es"
                }).attr('readonly','readonly').val('');
        
        $("#desde").val(mesA());
        $("#hasta").val(hoy2());

        $('#clienteAbono').select2({width:'100%'});
    });

    $('#tipoM').change(function()
            {                   
                var tipoM = $("#tipoM").val();
                if(tipoM == 2){
                    $("#divtipoA").show();
                    $("#tipoA").val(1).prop({selected: 'selected'})
                }else{
                    $("#divtipoA").hide();
                    $("#divcli").hide();
                }

            });
    $('#divtipoA').change(function()
            {                   
                var tipoA = $("#tipoA").val();
                if(tipoA == 2){
                    $("#divcli").show();
                }else{                    
                    $("#divcli").hide();
                }

            });
    function reimprimeR(id){
        window.open("../../modulos/pos/ticketRetiro.php?idretiro=" +id);
     }
    function reimprimeA(id){
        window.open("../../modulos/pos/ticketAbono.php?idabono=" +id);
     }
    function cuntasC(){
        window.parent.agregatab("../../modulos/punto_venta/reportes/rcxc.php","Cuentas por Cobrar","",1266);
    }
</script>

