<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/fdd07b1cf7.js"></script>
<script language='javascript'>
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
		$('.backupSpin').hide();
		$
	});

	function respaldar() {
		$('#descargarZip').css('display', 'none');
		$('#backupSpin').show();
		$.post("ajax.php?c=backup&f=backup_tables",
		function (data) {
			$("#resultBackup").html(data);
			$('#descargarZip').css('display', '');
			var downloadZip = $('#zipPath').val();
			$("#descargarZip").attr("href", downloadZip);
		});
	}

	function importar(){
		var nombre_instancia;
		nombre_instancia = $('#nombre_instancia').val();
		$('#import-info').hide();
		$('.backupSpin').show();
		$('#import-info').html('<h4>Traspaso concluido</h4>');
		if(nombre_instancia == '' || nombre_instancia == null || nombre_instancia == undefined){
			alert("Debe ingresar el nombre de la instancia que desea importar.");
		} else {
			$.post("ajax.php?c=edu&f=importar_datos",
				{
					nombre_instancia: nombre_instancia
				},
				function(data){
					$('#input-importar').hide();
					console.log(data);
					$.each(data, function(index, elemento){
						var innerHTML = ""+
						"<li>"+
							"Tabla "+index+" estado: "+elemento.estado+
						"</li>"
						;
						$('#import-info').append(innerHTML);
					});
					$('.backupSpin').hide();
					$('#import-info').show();
				}, "JSON");
		}
	}

	function guardaInicio(){
		$('#guardaInicio').prop("disabled", true);
		$('#guardaInicio').css('cursor', 'wait');
		$.post("ajax.php?c=Config&f=FirstExercise",
		{
			Ejercicio: $("#ejercicio").val(),
		},
		function()
		{
			location.reload()
		});
	};

	function Cerrar(IdEx,NombreEjercicio)
	{
		var confirma = confirm("Esta seguro de cerrar el ejercicio "+NombreEjercicio+"?")
		if(confirma)
		{
			$.post("ajax.php?c=Config&f=CloseExercise",
			{
				Id: IdEx,
				Ejercicio: NombreEjercicio,
			},
			function(data)
			{
				switch(data)
				{
					case 'Si':
							alert("El Ejercicio "+NombreEjercicio+" ha sido Cerrado");
							location.reload()
							break;
					case 'No':
							alert("((ALERTA)) No se ha generado la poliza del periodo 13, por lo tanto no se puede cerrar el ejercicio.");
							break;
					default:
							alert("No se ha cerrado el ejercicio anterior.")
				}


			});
		}
	}

	function validar(f)
	{
		if(f.activo.checked == false)
		{
			alert("Seleccione un ejercicio");
			return false
		}
	}
	function reiniciar_ejer()
	{
		if(confirm("Esta seguro que desea reiniciar el ejercicio?"))
		{
			$.post("ajax.php?c=Config&f=reiniciar_ejer",
			function()
			{
				location.reload();
			});
		}
	}
</script>

<link rel="stylesheet" type="text/css" href="css/datepicker.css" />
<style>
	#title
	{
		width:350px;
		border-bottom:2px solid white;
		text-align: center;
	}
	table tr
	{
		background-color:#EEEEEE;
	}
	table tr td
	{
		width:136px;
		text-align: center;
		font-size:16px;
		padding:10px;
		border-bottom:1px solid #BEBCBC;
	}
	a
	{
		color:black;
		text-decoration: none;
	}
	a:hover
	{
		text-decoration: underline;
	}
	.row
	{
		margin-top: 1em !important;
	}
	.btnMenu{
		border-radius: 0;
		width: 100%;
		margin-bottom: 1em;
	}
	.btnMenuInterno{
		border-radius: 0;
		width: 30%;
	}
	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
</style>

<?php
		if(!$IsEmpty)
		{
?>
			<center><div id='title'>Comenzando desde?</div>
				<table cellspacing='0' cellpadding='0'>
					<tr><td style='width:28px;'></td><td>
					<select name='ejercicio' id='ejercicio'>
						<?php
						$anyo = date('Y');
						for($i=0;$i<=9;$i++)
						{
							echo "<option value='$anyo'>$anyo</option>";
							$anyo -= 1;

						}
						?>
					</select>
					<td><input type='button' id='guardaInicio' value='Establecer' onClick='javascript:guardaInicio()'></td>
				</table>
			</center>
			<script>
				//Atajo para modal migrar ctrl + shift + i
				$(document).keydown(function(evt){
					if (evt.keyCode==73 && (evt.ctrlKey) && (evt.shiftKey)){
						evt.preventDefault();
						$('.backupSpin').hide();
						$('#nombre_instancia').val('');
						$('#importar').modal('show');
					}
				});
			</script>
<?php
		}
		else
		{
?>
			<div class="container">
				<h3 class="nmwatitles text-center">Cat&aacute;logo de Ejercicios</h3>
          		<div class="row" style="margin-top: 4em">
          			<div class="col-md-4"></div>
          			<div class="col-md-4" style="border: 1px solid; padding: 3em 2em;">
          			<?php
          			if($primera_vez)
          			{
          			?>
          				<button onclick='reiniciar_ejer()' class='btn btn-danger'>Reiniciar Ejercicio</button>
          			<?php
          			}
          			?>


          				<form name='guarda' method='post' action='index.php?c=Config&f=Establecer' onSubmit='return validar(this)'>
							<?php
								while($Ex = $Exercises->fetch_array())
								{
									if($Ex['EjercicioActual'])
									{
										$checked = "checked";
										$estilo = "background-color: #bfc9ca ;color:black;";
									}
									else
									{
										$checked = "";
										$estilo = "background-color:none;color:black;";
									}
									if(!$Ex['Cerrado'])
									{
										$cerrado = "<button type='button' class='btn btn-default btnMenuInterno' onClick='javascript:Cerrar(".$Ex['Id'].",".$Ex['NombreEjercicio'].");'>¿Cerrar?</button>";
									}else
									{
										$cerrado = "<label class='text-center' style='width: 30%; font-size: 14px; margin-top: 10px; margin-bottom: 10px !important;'>Cerrado</label>";
									}
									echo 	'<div class="row" style="' . $estilo . '">
												<div class="col-md-12">
													<input style="width: 10%" type="radio" name="activo" value="' . $Ex['NombreEjercicio'] . '" ' . $checked . '>
													<label style="width: 50%; font-size: 14px; margin-top: 10px; margin-bottom: 10px !important;" class="text-center">' . $Ex['NombreEjercicio'] . '</label>'
													. $cerrado . '
												</div>
											</div>';
								}
							?>
							<div class="row">
								<div class="col-md-6">
									<button type='submit' class="btn btn-primary  btnMenu" name="guardar">Establecer como</br>actual</button>
								</div>
								<div class="col-md-6">
									<button type='button' class="btn btn-primary btnMenu" onClick="window.open('index.php?c=Config&f=configExercise','_self')">Configuraci&oacute;n</button>
								</div>
							</div>
						</form>
          			</div>
          			<div class="col-md-4"></div>
          		</div>
          	</div>
				<?php
			}
			?>
				<script type="text/javascript">
				//Atajo para modal exportar ctrl + shift + l
				$(document).keydown(function(evt){
					if (evt.keyCode==76 && (evt.ctrlKey) && (evt.shiftKey)){
						evt.preventDefault();
						$('.backupSpin').hide();
						$('#exportar').modal('show');
					}
				});
				</script>

				<!-- Exportar -->
				<div id="exportar" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Respaldo</h4>
				      </div>
				      <div class="modal-body" style="overflow-y: scroll; max-height:350px;">
				        <p style="display: inline-block;">Haga click en exportar para generar un respaldo de su información.</p>
								<button type="button" class="btn btn-primary" onclick= "respaldar()">
									Exportar
								</button>
								<br>
								<div id="resultBackup">
									<center>
										<i class="backupSpin fa fa-spinner fa-pulse fa-3x fa-fw"></i>
										<span class="sr-only">Loading...</span>
									</center>
								</div>
				      </div>
				      <div class="modal-footer">
				      	<a href="#" id="descargarZip" class="btn btn-info" 
								style="display: none;">Descargar zip</a>
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div> <!-- // Exportar -->

				<!-- Importar -->
				<div id="importar" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Importar datos</h4>
				      </div>
					    <div class="modal-body" style="overflow-y: scroll; max-height:350px;">
				      	<span id="input-importar">
					        <div class="col-md-12">
						        <div class="form-group">
							      	<label for="nombre_instancia">Ingrese el nombre de la instancia:</label>
							      	<input type="text" id="nombre_instancia" class="form-control">
							      </div>
					        </div>
					        <div class="col-md-3 col-md-offset-9">			        	
										<button type="button" class="btn btn-primary btn-block" onclick="importar()">
											Importar
										</button>
					        </div>
				      	</span>
				        <!-- Texto de carga para el usuario -->
								<center class="backupSpin">
									<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
									<h3>Cargando...</h3>
								</center>
								<!-- Texto de información para el usuario -->
								<ul id="import-info" style="list-style: none;display: none;"></ul>
				      </div>
				      <div class="modal-footer">
				      	<a href="#" id="descargarZip" class="btn btn-info" 
								style="display: none;">Descargar zip</a>
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div> <!-- // Importar -->

</body>
</html>
