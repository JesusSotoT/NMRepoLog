<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
	<link   rel="stylesheet" type="text/css" href="css/registroentradas.css"> 
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
	<script type="text/javascript" src="../../libraries/numeral.min.js"></script>
	<script type="text/javascript" src='js/calculo_ptu.js'></script>
	<link   rel="stylesheet" type="text/css" href="css/calculo_ptu.css">
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			ptuselec=$("#ptupru").val();
			$("#ptu").val(ptuselec);
		});
	</script>
	<body>
		<div class="container-fluid" class='encabezado' style="text-align:center;font-family: Courier;background-color:#F5F5F5;font-size: 25px;">
			<b>Cálculo del reparto de utilidades</b>
		</div>
		<br>
		<br>
		<div class="container well" style="width: 97%;">
			<form method="post" action="index.php?c=Sobrerecibo&f=calculoptuview" id="formfecha">
				<fieldset class="scheduler-border">
					<legend class="scheduler-border" align="center">
						<div style="border-radius: 5px;"><p  style="font-size: 16px" align="center">Calcule el monto a repartir entre los empleados</p></div>	
					</legend>
					<div class="col-md-12">
						<div class="col-md-4">
						</div>	
						<div class="col-md-4">
							<p>Cantidad a repartir entre los empleados</p>
							<input type="text" id="montoRepartir" class="form-control numbersOnly" name="montoRepartir" value ="<?php echo @$_REQUEST['montoRepartir'] ?>" align="left"  required />
							<br>
							<p>Existencia de acumulados para el calculo del PTU</p>
							<select id="ejercicio"  class="selectpicker btn-sm form-control" data-live-search="true" name="ejercicio"  class="form-control" />	
							<option value="2" <?php echo (($_POST['ejercicio']=="2")?"selected":"");?> >No existen acumulados del ejercicio anterior</option>
							<option value="1" <?php echo(($_POST['ejercicio']=="1")?"selected":"");?> >Si existen acumulados del ejercicio anterior</option>			
						</select> 
						<br>
						<br>
						<p>Concepto para ISR de PTU</p>
						<select  class="selectpicker" data-width="100%" data-live-search="true" id="ptu" name="ptu" >
							<option value="0">Seleccione</option>
							<?php
							while($concep = $deducciones->fetch_object() ){?>
							<option value="<?php echo $concep->idconcepto;?>"><?php echo $concep->concepto." ".$concep->descripcion; ?></option>
							<?php } ?>
						</select>
						<input type="hidden" name="ptupru" id="ptupru" value= <?php echo @$_REQUEST['ptu'];?>>		
						<br><br>
						<input type="checkbox" name="descontarincidencias" id="descontarincidencias" class="css-checkbox"/>
						<label for="descontarincidencias" class="css-label" style="font-size: 13px;font-weight: normal;font-size: 15px;">Descontar incidencias que estén asociadas al acumulado "Días de PTU que no participan"</label>
						<br><br><br><br>

						<div style="text-align: center">
							<input type="submit" class="btn btn-primary btn-sm" id="load" required="" 
							style="text-align:center;width: 120px;" data-loading-text="Consultando<i class='fa fa-refresh fa-spin'></i>" value="Procesar" /> 	
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" style="width: 120px;">Agregar conceptos</button>
						</div>	
					</div>
					<div class="col-md-4">
					</div>
				</fieldset> 	
			</form>
			<br>
			<div class="alert alert-info" cellspacing="0" width="100%">
				<table id="tablaptu" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead> 
						<tr style="background-color:#B4BFC1;color:#000000;">
							<th  style="font-weight: bold">No.(ID)</th>
							<th  style="font-weight: bold">Fecha ingreso</th>
							<th  style="font-weight: bold">Codigo Empleado</th>
							<th  style="font-weight: bold">Empleado</th>
							<th  style="font-weight: bold">Dias trabajados</th>
							<th  style="font-weight: bold">Salario Diario</th>
							<th  style="font-weight: bold">Salario Percibido</th>
							<th  style="font-weight: bold">Factor por dias trabajados</th>
							<th  style="font-weight: bold">Factor por salario percibido</th>
							<th  style="font-weight: bold">Subtotal PTU</th>
							<th  style="font-weight: bold">ISR Gravado</th>
							<th  style="font-weight: bold">ISR Mensual</th>
							<th  style="font-weight: bold">Total PTU</th>
						</tr>
					</thead>
					<tfoot align="right" style="background-color: white">
						<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
					</tfoot>
					<tbody>
						<?php
						if($calculoptuview){
							$encode = array();
							while($in = $calculoptuview->fetch_assoc()){
								$encode[] = $in;
								?>
								<tr>
									<td><?php echo $in['idEmpleado'];?></td>
									<td><?php echo $in['fechaAlta'];?></td>
									<td><?php echo $in['Codigo'];?></td>
									<td><?php echo $in['nombreEmpleado'].' '.$in['apellidoPaterno'].' '.$in['apellidoMaterno'];?></td>
									<td><?php echo $in['diasTrabajados'];?></td>
									<td><?php echo number_format($in['Salario'],2);?></td>
									<td><?php echo number_format($in['totalPercibido'],2);?></td>
									<td><?php echo number_format($in['factorDiasTrabajados'],2);?></td>
									<td><?php echo number_format($in['factorSalarioPercibido'],2);?></td>
									<td><?php echo number_format($in['subtotalPTU'],2);?></td>
									<td><?php echo number_format($in['isr_gravado'],2);?></td>
									<td><?php echo number_format($in['isr_mensual'],2);?></td>
									<td><?php echo number_format($in['totalPTU'],2);?></td>
								</tr>
								<?php  } 
							}?> 
						</tbody>
					</table>
					<br>
					<div style="text-align: right">
						<button type="button" class="btn btn-primary btn-md" id="guardarPTU" name="guardarPTU"  
						style="text-align:center;width: 120px;" data-loading-text="Guardando<i class='fa fa-refresh fa-spin'></i>">Guardar</button> 
						<?php  
						if($calculoptuview){ 
							echo "<script type='text/javascript'>
							datosCalculoPTU = '".json_encode($encode)."';
						</script>
						";
					}
					?> 
				</div>
			</div>
		</div>

		<!--Conceptos adicionales-->
		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" >&times;</button>
						<h4 class="modal-title">Conceptos adicionales a calcular </h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<table id="grilla" class="lista table table-bordered ">
										<thead>
											<tr class="alert alert-info">
												<th>Tipo</th>
												<th>Concepto</th>
												<th>Eliminar</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Ejemplo</td>
												<input type="text" name="conceptos[]" hidden>
												<td>Ejemplo</td>
												<input type="text" name="conceptos[]" hidden>
												<td><a class="elimina"><img src="images/borro.png" style="width: 22px;height: 20px;" /></a></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"><strong>Cantidad:</strong> <span id="span_cantidad">4</span> Conceptos.</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<form action="javascript: fn_agregar();" method="post" id="frm_usu">
								<table class="formulario" align="left"><br />
									<thead>
										<tr>
										</tr>
									</thead>
									<tbody>
										<td colspan="4">
											<p style="font-size: 15px;"><img src="images/add.png"/> Agregar registro a tabla</p>
										</td>
										<tr>
											<td>
												<div class="input-group mb-2 mr-sm-2 mb-sm-0">
													<select id="nombre" class="form-control selectpicker btn-sm" data-live-search="true" name="tipoperiodo">
														<option value="*" selected="selected" >Seleccione</option>
														<?php 
														while ($e = $conceptos->fetch_object()){
															$b = "";
															if(isset($datos)){ if($e->idtipo == $datos->idtipo){ $b="selected"; } }
															echo '<option value="'. $e->idtipo .'" '. $b .'>'. $e->tipo .'  </option>';}
															?>
														</select>
													</div>	
												</td>
												<td>	
													<div class="input-group mb-2 mr-sm-2 mb-sm-0">
														<select id="nominas" class="form-control selectpicker btn-sm" data-live-search="true" name="nominas">
															<option value="*">Seleccione</option>
															<?php 
															while ($e = $cargadeConceptos->fetch_object()){
																$b = ""; 
																if(isset($datos)){ if($e->idtipo == $datos->descripcion){ $b="selected"; } }
																echo '<option value="'. $e->idtipo .'" '.$b .'>'.'('.$e->descripcion.')'.'  '.$e->fechainicio.' '.$e->fechafin.'</option>';}?> 
															</select>
														</div></td>
														<td>
															<input name="agregar" type="submit" id="agregar" value="Agregar" class="btn btn-primary" />
														</td>
													</tr>
												</tbody>
												<tfoot>
												</tfoot>
											</table>
										</form>
									</div>
									<fieldset class="scheduler-border col-md-12">
										<legend class="scheduler-border">Calcular</legend>
										<div class="control-group">
											<div class="row">
												<div class="col-md-4">
													<div class="radio">
														<label><input type="radio" name="optradio">Todos los empleados del periodo.</label>
													</div>
												</div>
												<div class="col-md-8">
													<div class="radio">
														<label><input type="radio" name="optradio">Los que faltan de calcular.</label>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
									<div class="row">
										<div class="col-md-12">
											<div class="checkbox">
												<label><input type="checkbox" value="">Calcular ajuste al neto</label>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="col-md-5 col-md-offset-7">
										<button type="button" class="btn btn-primary">Aceptar</button>
										<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</body>
				</html>