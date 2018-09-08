<style>
tfoot, thead {
  background-color: #B6B6B6;
  color: #000000;
  font-size: 100%;
  font-weight: bold;
}

.trf {
  /*
  background-color: #B6B6B6 !important;
  border-style:solid !important;
  border-color:black !important;
  border: 1px solid black !important;
  */
  height: 40px !important;
}
.trh{
  /*background-color: #E4E4E4 !important;*/
  border-style:solid !important;
  border-color:black !important;
  border-color:black !important;
  border: 1px solid black !important;
}
.trn {
  background-color: #FFFFFF !important;
}
.trhead{
font-weight: bold !important;
}
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Movimientos Inventario</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">

    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>

    <script src="js/reportes.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!--<script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

<body> 
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Movimientos Inventario</h3>
        </div>
    </div>
    <div class="row col-md-12" id="divfiltro">                     
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6"> 
                        <label>Rango de Fechas</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Desde">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        <label>Productos</label><br>
                        <select id="productoMI" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        <!--
                        <label>Departamento</label><br>
                        <select id="departamentoMI" class="form-control" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        <label>Familia</label><br>
                        <select id="productoMI" class="form-control" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        <label>Linea</label><br>
                        <select id="productoMI" class="form-control" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        <label>Caracteristicas</label><br>
                        <select id="productoMI" class="form-control" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        <label>Tipo de Movimiento</label><br>
                        <select id="productoMI" class="form-control" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select><br>
                        -->
                    </div>
                    
                    <div class="col-sm-6">
                    <label>Sucursal</label><br>
                        <select id="sucursalMI" class="form-control" style="width: 100%;">
                            <option value="0" selected>-Todos-</option>
                        </select>
                    <label>Almacen</label><br>
                        <select id="almacenMI" class="form-control chosen-select" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>
                        </select>
                    <!--
                    <div class="col-sm-6">
                        <label>Reporte</label><br>
                        <input type="radio" name="rep" id="R1unidadesIA" value="unidades" checked="checked">En Unidades<br>
                        <input type="radio" name="rep" id="R1importeIA" value="importe">En Importe<br>
                        <input type="radio" name="rep" id="R1ambosIA" value="ambos">Ambos <br>
                    </div>
                    -->
                    <button class="btn btn-default" id="btnprocesarIA" onclick="procesarMI();">Procesar</button> 
                                     
                    </div>
                </div>
            </div>    
        </div>
    </div>
   
<div class="container">
      <div class="container" id="divreporte" style="overflow:auto">
        <div class="col-sm-12">
        <h5>Movimientos de Inventario<br> Sucursal: <label id="lbsucursal"></label><br> Almacen: <label id="lbalmacen"></label> <br> Periodo: <label id="lbperiodo"></label></h5>
            <table id="table_mov" class="display compact" cellspacing="0" width="90%">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Folio</th>
                <th>Concepto</th>
                <th>Cliente /<br> Proveedor</th>
                <th>Cantidad</th>
                <th>Costo Unitario</th>
                <th>Importe <br> Total Compra</th>
                <th>$ Venta</th>
                <th>Importe <br>Total Venta</th>
                <th>.</th>
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

<script>
    $(document).ready(function() {

        reloadselectMI();
        $("#divreporte").hide();
        $("#sucursalMI, #almacenMI, #productoMI, #departamentoMI").select2();

        /*
            $("#productoMI").select2({data: function() {
                return {
                    results:data};
                    alert(1);
                }
            });
        */

        $("#hasta, #desde").datepicker({ 
                format: "yyyy-mm-dd",
                "autoclose": true, 
                language: "es"
            }).attr('readonly','readonly').val('');

        $("#desde").val(mesA());
        $("#hasta").val(hoy2());

    });    
</script>