<script src="../../libraries/jquery.min.js"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

<style>
.row
{
	margin-bottom:20px;
}
.container
{
	margin-top:20px;
}
th, td {
	text-align: center;
	vertical-align: middle;
}
</style>

<input type='hidden' id='pestania_prod' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
	<div class="row">
		<div class="col-xs-12 col-md-12"><h3>Lista de Alumnos, Profesores y Grupos.</h3></div>        
	</div>
	<div class="row">
	<div class="col-xs-12 col-md-3"><b>Selecciona la universidad</b></div>
		<div class="col-xs-12 col-md-3">
		  <select id='universidad' onchange='cargadatos()' class='form-control'>
			<?php 
			echo "<option value='0'>Ninguna</option>";
			while($u = $universidades->fetch_object())
			  echo "<option value='$u->id'>".$u->rubro."</option>";
			 ?>
		  </select>
		</div>
	</div>
	<div class="row">
	   <!-- Nav tabs -->
	  <ul id='myTabs' class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#profesores" aria-controls="profesores" role="tab" data-toggle="tab">Profesores</a></li>
		<li role="presentation"><a href="#alumnos" aria-controls="alumnos" role="tab" data-toggle="tab">Alumnos</a></li>
		<li role="presentation"><a href="#grupos" aria-controls="grupos" role="tab" data-toggle="tab">Grupos</a></li>
		<li role="presentation"><a href="#relaciones" aria-controls="relaciones" role="tab" data-toggle="tab">Relaciones</a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="profesores">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title">Listado de Profesores.</h3>
			  </div>
			  <div class="panel-body">
				
			  <div class="col-xs-12 col-md-12 table-responsive">
				  
				  <table id="tabla-prof" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
					  <thead>
						  <tr>
						  	<th>Id</th>
						  	<th>Razon Social</th>
						  	<th>Nombre</th>
						  	<th>Correo</th>
						  	<th>Telefono</th>
						  	<th>Giro</th>
						  	<th>Instancia</th>
						  	<th>Usuario Master</th>
						  	<th>Password Master</th>
						  	<th></th>
						  </tr>
					  </thead>
					  <tbody id='trs_prof'></tbody>
				  </table>
			  </div>

			  </div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="grupos">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title">Listado de Grupos.</h3>
			  </div>
			  <div class="panel-body">
				  <div class="col-xs-12 col-md-12 table-responsive">
					<div id='boton_virtual2'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-grupo-modal-sm" onclick="nuevo_grupo()">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
					<table id="tabla-grup" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Descripcion</th>
								<th>Modificar</th>
								<th>Eliminar</th>
							</tr>
						</thead>
						<tbody id='trs_grup'></tbody>
					</table>
				</div>
			  </div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="alumnos">
			 <div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title">Listado de Alumnos.</h3>
			  </div>
			  <div class="panel-body"> 
					<div class="col-xs-12 col-md-12 table-responsive">
					  
					  <table id="tabla-alum" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						  <thead>
							  <tr>
							  	<th>Id</th>
							  	<th>Razon Social</th>
							  	<th>Nombre</th>
							  	<th>Correo</th>
							  	<th>Telefono</th>
							  	<th>Giro</th>
							  	<th>Instancia</th>
							  	<th>Usuario Master</th>
							  	<th>Password Master</th>
							  	<th>Profesor</th>
						  	</tr>
						  </thead>
						  <tbody id='trs_alum'></tbody>
					  </table>
				  </div>
			  </div>
			</div>
		</div>
		 <div role="tabpanel" class="tab-pane fade" id="relaciones">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title">Listado de Relaciones.</h3>
			  </div>
			  <div class="panel-body">
				  <div class="row">
						<div class='col-xs-12 col-md-3'>
							<label for="grups_rel">Selecciona un grupo:</label>
						  <select id='grupos_rel' onchange='busca_relaciones()' class='form-control'></select>
						</div>
						<div id='boton_virtual3' class='col-xs-12 col-md-3'>
							<br>
							<button class='btn btn-primary' data-toggle="modal" data-target=".bs-relaciones-modal-sm" onclick="nueva_relacion(0)">
								Nuevo <span class='glyphicon glyphicon-plus'></span>
							</button>
						</div>
				  </div>
				  <div class="row">
					  <div class="col-xs-12 col-md-12 table-responsive">
							<table id="tabla-rel" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Id</th>
										<th>Profesor</th>
										<th>Alumno</th>
										<th>Grupo</th>
										<th><input type="checkbox" id="checkAll"></th>
									</tr>
								</thead>
								<tbody id='trs_rel'></tbody>
							</table>
						</div>
				  </div>
				  <div class="row">
				  	<div class="col-xs-12 col-md-3 col-md-offset-9">
							<button id="eliminar" class="btn btn-danger btn-block" onclick="eliminarSeleccionados()">
								Eliminar Seleccionados
							</button>
				  	</div>
				  </div>
			  </div>
			</div>
		</div>
	  </div>
	</div>
</div>

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<!--Button Print css -->
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<!--<script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script>-->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/panel.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-grupo-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
	<div class="modal-content">
	  <div id='blanco' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
	  <div class="modal-header panel-heading">
				<h4 id="modal-label">Nuevo Grupo</h4>
			</div>
	  <div class="modal-body">
		<div class="row">
				<div class="col-xs-4 col-md-6">
					<input type='hidden' style='width:150px;' id='id_grupo' class='form-control'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-4">
					<b id='nombre_label'>Nombre:</b>
				</div>
				<div class="col-xs-4 col-md-6">
					<input type='text' style='width:150px;' id='nombre_grupo' class='form-control'>
				</div>
		</div>
	  </div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' id='guardar' onclick='guarda_grupo()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_grupo()'>Cancelar</button>
			</div>      
	</div>
  </div>
</div>

<div class="modal fade bs-relaciones-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div id='blanco2' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
		  <div class="modal-header panel-heading">
					<h4 id="modal-label">Nueva Relacion</h4>
				</div>
		  <div class="modal-body">
			<div class="row">
					<div class="col-xs-4 col-md-6">
						<input type='hidden' style='width:150px;' id='id_rel' class='form-control'>
					</div>
			</div>
			<div class="row">
					<div class="col-xs-4 col-md-4">
						<b id='nombre_label'>Profesor:</b>
					</div>
					<div class="col-xs-4 col-md-6">
						<input type='text' id='id_profe' onchange='datos_user(1)'>
						<span id='mensajes_profe'></span>
					</div>
			</div>
			<div class="row">
					<div class="col-xs-4 col-md-4">
						<b id='nombre_label'>Alumno:</b>
					</div>
					<div class="col-xs-4 col-md-6">
						<input type='text' id='id_alumno' onchange='datos_user(0)'>
						<span id='mensajes_alumno'></span>
					</div>
			</div>
		  </div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' id='guardar_rel' onclick='guarda_rel()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_rel()'>Cancelar</button>
			</div>      
		</div>
  </div>
</div>

<div class="modal fade" id="enviar_mail_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header panel-heading">
				<h4 id="modal-label-correo">Enviar correo</h4>
			</div>
		  <div class="modal-body">
		  	<div>
		  		<label for="asunto_correo">Asunto:</label>
					<input type='text' id='asunto_correo' class='form-control'>
				</div>
				<br>
		  	<div>
		  		<label for="mensaje_correo">Mensaje:</label>
					<textarea type='text' id='mensaje_correo' class='form-control'></textarea>
				</div>
		  </div>
			<div class="modal-footer">
				<button class='btn btn-primary btn-sm' id='enviar_correo' onclick='enviar_email()'>Enviar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>      
		</div>
  </div>
</div>