
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
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

 
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!--Button Print css
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
     Button Print js
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script> -->


<body> 
<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Filtro</h3>
        </div>
    </div>
    <div class="row col-md-12">                     
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                        
                        <label>Tipo de Producto</label><br>
                        <select id="tipoPro" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected="selected">-Todos-</option>                         
                            <option value="1" >-Producto-</option>
                            <option value="2" >-Servicio-</option>
                            <option value="3" >-Insumo-</option>
                            <option value="4" >-Insumo Preparado-</option>
                            <option value="5" >-Receta-</option>
                            <option value="6" >-Kit-</option>
                        </select><br>

                        <div class="col-sm-6">
                            <label>Lotes</label><br>
                            <input type="radio" name="Rlotes" id="Rlotes3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rlotes" id="Rlotes1" value="1">SI<br>
                            <input type="radio" name="Rlotes" id="Rlotes0" value="0">NO
                        </div>
                        <div class="col-sm-6">
                            <label>Series</label><br>
                            <input type="radio" name="Rseries" id="Rseries3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rseries" id="Rseries1" value="1">SI<br>
                            <input type="radio" name="Rseries" id="Rseries0" value="0">NO
                        </div>

                    </div>
                    <div class="col-sm-3">
                        
                        <label>Productos</label><br>
                        <select id="productoS" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected="selected">-Todos-</option>                         
                        </select><br>
                        
                        <div class="col-sm-6">
                            <label>Pedimentos</label><br>
                            <input type="radio" name="Rpedi" id="Rpedi3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rpedi" id="Rpedi1" value="1">SI<br>
                            <input type="radio" name="Rpedi" id="Rpedi0" value="0">NO
                        </div>
                        <div class="col-sm-6">
                            <label>Caracteristicas</label><br>
                            <input type="radio" name="Rcarac" id="Rcarac3" value="3" checked="checked">TODAS<br>
                            <input type="radio" name="Rcarac" id="Rcarac1" value="1">SI<br>
                            <input type="radio" name="Rcarac" id="Rcarac0" value="0">NO
                        </div>

                    </div>
                    <div class="col-sm-3">

                        <label>Unidad</label><br>
                        <select id="unidadS" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected="selected">-Todos-</option>                        
                        </select><br>

                        <div class="col-sm-6">
                            <label>Activos</label><br>
                            <input type="radio" name="Ract" id="Ract3" value="3">TODOS<br>
                            <input type="radio" name="Ract" id="Ract1" value="1" checked="checked">Activos<br>
                            <input type="radio" name="Ract" id="Ract0" value="0">Inactivos
                        </div>
  
                    </div>
                    <div class="col-sm-3">

                     <label>Moneda</label><br>
                        <select id="monedaS" class="form-control" style="width: 100%;" multiple="multiple">
                            <option value="0" selected="selected">-Todos-</option>                        
                        </select>
                        <br><br>
                        <div class="col-sm-6">
                            <button class="btn btn-default" onclick="reloadtableCP();">Procesar</button>
                        </div>   
                    </div>
                    <div>
                        
                    </div>
                        
                </div><br>
            </div>    
        </div>
    </div>
</div>
<div class="container">
  <div id="divcp" class="container" style="overflow:auto">
    <h3>Catalogo de Productos</h3>
    <br>
    <div id="divp"></div>
  </div>

    <div id="divcs" class="container" style="overflow:auto">
    <h3>Catalogo de Servicios</h3>
    <br>
  <div id="divs"></div>

  </div>
</div>
     
</body>
</html>


  <!-- Modal para Modificar Convenio-->
  <div class="modal fade" id="modal_form_conv_edit" role="dialog">
  <div class="modal-dialog">
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
             
              <div class="col-md-2">
                <img id ="imagen" src="../pos/images/productos/perrier.jpg" border="0" width="100" height="100">
              </div>

              <div class="col-md-10">
                  <label class="control-label col-md-4">Codigo</label>
                  <div class="col-md-8">
                    <input class="col-md-12" type="text" id="codigo">
                  </div>
                  <label class="control-label col-md-4">Nombre</label>
                  <div class="col-md-8">
                    <input class="col-md-12" type="text" id="nombre">
                  </div>
                  <label class="control-label col-md-4">Tipo Producto</label>
                  <div class="col-md-8">
                    <input class="col-md-12" type="text" id="tipo">
                  </div>
                  <label class="control-label col-md-4">Unidad</label>
                  <div class="col-md-8">
                    <input class="col-md-12" type="text" id="unidad">
                  </div>
                  <label class="control-label col-md-4">Moneda</label>
                  <div class="col-md-8">
                    <input class="col-md-12" type="text" id="moneda">
                  </div>
                  
              </div>
              <div class="col-md-12">
                <div id = "divcarac">
                    <label class="control-label col-md-2">Caracteristicas</label>
                    <div class="col-md-10">
                      <textarea class="col-md-12" cols="30" rows="4" id="caract"></textarea>
                    </div>
                  </div>
                  <div id="divlotes">
                    <label class="control-label col-md-2">Lotes</label>
                    <div class="col-md-10">
                      <textarea class="col-md-12" cols="30" rows="4" id="lotes"></textarea>
                    </div>
                  </div>
                  <div id="divseries">
                    <label class="control-label col-md-2">Series</label>
                    <div class="col-md-10">
                      <textarea class="col-md-12" cols="30" rows="4" id="series"></textarea>
                    </div>
                  </div>
                  <div id="divpedimen">
                    <label class="control-label col-md-2">Pedimentos</label>
                    <div class="col-md-10">
                      <textarea class="col-md-12" cols="30" rows="4" id="pedimen"></textarea>
                    </div>
                  </div>
              </div>

            </div>

          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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



<script>
   $(document).ready(function() {
        $("#tipoPro").change(function(event) {
            var tipo = $("#tipoPro").val();
            $.ajax({
                    url: 'ajax.php?c=reportes&f=selectProductos',
                    type: 'post',
                    dataType: 'json',
                    data:{tipo:tipo},
            })
            .done(function(data) {
                $('#productoS').html('');
                $('#productoS').append('<option value="0">-Todos-</option>');
                $.each(data, function(index, val) {
                      $('#productoS').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
                });
            }) 
        });
        reloadselectCP();
        //reloadtableCP();
        $("#divcp, #divcs").hide();
        $('#tipoPro,#productoS, #unidadS, #monedaS').select2();

   });
   </script>


