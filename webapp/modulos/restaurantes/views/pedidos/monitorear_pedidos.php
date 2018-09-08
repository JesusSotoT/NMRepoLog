<!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Monitorear pedidos</title>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	<!-- jqueryui -->
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap-theme.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->

<!-- **	//////////////////////////- -				JS 						--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap-3.3.7/js/bootstrap.min.js"></script>	
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>
	
	<!-- Sistema -->
        <script type="text/javascript" src="js/pedidos/pedidos.js"></script>
        <script type="text/javascript" src="js/comandas/comandera.js"></script>
        
<!-- **	//////////////////////////- -			FIN JS 						--///////////////////// **-->
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Comandas activas</h3>
            </div>
            <div class="panel-body" id="div_comandas">
                <!-- En esta div se cargan las coamandas -->
            </div>
        </div>
    </body>
</html>
<script>
// Consulta las comandas al iniciar
	pedidos.listar_comandas_activas({
		div : 'div_comandas'
	});

// Consulta las comandas cada 15 segundos
	setInterval(function() {
		pedidos.listar_comandas_activas({
			div : 'div_comandas'
		});
	}, 15000); 
</script>