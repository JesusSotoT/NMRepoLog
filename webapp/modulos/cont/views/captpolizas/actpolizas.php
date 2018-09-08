<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="js/mask.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
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

$numPoliza['id'] = $PolizaInfo['id'];
require("views/captpolizas/agregar.php");
require("views/captpolizas/proveedores.php");
require("views/captpolizas/causacion.php");
require("views/captpolizas/facturas.php");
require("views/captpolizas/copiarpoliza.php");
?>
<script>
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

});
</script>

<!--Fin de Captura Movimientos-->
<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
<img class="nmwaicons" src="images/copiar.png" border="0" title='Copiar Poliza' id="copiapoli" onclick="copiarPoliza();">

<form name='newCompany' method='post' action='index.php?c=CaptPolizas&f=ActualizarPoliza&p=<?php echo $numPoliza['id']; ?>' onsubmit='return validaciones(this)' enctype='multipart/form-data'>
<input type="hidden" value="<?php echo $_REQUEST['bancos'];?>" id="bancos"/>
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
							<input type='text' class="form-control" name='NameExercise' id='NameExercise' size='50' readonly value='<?php echo $Ex['EjercicioActual']; ?>'>
						</div>
					</div>
				</div>
			</section>
			<section>
				<?php 
					$InicioEjercicio = explode("-",$Ex['InicioEjercicio']); 
					$FinEjercicio = explode("-",$Ex['FinEjercicio']); 
				?>
				<div class="row" style="background-color: #eee; font-size: 15px; margin: 0 !important;">
					<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label>Ejercicio Vigente: </label>
							del (<b><?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$Ex['EjercicioActual']; ?></b>) al (<b><?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$Ex['EjercicioActual']; ?></b>)
						</div>
					</div>
					<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
						<div class="form-group">
							<label>Periodo actual:</label> 
							<label id='PerAct'><?php echo $PolizaInfo['idperiodo']; ?></label>
							<input type='hidden' id='periodos' name='periodos' value='<?php echo $PolizaInfo['idperiodo'] ?>'> 
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
							<?php
								$fechaPoliza = explode("-",$PolizaInfo['fecha']);
							?>
							<label>Tipo de Poliza:</label>
							<select name='tipoPoliza' id='tipoPoliza' class="form-control" onchange="tipopoliza()">
								<?php
									while($LTP = $ListaTiposPolizas->fetch_assoc())
									{
										if($LTP['id'] === $PolizaInfo['idtipopoliza'])
										{
											$selected = "selected";
										}else
										{
											$selected ='';
										}
										echo "<option value='".$LTP['id']."' ".$selected." >".$LTP['titulo']."</option>";
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
							<input type='text' class="form-control" name='referencia' value='<?php echo $PolizaInfo['referencia']; ?>' maxlength='40' >
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Concepto:</label>
							<input type='text' class="form-control" name='concepto' id='concepto' value='<?php echo $PolizaInfo['concepto']; ?>' maxlength='50' >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Fecha:</label>
							<input type='text' name='fecha'  class="form-control" id='datepicker' value='<?php echo $fechaPoliza[2]."-".$fechaPoliza[1]."-".$fechaPoliza[0]; ?>'>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<a href='javascript:facturas()' style='font-weight:bold;color:black;' title='Ver Facturas' id='FacturasButton'>
								<img src='images/clip.png' style='vertical-align: middle;' width='40px' title="Asociar Facturas">Anexar Factura
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 ">
						<?php
							$readonly='';
						 	if(!$manualnumpol) 
							{
								$readonly = 'readonly';
							}
						?>
						<div class="form-group">
							<label># Poliza:</label>
							<?php
							$readonly='';
							 	if(!$manualnumpol) 
								{
									$readonly = 'readonly';
								}
							?>
					 		<input type='number' name='numpol' id='numpol' class="form-control" value='<?php echo $PolizaInfo['numpol'];?>' maxlength='6' <?php echo $readonly; ?>>
					 		<span id='numpolload' style='color:red;font-style:italic;font-size:10px;'>Cargando numero...</span>
					 		<input type='hidden' name='numtipo' id='numtipo' value='<?php echo $PolizaInfo['idtipopoliza']."-".$PolizaInfo['numpol'];?>' maxlength='6' <?php echo $readonly; ?>>
						</div>
					</div>
				</div>
				<div class="row" id="us" style="display: none">
					<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label>Usuario:</label>
							<select id="usuarios" name="usuarios">
								<?php
										while($user = $usuarios->fetch_assoc()){ 
											if($user['idempleado'] == $PolizaInfo['idUser']){ $selec = "selected"; }else{ $selec = ""; }?>
												<option value="<?php  echo $user['idempleado']?>"  <?php echo $selec; ?>><?php echo $user['usuario']?></option>
								<?php 	} ?>
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
			<section id="datospago">
				<h4>Datos del Pago</h4>
				<div class="row">
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>Forma de pago:</label>
							<select id="formapago" name="formapago">
								<option value="0">Elija una FormaPago</option>
							 	<?php 	while($f=$forma_pago->fetch_array()){ 
							 				if($f['idFormapago'] == $idformapago){?>
							 					<option value="<?php echo $f['idFormapago']; ?>" selected><?php echo mb_strtoupper($f['nombre'],'UTF-8')."(".$f['claveSat'].")";?></option>
							 	<?php		}else{ ?>
							 					<option value="<?php echo $f['idFormapago']; ?>"><?php echo mb_strtoupper($f['nombre'],'UTF-8')."(".$f['claveSat'].")";?></option>
							 	<?php 		}
										} 
								?>
							 </select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 display" id="contenedor-numero">
						<div class="form-group">
							<label>Numero:</label>
							<input type="text" size="20" class="form-control" id="numero"  name="numero" value="<?php echo $PolizaInfo['numero'];?>"/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 display ">
						<div class="form-group">
							<label>Banco Origen:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px" title="Agregar Cuenta Bancaria" onclick="mandacuentabancaria()" src="images/mas.png">
							<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actcuentasbancarias()" src="images/reload.png">
							<br>
							<select id="listabancoorigen" name="listabancoorigen" onchange="numerocuentorigen()"  >
								<option value="0">Seleccione Banco</option>
								<?php 
									while($b=$listacuentasbancarias->fetch_array()){ 
										if($b['idbancaria'] == $PolizaInfo['idCuentaBancariaOrigen']){?>
										<option value="<?php echo  $b['idbancaria']; ?>" selected><?php echo mb_strtoupper($b['nombre']." (".$b['description']."[".$b['manual_code']."])",'UTF-8'); ?> </option>
								<?php		}else{ ?>
											<option value="<?php echo  $b['idbancaria']; ?>"><?php echo mb_strtoupper($b['nombre']." (".$b['description']."[".$b['manual_code']."])",'UTF-8'); ?> </option>

								<?php       }
									} 
								?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 display ">
						<div class="form-group">
							<label>No. Cuenta Bancaria Origen/tarjeta:</label>
							<input type="text" id="numorigen" class="form-control" name="numorigen" value="<?php echo @$numeroorigen['cuenta'];?>" readonly/>
						</div>
					</div>
				</div>
				<div class="row display">
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<input type="checkbox" id="listaempleado"  onclick="listaEm()"/><b>Empleados / Beneficiario:</b>
							<input type="hidden" name="tipoBeneficiario" id="tipoBeneficiario" value="1"/>

							<?php 	if($PolizaInfo['tipoBeneficiario']!=2 && $PolizaInfo['tipoBeneficiario']!=6){ // el 2 esde empleado?>
										<img style="vertical-align:middle;" title="Agregar Beneficiario" onclick="irapadron()" src="images/cuentas.png">
										<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actualizaprove()" src="images/reload.png">
										<br>
										<select id="beneficiario" name="beneficiario"  onchange="cuentarbolbenefi();">
											<option value="0">Seleccione Beneficiario</option>
											<?php 
													while($b=$beneficiario->fetch_array()){
														if($b['idPrv'] == $PolizaInfo['beneficiario']){ ?>
															<option value="<?php echo  $b['idPrv']; ?>" selected><?php echo mb_strtoupper(($b['razon_social']),'UTF-8'); ?> </option>
											<?php } else { ?>
															<option value="<?php echo  $b['idPrv']; ?>" ><?php echo mb_strtoupper(($b['razon_social']),'UTF-8'); ?> </option>
											<?php }
													}	 
											?>
										</select>
							<?php 	}elseif($PolizaInfo['tipoBeneficiario']==2){ ?>
										<script>$("#listaempleado").attr("checked",true);</script>
										<select  id="beneficiario" name="beneficiario"  onchange="cuentarbolbenefi();">
											<option value="0">Elija un Beneficiario</option>
											<?php 
													while($b=$empleados->fetch_array()){
														if($b['idEmpleado'] == $PolizaInfo['beneficiario']){ ?>
															<option value="<?php echo  $b['idEmpleado']; ?>" selected><?php echo  mb_strtoupper($b['nombreEmpleado']." ".$b['apellidoPaterno']." ".$b['apellidoMaterno'],'UTF-8'); ?> </option>
											<?php } else { ?>
															<option value="<?php echo  $b['idEmpleado']; ?>" ><?php echo mb_strtoupper($b['nombreEmpleado']." ".$b['apellidoPaterno']." ".$b['apellidoMaterno'],'UTF-8'); ?> </option>
											<?php }
													}	 
											?>
										</select>
							<?php	} elseif($PolizaInfo['tipoBeneficiario']==6){ ?>
										<select  id="beneficiario" name="beneficiario" >
											<option value="1" selected=""><?php echo $Ex['nombreorganizacion'];?></option>
										</select>
							<?php	} 
							?>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>RFC:</label>
							<input type="text" id="rfc" name="rfc" class="form-control" value="<?php echo mb_strtoupper($PolizaInfo['rfc'],'UTF-8');?>" readonly/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>Banco Destino:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px"  id="mandabanco" title="Agregar Bancos al Proveedor" onclick='mandabancos()'  src="images/mas.png">
							<br>
							<select id="listabanco" name="listabanco" onchange="numerocuent()" class="form-control" >
							<?php 
								while($b=$listabancos->fetch_array()){ 
									if($b['idbanco'] == $PolizaInfo['idbanco']){?>
										<option value="<?php echo  $b['idbanco']."/0"; ?>" selected><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
							<?php	}else{ ?>
										<option value="<?php echo  $b['idbanco']."/0"; ?>"><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
							<?php   }
								} 
							?>
						 </select>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 ">
						<div class="form-group">
							<label>No. Cuenta Bancaria Dest./tarjeta:</label>
							<img style="vertical-align:middle;width: 15px;height: 15px" title="Cargar numero" onclick='numerocuent()' src="images/reload.png">
							<input type="text" id="numtarje" class="form-control"  name="numtarje" value="<?php echo $PolizaInfo['numtarjcuent'];?>" readonly/>
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
							<button type='button' class="btn btn-primary btnMenu" id='botonProveedores' onClick='abreProveedoresLista(<?php echo $numPoliza['id']; ?>)' title=''>Relacionar Proveedores</button>
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
					<?php  
						if($relacion2!="no"){  
							if(!$relacion['id']){
								$id=0;
								$op="<option value=".$id." selected>--Ninguno--</option>";
								
							}else{
								$id=$relacion['id'];
								$op="";
								
							}
						}
					?>
					<div class="col-md-4 col-sm-6 ">
						<div class="form-group">
							<label><font color="red" face="Comic Sans MS,arial,verdana">Agregar relacion con provision:</font></label>
							<select class="form-control" id="relacionextra" name="relacionextra">
								<option value=0>Ninguna</option>
								<?php 
									if($relacion2!=0 && $relacion2!="no"){
								 		echo $relacion2;
									}
								?>
							</select>
						</div>
				  	</div>
				  	<div class="col-md-4 col-sm-4 ">
						<div class="form-group">
							<label><font color="red" face="Comic Sans MS,arial,verdana">Saldado:</font></label>
							<input type="checkbox" id="saldado" name="saldado" value="0" onclick="polizadiario()"/>
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
						<label id='c_div'><?php echo $PolizaInfo['fecha_creacion']." / ".$usuario_creacion; ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 ">
						<label>Última Modificación:</label>
						<label id='m_div'><?php echo $PolizaInfo['fecha_modificacion']." / ".$usuario_modificacion; ?></label>
					</div>
				</div>
			</section>
			<section style="margin-bottom: 4em !important;">
				<div class="row">
					<div class="col-md-10 col-sm-9 ">
					</div>
					<div class="col-md-2 col-sm-3 ">
						<button type='button' class="btn btn-primary btnMenu" title='ctrl+enter' id='actualizarboton2' onclick="antesdemandar()">Actualizar Poliza</button>
						<button type='submit' class="btn btn-primary btnMenu" id='actualizarboton' title='ctrl+enter' style="display: none">Actualizar Poliza</button>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-1 col-sm-1 " id="c2">
		</div>
	</div>
</div>
</form>

<?php
	if(isset($_REQUEST['im'])){ //1- ingresos, 2- egresos
		if($_REQUEST['im']==1){
?> 
			<script>
				if(confirm("Desea realizar el Desglose de IVA?")){
					$("#botonClientes").click();
				}else{
					setTimeout(function(){ window.print(); },5000);
				}
			</script>
<?php	}if($_REQUEST['im']==2){ ?>
			<script>
			

			var prv = <?php echo $_REQUEST['prv']; ?>;
			setTimeout(function (){ 
				if(confirm("Desea realizar la Relacion con Proveedores?")){
					$("#botonProveedores").click();
					$('#ProveedoresSelect option[value='+prv+']').attr("selected","")
					 abreProveedores(prv,0);
					 
					//	var stack = $.Callbacks();
					//stack.add(abreProveedores(prv,0));
					//setTimeout(function(){ modificaImpuestos(); },5000);
					var tiempo = setInterval(function(){ calImp() }, 2000);

					function calImp() {
					if($("#ivas").html()){
						if($("#importe").val()>0 && $("#importeAntesRetenciones").val()<=0){
							<?php
							    		$ProviderTaxDefault = $this->CaptPolizasModel->getProviderTaxDefault($_REQUEST['prv']);
							  		?>
							  		$("input[name=iva][value='<?php echo $ProviderTaxDefault['Valor'];?>']").click();
									clearInterval(tiempo);
						}
					}
					   
					}


				}else{
					window.print(); 
				}
			},5000);
			</script>
<?php	}
		if($_REQUEST['im']==3){ ?>
			<script>
			setTimeout(function(){ window.print(); },5000);
			</script>
<?php	}
	}
?>
<script>
	if($('#tipoPoliza').val()!=2){
		$('#pago').hide();
		$('#formap').hide();
		$('#pago').val(0);
		
	}else{
		$('#pago').show();
		$('#formap').show();
		//cuentarbolbenefi();
	}
	

if($('#tipoPoliza').val()!=3){
	$.post('ajax.php?c=Ajustecambiario&f=moviextranjeros2',{
		idejer:$("#IdExercise").val(),
		idpoliza:$('#idpoli').val(),
		idperido:$('#periodos').val()
		},function (resp){
			if(resp!="no"){
				
				$('#relacionextra').html(resp);
				$("#relacionn").show();
			}
		});
 	$.post('ajax.php?c=CaptPolizas&f=verificasaldado',
 		{idpoliza:$('#idpoli').val()}
 		,function(resp2){
 		  if(resp2==1){
 		  	$('#saldado').prop("checked", true);
 		  }
 		});
 }else{
 	//$("#relacionn").val(0);
 }
 $(document).ready(function(){

<?php
	if(isset($_REQUEST['bancos'])){ ?>
		$("#tipoPoliza option:not(:selected)").attr('disabled', true); 
		$("#usuarios option:not(:selected)").attr('disabled', true); 
		
		$("#concepto").attr("readonly",true);
		$("#datepicker").datepicker({minDate:-1,maxDate:-2}).attr('readonly','readonly');
<?php } ?>
if(parseInt($("#tipoPoliza").val()) == 1)
	$(".display").hide();
	$("#contenedor-numero").show();
if(parseInt($("#tipoPoliza").val()) > 2)
	$("#datospago").hide();
});
</script>
<?php if(isset($_SESSION['anticipo'])){?>
 		<script>
 		$("#us").show();
 		$("#botonProveedores").hide();
 		</script>
<?php } ?>
