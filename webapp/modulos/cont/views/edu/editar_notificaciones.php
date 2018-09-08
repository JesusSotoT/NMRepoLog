<!-- CSS -->
<!-- JS -->
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/notificaciones.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		obtenerNotificaciones();
	});
</script>
<style>
	th{
		text-align: center;
	}
	td{
		hyphens: auto;
		text-align: center;
		vertical-align: middle !important;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2 class="title" style="text-align: center;">Ver Notificaciones</h2>
		</div>
		<div class="col-md-2" style="margin-top: 1.75em;">
			<a href="index.php?c=edu&f=capturar_notificacion" class="btn btn-primary btn-block">Agregar</a>
		</div>    
	</div>
	<hr>  
	<!-- Alinear formulario -->
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div id="lista-loading2">
					<h4 class="black-text">Cargando...</h4>
				</div>
				<div class="col-md-12 table-responsive">
					<table id="tabla-noticias2" class="table table-striped">
						<thead>
							<tr>
								<th style='width:10%;'>Producto</th>
								<th style='width:15%;'>Titulo</th>
								<th style='width:40%;'>Mensaje</th>
								<th style='width:15%;'>Fecha</th>
								<th style='width:5%;'>Estatus</th>
								<th style='width:15%;'>Archivo</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot style="background:#ccc;"></tfoot>
					</table>
				</div>
			</div>
		</div> <!-- // Alinear formulario -->
	</div>
</div>