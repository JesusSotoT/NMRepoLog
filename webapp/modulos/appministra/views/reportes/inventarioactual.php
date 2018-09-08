<style>
.rowhide {
    display: none;
}
.rowshow {
    display: none;
}
.sizeprint {
    max-width: 612px;
}
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inventario Actual</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
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
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

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
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Inventario Actual</h3>
        </div>
    </div>
    <div class="row col-md-12" id="divfiltro">                     
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6"> 
                        <!--<label>Rango de Fechas</label><br>
                        <label>Desde:</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Desde">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>-->
                        <label>Hasta el dia:</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        <label>Tipo de Producto</label><br>
                        <select id="tipoProIA" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>  
                            <option value="1" >-Producto-</option>
                            <option value="2" >-Servicio-</option>
                            <option value="3" >-Insumo-</option>
                            <option value="4" >-Insumo Preparado-</option>
                            <option value="5" >-Receta-</option>                 
                        </select>
                        <label>Productos</label><br>
                        <select id="productoIA" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                       
                        </select>
                        <div id="div_consignaFiltros" >
                        <label>Proveedor</label><br>
                        <select id="provedor" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>  
                        </select>   
                        <label>Productos Consigna</label>
                        <select id="consigna" class="form-control">
                            <option value="2">Todos</option>
                            <option value="1">Consigana</option>
                            <option value="0">No consigna</option>                           
                        </select>
                        </div>
                  
                        <!--
                        <label>Departamento</label><br>
                        <select id="departamentoIA" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>                        
                        </select>

                        <div id="divfamilia">
                            <label>Familia</label><br>
                            <select id="familiaIA" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todas-</option>                          
                            </select>
                        </div>
                        <div id="divlinea">
                            <label>Linea</label><br>
                            <select id="lineaIA" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todas-</option>                          
                            </select> 
                        </div>
                        <div id="divcaract">
                            <label>Caracteristicas</label><br>
                            <select id="caracteristicasIA" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todas-</option>                          
                            </select>
                        </div>
                        -->
                    </div>
                    <div class="col-sm-6">
                        <label>Sucursal</label><br>
                        <select id="sucursalIA" class="form-control" style="width: 100%;">
                            <option value="0" selected>-Todos-</option>
                        </select>
                        <label>Almacen</label><br>
                        <select id="almacenIA" class="form-control chosen-select" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todos-</option>
                        </select>
                        <label>Unidades</label><br>
                        <select id="unidadesIA" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todas-</option>
                            <?php 
                                foreach ($unidades as $key => $value) {
                                   echo '<option value="'.$value['id'].'">'.$value['clave'].'</option>';
                                }
                             ?>
                        </select>
                        <!--
                        <label>Unidad</label><br>
                        <select id="unidadIA" class="form-control chosen-select" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todas-</option>
                        </select>
                        <label>Seleccion de Unidad</label><br>
                        <select id="unidadSIA" class="form-control chosen-select" style="width: 100%;" multiple="multiple">
                            <option value="0" selected>-Todas-</option>
                        </select>
                        -->
                        <div class="col-sm-6">
                            <label>Reporte</label><br>
                            
                            <input type="radio" name="rep" id="R1IAUD" value="chais" checked="checked">Unidades Detalle<br>
                            <input type="radio" name="rep" id="R1IAID" value="impD">Importe Detalle<br>
                            <input type="radio" name="rep" id="R1ambosIA" value="ambos">Ambos <br>

                            <label>Tipo</label><br>                    
                            <input type="radio" name="rep2" id="R2det" value="det">Detallado<br>
                            <input type="radio" name="rep2" id="R2rap" value="rap" checked="checked">Rapido<br>

                            <!--
                            <input type="radio" name="rep" id="R1unidadesIA" value="unidades" checked="checked">En Unidades<br>
                            <input type="radio" name="rep" id="R1importeIA" value="importe">En Importe<br>
                            <input type="radio" name="rep" id="R1detalleIA" value="detalle">A detalle(ubicacion) <br>
                            <label>Imprimir productos</label><br>
                            <input type="radio" name="pro" id="R1todosIA" value="unidades" checked="checked">Todos<br>
                            <input type="radio" name="pro" id="R1exisIA" value="importe">Solo en Existencias<br>
                            -->
                        </div>

                        <label>Unidad</label><br>                    
                        <input type="radio" name="unid" id="unidC" value="com">Compra<br>
                        <input type="radio" name="unid" id="unidV" value="ven" checked="checked">Venta<br>
                        <br><br>  
                        <button class="btn btn-default" id="btnprocesarIA" onclick="procesarIA();">Procesar</button>                                                  
                    </div>
                </div>
            </div>    
        </div>
    </div>

  <div class="container" id="divambos" style="overflow:auto" max-width="2480px">
    
    <div class="col-sm-12" id="divunidades">
            <h5>Tipo: <label id="lbtipo">Unidades</label><br>
                Sucursal: <label id="lbsucursal"></label> <br> 
                Almacen: <label id="lbalmacen">-Todos-</label> <br>
                Perdido: <label id="lbperiodo">Sin Rango</label> <br> 
                Producto: <label id="lbproducto">Todos</label></h5>
                <div id = "resultadoU"></div>
        <div id="divtableU">
            <table id="table_unidaIA" class="table table-striped table-bordered sizeprint" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th width="90">Codigo de producto</th>
                    <th>Nombre</th>
                    <th width="30">Unidades</th>
                    <th width="30">Inicial</th>
                    <th width="30">Entradas</th>
                    <th width="30">Salidas</th>
                    <th width="30">Existencia Actual</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
      </div>
    </div>

    <div class="col-sm-12" id="divimporte">
            <h5>Tipo: <label id="lbtipoI">Importe</label><br>
                Sucursal: <label id="lbsucursalI"></label><br> 
                Almacen: <label id="lbalmacenI"></label> <br>
                A la Fecha de : <label id="lbperiodoI">2016-12-31</label> <br>
                Producto: <label id="lbproductoI">Todos</label></h5>
                <div id = "resultadoI"></div>
        <div id ="divtableI">
            <table id="table_ImporteIA" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th width="90">Codigo de producto</th>
                    <th>Nombre</th>
                    <th width="30">Unidades</th>
                    <th width="30">Inicial</th>
                    <th width="30">Entradas</th>
                    <th width="30">Salidas</th>
                    <th width="30">Existencia Actual</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>    
        
    </div>
  </div>

</div>


<!-- Modal para imagen e informacion de producto-->
<div class="modal fade" id="modalIA" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title_conv_edit">Ver Producto</h3>
			</div>

			<div class="modal-body form">
				<form action="#" id="form_convenio_edit" class="form-horizontal">
					<input type="hidden"  id="idproducto"/>
					<div class="form-body">
						<div class="form-group">
							<div class="col-md-4">
								&nbsp; <img id ="imagen" border="0" width="200" height="200">
							</div>

							<div class="col-md-8">
								<div class="row"> 
									<label class="control-label col-md-3">Código</label>
									<input class="col-md-6" type="text" id="codigo">
								</div> <br>

								<div class="row">
									<label class="control-label col-md-3">Nombre</label>
									<input class="col-md-6" type="text" id="nombre">
								</div> <br>

								<div class="row">
									<label class="control-label col-md-3">Tipo Producto</label>
									<input class="col-md-6" type="text" id="tipo">
								</div> <br>

								<div class="row">
									<label class="control-label col-md-3">Unidad</label>
									<input class="col-md-6" type="text" id="unidad">
								</div> <br>

								<div class="row">
									<label class="control-label col-md-3">Moneda</label>
									<input class="col-md-6" type="text" id="moneda">
								</div>
							</div>
						</div>
					</div>
				</form>
	
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>

			</div>

        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

    <div class="container" id="divprintU" style="overflow:auto" max-width="2480px">
        
    </div>
 
<script>

    $(document).ready(function() {

        $("#tipoProIA").change(function(event) {
            var tipo = $("#tipoProIA").val();
            $.ajax({
                    url: 'ajax.php?c=reportes&f=selectProductos',
                    type: 'post',
                    dataType: 'json',
                    data:{tipo:tipo},
            })
            .done(function(data) {
                $('#productoIA').html('');
                $.each(data, function(index, val) {
                      $('#productoIA').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
                });
            })
        });

            $.ajax({
                    url: 'ajax.php?c=reportes&f=selectProves',
                    type: 'post',
                    dataType: 'json',
                    
            })
            .done(function(data) {
                $('#provedor').html('');
                $.each(data, function(index, val) {
                      $('#provedor').append('<option value="'+val.idPrv+'">'+val.razon_social+'</option>');  
                });
            })


        $("#divunidades").hide();
        $("#divimporte").hide();
        $("#divunidades2").hide();
        $("#divimporte2").hide();

        reloadselectIA('IA');
        $("#almacenIA, #productoIA, #tipoProIA, #unidadesIA, #provedor").select2();
        $("#hasta, #desde").datepicker({ 
                    format: "yyyy-mm-dd",
                    "autoclose": true, 
                    language: "es"
                }).attr('readonly','readonly').val('');
        
        $("#desde").val(mesA());
        $("#hasta").val(hoy2());
    });

    
</script>





