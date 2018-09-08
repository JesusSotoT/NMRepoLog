<style>
	.divider{
		width: 100%;
		border:solid;
	}
</style>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">

	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	<!-- tooltipster -->
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/tooltipster.css">
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-shadow.css" />
	<!-- jqueryui -->
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap-theme.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- jquery Mobile -->
		<!-- <link rel="stylesheet" href="../../libraries/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css"> -->
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

	<!-- gridstack -->
	    <link rel="stylesheet" href="../../libraries/gridstack.js-master/dist/gridstack.css"/>
	    
	<!-- ** Sistema -->
		<link rel="stylesheet" type="text/css" href="css/comandas/comandas.css">
		

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap-3.3.7/js/bootstrap.min.js"></script>	
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- JQuery Mobile -->
		<!-- <script src="../../libraries/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>
	   
	    <script src="views/importar/simpleUpload.js"></script>
	    <script src="js/importar/importar.js"></script>

</head>
<body>

	<div class="container well">
		<h2>Importaciones</h2>
		
		 	<div class="form-group">

		 		<div class="row" style="padding-top: 15px;">
		 			<div class="col-sm-1">
		 				<label for="exampleInputFile">Clientes</label>			    		
		 			</div>
		 			<div class="col-sm-5">	
		 				<div class="input-group">	 				
		 					<label class="input-group-btn">
			                    <span class="btn btn-primary">
			                        Cargar&hellip; <input type="file" name="Filedata" class="form-control-file" id="clientesFile" aria-describedby="fileHelp" style="display: none;">			                        
			                    </span>
			                </label>
			                <input id="inpCli" type="text" class="form-control" readonly>
		 				</div>			    		
		 			</div>
		 			<div class="col-sm-1">		 				
		 				<button id="btnclientes" class="btn btn-primary" onclick="subirxml(1);">save</button>
		 			</div>		 						    		
		 		</div>

		 		<div class="row" style="padding-top: 15px;">
		 			<div class="col-sm-1">
		 				<label>Productos</label>			    		
		 			</div>
		 			<div class="col-sm-5">			    			
						<div class="input-group">
			                <label class="input-group-btn">
			                    <span class="btn btn-primary">
			                        Cargar&hellip; <input type="file" name="Filedata" class="form-control-file" id="productosFile" aria-describedby="fileHelp" style="display: none;">		    				                        
			                    </span>
			                </label>
			                <input id="inpPro" type="text" class="form-control" readonly>
						</div> 
		 			</div>
		 			<div class="col-sm-1">		 				
		 				<button id="btnclientes" class="btn btn-primary" onclick="subirxml(2);">save</button>
		 			</div>
				</div>
				<button class="btn btn-primary" onclick="deleteFiles();">Finalizar</button>
		  	</div>		  		 
		
	</div>
	
</body>
</html>
<script>
 $("#productosFile").change(function(event) {
 	var name = $(this).val(); 	
 	$("#inpPro").val(name);
 });
 $("#clientesFile").change(function(event) {
 	var name = $(this).val(); 	
 	$("#inpCli").val(name);
 });


</script>

