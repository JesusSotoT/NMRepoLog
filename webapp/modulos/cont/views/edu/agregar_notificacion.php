<!-- CSS -->
<!-- JS -->
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/notificaciones.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		var val_archivo = false;
		obtener_notificacion($('.title').attr('data-id'));
		$('#archivo').on( 'change', function() {
			val_archivo = validar_extension_pdf($(this));
		});
		$('#formNotificacion').submit(function(e){
			agregar_editar_notificacion(val_archivo);
			e.preventDefault();
		});
	});
</script>
<style>
	#mensaje{
		resize: none;
		hyphens: auto;
		height: 7em;
		overflow-y: scroll;
	}
</style>
<div class="container well">
	<h2 class="title" <?php echo($id); ?> style="text-align: center;">
		<?php echo($titulo);?>
	</h2>
	<!-- Alinear formulario -->
	<div class="col-md-8 col-md-offset-2" id="form-container">
		<form method="POST" id="formNotificacion">
			<div class="row">

				<!-- Titulo -->
				<div class="col-md-12" style="margin-bottom: .5em;">
					<label for="">Titulo de la Notificación:</label>
					<input type="text" class="form-control" name="titulo" id="titulo">
				</div>

				<!-- Mensaje -->
				<div class="col-md-12" style="margin-bottom: .5em;">
					<label for="">Mensaje:</label>
					<textarea class="form-control" name="mensaje" id="mensaje"></textarea>
				</div>
				
				<!-- Tipo instancia -->
				<div class="col-md-4">
					<label for="">Producto:</label><br>
					<select class="form-control" name="producto" id="producto" required> 
						<option value="0">Seleccione el producto</option>
						<option value="1">Acontia</option>
						<option value="2">Foodware</option>
						<option value="3">Appministra</option>
						<option value="4">Xtructur</option>
					</select>
				</div>
				
				<!-- Archivo -->
				<div class="col-md-5">
					<div id="subir_archivo">
						<label for="">Subir archivo:</label><br>
						<input type="file" id='archivo' class="form-control">
					</div>
					<div id="ver_pdf" style="display:none;">
						<label for="">Ver archivo:</label><br>
						<p>
							<a id='link_pdf' target="_blank">Ejemplo</a>
							<a id='reemplazar' class="btn btn-danger" onclick='reemplazar_pdf();' title='Reemplazar archivo actual' style='padding:.25em .5em;font-size:12px;'><span class="glyphicon glyphicon-remove"></span></a>
						</p>
					</div>
				</div>
				
				<!-- Activa -->
				<div class="col-md-3">
					<label for="">Activa:</label><br>
					<select class="form-control" name="activa" id="activa" required> 
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
				</div>
			</div><br>

			<div class="row">
				<div class="col-md-3 col-md-offset-6" style="margin-bottom: 1em;">
					<input id="enviar" type="submit" value='<?php echo($boton);?>' class="btn btn-primary btn-block">
				</div>
				<div class="col-md-3" style="margin-bottom: 1em;">
					<a href="index.php?c=edu&f=ver_notificaciones" class="btn btn-default btn-block">Cancelar</a>
				</div>
			</div>
		</form> <!-- // Formulario -->
	</div> <!-- // Alinear formulario -->
</div>