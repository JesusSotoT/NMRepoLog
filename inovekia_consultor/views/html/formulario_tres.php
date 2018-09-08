<!DOCTYPE html>
<html>
<head>
	<title>SESIÓN 2</title>
	<link href="../../libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<style type="text/css">
		.row {
			margin-bottom: 1em;
			margin-top: 1em;
		}

		.btn {
			width: 100% !important;
			padding: 1em;
		}
	</style>
</head>
<body>
	<form id="formulariotres">
		<!--Periodo-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				        		<label>PERIODO</label>  
				                <div class="row">
					            	<div class="col-sm-6 col-md-6">
						               <label>INICIO</label>
						               <div class="input-group date">
		                					<input type="text" class="form-control requerido" id="f3p1a" name="f3p1a"/>
		            						<span class="input-group-addon">
		                						<span class="glyphicon glyphicon-calendar"></span>
		            						</span>
		            					</div>
						            </div>
						            <div class="col-sm-6 col-md-6">
						               <label>FIN</label>
						               <div class="input-group date">
		                					<input type="text" class="form-control requerido" id="f3p1b" name="f3p1b"/>
		            						<span class="input-group-addon">
		                						<span class="glyphicon glyphicon-calendar"></span>
		            						</span>
		            					</div> 
						            </div>
						        </div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

	    <!-- Ventas-->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>VENTAS</label>  
				                <input type="text" class="form-control requerido" id="f3p2a" name="f3p2a" placeholder="Comentarios"/>
			                </div>
			            </div>
					</div>		
	        	</div>
	    	</div>
		</div>      

	    <!-- Numero de empleos-->
	    <div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>NUMERO DE EMPLEADOS</label>   
				                <input type="text" class="form-control requerido" id="f3p3a" name="f3p3a" placeholder="Comentarios"/>
			                </div>
			            </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!-- Nomina-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>NOMINA</label> 
				                <input type="text" class="form-control requerido" id="f3p4a" name="f3p4a" placeholder="Comentarios"/>
			                </div>
			            </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		  
		<!-- Indicadores de productividad-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>INDICADORES DE PRODUCTIVIDAD</label> 
				                <input type="text" class="form-control requerido" id="f3p5a" name="f3p5a" placeholder="Comentarios"/>
			                </div>
			            </div>
					</div>		
	        	</div>
	    	</div>
		</div>
	
		<!-- ENCUESTA DE SATISFACIÓN DEL CONSULTOR Y DEL CURSO-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>ENCUESTA DE SATISFACCION DEL CONSULTOR Y DEL CURSO</label> 
				                <input type="text" class="form-control requerido" id="f3p6a" name="f3p6a" placeholder="Comentarios"/>
			                </div>
			            </div>
					</div>		
	        	</div>
	    	</div>
		</div>
	</form>
	<div class="panel panel-default">
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3" align="center">
					<button type="submit" class="btn btn-primary" id="btn-enviar">ENVIAR</button>
				</div>
			</div>
		</div>
	</div>
   
   	<script src="../../libraries/jquery/js/jquery.min.js"></script>
   	<script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>
   	<script src="../../libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
   	<script src="../../libraries/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js"></script>
   	<script src="../general.js"></script>
   	<script src="../../views/java_script/formulario_tres.js"></script>

</body>
</html>