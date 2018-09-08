<input type='hidden' id='ejercicio_actual' value="<?php echo $configuracionPeriodos['ejercicio_actual']?>">
<input type='hidden' id='periodo_actual' value="<?php echo $configuracionPeriodos['id_periodo_actual']?>">
<input type='hidden' id='periodos_abiertos' value="<?php echo $configuracionPeriodos['periodos_abiertos']?>">
<input type='hidden' id='primer_ejercicio' value="<?php echo $primer_ejercicio?>">
<input type='hidden' id='ultimo_ejercicio' value="<?php echo $ultimo_ejercicio?>">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='js/bootstrap-datepicker.es.js'></script>

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"> </script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#lay_file").attr("abierto","0").hide();
        $("#producto").select2({'width':'100%'});
        inicializa_movimientos2();
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $("#tipo").val(2).trigger("change")
        $("#caracteristicas,#otrascarac").hide();
        var fechaInicial,fechaFinal,fechaActual;
        fechaActual = $("#ejercicio_actual").val()+'-'+$("#periodo_actual").val()+'-01'
        if(parseInt($("#periodos_abiertos").val()))
        {
            fechaInicial = $("#primer_ejercicio").val()+'-'+'01-01'
            fechaFinal = $("#ultimo_ejercicio").val()+'-'+'12-31'
        }
        else
        {
            fechaInicial = $("#ejercicio_actual").val()+'-'+$("#periodo_actual").val()+'-01'
            fechaFinal = $("#ejercicio_actual").val()+'-'+(parseInt($("#periodo_actual").val())+1)+'-00'
        }

        $('#fecha_pedimento,#fecha_fabricacion,#fecha_caducidad').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

        $('#fecha_mov,#fecha_tras').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es",
                    startDate: fechaInicial,
                    endDate: fechaFinal
            });
        
    });
     
    </script>
    <style>
.row
{
    margin-bottom:20px;
}
.container
{
    margin-top:20px;
}


</style>
<?php
require "views/partial/modal-generico.php";
?>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Ordenes de Traspaso</h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <div id='boton_virtual'><button class='btn btn-primary btn-sm' data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target=".bs-traslado-modal-lg" onclick='nuevo_traslado()'>Nuevo Traspaso<span class='glyphicon glyphicon-plus'></span></button>
            
            </div>
            <table id="tabla-data" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr><th># de Traspaso</th><th>Fecha</th><th>Origen</th><th>Destino</th><th>Solicitante</th><th>Referencia</th><th>Accion</th></tr>
                </thead>
                <tbody id='trs'>
                </tbody>
            </table>
        </div>
    </div>            
</div>
<!-- <script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script> -->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>

<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/inventarios.js'></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">

<!--AQUI ESTAN LOS MODALS-->

<div id='modal-traslado' principal-scroll='1' class="modal fade bs-traslado-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabelTraslado">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label">Nuevo Traspaso</h4>
            </div>
      <div class="modal-body well">
      <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Numero de Traspaso:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <label id='num_traslado'></label>
                    <input type='hidden' id='id_traslado' class='form-control'>
                    <input type='hidden' id='nuevo_modificar' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Almacen Origen:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                     <select id='almacen_origen_final' class='form-control'>
                        <option value='0'>Ninguno</option>
                        <?php
                          $nombre_anterior = ''; 
                          $codigo_sistema_anterior = 'z';
                          
                        while($l = $listaAlmacenes->fetch_assoc())
                        {
                            $num = substr_count($l['codigo_sistema'], '.');
                            $vacio = "";
                            for($i=1;$i<=$num;$i++)
                                $vacio .= "|&nbsp;&nbsp;&nbsp;";
                            
                                
                            $select .= "<option value='".$l['id']."'>$vacio".$l['nombre']."</option>";
                            
                        }    
                        echo $select;
                        ?>
                    </select>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Almacen Destino:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <select id='almacen_destino_traslado' class='form-control'>
                        <option value='0'>Ninguno</option>
                        <?php
                          $nombre_anterior = ''; 
                          $codigo_sistema_anterior = 'z';
                          
                        while($l = $listaAlmacenes->fetch_assoc())
                        {
                            $num = substr_count($l['codigo_sistema'], '.');
                            $vacio = "";
                            for($i=1;$i<=$num;$i++)
                                $vacio .= "|&nbsp;&nbsp;&nbsp;";
                            
                                
                            $select .= "<option value='".$l['id']."'>$vacio".$l['nombre']."</option>";
                            
                        }    
                        echo $select;
                        ?>
                    </select>
                    <input type='hidden' id='almacen_destino' value='<?php echo $idAlmacenTransito; ?>'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Fecha:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='fecha_tras' class='form-control' value=''>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Referencia:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='referencia_tras' class='form-control'>
                </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-md-offset-1 col-md-4">
                        <button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-md" onclick='nuevo_movimiento()'>Agregar Producto</button>
            </div>
        </div>
        <div class="row" style='margin-top:5px;'>
            <div class="col-xs-7 col-md-offset-1 col-md-11">
                            <table id='mov_productos'>
                            </table>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick='guardar_traslado()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_traslado()' id='cancelar_tras'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>
<!--XXXXXXXX-->
<div id='modal-principal' principal-scroll='1' class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div id='blanco' class='well' style='width:598px;height:100%;z-index:1;position:absolute;color:green;margin-top:180px;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label">Agregar Producto</h4>
            </div>
      <div class="modal-body well">
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                <input type='hidden' id='sinexistencias' value='<?php echo $salidasSinExistencia; ?>'>
                    
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                <input type='hidden' id='tipo' value='2'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Producto:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <select id='producto' onchange="inv(1)">
                        <option value='0'>Ninguno</option>
                        <?php
                        while($l = $listaProductos->fetch_assoc())
                        {
                            $id_costeo = $l['id_tipo_costeo'];
                            if(!$id_costeo)
                                $id_costeo = 0;
                            echo "<option value='".$l['id']."' unidad='".$l['unidad']."' moneda='".$l['moneda']."' id_costeo='".$id_costeo."'>( ".$l['codigo']." ) ".$l['nombre']."</option>";
                        }
                        ?>
                    </select>
                </div>
        </div>
        <div class="row" id='caracteristicas'>
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Caracteristicas:</b>
                </div>
                <div class="col-xs-4 col-md-7 input-group" id='listaCaracteristicas'></div>
        </div>
    
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Cantidad:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='cantidad' class='form-control' onchange='disponibilidad();valcoma(this);costo(this)' onkeypress="return NumCheck(event, this)"><span class="input-group-addon" id='unidad'></span>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Importe:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='importe' class='form-control' onchange='valcoma(this);costo(this)' onkeypress="return NumCheck(event, this)"><span class="input-group-addon" id='moneda'></span>
                </div>
        </div>
        <div class="row" id='otrascarac'>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Almacen Origen:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <select id='almacen_origen' class='form-control' onchange='disponibilidad()'>
                        <option value='0'>Ninguno</option>
                    </select>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Costo Unitario:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='hidden' id='almacen_destino_final'>
                    <input type='hidden' id='almacen_destino' value='<?php echo $idAlmacenTransito; ?>'>
                    <input type='text' id='costo' class='form-control' onchange='valcoma(this);costo(this)' onkeypress="return NumCheck(event, this)">
                    <input type='hidden' id='fecha_mov' class='form-control' value=''>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Referencia:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='referencia' class='form-control'>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick='guardar_movimiento_traslado()'>Agregar</button><button class='btn btn-default btn-sm' onclick='cancelar_movimiento()'>Cerrar</button>
            </div>      
    </div>
  </div>
</div>
<a id='printer' style='width:10px;color:white;' >.</a>