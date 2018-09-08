<script src="js/jquery-1.10.2.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="../posclasico/js/date.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="../posclasico/js/datepicker_cash.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src='js/select2/select2.min.js'></script>
<style>
.fila-base,.filadefault,.filadefault2,.fila-base2,.filadefault3,.fila-base3{ display: none; }
.eliminar,.eliminar2{ cursor: pointer; color: #000; }
.datos td, th
{
	width:160px;
	height:30px;
	word-wrap: break-word;
	max-width:160px; 
  	width:160px;
}
</style>
<style type="text/css">
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
  	h5, h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
    #capturaNoDeducible {
	    background-color: unset;
	    border: unset;
	    height: unset;
	    width: unset;
	}
</style>
<script language='javascript'>
function valida(cont)
{
	//alert($("#proveedores").val());
	if($("#clientes").val() == '' || !$("#clientes").val())
	{
		alert('Agregue una cuenta a clientes o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#clientes').focus();
    	return false;
	}

	/*if($("#ventas").val() == '' || !$("#ventas").val())
	{
		alert('Agregue una cuenta a ventas o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#ventas').focus();
    	return false;
	}

	if($("#IVA").val() == '' || !$("#IVA").val())
	{
		alert('Agregue una cuenta a IVA o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#IVA').focus();
    	return false;
	}*/

	if($("#caja").val() == '' || !$("#caja").val())
	{
		alert('Agregue una cuenta a caja o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#caja').focus();
    	return false;
	}

	/*if($("#TR").val() == '' || !$("#TR").val())
	{
		alert('Agregue una cuenta a tarjeta de regalo o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#TR').focus();
    	return false;
	}*/

	if($("#bancos").val() == '' || !$("#bancos").val())
	{
		alert('Agregue una cuenta a bancos o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#bancos').focus();
    	return false;
	}

	/*if($("#compras").val() == '' || !$("#compras").val())
	{
		alert('Agregue una cuenta a compras o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#compras').focus();
    	return false;
	}

	if($("#devoluciones").val() == '' || !$("#devoluciones").val())
	{
		alert('Agregue una cuenta a devoluciones o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#devoluciones').focus();
    	return false;
	}*/

	if($("#capital").val() == '' || !$("#capital").val())
	{
		alert('Agregue una cuenta a capital o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#capital').focus();
    	return false;
	}

	/*if($("#flujo").val() == '' || !$("#flujo").val())
	{
		alert('Agregue una cuenta a flujo o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#flujo').focus();
    	return false;
	}*/
	
	if($("#proveedores").val() == '' || !$("#proveedores").val())
	{
		alert('Agregue una cuenta a proveedores o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#proveedores').focus();
    	return false;
	}
	if($("#utilidad").val() == '' || !$("#utilidad").val())
	{
		alert('Agregue una cuenta a Utilidad o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#utilidad').focus();
    	return false;
	}
	if($("#perdida").val() == '' || !$("#perdida").val())
	{
		alert('Agregue una cuenta para Perdida o cancele la operacion')
    	// seleccionamos el campo incorrecto
    	$('#perdida').focus();
			return false;
	}
	if ($("#ivapendientepago").val() == '' || !$("#ivapendientepago").val()) {

		alert('Agregue una cuenta para IVA Acreditable Pendiente de pago')

		// seleccionamos el campo incorrecto

		$('#ivapendientepago').focus();

		return false;

	}

	if ($("#ivapagado").val() == '' || !$("#ivapagado").val()) {

		alert('Agregue una cuenta para IVA Acreditable Pagado')

		// seleccionamos el campo incorrecto

		$('#ivapagado').focus();

		return false;

	}

	if ($("#ivapendientecobro").val() == '' || !$("#ivapendientecobro").val()) {

		alert('Agregue una cuenta para IVA Trasladado Pendiente de cobro')

		// seleccionamos el campo incorrecto

		$('#ivapendientecobro').focus();

		return false;

	}

	if ($("#ivacobrado").val() == '' || !$("#ivacobrado").val()) {

		alert('Agregue una cuenta para IVA Trasladado Cobrado')

		// seleccionamos el campo incorrecto

		$('#ivacobrado').focus();

		return false;

	}

	if ($("#iepspendientepago").val() == '' || !$("#iepspendientepago").val()) {

		alert('Agregue una cuenta para IEPS Acreditable Pendiente de pago')

		// seleccionamos el campo incorrecto

		$('#iepspendientepago').focus();

		return false;

	}

	if ($("#iepspagado").val() == '' || !$("#iepspagado").val()) {

		alert('Agregue una cuenta para IEPS Acreditable Pagado')
		$('#iepspagado').focus();

		return false;

	}

	if ($("#iepspendientecobro").val() == '' || !$("#iepspendientecobro").val()) {

		alert('Agregue una cuenta para IEPS Trasladado Pendiente de cobro')
		$('#iepspendientecobro').focus();

		return false;

	}

	if ($("#iepscobrado").val() == '' || !$("#iepscobrado").val()) {
		alert('Agregue una cuenta para IEPS Trasladado Cobrado')
		$('#iepscobrado').focus();
			return false;

	}
	if ($("#deudores").val() == '' || !$("#deudores").val()) {
		alert('Agregue una cuenta para Deudores')
		$('#deudores').focus();
		return false;

	}

	if ($("#Cuentasgastospolizas").val() == '' || !$("#Cuentasgastospolizas").val()) {
		alert('Agregue una cuenta para la cuenta de Gastos de Poliza')
		$('#Cuentasgastospolizas').focus();
		return false;
	}

	// 
	if ($("#ish").val() == '' || !$("#ish").val()) {
		alert('Agregue una cuenta para ISH')
		$('#ish').focus();

		return false;

	}
	//retenciones
	if ($("#ivaretenido").val() == '' || !$("#ivaretenido").val()) {

		alert('Agregue una cuenta para IVA retenido')
		$('#ivaretenido').focus();

		return false;

	}
	if ($("#isrretenido").val() == '' || !$("#isrretenido").val()) {

		alert('Agregue una cuenta para ISR ')
		$('#isrretenido').focus();

		return false;

	}
	if ($("#iepsgasto").val() == '' || !$("#iepsgasto").val()) {

		alert('Agregue una cuenta para el Gasto  ')
		$('#iepsgasto').focus();

		return false;

	}
	if ($("#ivagasto").val() == '' || !$("#ivagasto").val()) {

		alert('Agregue una cuenta para el Gasto ')
		$('#ivagasto').focus();

		return false;

	}
	if ($("#sueldo").val() == '' || !$("#sueldo").val()) {

		alert('Agregue una cuenta Sueldo por Pagar')
		$('#sueldo').focus();

		return false;

	}
	
}
</script>
<link rel="stylesheet" href="js/select2/select2.css">
<style>
a{
	display:none;
}
.nminputbutton_color2
{
	width:250px;
}
</style>
<?php



	$optionsList = "<option value='-1'>NINGUNO</option>";
	while($Cuentas = $Accounts->fetch_array())
	{
		$optionsList .= "<option value='".$Cuentas['account_id']."'>".$Cuentas['description']."(".$Cuentas['manual_code'].")</option>";
	}
	$circulantelist = "<option value='-1'>NINGUNO</option>";
	while($row = $circulante->fetch_array())
	{
		$circulantelist .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
	}
	$gastoList = "<option value='-1'>NINGUNO</option>";
	while($row = $ishgasto->fetch_array())//aplica para perceciones
	{
		$gastoList .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
	}
	
	$listaCuentaSueldo = "<option value='-1'>NINGUNO</option>";
	while($row = $pasivoCirculante->fetch_array()){
		$listaCuentaSueldo .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
	}
	$listaCuentaDeduccionesPercepciones = "<option value='-1'>NINGUNO</option>";
	while($row = $afectables->fetch_array()){
		$listaCuentaDeduccionesPercepciones .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
	}
	$deducciones = "<option value='-1'>NINGUNO</option>";
	while($row = $deduccionesNomina->fetch_array()){
		$deducciones .= "<option  value='".$row['idAgrupador']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$percepciones = "<option value='-1'>NINGUNO</option>";
	while($row = $percepcionesNomina->fetch_array()){
		$percepciones .= "<option value='".$row['idAgrupador']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}
	$otrospagos = "<option value='-1'>NINGUNO</option>";
	while($row = $otrospagosNomina->fetch_array()){
		$otrospagos .= "<option value='".$row['idAgrupador']."'>".$row['descripcion']."(".$row['clave'].")</option>";
	}

?>

<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<h3 id="title" class="text-center">Asignación de Cuentas</h3>
			<form name='newCompany' method='post' action='index.php?c=Config&f=saveConfigAccounts' onsubmit='return valida(this)'>
				<section>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nombre de la organización:</label>
								<input class="form-control" type='text' name='NameCompany' size='50' readonly value='<?php echo $name['nombreorganizacion']; ?>'>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Ejercicio vigente:</label>
								<input class="form-control" type='text' name='NameExercise' size='50' readonly value='<?php echo $data['EjercicioActual']; ?>'>
							</div>
						</div>
					</div>
				</section>
				<h4>Selecciona la cuenta de mayor</h4>
				<section>
					<div class="row">
						<div class="col-md-1 col-sm-1">
						</div>
						<div class="col-md-10 col-sm-1">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de clientes:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showClientes" data-id='clientes'>Asignar cuenta de clientes</button>
										<div id="hideclientes">
											<select name='clientes' id='clientes' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelclientes" data-id="clientes">Cancelar</button>
											<!--Enviar valor a las variables eliminadas-->
											<input type='hidden' name='ventas' value='-1'><input type='hidden' name='IVA' value='-1'>
											<!--///////////////////////////////////////-->
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de caja:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showCaja" data-id='caja'>Asignar cuenta de Caja</button>
										<div id="hidecaja">
											<select name='caja' id='caja' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelcaja" data-id="caja">Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de bancos:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showBancos" data-id='bancos'>Asignar cuenta de Bancos</button>
										<div id="hidebancos">
											<select name='bancos' id='bancos' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelbancos" data-id="bancos">Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 col-sm-1">
						</div>
					</div>
					<div class="row">
						<div class="col-md-1 col-sm-1">
						</div>
						<div class="col-md-10 col-sm-10">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de Capital (Saldos ejercicios):</label>
										<button type='button' class="btn btn-primary btnMenu" id="showCapital" data-id="capital">Asignar Cuenta de Saldo ejercicios</button>
										<div id="hidecapital">
											<select name='capital' id='capital' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelcap" data-id="capital">Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de Proveedores:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showProveedores" data-id="proveedores">Asignar cuenta de Proveedores</button>
										<div id="hideproveedores">
											<select name='proveedores' id='proveedores' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelpro" data-id="proveedores">Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Utilidad en cambios:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showUtilidad" data-id="utilidad">Asignar Cuenta de Utilidad</button>
										<div id="hideutilidad">
											<select name='utilidad' id='utilidad' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelutilidad" data-id="utilidad">Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 col-sm-1">
						</div>
					</div>
					<div class="row">
						<div class="col-md-1 col-sm-1">
						</div>
						<div class="col-md-10 col-sm-10">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Perdida en cambios:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showPerdida" data-id="perdida">Asignar Cuenta de Perdida</button>
										<div id="hideperdida">
											<select name='perdida' id='perdida' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelperdida" data-id="perdida">Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Deudores:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showDeudores"  data-id="deudores">Asignar Cuenta Deudores</button>
										<div id="hidedeudores">
											<select name='deudores' id='deudores' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceldeudores" data-id="deudores">Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de Gastos de Polizas:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showCuentasgastospolizas" data-id="Cuentasgastospolizas">Asignar Cuenta de Gastos</button>
										<div id="hideCuentasgastospolizas">
											<select name='Cuentasgastospolizas' id='Cuentasgastospolizas' class='s2'>
												<?php
													echo $optionsList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelCuentasgastospolizas" data-id="Cuentasgastospolizas">Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 col-sm-1">
						</div>
					</div>
				</section>
				<h4>Selecciona la cuenta afectable</h4>
				<section>
					<div class="row">
						<div class="col-md-12">
							<h5>
								<div class="checkbox">
									<label style="padding-left: 0 !important; margin-left: 20px;">
								    	<input type="checkbox" id="defaultimp" name="defaultimp" value="1" onclick="defaultimpuesto();" />
								    	Asignar cuenta IVA,IEPS por default
								  </label>
								</div>
							</h5>
						</div>
					</div>
					<div class="row" id="cuentaivaieps" style="display: none;">
						<div class="col-md-1 col-sm-1">
						</div>
						<div class="col-md-10 col-sm-10">
							<div class="row" id="calculaiva">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>IVA Acreditable Pendiente de pago:</label>
												<button type='button' class="btn btn-primary btnMenu" id="showIvapendientepago"  data-id="ivapendientepago">Asignar IVA A.P.P</button>
												<div id="hideivapendientepago">
													<select name='ivapendientepago' id='ivapendientepago' class='s2'>
														<?php
															echo $circulantelist;
														?>
													</select>
													<button type='button' class="btn btn-danger btnMenu" id="cancelivapendientepago" data-id="ivapendientepago">Cancelar</button>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>IVA Acreditable Pagado:</label>
												<button type='button' class="btn btn-primary btnMenu" id="showIvapagado"  data-id="ivapagado">Asignar IVA A.P.</button>
												<div id="hideivapagado">
													<select name='ivapagado' id='ivapagado' class='s2'>
														<?php
															echo $circulantelist;
														?>
													</select>
													<button type='button' class="btn btn-danger btnMenu" id="cancelivapagado" data-id="ivapagado">Cancelar</button>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>IVA Trasladado Pendiente de cobro:</label>
												<button type='button' class="btn btn-primary btnMenu" id="showIvapendientecobro"  data-id="ivapendientecobro">Asignar IVA T.P.C</button>
												<div id="hideivapendientecobro">
													<select name='ivapendientecobro' id='ivapendientecobro' class='s2'>
														<?php
															echo $circulantelist;
														?>
													</select>
													<button type='button' class="btn btn-danger btnMenu" id="cancelivapendientecobro" data-id="ivapendientecobro">Cancelar</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>IVA Trasladado Cobrado:</label>
												<button type='button' class="btn btn-primary btnMenu" id="showIvacobrado"  data-id="ivacobrado">Asignar IVA T.C.</button>
												<div id="hideivacobrado">
													<select name='ivacobrado' id='ivacobrado' class='s2'>
														<?php
															echo $circulantelist;
														?>
													</select>
													<button type='button' class="btn btn-danger btnMenu" id="cancelivacobrado" data-id="ivacobrado">Cancelar</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>					
						</div>
						<div class="col-md-1 col-sm-1">
						</div>
					</div>
					<div class="row" id="calculaieps" style="display:none">
						<div class="col-md-1 col-sm-1">
						</div>
						<div class="col-md-10 col-sm-1">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>IEPS Acreditable Pendiente de pago:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIepspendientepago"  data-id="iepspendientepago">Asignar IEPS A.P.P</button>
										<div id="hideiepspendientepago">
											<select name='iepspendientepago' id='iepspendientepago' class='s2'>
												<?php
													echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceliepspendientepago" data-id="iepspendientepago" >Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>IEPS Acreditable Pagado:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIepspagado"  data-id="iepspagado">Asignar IEPS A.P.</button>
										<div id="hideiepspagado">
											<select name='iepspagado' id='iepspagado' class='s2'>
												<?php
													echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceliepspagado" data-id="iepspagado" >Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>IEPS Trasladado Pendiente de cobro:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIepspendientecobro"  data-id="iepspendientecobro">Asignar IEPS T.P.C</button>
										<div id="hideiepspendientecobro">
											<select name='iepspendientecobro' id='iepspendientecobro' class='s2'>
												<?php
													echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceliepspendientecobro" data-id="iepspendientecobro" >Cancelar</button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>IEPS Trasladado Cobrado:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIepscobrado"  data-id="iepscobrado">Asignar IEPS T.C</button>
										<div id="hideiepscobrado">
											<select name='iepscobrado' id='iepscobrado' class='s2'>
												<?php
												echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceliepscobrado" data-id="iepscobrado" >Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 col-sm-1">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h5>
								<div class="checkbox">
									<label style="padding-left: 0 !important; margin-left: 20px;">
								    	<input type="checkbox" id="defaultiva" name="defaultiva" value="1" onclick="defaultivacuenta();" />
								    	Calcula IVA
								  </label>
								</div>
							</h5>
						</div>
					</div>
					<div class="row" id="nocalculaiva" style="display: none">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de Gasto:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIvagasto"  data-id="ivagasto">Asignar Cuenta de Gasto</button>
										<div id="hideivagasto">
											<select name='ivagasto' id='ivagasto' class='s2'>
												<?php
												echo $gastoList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelivagasto" data-id="ivagasto" >Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h5>
								<div class="checkbox">
									<label style="padding-left: 0 !important; margin-left: 20px;">
								    	<input type="checkbox" id="defaultieps" name="defaultieps" value="1" onclick="defaultiepscuenta();" />
								    	Calcula IEPS
								  </label>
								</div>
							</h5>
						</div>
					</div>
					<div class="row" id="nocalculaieps" style="display: none">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Cuenta de Gasto:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIepsgasto"  data-id="iepsgasto">Asignar Cuenta de Gasto</button>
										<div id="hideiepsgasto">
											<select name='iepsgasto' id='iepsgasto' class='s2'>
												<?php
													echo $gastoList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="canceliepsgasto" data-id="iepsgasto" >Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h5>
								<div class="checkbox">
									<label style="padding-left: 0 !important; margin-left: 20px;">
								    	<input type="checkbox" id="retencion" name="retencion" value="1" onclick="retenshowhide();"/>
								    	Asignar cuenta ISH y Retenciones por default
								  </label>
								</div>
							</h5>
						</div>
					</div>
					<div class="row" id="retencionhideshow" style="display: none;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>ISH:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIsh"  data-id="ish">Asignar ISH</button>
										<div id="hideish">
											<select name='ish' id='ish' class='s2'>
												<?php
													echo $gastoList;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelish" data-id="ish" >Cancelar</button>
										</div>
									</div>
								</div>
							</div>
							<h6>Selecciona cuenta para Retenciones</h6>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>IVA Retenido:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIvaretenido"  data-id="ivaretenido">Asignar Retencion</button>
										<div id="hideivaretenido">
											<select name='ivaretenido' id='ivaretenido' class='s2'>
												<?php
													echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelivaretenido" data-id="ivaretenido" >Cancelar</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>ISR:</label>
										<button type='button' class="btn btn-primary btnMenu" id="showIsrretenido"  data-id="isrretenido">Asignar Retencion</button>
										<div id="hideisrretenido">
											<select name='isrretenido' id='isrretenido' class='s2'>
												<?php
													echo $circulantelist;
												?>
											</select>
											<button type='button' class="btn btn-danger btnMenu" id="cancelisrretenido" data-id="isrretenido">Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<h4>Conceptos de nomina</h4>
				<section>
					<h5>Percepciones</h5>
					<div class="row fila-base">
						<div class="col-md-4">
							<div class="form-group">
								<select name="percepciones[]" id="percepciones" onchange="" class="">
									<?php echo $percepciones; ?>
								</select>
								<label id="name[]" id="name"></label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<select name="cuentapercepcion[]" id="cuentapercepcion"  onchange="validaPercep(this.value)" class="">
									<?php echo $listaCuentaDeduccionesPercepciones; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<button type='button' class="btn btn-danger btnMenu eliminar">Eliminar</button>
							</div>
						</div>
					</div>
					<div class="row filadefault">
						<div class="col-md-6">
							<div class="form-group">
								<label id="nombrep" name="nombrep[]"></label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label id="cuentap" name="cuentap[]"></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<input type="button" id="agregar" value="Agregar" class="btn btn-primary btnMenu"/>
							<input type="button" id="agregardefault" value="Agregar" style="display: none" class="btn btn-primary btnMenu"/>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					<section id="tabla">
					</section>
					<h5>Deducciones</h5>
					<div class="row fila-base2">
						<div class="col-md-4">
							<div class="form-group">
								<select name="deducciones[]" id="deducciones" onchange="" class="s2">
									<?php echo $deducciones; ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<select name="cuentadeducciones[]" id="cuentadeducciones" class="s2" onchange="validaDeducci(this.value)" >
									<?php echo $listaCuentaDeduccionesPercepciones; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<button type='button' class="btn btn-danger btnMenu eliminar2">Eliminar</button>
							</div>
						</div>
					</div>
					<div class="row filadefault2">
						<div class="col-md-6">
							<div class="form-group">
								<label id="nombred" name="nombred[]"></label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label id="cuentad" name="cuentad[]"></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<input type="button" id="agregar2" value="Agregar" class="btn btn-primary btnMenu"/>
							<input type="button" id="agregardefault2" value="Agregar" style="display: none" class="btn btn-primary btnMenu"/>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					<section id="tabla2">
					</section>
					<h5>Otros Pagos</h5>
					<div class="row fila-base3">
						<div class="col-md-4">
							<div class="form-group">
								<select name="otrospagos[]" id="otrospagos" onchange="" class="">
									<?php echo $otrospagos; ?>
								</select>
								<label id="name[]" id="name"></label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<select name="cuentaotrospagos[]" id="cuentaotrospagos"  onchange="validaotrospagos(this.value)" class="">
									<?php echo $listaCuentaDeduccionesPercepciones; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<button type='button' class="btn btn-danger btnMenu eliminar3">Eliminar</button>
							</div>
						</div>
					</div>
					<div class="row filadefault3">
						<div class="col-md-6">
							<div class="form-group">
								<label id="nombreo" name="nombreo[]"></label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label id="cuentao" name="cuentao[]"></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<input type="button" id="agregar3" value="Agregar" class="btn btn-primary btnMenu"/>
							<input type="button" id="agregardefault3" value="Agregar" style="display: none" class="btn btn-primary btnMenu"/>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					<section id="tabla3">
					</section>
				</section>
				<section>
					<div class="row">
						<div class="col-md-12">
							<h5>
								<div class="checkbox">
									<label style="padding-left: 0 !important; margin-left: 20px;">
								    	<input type="checkbox" id="defaultsueld" name="defaultsueld" value="1" onclick="defaultsueldo();" />
								    	Asignar cuenta Sueldo por Pagar
								  </label>
								</div>
							</h5>
						</div>
					</div>
					<div class="row" id="cuentasueldo"  style="display: none;">
						<div class="col-md-4">
							<div class="form-group">
								<label>Sueldo por Pagar:</label>
								<button type='button' class="btn btn-primary btnMenu" id="showSueldo"  data-id="sueldo">Asignar cuenta</button>
								<div id="hidesueldo">
									<select name='sueldo' id='sueldo' class='s2'>
										<?php echo $listaCuentaSueldo; ?>
									</select>
									<button type='button' class="btn btn-danger btnMenu" id="cancelsueldo" data-id="sueldo">Cancelar</button>
								</div>
							</div>
						</div>
					</div>
				</section>
				<div class="row" style="margin-bottom: 5em !important;">
					<div class="col-md-3 col-md-offset-9">
						<button type='submit' class="btn btn-primary btnMenu" name='save'  class="nminputbutton">Guardar</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>

<script>
var arrayPercepciones		= new Array(); 
var arrayPercepcionesBD		= new Array(); 

var arrayDeducciones		  	= new Array();
var arrayCuentaPercepcion	= new Array();
var arrayCuentaDeduccion  	= new Array();

var arrayCuentaOtros 	= new Array();


$(function(){
	$("#agregar").on('click', function(){
		$("#percepciones").select2('destroy');
		$("#cuentapercepcion").select2('destroy');
		$(".fila-base").clone().removeClass('fila-base').appendTo("#tabla");
		$('#percepciones').find('option').removeAttr("selected");
		$('#cuentapercepcion').find('option').removeAttr("selected");
		$("#percepciones,#cuentapercepcion").select2({'width':'300px'}); //});	
	});
	$("#agregardefault").on('click', function(){
		$(".filadefault").clone().removeClass('filadefault').appendTo("#tabla");
	})
	$("#agregardefault2").on('click', function(){
		$(".filadefault2").clone().removeClass('filadefault2').appendTo("#tabla2");
	})
	$("#agregardefault3").on('click', function(){
		$(".filadefault3").clone().removeClass('filadefault3').appendTo("#tabla3");
	})
	$(document).on("click",".eliminar",function(){
		var parent = $(this).parents().get(2);
		$(parent).remove();
	});
	
	$("#agregar2").on('click', function(){
		$("#deducciones").select2('destroy');
		$("#cuentadeducciones").select2('destroy');
		$(".fila-base2").clone().removeClass('fila-base2').appendTo("#tabla2");
		$('#deducciones').find('option').removeAttr("selected");
		$('#cuentadeducciones').find('option').removeAttr("selected");
		$("#deducciones,#cuentadeducciones").select2({'width':'300px'}); //});	
	});
	$(document).on("click",".eliminar2",function(){
		var parent = $(this).parents().get(2);
		$(parent).remove();
	});
	
	$("#agregar3").on('click', function(){
		$("#otrospagos").select2('destroy');
		$("#cuentaotrospagos").select2('destroy');
		$(".fila-base3").clone().removeClass('fila-base3').appendTo("#tabla3");
		$('#otrospagos').find('option').removeAttr("selected");
		$('#cuentaotrospagos').find('option').removeAttr("selected");
		$("#otrospagos,#cuentaotrospagos").select2({'width':'300px'}); //});	
	});
	$(document).on("click",".eliminar3",function(){
		var parent = $(this).parents().get(2);
		$(parent).remove();
	});
	
});   
function validaPercep(v){
	var cuentapercepcion = v;
	if (cuentapercepcion == $("#ivapendientepago").val() || cuentapercepcion == $("#ivapagado").val() || cuentapercepcion == $("#ivapendientecobro").val() || cuentapercepcion == $("#ivacobrado").val() || cuentapercepcion == $("#iepspendientepago").val() || cuentapercepcion == $("#iepspagado").val() || cuentapercepcion == $("#iepspendientecobro").val() || cuentapercepcion == $("#iepscobrado").val() || cuentapercepcion==$("#isrretenido").val() || cuentapercepcion==$("#ivaretenido").val() || cuentapercepcion == $("#iepsgasto").val() || cuentapercepcion == $("#ish").val() || cuentapercepcion == $("#ivagasto").val() ) {
		alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
		$('#cuentapercepcion > option[value="'+v+'"]').attr('selected', false);
		$("#cuentapercepcion").select2({'width':'300px'});
	}
	//$("#cuentapercehidden").val(v);
}
function validaDeducci(v){
	var cuentadeduccion = v;
	$("#cuentadeducciones").select2('destroy');
	if (cuentadeduccion == $("#ivapendientepago").val() || cuentadeduccion == $("#ivapagado").val() || cuentadeduccion == $("#ivapendientecobro").val() || cuentadeduccion == $("#ivacobrado").val() || cuentadeduccion == $("#iepspendientepago").val() || cuentadeduccion == $("#iepspagado").val() || cuentadeduccion == $("#iepspendientecobro").val() || cuentadeduccion == $("#iepscobrado").val() || cuentadeduccion==$("#isrretenido").val() || cuentadeduccion==$("#ivaretenido").val() || cuentadeduccion == $("#iepsgasto").val() || cuentadeduccion == $("#ivagasto").val() || cuentadeduccion == $("#ish").val() || cuentadeduccion == $("#sueldo").val()) {
		alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
		
		$('#cuentadeducciones > option[value="'+v+'"]').attr('selected', false);
		//$("#cuentadeducciones").select2('destroy');
		$("#deducciones,#cuentadeducciones").select2({'width':'300px'});
	}
	
}
function validaotrospagos(v){
	var cuentaotros = v;
	$("#cuentaotrospagos").select2('destroy');
	if (cuentaotrospagos == $("#ivapendientepago").val() || cuentaotrospagos == $("#ivapagado").val() || cuentaotrospagos == $("#ivapendientecobro").val() || cuentaotrospagos == $("#ivacobrado").val() || cuentaotrospagos == $("#iepspendientepago").val() || cuentaotrospagos == $("#iepspagado").val() || cuentaotrospagos == $("#iepspendientecobro").val() || cuentaotrospagos == $("#iepscobrado").val() || cuentaotrospagos==$("#isrretenido").val() || cuentaotrospagos==$("#ivaretenido").val() || cuentaotrospagos == $("#iepsgasto").val() || cuentaotrospagos == $("#ivagasto").val() || cuentaotrospagos == $("#ish").val() || cuentaotrospagos == $("#sueldo").val()) {
		alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
		
		$('#cuentaotrospagos > option[value="'+v+'"]').attr('selected', false);
		//$("#cuentadeducciones").select2('destroy');
		$("#otrospagos,#cuentaotrospagos").select2({'width':'300px'});
	}
	
}

	$(document).ready(function(){
	
		// Inicia seccion de asignacion de valores iniciales
			/*jshint ignore:start*/
				<?php
					$datos  = ( $data['CuentaClientes'] 	 != -1) ? "$( '#clientes'     ).val( " . $data['CuentaClientes'] . ");" : "";
					$datos .= ( $data['CuentaVentas']  		 != -1) ? "$( '#ventas'       ).val( " . $data['CuentaVentas']   . ");" : "";
					$datos .= ( $data['CuentaIVA']  		 != -1) ? "$( '#IVA'          ).val( " . $data['CuentaIVA']      . ");" : "";
					$datos .= ( $data['CuentaCaja']  		 != -1) ? "$( '#caja'         ).val( " . $data['CuentaCaja']     . ");" : "";
					$datos .= ( $data['CuentaTR']  			 != -1) ? "$( '#TR'           ).val( " . $data['CuentaTR']     	 . ");" : "";
					$datos .= ( $data['CuentaBancos']  		 != -1) ? "$( '#bancos'       ).val( " . $data['CuentaBancos']   . ");" : "";
					$datos .= ( $data['CuentaCompras']  	 != -1) ? "$( '#compras'      ).val( " . $data['CuentaCompras']  . ");" : "";
					$datos .= ( $data['CuentaDev']  		 != -1) ? "$( '#devoluciones' ).val( " . $data['CuentaDev']      . ");" : "";
					$datos .= ( $data['CuentaSaldos']  		 != -1) ? "$( '#capital' 	  ).val( " . $data['CuentaSaldos']   . ");" : "";
					$datos .= ( $data['CuentaFlujoEfectivo'] != -1) ? "$( '#flujo'		  ).val( " . $data['CuentaFlujoEfectivo'] . ");" : "";
					$datos .= ( $data['CuentaProveedores']   != -1) ? "$( '#proveedores'  ).val( " . $data['CuentaProveedores']   . ");" : "";
					$datos .= ( $data['CuentaUtilidad']   != -1) ? "$( '#utilidad'  ).val( " . $data['CuentaUtilidad']   . ");" : "";
					$datos .= ( $data['CuentaPerdida']   != -1) ? "$( '#perdida'  ).val( " . $data['CuentaPerdida']   . ");" : "";
					$datos .= ( $data['CuentaIVAPendientePago']   != -1) ? "$( '#ivapendientepago'  ).val( " . $data['CuentaIVAPendientePago']   . ");" : "";
					$datos .= ( $data['CuentaIVApagado']   != -1) ? "$( '#ivapagado'  ).val( " . $data['CuentaIVApagado']   . ");" : "";
					$datos .= ( $data['CuentaIVAPendienteCobro']   != -1) ? "$( '#ivapendientecobro'  ).val( " . $data['CuentaIVAPendienteCobro']   . ");" : "";
					$datos .= ( $data['CuentaIVAcobrado']   != -1) ? "$( '#ivacobrado'  ).val( " . $data['CuentaIVAcobrado']   . ");" : "";
					$datos .= ( $data['CuentaIEPSPendientePago']   != -1) ? "$( '#iepspendientepago'  ).val( " . $data['CuentaIEPSPendientePago']   . ");" : "";
					$datos .= ( $data['CuentaIEPSpagado']   != -1) ? "$( '#iepspagado'  ).val( " . $data['CuentaIEPSpagado']   . ");" : "";
					$datos .= ( $data['CuentaIEPSPendienteCobro']   != -1) ? "$( '#iepspendientecobro'  ).val( " . $data['CuentaIEPSPendienteCobro']   . ");" : "";
					$datos .= ( $data['CuentaIEPScobrado']   != -1) ? "$( '#iepscobrado'  ).val( " . $data['CuentaIEPScobrado']   . ");" : "";
					$datos .= ( $data['CuentaDeudores']  != -1) ? "$( '#deudores'  ).val( " . $data['CuentaDeudores'] . ");" : "";
					$datos .= ( $data['CuentasGastosPolizas']  != -1) ? "$( '#Cuentasgastospolizas'  ).val( " . $data['CuentasGastosPolizas'] . ");" : "";
					$datos .= ( $data['ISH']   != -1) ? "$( '#ish'  ).val( " . $data['ISH']   . ");" : "";
					$datos .= ( $data['ISRretenido']   != -1) ? "$( '#isrretenido'  ).val( " . $data['ISRretenido']   . ");" : "";
					$datos .= ( $data['IVAretenido']   != -1) ? "$( '#ivaretenido'  ).val( " . $data['IVAretenido']   . ");" : "";
					$datos .= ( $data['CuentaIEPSgasto']   != -1) ? "$( '#iepsgasto'  ).val( " . $data['CuentaIEPSgasto']   . ");" : "";
					$datos .= ( $data['CuentaIVAgasto']   != -1) ? "$( '#ivagasto'  ).val( " . $data['CuentaIVAgasto']   . ");" : "";
					$datos .= (	$data['statusIVAIEPS']   != 0) ? "$( '#defaultimp'  ).prop('checked',true);$('#cuentaivaieps').show(); " : "";
					$datos .= (	$data['statusRetencionISH']   != 0) ? " $( '#retencion'  ).prop('checked',true);$('#retencionhideshow').show(); " : "";
					$datos .= ( $data['statusIEPS'] !=0 ) ? " $( '#defaultieps ').prop('checked',true); $('#calculaieps').show();$('#nocalculaieps').hide(); " : "";
					$datos .= ( $data['statusIEPS'] ==0 ) ? "  $( '#defaultieps ').prop('checked',false); $('#nocalculaieps').show(); $('#calculaieps').hide();" : "";
					$datos .= ( $data['statusIVA'] !=0 ) ? " $( '#defaultiva ').prop('checked',true); $('#calculaiva').show();$('#nocalculaiva').hide(); " : "";
					$datos .= ( $data['statusIVA'] ==0 ) ? "  $( '#defaultiva ').prop('checked',false); $('#nocalculaiva').show(); $('#calculaiva').hide();" : "";
					$datos .= ( $data['statuSueldoxPagar'] ==1 ) ? "  $( '#defaultsueld ').prop('checked',true); $('#cuentasueldo').show();" : "";
					$datos .= ( $data['CuentaSueldoxPagar']  != -1) ? "$( '#sueldo'  ).val( " . $data['CuentaSueldoxPagar'] . ");" : "";
					
			echo $datos;
			
					while ($p = $creadasPercepciones->fetch_assoc()){
						echo "
						arrayCuentaPercepcion.push(".$d['account_id'].");
							$('#percepciones > option[value=".$p['idAgrupador']."]').attr('disabled', 'disabled');
							$('#cuentapercepcion > option[value=".$p['account_id']."]').attr('disabled', 'disabled');
							$('#nombrep').html('".$p['descripcion']."');
							$('#cuentap').html('".$p['description']."');
							$('#agregardefault').click();
						";
					 }
					while ($d = $creadasDeducciones->fetch_assoc()){
						echo "
						arrayCuentaDeduccion.push(".$d['account_id'].");
						$('#deducciones > option[value=".$d['idAgrupador']."]').attr('disabled', 'disabled');
						$('#cuentadeducciones > option[value=".$d['account_id']."]').attr('disabled', 'disabled');
						$('#nombred').html('".$d['descripcion']."');
						$('#cuentad').html('".$d['description']."');
						$('#agregardefault2').click();
						";
				 	}
					while ($o = $creadasOtros->fetch_assoc()){
						echo "
						arrayCuentaOtros.push(".$o['account_id'].");
						$('#otrospagos > option[value=".$o['idAgrupador']."]').attr('disabled', 'disabled');
						$('#cuentaotrospagos > option[value=".$o['account_id']."]').attr('disabled', 'disabled');
						$('#nombreo').html('".$o['descripcion']."');
						$('#cuentao').html('".$o['description']."');
						$('#agregardefault3').click();
						";
				 	}
				?>
			/* jshint ignore:end*/

		// Termina seccion de asignacion de valores iniciales
<?php if(isset($idpercepcion)){?>
		$('#percepciones > option[value="<?php echo $idpercepcion['idAgrupador']?>"]').attr('selected', true);
		$("#agregar").click();

<?php }
	  if(isset($ideduccion)){?>
	  	$('#deducciones > option[value="<?php echo $ideduccion['idAgrupador']?>"]').attr('selected', true);
		$("#agregar2").click();
<?php  }
	  if(isset($idotros)){?>
	  	$('#otrospagos > option[value="<?php echo $idotros['idAgrupador']?>"]').attr('selected', true);
		$("#agregar3").click();
<?php  } ?>



		// Inicia seccion de control de botones de modificacion
		
			 $("select.s2").each(function(){
				if( parseInt( $(this).val(),10 ) !== -1 )
				{
					$(this).attr("disabled",'disabled').after("<input type='hidden' name='" + $(this).attr("name") + "' value='" + $(this).val() + "'>");
					//$(":button[data-id=" + $(this).attr("id") + "]").remove();
					$(":button[data-id=" + $(this).attr("id") + "]").hide();
				}
				else
				{
					$("#compras,#ventas,#devoluciones,#clientes,#IVA,#caja,#TR,#bancos,#capital,#flujo,#proveedores,#utilidad,#perdida,#ivapendientepago,#ivapagado,#ivapendientecobro,#ivacobrado,#iepspendientepago,#iepspagado,#iepspendientecobro,#iepscobrado,#deudores, #Cuentasgastospolizas, #ish, #isrretenido, #ivaretenido, #iepsgasto, #sueldo, #ivagasto").select2({"width":"300px"});
					$("#hide"+$(this).attr("id")).hide();
				}
			});
		// Termina seccion de control de botones de modificacion

		// Inicia seccion de boton de modificacion de cuentas
			$("#showCompras,#showVentas,#showDevoluciones,#showClientes,#showIva,#showCaja,#showTr,#showBancos,#showCapital,#showFlujo,#showProveedores,#showPerdida,#showUtilidad,#showUtilidad,#showIvapendientepago,#showIvapagado,#showIvapendientecobro,#showIvacobrado,#showIepspendientepago,#showIepspagado,#showIepspendientecobro,#showIepscobrado,#showDeudores,#showCuentasgastospolizas,#showIsh,#showIvaretenido,#showIsrretenido,#showIepsgasto,#showSueldo,#showIvagasto").click(function(){
				if(confirm("Esta seguro de asignar la cuenta de " + $(this).data("id") + "?.\nEsta accion no podra ser modificada en ningun momento."))
				{
					$("#hide" + $(this).data("id") ).show();
					$( "#" + $(this).data('id') + " option[value=-1]" ).prop("disabled",true);
					$( "#" + $(this).data('id') ).select2({"width": "300px"});
					$(this).hide();
				}
			});
		// Termina seccion de boton de modificacion de cuentas

		// Inicia seccion de cancelacion de modificacion de cuentas
			$("#cancelcompras,#cancelventas,#canceldev,#cancelclientes,#cancelIVA,#cancelcaja,#cancelTR,#cancelbancos,#cancelcap,#cancelflu,#cancelpro,#cancelutilidad,#cancelperdida,#cancelivapendientepago,#cancelivapagado,#cancelivapendientecobro,#cancelivacobrado,#canceliepspendientepago,#canceliepspagado,#canceliepspendientecobro,#canceliepscobrado,#canceldeudores,#cancelCuentasgastospolizas,#cancelish,#cancelisrretenido,#cancelivaretenido,#canceliepsgasto,#cancelsueldo,#cancelivagasto").click(function(){
				var type = ucFirst($(this).data("id"));
				console.log(type);
				$("#show" + type).show();
				$("#hide" + $(this).data("id") ).hide();
				$("#" + $(this).data('id') + " option[value=-1]").prop("disabled",false);
				$("#" + $(this).data('id') ).select2('destroy').select2({'width':'300px'});
				$('#' + $(this).data('id') ).val('-1');
			});
		// Termina seccion de cancelacion de modificacion de cuentas
						

		// Inicia Validacion de desigualdad
		
			$("#compras,#ventas,#devoluciones,#clientes,#IVA,#caja,#TR,#bancos,#capital,#flujo,#proveedores,#perdida,#utilidad,#ivapendientepago,#ivapagado,#ivapendientecobro,#ivacobrado,#iepspendientepago,#iepspagado,#iepspendientecobro,#iepscobrado,#deudores,#CuentasGastosPolizas,#ish,#isrretenido,#ivaretenido,#iepsgasto,#sueldo,#ivagasto").on('change',function(){
				//var compras = $("#compras").val();
				var compras = 0;
				var ventas  = $("#ventas").val();
				var IVA  = $("#IVA").val();
				var caja  = $("#caja").val();
//				var TR  = $("#TR").val();
				var TR  = 0;
				var bancos = $("#bancos").val();
				//var dev = $("#devoluciones").val();
				var dev = 0;
				var clientes = $("#clientes").val();
				var capital = $("#capital").val();
				//var flujo = $("#flujo").val();
				var flujo = 0;
				var proveedores = $('#proveedores').val();
				var utilidad = $('#utilidad').val();
				var perdida = $('#perdida').val();
				var ivapendientepago = $('#ivapendientepago').val();
				var ivapagado = $('#ivapagado').val();
				var ivapendientecobro = $('#ivapendientecobro').val();
				var ivacobrado = $('#ivacobrado').val();
				var iepspendientepago = $('#iepspendientepago').val();
				var iepspagado = $('#iepspagado').val();
				var iepspendientecobro = $('#iepspendientecobro').val();
				var iepscobrado = $('#iepscobrado').val();
				var deudores = $("#deudores").val();
				var cuentasGastosPolizas = $('#CuentasGastosPolizas').val();
				var ish = $('#ish').val();
				var isrretenido = $('#isrretenido').val();
				var ivaretenido = $('#ivaretenido').val();
				var iepsgasto = $('#iepsgasto').val();
				var ivagasto = $('#ivagasto').val();
				var sueldo = $('#sueldo').val();
// 				
				var id = $(this).attr('id');
				switch(id)
				{
					// en todas se quito la compracion con devoluciones || compras == devoluciones 
					case "compras":
						if(compras == ventas || compras == clientes || compras == IVA || compras == caja || compras == TR || compras == bancos || compras==proveedores || compras==utilidad || compras==perdida || compras==deudores || compras==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						} 
						break;
					case "ventas":
						if(ventas == compras  || ventas == clientes || ventas == IVA || ventas == caja || ventas == TR || ventas == bancos || ventas==proveedores || ventas==utilidad || ventas==perdida || ventas==deudores || ventas==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "IVA":
						if(IVA == compras || IVA == clientes  || IVA == ventas || IVA == caja || IVA == TR || IVA == bancos || IVA==proveedores || IVA==utilidad || IVA==perdida || IVA==deudores || IVA==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "devoluciones":
						if(devoluciones == ventas || devoluciones == compras || devoluciones == clientes || devoluciones == IVA || devoluciones == caja || devoluciones == TR || devoluciones == bancos || devoluciones==proveedores || devoluciones==utilidad || devoluciones==perdida || devoluciones==deudores || devoluciones==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "clientes":
						if(clientes == ventas || clientes == compras  || clientes == IVA || clientes == caja || clientes == TR || clientes == bancos || clientes==proveedores || clientes==utilidad || clientes==perdida || clientes==deudores || clientes==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;	

					case "caja":
						
						if(caja == ventas || caja == compras || caja == IVA || caja == TR || caja == bancos || caja == clientes || caja==proveedores || caja==utilidad || caja==perdida || caja==deudores || caja==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;	
						
					case "TR":
						if(TR == ventas || TR == compras || TR == IVA || TR == caja || TR == bancos || TR == clientes || TR==proveedores || TR==utilidad || TR==perdida || TR==deudores || TR==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;	
						
					case "bancos":
						if(bancos == ventas || bancos == compras || bancos == IVA || bancos == TR || bancos == caja || bancos == clientes || bancos==proveedores || bancos==utilidad || bancos==perdida || bancos==deudores || bancos==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;	
					case "proveedores":
						if(proveedores == ventas || proveedores == compras  || proveedores == IVA || proveedores == TR || proveedores == caja || proveedores == clientes || proveedores == bancos || proveedores==utilidad || proveedores==perdida || proveedores==deudores || proveedores==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "utilidad":
						if(utilidad == ventas || utilidad == compras  || utilidad == IVA || utilidad == TR || utilidad == caja || utilidad == clientes || utilidad == bancos || utilidad==proveedores || utilidad==perdida || utilidad==deudores || utilidad==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
					case "perdida":
				
						if(perdida == ventas || perdida == compras  || perdida == IVA || perdida == TR || perdida == caja || perdida == clientes || perdida == bancos || perdida==proveedores || perdida==utilidad || perdida==deudores || perdida==cuentasGastosPolizas)
						{
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						break;
						//AFECTABLES

					case "ivapendientepago":
				
					if (ivapendientepago == ivapagado || ivapendientepago == ivapendientecobro || ivapendientepago == ivacobrado || ivapendientepago == iepspendientepago || ivapendientepago == iepspagado || ivapendientepago == iepspendientecobro || ivapendientepago == iepscobrado || ivapendientepago==iepsgasto || ivapendientepago==sueldo || ivapendientepago==ivagasto) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivapendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ivapendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ivapendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
				
					case "ivapagado":
				
					if (ivapagado == ivapendientepago || ivapagado == ivapendientecobro || ivapagado == ivacobrado || ivapagado == iepspendientepago || ivapagado == iepspagado || ivapagado == iepspendientecobro || ivapagado == iepscobrado || ivapagado==iepsgasto || ivapagado==sueldo || ivapagado==ivagasto) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivapagado))>=0){//-1 es q no lo encuentra
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						else if(arrayCuentaOtros.indexOf(parseInt(ivapagado))>=0){//-1 es q no lo encuentra
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						else if(arrayCuentaPercepcion.indexOf(parseInt(ivapagado))>=0){//-1 es q no lo encuentra
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
				
					case "ivapendientecobro":
				
					if (ivapendientecobro == ivapendientepago || ivapendientecobro == ivapagado || ivapendientecobro == ivacobrado || ivapendientecobro == iepspendientepago || ivapendientecobro == iepspagado || ivapendientecobro == iepspendientecobro || ivapendientecobro == iepscobrado || ivapendientecobro == iepsgasto || ivapendientecobro == ivagasto || ivapendientecobro==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivapendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ivapendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ivapendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
				
					case "ivacobrado":
				
					if (ivacobrado == ivapendientepago || ivacobrado == ivapagado || ivacobrado == ivapendientecobro || ivacobrado == iepspendientepago || ivacobrado == iepspagado || ivacobrado == iepspendientecobro || ivacobrado == iepscobrado || ivacobrado == iepsgasto || ivacobrado == ivagasto || ivacobrado==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}
					else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivacobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ivacobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ivacobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
					break;
				
					case "iepspendientepago":
				
					if (iepspendientepago == ivapendientepago || iepspendientepago == ivapagado || iepspendientepago == ivapendientecobro || iepspendientepago == ivacobrado || iepspendientepago == iepspagado || iepspendientepago == iepspendientecobro || iepspendientepago == iepscobrado || iepspendientepago == iepsgasto || iepspendientepago == ivagasto || iepspendientepago==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}
					else{
						if(arrayCuentaDeduccion.indexOf(parseInt(iepspendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(iepspendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(iepspendientepago))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
					break;
				
					case "iepspagado":
				
					if (iepspagado == ivapendientepago || iepspagado == ivapagado || iepspagado == ivapendientecobro || iepspagado == ivacobrado || iepspagado == iepspendientepago || iepspagado == iepspendientecobro || iepspagado == iepscobrado || iepspagado == iepsgasto || iepspagado == ivagasto  || iepspagado==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(iepspagado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(iepspagado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(iepspagado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
				
					case "iepspendientecobro":
				
					if (iepspendientecobro == ivapendientepago || iepspendientecobro == ivapagado || iepspendientecobro == ivapendientecobro || iepspendientecobro == ivacobrado || iepspendientecobro == iepspendientepago || iepspendientecobro == iepspagado || iepspendientecobro == iepscobrado || iepspendientecobro == iepsgasto || iepspendientecobro == ivagasto || iepscobrado==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(iepspendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(iepspendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(iepspendientecobro))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
				
					case "iepscobrado":
				
					if (iepscobrado == ivapendientepago || iepscobrado == ivapagado || iepscobrado == ivapendientecobro || iepscobrado == ivacobrado || iepscobrado == iepspendientepago || iepscobrado == iepspagado || iepscobrado == iepspendientecobro || iepscobrado==deudores || iepscobrado == iepsgasto || iepscobrado == ivagasto || iepscobrado==sueldo) {
				
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();
				
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(iepscobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(iepscobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(iepscobrado))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
				
					break;
					case "deudores":
				
					if(deudores == ventas || deudores == compras  || deudores == IVA || deudores == TR || deudores == caja || deudores == clientes || deudores == bancos || deudores==proveedores || deudores==utilidad || deudores==perdida || deudores==sueldo)
					{
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
						$("#cancel" + id).click();
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(deudores))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(deudores))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(deudores))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
					break;
					case "cuentasGastosPolizas":
				
					if(cuentasGastosPolizas == ventas || cuentasGastosPolizas == compras  || cuentasGastosPolizas == IVA || cuentasGastosPolizas == TR || cuentasGastosPolizas == caja || cuentasGastosPolizas == clientes || cuentasGastosPolizas == bancos || cuentasGastosPolizas == proveedores || cuentasGastosPolizas == utilidad || cuentasGastosPolizas == perdida || cuentasGastosPolizas == sueldo || cuentasGastosPolizas == deudores)
					{
						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
						$("#cancel" + id).click();
					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(cuentasGastosPolizas))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(cuentasGastosPolizas))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(cuentasGastosPolizas))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
					break;
					case "ish":
// 				
					if (ish == ivapendientepago || ish == ivapagado || ish == ivapendientecobro || ish == ivacobrado || ish == iepspendientepago || ish == iepspagado || ish == iepspendientecobro || ish == iepscobrado || ish==isrretenido || ish==ivaretenido || ish == iepsgasto || ish==sueldo || ish==ivagasto) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ish))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ish))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ish))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
// 
					break;
					//RETENCION
					case "isrretenido":
				
					if (isrretenido == ivapendientepago || isrretenido == ivapagado || isrretenido == ivapendientecobro || isrretenido == ivacobrado || isrretenido == iepspendientepago || isrretenido == iepspagado || isrretenido == iepspendientecobro || isrretenido == iepscobrado || isrretenido==ivaretenido || isrretenido==ish || isrretenido == iepsgasto || isrretenido == ivagasto || isrretenido==sueldo) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(isrretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(isrretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(isrretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
// 
					break;
					case "ivaretenido":
				
					if (ivaretenido == ivapendientepago || ivaretenido == ivapagado || ivaretenido == ivapendientecobro || ivaretenido == ivacobrado || ivaretenido == iepspendientepago || ivaretenido == iepspagado || ivaretenido == iepspendientecobro || ivaretenido == iepscobrado || ivaretenido==isrretenido || ivaretenido==ish || ivaretenido == iepsgasto || ivaretenido == ivagasto || ivaretenido==sueldo) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivaretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ivaretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ivaretenido))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}

					break;
					case "iepsgasto":
					if (iepsgasto == ivapendientepago || iepsgasto == ivapagado || iepsgasto == ivapendientecobro || iepsgasto == ivacobrado || iepsgasto == iepspendientepago || iepsgasto == iepspagado || iepsgasto == iepspendientecobro || iepsgasto == iepscobrado || iepsgasto==isrretenido || iepsgasto==ish || iepsgasto == ivaretenido || iepsgasto==sueldo || iepsgasto==ivagasto) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(iepsgasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(iepsgasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(iepsgasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}
					break;
					case "ivagasto":
				
					if (ivagasto == ivapendientepago || ivagasto == ivapagado || ivagasto == ivapendientecobro || ivagasto == ivacobrado || ivagasto == iepspendientepago || ivagasto == iepspagado || ivagasto == iepspendientecobro || ivagasto == iepscobrado || ivagasto==isrretenido || ivagasto==ish || ivagasto == ivaretenido || ivagasto==sueldo || ivagasto==iepsgasto) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(ivagasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(ivagasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaPercepcion.indexOf(parseInt(ivagasto))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					}

					break;
					case "sueldo":
					if (sueldo == ivapendientepago || sueldo == ivapagado || sueldo == ivapendientecobro || sueldo == ivacobrado || sueldo == iepspendientepago || sueldo == iepspagado || sueldo == iepspendientecobro || sueldo == iepscobrado || sueldo==isrretenido || sueldo==ish || sueldo == ivaretenido || sueldo ==iepsgasto || sueldo ==ivagasto) {

						alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
				
						$("#cancel" + id).click();

					}else{
						if(arrayCuentaDeduccion.indexOf(parseInt(sueldo))>=0){//-1 es q no lo encuentra
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						if(arrayCuentaOtros.indexOf(parseInt(sueldo))>=0){//-1 es q no lo encuentra
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}if(arrayCuentaPercepcion.indexOf(parseInt(sueldo))>=0){
							alert("No debe Elegir cuentas repetidas para esta operacion. Cancelando...");
							$("#cancel" + id).click();
						}
						
					
					}

					break;
					
					
					
			}
	});
		// Termina Validacion de desigualdad	
	
	});
		function ucFirst(string) {
			return string.substring(0, 1).toUpperCase() + string.substring(1).toLowerCase();
		}

    function defaultimpuesto(){ 
    	if($('#defaultimp').is(':checked')){
				$("#cuentaivaieps").show();
			}else{
				if(confirm("Se perderán las cuentas asignadas desea continuar?")){
					$("#cuentaivaieps").hide();
					$(":hidden[name='ivapendientepago'],:hidden[name='ivapagado'],:hidden[name='ivapendientecobro'],:hidden[name='ivacobrado']").val("-1");
					$("#cancelivapendientepago,#cancelivapagado,#cancelivapendientecobro,#cancelivacobrado").click();
					$("#cancelivapendientepago,#cancelivapagado,#cancelivapendientecobro,#cancelivacobrado").show();
					$("#ivapendientepago,#ivapagado,#ivapendientecobro,#ivacobrado").prop("disabled",false);
				}
			}
    }
    function retenshowhide(){//pp
    	if($('#retencion').is(':checked')){
				$("#retencionhideshow").show();
			}else{
				if(confirm("Se perderán las cuentas asignadas desea continuar?")){
					$("#retencionhideshow").hide();
					$(":hidden[name='ish'],:hidden[name='ivaretenido'],:hidden[name='isrretenido']").val("-1");
					$("#cancelish,#cancelisrretenido,#cancelivaretenido").click();
					$("#cancelish,#cancelisrretenido,#cancelivaretenido").show();
					$("#ish,#isrretenido,#ivaretenido").prop("disabled",false);
				}
			}
    }
    function defaultiepscuenta(){ 
	    	if($('#defaultieps').is(':checked')){
	    		if(confirm("Se perderán la cuenta de gasto asignada desea continuar?")){
	    			$("#calculaieps").show();
	    			$("#nocalculaieps").hide();
	    			$(":hidden[name='iepsgasto']").val("-1");
	    			$("#canceliepsgasto").click();
				//$("#canceliepsgasto").show();
				$("#iepsgasto").prop("disabled",false);
	    		}
			
		}else{
			if(confirm("Se perderán las cuentas asignadas desea continuar?")){
				$("#calculaieps").hide();
				$(":hidden[name='iepspendientepago'],:hidden[name='iepspagado'],:hidden[name='iepspendientecobro'],:hidden[name='iepscobrado']").val("-1");
				$("#canceliepspendientepago,#canceliepspagado,#canceliepspendientecobro,#canceliepscobrado").click();
				$("#canceliepspendientepago,#canceliepspagado,#canceliepspendientecobro,#canceliepscobrado").show();
				$("#iepspendientepago,#iepspagado,#iepspendientecobro,#iepscobrado").prop("disabled",false);
				$("#nocalculaieps").show();
			}
		}
				
    }
    function defaultivacuenta(){ 
	    	if($('#defaultiva').is(':checked')){
	    		if(confirm("Se perderán la cuenta de gasto asignada desea continuar?")){
	    			$("#calculaiva").show();
	    			$("#nocalculaiva").hide();
	    			$(":hidden[name='ivagasto']").val("-1");
	    			$("#cancelivagasto").click();
				$("#ivagasto").prop("disabled",false);
	    		}
			
		}else{
			if(confirm("Se perderán las cuentas asignadas desea continuar?")){
				$("#calculaiva").hide();
				$(":hidden[name='ivapendientepago'],:hidden[name='ivapagado'],:hidden[name='ivapendientecobro'],:hidden[name='ivacobrado']").val("-1");
				$("#cancelivapendientepago,#cancelivapagado,#cancelivapendientecobro,#cancelivacobrado").click();
				$("#cancelivapendientepago,#cancelivapagado,#cancelivapendientecobro,#cancelivacobrado").show();
				$("#ivapendientepago,#ivapagado,#ivapendientecobro,#ivacobrado").prop("disabled",false);
				$("#nocalculaiva").show();
			}
		}
				
    }
    function defaultsueldo(){
    		if($('#defaultsueld').is(':checked')){
			$("#cuentasueldo").show();
		}else{
			if(confirm("Se perderá la cuenta asignada desea continuar?")){
				$("#cuentasueldo").hide();
				$(":hidden[name='sueldo']").val('-1');
				$("#cancelsueldo").click();
				$("#cancelsueldo").show();
				$("#sueldo").prop("disabled",false);
			}
		}
    }
function iracuenta(){
	window.parent.agregatab('../../modulos/cont/index.php?c=AccountsTree','Cuentas','',145);
}				
 
   
</script>