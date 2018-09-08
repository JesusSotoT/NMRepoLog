
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script>
<?php 
	require 'js/almacenes.js.php';
?>
</script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<link rel="stylesheet" href="css/sucursal.css">
<style>
	.btn-container{
		padding-left: 1em;
	}
	#arbol-container{
		padding: 0;
		overflow:scroll; 
		border:1px solid #ccc;
		height: 32em;
	}
	#mostrar-inactivas-container{
		top:.5em;
	}
	#srch{
		padding-left: none;
	}
	.row {
		margin-bottom:.5em;
	}
	hr {
		margin-bottom:.5em;
	}
	.input-group > .glyphicon-search{
		top: 0 !important;
		border-radius: 0 4px 4px 0 !important;
	}
	@media screen and (min-width:780px) {
		#moviles,#moviles2{display:none;}
	}
	@media screen and (max-width:1019px){
		#consignacion-container{
			top: 2.1em;
		}
		#mostrar-inactivas-container{
			top:-.5em;
		}
		.content-wrapper{
			padding: .5em;
		}

	}
</style>
<div class="col s12" id="title-container">
  <h5>Capture y edite sus almacenes</h5>
</div>

<div class="content-wrapper">
	<!--INICIA Solo visible para moviles-->
	<div class='row' id='moviles'>
		<div class='col-xs-12 col-md-1 col-md-offset-5'>
			<a class="btn btn-primary" href="#captura" id='abrir' role="button">Nuevo</a>
		</div>
	</div>
	<!--TERMINA Solo visible para moviles-->
	<!-- Contenido -->
	<div class="row margin-sides-0">
		<!-- Lado arbol -->
		<div class="col-xs-12 col-md-4 col-md-offset-1">
			<div class="row">
				<!-- Titulo arbol -->
				<div class="col-xs-12">
					<h5>Mis Almacenes</h5>
					<hr>
				</div> <!-- // titulo arbol -->
			</div>	
			<!-- Acciones arbol -->
			<div class="row">
				<!-- Mostrar inactivas -->
				<div class='col-xs-6' id="mostrar-inactivas-container">
					<input type='checkbox' id='display' onclick='inactivas()'> Mostrar Inactivas
				</div> <!-- // Mostrar inactivas -->

				<!-- Buscar en arbol -->
				<div id='srch' class='col-xs-6'>
					<div class='input-group'>
						<input type="text" class='form-control' id='search'>
						<span class="input-group-addon glyphicon glyphicon-search"></span>
					</div>
				</div> <!-- // Buscar en arbol -->
			</div> <!-- // Acciones arbol -->	

			<!-- Arbol -->
			<div class='col-xs-12' id="arbol-container">
				<div id='cont'>
					<ul></ul>
				</div>
			</div> <!-- // Arbol -->
		</div> <!-- // Lado Arbol -->

		<!-- Lado captura -->
		<div class="col-xs-12 col-md-6">
			<!-- Titulo -->
			<div class="row">
				<div class="col-xs-12">
					<h5>Creación y edición de almacenes</h5>
					<hr>
				</div>
			</div> <!-- // Titulo -->

			<!-- Formulario -->
			<div class="row">
				<!-- Formulario col -->
				<!-- Guardamos el id del almacen -->
				<input type='hidden' id='idalmacen'>
				<div class="col-xs-12">
					<!-- Clave -->
					<div class="form-group col-md-3 col-xs-12">
						<label for="clave">Clave:</label><span class="required"> *</span>
						<input type='text' id='clave' class='form-control'>
					</div>
					<!-- Nombre -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="nombre">Nombre:</label><span class="required"> *</span>
						<input type='text' id='nombre' class='form-control'>
					</div>
					<!-- Tipo -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="tipo">Tipo:</label><br>
						<select id='tipo' class='form-control' onchange='tipo()'>
							<?php while($t = $tipos->fetch_assoc()) {
								echo "<option value='".$t['id']."'>".$t['nombre']."</option>";
							} ?>
						</select>
					</div>
					<!-- Depende de -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="depende">Depende de:</label>
						<select id='depende' class='form-control' onchange='depende()'>
							<option value='0'>Ninguno</option>
							<?php while($p = $padres->fetch_assoc()){
								echo "<option tipo='".$p['id_almacen_tipo']."' value='".$p['id']."'>(".$p['codigo_manual'].") ".$p['nombre']."</option>";
							} ?>
						</select>
					</div>
					<!-- Sucursal -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="sucursal">Sucursal:</label>
						<select id='sucursal' class='form-control'>
							<?php while($s = $sucursales->fetch_assoc()) {
								echo "<option value='".$s['idSuc']."'>".$s['nombre']."</option>";
							} ?>
						</select>
					</div>
					<!-- Estado -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="estado">Estado:</label>
						<select id="estado" class='form-control' onchange='estado()'>
							<option value='0'>Ninguno</option>
							<?php while($e = $estados->fetch_assoc()) {
								echo "<option value='".$e['idestado']."'> ".$e['estado']."</option>";
							} ?>
						</select>
					</div>
					<!-- Municipio -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="municipio">Municipio:</label>
						<input type='hidden' id='muni' value='0'>
						<select id="municipio" class='form-control'>
							<option value='0'>Ninguno</option>
						 <?php  while($m = $municipios->fetch_assoc()) {
								   echo "<option value='".$m['idmunicipio']."'> ".$m['municipio']."</option>";
								} ?>        
						</select>
					</div>
					<!-- Dirección -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="nombre">Dirección:</label>
						<input type='text' id='direccion' class='form-control'>
					</div>
					<!-- Encargado -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="encargado">Encargado:</label>
						<select id="encargado" class='form-control'>
							<option value='0'>Ninguno</option>
							<?php while($e = $empleados->fetch_assoc()) {
								echo "<option value='".$e['idEmpleado']."'>(".$e['codigo'].") ".$e['nombreEmpleado']." ".$e['apellidoPaterno']."</option>";
							} ?>
						</select>
					</div>
					<!-- Clasificador -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="clasificador">Clasificador:</label>
						<select id="clasificador" class='form-control'>
							<option value='0'>Ninguno</option>
							<?php while($c = $clasificadores->fetch_assoc()) {
								echo "<option value='".$c['id']."'>(".$c['clave'].") ".$c['nombre']."</option>";
							} ?>     
						</select>
					</div>	
					<!-- Teléfono -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="telefono">Teléfono:</label>
						<input type='text' id='telefono' class='form-control'>
					</div>
					<!-- Ext -->
					<div class="form-group col-md-3 col-xs-6">
						<label for="ext">Ext:</label>
						<input type='text' id='ext' class='form-control'>
					</div>
					<!-- Es consignación -->
					<div class="form-check col-md-3 col-xs-6" id="consignacion-container">
						<label class="form-check-label" for="consignacion">¿Consignación?</label>
						<input type='checkbox' id='consignacion' class='form-check-input'>
					</div>
					<!-- Status -->
					<div class="form-group col-md-6 col-xs-12">
						<label for="status">Status:</label>
						<select id='status' class='form-control'>
							<option value='1'>Activo</option>
							<option value='0'>Inactivo</option>
						</select>
					</div>
				</div> <!-- // Formulario col -->
			</div> <!-- // Formulario -->
			
			<!-- Botones -->
			<div class="row btn-container">
				<div class="form-group col-md-3 col-md-offset-6 col-xs-12">
					<button id='guardar' onclick='guardar()' class='btn btn-default btn-block'>
						Guardar <i class="fa fa-camera-retro fa-lg"></i>
					</button>
				</div>
				<div class="form-group col-md-3 col-xs-12">
					<button id='guardar' onclick='cancelar()' class='btn btn-default btn-block'>
						Cancelar <i class="fa fa-camera-retro fa-lg"></i>
					</button>
				</div>	
			</div> <!-- botones -->
		</div> <!-- // Lado Formulario -->
	</div> <!-- // Contenido -->
	<!--INICIA Solo visible para moviles-->
	<div class='row' id='moviles2'>
		<div class='col-xs-12 col-md-1 col-md-offset-5'>
				  <a class="btn btn-primary" href="#sube" id='subir' role="button">Ver Arbol</a>
		</div>
	</div>
	<!--TERMINA Solo visible para moviles-->         
</div>
<div id='blanca' style='background-color:white;position:absolute;top:110px;width:100%;height:100%;z-index:999;'>
	<?php
		$largo = "1000px";
		$mensaje_loading = "Cargando Arbol de Almacenes...";
		require "views/partial/loading_all.php";
	?>
</div>




<!--AQUI ESTAN LOS MODALS-->
