<style>
    th { font-size: 14px; }
    td { font-size: 13px; }
</style>
<!DOCTYPE html>
<html lang="">
	<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte Reorden</title>
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/reorden.js"></script>
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
           <h3>REPORTE DE PUNTO DE REORDEN </h3>
        </div>
    </div>
    <div>

        <div class="panel panel-default" id="divfiltro">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <div>
                                <label>Rango para promedio de consumo diario</label><br>    


                        <div class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Desde">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span> 
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>                       
                                                            
                            </div>
                            <label>Tipo de Producto</label><br>
                            <select id="tipo" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>Todos</option>  
                                <option value="1" >Producto</option>
                                <option value="3" >Insumo</option>
                                <option value="4" >Insumo Preparado</option>                        
                            </select>
                        </div>
                        <div>
                            <label>Productos</label><br>
                            <select id="producto" class="form-control" style="width: 100%;" multiple="none">
                                <option value="0" selected="selected">Todos</option> 
                                <?php 
                                    foreach ($productos as $k => $v) {
                                        echo "<option value=".$v['id'].">".$v['nombre']."</option> ";
                                    }
                                 ?>                       
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div>
                            <label>Sucursal</label><br>
                            <select id="suc" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected="selected">-Todas-</option>
                                <?php 
                                    foreach ($sucursales as $k => $v) {
                                        echo "<option value=".$v['idSuc'].">".$v['nombre']."</option> ";
                                    }
                                 ?>                             
                            </select>
                        </div>
                        <div>
                            <label>Días de entrega</label><br>                            
                                <input class="form-control" type="text" id="dias" title="El punto de reorden es calculado con base al tiempo de entrega de tu proveedor.">                        
                        </div>
                        <br>
                        <button type="button" class="btn btn-primary" onclick="generar();">Generar</button>
                    </div>
                </div>
            </div>  
            <div id="divtable" class="panel-body">
            </div>  
        </div>

    </div>
                         
        
    

	</body>
</html>
<script>
    $(document).ready(function() {
    	$('#producto, #tipo, #suc').select2();
    	$("#dias").numeric();
    	$("#hasta, #desde").datepicker({ 
                    format: "yyyy-mm-dd",
                    "autoclose": true, 
                    language: "es"
                }).attr('readonly','readonly').val('');
        
        $("#desde").val(mesA());
        $("#hasta").val(hoy2());
   	});

    $('#tipo').change(function()
            { 
                $('#producto').html('');
                $('#producto').append('<option selected="selected" value="0">-Todos-</option>');
                tipo = $('#tipo').val();
                    $.ajax({ 
                        data : {tipo:tipo},
                        url: 'ajax.php?c=reorden&f=productos',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {                    
                        $.each(data, function(index, val) {
                              $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });
                    }) 
                    
                    
                
            });
</script>