<script src="js/jquery-1.10.2.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="js/date.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/datepicker_cash.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src='js/select2/select2.min.js'></script>
<script language='javascript' src='js/config.js'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<link rel="stylesheet" href="js/select2/select2.css">

<script>
$(function(){
$("#subir_facturas,#buscar_facturas,#asignar_facturas,#busqueda,#busqueda2,#tipo_busqueda,#titulo_tipo_busqueda,#buscando_text").hide()
});
		<?php 
		$tipoinstancia = 1;
		require 'js/arbolpredefinido.js.php';
		?>
		function abrir()
		{
			if($("#subircuentas").attr('abierto') == '0')
			{
				$('#subircuentas').show('slow')
				$("#subircuentas").attr('abierto','1')
				$("#abr").text('Cerrar')
			}
			else
			{
				$('#subircuentas').hide('slow')
				$("#subircuentas").attr('abierto','0')
				$("#abr").text('Abrir')
			}
		}

		function validar()
		{
			var extension = $("#layout_cuentas").val()
			extension = extension.split('.')
			if(!$("#layout_cuentas").val() || extension[1] != 'xls')
			{
				alert('Es necesario agregar el layout (descargar el archivo xls) para generar este proceso')
				return false
			}
		}
		</script>

<style>
	.btn-preview {
		background: #fff;
		border-style: none;
		border: 1px solid #ddd;
		padding: .2em 1.2em;
		border-radius: .5em;
		font-size: .95em;
		box-shadow: 0 1px 0px rgba(0,0,0,0.3);
		text-decoration: none;
		color: #000;
	}
	.btn-preview:hover {
		text-decoration: none;
		color: #000;
		background: #eee;
	}
	.dp-choose-date{
		display:none;
	}
	.btnMenu{
		border-radius: 0; 
		width: 100%;
		margin-bottom: 0.3em;
		margin-top: 0.3em;
	}
	.row
	{
		margin-top: 0.5em !important;
	}
	h4, h5{
		background-color: #eee;
		padding: 0.4em;
	}
	.nmwatitles, [id="title"] {
		padding: 8px 0 3px !important;
	}
	.radioInt{
		margin-right: 0.5em !important;
	}
	.modal-title{
		background-color: unset !important;
		padding: unset !important;
	}
</style>
<?php
//Validacion si es configuracion de empresa o de gubernamental
$tipoinstancia = 1;
if($tipoConfiguracion == 1)
{
	$priv = "style='display:block;'";
	$gob = "style='display:none;'";
}
else
{
	$priv = "style='display:none;'";
	$gob = "style='display:block;'";
}
//TERMINA Validacion
			
if(!isset($data['id']))
{
	$act=0;
	$titulo='Crear Organizaci&oacute;n';
}
else
{
	$act=1; 
	$titulo='Modificar Organizaci&oacute;n';
}


if(!intval($data['PrimeraVez']))
{
	$readonly = 'readonly';
	$disabled = 'disabled';
	$display = 'style="display:none"';
	$segundodisplay = 'style="display:block"';
	$cl_lab = "<label>".$data['ClaveOrg']."</label>";
	$verSel = "style='display:none;";
	$activo = 'si';

}else
{
	$display = 'style="display:block"';
	$segundodisplay = 'style="display:none"';
	$readonly = '';
	$disabled = '';
	$cl_lab = "";
	$verSel = "style='display:block;";
	$activo = 'no';
}

if(intval($data['TipoCatalogo']) == 3)
{
	$chkd1 = '';
	$chkd2 = 'checked';
}
else
{
	$chkd1 = 'checked';
	$chkd2 = '';  
}

$numPoliza['id'] = "temporales";

require("views/captpolizas/facturas.php");
?>
<input type='hidden' id='idpoliza' value='temporales'>

<div class="container">
	<h3 class="nmwatitles text-center"> <?php echo $titulo; ?> </h3>
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<form name='newCompany' method='post' action='index.php?c=Config&f=saveConfig&act=<?php echo $act; ?>' onsubmit='return validaciones(this)' enctype="multipart/form-data" >
				<h4>General</h4>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Nombre de la organizaci&oacute;n:</label>
							<input type='hidden' name='tipoinstancia' value='<?php echo $tipoinstancia; ?>'>
							<input type='text' class="form-control" name='NameCompany' size='50' readonly value='<?php echo $name['nombreorganizacion']; ?>'>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Ejercicio Vigente:</label>
							<input type='text' class="form-control" name='NameExercise' size='50' readonly value='<?php echo $data['EjercicioActual']; ?>'>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" <?php echo @$segundodisplay ?>>
							<label>Cat&aacute;logo de Cuentas:</label>
							<button type='button' class="btn btn-primary btnMenu" onclick='reiniciar(<?php echo $bancos; ?>)' activo='<?php echo $activo; ?>' id='reinicia'>Reiniciar Contabilidad</button>
						</div>
					</div>
				</div>
				<?php
				if($tipoinstancia)
				{
				?>
					<section <?php echo @$display ?>>
						<h4>Cat&aacute;logo de Cuentas</h4>
						<div class="row">
							<div class="col-md-6">
								<h5><input class="radioInt" type="radio" value="2" name="tipoCarga" id="carga2"  <?php echo @$disabled; ?> <?php echo @$chkd1; ?>>Carga catalogo predefinido</h5>
								<?php if(intval($data['TipoCatalogo']) == 0)
								{
								?>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='1' <?php echo @$disabled; ?> >Si
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='0' checked <?php echo @$disabled; ?> >No
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='2'<?php echo @$disabled; ?> >Importar Cuentas de Otra Instancia
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type="file" name="archivo" id="archivo"  value="Importar Cuentas" <?php echo @$disabled; ?>/>
										</div>
									</div>
								<?php 
								}
								if(intval($data['TipoCatalogo']) == 1)
								{
								?>
									<div class="row">
										<div class="col-md-2 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='1' checked <?php echo @$disabled; ?> >Si
										</div>
										<div class="col-md-8">
											<a class="btn-preview" role="button" data-toggle="modal" data-target="#modalTreePreview">Ver catalago predeterminado</a>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='0' <?php echo @$disabled; ?> >No
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='2'<?php echo @$disabled; ?> >Importar Cuentas de Otra Instancia
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type="file" name="archivo" id="archivo"  value="Importar Cuentas" <?php echo @$disabled; ?>/>
										</div>
									</div>
								<?php 
								}
								if(intval($data['TipoCatalogo']) == 2)
								{
								?>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='1' <?php echo @$disabled; ?> >Si
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='0' <?php echo @$disabled; ?> >No
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type='radio' class="nminputradio radioInt" name='default_catalog' value='2' checked <?php echo @$disabled; ?> >Importar Cuentas de Otra Instancia
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-1">
											<input type="file" name="archivo" id="archivo"  value="Importar Cuentas" <?php echo @$disabled; ?>/>
										</div>
									</div>
								<?php 
								}
								?>
							</div>
							<div class="col-md-6">
								<h5><input class="radioInt" type="radio" value="3" name="tipoCarga" id="carga3" <?php echo @$disabled; ?> <?php echo @$chkd2; ?>>Carga otros Sistemas</h5>
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<label>Descargar plantillas de Datos</label>
										<a href="Formato_cuentas3.xls" style="display:block;text-align:left;"><img src="images/xls_icon.gif" alt="" class="radioInt">Cuentas<div style='color: #FF0000;float:right;'> (No elimine ninguna columna del formato.)</div></a>
										<a href="datos.txt" style="display:block;text-align:left;"><img src="images/txt_icon.gif" alt="" class="radioInt">Definicion de datos</a>
										<a href="Formato_polizas.xls" style="display:block;text-align:left;"><img src="images/xls_icon.gif" alt="" class="radioInt">Polizas<div style='color: #FF0000;float:right;'> (No elimine ninguna columna del formato.)</div></a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-md-offset-7">
										<button class="btn btn-primary btnMenu" type="button" name='xmls' onclick="facturasConf()">Importar XML's</button>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5 col-md-offset-1">
										<label>Importar Cuentas:</label>
										<input type="file" name="CONTPAC[]" id="archivo1"  value="Importar Cuentas CONTPAC" <?php echo @$disabled; ?>/>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5 col-md-offset-1">
										<label>Importar Polizas:</label>
										<input type="file" name="CONTPAC[]" id="archivo2"  value="Importar Polizas CONTPAC" <?php echo @$disabled; ?>/>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5 col-md-offset-1">
										<label>Mascara:</label>
										<input class="form-control" type="text" name="txtMascara" value="" id="txtMascara" <?php echo @$disabled; ?>> 
									</div>
									<div class="col-md-5">
										<label>Separador:</label>
										<input class="form-control" type="text" name="txtSeparador" onblur="validaSeparador()" value="" id="txtSeparador" <?php echo @$disabled; ?>>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?php
				} 
				?>
				<div class="row">
					<input type='hidden' name='values' value='n'>
					<div class="col-md-4">
						<h5>Cuentas</h5>
						<?php
						if(!isset($data['TipoNiveles']) OR $data['TipoNiveles'] == 'a')
						{
						?>
							<div class="form-group">
								<div class="checkbox">
									<label style="padding-left: 0 !important;">
											<input type="radio" name='level' id='lev_manual' value='m' <?php echo @$disabled; ?> >
											Numeros Manuales
										</label>
								</div>
								<input type='text' class="form-control" name='structure' id='structure' value='<?php echo @$data['Estructura'];?>' onchange='estructura_vacia()' <?php echo @$readonly; ?>>
							</div>
						<?php 
						}
						else
						{
						?>
							<div class="form-group">
								<div class="checkbox">
									<label style="padding-left: 0 !important;">
										<input type="radio" name='level' value='m' checked <?php echo @$disabled; ?> >
											Numeros Manuales
										</label>
								</div>
								<input type='text' class="form-control" name='structure' id='structure' value='<?php echo @$data['Estructura'];?>' onchange='estructura_vacia()' <?php echo @$readonly; ?>>
							</div>
						<?php 
						}
						?>
					</div>
					<div class="col-md-4">
						<h5>Polizas</h5>
						<div class="row">
							<?php 
							if(!isset($data['NumPol']) OR !intval($data['NumPol']))
							{
							?>
								<div class="col-md-6">
									<div class="form-group">
										<div class="checkbox">
											<label style="padding-left: 0 !important;">
													<input type='radio' name='numpol' value='0' checked <?php echo @$disabled; ?> >
													Numeros Automaticos
											</label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="checkbox">
											<label style="padding-left: 0 !important;">
													<input type='radio' name='numpol' value='1' <?php echo @$disabled; ?> >
													Numeros Manuales
											</label>
										</div>
									</div>
								</div>
							<?php 
							}
							else
							{
							?>
								<div class="col-md-6">
									<div class="form-group">
										<div class="checkbox">
											<label style="padding-left: 0 !important;">
													<input type='radio' name='numpol' value='0' <?php echo @$disabled; ?> >
													Numeros Automaticos
											</label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="checkbox">
											<label style="padding-left: 0 !important;">
													<input type='radio' name='numpol' value='1' checked <?php echo @$disabled; ?> >
													Numeros Manuales
											</label>
										</div>
									</div>
								</div>
							<?php 
							}
							?>
						</div>
					</div>
					<div class="col-md-4" <?php echo $priv;?>>
						<div class="form-group">
								<h5>RFC</h5>
								<input type='text' class="form-control" name='rfc' size='50' readonly value='<?php echo $rfc; ?>'>
						</div>
					</div>
				</div>
				<div class="row" <?php echo $gob;?>>
					<div class="col-md-4">
						<div class="form-group">
								<h5>Clave Administrativa</h5>
								<?php echo $cl_lab; ?>
								<select class="form-control" name='cl_num' id='cl_num' <?php echo $verSel?>>
								<?php 
									echo $sel;
								?>
							</select>
						</div>
					</div>
				</div>
				<input type='hidden' value='-' name='cl_num'>
				<h4>Ejercicio Financiero</h4>
				<div class="row">
					<?php
						if(!isset($data['InicioEjercicio']))
						{
							$ejercicio = '01-01-'.$data['EjercicioActual'];
						}
						else
						{
							$ej = explode('-',$data['InicioEjercicio']);
							$ejercicio = $ej[2].'-'.$ej[1].'-'.$ej[0];
						}
					?>
					<div class="col-md-6">
						<div class="form-group">
							<label>Inicio del ejercicio:</label>
							<input type='text' class="form-control date-pick" name='begin'  value='<?php echo $ejercicio; ?>' onchange='cambia_fecha()' readonly >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Fin del ejercicio:</label>
							<input type='text' class="form-control" readonly id='fecha_fin'>
						</div>
					</div>
				</div>
				<h4>Periodos contables</h4>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipo de periodo:</label>
									<label>Mensual</label>
									<input type='hidden' name='period' id='period' value='m'>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Periodos del ejercicio:</label>
									<label>12</label>
									<input type='hidden' name='periods' value='12'>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?php
										if(!isset($data['PeriodoActual']))
										{
											$data['PeriodoActual'] = '1';
										}
									?>
									<label>Periodo vigente:</label>
									<input type='text' class="form-control" value='<?php echo $data['PeriodoActual'];?>' name='current_period' id='current_period' size='2'  onchange='cambia_periodo()' onkeypress="return validar_let(event)" maxlength='2' >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								Del <label id='inicio_mes'></label>
							</div>
							<div class="col-md-4">
								Al <label id='fin_mes'></label>
								<input type='hidden' name='primera_vez' value='<?php echo $data['PrimeraVez']; ?>'>
							</div>
							<div class="col-md-4">
								<?php
									$checked;
									if(@$data['PeriodosAbiertos'])
									{
										$checked='checked';
									}
								?>
								<div class="form-group">
									<div class="checkbox" style="padding-left: 20px">
										<label style="padding-left: 0 !important;">
												<input type='checkbox' value='1' name='open_periods' <?php echo @$checked; ?> >
												Manejar periodos abiertos
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
										$checked2;
										if(@$data['TodasFacturas'])
										{
											$checked2='checked';
										}
									?>
									<div class="form-group">
										<div class="checkbox" style="padding-left: 20px">
											<label style="padding-left: 0 !important;">
													<input type='checkbox' value='1' name='todas_facturas' <?php echo @$checked2; ?> >
													Buscar Facturas por Rango de Fecha en Captura de Polizas.
											</label>
										</div>
									</div>

							</div>
						</div>
						<!-- Confirmar las polizas -->
						<div class="row">
							<div class="col-md-12">
								<?php
									$checked3;
									if(@$data['ConfirmPoliza'])
									{
										$checked3='checked';
									}
								?>
								<div class="form-group">
									<div class="checkbox" style="padding-left: 20px">
										<label style="padding-left: 0 !important;">
												<input type='checkbox' value='1' name='confirmar_poliza' <?php echo @$checked3; ?> >
												Confirmar las polizas para que sean válidas.
										</label>
									</div>
								</div>
							</div>
						</div> <!-- // Confirmar las polizas -->

						<!-- Activar 7 dimensiones (virbac) -->
						<div class="row">
							<div class="col-md-12">
								<?php
									$checked4;
									if(@$data['siete_dimensiones'])
									{
										$checked4='checked';
									}
								?>
								<div class="form-group">
									<div class="checkbox" style="padding-left: 20px">
										<label style="padding-left: 0 !important;">
												<input type='checkbox' value='1' name='siete_dimensiones' <?php echo @$checked4; ?> >
												Activar las 7 dimensiones.
										</label>
									</div>
								</div>
							</div>
						</div> <!-- // Activar 7 dimensiones (virbac) -->

					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
					</div>
					<div class="col-md-3">
						<button type='submit' class="btn btn-primary btnMenu" name='save' onclick="$('#nmloader_div',window.parent.document).show();">Guardar</button>
					</div>
					<div class="col-md-3">
						<button type='button' class="btn btn-danger btnMenu" name='cancel' onclick='regresar()'>Cancelar</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalTreePreview" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Vista previa - Catalago predefinido</h4>
			</div>
			<div class="modal-body" style="overflow-y: scroll; max-height: 350px;">
				<div id="cont">
					<ul></ul>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>