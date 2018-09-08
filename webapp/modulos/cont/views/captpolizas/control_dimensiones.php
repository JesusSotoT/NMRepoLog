<style>
	thead{
		background:#337ab7;
		color: white;
	}
	tfoot{
		text-align: center;
		background: #fff !important;
		display: none;
	}
	th,td{
		vertical-align: middle;
		text-align: center;
	}
	hr{
		border-color:rgba(0,0,0,0.15);
	}
	.table-responsive{
		border-radius:.5em;
	}
	#container_agregar_registro, #container_modificar_registro{
		top:1.7em !important;
	}
	#captura_registros, #modificar_registros{
		display: none;
	}
	#titulo_agregar, #titulo_ver{
		background: rgba(51,122,183,0.25);
		padding: .4em;
		padding-left: 1.2em;
		border-radius: .2em;
	}
</style>

<div class="main-wrapper container">
	<!-- Titulo header -->
	<div class="row">
		<div class="col-md-12">
			<h2>Gestion de Registros</h2>
			<hr>
		</div>
	</div> <!-- //Titulo header -->

	<section class="well" style="background:#EDEDED;">
		<!-- Seleccionar catalogo -->
		<div class="row">
			<div class="form-group col-md-offset-1 col-md-10 col-sm-12">
				<label for="tablas">Seleccione un catalogo:</label>
				<br>
				<select name="" id="tablas" class="form-control">
					<option value="0">Ninguno</option>
					<option value="centro_costo">Centro costo</option>
					<option value="partner_pais">Partner país</option>
					<option value="proyecto">Proyecto</option>
					<option value="perdidas_ganancias">Perdidas y ganancias</option>
					<option value="evento_contable">Evento contable</option>
				</select>
			</div>
		</div> <!-- // Seleccionar catalogo -->

		<!-- Captura -->
		<section id="captura_registros">
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<h4 id="titulo_agregar" style="margin-top:.25em;">Agregar registro</h4>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-offset-1 col-md-6 col-sm-8">
					<label for="nombre_campo">Ingrese el nombre del registro que desea agregar:</label>
					<br>
					<input type="text" class="form-control" id="nombre_campo">
				</div>
				<div class="form-group col-md-2 col-sm-4">
					<label for="nombre_campo">Activo:</label>
					<br>
					<select id="activo" class="form-control">
						<option value="1" selected>Si</option>
						<option value="0">No</option>
					</select>
				</div>
				<div id="container_agregar_registro" class="form-group col-md-2">
					<button onclick="agregar_registro();" id="agregar_registro" class="btn btn-sm btn-primary material-icons">add_circle</button>
				</div>
				<div id="container_modificar_registro" class="form-group col-md-2" style="display: none;">
					<button onclick="modificar_registro();" id="modificar_registro" class="btn btn-sm btn-success material-icons">edit</button>
					<button onclick='cancelar();' id="cancel" class="btn btn-sm btn-danger material-icons">cancel</button>
				</div>
			</div>
		</section> <!-- // Captura -->

		<!-- Acciones captura -->
		<div class="row">
		</div> <!-- // Acciones captura -->

		<!-- Modificar registros -->
		<section id="modificar_registros">
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<h4 id="titulo_ver" style="margin-top:.25em;">Ver registros</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<div class="table-responsive">
						<table id="registros_catalogo" class="table table-striped table-hover table-bordered">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Activo</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2">
										<h5>Cargando...</h5>
										<i class="material-icons spin">sync</i>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</section> <!-- // Modificar registros -->
	</section>
</div>

<script>
	$(document).ready(function(){
		$('#tablas').on('change', function(data){
			cambiar_titulo();
			esconder_campos();
			rellenar_tabla();
		});
	});

	function cambiar_titulo(){
		$('#titulo_agregar').html("Agregar registro");
		$('#titulo_ver').html("Registros");
		var val = $('#tablas').val();
		if (val != 0){
			$('#titulo_agregar').append(" para <b>" + $("#tablas option[value='"+val+"']").text() + "</b>");
			$('#titulo_ver').append(" de <b>" + $("#tablas option[value='"+val+"']").text() + "</b>");
		} else {
			$('#titulo_agregar').html("Agregar registro");
			$('#titulo_ver').html("Registros");
		}
	}

	function esconder_campos(){
		if ($('#tablas').val() != 0){
			$('#captura_registros').show();
			$('#modificar_registros').show();
		} else {
			$('#captura_registros').hide();
			$('#modificar_registros').hide();
		}
	}

	function agregar_registro(){
		var tabla = $('#tablas').val();
		var campo = $('#nombre_campo').val();
		var estatus = $('#activo').val();
		//Validamos que se seleccione una tabla
		if (campo == '') {
			alert("Debe ingresar un nombre para el registro.");
		} else {
			$.post('ajax.php?c=CaptPolizas&f=add_campo',
			{
				tabla: 'cont_'+tabla,
				campo: campo
			}, function(data){
				//console.log(data);
				if (data) {
					alert("El registro se agrego exitosamente.");
					rellenar_tabla();
				}
			});			
		}
	}

	function rellenar_tabla(){
		$('tfoot').fadeIn();
		var tabla = $('#tablas').val();
		$.post('ajax.php?c=captpolizas&f=obtener_tabla',
		{
			tabla: 'cont_'+tabla,
			estatus: 1
		}, function(data){
			//console.log(data);
			$('#registros_catalogo tbody').html("");
			$.each(data, function(index, registro){
				var estatus = "No";
				if (registro.estatus == 1) {
					estatus = "Si";
				}
				var link = "<a onclick='mostrar_modificar("+registro.id+", \""+registro.nombre+"\", "+registro.estatus+")' title='Modificar'>"+registro.nombre+"</a>";
				$('#registros_catalogo').append("<tr><td>"+link+"</td><td>"+estatus+"</td></tr>");
			});
		}, "JSON");
		$('tfoot').fadeOut();
	}

	function modificar_registro(){
		tabla = $('#tablas').val();
		campo = $('#nombre_campo').val();
		id = $('#modificar_registro').attr('data-id');
		if (tabla == 0) {
			alert("Primero seleccione un catalago.");
		} else if(campo == null || campo == ''){
			alert("Debe ingresar un nombre.");
		} else {
			$.post('ajax.php?c=CaptPolizas&f=modificar_registro',
			{
				tabla: 'cont_'+tabla,
				campo: campo,
				activo: $('#activo').val(),
				id: id
			},function(data){
				if (data) {
					alert("El registro se modifico exitosamente.");
					rellenar_tabla();
				} else {
					alert("El registro no se modifico.");
				}
			}, "JSON");
		}
	}

	function mostrar_modificar(id,nombre,activo){
		$('#container_agregar_registro').hide();
		$('#container_modificar_registro').show();
		$('#modificar_registro').attr('data-id', id);
		$('#nombre_campo').val(nombre);
		$('#activo').val(activo);
		$('#titulo_agregar').html("Modificar registro");
		$('#titulo_agregar').append(" para <b>" + $("#tablas option[value='"+$('#tablas').val()+"']").text() + "</b>");
	}

	function cancelar(){
		$('#container_modificar_registro').hide();
		$('#container_agregar_registro').show();
		$('#nombre_campo').val("");
		$('#activo').val(1);
		cambiar_titulo();
	}
</script>