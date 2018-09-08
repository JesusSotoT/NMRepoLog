<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script>
	$(function(){
		obtener_selects();
		$('#ver_factura').hide();
		$('#facturaSelect').change(function(){
			var valor = $(this).val();
			if (valor != '-') {
				$('#ver_factura').show();
			} else {
				$('#ver_factura').hide();
			}
		});
		$('#subcuentade').select2();
		$('#oficial').select2();
	});
</script>
<style>
#capturaMovimiento > .modal-dialog {
 width: 80% !important;
}

#lista td
{
	width:146px;
	text-align: center;
	border:1px solid #BDBDBD;
}

#buscar
{
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
}

#cargando
{
	display:none;
	position:absolute;
	z-index:1;
}

#capturaMovimiento{
	background-color: unset;
    border: unset;
    height: unset;
    width: unset;
}

</style>

<div id="capturaMovimiento" class="modal fade" tabindex="-1" role="dialog" >
	<div class="modal-dialog">
  	<div class="modal-content">
  		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    		<h4 class="modal-title">Agregar Movimientos</h4>
  		</div>
  		<div class="modal-body" id="agregar_movimiento">
    		<input type='hidden' value='<?php echo $numPoliza['id']; ?>' name='idpoliza' id='idpoliza'>
    		<div class="row" style="margin-bottom: .23em">
					<div class="col-md-4">
						<label>Movimiento #:</label>
						<input class="form-control" type='text' class="nminputtext" name='movto' size='2' id='movto'>
					</div>
					<div class="col-md-4">
						<label>Asignar XML:</label>
						<label style="padding-left: 0 !important; margin-left: 20px !important;">
					    	<input type='checkbox' onclick='sel_multiple(this)' id='sel_multiple' value='1' style="margin: 0 !important;">
					    	Multiple
					  	</label>
					  	<select class="form-control" name='facturaSelect' id='facturaSelect' placeholder='Facturas'>
						</select>
					</div>
					<div class="col-md-4" style="padding-top: 1.75em;" id="ver_factura">
						<button class="btn btn-block btn-primary btn-sm" onclick="ver_factura();">
							<i class="glyphicon glyphicon-eye-open"></i> Ver factura
						</button>
					</div>
				</div>
				<div class="row" id="extra" style="display: none;">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">
								<label>Tipo Cambio:</label>
								<img src="images/intro.png" style="vertical-align:middle;" width="22px" height="22px" id="int" onclick="cambiaintro()" title="Introducir Tipo de Cambio"/>
								<img src="images/dine.jpeg" style="vertical-align:middle;display: none" width="22px" height="22px" id="int2" onclick="listadoin()" title="Seleccionar Tipo de Cambio"/>
								<input class="t2 form-control" type="text" id="tipocambio2" name="tipocambio2" placeholder="0.00" style="display: none" onkeyup=" tipoc(this.value);">
								<select id="tipocambio" class="t1" onchange="tipoc(this.value)">
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Cuenta:</label>
						<img src='images/cuentas.png' onclick='iracuenta()' title='Abrir Ventana de Cuentas' style='vertical-align:middle;'>
						<img src='images/reload.png' onclick='actualizaCuentas()' title='Actualizar Cuentas' style='vertical-align:middle;'>
						<i class="material-icons" onclick="mostrar_agregar_cuenta();" title="Agregar Cuenta"
						style="font-size:1.3em;vertical-align:middle; color:#96BE33;">add_circle</i>
						<div id='cargando-mensaje' style='font-size:8px;color:red;width:20px;'> Cargando...</div>
						<select name='cuenta' id='cuenta' class="" onclick="buscacuentaext(this.value)">
							<?php
							while($Cuentas = $Accounts->fetch_assoc())
							{
								echo "<option value='".$Cuentas['account_id']."'>".$Cuentas['description']."(".$Cuentas[$type_id_account].")</option>";
							}
							?>			
						</select>
					</div>
					<div class="col-md-4">
						<label>Referencia:</label> 
						<input type='text' class="form-control" name='referencia_mov' id='referencia_mov' maxlength='40' >
					</div>
					<div class="col-md-4">
						<label>Concepto:</label>
						<input type='text' class="form-control" name='concepto_mov' id='concepto_mov' maxlength='50' >
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Segmento de Negocio:</label> 
						<select name='segmento' id='segmento' class="">
							<?php
							while($LS = $ListaSegmentos->fetch_assoc())
							{
								echo "<option value='".$LS['idSuc']."'>".$LS['clave']." / ".$LS['nombre']."</option>";
							}
							?>
						</select>
					</div>
					<div class="col-md-4">
						<section id="muestraextca" style="display: none">
							<label>Cargo:</label> 
							<input type='text' class="form-control" name='cargo' id='cargoext' value='0.00' onChange='abonoscargosext(); aritmetica(this)'>
							<label id="carext"></label>
						</section>
						<label id="c">Cargo: $</label>
						<input type='text' class="form-control" name='cargo' id='cargo' value='0.00' onChange='abonoscargos(); aritmetica(this)'>
					</div>
					<div class="col-md-4">
						<section id="muestraextab" style="display: none">
							<label>Abono:</label> 
							<input type='text' class="form-control" name='abono' id='abonoext' value='0.00' onChange='abonoscargosext(); aritmetica(this)'>
							<label id="abext"></label>
						</section>
						<label id="a">Abono: $</label>
						<input type='text' class="form-control" name='abono' id='abono' value='0.00' onChange='abonoscargos(); aritmetica(this)'>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Sucursal:</label> 
						<select name='sucursal' id='sucursal' style='text-overflow: ellipsis;'  class="">
						<?php
							while($LS = $ListaSucursales->fetch_assoc())
							{
								echo "<option value='".$LS['idSuc']."'>".$LS['nombre']."</option>";
							}
							?>
						</select>
					</div>
				</div>
				<input type='hidden' id='nuevo'>
				<input type='hidden' id='cambio'>
				<img src="images/loading.gif" style="display: none" id="load" title="Cargando tipo de cambio">
				<div class="row">
    			<div class="col-md-12">
      			<div class="table-responsive">          
		  				<table class="table TablaAgregar">
		  					<tr>
								<td id='CargosAgregar'></td>
								<td id='AbonosAgregar'></td>
								<td style='cursor:pointer;' title='(alt + arriba) para subir el cuadre de la poliza' id='CuadreAgregar' onclick="cuadraPoliza()"></td>
							</tr>
		  				</table>
		  			</div>
		  		</div>
    		</div>
    		<div class="row" id='cargando'>
    			<div class="col-md-12">
      			<b style='color:#91C313;'>Guardando Datos...</b>
		  		</div>
    		</div>
  		</div>
  		<!-- Agregar cuenta -->
  		<div class="modal-body" id="agregar_cuenta" style="display:none;">
	  		<div class="row">
					<div class="col-md-12">
						<h4 id='titulo_captura'></h4>
						<input type='hidden' id='idcuenta' value='0'>
						<!-- Numero de cuenta y Nombre de cuenta -->
						<div class="row">
							<!-- Numero de cuenta -->
							<div class="col-sm-12 col-md-6">
								<label>Numero de la cuenta:</label>
								<input type="text" class="validate form-control" name="accountNumber" id="accountNumber">
							</div> <!-- // numero de cuenta -->
							<!-- Nombre de cuenta -->
							<div class="col-sm-12 col-md-6">
								<label>Nombre de la cuenta:</label>
								<input type='text' id='nombre_cuenta' class="validate form-control">
							</div> <!-- // Nombre de cuenta -->
						</div> <!-- // Numero de cuenta y Nombre de cuenta -->
						<br>
						<!-- Nombre en segundo idioma y Subcuenta -->
						<div class="row">
							<!-- Nombre en segundo idioma -->
							<div class="col-sm-12 col-md-6">
								<label>Nombre en segundo idioma:</label>
								<input type='text' id='nombre_cuenta_idioma' class="validate form-control">
							</div> <!-- // Nombre de cuenta -->
							<!-- Subcuenta de -->
							<div class="col-sm-12 col-md-6">
								<label id='sbcta'>Subcuenta de:</label><br>
								<select class="" id='subcuentade'></select>
								<label id='sbcta_label'></label>
								<input type='hidden' id='sbcta_hidden'>
							</div> <!-- // Subcuenta de -->
						</div> <!-- // Nombre en segundo idioma y Subcuenta -->
						<!-- Naturaleza y moneda -->
						<div class="row">
							<!-- Naturaleza -->
							<div class="col-sm-12 col-md-6">
								<label>Naturaleza:</label><br>
								<select class="form-control" id='nature'></select>
							</div> <!-- // Naturaleza -->
							<!-- Moneda -->
							<div class="col-sm-12 col-md-6">
								<label>Moneda:</label><br>
								<select class="form-control" id='coins'></select>
							</div> <!-- // Moneda -->	
						</div> <!-- // Naturaleza y Moneda -->
						<br>
						<!-- Clasificación, Digito agrupador y Estatus -->
						<!-- Clasificación -->
						<div class="row">
							<div class="col-sm-12 col-md-4">
								<label>Clasificación:</label><br>
								<select class="form-control" id='type'></select>
							</div> <!-- // Clasificación -->
							<!-- Digito -->
							<div class="col-sm-12 col-md-8">
								<label>Digito agrupador:</label><br>
								<select class="" id='oficial'>
									<option value='0'>Ninguna</option>
								</select>
							</div> <!-- // Digito -->
						</div> <!-- // Clasificación, Digito agrupador y Estatus -->
						<br>
						<div class="row">
							<!-- Estatus -->
							<div class="col-sm-12 col-md-4">
								<label>Estatus:</label><br>
								<select class="form-control" id='status'></select>
							</div> <!-- // Estatus -->
							<div class="col-sm-12" style="display: none;">
								<input type="hidden" id="tipo_instancia">
							</div>
						</div>
					</div>
	  		</div>
  		</div>
  		<div class="modal-footer">
  		<div class="row ">
  			<div class="col-md-12">
    			<button id="agregar_movimientos_btn" type="button" class="btn btn-primary btnMenu">Agregar movimiento</button>
  			</div>
  			<div class="col-md-6">
	    		<button id="agregar_cuenta_btn" type="button" onclick="guardar_cuenta();" class="btn btn-primary btnMenu" style="display: none;">Agregar cuenta</button>
  			</div>
  			<div class="col-md-6">
    			<button id="volver_movimientos" onclick="mostrar_agregar_movimientos();" class="btn btn-default btnMenu" style="display: none;">Volver</button>
  			</div>
  		</div>
  		</div>
  	</div>
	</div>
</div>
