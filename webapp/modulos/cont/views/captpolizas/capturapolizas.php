<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script language='javascript' src='js/mask.js'></script>
<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/agregar.js"></script>
<script src="js/copiar.js"></script>
<script src="js/funcionesPolizas.js"></script>
<script src="js/funcionesProveedores.js"></script>
<script src="js/funcionesCausacion.js"></script>
<script src="js/BigEval.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<link rel="stylesheet" type="text/css" href="css/imprimir.css" />
<style>
	.select_btn{
		text-decoration:none !important;
		cursor:pointer !important;
		color:#96BE33;
	}
	.select_btn > i{
		font-size:1.5em;
		vertical-align:middle;
	}
	.select_btn:hover{
		color:#749C11;
	}
	.TablaAgregar
	{
		background-color:#98ac31;
		color:white;
		font-size:8px;
	}
	.TablaAgregar b
	{
		font-size:10px;
	}
	.TablaAgregar td
	{
		width:250px;
	}
	.btnMenu{
	    border-radius: 0;
	    width: 100%;
	    margin-bottom: 0.3em;
	    margin-top: 0.3em;
	}
	.row
	{
	    margin-top: 0.1em !important;
	}
	.select2-container{
		width: 100% !important;
	}
	.select2-container .select2-choice{
		background-image: unset !important;
		height: 31px !important;
	}
	td, th{
		vertical-align: middle !important;
	}
	.buscar_input{
		background-image: url("search.png");
	    background-position: right center;
	    background-repeat: no-repeat;
	    background-size: 20px 20px;
	}
</style>
<!--Inicio de Captura Movimientos-->
<?php
require("views/captpolizas/agregar.php");
require("views/captpolizas/proveedores.php");
require("views/captpolizas/causacion.php");
require("views/captpolizas/facturas.php");
require("views/captpolizas/copiarpoliza.php");
?>
<script>

if($('#tipoPoliza').val()!=2){
		//$('#pago').hide();
		$('#formap').hide();
		//$('#pago').val(0);
		$("#datospago").hide();

	}else{
		$("#datospago").show();
		//$('#pago').show();
		$('#formap').show();
	}

	$(document).ready(function(){
		<?php
		$listaprv = "<option value=0 >--Seleccione--</option>";
		$listaempleado = '<option value=0 >--Seleccione--</option>';
		while($b=$beneficiariolist->fetch_array()){
			$listaprv.="<option value=".$b['idPrv'].">".mb_strtoupper($b['razon_social'],'UTF-8')."</option>";
			}
		while($b=$empleadoslist->fetch_array()){
			$listaempleado.="<option value=".$b['idEmpleado']." >".mb_strtoupper($b['nombreEmpleado']." ".$b['apellidoPaterno']." ".$b['apellidoMaterno'],'UTF-8')." </option>";
		}
		?>
		 listaprv = "<?php echo $listaprv; ?>";
		 listaempleado = "<?php echo $listaempleado; ?>";
		$(".display").hide();
	});
</script>
<!--Fin de Captura Movimientos-->
<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
<!-- <img class="nmwaicons" src="images/copiar.png" border="0" title='Copiar Poliza' onclick="copiarPoliza();"> -->
<form name='newCompany' id='forma' method='post' action='index.php?c=CaptPolizas&f=ActualizarPoliza&p=<?php echo $numPoliza['id']; ?>' onsubmit='return validaciones(this)'>
 <?php /*if(isset($_SESSION['anticipo'])){?>
		<h3 id="title" class="text-center">Anticipo Gastos</h3>
<?php }else{ ?>
		<h3 id="title" class="text-center">Capturar polizas</h3>
<?php } */

?>

<div class="container">
	<div class="row">
		<div class="col-md-1 col-sm-1 " id="c1">
		</div>
		<div class="col-md-10 col-sm-10" id="c3">
			<section>
				<div class="row">
					<div class="col-md-6 col-sm-6 ">
						<div class="form-group">
							<input type="hidden" id="idpoli" value="<?php echo $numPoliza['id']; ?>" />
							<label style="font-size: 15px;">Nombre de la organizaci&oacute;n:</label>
							<input type='text' class="form-control" name='NameCompany' size='50' readonly value='<?php echo $Ex['nombreorganizacion']; ?>'>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 ">
						<div class="form-group">
							<label style="font-size: 15px;">Ejercicio:</label>
							<input type='hidden' name='IdExercise' id='IdExercise' size='50' value='<?php echo $Ex['IdEx']; ?>'>
							<input type='text' name='NameExercise' class="form-control" id='NameExercise' size='50' readonly value='<?php echo $Ex['EjercicioActual']; ?>'>
						</div>
					</div>
				</div>
			</section>
			<section>
				<?php
					$InicioEjercicio = explode("-",$Ex['InicioEjercicio']);
					$FinEjercicio = explode("-",$Ex['FinEjercicio']);
				?>
				<div class="row" style="background-color: #eee; font-size: 15px;  margin: 0 !important;">
					<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label>Ejercicio Vigente: </label>
							del (<b><?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$Ex['EjercicioActual']; ?></b>) al (<b><?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$Ex['EjercicioActual']; ?></b>)
						</div>
					</div>
					<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label>Periodo actual:</label>
							<label id='PerAct'><?php echo $Ex['PeriodoActual']; ?></label>
							<input type='hidden' id='periodos' name='periodos' value='<?php echo $Ex['PeriodoActual']; ?>'>
							del (<label id='inicio_mes'></label>) al (<label id='fin_mes'></label>)
						</div>
					</div>
				</div>
			</section>
			<section>
				<h4>Datos de la Poliza</h4>
				<div class="row">
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Tipo de Poliza:</label>
							<select name='tipoPoliza' class="form-control" id='tipoPoliza' onchange="tipopoliza()">
								<?php
								while($LTP = $ListaTiposPolizas->fetch_assoc())
								{
									echo "<option value='".$LTP['id']."'>".$LTP['titulo']."</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Referencia:</label>
							<input type='hidden' id='estruc' value='<?php echo $estructura; ?>'>
							<input type='hidden' id='tipo_cuentas' value='<?php echo $type_id_account; ?>'>
							<input type='text' class="form-control" name='referencia' id="referencia" maxlength='40'>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Concepto:</label>
							<input type='text' class="form-control" name='concepto' id='concepto' maxlength='50'>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Fecha:</label>
							<input type='text' class="form-control" name='fecha' id='datepicker' onchange="actuali()">
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<a href='javascript:facturas()' style='font-weight:bold;color:black;' title='Ver Facturas' id='FacturasButton'>
								<img src='images/clip.png' style='vertical-align: middle;' width='40px' title="Asociar Facturas">
								Anexar Factura
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label># Poliza:</label>
							<?php
							$readonly='';
							 	if(!$manualnumpol)
								{
									$readonly = 'readonly';
								}
							?>
					 		<input type='number' name='numpol' id='numpol' class="form-control" value='<?php echo $numpol; ?>' maxlength='6' <?php echo $readonly; ?>>
					 		<span id='numpolload' style='color:red;font-style:italic;font-size:10px;'>Cargando numero...</span>
					 		<input type='hidden' name='numtipo' id='numtipo' value='<?php echo "1-".$numpol;?>' maxlength='6' <?php echo $readonly; ?>>
						</div>
					</div>
				</div>
				<div class="row" id="us" style="display: none">
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Usuario:</label>
							<select id="usuarios" name="usuarios" class="" style="">
								<?php
									while($user = $usuarios->fetch_assoc()){ ?>
										<option value="<?php  echo $user['idempleado']?>"><?php echo $user['usuario']?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<label>Importe:</label>
						<input type="text" id="importeprevio" name="importeprevio" class="form-control" />
					</div>
				</div>
			</section>
			<section>
				<h4>Cuadre de los Movimientos</h4>
				<div class="row" style="background-color: #eee;  margin: 0 !important;">
					<div class="col-md-4 col-sm-4 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label id='Cargos' style="font-size: 15px;"></label>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label id='Abonos' style="font-size: 15px;"></label>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label id='Cuadre' style="font-size: 15px;"></label>
						</div>
					</div>
				</div>
			</section>

			<!-- 7 dimensiones VIRBAC -->
			<section id="siete_dimensiones" style="display:<?php echo($mostrar_dim); ?>;">
				<hr>
				<h4>7 Dimensiones</h4>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="centro_costo">Centro costo:</label>
							<br>
							<select class="form-control" id="centro_costo">
								<option value="0">Ninguno</option>
								<?php 
								foreach ($tablas['centro_costo'] as $registro => $array) {
									$id = '';
									$nombre = '';
									foreach ($array as $campo => $valor) {
										if ($campo == 'id') {
											$id = $valor;
										} else if ($campo == 'nombre') {
											$nombre = $valor;
										}
									}
									echo "<option value='$id'>$nombre</option>";
								} ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="partner_pais">Partner País:</label>
							<br>
							<select class="form-control" id="partner_pais">
								<option value="0">Ninguno</option>
								<?php 
								foreach ($tablas['partner_pais'] as $registro => $array) {
									$id = '';
									$nombre = '';
									foreach ($array as $campo => $valor) {
										if ($campo == 'id') {
											$id = $valor;
										} else if ($campo == 'nombre') {
											$nombre = $valor;
										}
									}
									echo "<option value='$id'>$nombre</option>";
								} ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="proyecto">Proyecto:</label>
							<br>
							<select class="form-control" id="proyecto">
								<option value="0">Ninguno</option>
								<?php 
								foreach ($tablas['proyecto'] as $registro => $array) {
									$id = '';
									$nombre = '';
									foreach ($array as $campo => $valor) {
										if ($campo == 'id') {
											$id = $valor;
										} else if ($campo == 'nombre') {
											$nombre = $valor;
										}
									}
									echo "<option value='$id'>$nombre</option>";
								} ?>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="pedidos_ganancias">Pedidos y ganancias:</label>
							<br>
							<select class="form-control" id="pedidos_ganancias">
								<option value="0">Ninguno</option>
								<?php 
								foreach ($tablas['perdidas_ganancias'] as $registro => $array) {
									$id = ''; 
									$nombre = '';
									foreach ($array as $campo => $valor) {
										if ($campo == 'id') {
											$id = $valor;
										} else if ($campo == 'nombre') {
											$nombre = $valor;
										}
									}
									echo "<option value='$id'>$nombre</option>";
								} ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="evento_contable">Evento contable:</label>
							<br>
							<select class="form-control" id="evento_contable">
								<option value="0">Ninguno</option>
								<?php 
								foreach ($tablas['evento_contable'] as $registro => $array) {
									$id = '';
									$nombre = '';
									foreach ($array as $campo => $valor) {
										if ($campo == 'id') {
											$id = $valor;
										} else if ($campo == 'nombre') {
											$nombre = $valor;
										}
									}
									echo "<option value='$id'>$nombre</option>";
								} ?>
							</select>
						</div>
					</div>
				</div>
			</section> <!-- // 7 dimensiones VIRBAC -->

			<section id="datospago">
				<hr>
				<h4>Datos del Pago</h4>
				<div class="row">
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>Forma de pago:</label>
							<select id="formapago" name="formapago" >
							 	<option value="0">Elija una forma de pago</option>
							 	<?php while($f=$forma_pago->fetch_array()){
							 		//Si es la claveSat 98 se cambiara por NA
							 		if($f['claveSat'] == '98') { ?>
										<option value="<?php echo $f['idFormapago'];?>">
											<?php echo"(".$f['claveSat'].") NA";?>
										</option>
									<!-- Si cumple con las siguientes caracteristicas no se va a mostrar -->
									<?php } else if(
										($f['nombre'] == 'Cortesia') || 
										($f['nombre'] == 'Credito') || 
										($f['nombre'] == 'Crédito') || 
										($f['claveSat'] == '28') || 
										($f['claveSat'] == '29') || 
										(($f['claveSat'] == '99') && ($f['nombre'] !== 'Otros')) || 
										($f['claveSat'] == 'NA')
									){ ?>
									<!-- Si es la claveSat 07 se cambiara a tarjeta digital -->
									<?php } else if($f['claveSat'] == '07') { ?>
										<option value="<?php echo $f['idFormapago'];?>">
											<?php echo"(".$f['claveSat'].") TARJETAS DIGITALES";?>
										</option>
									<!-- si no, imprimir de forma normal -->
							 		<?php } else { ?>
								 		<option value="<?php echo $f['idFormapago'];?>">
											<?php echo "(".$f['claveSat'].") ".mb_strtoupper($f['nombre']); ?>
										</option>
							 		<?php	} } ?>
						 	</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="form-group">
							<label>Numero:</label>
							<input type="text" size="20" id="numero"  name="numero" value="" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 display">
						<div class="form-group">
							<label>Banco Origen:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px" title="Agregar Cuenta Bancaria" onclick="mandacuentabancaria()" src="images/mas.png">
							<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actcuentasbancarias()" src="images/reload.png">
							</br>
							<select id="listabancoorigen" name="listabancoorigen" onchange="numerocuentorigen()"  style="width:100%;">
								<option value="0">Elija Banco:</option>
								<?php
									while($b=$listacuentasbancarias->fetch_array()){ ?>
										<option value="<?php echo  $b['idbancaria']; ?>"><?php echo $b['nombre']." (".$b['description']."[".$b['manual_code']."])"; ?> </option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 display">
						<div class="form-group">
							<label>No. Cuenta Bancaria Origen/tarjeta:</label>
							<input type="text" id="numorigen" class="form-control" name="numorigen" value="" readonly/>
						</div>
					</div>
				</div>
				<div class="row display">
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<input type="checkbox" id="listaempleado"  onclick="listaEm()"/><b>Empleados / Beneficiario:</b>
							<input type="hidden" name="tipoBeneficiario" id="tipoBeneficiario" value="1"/>

							<img style="vertical-align:middle;" title="Agregar Beneficiario" onclick="irapadron()" src="images/cuentas.png">
							<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actualizaprove()" src="images/reload.png">
							</br>
							<select id="beneficiario" name="beneficiario"   onchange="cuentarbolbenefi();">
								<option value="0">Elija un Beneficiario</option>
								<?php while($b=$beneficiario->fetch_array()){ ?>
										<option value="<?php echo  $b['idPrv']; ?>"><?php echo ($b['razon_social']); ?> </option>
								<?php }
									while($user = $usuarios->fetch_assoc()){ ?>
										<option value="<?php  echo $user['idempleado']?>"><?php echo $user['usuario']?></option>
							<?php 	} ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>RFC:</label>
							<input type="text" id="rfc" name="rfc" value="" class="form-control" readonly/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>Banco Destino:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px"  id="mandabanco" title="Agregar Bancos al Prv" onclick='mandabancos()'  src="images/mas.png">
							<select id="listabanco" name="listabanco" onchange="numerocuent()" class="form-control" >
								<option value="0">Elija Banco</option>
								<?php while($b=$listabancos->fetch_array()){ ?>
										<option value="<?php echo  $b['idbanco']; ?>"><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>No. Cuenta Bancaria Dest./tarjeta:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px" title="Cargar numero" onclick='numerocuent()' src="images/reload.png">
							<input type="text" id="numtarje" name="numtarje" class="form-control" value="" readonly/>
						</div>
					</div>
				</div>
			</section>
			<section>
				<div class="row">
					<div class="col-md-3 col-sm-4 ">
						<div class="form-group">
							<button type='button' class="btn btn-primary btnMenu" id='agregar' title='ctrl + m'>Agregar Movimientos</button>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<input style="margin-top: 0.3em;" type='text' class="buscar_input form-control" id='buscar'  name='buscar' placeholder='Buscar'>
						</div>
					</div>

					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<button type='button' class="btn btn-primary btnMenu" id='botonClientes' onClick='abreCausacion(<?php echo $numPoliza['id']; ?>)' title='ctrl + i'>Desglose de IVA Causado</button>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<button type='button' class="btn btn-primary btnMenu" id='botonProveedores' onClick='abreProveedoresLista(<?php echo $numPoliza['id']; ?>)' title='ctrl + i'>Relacionar Proveedores</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="table-responsive">
			  				<table class="table" id='lista'>
			  				</table>
			  			</div>
				  	</div>
				</div>
				<div class="row" id="relacionn" style="display: none">
					<div class="col-md-4 col-sm-6 ">
						<div class="form-group">
							<label><font color="red" face="Comic Sans MS,arial,verdana">Agregar relacion con provision:</font></label>
							<select class="form-control" id="relacionextra" name="relacionextra">
							</select>
						</div>
				  	</div>
				  	<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label><font color="red" face="Comic Sans MS,arial,verdana">Saldado:</font></label>
							<input class="form-group" type="checkbox" id="saldado" name="saldado" value="0" onclick="polizadiario()"/>
						</div>
				  	</div>
				</div>
			</section>
			<section>
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="checkbox">
							<label style="padding-left: 0 !important; margin-left: 20px;">
						    	<input type='checkbox' id='facsCheck' onclick='listafaccheck(this)'>
						    	Desplegar Lista de Grupo de Facturas
						  	</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="table-responsive">
			  				<table class="table" id='listaMovsFacturas' style='display:none;'>
			  				</table>
			  			</div>
					</div>
				</div>
			</section>
			<section>
				<div class="row">
					<div class="col-md-12 col-sm-12 ">
						<label>Creación:</label>
						<label id='c_div'><?php echo date("Y-m-d H:i:s")." / ".$usuario_creacion; ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 ">
						<label>Última Modificación:</label>
						<label id='m_div'><?php echo date("Y-m-d H:i:s")." / ".$usuario_modificacion; ?></label>
					</div>
				</div>
			</section>
			<section>
				<div class="row">
					<div class="col-md-8 col-sm-6 ">
					</div>
					<div class="col-md-2 col-sm-3 ">
						<button type='button' class="btn btn-primary btnMenu" title='ctrl+enter' id='guardarpolizaboton2' onclick="antesdemandar()">Guardar Poliza</button>
						<button type='submit' class="btn btn-primary btnMenu" title='ctrl+enter' id='guardarpolizaboton' style="display:none">Guardar Poliza</button>
					</div>
					<div class="col-md-2 col-sm-3 ">
						<button type='button' class="btn btn-danger btnMenu" title='ctrl + x' onClick='CancelPoliza()' id='cancelarpolizaboton'>Cancelar Poliza</button>
						<button type='button' class="btn btn-danger btnMenu" title='ctrl + x' onClick='CancelPolizaAnticipo()' id='cancelarpolizabotonanticipo' style="display: none">Cancelar Poliza</button>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-1 col-sm-1" id="c2">
		</div>
	</div>
</div>
</form>

<?php if(isset($_SESSION['anticipo'])){?>
 		<script>
 		$(document).ready(function(){
	 		$("#cancelarpolizabotonanticipo,#us").show();
	 		$("#cancelarpolizaboton").hide();
	 		$("#tipoPoliza").html("<option value='2' selected>Egresos</option>");
	 		$("#datospago").show();
	 		$(".display").show();
	 		$("#botonProveedores").hide();
	 		$("#listabancoorigen").attr("onchange",'numerocuentorigenanticipo()');
	 		$("#beneficiario").attr("onchange",'cuentArbolBenefiAnticipo()');
 		});
 		</script>
<?php } ?>
